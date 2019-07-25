<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCryptoBalance extends Model
{
    protected $guarded = ['id'];

    public function crypto()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class)->withDefault();
    }
}
