<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];

    public function getPositionRankAttribute($value) {
        switch ($value) {
            case 0:
                $value = "普通员工";
                break;
            case 3:
                $value = "部门经理";
                break;
            case 700:
                $value = "区域经理";
                break;
            case 900:
                $value = "总经理";
                break;
            case 1000:
                $value = "OA管理员";
                break;

        }
        return $value;
    }

    public function setPositionRankAttribute($value) {
        switch ($value) {
            case "普通员工":
                $value = 0;
                break;
            case "部门经理":
                $value = 3;
                break;
            case "区域经理":
                $value = 700;
                break;
            case "总经理":
                $value = 900;
                break;
            case "OA管理员":
                $value = 1000;
                break;

        }
        $this->attributes['position_rank'] = $value;
    }


}
