<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsNotification extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'created_by_name',
        'created_by_role',
        'created_by_id'
    ];
}
