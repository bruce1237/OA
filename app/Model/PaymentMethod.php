<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //
    protected $primaryKey="payment_method_id";
    protected $guarded=[];

    public function setPaymentMethodAttributesAttribute($value){
        $this->attributes['payment_method_attributes'] =json_encode($value);

    }

    public function getPaymentMethodAttributesAttribute($value){
        return json_decode($value,true);
    }
}
