<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Add this import

class JobPosting extends Model
{
    protected $table = 'job_posting';
    protected $fillable = [
        'position',
        'company_name',
        'sex',
        'age',
        'disability_type',
        'educational_attainment',
        'salary_range',
        'job_posting_objectives',
        'requirements',
        'company_logo',
        'status',
        'description',
        'experience',
        'skills',
        'contact_phone',
        'contact_email',
        'job_provider_id',
    ];

    public function recruitment()
    {
        return $this->belongsTo(User::class, 'job_provider_id');
    }
}
