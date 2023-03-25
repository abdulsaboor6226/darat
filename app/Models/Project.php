<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'approved' => 'boolean',
        'is_featured' => 'boolean',
    ];
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }
     public function projectImages()
    {
        return $this->hasMany(ProjectImages::class);
    }


}
