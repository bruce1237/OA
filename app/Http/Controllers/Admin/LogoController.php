<?php

namespace App\Http\Controllers\Admin;

use App\Model\Logo;
use App\Model\LogoFlow;
use App\Model\LogoGoods;
use App\Model\LogoSeller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


class LogoController extends Controller
{
    //
    protected $pageSize = 40; //show how many records per page

    protected $tumbWidth = 350;
    protected $tumbHeight = 154;

    protected $tmpExcelFile;
    protected $tmpExcelPicFolder;

    protected $redisUniqueKey;

    protected $excelReaderClass;
    protected $excelHeader = [
        "注册号",
        "商标分类",
        "商标名称",
        "商标图片",
        "专用权期限起始",
        "专用权期限结束",
        "商标名字种类",
        "字符数量",
        "交易类型",
        "商标类型",
        "商标联系人",
        "联系电话",
        "手机",
        "微信",
        "地址",
        "邮编",
        "价格",
        "是否共用商标",
        "电商适用",
        "适用项目组",
        "适用项目名称",
        "注册公告期号",
        "注册公告日期",
        "商标代理",
        "申请日期",
        "申请人中文",
        "申请人英文",
        "申请人证件号",
        "共有申请人",
        "地址中文",
        "地址英文",
        "初审公告日期",
        "初审公告号",
        "国际注册日期",
        "后期指定日期",
        "优先权日期",
        "指定颜色",
        "创意图片",
        "创意说明",
        "流程"

    ];


    public function test(Request $request) {
//      Redis::set("ABC","name");
//      echo Redis::get("ABC");

//        $request = app('request')->name;
//        dd($request);
//        $aa = Request()->post('name');
//        echo $aa;exit;


        echo Auth::guard('admin')->user()->email;
    }

    public function __construct() {
        //use admin's eail as the unique key for redis
//        $this->redisUniqueKey = Auth::guard('admin')->user()->email;
    }


    public function __destruct() {
        // TODO: Implement __destruct() method.
        Storage::disk('logo_excel')->delete($this->tmpExcelFile);
    }

