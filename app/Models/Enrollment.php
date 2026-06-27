<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'user_id', 
        'course_id', 
        'name', 
        'email', 
        'phone', 
        'college', 
        'department', 
        'roll_no', 
        'semester', 
        'address', 
        'progress', 
        'program', 
        'year', 
        'class_section',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
