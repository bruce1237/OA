<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
    //
    protected $primaryKey = "order_id";
    protected $guarded = [];
    use SoftDeletes;

    public function getOrderTaxTypeAttribute($value) {
        $res = "";
        switch ($value) {
            case "0":
                $res = "无票";
                break;
            case "1":
                $res = "普票";
                break;
            case "2":
                $res = "专票";
                break;
        }
        return $res;

    }

    public function setOrderTaxTypeAttribute($value) {
        $res = 0;
        switch ($value) {
            case "无票":
                $res = "0";
                break;
            case "普票":
                $res = "1";
                break;
            case "专票":
                $res = "2";
                break;
        }
        $this->attributes['order_tax_type'] = $res;
    }

    public function getOrderSettlementAttribute($value) {
        $res = "";
        switch ($value) {
            case "0":
                $res = "未结算";
                break;
            case "1":
                $res = "已结算";
                break;
        }
        return $res;
    }

    public function setOrderSettlementAttribute($value) {
        $res = 0;
        switch ($value) {
            case "未结算":
                $res = "0";
                break;
            case "已结算":
                $res = "1";
                break;
        }
        $this->attributes['order_settlement'] = $res;
    }


    public function getOrderTaxableAttribute($value) {
        $res = "";
        switch ($value) {
            case "0":
                $res = "无票";
                break;
            case "1":
                $res = "普票";
                break;
            case "2":
                $res = "专票";
                break;

        }
        return $res;
    }

    public function setOrderTaxableAttribute($value) {
        $res = 0;
        switch ($value) {
            case "无票":
                $res = "0";
                break;
            case "普票":
                $res = "1";
                break;
            case "专票":
                $res = "2";
                break;

        }
        $this->attributes['order_taxable'] = $res;
    }
}