    public function index() {
        $this->redisUniqueKey = Auth::guard('admin')->user()->email;
        $category = $this->logoCateList();
        $logoLength = $this->logoLengthList();
        $logoType = $this->logoTypeList();
        $logoSellers = $this->logoSellerList();
        return view('admin/logo/logoManage', ['category' => $category, 'logoLength' => $logoLength, 'logoType' => $logoType, 'logoSellers' => $logoSellers, 'pageSize' => $this->pageSize]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function searchLogo(Request $request) {


        if (!$request->isMethod('post')) {
            $data['status'] = false;
            $data['message'] = "请求不合法";
        }

        $this->validate($request,
            [//rule
                'logoName' => 'required'
            ],
            [//message
                'required' => '必填内容，不可以为空',
            ],
            [//attribute
                'logoName' => '商标名字',
            ]
        );


        $searchResult = $this->logoSearchMysql($request);


        if ($searchResult) {
            $data['status'] = true;
            $data['message'] = $searchResult;
            $data['seller_list'] = $this->logoSellerList();
        } else {
            $data['status'] = false;
            $data['message'] = "无结果！";
        }

//        dd($data['message']);


        return json_encode($data);//ajax method hold up by the ajax paginate with 302 auth error
    }

    public function deleteLogo(Request $request) {
        $logo = Logo::find($request->post('id'));
        LogoFlow::destroy($logo->flow_id);
        LogoGoods::destroy($logo->goods_id);
        $logo->delete();
        $data = ['status' => true, 'message' => '删除成功'];
        return json_encode($data);

    }

    public function updateLogo(Request $request) {

        $logoInfo = json_decode($request->post('logo'), true);
        $goodsInfo = json_decode($request->post('goods'), true);
        $flows = json_decode($request->post('flows'), true);
        $seller = json_decode($request->post('seller'), true);

        foreach ($logoInfo as $key => $value) {

            $logoInfo[$key] = $value ? $value : null;


        }


        $data = ['status' => true, 'message' => "更新成功"];
        if ($request->file('logo_pic')) {
            Storage::disk('logo_images')->delete($logoInfo['logo_img']);

            $logo_img_name = $logoInfo['id'] . "." . $request->file('logo_pic')->getClientOriginalExtension();

            $new_logo_image = $request->file('logo_pic')->storeAs($logoInfo['int_cls'], 'logo_images');

            $logoInfo['logo_img'] = $new_logo_image;


        } else {
            unset($logoInfo['logo_img']);
        }

        if ($newSellerObj = LogoSeller::updateOrCreate($seller)) {
            $logoInfo['seller_id'] = $newSellerObj->id;
        } else {
            $data['status'] = false;
            $data['message'] = "卖家更新失败";
            return json_encode($data);
        }


        if (!Logo::where('id', '=', $logoInfo['id'])->update($logoInfo)) {
            $data['status'] = false;
            $data['message'] = "商标更新失败";
            return json_encode($data);
        }


        if (!LogoGoods::where('id', '=', $logoInfo['goods_id'])->update($goodsInfo)) {
            $data['status'] = false;
            $data['message'] = "适用商品更新失败";
            return json_encode($data);
        }
//        dd(LogoFlow::where('id', '=', $logoInfo['flow_id'])->toSql());

        if (!LogoFlow::where('id', '=', $logoInfo['flow_id'])->update($flows)) {
            $data['status'] = false;
            $data['message'] = "流程更新失败";
            return json_encode($data);
        }

        return json_encode($data);


    }

    public function newLogo(Request $request) {
        $logoPic = $request->file('logo_pic');

        $logoInfo = json_decode($request->post('logo'), true);
        $goodsInfo = json_decode($request->post('goods'), true);
        $flowsInfo = json_decode($request->post('flows'), true);
        $sellerInfo = json_decode($request->post('seller'), true);

        $logoInfo['id'] = Logo::all()->max('id') + 1;
        $logoInfo['goods_id'] = LogoGoods::all()->max('id') + 1;
        $logoInfo['flow_id'] = LogoFlow::all()->max('id') + 1;


        $logo_img_name = $logoInfo['id'] . "." . $logoPic->getClientOriginalExtension();

        $new_logo_image = $request->file('logo_pic')->storeAs(md5(uniqid()), $logo_img_name, 'logo_images');

        $this->picResize(public_path("images/logo_images/$new_logo_image"));

        $logoInfo['logo_img'] = $new_logo_image;

        $sellerObj = LogoSeller::updateOrCreate($sellerInfo);
        $logoInfo['seller_id'] = $sellerObj->id;

        $goodsObj = LogoGoods::create($goodsInfo);
        $logoInfo['goods_id'] = $goodsObj->id;

        $flowsObj = LogoFlow::create($flowsInfo);
        $logoInfo['flow_id'] = $flowsObj->id;

        foreach ($logoInfo as $key => $value) {
            $logoInfo[$key] = $value ? $value : null;

        }


        Logo::create($logoInfo);

        $data['status'] = true;
        $data['message'] = "商标添加成功";
        return json_encode($data);

    }


    public function importLogo(Request $request) {
        $data = array();

        if (!$request->file('logo_excel')) {
            $data['status'] = false;
            $data['message'] = "请选择您要上传的文件";
            return $data;
        }


        $this->tmpExcelPicFolder = $tempLogoPicFolder = date("y-m-d H-i-s");// uniqid();

        //read Excel
        $excel_array = $this->readExcel($request->file('logo_excel'));


        $duplicateExcelRows = $this->excelArrayUniqueCheck($excel_array);



        $tmpExcelFileName = "\NeedsModify." . $request->file('logo_excel')->getClientOriginalExtension();
        $this->tmpExcelFile = public_path('excel') . $tmpExcelFileName;
        if ($duplicateExcelRows) {
            $this->exportExcel($this->excelHeader, $excel_array, $duplicateExcelRows, $this->tmpExcelFile);

            //there are some duplicated values in the excel file
            $data['status'] = false;
            $data['message'] = "Excel 文档中,【注册号+分类】有重复，请<a href=\"' . url(\"/excel/$tmpExcelFileName\") . '\">下载文档</a>，后移除重复项再上传";
            Storage::disk('logo_images')->deleteDirectory($tempLogoPicFolder);
            return json_encode($data);
        }


        $logoPicArr = $this->readPic($request->file('logo_excel'));


        $logoInfo = $this->excelValidation($excel_array, $logoPicArr);

        if ($logoInfo['excelWarning']) {
            $this->exportExcel($this->excelHeader, $logoInfo['excelArray'], $logoInfo['excelWarning'], $this->tmpExcelFile);
            $data['status'] = false;
            $data['message'] = 'Excel文档有不正确的地方，请<a href="' . url("/excel/$tmpExcelFileName") . '">下载文档</a>， 然后修改后再上传！';
            $data['download_link'] = url('excel/') . "\NeedsModify." . $request->file('logo_excel')->getClientOriginalExtension();
            Storage::disk('logo_images')->deleteDirectory($tempLogoPicFolder);
            return json_encode($data);
        }

        //the excel file is perfect, get ready to update the database and ES]
        $databaseWillBe = $this->databaseUpdateCheck($logoInfo['logoInfoArray']);


        if (Redis::exists('logoPicTmpFolder' . $this->redisUniqueKey)) {
            Storage::disk('logo_images')->deleteDirectory(Redis::get('logoPicTmpFolder'));
        }


        Redis::set('logoInfo' . $this->redisUniqueKey, json_encode($logoInfo));
        Redis::set('logoPicTmpFolder' . $this->redisUniqueKey, $this->tmpExcelPicFolder);

        $data['status'] = true;

        $updatelogos = '<div class="row"><div class="col-3 col-sm-4">' . implode('</div><div class="col-3 col-sm-4">', $databaseWillBe['updateReg']) . '</div></div>';
        $insertlogos = '<div class="row"><div class="col-3 col-sm-4">' . implode('</div><div class="col-3 col-sm-4">', $databaseWillBe['insertReg']) . '</div></div>';

        $data['message'] = "<h4> 更新：" . count($databaseWillBe['updateReg']) . " 个商标</h4>" . $updatelogos . "<h4>新增：" . count($databaseWillBe['insertReg']) . " 个商标</h4>" . $insertlogos;
        $data['updateReg'] = $databaseWillBe['updateReg'];
        $data['insertReg'] = $databaseWillBe['insertReg'];
        return json_encode($data);


//        $data['excelArray'] = $data['excelWarning'] = $data['logoInfoArray'];
//        $logoInfo = $this->excelToLogoArray($excel_array, $picNameArray);

    }


    public function updateDatabase(Request $request) {
        $data = array();

        if (!Redis::exists('logoPicTmpFolder' . $this->redisUniqueKey) || !Redis::exists('logoInfo' . $this->redisUniqueKey)) {
            $data['status'] = false;
            $data['message'] = '超时， 请重新上传';
            return json_encode($data);
        }
        $logoInfo = json_decode(Redis::get('logoInfo' . $this->redisUniqueKey), true);
        $logoPicTmpFolder = Redis::get('logoPicTmpFolder' . $this->redisUniqueKey);

        if ($request->post('data')) {
            $importedLogosIds = $this->databaseImport($logoInfo['logoInfoArray']);


            $data['status'] = true;
            $data['message'] = "商标上传成功, 共计：<br /> 更新：" . count($importedLogosIds['updated']) . " 个商标<br />新增：" . count($importedLogosIds['created']) . " 个商标";

            $importedLogosIds = array_merge($importedLogosIds['updated'], $importedLogosIds['created']);
            $this->updateES($importedLogosIds);
        } else {
            //remove uploaded pics and clear redis cache
            //remove pics

            Storage::disk('logo_images')->deleteDirectory($logoPicTmpFolder);

            $data['status'] = false;
            $data['message'] = "放弃商标导入";

        }

        //clear redis
        Redis::del('logoInfo' . $this->redisUniqueKey);
        Redis::del('logoPicTmpFolder' . $this->redisUniqueKey);

        return json_encode($data);


    }


    private function databaseUpdateCheck(array $logoInfo) {
        $affected['updateReg'] = $affected['insertReg'] = array();
        foreach ($logoInfo as $record) {
            if (Logo::where('reg_no', '=', $record['reg_no'])->where('int_cls', '=', $record['int_cls'])->exists()) {
                //商标已存在
                $affected['updateReg'][] = Logo::where('reg_no', '=', $record['reg_no'])->where('int_cls', '=', $record['int_cls'])->select('reg_no')->first()->reg_no;
            } else {
                $affected['insertReg'][] = $record['reg_no'];
            }
        }

        return $affected;

    }

    private function excelArrayUniqueCheck($excelArray) {
        //chek the unique of reg_no excel[A] and int_cls[B]
        $duplicateExcelRows = array();

        $collection = collect($excelArray);

        $unique = $collection->unique(function ($item) {
            return $item['A'] . $item['B'];
        });

        $unique_array = $unique->values()->all(); //unique_array of $excel_array


        if (sizeof($excelArray) - sizeof($unique_array)) {

            $duplicateExcelRows = [];
            foreach ($excelArray as $key => $value) {
                if (!in_array($value, $unique_array)) {
                    $duplicateExcelRows[] = "A" . $key;
                }
            }
        }

        return $duplicateExcelRows;


    }

    private function updateES(array $logoIds) {
        $client = ClientBuilder::create()->build();
        try {
            $this->initEs($client);

        } catch (\Exception $e) {
            $indexExist = json_decode($e->getMessage());
        }


        $logos = Logo::
        leftJoin('logo_cates', 'logos.int_cls', '=', 'logo_cates.id')
            ->leftJoin('logo_flows', 'logos.flow_id', '=', 'logo_flows.id')
            ->leftJoin('logo_goods', 'logos.goods_id', '=', 'logo_goods.id')
            ->leftJoin('logo_length', 'logos.logo_length', '=', 'logo_length.id')
            ->leftJoin('logo_sellers', 'logos.seller_id', '=', 'logo_sellers.id')
            ->leftJoin('logo_type', 'logos.name_type', '=', 'logo_type.id')
            ->whereIn('logos.id', $logoIds)
            ->select(
                'logos.*',
                'logo_cates.category_name',
                'logo_flows.flow_data',
                'logo_goods.goods_name', 'logo_goods.goods_code',
                'logo_length.name_length',
                'logo_sellers.name', 'logo_sellers.tel', 'logo_sellers.wx', 'logo_sellers.mobile', 'logo_sellers.address', 'logo_sellers.post_code',
                'logo_type.type'
            )
            ->get();

        foreach ($logos as $logo) {
            $params = [
                'index' => config('essearch.es_index'),
                'type' => config('essearch.es_type'),
                'id' => $logo->id,
                'body' => $logo,
            ];

            $client->index($params);
        }


    }

    protected function initEs($client) {


        $params = [
            'index' => config('essearch.es_index'),
            'body' => [
                'settings' => [
                    'number_of_shards' => 5,
                    'number_of_replicas' => 1
                ]
            ]
        ];


        $client->indices()->create($params);

    }

    private function databaseImport($excelArray) {
        $logo_count['created'] = $logo_count['updated'] = array();

        foreach ($excelArray as $record) {


            $seller_id = LogoSeller::updateOrCreate([
                    'name' => $record['name'],
                    'tel' => $record['tel'],
                    'mobile' => $record['mobile'],
                    'wx' => $record['wx'],
                    'address' => $record['address'],
                    'post_code' => $record['post_code']]
            )->id;
            $record['seller_id'] = $seller_id;


            //check if the logo exist
            if (Logo::where('reg_no', '=', $record['reg_no'])->where('int_cls', '=', $record['int_cls'])->exists()) {
                //商标已存在
                $oldLogoInfo = Logo::where('reg_no', '=', $record['reg_no'])->where('int_cls', '=', $record['int_cls'])->select('logo_img', 'goods_id', 'flow_id')->first();


                if ($oldLogoInfo->logo_img) {


                    Storage::disk('logo_images')->delete($oldLogoInfo->logo_img);

                    $oldLogoFolder = explode("/", $oldLogoInfo->logo_img)[0];

                    if (!Storage::disk('logo_images')->allFiles($oldLogoFolder)) {
                        //folder is empty
                        Storage::disk('logo_images')->deleteDirectory($oldLogoFolder);
                    }

                }

                $goods_id = LogoGoods::where('id', '=', $oldLogoInfo->goods_id)->update(['goods_name' => $record['goods_name'], 'goods_code' => $record['goods_code']]);

                $flow_id = LogoFlow::where('id', '=', $oldLogoInfo->flow_id)->update(['flow_data' => $record['flow_data']]);


                $record['goods_id'] = $oldLogoInfo->goods_id;
                $record['flow_id'] = $oldLogoInfo->flow_id;

                unset(
                    $record['name'],
                    $record['tel'],
                    $record['mobile'],
                    $record['wx'],
                    $record['address'],
                    $record['post_code'],
                    $record['goods_name'],
                    $record['goods_code'],
                    $record['flow_data']

                );


                Logo::where('reg_no', '=', $record['reg_no'])->where('int_cls', '=', $record['int_cls'])->update($record);

                $logo_count['updated'][] = Logo::where('reg_no', '=', $record['reg_no'])->where('int_cls', '=', $record['int_cls'])->first()->id;

            } else {

                $goods_id = LogoGoods::create($record)->id;
                $flow_id = LogoFlow::create($record)->id;

                $record['seller_id'] = $seller_id;
                $record['goods_id'] = $goods_id;
                $record['flow_id'] = $flow_id;

                unset(
                    $record['name'],
                    $record['tel'],
                    $record['mobile'],
                    $record['wx'],
                    $record['address'],
                    $record['post_code'],
                    $record['goods_name'],
                    $record['goods_code'],
                    $record['flow_data']

                );

                $logo_count['created'][] = Logo::updateOrCreate($record)->id;
            }


        }


        return $logo_count;

    }

    private function exportExcel($excelHeader, $excelContent, $excelWarningCellList, $excelFileName) {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription(
                "Test document for Office 2007 XLSX, generated using PHP classes."
            )
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        foreach ($excelWarningCellList as $key => $value) {
            $spreadsheet->getActiveSheet()->getStyle($value)->getFont()->getColor()->setARGB(Color::COLOR_RED);
        }


        $spreadsheet->getActiveSheet()->fromArray($excelHeader, null, 'A1');

        $spreadsheet->getActiveSheet()->fromArray($excelContent, null, 'A2');


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);


        $writer->save($excelFileName);

//        return $download_link  =$excelFileName;


    }


    private function excelValidation($excelArray, $picsArray) {


        $data = array();
        $excelWarning = array();
        $logoInfoArray = array();
        $logoComb = [
            "中文" => 1,
            "中文+英文" => 2,
            "中文+拼音" => 3,
            "中文+英文+图形" => 4,
            "图形" => 5,
            "英文+图形" => 6,
            "英文+数字" => 7,
            "英文" => 8,
            "中文+数字" => 9,
            "中文+图形" => 10,
        ];
        $tradeType = ["转让", "授权"];
        $category = ["一般", "特殊", "集体", "证明"];
        $logoNameLength = [
            1 => '1个',
            2 => '2个',
            3 => '3个',
            4 => '4个',
            5 => '5个',
            6 => '6个',
            7 => '6个以上'
        ];

        foreach ($excelArray as $row => $record) {
            foreach ($record as $col => $value) {
                switch ($col) {
                    case "A": //注册号
                        $logoInfoArray[$row]['reg_no'] = (string)$value;
                        break;
                    case "B": //商标分类
                        $value = str_replace("-", "", $value);
                        $value = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                        if ($excelArray[$row][$col] = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 45]])) {
                            $logoInfoArray[$row]['int_cls'] = $value;
                        } else {
                            $excelArray[$row][$col] = "商标分类格式不正确 " . $value;
                            $excelWarning[] = $col . $row;
                        }

                        break;
                    case "C": //商标名称
                        $logoInfoArray[$row]['logo_name'] = $value;
                        break;
                    case "D": //商标图片
                        $logo_reg = $excelArray[$row]['A'];


                        if (array_key_exists((string)$logo_reg, $picsArray)) {
                            $logoInfoArray[$row]['logo_img'] = $excelArray[$row]['D'] = $this->tmpExcelPicFolder . "/" . $picsArray[$logo_reg];
                        } else {
                            $excelArray[$row][$col] = "图片不存在 " . $value;
                            $excelWarning[] = $col . $row;
                        }

                        break;
                    case "E": //专用权期限起始
                        if (gettype($value) == "string") {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                        } else {
                            $value = intval(($value - 25569) * 3600 * 24);
                            $value = date("Y-m-d", $value);
                        }


                        if (strtotime($value)) {
                            $logoInfoArray[$row]['private_start'] = date("Y-m-d", strtotime($value));
                            $logoInfoArray[$row]['private_end'] = date("Y-m-d", strtotime("$value+10year-1day"));
                            $excelArray[$row][$col] = $value;
                        } else {
                            $excelArray[$row][$col] = "专用权期限起始日期不合法 " . $value;
                            $excelWarning[] = $col . $row;
                        }
                        break;
                    case "F": //专用权期限结束
                        break;
                    case "G": //商标名字种类

                        $value = trim($value);
                        if (array_key_exists($value, $logoComb)) {
                            $logoInfoArray[$row]['name_type'] = $logoComb[$value];

                        } else {
                            $excelArray[$row][$col] = "商标类型不存在 " . $value;
                            $excelWarning[] = $col . $row;
                        }
                        break;
                    case "H": //字符数量
                        if (in_array($value, $logoNameLength)) {
                            $logoInfoArray[$row]['logo_length'] = array_flip($logoNameLength)[$value];
                        } else {
                            $excelArray[$row][$col] = "字符数量不存在 " . $value;
                            $excelWarning[] = $col . $row;
                        }
                        break;
                    case "I": //交易类型
                        if (in_array($value, $tradeType)) {
                            $logoInfoArray[$row]['trade_type'] = $excelArray[$row][$col];
                        } else {
                            $excelArray[$row][$col] = "交易类型不存在 " . $value;
                            $excelWarning[] = $col . $row;
                        }
                        break;
                    case "J": //商标类型
                        if (in_array($value, $category)) {
                            $logoInfoArray[$row]['category'] = $excelArray[$row][$col];
                        } else {
                            $excelArray[$row][$col] = "商标类型不存在 " . $value;
                            $excelWarning[] = $col . $row;
                        }
                        break;
                    case "K": //商标联系人
                        $logoInfoArray[$row]['name'] = $excelArray[$row][$col];
                        break;
                    case "L": //联系电话
                        if ($value) {
                            if ($excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                                $logoInfoArray[$row]['tel'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "联系电话不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        } else {
                            $logoInfoArray[$row]['tel'] = "";
                        }
                        break;
                    case "M": //手机
                        if ($value) {
                            if ($excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                                $logoInfoArray[$row]['mobile'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "联系电话不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        } else {
                            $logoInfoArray[$row]['mobile'] = "";
                        }
                        break;
                    case "N": //微信
                        if ($value) {
                            if ($excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                                $logoInfoArray[$row]['wx'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "联系电话不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        } else {
                            $logoInfoArray[$row]['wx'] = "";
                        }
                        break;
                    case "O": //地址
                        $logoInfoArray[$row]['address'] = $excelArray[$row][$col];
                        break;
                    case "P": //邮编
                        $logoInfoArray[$row]['post_code'] = $excelArray[$row][$col];
                        break;
                    case "Q": //价格
                        if (false !== $excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                            if ($excelArray[$row][$col] < 100) {
                                $value = $excelArray[$row][$col] * 10000;
                            }
                            $logoInfoArray[$row]['price'] = $value;
                        } else {
                            $excelArray[$row][$col] = "价格不正确 " . $value;
                            $excelWarning[] = $col . $row;
                        }
                        break;
                    case "R": //是否共用商标
                        if ($value == "是") {
                            $logoInfoArray[$row]['applicant_share'] = "这个是共有商标";
                        } else {
                            $logoInfoArray[$row]['applicant_share'] = "";
                        }

                        break;
                    case "S": //电商适用
                        $logoInfoArray[$row]['suitable'] = $excelArray[$row][$col];
                        break;
                    case "T": //适用项目组
                        $logoInfoArray[$row]['goods_code'] = $excelArray[$row][$col];
                        break;
                    case "U": //适用项目名称
                        $logoInfoArray[$row]['goods_name'] = $excelArray[$row][$col];
                        break;
                    case "V": //注册公告期号
                        //数字验证
                        if ($value) {
                            if ($excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                                $logoInfoArray[$row]['reg_issue'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "内容不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "W": //注册公告日期
                        //日期验证
                        if ($value) {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                            if (date("Y-m-d", strtotime($value)) == $value) {
                                $logoInfoArray[$row]['reg_date'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "日期格式不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "X":
                        break;
                    case "Y": //申请日期
                        //日期验证
                        if ($value) {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                            if (date("Y-m-d", strtotime($value)) == $value) {
                                $logoInfoArray[$row]['app_date'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "日期格式不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "Z":
                        break;
                    case "AA":
                        break;
                    case "AB": //申请人证件号
                        //数字验证
                        if ($value) {
                            if ($excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                                $logoInfoArray[$row]['applicant_id'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "内容不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "AC":
                        break;
                    case "AD":
                        break;
                    case "AE":
                        break;
                    case "AF"://初审公告日期
                        //日期验证
                        if ($value) {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                            if (date("Y-m-d", strtotime($value)) == $value) {
                                $logoInfoArray[$row]['announcement_date'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "日期格式不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "AG": //初审公告号
                        //数字验证
                        if ($value) {
                            if ($excelArray[$row][$col] = filter_var($value, FILTER_SANITIZE_NUMBER_INT)) {
                                $logoInfoArray[$row]['announcement_issue'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "内容不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "AH"://国际注册日期
                        //日期验证
                        if ($value) {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                            if (date("Y-m-d", strtotime($value)) == $value) {
                                $logoInfoArray[$row]['international_date'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "日期格式不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "AI"://后期指定日期
                        //日期验证
                        if ($value) {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                            if (date("Y-m-d", strtotime($value)) == $value) {
                                $logoInfoArray[$row]['post_date'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "日期格式不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;
                    case "AJ"://优先权日期
                        //日期验证
                        if ($value) {
                            $value = str_replace(["年", "月", "日", "/", "-", "至", ".", "--"], "-", $value);
                            if (date("Y-m-d", strtotime($value)) == $value) {
                                $logoInfoArray[$row]['privilege_date'] = $excelArray[$row][$col];
                            } else {
                                $excelArray[$row][$col] = "日期格式不正确 " . $value;
                                $excelWarning[] = $col . $row;
                            }
                        }
                        break;

                    case "AK": //指定颜色
                        $logoInfoArray[$row]['color'] = $excelArray[$row][$col];
                        break;
                    case "AL": //创意图片
                        //无内容
                        break;
                    case "AM": //创意说明
                        //无内容
                        break;
                    case "AN": //流程
                        $logoInfoArray[$row]['flow_data'] = $excelArray[$row][$col];
                        //不进行验证
                        break;
                }
            }
        }

        $data['excelArray'] = $excelArray;
        $data['excelWarning'] = $excelWarning;
        $data['logoInfoArray'] = $logoInfoArray;


        return $data;

    }


    private function picResize($pic) {

        list($SWidth, $SHeight, $imgType) = getimagesize($pic);

        switch ($imgType) {
            case 1 :
                $sourceImg = imagecreatefromgif($pic);
                $imgCreator = "imagegif";
                break;
            case 2 :
                $sourceImg = imagecreatefromjpeg($pic);
                $imgCreator = "imagejpeg";
                break;
            case 3 :
                $sourceImg = imagecreatefrompng($pic);
                $imgCreator = "imagepng";
                break;
            default:
                exit("('警告：此图片类型本系统不支持！')");
        }
        //等比缩放
        $ratio_orig = $SWidth / $SHeight;
        if ($this->tumbWidth / $this->tumbHeight > $ratio_orig) {
            $width = $this->tumbHeight * $ratio_orig;
            $height = $this->tumbHeight;
            $dst_x = ($this->tumbWidth - $width) / 2;
            $dst_y = 0;

        } else {
            $height = $this->tumbWidth / $ratio_orig;
            $width = $this->tumbWidth;
            $dst_x = 0;
            $dst_y = ($this->tumbHeight - $height) / 2;
        }

        if ($thumbnail = imagecreatetruecolor($this->tumbWidth, $this->tumbHeight)) { //创建缩略图

            $white = imagecolorallocate($thumbnail, 255, 255, 255);
            if (imagefill($thumbnail, 0, 0, $white)) { //填充缩略图背景色颜色

                //复制图像并改变大小
                if (imagecopyresampled($thumbnail, $sourceImg, $dst_x, $dst_y, 0, 0, $width, $height, $SWidth, $SHeight)) {
                    //输出图像
                    if ($thumbPic = $imgCreator($thumbnail, $pic)) {
                        $msg = true;


                        return $pic;

                    } else {
                        exit("无法生成缩略图");
                    }
                } else {
                    exit("复制图像失败");
                }
            } else {
                exit("填充缩略图背景色颜色失败");
            }
        } else {
            exit("创建缩略图失败");
        }

    }


    private function readExcel($excelFile) {
        $fileType = $excelFile->getClientOriginalExtension();
        $this->excelReaderClass = $readClass = "PhpOffice\PhpSpreadsheet\Reader\\" . ucfirst($fileType);
        $readClassObj = new $readClass;
//        $readClassObj = new Xlsx();
        $spreadSheet = $readClassObj->load($excelFile);
        $workSheet = $spreadSheet->getActiveSheet();
        $highestRow = $workSheet->getHighestRow();
        $highestColumn = $workSheet->getHighestColumn();

        $highestColumn++; //get the last column

        $data = array();

        for ($row = 2; $row <= $highestRow; $row++) {
            for ($col = "A"; $col != $highestColumn; $col++) {
                if ($workSheet->getCell('A' . $row)->getValue()) {

                    $data[$row][$col] = $workSheet->getCell($col . $row)->getValue();
                }
            }
        }

        return $data;


    }

    private function logoSearchMysql(Request $request) {

        $condition[] = ['logos.logo_name', 'like', "%" . $request->post('logoName') . "%"];

        if ($request->post('logoCate')) {

            $condition[] = ['logos.int_cls', '=', $request->post('logoCate')];
        }

        if ($request->post('logoType')) {
            $condition[] = ['logos.name_type', '=', $request->post('logoType')];
        }
        if ($request->post('logoLength')) {
            $condition[] = ['logos.logo_length', '=', $request->post('logoLength')];
        }

        $logos = Logo::
        leftJoin('logo_cates', 'logos.int_cls', '=', 'logo_cates.id')
            ->leftJoin('logo_flows', 'logos.flow_id', '=', 'logo_flows.id')
            ->leftJoin('logo_goods', 'logos.goods_id', '=', 'logo_goods.id')
            ->leftJoin('logo_length', 'logos.logo_length', '=', 'logo_length.id')
            ->leftJoin('logo_sellers', 'logos.seller_id', '=', 'logo_sellers.id')
            ->leftJoin('logo_type', 'logos.name_type', '=', 'logo_type.id')
            ->where($condition)
            ->select(
                'logos.*',
                'logo_cates.category_name',
                'logo_flows.flow_data',
                'logo_goods.goods_name', 'logo_goods.goods_code',
                'logo_length.name_length',
                'logo_sellers.name', 'logo_sellers.tel', 'logo_sellers.wx', 'logo_sellers.mobile', 'logo_sellers.address', 'logo_sellers.post_code',
                'logo_type.type'
            )
            ->offset($request->post('pageNo') * $this->pageSize)
            ->limit($this->pageSize)
            ->get();


        return $logos;
    }

    public function logoCateList() {
        return DB::table('logo_cates')->select(['id', 'category_name'])->orderBy('id')->get();
    }

    public function logoLengthList() {
        return DB::table('logo_length')->select(['id', 'name_length'])->orderBy('id')->get();

    }

    public function logoTypeList() {
        return DB::table('logo_type')->select(['id', 'type'])->orderBy('id')->get();

    }

    public function logoSellerList() {
        return LogoSeller::orderBy('id')->get();
    }

    /*
     * $file excel file with
     */
    public function readPic($file = null) {
//        $file = storage_path('pic_test.xlsx'); //excel file
//        $file = storage_path($file); //get the excel path
//dd($file);
//        $pics_folder = uniqid(); //unique fold to put the export of the pics form excel
//        mkdir(storage_path($pics_folder));//mk the dir
        mkdir(public_path('images\\logo_images\\') . $this->tmpExcelPicFolder);

        $picArr = array();
//        $reader = new Xlsx(); //read excel
        $reader = new $this->excelReaderClass; //read excel
        $spreadsheet = $reader->load($file); //load worksheet
        $workSheet = $spreadsheet->getActiveSheet();


        foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $row => $drawing) {
            //get the reg_no cell from excel
            $reg_no_cell = "A" . filter_var($drawing->getCoordinates(), FILTER_SANITIZE_NUMBER_INT);

            //don't have clues, this part is not being working in the system
            if ($drawing instanceof MemoryDrawing) {
                ob_start();
                call_user_func(
                    $drawing->getRenderingFunction(),
                    $drawing->getImageResource()
                );

                $imageContents = ob_get_contents();
                ob_end_clean();
                switch ($drawing->getMimeType()) {
                    case MemoryDrawing::MIMETYPE_PNG:
                        $extension = "png";
                        break;
                    case MemoryDrawing::MIMETYPE_GIF:
                        $extension = "gif";
                        break;
                    case MemoryDrawing::MIMETYPE_JPEG:
                        $extension = "jpg";
                        break;
                }

            } else {
                //go inside the excel and get the info from it
                //$drawing->getCoordinates()  the pics col&row in excel
                //$drawing->getExtension() get the pic extensiton without .
//                $workSheet->getCell($drawing->getCoordinates())->getValue();


                $name = $workSheet->getCell($reg_no_cell)->getValue() . "." . $drawing->getExtension();

//                $name = $row."-".$drawing->getCoordinates().".".$drawing->getExtension();


//                $name = $row."-".$drawing->getCoordinates().".".$drawing->getExtension();
                $zipReader = fopen($drawing->getPath(), 'r'); //zip out the pic
                $imageContents = "";
                while (!feof($zipReader)) {
                    $imageContents .= fread($zipReader, 1024); //read the pic in binary, read upto 1MB in size
                }
                fclose($zipReader); //finish reading
//                $extension = $drawing->getExtension();
            }

            // make the pic name equals to it's own cell like 'A1','B2'
            $myFileName = public_path('images\\logo_images\\') . $this->tmpExcelPicFolder . "\\" . $name;
            $picArr[$workSheet->getCell($reg_no_cell)->getValue()] = $name;

            file_put_contents($myFileName, $imageContents);//save the pics

            $this->picResize($myFileName); //resize the pic

        }


        return $picArr;


    }


}
