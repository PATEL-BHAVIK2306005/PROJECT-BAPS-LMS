<?php

namespace App\Http\Controllers;

use App\Models\TimeCapsule;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class TimeCapsuleController extends Controller
{
    public function index()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);
        
        $capsules = TimeCapsule::where('user_id', $userId)
            ->with('targetCourse')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Fetch student's enrolled courses to allow setting course lock triggers
        $enrolledCourseIds = Enrollment::where('user_id', $userId)
            ->where('status', 'approved')
            ->pluck('course_id');
        $courses = Course::whereIn('id', $enrolledCourseIds)->get();

        // Calculate stats
        $totalStaked = $capsules->where('status', 'locked')->sum('staked_xp');
        $totalEarned = 0;
        foreach ($capsules->where('status', 'unlocked') as $c) {
            $totalEarned += (int)($c->staked_xp * 2.5);
        }

        return view('student.time_capsules', compact('user', 'capsules', 'courses', 'totalStaked', 'totalEarned'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'secret_message' => 'required|string',
            'lock_type' => 'required|string|in:date,level,xp,course',
            'unlock_date' => 'nullable|required_if:lock_type,date|date|after:today',
            'target_level' => 'nullable|required_if:lock_type,level|integer|min:2',
            'target_xp' => 'nullable|required_if:lock_type,xp|integer|min:10',
            'target_course_id' => 'nullable|required_if:lock_type,course|exists:courses,id',
            'staked_xp' => 'required|integer|min:0',
        ]);

        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);

        // Verify XP stake
        $stakedXp = (int)$request->staked_xp;
        if ($stakedXp > 0 && $user->xp < $stakedXp) {
            return back()->with('error', 'You do not have enough XP to stake this amount! Staking: ' . $stakedXp . ' XP. Your balance: ' . $user->xp . ' XP.');
        }

        // Deduct XP
        if ($stakedXp > 0) {
            $user->xp -= $stakedXp;
            $user->save();
        }

        TimeCapsule::create([
            'user_id' => $userId,
            'title' => $request->title,
            'secret_message' => $request->secret_message,
            'lock_type' => $request->lock_type,
            'unlock_date' => $request->lock_type === 'date' ? $request->unlock_date : null,
            'target_level' => $request->lock_type === 'level' ? $request->target_level : null,
            'target_xp' => $request->lock_type === 'xp' ? $request->target_xp : null,
            'target_course_id' => $request->lock_type === 'course' ? $request->target_course_id : null,
            'staked_xp' => $stakedXp,
            'status' => 'locked',
        ]);

        return back()->with('success', 'Your Future-Self Time Capsule has been cryptographically locked! Focus on your goals to unlock it.');
    }

    public function unlock($id)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);
        $capsule = TimeCapsule::findOrFail($id);

        if ($capsule->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        if ($capsule->status !== 'locked') {
            return back()->with('info', 'This capsule is already unlocked.');
        }

        // Perform trigger validation
        $canUnlock = false;
        $reason = '';

        switch ($capsule->lock_type) {
            case 'date':
                $today = now()->format('Y-m-d');
                if ($today >= $capsule->unlock_date) {
                    $canUnlock = true;
                } else {
                    $reason = 'The unlock date has not arrived yet. Locked until: ' . \Carbon\Carbon::parse($capsule->unlock_date)->format('M d, Y') . '.';
                }
                break;

            case 'level':
                if ($user->level >= $capsule->target_level) {
                    $canUnlock = true;
                } else {
                    $reason = 'You have not reached the target level. Level required: ' . $capsule->target_level . ' (Current: ' . $user->level . ').';
                }
                break;

            case 'xp':
                if ($user->xp >= $capsule->target_xp) {
                    $canUnlock = true;
                } else {
                    $reason = 'You have not reached the target XP. XP required: ' . $capsule->target_xp . ' (Current: ' . $user->xp . ').';
                }
                break;

            case 'course':
                // Check if progress is 100% in enrollment
                $enrollment = Enrollment::where('user_id', $userId)
                    ->where('course_id', $capsule->target_course_id)
                    ->where('status', 'approved')
                    ->first();
                if ($enrollment && $enrollment->progress >= 100) {
                    $canUnlock = true;
                } else {
                    $courseName = $capsule->targetCourse->title ?? 'Course';
                    $progress = $enrollment ? $enrollment->progress : 0;
                    $reason = 'You must complete the course "' . $courseName . '" with 100% progress. (Current progress: ' . $progress . '%).';
                }
                break;
        }

        if (!$canUnlock) {
            return back()->with('error', 'Goal contract validation failed: ' . $reason);
        }

        // Unlock and reward
        $capsule->status = 'unlocked';
        $capsule->save();

        if ($capsule->staked_xp > 0) {
            $reward = (int)($capsule->staked_xp * 2.5);
            $user->xp += $reward;
            
            // Simple level-up checking: 1 level per 1000 XP
            $newLevel = floor($user->xp / 1000) + 1;
            if ($newLevel > $user->level) {
                $user->level = $newLevel;
            }
            $user->save();

            return back()->with('success_unlocked', [
                'message' => 'Congratulations! The goal has been attained and the Time Capsule has been opened. Decrypted message: "' . $capsule->secret_message . '"',
                'xp_reward' => $reward
            ]);
        }

        return back()->with('success_unlocked', [
            'message' => 'Congratulations! The goal has been attained and the Time Capsule has been opened. Decrypted message: "' . $capsule->secret_message . '"',
            'xp_reward' => 0
        ]);
    }

    public function destroy($id)
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::findOrFail($userId);
        $capsule = TimeCapsule::findOrFail($id);

        if ($capsule->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        if ($capsule->status === 'locked') {
            // Warn that deleting a locked capsule forfeits the staked XP
            $staked = $capsule->staked_xp;
            $capsule->delete();
            return back()->with('success', 'Locked capsule discarded. Staked ' . $staked . ' XP has been burned.');
        }

        $capsule->delete();
        return back()->with('success', 'Unlocked capsule entry removed from history.');
    }
}
