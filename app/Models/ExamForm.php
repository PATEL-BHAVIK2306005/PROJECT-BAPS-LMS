<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamForm extends Model
{
    protected $fillable = ['user_id', 'status', 'filled_by_name', 'selected_courses'];

    protected $casts = [
        'selected_courses' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
