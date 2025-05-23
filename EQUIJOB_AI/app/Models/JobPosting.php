<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    protected $table = 'job_posting';
    protected $fillable = [
        'recruitment_id',
        'title',
        'description',
        'requirements',
        'location',
        'salary_range',
    ];

    public function recruitment()
    {
        return $this->belongsTo(users::class, 'recruitment_id');
    }
}
