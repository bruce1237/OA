<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-15
 * Time: 10:31
 * Used:
 */

namespace App\Lib\contractMaker;


use Throwable;

class ContractException extends \Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}
