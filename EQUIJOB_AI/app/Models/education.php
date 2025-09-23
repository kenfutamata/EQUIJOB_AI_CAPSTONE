<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $resumeID
 * @property string|null $school
 * @property string|null $degree
 * @property string|null $location
 * @property string|null $year
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resume $resume
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereDegree($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereResumeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|education whereYear($value)
 * @mixin \Eloquent
 */
class education extends Model
{
    protected $fillable = [
        'resumeID',
        'school',
        'degree',
        'location',
        'year', 
        'description'
    ];

    protected $table = 'educations';

    public function resume()
    {
        return $this->belongsTo(resume::class, 'resumeID');
    }
}
