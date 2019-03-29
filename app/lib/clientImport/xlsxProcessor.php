<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-28
 * Time: 15:56
 * Used: read the xlsx file and convert into array
 */

namespace App\Lib\clientImport;


use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class xlsxProcessor extends FileProcessor {
    public function __construct($FullFilePathName, $infoSourceId, $firmId) {
        parent::__construct($FullFilePathName, $infoSourceId, $firmId);
        $this->headerLine = 1; // indicate which line the head line, 1: the first line
        $this->colTitle = [    //map the xlsx file column corresponding to the database filed
            '姓名' => 'client_name',
            '手机号' => 'client_mobile',
            '咨询内容' => 'client_enquiries',
            '日期' => 'enquiry_date'];
    }

    /**
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * Used For: read the xlsx file
     */
    protected function readFile(): bool {
        $reader = new Xlsx(); //instant the reader
        $spreadsheet = $reader->load($this->fullFilePathName); //load the xlsx file into reader
        $worksheet = $spreadsheet->getActiveSheet(); //get the activeSheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10 // get the last row number

        for ($col = 'A'; $col != 'AA'; $col++) { //go through the header and end AA column
            $excelTitle = $worksheet->getCell($col . $this->headerLine)->getValue(); //get the value of each column in the row 1
            if (key_exists($excelTitle, $this->colTitle)) { // if the column title corresponding to the map, then assign to the structure array with col number
                $this->_csvStructure[$col] = $this->colTitle[$excelTitle];
            }
        }

        for ($row = $this->headerLine + 1; $row <= $highestRow; $row++) { //go throught the xlsx file row by row start from the row next to the header
            foreach ($this->_csvStructure as $col => $field) { //walk through the structure array to map out each value
                $value = (string)$worksheet->getCell($col . $row)->getValue(); // only get the xlsx col value which corresponding the database
                if ($field == "enquiry_date") { // as the stupid wps date format, now have to convert to timestamp
                    $value = $this->newDate($value - 1, "1900-01-0"); // convert wps dataTime into timestamp Y-m-d H:i:s format
                }
                $this->_csvArray[$row][$field] = $value; //assign each value into csvArray
            }

            //add some extra field for the database
            $this->_csvArray[$row]['client_assign_to'] = -1; //-1:indicate new unassigned client
            $this->_csvArray[$row]['client_belongs_company'] = $this->firmId; //firmId
            $this->_csvArray[$row]['client_source'] = $this->infoSourceId; //sourceId
            //combine the enquire and enquire date into one piece
            $this->_csvArray[$row]['client_enquiries'] = $this->_csvArray[$row]['client_enquiries'] . "({$this->_csvArray[$row]['enquiry_date']})";
            unset($this->_csvArray[$row]['enquiry_date']); //free the unnecessary filed
        }
        if (!$this->_csvArray) {
            return false;
        }
        return true;
    }

}
