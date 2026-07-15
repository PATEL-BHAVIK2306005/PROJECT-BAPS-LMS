<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        $departments = Department::all();
        return view('auth.register', compact('departments'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'enrollment_no' => 'required|unique:users',
            'abc_card_id' => 'required',
            'phone' => 'required',
            'dob' => 'required|date',
            'gender' => 'required',
            'blood_group' => 'required',
            'aadhar_no' => 'required',
            'guardian_name' => 'required',
            'address' => 'required',
            'department_id' => 'required'
        ]);

        $status = 'pending';
        $loginCode = null;

        // If a CR, Class Coordinator, Admin, or HOD submits this
        // Give instant approval
        if (session('user_role') && in_array(session('user_role'), ['admin', 'cr', 'faculty', 'dean', 'hod'])) {
            $status = 'approved';
            $loginCode = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'enrollment_no' => $request->enrollment_no,
            'abc_card_id' => $request->abc_card_id,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'aadhar_no' => $request->aadhar_no,
            'guardian_name' => $request->guardian_name,
            'address' => $request->address,
            'status' => $status,
            'login_code' => $loginCode,
            'level' => 1,
            'xp' => 0,
            'department_id' => $request->department_id,
            'access_level' => 100
        ]);

        if ($status === 'approved') {
            return back()->with('success', "Student approved instantly. Their 5-Digit Login Code is: $loginCode");
        }

        return redirect('/register')->with('success', 'Registration submitted! Awaiting approval. You can track your status using your Email ID.');
    }

    public function trackApplication(Request $request)
    {
        $request->validate(['tracking_id' => 'required|email']);
        
        $user = User::where('email', $request->tracking_id)->first();
        if (!$user) {
            return back()->with('track_error', 'Invalid Email ID. No application found.');
        }

        return back()->with('tracked_user_id', $user->id)->with('track_status', ucfirst($user->status));
    }

    public function submitTc(Request $request)
    {
        $request->validate([
            'tracking_id' => 'required|email',
            'digital_signature' => 'required|string',
            'tc_accepted' => 'required|accepted'
        ]);

        $user = User::where('email', $request->tracking_id)->first();
        if (!$user) {
            return back()->with('track_error', 'Invalid Email ID.');
        }

        if ($user->application_stage == 3) {
            $user->tc_accepted = true;
            $user->digital_signature = $request->digital_signature;
            $user->application_stage = 4;
            $user->save();
            return back()->with('tracked_user_id', $user->id)->with('success', 'Terms & Conditions Accepted successfully. Awaiting Final Verification.');
        }

        return back()->with('tracked_user_id', $user->id)->with('track_error', 'Invalid stage for T&C submission.');
    }

    public function showLogin()
    {
        return view('auth.unified_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // Support both enrollment number and email
        $user = User::where('email', $request->email)
                    ->orWhere('enrollment_no', $request->email)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User identity not found in systems.']);
        }

        if ($user->status !== 'approved') {
            $msg = $user->status === 'pending'
                ? 'Your account is pending. Contact Your Department HOD for approval.'
                : 'Your account is suspended. Please contact the administrator.';
            return back()->withErrors(['email' => $msg]);
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Invalid Password.']);
        }

        Auth::login($user);
        
        // Configure vital session properties that the rest of the application depends on
        session(['user_role' => $user->role ?? 'student']);
        
        // If this user is a staff-level user (admin/dean/hod), try to mirror the AdminController session
        if (in_array($user->role, ['admin', 'dean', 'hod'])) {
            try {
                $staff = \App\Models\Staff::where('email', $user->email)->first();
                if ($staff) {
                    // Set the same session keys AdminController expects
                    session([
                        'user_role' => $staff->role ?? $user->role,
                        'staff_id' => $staff->id,
                        'staff_name' => $staff->name,
                        'dept_id' => $staff->department_id
                    ]);
                } else {
                    // Fallback: set minimal staff session using User's data
                    session([
                        'user_role' => $user->role,
                        'staff_id' => $user->id,
                        'staff_name' => $user->name,
                        'dept_id' => $user->department_id ?? null
                    ]);
                }

                // Redirect to admin area (user will have same session shape as AdminController after secure verify)
                return redirect('/admin');
            } catch (\Exception $e) {
                // On error, log and continue to normal dashboard
                \Illuminate\Support\Facades\Log::warning('Failed to mirror staff session after unified login: ' . $e->getMessage());
            }
        }
        
        if ($user->role === 'parent') {
            return redirect('/parent/dashboard');
        }
        
        return redirect('/dashboard');
    }
    public function forgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function submitForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'requested_password' => 'required|min:4'
        ]);

        \App\Models\PasswordApproval::create([
            'email' => $request->email,
            'requested_password' => $request->requested_password,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Your password reset request has been submitted to the Admin for approval.');
    }

    public function showParentRegister()
    {
        return view('auth.parent_register');
    }

    public function parentRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'student_enrollment' => 'required|string',
            'student_dob' => 'required|date',
        ]);

        // Find the student child
        $student = User::where('enrollment_no', $request->student_enrollment)
                       ->where('dob', $request->student_dob)
                       ->first();

        if (!$student) {
            return back()->withInput()->withErrors(['student_enrollment' => 'Verification failed: Student not found with these credentials. Please verify Child Enrollment No and DOB.']);
        }

        // Check parent slots (max 4)
        $parentSlotField = null;
        if (!$student->parent_1_id) {
            $parentSlotField = 'parent_1_id';
        } elseif (!$student->parent_2_id) {
            $parentSlotField = 'parent_2_id';
        } elseif (!$student->parent_3_id) {
            $parentSlotField = 'parent_3_id';
        } elseif (!$student->parent_4_id) {
            $parentSlotField = 'parent_4_id';
        }

        if (!$parentSlotField) {
            return back()->withInput()->withErrors(['student_enrollment' => 'Registration failed: This student already has the maximum of 4 linked parent/guardian profiles.']);
        }

        // Create parent user
        $parent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'parent',
            'parent_student_id' => $student->id,
            'status' => 'approved', // instantly approved
            'level' => 1,
            'xp' => 0,
            'access_level' => 60, // 60% access
        ]);

        // Link parent to child's slot
        $student->update([$parentSlotField => $parent->id]);

        return redirect('/login')->with('success', 'Parent account registered successfully. Please log in with your email & password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
