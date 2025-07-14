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
        'jobApplicationNumber',
        'jobPostingID',
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

    public function scopeSearch($query, $search)
    {
        $searchTerm = "%{$search}%";

        return $query->where(function ($q) use ($searchTerm) {
            $q->where('jobApplicationNumber', 'like', $searchTerm)
                ->orWhere('status', 'like', $searchTerm)
                ->orWhereHas('jobPosting', function ($q2) use ($searchTerm) {
                    $q2->where('position', 'like', $searchTerm)
                        ->orWhereRaw('"companyName" LIKE ?', [$searchTerm])
                        ->orWhereRaw('"disabilityType" LIKE ?', [$searchTerm]); // <-- THE FIX
                })
                ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                    $q3->where('first_name', 'like', $searchTerm)
                        ->orWhere('last_name', 'like', $searchTerm);
                });
        });
    }
    public function applicant()
    {
        return $this->belongsTo(\App\Models\User::class, 'applicantID');
    }
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'jobPostingID');
    }
}
