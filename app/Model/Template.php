<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    //
    protected $primaryKey = "template_id";
    protected $guarded = [];
    public $timestamps=false;
}
