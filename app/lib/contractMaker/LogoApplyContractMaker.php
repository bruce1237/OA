<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-15
 * Time: 10:10
 * Used:
 */

namespace App\Lib\contractMaker;


use App\Model\Contract;
use App\Model\Firm;
use App\Model\Order;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class LogoApplyContractMaker extends ContractMaker {

    public function make(int $orderId, int $contractId, array $serviceIds):bool {
        $orderObj = $this->getOrderInfo($orderId);

        $contractObj = $this->getContractInfo($contractId);
        $firmObj = $this->getFirmInfo($orderObj->order_firm_id);
        $staffObj = $this->getStaffInfo($orderObj->order_staff_id);

        $orderInfo = array_merge($firmObj->toArray(),$orderObj->toArray(),$staffObj->toArray());
        $wordTemplateFile['file']= $this->processTemplate($contractObj,$orderId,$serviceIds,$orderInfo);
        $wordTemplateFile['seal']=storage_path("firms/{$firmObj->firm_id}/seal/{$firmObj->firm_id}.png");
        //todo: convert word into PDF with Seal
        return true;
    }

    private function processTemplate($contractObj, int $orderId,array $serviceIds, array $orderInfo){

            $templateFile = storage_path("contractTemplates\\{$contractObj->contract_file}");
            $word = new PhpWord();

            $templateProcessor = new TemplateProcessor($templateFile);
            $templateWriter = IOFactory::createWriter($word,'Word2007');
            $table = $this->makeCartDetailsTable($word, $orderId,$serviceIds);
//            $abc = $templateWriter->getWritePart('document')->getObjectAsText($table);
//            $orderInfo['cartDetails'] = $abc;
            $orderInfo['order_payment_method_details'] = $this->convertPaymentDetails($orderInfo['order_payment_method_details']);
        $orderInfo['order_totalCHN']=$table['totalCHN'];
        $orderInfo['order_total']=$table['total'];
            foreach ($orderInfo as $key=>$value){
                $templateProcessor->setValue($key,$value);
            }
        $newfilename = storage_path('contractTemplates\tmp44333.doc');
        $templateProcessor->saveAs($newfilename);
//            $wordTemplateFile = storage_path("contractTemplates/Temp/{$orderId}/{$contractObj->contract_name}.doc");

//            $templateWriter->save($wordTemplateFile);
//            return $wordTemplateFile;
    }



    protected function makeCartDetailsTable(PhpWord $word, int $orderId, array $serviceIds){
        $total = 0;
        $cartObj = $this->getCarts($orderId,$serviceIds);

        $cartArr = $this->restructureCarts($cartObj);

        $section = $word->addSection();
        $table = $section->addTable();
        $table->addRow(900);
        $fontStyle = array('align'=>'center');
        $table->addCell(1000)->addText("服务类型",$fontStyle);
        $table->addCell(3000)->addText("商标名称",$fontStyle);
        $table->addCell(5000)->addText("类别",$fontStyle);
        $table->addCell(1000)->addText("价格(元)",$fontStyle);

        foreach ($cartArr as $cart) {

            $table->addRow(900);
            $table->addCell(1000)->addText($cart['category'],$fontStyle);
            $table->addCell(3000)->addText($cart['name'],$fontStyle);
            $table->addCell(5000)->addText($cart['attr'],$fontStyle);
            $table->addCell(1000)->addText($cart['price']."元",$fontStyle);
            $total+=$cart['price'];
        }
        $fontRight = array('align'=>'right');
        $table->addRow(900);
        $table->addCell(9000)->addText("共计: ",$fontRight);
        $table->addCell(1000)->addText($total."元",$fontStyle);

        $totalCHN = $this->toChineseNumber($total);

        $result['table']=$table;
        $result['total']=$total;
        $result['totalCHN']=$totalCHN;


        return $result;

    }

    private function restructureCarts($cartObj){
        $serviceName=$cartObj[0]['service_name'];
        $restructureArr=array();
        $restructureArr1=array();
        $priceTotal=0;

        foreach ($cartObj as $key=>$cart){
            if($serviceName == $cart->service_name){
                $priceTotal+=$cart->service_price;
            }else{
                $priceTotal=0;
                $serviceName = $cart->service_name;
            }
            $restructureArr[$cart->service_name][$key]  = $cart;
        }


        foreach ($restructureArr as $key=>$value){
            $restructureArr1[$key]['price']=0;
            $restructureArr1[$key]['attr']="";
            foreach ($value as $v) {
                $attributes = json_decode($v->service_attributes,true);
                foreach($attributes as $attribute){
//                    if($attribute['name']=="类别" || $attribute['name']=="扩展小项"){
                    if($attribute['name']=="类别" ){
                        $restructureArr1[$key]['attr'] .= $attribute['value']."类, ";
                    }
                }
                $restructureArr1[$key]['price'] +=$v->service_price;
                $restructureArr1[$key]['name'] =$v->service_name;
                $restructureArr1[$key]['category'] =$v->service_category;

            }

        }

        return $restructureArr1;
    }

    protected function getTemplateFile():string {
        $fileName = Contract::find(env('LOGOAPPLY'))->contract_file;
        $path = storage_path('contractTemplates/');
        if(storage_path('contractTemplates/')->exists($fileName)){
            return $path.$fileName;
        }else{
            throw new ContractException("合同模板文件不存在!");
        }
    }


}
