<?php

namespace App;

use App\Model\Order;
use App\Model\UserVehicleDetail;
use App\Model\UserBankDetail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'phone', 'email', 'password', 'provider_name', 'provider_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_phone_verified' => 'integer',
        'update_version' => 'integer',
    ];

    public function bank_details(){
        return $this->hasOne(UserBankDetail::class,'user_id');
    }
    
    public function vehicle_details(){
        return $this->hasOne(UserVehicleDetail::class,'user_id');
    }

    
}
