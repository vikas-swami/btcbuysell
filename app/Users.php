<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{

    protected $guarded = ['id'];

    protected $table = 'users';

    public function crypto()
    {
        return $this->hasMany(UserCryptoBalance::class, 'user_id', 'id');
    }



}
