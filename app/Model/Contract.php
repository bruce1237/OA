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

    public function getContractServicesAttribute($value){
      return explode(",",$value);
    }

    public function setContractServicesAttribute($value){
        if(is_array($value)){
            $this->attributes['contract_services'] = implode(',',$value);
        }
            $this->attributes['contract_services'] = $value;
    }

}
