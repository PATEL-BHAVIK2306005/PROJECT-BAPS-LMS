<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'day_of_week',
        'slot',
        'duration',
        'subject',
        'faculty',
        'room',
        'is_cancelled',
        'cancel_reason',
        'faculty_cancel_reason',
        'student_cancel_reason',
        'is_extra',
        'extra_reason'
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }
}
