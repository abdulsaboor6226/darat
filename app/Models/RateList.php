<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateList extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function rateListDetails()
    {
        return $this->hasMany(RateListDetail::class);
    }
   
}
