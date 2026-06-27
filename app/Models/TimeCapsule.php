<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeCapsule extends Model
{
    protected $table = 'time_capsules';

    protected $fillable = [
        'user_id',
        'title',
        'secret_message',
        'lock_type',
        'unlock_date',
        'target_level',
        'target_xp',
        'target_course_id',
        'staked_xp',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function targetCourse()
    {
        return $this->belongsTo(Course::class, 'target_course_id');
    }
}
