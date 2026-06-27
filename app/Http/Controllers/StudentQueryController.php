<?php

namespace App\Http\Controllers;

use App\Models\StudentQuery;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StudentQueryController extends Controller
{
    /**
     * Store a new student query.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|in:Schedule,Class Cancel,Fees Issue,LMS Issue,Other/Document Request',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_type' => 'required|string|in:staff,cr',
            'assigned_staff_id' => 'nullable|required_if:assigned_type,staff|exists:staff,id',
            'assigned_cr_id' => 'nullable|required_if:assigned_type,cr|exists:users,id',
        ]);

        $studentId = session('demo_user_id') ?? auth()->id() ?? 1;

        StudentQuery::create([
            'student_id' => $studentId,
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_type' => $request->assigned_type,
            'assigned_staff_id' => $request->assigned_type === 'staff' ? $request->assigned_staff_id : null,
            'assigned_cr_id' => $request->assigned_type === 'cr' ? $request->assigned_cr_id : null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Your student query ticket has been successfully created and assigned!');
    }

    /**
     * Update Faculty/Dean DND or Out of Station availability status.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:active,dnd,out_of_station',
        ]);

        $staffId = session('staff_id');
        if (!$staffId) {
            return redirect()->back()->with('error', 'Staff authentication session not found.');
        }

        $staff = Staff::findOrFail($staffId);
        $staff->status = $request->status;
        $staff->save();

        return redirect()->back()->with('success', 'Your availability status has been successfully updated to ' . strtoupper(str_replace('_', ' ', $request->status)) . '!');
    }

    /**
     * Resolve a student query ticket.
     */
    public function resolve(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:solved,unsolved',
            'resolution_notes' => 'required|string',
        ]);

        $query = StudentQuery::findOrFail($id);

        // Get resolving identity details
        $resolvedByName = 'Resolver';
        $resolvedByRole = 'Staff';
        $resolvedBySig = '';

        if (session('user_role') === 'cr') {
            $crId = session('demo_user_id') ?? auth()->id() ?? 1;
            $cr = User::find($crId);
            if ($cr) {
                $resolvedByName = $cr->name;
                $resolvedByRole = 'Class Representative';
                $resolvedBySig = $cr->digital_signature;
            }
        } else {
            $staffId = session('staff_id') ?? 1;
            $staff = Staff::find($staffId);
            if ($staff) {
                $resolvedByName = $staff->name;
                $resolvedByRole = ucfirst($staff->role);
                $resolvedBySig = $staff->digital_signature;
            }
        }

        $query->status = $request->status;
        $query->resolution_notes = $request->resolution_notes;
        $query->resolved_by_name = $resolvedByName;
        $query->resolved_by_role = $resolvedByRole;
        $query->resolved_by_signature = $resolvedBySig;

        // Handle penalties if unsolved
        if ($request->status === 'unsolved') {
            if ($query->assigned_type === 'staff') {
                $query->salary_cut_applied = true;
                $query->salary_cut_amount = 10000.00;
                $query->original_penalty_amount = 10000.00;
            } else {
                $query->fine_applied = true;
                $query->fine_amount = 100.00;
                $query->original_penalty_amount = 100.00;
            }
        } else {
            // If resolved, clear any penalty
            $query->salary_cut_applied = false;
            $query->salary_cut_amount = 0.00;
            $query->fine_applied = false;
            $query->fine_amount = 0.00;
        }

        $query->save();

        $statusText = $request->status === 'solved' ? 'solved (marked passing)' : 'UNSOLVED (penalties applied)';
        return redirect()->back()->with('success', "Query ticket has been marked as {$statusText}!");
    }

    /**
     * Waive or reduce fine/salary cut (Admin & HOD only).
     */
    public function waiveOrReduce(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|string|in:waive,reduce',
            'reduced_amount' => 'nullable|required_if:action,reduce|numeric|min:0',
        ]);

        $role = session('user_role');
        if (!in_array($role, ['admin', 'hod'])) {
            return redirect()->back()->with('error', 'Strictly Unauthorized. Only Admin and HOD roles can waive or reduce penalties.');
        }

        $query = StudentQuery::findOrFail($id);

        if ($request->action === 'waive') {
            $query->is_waived = true;
            if ($query->assigned_type === 'staff') {
                $query->salary_cut_amount = 0.00;
            } else {
                $query->fine_amount = 0.00;
            }
            $query->save();
            return redirect()->back()->with('success', 'Penalty has been successfully WAIVED to ₹0.00!');
        } else {
            $reduced = (float)$request->reduced_amount;
            $query->is_waived = false; // It is reduced, not fully waived
            if ($query->assigned_type === 'staff') {
                $query->salary_cut_amount = $reduced;
            } else {
                $query->fine_amount = $reduced;
            }
            $query->save();
            return redirect()->back()->with('success', 'Penalty has been successfully REDUCED to ₹' . number_format($reduced, 2) . '!');
        }
    }
}
