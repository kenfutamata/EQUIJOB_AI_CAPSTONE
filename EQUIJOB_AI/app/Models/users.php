<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string|null $address
 * @property string $phone_number
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property string|null $type_of_disability
 * @property string|null $pwd_id
 * @property string|null $upload_pwd_card
 * @property string $role
 * @property string $status
 * @property string|null $company_name
 * @property string|null $company_logo
 * @property string|null $profile_picture
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
        'company_logo',
        'business_permit', 
        'userID'
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
         return $this->hasOne(\App\Models\Resume::class, 'user_id');
    }

}
