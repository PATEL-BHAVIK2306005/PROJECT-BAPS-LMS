<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'course_id', 
        'subject_id', 
        'section', 
        'title', 
        'description', 
        'due_date', 
        'max_points', 
        'passing_marks', 
        'assignment_type'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
