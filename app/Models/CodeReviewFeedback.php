<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeReviewFeedback extends Model
{
    protected $table = 'code_review_feedbacks';
    protected $fillable = ['request_id', 'reviewer_id', 'rating', 'comments', 'signature_type', 'signature_data', 'badge_hash'];

    public function request()
    {
        return $this->belongsTo(CodeReviewRequest::class, 'request_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Staff::class, 'reviewer_id');
    }

    public function privilegeApplications()
    {
        return $this->hasMany(PrivilegeApplication::class, 'feedback_id');
    }
}
