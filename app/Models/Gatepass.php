<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gatepass extends Model
{
    protected $fillable = [
        'user_id',
        'reason',
        'destination',
        'out_time',
        'in_time',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
