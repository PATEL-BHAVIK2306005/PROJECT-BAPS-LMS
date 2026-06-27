<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class ExamController extends Controller
{
    public function studentAdmitCard()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::with('department')->find($userId);

        if (!$user) {
            return redirect('/login')->with('error', 'Please login to view your admit card.');
        }

        $examForm = \App\Models\ExamForm::where('user_id', $userId)->first();
        $enrollments = Enrollment::where('user_id', $userId)->where('status', 'approved')->with('course')->get();

        $examSchedule = [];
        if ($examForm && $examForm->status == 'published') {
            $selectedCourseIds = $examForm->selected_courses ?? [];
            $approvedEnrollments = $enrollments->whereIn('course_id', $selectedCourseIds);
            
            $startDate = strtotime('next monday');
            foreach ($approvedEnrollments as $index => $enrollment) {
                if ($enrollment->course) {
                    $examSchedule[] = [
                        'course_code' => 'CS' . str_pad($enrollment->course->id, 3, '0', STR_PAD_LEFT),
                        'course_name' => $enrollment->course->title,
                        'date' => date('d M Y', strtotime("+" . ($index * 2) . " days", $startDate)),
                        'time' => '10:00 AM - 01:00 PM',
                        'room' => 'Hall ' . chr(65 + ($index % 5))
                    ];
                }
            }
        }
        
        $barcodeString = $user->enrollment_no ?? mt_rand(10000000, 99999999);
        $isAdminView = ($examForm && $examForm->status == 'published'); // Allow student to see card if published

        return view('student.admit_card', compact('user', 'examForm', 'enrollments', 'examSchedule', 'barcodeString', 'isAdminView'));
    }

    public function submitExamForm(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $request->validate(['course_ids' => 'required|array']);
        
        \App\Models\ExamForm::updateOrCreate(
            ['user_id' => $userId],
            [
                'status' => 'pending', 
                'filled_by_name' => 'Self', 
                'selected_courses' => $request->course_ids,
                'created_at' => now()
            ]
        );

        return back()->with('success', 'Exam Registration Form submitted successfully. Waiting for Admin approval.');
    }

    public function reCheckRequest(Request $request)
    {
        // For now, just a logic stub that returns success
        return back()->with('success', 'Your Re-Check request has been logged. The department will notify you after verification.');
    }

    public function duplicateRequest(Request $request)
    {
        // For now, just a logic stub that returns success
        return back()->with('success', 'Duplicate Marksheet request received. Please visit the administrative office for the physical copy.');
    }

    // Admin / Staff / CR functions
    public function adminForms()
    {
        if (!in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'faculty', 'coordinator', 'faculty-lecturer-coordinator']) && session('staff_name') != 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $forms = \App\Models\ExamForm::with('user.department')->get();
        // Load enrollments so admin can select courses for student
        $students = User::where('role', 'student')->whereNotIn('id', $forms->pluck('user_id'))->with('enrollments.course')->get();
        return view('admin.exam_forms', compact('forms', 'students'));
    }

    public function adminSubmitForm(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_ids' => 'required|array'
        ]);
        $staffName = session('staff_name') ?? 'Admin';

        \App\Models\ExamForm::firstOrCreate(
            ['user_id' => $request->student_id],
            ['status' => 'pending', 'filled_by_name' => 'Staff: ' . $staffName, 'selected_courses' => $request->course_ids]
        );
        return back()->with('success', 'Exam Form submitted for the student.');
    }

    public function publishAdmitCard($formId)
    {
        $form = \App\Models\ExamForm::findOrFail($formId);
        $form->update(['status' => 'published']);
        return back()->with('success', 'Admit Card Published Successfully.');
    }

    public function adminViewAdmitCard($userId)
    {
        $user = User::with('department')->findOrFail($userId);
        $form = \App\Models\ExamForm::where('user_id', $userId)->first();
        $selectedCourseIds = $form ? ($form->selected_courses ?? []) : [];

        $enrollments = Enrollment::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereIn('course_id', $selectedCourseIds)
            ->with('course')
            ->get();
        
        $examSchedule = [];
        $startDate = strtotime('next monday');
        foreach ($enrollments as $index => $enrollment) {
            if ($enrollment->course) {
                $examSchedule[] = [
                    'course_code' => 'CS' . str_pad($enrollment->course->id, 3, '0', STR_PAD_LEFT),
                    'course_name' => $enrollment->course->title,
                    'date' => date('d M Y', strtotime("+" . ($index * 2) . " days", $startDate)),
                    'time' => '10:00 AM - 01:00 PM',
                    'room' => 'Hall ' . chr(65 + ($index % 5))
                ];
            }
        }
        $barcodeString = $user->enrollment_no ?? mt_rand(10000000, 99999999);
        $isAdminView = true;
        
        return view('student.admit_card', compact('user', 'examSchedule', 'barcodeString', 'isAdminView'));
    }

    public function studentResults()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::with('department')->find($userId);

        if (!$user) {
            return redirect('/login')->with('error', 'Please login to view your results.');
        }

        $results = \App\Models\Result::with(['course'])->where('user_id', $userId)->get();

        if ($results->isEmpty()) {
            return back()->with('error', 'No results published yet for your account.');
        }

        // Calculate SGPA and backlogs
        $totalCredits = 0;
        $earnedPoints = 0;
        $currentBacklog = 0;
        $totalBacklog = 0;
        $failedSubjects = [];

        foreach ($results as $result) {
            $credits = $result->course->credits ?? 4;
            $totalCredits += $credits;

            $point = 0;
            switch ($result->grade) {
                case 'O':
                case 'A+': $point = 10; break;
                case 'A': $point = 9; break;
                case 'B+': $point = 8; break;
                case 'B': $point = 7; break;
                case 'C': $point = 6; break;
                case 'P':
                case 'D': $point = 5; break;
                case 'F': 
                    $point = 0; 
                    $currentBacklog++;
                    $totalBacklog++;
                    $failedSubjects[] = $result->course->title;
                    break;
            }
            $earnedPoints += ($point * $credits);
        }

        $sgpa = $totalCredits > 0 ? round($earnedPoints / $totalCredits, 2) : 0;
        $cgpa = $sgpa;
        
        if ($sgpa >= 9.1 && $currentBacklog == 0) $sgpa_grade = 'O+';
        elseif ($sgpa >= 8.7 && $currentBacklog == 0) $sgpa_grade = 'O';
        elseif ($sgpa >= 7.5 && $currentBacklog == 0) $sgpa_grade = 'A';
        elseif ($sgpa >= 6.0 && $currentBacklog == 0) $sgpa_grade = 'B';
        elseif ($sgpa >= 5.5 && $currentBacklog == 0) $sgpa_grade = 'C';
        elseif ($sgpa >= 4.5 && $currentBacklog == 0) $sgpa_grade = 'P';
        else $sgpa_grade = 'Fail';

        $status = $sgpa_grade === 'Fail' ? 'FAIL' : 'PASS';
        
        $customRemark = "";
        if ($status === 'FAIL') {
            $customRemark = "Need Improvement and You have Lost Your performance In " . implode(', ', $failedSubjects);
        }

        $examTitle = $results->first()->exam_title ?? 'University Examination 2026';

        return view('admin.print_gradesheet', compact('user', 'results', 'sgpa', 'cgpa', 'status', 'currentBacklog', 'totalBacklog', 'examTitle', 'sgpa_grade', 'customRemark'));
    }

    public function studentExcellenceCert()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);
        return view('admin.print_excellence_certificate', compact('user'));
    }
}
