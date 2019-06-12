<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    //
    protected $primaryKey = 'visit_id';
    protected $guarded =[];

    public function getVisitStatusAttribute($value){

        return VisitStatus::find($value)->visit_status_name;
    }


}
