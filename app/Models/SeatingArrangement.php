<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeatingArrangement extends Model
{
    protected $fillable = ['exam_schedule_id', 'room_no', 'capacity', 'student_ids'];

    protected $casts = [
        'student_ids' => 'array',
    ];

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }
}
