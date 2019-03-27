<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-27
 * Time: 15:56
 */

namespace App\Lib;

use App\Model\Client;

class baiduCsvReader extends csvReader
{

    public function __construct($FullfilePathName,$infoSourceId,$firmId)
    {
        parent::__construct($FullfilePathName,$infoSourceId,$firmId);
        $this->headerLine = 3;
        $this->colTitle = [
            '姓名' => 'client_name',
            '手机号' => 'client_mobile',
            '商标名称' => 'client_enquiries',
            '日期' => 'enquiry_date'];

        $this->readCsv();
        $this->writeToDatabase();
    }

    protected function readCsv()
    {
        // TODO: Implement readCsv() method.
        $lineCount = 0;
        while ($line = fgets($this->fileResource)) {
            $lineArray = $this->str2Arr($line);
            if ($lineCount == $this->headerLine) { //the header
                foreach ($lineArray as $col => $value) {
                    if (key_exists($value, $this->colTitle)) {
                        $this->_csvStructure[$col] = $this->colTitle[$value];
                    }
                }
                foreach ($this->_csvStructure as $key=>$value){
                    if($value == "client_enquiries"){
                        $enquiryKey = $key;
                    }
                    if($value =="enquiry_date"){
                        $enquiryDateKey = $key;
                    }
                    if($value =="client_mobile"){
                        $mobileKey = $key;
                    }
                }
                unset($this->_csvStructure[$enquiryKey]);
                unset($this->_csvStructure[$enquiryDateKey]);
            }

            if ($lineCount > $this->headerLine) {
                $lineArray[$mobileKey] = filter_var($lineArray[$mobileKey],FILTER_SANITIZE_NUMBER_INT);
                $this->_csvArray[$lineCount]['client_enquiries']=$lineArray[$enquiryKey]." (".$lineArray[$enquiryDateKey].")";
                foreach ($this->_csvStructure as $key => $value) {
                        $this->_csvArray[$lineCount][$value]= $lineArray[$key];
                }
                $this->_csvArray[$lineCount]['client_assign_to']=-1;
                $this->_csvArray[$lineCount]['client_belongs_company']=$this->firmId;
                $this->_csvArray[$lineCount]['client_source']=$this->infoSourceId;
            }
            $lineCount++;
        }
    }
    protected function writeToDatabase(){
        foreach ($this->_csvArray as $client){


            Client::updateOrCreate(['client_mobile'=>$client['client_mobile']],$client);

        }
    }
}
