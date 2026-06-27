<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpdcHackerrankProblem extends Model
{
    protected $table = 'ipdc_hackerrank_problems';
    protected $fillable = [
        'title',
        'description',
        'input_format',
        'constraints',
        'output_format',
        'sample_input',
        'sample_output',
        'test_cases',
        'difficulty',
        'points',
        'created_by'
    ];

    protected $casts = [
        'test_cases' => 'array'
    ];

    public function submissions()
    {
        return $this->hasMany(IpdcHackerrankSubmission::class, 'problem_id');
    }
}
