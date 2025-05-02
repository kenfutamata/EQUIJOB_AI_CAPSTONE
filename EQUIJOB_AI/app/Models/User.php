<?php

namespace App\Models;

Use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable=[
        'email', 
        'password', 
        'role', 
        'status', 
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
