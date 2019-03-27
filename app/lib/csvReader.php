<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-27
 * Time: 15:55
 */
namespace App\Lib;

abstract class csvReader
{
    protected $infoSourceId;
    protected $firmId;
    protected $fileResource;
    protected $headerLine;
    protected $colTitle=array();
    protected $csvArray = array();
    protected $_csvStructure;
    protected $_csvArray;

    public function __construct($FullFilePathName,$infoSourceId,$firmId){
        $this->infoSourceId=$infoSourceId;
        $this->firmId=$firmId;
        $this->fileResource = fopen($FullFilePathName,"r");
    }
    protected function str2Arr($str){
        $str = str_replace("\n","",$str);
        $str = str_replace("\r","",$str);
        return explode(',',$str);
    }

    abstract protected function readCsv();
    abstract protected function writeToDatabase();
}
