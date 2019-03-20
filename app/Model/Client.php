<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $primaryKey = 'client_id';
    protected $guarded=[];


    public function getClientSourceAttribute($value){

     return  InfoSource::find($value)->info_source_name;
    }

    public function getClientAssignToAttribute($value){

       if($value>0){
           return Staff::find($value)->staff_name;
       }else{
           return "";
       }
    }

//    public function setClientAssignToAttribute($value){
//dd($value);
//        try{
//            $this->attributes['client_assign_to'] =Staff::where('staff_name', '=', $value)->first()->staff_id;
//        }catch (\Exception $e){
//            $this->attributes['client_assign_to'] = 0;
//        }
//
//
//    }

    public function getClientBelongsCompanyAttribute($value){
        return Firm::find($value)->firm_name;
    }

    public function getCreatedAtAttribute($value){
        return  date("Y-m-d",strtotime($value));
    }

    public function setClientBelongsCompanyAttribute($value){
        $this->attributes['client_belongs_company'] = Firm::where('firm_name','=',$value)->first()->firm_id;
    }


}
