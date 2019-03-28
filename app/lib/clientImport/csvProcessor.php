<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-28
 * Time: 16:13
 * Used: extend the file Processor and complete the readFile function
 */

namespace App\Lib\clientImport;


class csvProcessor extends FileProcessor {
    public function __construct($FullFilePathName, $infoSourceId, $firmId) {
        parent::__construct($FullFilePathName, $infoSourceId, $firmId);
        $this->headerLine = 3; //indicate line number of the header/title
        $this->colTitle = [ //file header corresponding to the database table filed
            '姓名' => 'client_name',
            '手机号' => 'client_mobile',
            '商标名称' => 'client_enquiries',
            '日期' => 'enquiry_date'];
    }

    /**
     * @return bool
     * Used For:read the csv file and put into array
     * NOTE: can not read other encoded csv but UTF-8
     * fgetcsv() not working perfect on Chinese characters,
     * so use fgets() instead
     */
    protected function readFile(): bool {
        $lineCount = 0; //init the line count: start from line 1
        $fileResource = fopen($this->fullFilePathName, "r"); //get file resource
        while ($line = fgets($fileResource)) { //walk through the file line by line
            $lineArray = $this->str2Arr($line); //convert the line content into array
            if ($lineCount == $this->headerLine) { //reading the header line
                foreach ($lineArray as $col => $value) { //go through the file header array
                    if (key_exists($value, $this->colTitle)) { //if the header matches the designed database filed, then
                        $this->_csvStructure[$col] = $this->colTitle[$value]; // save it into the csvStructure
                    }
                }

                try { //combine the enquiries and the dateTime of this enquires into one piece of info
                    foreach ($this->_csvStructure as $key => $value) {
                        if ($value == "client_enquiries") {
                            $enquiryKey = $key;
                        }
                        if ($value == "enquiry_date") {
                            $enquiryDateKey = $key;
                        }
                        if ($value == "client_mobile") {
                            $mobileKey = $key; //used to filter/sanitize the value
                        }
                    }
                } catch (\Exception $e) {
                    //can not find relative filed/information from he file
                    return false;
                }
                unset($this->_csvStructure[$enquiryKey]); //get rid of the no longer / unnecessary keys
                unset($this->_csvStructure[$enquiryDateKey]); ////get rid of the no longer / unnecessary keys
            }

            if ($lineCount > $this->headerLine) { //here is the main content starts
                $lineArray[$mobileKey] = filter_var($lineArray[$mobileKey], FILTER_SANITIZE_NUMBER_INT); //filter/sanitize the value: remove none numeric value
                $this->_csvArray[$lineCount]['client_enquiries'] = $lineArray[$enquiryKey] . " (" . $lineArray[$enquiryDateKey] . ")"; //combine the enquiry and date into one
                foreach ($this->_csvStructure as $key => $value) { //walk through the structure array to assign each csv file value into corresponding database filed
                    $this->_csvArray[$lineCount][$value] = $lineArray[$key];
                }
                //add some extra files
                $this->_csvArray[$lineCount]['client_assign_to'] = -1; //this indicate those are new / unassigned client
                $this->_csvArray[$lineCount]['client_belongs_company'] = $this->firmId; // indicate the client's belongs to which firm
                $this->_csvArray[$lineCount]['client_source'] = $this->infoSourceId; //indicate the client come from which source
            }
            $lineCount++;
        }
        if (!$this->_csvArray) { // if the csvArray which holds the value of the whole csv file is empty, then it must be something wrong with the file
            return false;
        }
        return true;
    }

}
