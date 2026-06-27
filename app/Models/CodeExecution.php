<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'code',
        'output',
        'stderr',
        'status_code',
        'api_key_used',
    ];
}
