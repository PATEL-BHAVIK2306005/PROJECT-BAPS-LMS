<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseRating;

class CourseRatingController extends Controller
{
    public function store(Request $request, $courseId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        CourseRating::updateOrCreate(
            ['user_id' => 1, 'course_id' => $courseId],
            ['rating' => $request->rating, 'review' => $request->review]
        );

        return back()->with('success', 'Thank you for your review!');
    }
}
