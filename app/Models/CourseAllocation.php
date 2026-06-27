<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseAllocation extends Model
{
    protected $fillable = ['course_id', 'staff_id', 'class_section'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
