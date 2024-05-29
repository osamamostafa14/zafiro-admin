<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Order extends Model
{

    protected $casts = [
        'status' => 'integer',
    ];
    protected $guarded=[]; 

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }
    
    public function order_routes()
    {
        return $this->hasMany(OrderRoute::class);
    }

    
}
