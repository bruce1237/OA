<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;

class Admin extends User
{
    //
    use SoftDeletes;
    protected $guarded=[];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }

}
