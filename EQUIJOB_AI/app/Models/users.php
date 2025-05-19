<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class users extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'date_of_birth',
        'address',
        'gender', 
        'type_of_disability',
        'pwd_id',
        'upload_pwd_card',
        'role',
        'status',
        'company_name',
        'company_logo'
    ];

    protected $hidden = [
        'password'
    ];
    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
