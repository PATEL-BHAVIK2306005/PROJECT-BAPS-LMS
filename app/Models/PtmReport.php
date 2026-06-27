<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PtmReport extends Model
{
    protected $table = 'ptm_reports';

    protected $fillable = [
        'student_id',
        'created_by_role',
        'created_by_name',
        'academic_term',
        'category',
        'subject',
        'report_content',
        'parent_reply',
        'parent_replied_at',
    ];

    protected $casts = [
        'parent_replied_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
