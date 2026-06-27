<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['staff_id', 'type', 'message', 'is_read'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
