<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class education extends Model
{
    protected $fillable = [
        'resume_id',
        'school',
        'degree',
        'location',
        'year', 
        'description'
    ];

    protected $table = 'educations';

    public function resume()
    {
        return $this->belongsTo(resume::class, 'resume_id');
    }
}
