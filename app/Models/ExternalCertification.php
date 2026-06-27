<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalCertification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'platform',
        'title',
        'file_path',
        'credential_link',
        'issue_date',
        'verification_status',
        'verified_by',
        'admin_remarks',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
