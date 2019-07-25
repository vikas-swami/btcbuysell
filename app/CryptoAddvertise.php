<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CryptoAddvertise extends Model
{
    protected $guarded = ['id'];

    public function gateway()
    {
        return $this->hasOne(Gateway::class, 'id', 'gateway_id')->withDefault();
    }

    public function method()
    {
        return $this->hasOne(Crypto::class, 'id', 'method_id')->withDefault();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault();
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id')->withDefault();
    }

    public function advertise()
    {
        return $this->belongsTo(AdvertiseDeal::class)->withDefault();
    }
}

