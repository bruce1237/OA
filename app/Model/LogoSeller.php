<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogoSeller extends Model
{
    //
    use SoftDeletes;
    protected $fillable=['name','tel','wx','mobile','address','post_code'];
    public function logo(){
        return $this->belongsTo(Logo::class);
    }
}
