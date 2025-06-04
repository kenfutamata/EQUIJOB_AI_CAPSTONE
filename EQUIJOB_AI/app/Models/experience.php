<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class experience extends Model
{
    protected $fillable = [
        'resume_id',
        'employer',
        'job_title',
        'location',
        'year',
        'description'
    ];

    protected $table = 'experiences';

    public function resume()
    {
        return $this->belongsTo(resume::class, 'resume_id');
    }
}
