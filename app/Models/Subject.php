<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['course_id', 'name', 'code', 'type'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
