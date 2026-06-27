<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    protected $fillable = [
        'title',
        'content',
        'category',
        'created_by_name',
        'created_by_role',
        'created_by_id',
        'signature_type',
        'manual_signature_name',
        'manual_signature_designation',
        'manual_signature_svg'
    ];
}
