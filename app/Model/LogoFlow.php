<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogoFlow extends Model
{
    //
    use SoftDeletes;
    protected $fillable =['flow_data'];
    public function logo(){
        return $this->belongsTo('App\Model\Logo');
    }
}
