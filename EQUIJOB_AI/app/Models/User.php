<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// THIS IS THE MAIN FIX: Use the full path to Laravel's Attribute class
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // THIS IS THE SECOND FIX: List the actual database columns
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full name.
     */
    protected function name(): Attribute
    {
        return Attribute::get(
            fn() => $this->firstName . ' ' . $this->lastName
        );
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'jobProviderID');
    }

    public function resume()
    {
        return $this->hasOne(Resume::class, 'userID');
    }
}