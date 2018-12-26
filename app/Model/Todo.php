<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    //
    protected $table='todo';
    use SoftDeletes;
    protected $guarded=[];
}
