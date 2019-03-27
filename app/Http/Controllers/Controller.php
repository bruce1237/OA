<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected  $modalFullNameSpace = "App\Model\\";
    protected $returnData =[
        'status'=>false,
        'msg' => 'init',
        'code' => 2,
        'data'=>null,
    ];

    public function runTimer(string $process)
    {
        $this->log([
            $process,
            microtime(true),
        ], "timer");
    }


    /**
     * log system  record logs
     * @param array $logData
     * @param string $type
     */
    public function log(array $logData, $type = "log")
    {
        $time = date("Y-m-d H:i:s");
        array_unshift($logData, $time);
        $log = array_map('json_encode', $logData);
        $fileName = "{$type}.log";
        file_put_contents(storage_path("logs/{$fileName}"), implode("-", $log) . "\r\n", FILE_APPEND);
    }


}
