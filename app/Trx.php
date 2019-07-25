<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trx extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id')->withDefault();
    }
}
