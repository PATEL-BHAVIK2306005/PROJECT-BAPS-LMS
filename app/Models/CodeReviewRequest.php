<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeReviewRequest extends Model
{
    protected $table = 'code_review_requests';
    protected $fillable = ['user_id', 'mentor_id', 'title', 'description', 'code_snippet', 'language', 'category', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Staff::class, 'mentor_id');
    }

    public function feedback()
    {
        return $this->hasOne(CodeReviewFeedback::class, 'request_id');
    }
}
