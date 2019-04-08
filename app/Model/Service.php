<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    //
    protected $primaryKey="service_id";
    protected $guarded=[];
    use SoftDeletes;
}
