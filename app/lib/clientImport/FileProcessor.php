<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-28
 * Time: 16:09
 * Used: this is a Abstract class for processing all different kind of files (csv, xlxs....)
 * each child child class used to read the file and convert the file content into a database structured multi-dimensional array
 * then write to the database
 */

namespace App\Lib\clientImport;


use App\Http\Controllers\Admin\ClientController;

abstract class FileProcessor {
    protected $infoSourceId; //client source come from
    protected $firmId; //indicate which of the firm those clients are belonged to
    protected $fullFilePathName; //the full path and full name of the uploaded file
    protected $headerLine; //indicate the line number of the head/title line
    protected $colTitle = array(); //set up each title in the file corresponding to the filed in the database
    protected $_csvStructure = array(); //use to indicate the file corresponding to the database relation
    protected $_csvArray = array(); //used to hold the converted data of the uploaded file

    /**
     * FileProcessor constructor.
     * @param $FullFilePathName: the full file path name into the property
     * @param $infoSourceId: indicate which those clients are belongs to
     * @param $firmId: indicate which firm fo the those clients are belongs to
     */
    protected function __construct($FullFilePathName, $infoSourceId, $firmId) {
        $this->infoSourceId = $infoSourceId;
        $this->firmId = $firmId;
        $this->fullFilePathName = $FullFilePathName;
    }

    /**+
     * @param $FullFilePathName
     * @param $infoSourceId
     * @param $firmId
     * @return bool
     * Used For: use the file extension to instant related class, then read the file and write to database
     */
    public static function process($FullFilePathName, $infoSourceId, $firmId): bool {
        $fileType = explode(".", $FullFilePathName)[1]; //get the file extension from the filename
        switch ($fileType) {
            case "csv": // csv file, note the csv file has to be UTF-8 encoded
                $processor = new csvProcessor($FullFilePathName, $infoSourceId, $firmId);
                break;
            case "xlsx": //xlsx file
                $processor = new xlsxProcessor($FullFilePathName, $infoSourceId, $firmId);
                break;
            default:
                $processor = false;
                break;
        }
        if (!$processor->readFile()) { //read the file
            return false;
        }
        return $processor->writeToDatabase(); //return the result of write to database
    }

    /**
     * @return bool
     * Used For: write data into database
     */
    protected function writeToDatabase(): bool {
        if (!$this->_csvArray) { //check if there is any data in the _csvArray, while holds the converted file data
            return false;
        }
        $clientObj = new ClientController(); //instant the clientController
        $count = 0;
        foreach ($this->_csvArray as $client) {
            $clientObj->createClient($client); //insert to database
            $count++;
        }
        if ($count != sizeof($this->_csvArray)) { //check if all the data has been recorded into database
            return false;
        }
        return true;
    }

    /**
     * @return bool
     * Used For: read the file, all the child class must complete this function
     */
    abstract protected function readFile(): bool;

    /**
     * @param $days
     * @param string $oldDate
     * @param string $timezone
     * @return string
     * @throws \Exception
     * Used For: convert excel stupid numeric date into timestamp
     */
    protected function newDate($days, $oldDate = "1900-01-01", $timezone = "PRC") {
        $dateTime = new \DateTime($oldDate, new \DateTimeZone($timezone));
        $unixTime = $dateTime->format('U') + $days * 24 * 60 * 60;
        $dateTime = new \DateTime("@$unixTime");//DateTime类的bug，加入@可以将Unix时间戳作为参数传入
        $dateTime->setTimezone(new \DateTimeZone($timezone));
        return $dateTime->format("Y-m-d H:i:s");
    }

    /**
     * @param $str
     * @return array
     * Used For:convert csv string into array
     */
    protected function str2Arr($str): array {
        $str = str_replace("\n", "", $str);
        $str = str_replace("\r", "", $str);
        return explode(',', $str);
    }
}
