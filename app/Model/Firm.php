<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Firm extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey="firm_id";
    protected $guarded=[];

}
