<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $resume_id
 * @property string|null $employer
 * @property string|null $job_title
 * @property string|null $location
 * @property string|null $year
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resume $resume
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereEmployer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereResumeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|experience whereYear($value)
 * @mixin \Eloquent
 */
class experience extends Model
{
    protected $fillable = [
        'resume_id',
        'employer',
        'job_title',
        'location',
        'year',
        'description'
    ];

    protected $table = 'experiences';

    public function resume()
    {
        return $this->belongsTo(resume::class, 'resume_id');
    }
}
