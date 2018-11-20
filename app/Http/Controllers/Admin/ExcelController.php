<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class ExcelController extends Controller
{

    //RULES:
    // all the folder variables mush ends with dash \

    //***********predefined info
    //define the col in the excel which for name the export the pic, normally is the logo reg_no
    protected static $excelColForExportPicName = "A";

    //define the folder which the excel will be stored in
    protected static $excelFileFolder = "excelLogoUpload\\";

    //folder name in the storage folder
    protected static $storage = "excel\\";


    //d:/folder/file.txt
    protected $fileName; //eg.file
    protected $fileExt; //txt
    protected $fileFullName;//eg. file.txt
    protected $filePathName;//eg. d:/folder/file.txt

    protected $exportFolder; //eg. D:/folder


    protected static $_excelFileType = ['xls', 'xlsx']; //excel file type this program can process
    protected static $_excelReaderPrix = "PhpOffice\PhpSpreadsheet\Reader\\";
    protected $_excelReaderClass;

    protected $thumbObj;
    protected static $thumbWidth = 350;
    protected static $thumbHeight = 154;

    /**
     * ExcelController constructor.
     * @param string $excelFolder the folder which contained the excel file
     * @param string $storage the folder for storage
     * init the ThumbObj for thumbnail processing later on
     */
    public function __construct($excelFolder = "excelLogoUpload\\", $storage = "excel\\", $thumbWidth = 350, $thumbHeight = 154) {
        self::$excelFileFolder = $excelFolder;
        self::$storage = $storage;
        self::$thumbHeight = $thumbHeight;
        self::$thumbWidth = $thumbWidth;

        $this->thumbObj = new ThumbController();
    }

    public function index() {


        $fileLists = self::fileList(self::$storage . self::$excelFileFolder, self::$_excelFileType);
        return view('admin/excel/index', ['fileLists' => $fileLists]);
    }

    public function export(Request $request) {


        $fileAttr = explode(".", $request->post('fileName')); //put fileName info into array
        $this->fileFullName = $request->post('fileName');//define the file full name
        $this->fileName = $fileAttr[0];//define the file name without ext
        $this->exportFolder = storage_path(self::$storage . self::$excelFileFolder . $this->fileName . "\\");// folder which will contains the pics for export
        $this->fileExt = $fileAttr[1];//define the file extension without the dot
        $this->filePathName = storage_path(self::$storage . self::$excelFileFolder . $this->fileFullName);//define the full file name with path info
        $this->_excelReaderClass = self::$_excelReaderPrix . ucfirst($this->fileExt); //define the excel file reading class

        //check if the folder for pics export exists, if not mkdir
        is_dir($this->exportFolder) ?: mkdir($this->exportFolder);


        $this->exportExcelPics($this->_excelReaderClass, $this->filePathName); //export the pics from the excel file and trim and resize

        $excelVerify = new ReadVerifyExcel(storage_path(self::$storage . self::$excelFileFolder . "\\"), $this->fileFullName); //validate the excel file content

        $data = $excelVerify->init($this->_excelReaderClass);


        return json_encode($data);


    }


    /**
     * @return array file list
     */
    public static function fileList($folder, array $fileType) {
        $fileLists = array();
        $handler = opendir(storage_path($folder)); //
        while (($fileFullName = readdir($handler)) !== false) {
            if ($fileFullName != "." && $fileFullName != ".." && !is_dir(storage_path($folder . "\\" . $fileFullName))) {
                $file_attr = explode(".", $fileFullName);
                if (in_array($file_attr[1], $fileType)) {
                    array_push($fileLists, $fileFullName);
                }
            }
        }
        return $fileLists;
    }

    /**
     * download the indicated files
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadErrorExcelFile(Request $request) {
        $file = $request->get('file');
        return response()->download(storage_path(self::$storage . self::$excelFileFolder . $file));
//        return response()->download(storage_path("excel\\$file"))->deleteFileAfterSend(true); //delete the file after successfully downloaded
    }


    protected function exportExcelPics($fileReader, $filePathName) {

        //define the excel reader obj
        $excel_Reader = new $fileReader;
        $worksheet = $excel_Reader->load($filePathName);

        //lists all the worksheet Name(s) in the excel file in array
        $sheets = $excel_Reader->listWorksheetNames($filePathName);


        foreach ($sheets as $sheet) {
            $worksheet->setActiveSheetIndexByName($sheet);
            $spreadsheet = $worksheet->getActiveSheet();
            $this->exportSheet($worksheet, $spreadsheet); //export the pics in the sheet
        }

        //trim and resize the pics
        $this->thumbObj->thumbNail($this->exportFolder, $this->exportFolder, self::$thumbWidth, self::$thumbHeight);

    }


    protected function exportSheet(Spreadsheet $worksheet, $spreadsheet) {
        foreach ($worksheet->getActiveSheet()->getDrawingCollection() as $row => $drawing) {

            //get the indicated col for name the exported pics
            $reg_no_call = self::$excelColForExportPicName . filter_var($drawing->getCoordinates(), FILTER_SANITIZE_NUMBER_INT);
            $picName = $spreadsheet->getCell($reg_no_call)->getValue() ? $spreadsheet->getCell($reg_no_call)->getValue() : "aaa" . rand(1, 10000);

            if ($drawing instanceof MemoryDrawing) {
                //this part is for xls
                ob_start();
                call_user_func(
                    $drawing->getRenderingFunction(),
                    $drawing->getImageResource()
                );
                $imageContents = ob_get_contents();
                ob_end_clean();


                switch ($drawing->getMimeType()) {
                    case MemoryDrawing::MIMETYPE_JPEG:
                        $picExt = "jpg";
                        break;
                    case MemoryDrawing::MIMETYPE_GIF:
                        $picExt = "gif";
                        break;
                    case MemoryDrawing::MIMETYPE_PNG:
                        $picExt = "png";
                        break;
                    default:
                        $picExt = "jpg";
                        break;
                }
            } else {
                //this part is for xlsx file

                $picExt = $drawing->getExtension() == "jpeg" ? "jpg" : $drawing->getExtension();

                $zipReader = fopen($drawing->getPath(), "r");
                $imageContents = "";
                while (!feof($zipReader)) {
                    $imageContents .= fread($zipReader, 1024);
                }
                fclose($zipReader);
            }

            //generate the pic
            file_put_contents($this->exportFolder . $picName . ".$picExt", $imageContents);

            //unify the pic type to jpg
            if ($picExt != "jpg" && $picExt != "jpeg") {
                $this->picTypeConvert($picName, $picExt);
            }

        }
    }

    /**
     * convert different pic type into jpg file type
     * @param $picName pic name with out extension
     * @param $picExt pic extension
     */
    public function picTypeConvert($picName, $picExt) {
        $create_from = "imagecreatefrom" . $picExt;
        $fromPic = $create_from($this->exportFolder . $picName . '.' . $picExt);

        imagejpeg($fromPic, $this->exportFolder . $picName . '.jpg');
        imagedestroy($fromPic);
        unlink($this->exportFolder . $picName . '.' . $picExt);

    }


}
