<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{
    protected $guarded = ['id'];

    public function method()
    {
        return $this->belongsTo(CryptoAddvertise::class)->withDefault();
    }
}
