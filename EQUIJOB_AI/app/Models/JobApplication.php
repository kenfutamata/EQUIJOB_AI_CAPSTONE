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
        'hiredAt',
        'notifiedAt',
        'appliedAt',
        'remarks',
        'interviewDate',
        'reminderSentAt', 
        'interviewTime',
        'interviewLink', 
        'uploadResume',
        'uploadApplicationLetter',
    ];

    protected $casts = [
        'googleTokenExpiry' => 'datetime',
        'interviewDate' => 'date',
        'interviewTime' => 'datetime',
        'hiredAt' => 'datetime',
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
                        ->orWhereRaw('"disabilityType" LIKE ?', [$searchTerm]); 
                })
                ->orWhereHas('applicant', function ($q3) use ($searchTerm) {
                    $q3->where('firstName', 'like', $searchTerm)
                        ->orWhere('lastName', 'like', $searchTerm);
                });
        });
    }
    public function applicant()
    {
        return $this->belongsTo(\App\Models\User::class, 'applicantID', 'id');
    }
    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'jobPostingID');
    }
    public function feedbacks()
    {
        return $this->hasMany(Feedbacks::class);
    }
}
