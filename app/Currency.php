<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $guarded = ['id'];

    public function currency()
    {
        return $this->belongsTo(CryptoAddvertise::class)->withDefault();
    }
}
