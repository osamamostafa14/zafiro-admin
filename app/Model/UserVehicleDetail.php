<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserVehicleDetail extends Model
{
    protected $casts = [
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
