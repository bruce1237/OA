<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller {

    //
    public function index() {

//        $mysql = new \mysqli('127.0.0.1','root','root','biaonew');
//        $result = $mysql->query("select * from admins");
//        $rr = $result->fetch_all(MYSQLI_ASSOC);
//        dd($rr);
    $redis = new redisList(10);
//        $this->queue();
//        $dsn = "mysql:dbname=biaonew;host=127.0.0.1;charset=utf8";
//        $user = "root";
//        $pwd = "root";
//        $pdo = new \PDO($dsn, $user, $pwd);
//        $result = $pdo->prepare("SELECT * from admins");
//        $result->execute();
//        $rr = $result->fetchAll();
//        dd($rr);
        return view('Home/test/test');
    }

    public function connect() {
        $dsn = "mysql:host=127.0.0.1;dbname=biao";
        $user = "root";
        $pwd = "root";
        $query = "select * from logos limit 0,100";
        $pdo = new \PDO($dsn, $user, $pwd);
        $result = $pdo->query($query);
        $rr = $result->fetchAll();

        dd($rr);
    }

    

}
