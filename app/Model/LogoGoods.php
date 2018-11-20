<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogoGoods extends Model
{
    //
    use SoftDeletes;
    protected $fillable=['logo_id','goods_name','goods_code'];
    public function logo(){
        return $this->belongsTo(Logo::class);
    }
}
