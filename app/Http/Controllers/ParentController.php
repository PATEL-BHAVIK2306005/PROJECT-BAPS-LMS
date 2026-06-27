<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PtmReport;
use App\Models\Gatepass;
use App\Models\Leave;
use App\Models\StudentQuery;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function dashboard()
    {
        $parent = Auth::user();
        if (!$parent || $parent->role !== 'parent') {
            return redirect('/login')->with('error', 'Authentication required.');
        }

        $studentId = $parent->parent_student_id;
        if (!$studentId) {
            return redirect('/login')->with('error', 'No student is associated with your parent account.');
        }

        $student = User::with('department')->findOrFail($studentId);

        // Fetch Student Stats
        // 1. Enrolled Courses
        $enrollments = \App\Models\Enrollment::where('user_id', $student->id)->with('course')->get();

        // 2. Attendance Stats
        $attendances = \App\Models\Attendance::where('user_id', $student->id)->get();
        $totalClasses = $attendances->count();
        $presentClasses = $attendances->whereIn('status', ['present', 'late'])->count();
        $attendancePercentage = $totalClasses > 0 ? round(($presentClasses / $totalClasses) * 100, 2) : 0;

        // 3. Exam Grades
        $results = \App\Models\Result::where('user_id', $student->id)->with('course')->get();
        $admitCard = \App\Models\ExamForm::where('user_id', $student->id)->first();

        // 4. Gatepasses and Leaves
        $gatepasses = Gatepass::where('user_id', $student->id)->orderBy('created_at', 'desc')->get();
        $leaves = Leave::where('user_id', $student->id)->orderBy('created_at', 'desc')->get();

        // 5. PTM Reports
        $ptmReports = PtmReport::where('student_id', $student->id)->orderBy('created_at', 'desc')->get();

        // 6. Child Queries
        $queries = StudentQuery::where('student_id', $student->id)->orderBy('created_at', 'desc')->get();

        return view('parent.parent_dashboard', compact(
            'student', 'enrollments', 'totalClasses', 'presentClasses',
            'attendancePercentage', 'attendances', 'results', 'admitCard',
            'gatepasses', 'leaves', 'ptmReports', 'queries'
        ));
    }

    public function submitReply(Request $request, $id)
    {
        $parent = Auth::user();
        if (!$parent || $parent->role !== 'parent') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'reply' => 'required|string|max:1000'
        ]);

        $report = PtmReport::findOrFail($id);

        // Security check: Ensure the report belongs to the parent's child
        if ($report->student_id != $parent->parent_student_id) {
            return response()->json(['error' => 'Unauthorized to reply to this report.'], 403);
        }

        $report->update([
            'parent_reply' => $request->reply,
            'parent_replied_at' => now()
        ]);

        return back()->with('success', 'PTM Report reply submitted successfully!');
    }

    public function submitPtmReport(Request $request)
    {
        // Only Admin, Dean, HOD, CR, CC (Coordinator), Faculty, Exam Coordinator can submit reports
        $authorizedRoles = ['admin', 'dean', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator', 'faculty-lecturer-lab', 'moderator'];
        if (!in_array(session('user_role'), $authorizedRoles)) {
            return back()->with('error', 'Unauthorized to submit PTM Child Reports.');
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'category' => 'required|string|in:Academic,Behavior,Attendance,Exams',
            'subject' => 'required|string|max:255',
            'report_content' => 'required|string|max:2000',
        ]);

        $termFile = storage_path('app/custom_tabs.json'); // Fallback or retrieve term
        $academicTerm = localStorage_get('set_term', '2026 Even Semester'); 

        PtmReport::create([
            'student_id' => $request->student_id,
            'created_by_role' => session('user_role'),
            'created_by_name' => session('staff_name') ?? Auth::user()->name ?? 'Institutional Lead',
            'academic_term' => $academicTerm,
            'category' => $request->category,
            'subject' => $request->subject,
            'report_content' => $request->report_content,
        ]);

        return back()->with('success', 'PTM Child Report submitted successfully to the Parents!');
    }

    public function submitGatepass(Request $request)
    {
        $parent = Auth::user();
        $studentId = $parent->parent_student_id;
        if (!$studentId) {
            return back()->with('error', 'Associated child not found.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'out_time' => 'required|date',
            'in_time' => 'required|date|after:out_time',
        ]);

        Gatepass::create([
            'user_id' => $studentId,
            'reason' => $request->reason . ' (Submitted by Parent/Guardian)',
            'destination' => $request->destination,
            'out_time' => $request->out_time,
            'in_time' => $request->in_time,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Child Gatepass Request submitted successfully!');
    }

    public function submitLeave(Request $request)
    {
        $parent = Auth::user();
        $studentId = $parent->parent_student_id;
        if (!$studentId) {
            return back()->with('error', 'Associated child not found.');
        }

        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        Leave::create([
            'user_id' => $studentId,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason . ' (Submitted by Parent/Guardian)',
            'status' => 'pending'
        ]);

        return back()->with('success', 'Child Hostel Leave Request submitted successfully!');
    }

    public function submitQuery(Request $request)
    {
        $parent = Auth::user();
        $studentId = $parent->parent_student_id;
        if (!$studentId) {
            return back()->with('error', 'Associated child not found.');
        }

        $request->validate([
            'query_type' => 'required|string',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'assigned_role' => 'required|string',
        ]);

        StudentQuery::create([
            'student_id' => $studentId,
            'query_type' => $request->query_type,
            'subject' => $request->subject . ' (Parent Request)',
            'description' => $request->description,
            'assigned_role' => $request->assigned_role,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Support Ticket submitted to Department successfully!');
    }
}

// Helper to simulate localStorage on php side if needed
function localStorage_get($key, $default = null) {
    return $default;
}
