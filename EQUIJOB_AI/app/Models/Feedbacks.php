<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedbacks extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'jobApplicationID',
        'jobPostingID',
        'applicantID',
        'firstName',
        'lastName',
        'email',
        'phoneNumber',
        'feedbackType',
        'feedbackText',
        'rating', 
        'status'
    ];

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'jobPostingID');
    }


    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class, 'jobApplicationID');
    }
    public function applicant()
    {
        return $this->belongsTo(User::class, 'applicantID');
    }
}
