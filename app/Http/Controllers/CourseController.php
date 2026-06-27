<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = \App\Models\User::find($userId);

        $allCourses = Course::with('department')->get();
        $explicitEnrollments = \App\Models\Enrollment::where('user_id', $userId)->get();
        $enrollmentsMap = $explicitEnrollments->keyBy('course_id');

        $isStaff = in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'faculty']) || session('staff_name') == 'Rajunakum Sir';

        $curriculumCourses = [];
        $specialCourses = [];

        foreach ($allCourses as $c) {
            // Check if student has approved enrollment
            $hasApprovedEnrollment = isset($enrollmentsMap[$c->id]) && $enrollmentsMap[$c->id]->status === 'approved';

            // Check if matches student profile
            $matchesProfile = false;
            if ($user && $user->program && $user->semester) {
                $matchesProfile = (strtolower($c->program) === strtolower($user->program)) &&
                                  (intval($c->semester) === intval($user->semester)) &&
                                  (intval($c->year) === intval($user->year)) &&
                                  (empty($c->class_section) || strtolower($c->class_section) === strtolower($user->class_section));
            }

            if ($isStaff || $matchesProfile || $hasApprovedEnrollment) {
                $curriculumCourses[] = $c;
                if ($isStaff) {
                    $pseudoEnr = new \App\Models\Enrollment(['status' => 'approved', 'course_id' => $c->id]);
                    $enrollmentsMap->put($c->id, $pseudoEnr);
                }
            } else {
                $specialCourses[] = $c;
            }
        }

        $courses = collect($curriculumCourses);
        $specialCourses = collect($specialCourses);

        if ($request->has('search') && !empty($request->get('search'))) {
            $search = strtolower($request->get('search'));
            $filter = function($c) use ($search) {
                return str_contains(strtolower($c->title), $search) ||
                       str_contains(strtolower($c->description), $search) ||
                       str_contains(strtolower($c->instructor), $search);
            };
            $courses = $courses->filter($filter);
            $specialCourses = $specialCourses->filter($filter);
        }

        return view('student.courses', compact('courses', 'specialCourses', 'user', 'enrollmentsMap'));
    }

    public function enroll(Request $request, Course $course)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = \App\Models\User::find($userId);

        if ($user) {
            $enrollment = \App\Models\Enrollment::firstOrNew([
                'course_id' => $course->id,
                'user_id' => $user->id
            ]);

            if (!$enrollment->exists || $enrollment->status === 'rejected') {
                $enrollment->name = $request->input('name', $user->name);
                $enrollment->email = $request->input('email', $user->email);
                $enrollment->phone = $request->input('phone', $user->phone);
                $enrollment->roll_no = $request->input('roll_no', $user->enrollment_no);

                $enrollment->program = $course->program ?? 'Core';
                $enrollment->year = $course->year ?? 1;
                $enrollment->semester = $request->input('semester', $course->semester ?? 1);
                $enrollment->class_section = 'Self-Enrolled';
                $enrollment->department = $request->input('department', $course->department?->name ?? 'Core Menu');
                $enrollment->status = 'pending';
                $enrollment->save();

                return back()->with('success', 'Application Form Submitted! You will gain access once authorized by the Administration.');
            }

            return back()->with('info', 'Enrollment is already ' . $enrollment->status);
        }
        return back()->with('error', 'User validation failed!');
    }

    public function show(Course $course)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $enrollment = \App\Models\Enrollment::where('course_id', $course->id)->where('user_id', $userId)->first();
        
        $assignedFaculty = $course->faculty; // default fallback
        if ($enrollment && $enrollment->class_section) {
            $allocation = \App\Models\CourseAllocation::where('course_id', $course->id)
                ->where('class_section', $enrollment->class_section)
                ->with('staff')
                ->first();
            if ($allocation && $allocation->staff) {
                $assignedFaculty = $allocation->staff;
            }
        }

        $course->load('lessons.uploader', 'tasks');

        return view('student.course', compact('course', 'assignedFaculty', 'enrollment'));
    }

    public function toggleFavorite(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = \App\Models\User::find($userId);
        $courseId = $request->course_id;

        if ($user) {
            if ($user->favorites()->where('course_id', $courseId)->exists()) {
                $user->favorites()->detach($courseId);
                $status = 'detached';
            } else {
                $user->favorites()->attach($courseId);
                $status = 'attached';
            }
        } else {
            $status = 'error';
        }

        return response()->json(['status' => $status]);
    }

    public function dashboard()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = auth()->user() ?? \App\Models\User::find($userId);

        $isStaff = in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'faculty']) || session('staff_name') == 'Staff Member' || session('staff_name') == 'Rajunakum Sir';

        if ($isStaff) {
            $courses = Course::all();
            $enrolledCourseIds = Course::pluck('id');
        } else {
            // Get all approved enrollments
            $approvedCourseIds = \App\Models\Enrollment::where('user_id', $userId)
                ->where('status', 'approved')
                ->pluck('course_id')
                ->toArray();

            // Also get matching curriculum courses
            $courses = Course::all()->filter(function ($c) use ($user, $approvedCourseIds) {
                if (in_array($c->id, $approvedCourseIds)) {
                    return true;
                }
                if ($user && $user->program && $user->semester) {
                    return (strtolower($c->program) === strtolower($user->program)) &&
                           (intval($c->semester) === intval($user->semester)) &&
                           (intval($c->year) === intval($user->year)) &&
                           (empty($c->class_section) || strtolower($c->class_section) === strtolower($user->class_section));
                }
                return false;
            });
            $enrolledCourseIds = collect($approvedCourseIds);
        }

        $enrolledCount = $courses->count();
        $inProgress = \App\Models\Enrollment::where('user_id', $userId)->where('status', 'approved')->where('progress', '>', 0)->where('progress', '<', 100)->count();
        $completed = \App\Models\Enrollment::where('user_id', $userId)->where('status', 'approved')->where('progress', 100)->count();

        $personalNotes = \App\Models\PersonalNote::where('user_id', $userId)->latest()->get();

        $crQueries = collect();
        if ($user && $user->role === 'cr') {
            $crQueries = \App\Models\StudentQuery::where('assigned_cr_id', $user->id)
                ->with(['student.department', 'assignedStaff', 'assignedCr'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('student.dashboard', compact('courses', 'enrolledCount', 'inProgress', 'completed', 'user', 'personalNotes', 'crQueries'));
    }

    public function submitTask(Request $request, $taskId)
    {
        $request->validate(['file' => 'required|file|max:10240']); // 10MB max

        $path = $request->file('file')->store('submissions', 'public');

        \Illuminate\Support\Facades\DB::table('task_submissions')->insert([
            'task_id' => $taskId,
            'user_id' => auth()->check() ? auth()->id() : 1,
            'file_path' => $path,
            'grade' => null,
            'feedback' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Assignment submitted successfully!');
    }

    public function takeQuiz($courseId, $quizId)
    {
        $course = \App\Models\Course::findOrFail($courseId);
        $quiz = \App\Models\Quiz::with('questions.options')->findOrFail($quizId);
        return view('student.take_quiz', compact('course', 'quiz'));
    }

    public function submitQuiz(Request $request, $courseId, $quizId)
    {
        $quiz = \App\Models\Quiz::findOrFail($quizId);
        $answers = $request->input('answers', []);

        $score = 0;
        foreach ($answers as $questionId => $answerPayload) {
            $question = \App\Models\Question::find($questionId);
            if (!$question) continue;

            if ($question->question_type === 'mcq') {
                $option = \App\Models\Option::find($answerPayload);
                if ($option && $option->is_correct) {
                    $score += $question->points;
                }
            } elseif ($question->question_type === 'code') {
                // Auto-evaluate based on presence and loose text checking for now, 
                // as actual test cases weren't set up.
                $codeSubmission = trim((string)$answerPayload);
                if (!empty($codeSubmission)) {
                    // Temporarily award points for attempting the coding problem natively
                    $score += $question->points; 
                }
            } else {
                // Subjective questions (VSQ/Long) automatically awarded points for attempts right now
                if (!empty(trim((string)$answerPayload))) {
                    $score += $question->points;
                }
            }
        }

        $passed = $score >= $quiz->min_score;
        $uid = auth()->check() ? auth()->id() : 1; // Fallback for testing environments

        \App\Models\QuizAttempt::updateOrCreate(
            ['quiz_id' => $quizId, 'user_id' => $uid],
            ['score' => $score, 'passed' => $passed]
        );

        if ($passed) {
            // Check if they have watched all lessons before awarding the master certificate
            $course = \App\Models\Course::findOrFail($courseId);
            $totalLessons = $course->lessons()->count();
            $completedLessons = \App\Models\Progress::where('user_id', $uid)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->where('completed', true)
                ->count();

            if ($totalLessons == 0 || $completedLessons == $totalLessons) {
                // Ensure ALL active quizzes for this course are passed
                $activeQuizzes = $course->quizzes()->where('is_active', true)->pluck('id');
                $passedQuizzes = \App\Models\QuizAttempt::where('user_id', $uid)
                    ->whereIn('quiz_id', $activeQuizzes)
                    ->where('passed', true)
                    ->count();

                if ($passedQuizzes >= $activeQuizzes->count()) {
                    \App\Models\Certificate::firstOrCreate(
                        ['user_id' => $uid, 'course_id' => $courseId],
                        ['unique_code' => 'BAPS-' . strtoupper(\Illuminate\Support\Str::random(10))]
                    );
                    $msg = "Assessment Complete! You scored $score points. You PASSED! Your Master Course Certificate has been unlocked!";
                    return redirect('/courses/' . $courseId)->with('success', $msg);
                }
            }
        }

        $msg = "Assessment Complete! You scored $score points. ";
        $msg .= $passed ? "You PASSED!" : "You failed to map the required thresholds.";

        return redirect('/courses/' . $courseId)->with($passed ? 'success' : 'error', $msg);
    }

    public function assignDeputyCr(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = \App\Models\User::find($userId);

        // Verify that the logged-in user is a CR or HOD
        $role = session('user_role');
        if (!in_array($role, ['cr', 'hod', 'admin', 'dean'])) {
            return back()->with('error', 'Unauthorized! Only a Class Representative or HOD can assign a Deputy CR.');
        }

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'tc_accepted' => 'required|accepted'
        ]);

        // Find the student
        $student = \App\Models\User::findOrFail($request->student_id);

        // Ensure the student belongs to the same department
        if ($student->department_id != $user->department_id && $role !== 'admin' && $role !== 'dean' && $role !== 'hod') {
            return back()->with('error', 'Invalid Student! You can only appoint a student from your own department.');
        }

        // Revoke any existing deputy CR in this department first
        \App\Models\User::where('department_id', $user->department_id)
            ->where('role', 'deputy-cr')
            ->update(['role' => 'student', 'access_level' => 100]);

        // Promote new student to deputy-cr with access_level = 110
        $student->role = 'deputy-cr';
        $student->access_level = 110;
        $student->save();

        return back()->with('success', "Student {$student->name} has been successfully appointed as Deputy Class Representative via signed Terms & Conditions.");
    }

    public function revokeDeputyCr(Request $request)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = \App\Models\User::find($userId);

        $role = session('user_role');
        if (!in_array($role, ['cr', 'hod', 'admin', 'dean'])) {
            return back()->with('error', 'Unauthorized! Only a Class Representative or HOD can revoke a Deputy CR.');
        }

        // Find deputy CR in this department
        $deputy = \App\Models\User::where('department_id', $user->department_id)
            ->where('role', 'deputy-cr')
            ->first();

        if ($deputy) {
            $deputy->role = 'student';
            $deputy->access_level = 100;
            $deputy->save();
            return back()->with('success', "Deputy CR status revoked for {$deputy->name}.");
        }

        return back()->with('info', 'No Deputy CR is currently assigned.');
    }
}
