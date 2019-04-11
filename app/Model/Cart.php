<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    //
    protected $primaryKey='cart_id';
    protected $guarded=[];
    use SoftDeletes;
}
