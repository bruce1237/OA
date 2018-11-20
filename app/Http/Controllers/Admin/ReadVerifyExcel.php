<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;


class ReadVerifyExcel extends Controller
{


    //excel file type: *.xls or * xlsx
    protected $fileType = ['xls', 'xlsx'];

    //uploaded file original name
    protected $excelFileName = "";
    protected $excelPathName;

    //used for decided with excel reader class going to use
    protected $excelReadClass = "";
    protected $readerObj;

    //used for decided with excel writer class going to use
    protected $excelWriteClass = "";
    protected $excelWriter;

    //new file name after upload
    protected $filename = "";

    //the full pathName of the file uploaded
    protected $filePathName = "";

    //logo category
    protected $logoCate = [
        "第01类-化学原料" => 1000,
        "第02类-颜料油漆" => 1001,
        "第03类-日化用品" => 1002,
        "第04类-燃料油脂" => 1003,
        "第05类-医药" => 1004,
        "第06类-金属材料" => 1005,
        "第07类-机械设备" => 1006,
        "第08类-手工器械" => 1007,
        "第09类-科学仪器" => 1008,
        "第10类-医疗器械" => 1141,
        "第11类-灯具空调" => 1142,
        "第12类-运输工具" => 1143,
        "第13类-军火烟火" => 1144,
        "第14类-珠宝钟表" => 1145,
        "第15类-乐器" => 1146,
        "第16类-办公用品" => 1147,
        "第17类-橡胶制品" => 1148,
        "第18类-皮革皮具" => 1149,
        "第19类-建筑材料" => 1150,
        "第20类-家具" => 1151,
        "第21类-厨房洁具" => 1152,
        "第22类-绳网袋篷" => 1153,
        "第23类-纱线丝" => 1154,
        "第24类-布料床单" => 1155,
        "第25类-服装鞋帽" => 1156,
        "第26类-钮扣拉链" => 1157,
        "第27类-地毯席垫" => 1158,
        "第28类-健身器材" => 1159,
        "第29类-食品" => 1160,
        "第30类-方便食品" => 1161,
        "第31类-饲料种籽" => 1162,
        "第32类-啤酒饮料" => 1163,
        "第33类-酒" => 1164,
        "第34类-烟草烟具" => 1165,
        "第35类-广告销售" => 1166,
        "第36类-金融物管" => 1167,
        "第37类-建筑修理" => 1168,
        "第38类-通讯服务" => 1169,
        "第39类-运输贮藏" => 1170,
        "第40类-材料加工" => 1171,
        "第41类-教育娱乐" => 1172,
        "第42类-网站服务" => 1173,
        "第43类-餐饮住宿" => 1174,
        "第44类-医疗园艺" => 1175,
        "第45类-社会服务" => 1176,
    ];
    protected $logoCateSimple = [
        "01" => 1000,
        "02" => 1001,
        "03" => 1002,
        "04" => 1003,
        "05" => 1004,
        "06" => 1005,
        "07" => 1006,
        "08" => 1007,
        "09" => 1008,
        "10" => 1141,
        "11" => 1142,
        "12" => 1143,
        "13" => 1144,
        "14" => 1145,
        "15" => 1146,
        "16" => 1147,
        "17" => 1148,
        "18" => 1149,
        "19" => 1150,
        "20" => 1151,
        "21" => 1152,
        "22" => 1153,
        "23" => 1154,
        "24" => 1155,
        "25" => 1156,
        "26" => 1157,
        "27" => 1158,
        "28" => 1159,
        "29" => 1160,
        "30" => 1161,
        "31" => 1162,
        "32" => 1163,
        "33" => 1164,
        "34" => 1165,
        "35" => 1166,
        "36" => 1167,
        "37" => 1168,
        "38" => 1169,
        "39" => 1170,
        "40" => 1171,
        "41" => 1172,
        "42" => 1173,
        "43" => 1174,
        "44" => 1175,
        "45" => 1176,
    ];

    //logo name sting combination type
    protected $logoComb = [
        "中文" => 1,
        "中文+英文" => 2,
        "中文+拼音" => 3,
        "中文+英文+图形" => 4,
        "图形" => 5,
        "英文+图形" => 6,
        "英文+数字" => 7,
        "英文" => 9,
        "中文+数字" => 10,
        "中文+图形" => 11,
    ];

    //trade type
    protected $tradeType = ["转让", "授权"];

    //special characters in the excel for data format which will be replace to unify
    protected $specialChar = ["年", "月", "日", "/", "-", "至", ".", "--"];

