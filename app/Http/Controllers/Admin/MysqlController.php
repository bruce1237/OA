<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MysqlController extends Controller
{
    protected $excelData; //excel content in array
    protected $logoCategory;//logocategor in array format of $value=>key
    protected $logoComb;//logo name type
    protected $lastId; //the max id of the table la_fangchuzu id from database, used for increase insert
    protected $database; //database selection: Live or test
    protected $redisKey; //redisKey which hold the data of the excel
    protected $logoFolder;

    public function __construct(Request $request) {
        $this->database = $request->post('type');

        $user = "root";
        $pwd = "root";
        $db = "lubiao";

        //initialize the database connection
        switch ($request->post('type')) {

            case "live": //lubiaoip.com live mysql database server
                $host ="101.200.45.103";


                $this->dbBackup($host, $user,$pwd,$db,storage_path("excel/dbBackup/"));//backup the online database if case of anything goes wrong

                $this->mysql = new \mysqli($host,$user,$pwd,$db);
                break;
            default:
                $host = "127.0.0.1";
                //testing local mysql database server
                $this->mysql = new \mysqli($host, $user, $pwd,$db);
                break;
        }

        $this->redisKey = $request->post('redisKey');//get the redis key from url which holds all verified excel data

        $this->excelData = json_decode(Redis::get($this->redisKey), true); //convert the json to array from redis
        $this->logoCategory = json_decode(Redis::get('logoCategory'), true); //get logoCategory info from redis
        $this->logoComb = json_decode(Redis::get('logoComb'), true); //get logoComb info from redis
        $this->logoFolder = Redis::get('logoFolder');

        $init_query = "set global max_allowed_packet=1024*1024*16;";
        $this->mysql->query($init_query) or die(mysqli_error($this->mysql));

        //get last id from database
        $maxIdQuery = "SELECT max(id)as Max_Id from la_fangchuzu";

        $this->lastId = $this->mysql->query($maxIdQuery)->fetch_assoc()['Max_Id'];

    }

    public function index() {


        $insertResult = $this->insert(); //insert data into database

        $insertResult['database'] = $this->database; //put the database selection for front ajax
        $insertResult['redisKey'] = $this->redisKey;

        if (!$insertResult['status']) {
            //data insert into database fail
            return json_encode($insertResult);
        }

        //if inserted count is not equals to excelData Count
        if(!sizeof($this->excelData)==$insertResult['count']){
            $insertResult['status'] = false;
            $insertResult['message'] = "数据库插入的信息条数和Excel表格的信息条数，数目不符，请查看详情或联系管理员！";
            $insertResult['excel'] = sizeof($this->excelData);
        }

        //now inserted data records is equals to excel records

        $insertResult['message'] = "数据插入成功并且插入的数目和excel表格的条数一致";
        if($this->database=="live"){
            Redis::del($this->redisKey);
        }




        return json_encode($insertResult);


    }
    private function dbBackup($host,$user,$pwd,$db,$path){
        header("Content-Type: text/html; charset=utf-8");
        $sqlFile = $path.date("Y-m-d")."-lubiao.sql";
        $exec="D:/tools/myphp_www/PHPTutorial/MySQL/bin/mysqldump --host=$host -u $user -p$pwd $db > $sqlFile";
        exec($exec);

    }

    public function insert() {

        //insert into database
        $insert_id = $this->lastId;
        $fangchuzu_insert_values = "";
        $fangchuzu_data_insert_values = "";
        $content_insert_values = "";

        for ($i = 2; $i <= sizeof($this->excelData) + 1; $i++) {
            $insert_id++;

            $fangchuzu_insert_values .= "(
                '" . $insert_id . "',
                1,
                '" . mysqli_real_escape_string($this->mysql, $this->excelData[$i]['B']) . "',
                '" . $this->logoComb[$this->excelData[$i]['E']] . "',
                '" . $this->excelData[$i]['F'] . "',
                '" . $this->excelData[$i]['G'] . "',
                '" . $this->excelData[$i]['H'] . "',
                '" . $this->excelData[$i]['I'] . "',
                '" . $this->logoFolder."/".$this->excelData[$i]['A'] . ".jpg',
                '" . $this->excelData[$i]['J'] . "',
                '" . $this->excelData[$i]['K'] . "',
                '" . $this->excelData[$i]['L'] . "',
                '" . $this->excelData[$i]['M'] . "',
                '" . $this->excelData[$i]['N'] . "',
                '" . $this->excelData[$i]['O'] . "',
                '" . $this->excelData[$i]['P'] . "',
                '" . $this->excelData[$i]['Q'] . "',
                '" . $this->excelData[$i]['R'] . "',
                '" . $this->excelData[$i]['S'] . "',
                '" . $this->excelData[$i]['T'] . "',
                '" . $this->excelData[$i]['U'] . "',
                '" . $this->excelData[$i]['V'] . "',
                ''
            ),";

            $content_insert_values .= "(
            '31',
            '" . $this->logoCategory[$this->excelData[$i]['D']] . "',
            '0',
            '5',
            '" . $insert_id . "',
            '" . mysqli_real_escape_string($this->mysql, $this->excelData[$i]['B']) . "',
            '',
            '',
            '',
            '',
            '',
            '" . time() . "',
            '" . time() . "',
            '127.0.0.1',
            '1',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '0',
            '" . $this->logoFolder."/".$this->excelData[$i]['A'] . ".jpg',
            '" . $this->excelData[$i]['G'] . "',
            '0',
            '0',
            '0',
            '0',
            '1',
            '0',
            '0',
            '0',
            '" . $this->excelData[$i]['W'] . "',
            '" . $this->excelData[$i]['X'] . "',
            '" . $this->excelData[$i]['Y'] . "',
            '" . $this->excelData[$i]['Z'] . "',
            '1',
            '0'
            ),";

            $fangchuzu_data_insert_values .= "('" . $insert_id . "',1),";

        }

        //delete the last , from the string
        $fangchuzu_insert_values = substr($fangchuzu_insert_values, 0, -1);

        $fangchuzu_data_insert_values = substr($fangchuzu_data_insert_values, 0, -1);
        $content_insert_values = substr($content_insert_values, 0, -1);

        $fangchuzu_sql = "INSERT INTO `la_fangchuzu`(`id`, `siteid`, `title`, `map`, `shenfen`, `chuzuxingshi`, `fangxing`, `zujin`, `tupian`, `expire`, `csgg`, `csggtime`, `zcgg`, `zcggtime`, `hqzdtime`, `gjzctime`, `yxqtime`, `gysb`, `qi`, `zhong`, `cytp`, `cysm`, `sbconts`) VALUES " . $fangchuzu_insert_values;

        $content_sql = "INSERT INTO `la_content`(`moduleid`, `typeid`, `areaid`, `modelid`, `contentid`, `title`, `keywords`, `description`, `telephone`, `url`, `userid`, `inputtime`, `updatetime`, `ip`, `clicks`, `comments`, `istopic`, `istop`, `isfocus`, `islink`, `isvip`, `isauth`, `thumb`, `expire`, `e`, `eclick`, `ethumb`, `autofresh`, `status`, `isdeleted`, `ishidden`, `adminpost`, `contactuser`, `contacttel`, `contactaddress`, `contactqq`, `siteid`, `sid`) VALUES " . $content_insert_values;
        $fangchuzu_data_sql = "INSERT INTO `la_fangchuzu_data`(`mid`, `siteid`) VALUES " . $fangchuzu_data_insert_values;

        //原生，一次插入全部记录
        $error = "";

        if (!mysqli_query($this->mysql, $fangchuzu_sql)) {

            $error = mysqli_error($this->mysql) . "fangchuzu<br/>";
        }
        $fangchuzu_insert_count = mysqli_affected_rows($this->mysql);

        if (!mysqli_query($this->mysql, $fangchuzu_data_sql)) {

            $error .= mysqli_error($this->mysql) . "fangchuzu_data<br/>";
        }
        $fangchuzu_data_insert_count = mysqli_affected_rows($this->mysql);

        if (!mysqli_query($this->mysql, $content_sql)) {

            $error .= mysqli_error($this->mysql) . "content<br/>";
        }
        $content_insert_count = mysqli_affected_rows($this->mysql);


        if (empty($error) && $fangchuzu_insert_count !== 0 &&
            ($fangchuzu_insert_count == $fangchuzu_data_insert_count
                && $fangchuzu_data_insert_count == $content_insert_count
                && $fangchuzu_data_insert_count == $content_insert_count)) {
            $data = ['status' => true, 'count' => $fangchuzu_insert_count];
        } else {
            $data = ['status' => false, 'message'=>"数据库写入数据失败，请联系管理员！！",
                'count' => [
                'fangchuzu' => $fangchuzu_insert_count,
                'fangchuzu_data' => $fangchuzu_data_insert_count,
                'content' => $fangchuzu_insert_count
                ]
            ];
        }
        return $data;

    }
}
