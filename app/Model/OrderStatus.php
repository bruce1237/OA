<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    //
    protected $primaryKey = "order_status_id";
    protected $table = "order_status";
    protected $guarded=[];

    public function getOrderStatusCategoryAttribute($value){
        switch($value){
            case "1":
                $value ="合法性审批";
                break;
            case "2":
                $value="有效性审批";
                break;
            case "3":
                $value="状态更新";
                break;
        }
        return $value;
    }

    public function setOrderStatusCategoryAttribute($value){
        switch($value){
            case "合法性审批":
                $value ="1";
                break;
            case "有效性审批":
                $value="2";
                break;
            case "状态更新":
                $value="3";
                break;
        }
        $this->attributes['order_status_category']=$value;
    }
}
