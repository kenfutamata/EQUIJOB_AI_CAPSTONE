<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    public $timestamps = false;
    protected $primaryKey = 'id';


    protected $fillable = [
        'provinceName',
    ];

    public function cities()
    {
        return $this->hasMany(Cities::class, 'provinceId');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'provinceId');
    }
}
