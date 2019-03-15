<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    //
    protected $primaryKey = "order_status_id";
    protected $table = "order_status";
    protected $guarded=[];
}
