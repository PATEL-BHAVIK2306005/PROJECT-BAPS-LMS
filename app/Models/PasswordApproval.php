<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordApproval extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'requested_password', 'status'];
}
