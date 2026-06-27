<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdcHackerrankSubmission extends Model
{
    protected $table = 'ipdc_hackerrank_submissions';
    protected $fillable = [
        'user_id',
        'problem_id',
        'code',
        'language',
        'status',
        'passed_test_cases',
        'total_test_cases'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function problem()
    {
        return $this->belongsTo(IpdcHackerrankProblem::class, 'problem_id');
    }
}
