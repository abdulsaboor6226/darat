<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'ceo_name',
        'phone',
        'device',
        'status',
        'mobile_os',
        'image',
        'company_name',
        'whatsapp_number',
        'mobile_number_1',
        'mobile_number_2',
        'company_address',
        'about_us',
        'construction_company',
        'real_state_agent',
        'architect',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'construction_company' => 'boolean',
        'real_state_agent' => 'boolean',
        'architect' => 'boolean',
    ];
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
