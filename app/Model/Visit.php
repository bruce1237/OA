<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    //
    protected $primaryKey = 'visit_id';
    protected $guarded =[];
    public function getvisitByStaffIdAttribute($value){
        return Staff::find($value)->staff_name;
    }

    public function getVisitStatusAttribute($value){
        return VisitStatus::where('visit_status_id','=',$value)->orderBy('created_at','desc')->first()->visit_status_name;
    }


}
