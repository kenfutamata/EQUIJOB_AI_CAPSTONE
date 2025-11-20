<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// No need for this: use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;


class users extends Authenticatable
{
    use HasFactory, Notifiable; 

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // This is correct, it points to your 'users' table

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'phoneNumber',
        'dateOfBirth',
        'address',
        'gender',
        'typeOfDisability',
        'pwdId',
        'certificates',
        'upload_pwd_card',
        'role',
        'status',
        'companyName',
        'companyAddress',
        'companyLogo',
        'businessPermit',
        'userID',
        'profilePicture',
        'cityId',
        'provinceId',
        'extractedCertificates',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'certificates'=>'array', 
            'extractedCertificates'=>'array', 
        ];
    }

    public function resume()
    {
         return $this->hasOne(Resume::class, 'userID');
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'jobProviderID');
    }

    protected function name(): Attribute
    {
        return Attribute::get(
            fn() => $this->firstName . ' ' . $this->lastName
        );
    }

    /**
     * Get the city that the user belongs to.
     */
    public function city()
    {
        return $this->belongsTo(Cities::class, 'cityId');
    }

    /**
     * Get the province that the user belongs to.
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceId');
    }
}