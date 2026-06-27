<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gatepass;
use App\Models\Leave;
use App\Models\User;

class ServiceController extends Controller
{
public function hubPage()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);
        $gatepasses = Gatepass::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        $leaves = Leave::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        
        // Fetch Favorites via User relationship
        $favorites = $user->favorites()->with('faculty')->get();
        $feePayments = \App\Models\FeePayment::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
        
        // Student queries data
        $queries = \App\Models\StudentQuery::where('student_id', $userId)->with(['student.department', 'assignedStaff', 'assignedCr'])->orderBy('created_at', 'desc')->get();
        $faculties = \App\Models\Staff::whereIn('role', ['faculty', 'dean'])->orderBy('name')->get();
        $crs = \App\Models\User::where('role', 'cr')->orderBy('name')->get();

        return view('student.hub', compact('user', 'gatepasses', 'leaves', 'favorites', 'feePayments', 'queries', 'faculties', 'crs'));
    }

    public function submitGatepass(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $request->validate([
            'reason' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'out_time' => 'required|date',
            'in_time' => 'required|date|after:out_time',
        ]);

        Gatepass::create([
            'user_id' => $userId,
            'reason' => $request->reason,
            'destination' => $request->destination,
            'out_time' => $request->out_time,
            'in_time' => $request->in_time,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Gatepass Application Submitted! Please await approval.');
    }

    public function submitLeave(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        Leave::create([
            'user_id' => $userId,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Official Leave Request Submitted! Please await authorization.');
    }

    public function requestFeeToken(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);
        
        // Ensure no pending request exists
        $exists = \App\Models\FeePayment::where('user_id', $userId)->where('status', 'pending')->first();
        if ($exists) {
            return back()->with('error', 'You already have a pending fee payment token ('.$exists->token_number.'). Please complete it before requesting another.');
        }

        // Validate and update User
        $request->validate([
            'name' => 'required|string|max:255',
            'enrollment_no' => 'required|string',
            'phone' => 'required|string',
            'program' => 'required|string'
        ]);

        $user->update([
            'name' => $request->name,
            'enrollment_no' => $request->enrollment_no,
            'phone' => $request->phone,
            'program' => $request->program
        ]);

        $program = strtolower(trim($user->program));
        $amount = 1200; // default bachelors
        if (str_contains($program, 'master')) $amount = 3500;
        elseif (str_contains($program, 'phd')) $amount = 5000;

        $token = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        \App\Models\FeePayment::create([
            'user_id' => $userId,
            'amount' => $amount,
            'token_number' => $token,
            'status' => 'pending'
        ]);

        return back()->with('success', "Fee Payment Token [ $token ] generated! Amount required: ₹$amount. Please provide this to the Administrator/Coordinator to securely log your transaction.");
    }
}
