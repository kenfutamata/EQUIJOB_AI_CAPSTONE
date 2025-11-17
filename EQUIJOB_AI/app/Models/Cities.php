<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'provinceId',
        'cityName',
    ];


    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceId');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'cityId');
    }
}
