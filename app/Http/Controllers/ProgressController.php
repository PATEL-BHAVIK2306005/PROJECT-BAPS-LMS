<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use App\Models\Lesson;
use App\Models\Notification;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request)
    {
        $uid = auth()->check() ? auth()->id() : 1;
        
        $progress = Progress::updateOrCreate(
            ['user_id' => $uid, 'lesson_id' => $request->lesson_id],
            ['completed' => true]
        );

        // Award 10 XP (Tadka Feature)
        if ($progress->wasRecentlyCreated || $progress->wasChanged('completed')) {
            $user = \App\Models\User::find($uid);
            $user->increment('xp', 10);
            
            // Simple Level Up logic (every 100 XP)
            $newLevel = floor($user->xp / 100) + 1;
            if ($newLevel > $user->level) {
                $user->update(['level' => $newLevel]);
            }

            // Phase 2: 80% Completion Notification
            $lesson = \App\Models\Lesson::find($request->lesson_id);
            $course = $lesson->course;
            $totalLessons = $course->lessons()->count();
            $completedLessons = Progress::where('user_id', $uid)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->where('completed', true)
                ->count();

            if ($totalLessons > 0 && ($completedLessons / $totalLessons) >= 0.8) {
                $message = "Student " . ($user->enrollment_no ?? 'Unknown') . " has reached 80% completion in '{$course->title}'";
                
                // Notify Faculty
                if ($course->faculty_id) {
                    \App\Models\Notification::firstOrCreate([
                        'staff_id' => $course->faculty_id,
                        'type' => '80_percent_alert',
                        'message' => $message
                    ]);
                }

                // Notify Admins
                $admins = \App\Models\Staff::whereIn('role', ['admin', 'dean'])->get();
                foreach ($admins as $admin) {
                    \App\Models\Notification::firstOrCreate([
                        'staff_id' => $admin->id,
                        'type' => '80_percent_alert',
                        'message' => $message
                    ]);
                }
            }

            // Phase 2 & 3.8: 100% Completion Certificate
            if ($totalLessons > 0 && $completedLessons == $totalLessons) {
                // If the course has an active assessment, it blocks graduation until all quizzes are explicitly passed.
                $activeQuizzes = $course->quizzes()->where('is_active', true)->pluck('id');
                
                $canGraduate = true;
                if ($activeQuizzes->count() > 0) {
                    $passedQuizzes = \App\Models\QuizAttempt::where('user_id', $uid)
                                     ->whereIn('quiz_id', $activeQuizzes)
                                     ->where('passed', true)
                                     ->count();
                    $canGraduate = ($passedQuizzes >= $activeQuizzes->count());
                }

                if ($canGraduate) {
                    \App\Models\Certificate::firstOrCreate([
                        'user_id' => $uid,
                        'course_id' => $course->id
                    ], [
                        'unique_code' => 'BAPS-' . strtoupper(\Illuminate\Support\Str::random(10))
                    ]);
                }
            }
        }

        return response()->json(['status' => 'done', 'xp_gained' => 10]);
    }
}
