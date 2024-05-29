<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\User;

class OrderRoute extends Model
{

    protected $tabel='order_routes';
    protected $fillable=[
        'order_id','driver_id','customer_name','price' ,'address','route_order','latitude','longitude','status',
        'start_date','arrival_date','delivery_date','driver_status','is_current_route','cancellation_reasen',
        'distance','duration','delivery_method_details','delivery_method','notes','delivery_method','delivery_method_details',
        'phone','rx_or_item','type_of_location','order_type','order_priority','route_name','notes','live_tracking_long','live_tracking_lat'
    ];

    protected $casts = [
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // public function getDriverStatusAttribute($value)
    // {
    //     if($value == "not_started"){
    //         return "Not Started";
    //     }
    //     else if($value == "started"){
    //         return "Started";
    //     }
    //     else if($value == "delivered"){
    //         return "Delivered";
    //     }
    //     else if($value == "canceled")
    //     {
    //         return "Canceled";
    //     }
    //     else {
    //         return "Arrived";
    //     }

    // }

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    
    public function delivery_routes()
    {
        return $this->belongsTo(User::class,'driver_id');
    }
}
