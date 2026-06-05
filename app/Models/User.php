<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\BusinessUnit;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Auto-load business unit with authenticated user
     */
    protected $with = ['businessUnit'];

    /**
     * One user → one business unit
     * users.id = business_units.bu_user
     */
    public function businessUnit()
    {
        return $this->hasOne(BusinessUnit::class, 'bu_user', 'id');
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
