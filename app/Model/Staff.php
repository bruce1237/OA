<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    //
    use SoftDeletes;
    protected $primaryKey = 'staff_id';
    protected $guarded =[];

    public function getDepartmentIdAttribute($value){
        return Department::find($value)->depart_name;

    }
    public function setDepartmentIdAttribute($value){
        $this->attributes['department_id'] = Department::where('depart_name','=',$value)->first()->id;
    }

    public function getStaffGenderAttribute($value){
        switch ($value){
            case 0:
                $value ="女";
                break;
            case 1:
                $value ="男";
                break;
        }

        return $value;
    }
    public function setStaffGenderAttribute($value){
        switch ($value){
            case "女":
                $value ="0";
                break;
            case "男":
                $value ="1";
                break;
        }

        $this->attributes['staff_gender'] = $value;
    }

    public function getStaffMarriageAttribute($value){
        switch($value){
            case 0:
                $value ="未婚";
                break;
            case 1:
                $value ="已婚";
                break;
            case 2:
                $value ="离异";
                break;
            case 3:
                $value ="丧偶";
                break;

        }
        return $value;
    }
    public function setStaffMarriageAttribute($value){
        switch($value){
            case "未婚":
                $value = "0";
                break;
            case "已婚":
                $value = "1";
                break;
            case "离异":
                $value = "2";
                break;
            case "丧偶":
                $value = "3";
                break;
        }
        $this->attributes['staff_marriage'] = $value;
    }

    public function getStaffStatusAttribute($value){
        switch($value){
            case 0:
                $value ="离职";
                break;
            case 1:
                $value = "正常";
                break;
        }
        return $value;
    }
    public function setStaffStatusAttribute($value){
        switch($value){
            case "离职":
                $value = "0";
                break;
            case "正常":
                $value = "1";
                break;
        }
        $this->attributes['staff_status'] = $value;
    }

    public function getStaffTypeAttribute($value){
        switch($value){
            case 0:
                $value="试用员工";
                break;
            case 1:
                $value ="正式员工";
                break;
        }
        return $value;
    }
    public function setStaffTypeAttribute($value){
        switch($value){
            case "试用员工":
                $value= "0";
                break;
            case "正式员工":
                $value ="1";
                break;
        }
        $this->attributes['staff_type'] = $value;
    }


}
