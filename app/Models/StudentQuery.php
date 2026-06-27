<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentQuery extends Model
{
    protected $table = 'student_queries';

    protected $fillable = [
        'student_id',
        'category',
        'title',
        'description',
        'assigned_type',
        'assigned_staff_id',
        'assigned_cr_id',
        'status',
        'salary_cut_applied',
        'salary_cut_amount',
        'fine_applied',
        'fine_amount',
        'is_waived',
        'original_penalty_amount',
        'resolution_notes',
        'resolved_by_name',
        'resolved_by_role',
        'resolved_by_signature',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function assignedCr()
    {
        return $this->belongsTo(User::class, 'assigned_cr_id');
    }
}
