<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;


/**
 * 
 *
 * @property int $id
 * @property string $firstName
 * @property string $lastName
 * @property string $email
 * @property string $password
 * @property string|null $address
 * @property string $phoneNumber
 * @property string|null $dateOfBirth
 * @property string|null $gender
 * @property string|null $typeOfDisability
 * @property string|null $pwdId
 * @property string|null $upload_pwd_card
 * @property string $role
 * @property string $status
 * @property string|null $companyName
 * @property string|null $companyLogo
 * @property string|null $profilePicture
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $userID
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Resume|null $resume
 * @method static \Database\Factories\usersFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereCompanyLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereProfilePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users wherePwdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereTypeOfDisability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereUploadPwdCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|users whereUserID($value)
 * @mixin \Eloquent
 * @method \Illuminate\Database\Eloquent\Relations\HasOne resume()
 */
class users extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id';
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

    public function resume(){
         return $this->hasOne(\App\Models\Resume::class, 'userID');
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

    public function city()
    {
        return $this->belongsTo(Cities::class, 'cityId');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceId');
    }

}