    //the char used to unify date format
    protected $specifiedChar = "-";

    //default date format
    protected $dateFormat = "Y/m/d";

    //state the start & end date for validate the logo valid date
    protected $lawStartDate = "1950/01/01";
    protected $lawEndDate = "3000/01/01";


    //array to store the content of the excel content
    protected $excelArray = array();


    //array of the excel cell with misplaced information
    protected $excelWarningCell = array();


    //SET UP header of the excel going to export
    protected $excelHeader = [
        "A" => "商标ID",
        "B" => "商标名称",
        "C" => "商标图片",
        "D" => "商标分类",
        "E" => "商标类型",
        "F" => "交易类型",
        "G" => "电商适用",
        "H" => "字符数量",
        "I" => "注册年限",
        "J" => "适用项目",
        "K" => "初审公告号",
        "L" => "初审公告日期",
        "M" => "注册公告期号",
        "N" => "注册公告日期",
        "O" => "后期指定日期",
        "P" => "国际注册日期",
        "Q" => "优先权日期",
        "R" => "是否共用商标",
        "S" => "专用权期限起始",
        "T" => "专用权期限结束",
        "U" => "创意图片",
        "V" => "创意说明",
        "W" => "商标联系人",
        "X" => "联系电话",
        "Y" => "价格",
        "Z" => "注册号",

    ];


    //variable to store the logo start&end time for later to write into excel file
    protected $excelLogoStart = "string";
    protected $excelLogoEnd = "string";

    //logo name length
    protected $logoLength = "";

    //logo picture dir
    protected $logoPath = "";
    protected $logoFolder = "";

    //array of all the pics uploaded or readed from the local disk
    protected $logoPics = [];


    public function __construct($FolderPath, $FileFullName) {


        $fileAttr = explode(".", $FileFullName);

        $this->excelFileName = $FileFullName; //excel file full name
        $this->excelPathName = $FolderPath . $FileFullName; //

        $this->logoPath = $FolderPath . $fileAttr[0] . "\\"; //for pics upload
        $this->logoFolder = $fileAttr[0];

    }

    public function __destruct() {
        // remove uploaded file

//        unlink( $this->filePathName);
    }

    public function init($excelReadClass) {

        $this->excelReadClass = "\\".$excelReadClass;




        $this->readerObj = new $this->excelReadClass;//define reader obj


        $this->excelWriteClass = str_replace("Reader", "Writer", $excelReadClass); //define writer class

        $excel_data = $this->read_excel($this->excelPathName); //put the excel content into 2D-kv-array


        $this->verify($excel_data);//verify the excel data with rules

        if ($this->excelWarningCell) {
            if ($this->exportExcel($this->excelHeader, $this->excelArray, $this->excelPathName, $this->excelWarningCell)) {
                $data = ['status' => true, "code" => 201, "message" => "表格文件有问题，请下载后重新修改", "file" => $this->excelFileName];

            } else {
                $data = ['status' => true, "code" => 400, "message" => "尝试生成表格文件失败，请联系管理员", "file" => $this->excelFileName];
            }

        } else {

            // "NO ERROR";
            //step 1 put no error excel array to redis
            $redis_unique_name = uniqid();
            Redis::set($redis_unique_name, json_encode($this->excelArray));
            Redis::set('logoCategory', json_encode($this->logoCate));
            Redis::set('logoComb', json_encode($this->logoComb));
            Redis::set('logoFolder', $this->logoFolder);
//            Redis::set('ThumbFolder', "logo_pics\\thumb" . $this->logoFolder);

            $data = ['status' => true, "code" => 200, "message" => "excel文件没有错误", "redisKey" => $redis_unique_name];


        }
        return $data;
    }


