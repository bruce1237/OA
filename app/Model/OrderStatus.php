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
            case "0":
                $v ="合法性审批";
                break;
            case "1":
                $v="有效性审批";
                break;
            case "2":
                $v="状态更新";
                break;
        }
        return $v;
    }

    public function setOrderStatusCategoryAttribute($value){
        switch($value){
            case "合法性审批":
                $value ="0";
                break;
            case "有效性审批":
                $value="1";
                break;
            case "状态更新":
                $value="2";
                break;
        }
        $this->attributes['order_status_category']=$value;
    }
}
