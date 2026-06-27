<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'subject_id', 'exam_title', 
        'internal_marks', 'practical_marks', 'external_marks_raw', 'external_marks_final', 
        'total_obtained', 'total_max', 'grade', 'remarks', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
