<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $casts = [
        'driver_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

}
