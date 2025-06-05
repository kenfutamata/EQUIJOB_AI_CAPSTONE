<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'dob',
        'address',
        'disability_type',
        'email',
        'phone',
        'experience',
        'photo',
        'summary',
        'skills'
    ];
    protected $table = 'resume';

    public function experiences(){
        return $this->hasMany(experience::class, 'resume_id');
    }

    public function educations(){
        return $this->hasMany(education::class, 'resume_id');
    }

    public function user(){
        return $this->belongsTo(users::class, 'user_id');
    }
}
