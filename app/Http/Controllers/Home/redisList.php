<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\Home;

/**
 * Description of redisList
 *
 * @author Administrator
 */
class redisList {

    //put your code here
    public $redis;
    private $QLength;
    private $host = "192.168.99.100";
    private $port = "6379";
    private $QName = "Queue";
    private $info = "logos";
    private $startTime = 1438017195;

    public function __construct($QLength) {
        $redis = new \Redis();
        $redis->pconnect($this->host, $this->port);
        $this->redis = $redis;
        $this->QLength = $QLength;
        $data = $this->generateQueue();
        $redis->ltrim($this->QName, 1, 0); //empty the queue
        $redis->del("logos:1");
        if (time() >= $this->startTime) {
            $this->QPush($data);
        }
        $count = $this->redis->lLen($this->QName);
        for($i=0;$i<$count; $i++){
           echo $this->redis->hGet($this->info.":".$this->redis->lPop($this->QName),'logo_name');
        }

        
                
    }

    public function generateQueue() {
        //in this example use logos from data as source to generate the queue
        $mysql = new \mysqli("127.0.0.1", 'root', 'root', 'biao');
        $query = "SELECT * FROM logos limit 23,132";
        $result = $mysql->query($query)->fetch_all(MYSQLI_ASSOC);
        return $result;
    }

    public function QPush(array $queue) {
        if (!$this->redis->lLen($this->QName)) {
            foreach ($queue as $value) {

                if (($this->redis->lLen($this->QName) < $this->QLength)) {

                    $this->redis->lPush($this->QName, $value['id']);
                    $this->redis->hMset($this->info .":". $value['id'], $value);
                } else {
                    break;
                }
            }
        }
    }

    public function getInfo() {
        
    }

}
