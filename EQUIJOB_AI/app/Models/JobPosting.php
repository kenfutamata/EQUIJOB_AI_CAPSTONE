<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Add this import
use Illuminate\Notifications\Notifiable;

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
