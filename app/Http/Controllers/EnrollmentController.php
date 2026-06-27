<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enrollForm($courseId)
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/dashboard')->with('error', 'Unauthorized! Only Admin, Class Coordinator (CR), or Rajunakum Sir can perform enrollments.');
        }

        $course = Course::findOrFail($courseId);
        $departments = Department::all();
        return view('student.enroll', compact('course', 'departments'));
    }

    public function store(Request $request)
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/dashboard')->with('error', 'Unauthorized! Only Admin, Class Coordinator (CR), or Rajunakum Sir can perform enrollments.');
        }

        $request->validate([
            'course_id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'college' => 'required',
            'department' => 'required', // This will be the name now
            'roll_no' => 'required',
            'semester' => 'required',
            'address' => 'required'
        ]);

        Enrollment::create($request->all() + ['user_id' => 1]);

        return redirect('/courses/'.$request->course_id)->with('success', 'Enrolled successfully!');
    }
}
