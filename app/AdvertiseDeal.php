<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertiseDeal extends Model
{
    protected $guarded = ['id'];

    public function to_user()
    {
        return $this->hasOne(User::class, 'id', 'to_user_id')->withDefault();
    }

    public function from_user()
    {
        return $this->hasOne(User::class, 'id', 'from_user_id')->withDefault();
    }

    public function conversation()
    {
        return $this->hasMany(DealConvertion::class, 'deal_id', 'id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id')->withDefault();
    }

    public function gateway()
    {
        return $this->hasOne(Gateway::class, 'id', 'gateway_id')->withDefault();
    }
    public function method()
    {
        return $this->hasOne(Crypto::class, 'id', 'method_id')->withDefault();
    }



}
