<?php

namespace App\Http\Controllers\Admin;

use App\Model\LogoCate;
use App\Model\LogoLength;
use App\Model\LogoType;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EsSearchController extends Controller
{
    //

    protected $elasticSearchIndex;
    protected $elasticSearchType;
    protected $elasticSearchSize;
    protected $elasticSearchHost;
    protected $highLight;

    protected $logoCateList;
    protected $logoTypeList;
    protected $logoLengthList;


    public function __construct()
    {


        $logo_info = new LogoController();
        $this->logoCateList = $logo_info->logoCateList();
        $this->logoTypeList = $logo_info->logoTypeList();
        $this->logoLengthList = $logo_info->logoLengthList();

        $this->elasticSearchIndex = config('essearch.es_index');
        $this->elasticSearchType = config('essearch.es_type');
        $this->elasticSearchSize = config('essearch.es_size');
        $this->elasticSearchHost = config('essearch.es_host');

    }

    public function index()
    {
        return view('admin/essearch/essearch', ['logoCateList' => $this->logoCateList, 'logoTypeList' => $this->logoTypeList, 'logoLengthList' => $this->logoLengthList]);
    }


    public function search(Request $request)
    {
        $error=[];

        if (!$request->Ajax()) {
            $error['status']=false;
            $error['message']="Request Method is illegal";
            return json_encode($error);
        }


        if (!$keyword['logo_name'] = $request->post('logoName')) {
            $error['status']=false;
            $error['message']="EMPTY KEY WORD";
            return json_encode($error);
        }


        $searchArr = [];


        if (is_array($request->post('cateName'))) {
            if ($request->post('cateName')) {
                foreach (LogoCate::find($request->post('cateName')) as $cate) {
                    $searchArr[]['match']['category_name'] = $cate->category_name;
                }
            }
        }


        if (is_array($request->post('logoType'))) {
            if ($request->post('logoType')) {
                foreach (LogoType::find($request->post('logoType')) as $logoType) {
                    $searchArr[]['match']['type'] = $logoType->type;
                }
            }
        }


        if (is_array($request->post('eType'))) {
            foreach ($request->post('eType') as $eType) {
                $searchArr[]['match']['suitable'] = $eType;
            }
        }


        if (is_array($request->post('nameLength'))) {
            if ($request->post('nameLength')) {
                foreach (LogoLength::find($request->post('nameLength')) as $nameLength) {
                    $searchArr[]['match']['name_length'] = $nameLength->name_length;
                }
            }
        }




        $page = $request->post('page')-1;



        $search_result = $this->elasticSearch($keyword, $searchArr, $page, $this->elasticSearchSize, $this->elasticSearchIndex, $this->elasticSearchType);
        $response = $this->responseFilter($search_result);


        if($response['result_total']>$this->elasticSearchSize){

            $data['pages'] = ($response['result_total'] % $this->elasticSearchSize == 0 ? $response['result_total'] / $this->elasticSearchSize : (int)($response['result_total'] / $this->elasticSearchSize) + 1);
        }else{
            $data['pages']=1;
        }
        $data['total_records']=$response['result_total'];
        unset($response['result_total']);
        $data['data'] = $response;
        $data['status']=true;


        return json_encode($data);
    }


//

    public function responseFilter(array $searchResult)
    {


        if (!Auth::guard('admin')->check()) {
            //普通用户，仅返回相关结果
            foreach ($searchResult as $key => $result) {
                unset(
                    $searchResult[$key]['reg_no'],
                    $searchResult[$key]['agent'],
                    $searchResult[$key]['applicant_cn'],
                    $searchResult[$key]['applicant_en'],
                    $searchResult[$key]['applicant_id'],
                    $searchResult[$key]['applicant_1'],
                    $searchResult[$key]['applicant_2'],
                    $searchResult[$key]['address_cn'],
                    $searchResult[$key]['address_en'],
                    $searchResult[$key]['category'],
                    $searchResult[$key]['color'],
                    $searchResult[$key]['price'],
                    $searchResult[$key]['seller_id'],
                    $searchResult[$key]['flow_id'],
                    $searchResult[$key]['goods_id'],
                    $searchResult[$key]['deleted_at'],
                    $searchResult[$key]['created_at'],
                    $searchResult[$key]['updated_at']
                );
            }

        }

        return $searchResult;


    }


    public function elasticSearch($keyword, $searchArr, $page = 1, $size = 4, $index = 'my_index7', $type = 'my_type7')
    {

//
//        if (!(sizeof($searchArr))) {
//            $should = null;
//        } else {
//            $should['should'] = $searchArr;
//        }
//




        $params = [
            'size' => $size, //no of result displayed per page
            'from' => $page*$size, // page no of current page
            'index' => $index,
            'type' => $type,
            'body' => [
                'query' => [
                    "bool" => [
                        'must' => ['match' => $keyword],
                        'should'=>$searchArr,

                    ]
                ],
                'highlight' => [
                    "fields" => [
                        'logo_name' => new \stdClass(),





                    ],
                ],
            ]
        ];

        $client = ClientBuilder::create()->build();
//        dd($params);
        $response = $client->search($params);



        //处理和过滤返回的数组中，
        if ($response['hits']['total']) {
            foreach ($response['hits']['hits'] as $key => $result) {

                $response['hits']['hits'][$key] = $result['_source'];
                $response['hits']['hits'][$key]['logo_name'] = $result['highlight']['logo_name'][0];
                unset($response['hits']['hits'][$key]['highlight']);

            }
        }
        $response['hits']['hits']['result_total'] = $response['hits']['total'];


        return $response['hits']['hits'];
    }
}
