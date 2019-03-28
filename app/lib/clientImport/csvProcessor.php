<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-28
 * Time: 9:45
 */

namespace App\Lib\clientImport;


use App\Http\Controllers\Admin\ClientController;

abstract class csvProcessor
{
    protected $infoSourceId;
    protected $firmId;
    protected $fileResource;
    protected $headerLine;
    protected $colTitle = array();
    protected $csvArray = array();
    protected $_csvStructure;
    protected $_csvArray;

    protected function __construct($FullFilePathName, $infoSourceId, $firmId)
    {
        $this->infoSourceId = $infoSourceId;
        $this->firmId = $firmId;
        $this->fileResource = fopen($FullFilePathName, "r");
    }

    /**+
     * @param $filePathName : csv file path with full file name
     * @param $sourceId :  refers to database info_source_id
     * @param $firmId : which of firm is assigned to those csv client
     * @return bool
     */
    public static function process($filePathName, $sourceId, $firmId):bool
    {
        switch ($sourceId) {
            //baidu
            case 4:
            case 22:
                $processor = new csvProcessorBaidu($filePathName, $sourceId, $firmId);
                return $processor->processing();
                break;
            default:

                return false;
                break;
        }
    }

    protected function processing(): bool
    {
        // TODO: Implement process() method.

        if (!$this->readCsv()) {
            return false;
        }
        return $this->writeToDatabase();

    }

    protected function readCsv(): bool
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
                try {
                    foreach ($this->_csvStructure as $key => $value) {
                        if ($value == "client_enquiries") {
                            $enquiryKey = $key;
                        }
                        if ($value == "enquiry_date") {
                            $enquiryDateKey = $key;
                        }
                        if ($value == "client_mobile") {
                            $mobileKey = $key;
                        }
                    }
                } catch (\Exception $e) {
                    //can not find relative filed/information from he file

                    return false;
                }

                unset($this->_csvStructure[$enquiryKey]);
                unset($this->_csvStructure[$enquiryDateKey]);
            }

            if ($lineCount > $this->headerLine) {
                $lineArray[$mobileKey] = filter_var($lineArray[$mobileKey], FILTER_SANITIZE_NUMBER_INT);
                $this->_csvArray[$lineCount]['client_enquiries'] = $lineArray[$enquiryKey] . " (" . $lineArray[$enquiryDateKey] . ")";
                foreach ($this->_csvStructure as $key => $value) {
                    $this->_csvArray[$lineCount][$value] = $lineArray[$key];
                }
                $this->_csvArray[$lineCount]['client_assign_to'] = -1;
                $this->_csvArray[$lineCount]['client_belongs_company'] = $this->firmId;
                $this->_csvArray[$lineCount]['client_source'] = $this->infoSourceId;
            }
            $lineCount++;
        }
        if (!$this->_csvArray) {
            return false;
        }
        return true;
    }

    protected  function str2Arr($str) : array
    {
        $str = str_replace("\n", "", $str);
        $str = str_replace("\r", "", $str);
        return explode(',', $str);
    }

    protected function writeToDatabase(): bool
    {
        if (!$this->_csvArray) {
            return false;
        }


        $clientObj = new ClientController();
        $count = 0;

        foreach ($this->_csvArray as $client) {
            $clientObj->createClient($client);
            $count++;
        }

        if ($count != sizeof($this->_csvArray)) {
            return false;
        }
        return true;
    }
}
