<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logo extends Model
{
    use SoftDeletes;
    protected $fillable=["reg_no", "int_cls", "logo_name", "logo_img", "reg_issue", "reg_date", "agent", "app_date", "applicant_cn", "applicant_en", "applicant_id", "applicant_share", "address_cn", "address_en", "announcement_date", "announcement_issue", "international_date", "post_date", "private_start", "private_end", "privilege_date", "category", "color", "trade_type", "price", "name_type", "suitable", "logo_length", "seller_id", "flow_id", "goods_id"];


    public function logoFlow()
    {
        return $this->hasOne(LogoFlow::class, 'id', 'flow_id');
    }
    public function logoCate(){
        return $this->hasOne(LogoCate::class,'id','int_cls');
    }


    public function logoGoods()
    {
        return $this->hasMany(LogoGoods::class, 'logo_id', 'id');
    }

    public function logoSeller()
    {
        return $this->hasMany('App\Model\LogoFlow');
    }

    //
}
