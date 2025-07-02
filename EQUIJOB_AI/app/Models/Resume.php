<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $dob
 * @property string|null $address
 * @property string $email
 * @property string|null $phone
 * @property string|null $type_of_disability
 * @property string|null $experience
 * @property string|null $photo
 * @property string|null $summary
 * @property string|null $skills
 * @property string|null $ai_generated_summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\education> $educations
 * @property-read int|null $educations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\experience> $experiences
 * @property-read int|null $experiences_count
 * @property-read \App\Models\users $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereAiGeneratedSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereTypeOfDisability($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resume whereUserId($value)
 * @mixin \Eloquent
 */
class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'dob',
        'address',
        'email',
        'phone',
        'experience',
        'photo',
        'summary',
        'skills',
        'type_of_disability'
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
