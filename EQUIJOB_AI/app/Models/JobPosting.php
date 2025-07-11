<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 
 *
 * @property int $id
 * @property int $jobProviderID
 * @property string $position
 * @property string $companyName
 * @property string $sex
 * @property int $age
 * @property string $disabilityType
 * @property string $educationalAttainment
 * @property string $salaryRange
 * @property string $jobPostingObjectives
 * @property string $requirements
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $companyLogo
 * @property string|null $description
 * @property string|null $experience
 * @property string|null $skills
 * @property string|null $contactPhone
 * @property string|null $contactEmail
 * @property string|null $remarks
 * @property-read \App\Models\users $jobProvider
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read User $recruitment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereCompanyLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereDisabilityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereEducationalAttainment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereExperience($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereJobPostingObjectives($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereJobProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereSalaryRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JobPosting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JobPosting extends Model
{
    use Notifiable;
    protected $table = 'jobPosting';
    protected $fillable = [
        'jobProviderID',
        'position',
        'companyName',
        'sex', 
        'companyLogo',
        'age',
        'disabilityType',
        'educationalAttainment',
        'salaryRange',
        'jobPostingObjectives',
        'requirements',
        'status',
        'description',
        'experience',
        'skills',
        'contactPhone',
        'contactEmail',
        'remarks'
    ];

    public function jobProvider()
    {
        return $this->belongsTo(User::class, 'jobProviderID');
    }

    public function recruitment()
    {
        return $this->belongsTo(User::class, 'jobProviderID');
    }
}
