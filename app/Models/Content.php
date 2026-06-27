<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = ['course_id', 'title', 'type', 'file_path'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
