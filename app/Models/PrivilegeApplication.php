<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivilegeApplication extends Model
{
    protected $table = 'student_privilege_applications';
    protected $fillable = ['user_id', 'feedback_id', 'privilege_type', 'justification', 'status', 'processed_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feedback()
    {
        return $this->belongsTo(CodeReviewFeedback::class, 'feedback_id');
    }

    public function processor()
    {
        return $this->belongsTo(Staff::class, 'processed_by');
    }
}
