<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Add this import
use Illuminate\Notifications\Notifiable;

/**
 * 
 *
 * @property int $id
 * @property int $job_provider_id
 * @property string $position
 * @property string $company_name
 * @property string $sex
 * @property int $age
 * @property string $disability_type
 * @property string $educational_attainment
 * @property string $salary_range
 * @property string $job_posting_objectives
 * @property string $requirements
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $company_logo
 * @property string|null $description
 * @property string|null $experience
 * @property string|null $skills
 * @property string|null $contact_phone
 * @property string|null $contact_email
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
    protected $table = 'job_posting';
    protected $fillable = [
        'position',
        'company_name',
        'sex',
        'age',
        'disability_type',
        'educational_attainment',
        'salary_range',
        'job_posting_objectives',
        'requirements',
        'company_logo',
        'status',
        'description',
        'experience',
        'skills',
        'contact_phone',
        'contact_email',
        'remarks',
        'job_provider_id'   
    ];

    public function jobProvider()
    {
        return $this->belongsTo(users::class, 'job_provider_id');
    }

    public function recruitment()
    {
        return $this->belongsTo(User::class, 'job_provider_id');
    }
}
