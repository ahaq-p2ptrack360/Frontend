<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'vendors';

    protected $fillable = [
        'company_id',
        'vendor_type_id',
        'name',
        'email',
        'password',
        'phone',
        'alternate_phone',
        'address_1',
        'address_2',
        'latitude',
        'longitude',
        'city',
        'state',
        'country',
        'zipcapode',
        'status',
        'bu_id',
        'active',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];
}
