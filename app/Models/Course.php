<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 
        'description', 
        'instructor', 
        'duration', 
        'level', 
        'department_id', 
        'program', 
        'year', 
        'semester',
        'class_section',
        'faculty_id',
        'credits',
        'deadline',
        'password',
        'live_link',
        'live_time',
        'google_meet_link',
        'class_mode',
        'transcript_content',
        'approval_status'
    ];

    public function faculty()
    {
        return $this->belongsTo(Staff::class, 'faculty_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function allocations()
    {
        return $this->hasMany(CourseAllocation::class);
    }
}
