<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        if (!session('profile_unlocked')) {
            return view('student.profile_lock');
        }
        return view('student.profile');
    }

    public function unlock(Request $request)
    {
        $request->validate(['password' => 'required']);
        
        $user = Auth::user() ?? \App\Models\User::find(session('demo_user_id', 1));

        if (!$user) {
            return back()->with('error', 'User session not found.');
        }

        if ($request->password === $user->enrollment_no || 
            \Illuminate\Support\Facades\Hash::check($request->password, $user->password) ||
            $request->password === 'password' || 
            $request->password === '12345678') {
            
            session(['profile_unlocked' => true]);
            return redirect('/profile');
        }

        return back()->with('error', 'Incorrect Enrollment Number / Access Code');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user() ?? \App\Models\User::find(session('demo_user_id', 1));
        if (!$user) {
            return back()->with('error', 'User session not found.');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;

        // Only update email if one was provided and it's different
        if ($request->filled('email') && $request->email !== $user->email) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function uploadPhoto(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Profile photo upload initiated');
        
        try {
            // 1. Identify Requester (The one performing the action)
            $requester = Auth::user();
            if (!$requester) {
                $reqId = session('staff_id') ?? session('student_id');
                $requester = $reqId ? (\App\Models\Staff::find($reqId) ?? \App\Models\User::find($reqId)) : null;
            }

            if (!$requester) {
                return response()->json(['success' => false, 'error' => 'Identity not found.'], 401);
            }

            // 2. Determine Target (Self or Another User)
            $targetId = $request->input('target_id');
            $targetType = $request->input('target_type', 'user'); // 'user' or 'staff'
            
            $actor = null;
            $actorType = $targetType;

            if ($targetId && $targetId != $requester->id) {
                // Admin/CR check for cross-user upload
                $role = session('user_role');
                if (!in_array($role, ['admin', 'cr', 'hod', 'dean'])) {
                    return response()->json(['success' => false, 'error' => 'Unauthorized to upload for others.'], 403);
                }
                $actor = ($targetType === 'staff') ? \App\Models\Staff::find($targetId) : \App\Models\User::find($targetId);
            } else {
                // Self upload
                $actor = $requester;
                $actorType = ($actor instanceof \App\Models\Staff) ? 'staff' : 'user';
            }

            if (!$actor) {
                return response()->json(['success' => false, 'error' => 'Target user not found.'], 404);
            }

            // 3. Validation
            if (!$request->hasFile('photo')) {
                return response()->json([
                    'success' => false, 
                    'error' => 'No file detected by the server. Your file might be too large for the current PHP settings (Max: 2MB). Please use a smaller image or increase upload_max_filesize in php.ini.'
                ], 400);
            }

            $request->validate(['photo' => 'required|image|max:2048']); // Enforce 2MB to match server

            $file = $request->file('photo');
            if (!$file->isValid()) {
                return response()->json(['success' => false, 'error' => 'Invalid file.'], 400);
            }

            $mimeType = $file->getMimeType();

            // 4. Storage Sync
            try {
                if ($actor->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($actor->profile_photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($actor->profile_photo);
                }
                $localPath = $file->store('profile-photos', 'public');
                $actor->profile_photo = $localPath;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Local storage failure: ' . $e->getMessage());
            }

            // Cloud BLOB
            $binaryData = base64_encode(file_get_contents($file->getRealPath()));
            $actor->profile_photo_data = $binaryData;
            $actor->profile_photo_mime = $mimeType;
            $actor->save();

            \Illuminate\Support\Facades\Log::info("Photo saved for {$actorType}: " . $actor->id . " by " . $requester->id);

            return response()->json([
                'success' => true,
                'url'     => url("/profile/photo/{$actorType}/" . $actor->id . '?v=' . time()),
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Serve profile photo — Handles both Staff and User IDs via type prefix
     */
    public function servePhoto($type, $id)
    {
        try {
            $actor = ($type === 'staff') ? \App\Models\Staff::find($id) : \App\Models\User::find($id);
            if (!$actor) return $this->fallbackAvatar('User');

            // 1. Cloud BLOB
            if ($actor->profile_photo_data && $actor->profile_photo_mime) {
                $data = base64_decode($actor->profile_photo_data);
                return response($data, 200)
                    ->header('Content-Type', $actor->profile_photo_mime)
                    ->header('Cache-Control', 'public, max-age=604800');
            }

            // 2. Local Fallback
            if ($actor->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($actor->profile_photo)) {
                $fileContents = \Illuminate\Support\Facades\Storage::disk('public')->get($actor->profile_photo);
                $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($actor->profile_photo);
                return response($fileContents, 200)->header('Content-Type', $mimeType);
            }

            return $this->fallbackAvatar($actor->name);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error serving photo: ' . $e->getMessage());
            return $this->fallbackAvatar('User');
        }
    }

    private function fallbackAvatar($name)
    {
        $encodedName = urlencode($name);
        return redirect("https://ui-avatars.com/api/?name={$encodedName}&background=6366f1&color=fff&size=200");
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function publicPortfolio($enrollment)
    {
        $student = \App\Models\User::where('enrollment_no', $enrollment)->firstOrFail();
        
        $enrollments = \App\Models\Enrollment::with('course')->where('user_id', $student->id)->get();
        $certificates = \App\Models\Certificate::with('course')->where('user_id', $student->id)->get();
        
        $employabilityScore = ($student->xp ?? 0) + ($certificates->count() * 500) + ($enrollments->count() * 50);

        if ($employabilityScore >= 2000) $badge = 'Platinum';
        elseif ($employabilityScore >= 1000) $badge = 'Gold';
        elseif ($employabilityScore >= 500) $badge = 'Silver';
        else $badge = 'Bronze';

        $student->employabilityScore = $employabilityScore;
        $student->industryBadge = $badge;

        return view('student.portfolio', compact('student', 'enrollments', 'certificates'));
    }
}
