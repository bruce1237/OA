<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    //
    use SoftDeletes;
    protected $guarded=[];




    public function getAssignableAttribute($value){

        if($value){
            $value = "分配";
        }else{
            $value = "不分配";
        }

        return $value;
    }

    public function setAssignableAttribute($value){

        if($value=="分配"){
            $value = '1';
        }else{
            $value = '0';
        }

          $this->attributes['assignable']=$value;
    }

}
