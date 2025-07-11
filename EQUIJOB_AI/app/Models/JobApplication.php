<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class JobApplication extends Model
{
    use Notifiable; 
    protected $table = 'jobApplications';
    protected $fillable = [
        'applicantID',
        'jobID',
        'status',
        'appliedAt',
        'remarks',
        'interviewDate',
        'interviewTime',
        'uploadResume',
        'uploadApplicationLetter',
        'googleAccessToken',
        'googleRefreshToken',
        'googleTokenExpiry'
    ];

    protected $casts = [
        'googleTokenExpiry' => 'datetime',
        'interviewDate' => 'date',
        'interviewTime' => 'datetime',
    ];
    public function applicant()
    {
        return $this->belongsTo(\App\Models\User::class, 'applicantID');
    }
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'jobID');
    }
}
