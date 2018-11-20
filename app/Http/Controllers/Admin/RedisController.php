<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Phpml\Association\Apriori;

class RedisController extends Controller
{
    protected $redis;
    //

    public function __construct() {
        $this->redis = new \Redis();
        $this->redis->pconnect(env("REDIS_HOST"));
    }


    public function index(){
        $this->set();
        $r = $this->get();

        dd($r);

        return view("admin/redis/index");
    }

    private function set(){
//        $this->redis->set("ABC",'DEF');
        $arr = ['a'=>1,'b'=>2,'c'=>3];
        $this->redis->hMset("ABC",$arr);

        for($i=0; $i<10;$i++){
            $this->redis->rPush("LIST",$i);
        }


        $a = new Apriori();
        dd($a);




    }

    private function get(){
        return $a = $this->redis->lRange("LIST",0,3);
        return $this->redis->hGet("ABC","b");
    }
}
