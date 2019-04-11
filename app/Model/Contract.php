<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    //
    protected $primaryKey = 'contract_id';
    protected $guarded =[];
    use SoftDeletes;

}
