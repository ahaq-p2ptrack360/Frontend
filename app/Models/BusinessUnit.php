<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BusinessUnit extends Model
{
    protected $table = 'business_units';

    protected $primaryKey = 'id'; // default, but explicit for clarity

    protected $fillable = [
        'bu_user',   // FK to users.id
        // add other business_units columns here
    ];

    public $timestamps = true; // set false if table doesn't have timestamps

    /**
     * Inverse relationship: BU belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'bu_user', 'id');
    }
}
