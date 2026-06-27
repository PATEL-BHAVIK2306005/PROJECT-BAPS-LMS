<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\User;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::all();
        $students = [];
        $selectedCourse = null;
        $date = $request->date ?? date('Y-m-d');

        if ($request->has('course_id')) {
            $selectedCourse = Course::findOrFail($request->course_id);
            // Assuming enrollments table links users and courses
            $students = User::whereHas('enrollments', function($q) use ($request) {
                $q->where('course_id', $request->course_id);
            })->whereNull('role')->orWhere('role', 'student')->get();
        }

        return view('admin.attendance', compact('courses', 'students', 'selectedCourse', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $markedBy = session('user_id') ?? 1; // Fallback to 1

        if (auth()->check()) {
            $markedBy = auth()->id();
        }

        foreach ($request->attendance as $userId => $status) {
            Attendance::updateOrCreate(
                ['course_id' => $request->course_id, 'user_id' => $userId, 'date' => $request->date],
                ['status' => $status, 'marked_by' => $markedBy]
            );
        }

        return back()->with('success', 'Attendance marked successfully.');
    }
}