    /**
     * use phpSpreadsheet to read excel
     * @param $fileType xls, xlsx Excel file extension
     * @param $filePathName the location  of excel file in the hardisk
     * @return array|bool or false the content of the excel in array
     */
    public function read_excel($filePathName) {


        $spreadsheet = $this->readerObj->load($filePathName); //load the excel

        $worksheet = $spreadsheet->getActiveSheet();//get the worksheet ??? still have question

        // Get the highest row number and column letter referenced in the worksheet
        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'

        // Increment the highest column letter
        $highestColumn++; //make sure to read the last col

        $data = array();

        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = 'A'; $col != $highestColumn; $col++) {

                if ($worksheet->getCell("B" . $row)->getValue() != "") {
                    $data[$row][$col] = $worksheet->getCell($col . $row)
                        ->getValue();
                }
            }
        }


        if (null !== $data) {
            return $data;
        } else {
            return false;
        }
    }


    /**
     * info: analysis values of the array
     * @param $array
     */
    public function verify($array) {
        foreach ($array as $key => $value) {
            foreach ($value as $k => $v) {
                $this->excelArray[$key][$k] = $this->verifyModify($k, trim($v), $key);
            }
        }
        return $this->excelArray;
    }

    /**
     * verify and change the value of the input array
     * format for verification is: array[$index][$standard]=>[$value]
     * @param $standard
     * @param $value
     * @param $index
     * @return string return the value for corrention
     */
    private function verifyModify($standard, $value, $index) {
        $result = "abc";
        $errorMsg = "";
        $excelError = false;
        $rowNo = $index;
        switch ($standard) {

            //商标注册号 default null
            case "A":
                //用注册号来确认对应商品的图片是否存在
                if (!file_exists($this->logoPath . $value . ".jpg")) {
                    $excelError = true;
                    array_push($this->excelWarningCell, $standard . $rowNo);
                    $errorMsg = "找不到图片！";
                }
                $result = $errorMsg . $value;

                break;
            //商标名称
            case "B":
                //剔除商标中的英文和+./_-
                $length = mb_strlen($value);

                $trimed_value = preg_replace('|[0-9a-zA-Z/+._ -]+|', '', $value);

                if ($trimed_value == null) {
                    $logo_length = $length;
                } else {
                    $logo_length = mb_strlen($trimed_value);
                }

                if ($logo_length > 0 && $logo_length <= 6) {
                    $this->logoLength = $logo_length . "个";
                } elseif ($logo_length > 6) {
                    $this->logoLength = "6个以上";
                } else {
                    $this->logoLength = "商标名称不符合标准";
                    $excelError = true;
                    array_push($this->excelWarningCell, $standard . $rowNo);
                }
                $result = $value;

                break;
            //商标图片
            case "C":
                $result=$value;
                break;
            //商标分类
            case "D":

                if (in_array($value, array_keys($this->logoCate))) {
                    $result = $value;
                } else {
                    //if not in the full category then try the logoCateSimple
                    if (preg_match('/\d+/', $value, $match_result)) {
                        //将类别转化为两位数，不足补零
                        $catNo = sprintf("%02d", $match_result[0]);
                        if (in_array($catNo, array_flip($this->logoCateSimple))) {
                            $catCode = $this->logoCateSimple[$catNo];
                            $filp_arry = array_flip($this->logoCate);
                            $catName = $filp_arry[$catCode];
                            if ($catName) {
                                $result = $catName;
                            }
                        } else {
                            $result = $value . "商标分类不存在或错误";//excel format with error warning
                            $excelError = true;
                        }

                    } else {
                        $result = "商标分类不存在或错误";//excel format with error warning
                        $excelError = true;
                    }
                }
                break;
            //商标类型
            case "E":
                if (in_array($value, array_keys($this->logoComb))) {
                    $result = $value;
                } else {

                    if (preg_match('/\d+/', $value, $typDigit)) {
                        if (in_array($typDigit[0], $this->logoComb)) {
//                          echo $typDigit[0]."<br />";
                            $new_arr = array_flip($this->logoComb);
                            $result = $new_arr[$typDigit[0]];
                        }
                    } else {
                        $result = "商标类型不合格";
                        $excelError = true;
                    }
                }
                break;
            //交易类型
            case "F":
                if (in_array($value, $this->tradeType)) {
                    $result = $value;
                } else {
                    $result = "交易类型不合法";
                    $excelError = true;
                }

                break;
            //电商适用
            case "G":
                if ($value != null) {
                    $value = str_replace(" ", ",", $value);
                    $result = $value;
                } else {
                    $result = "没有信息";
                    $excelError = true;
                }
                break;
            //字符数量
            case "H":
                $result = $this->logoLength;
                break;
            //注册年限
            case "I":
                //1.剔除空格
                $value = str_replace([" ", "\n"], "", $value);
                //2.转换特殊字符为指定字符
                $value = str_replace($this->specialChar, $this->specifiedChar, $value);
                //3.转换为数组
//                //将字符串切割为两个日期， 感觉健壮性不够，弃用
//                $end_date_string = mb_split("-",$value,4)[3];//将value用-拆分为4个数组，enddate就是最后一个数组
//                $start_date_string = str_replace("-".$end_date_string,"",$value);//在现有的字符串中删除enddate和enddate前面的-就是开始日期了
//
//
//                //格式化起止日期为指定的日期格式
//                $start_date =Date($this->dateFormat,strtotime($start_date_string));
//                $end_date =  Date($this->dateFormat,strtotime($end_date_string));


                if (substr($value, -1) == "-") {
                    $value = substr($value, 0, strlen($value) - 1);
                }

                $value_arr = explode("-", $value);

                //4.查看数组的长度来确认日期格式是否合法
                if (sizeof($value_arr) == 6) {
                    //转化为起止日期
                    //state the start & end date for validate the logo valid date
//                     $lawStartDate=date("Y/m/d",strtotime("1950-01-01"));
//                     $lawEndDate = date("Y/m/d",strtotime("3000-01-01"));

                    $start_date = Date($this->dateFormat, strtotime($value_arr[0] . "/" . $value_arr[1] . "/" . $value_arr[2]));
                    $end_date = Date($this->dateFormat, strtotime($value_arr[3] . "/" . $value_arr[4] . "/" . $value_arr[5]));

                    if ($start_date > $this->lawStartDate && $start_date < $this->lawEndDate
                        && $end_date > $this->lawStartDate && $end_date < $this->lawEndDate
                        && $end_date > $start_date) {


                        //dates valid
                        $result = $start_date . "-" . $end_date;
                        $this->excelLogoStart = $start_date;
                        $this->excelLogoEnd = $end_date;
                        $excelError = false;
                    } else {
                        //date invalide
                        $result = $value . "日期读取错误：无法读取";
                        $this->excelLogoStart = false;
                        $this->excelLogoEnd = false;
                        $excelError = true;
                    }
                }
                break;
            //适用项目
            case "J":
                $result = $value;
                break;
            //初审公告号
            case "K":
                $result = $value;
                break;
            //初审公告日期
            case "L":
                $result = $value;
                break;
            //注册公告期号
            case "M":
                $result = $value;
                break;
            //注册公告日期
            case "N":
                $result = $value;
                break;
            //后期指定日期
            case "O":
                $result = $value;
                break;
            //国际注册日期
            case "P":
                $result = $value;
                break;
            //优先权日期
            case "Q":
                $result = $value;
                break;
            //是否共用商标
            case "R":
                if (in_array($value, ['是', '否'])) {
                    $result = $value;
                } else {
                    $result = "不符合标准（是，否）";
                    $excelError = true;
                }
                break;
            //专用权期限起始
            case "S":
                if ($this->excelLogoStart) {
                    $result = $this->excelLogoStart;
                } else {
                    $result = "日期不合法，请检查注册年限日期格式";
                    $excelError = true;
                }


                break;
            //专用权期限结束
            case "T":
                if ($this->excelLogoEnd) {
                    $result = $this->excelLogoEnd;
                } else {
                    $result = "日期不合法，请检查注册年限日期格式";
                    $excelError = true;
                }
                break;
            //创意图片
            case "U":
                $result = $value;
                break;
            //创意说明
            case "V":
                $result = $value;
                break;
            //商标联系人
            case "W":
                $result = $value;
                break;
            //联系电话
            case "X":
                $result = $value;
                break;
            //价格
            case "Y":
                $new_value = (float)str_replace("万", "", $value);
                if ($new_value > 0 && $new_value < 100) {
                    $result = $new_value * 10000;
                }
//                elseif($new_value==0){
//                    $result = $value."无法读取价格";
//                    $excelError=true;
//                }
                else {
                    $result = $value;
                }
                break;
            //注册号
            case "Z":
                $result = $value;
                break;
            default:
                $result = "unKnow";
                $excelError = true;
                break;
        }
        if ($excelError) {
            array_push($this->excelWarningCell, $standard . $rowNo);
        }

        return $result;
    }


    private function exportExcel($excel_header, $excel_data, $file_name, $excelWarningCell) {


        if (null !== $excel_data) {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

            foreach ($excelWarningCell as $key => $value) {
                //highlight the warning cell
                $spreadsheet->getActiveSheet()->getStyle($value)->getFont()->getColor()->setARGB(Color::COLOR_RED);
            }

            //write excel header
            $spreadsheet->getActiveSheet()->fromArray($excel_header, null, 'A1');

            //write excel content data
            $spreadsheet->getActiveSheet()->fromArray($excel_data, null, 'A2');

            $writer = new $this->excelWriteClass($spreadsheet);

            $writer->save($file_name);//保存到服务器的指定目录

            return file_exists($this->excelPathName);


        }


    }


}
