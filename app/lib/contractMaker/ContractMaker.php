<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-12
 * Time: 16:33
 * Used:
 */

namespace App\Lib\contractMaker;


use App\Lib\office2pdf\office2pdf;
use App\Model\Cart;
use App\Model\Contract;
use App\Model\Firm;
use App\Model\Order;
use App\Model\Staff;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

abstract class ContractMaker {

    /**
     * @param int $orderId
     * @param int $contractId
     * @param array $serviceIds
     * @return bool
     *
     * Used For: generate PDF format contract
     */
    public function make(int $orderId, int $contractId, array $serviceIds): bool {
        //get all the information about this order
        $orderObj = $this->getOrderInfo($orderId);
        $contractObj = $this->getContractInfo($contractId);
        $firmObj = $this->getFirmInfo($orderObj->order_firm_id);
        $staffObj = $this->getStaffInfo($orderObj->order_staff_id);

        //merge into a single array for word template insert
        $orderInfo = array_merge($firmObj->toArray(), $orderObj->toArray(), $staffObj->toArray());
        $orderInfo['contract_name'] = $contractObj->contract_name;

        //process the word template
        $wordFile['file'] = $this->processTemplate($orderId, $serviceIds, $orderInfo);

        //get the contractSeal picture
        $wordFile['seal'] = storage_path("firms/{$firmObj->firm_id}/seal/{$firmObj->firm_id}.png");

        //check if the order Reference folder exist,
        if(!is_dir(public_path("storage/CRM/Order/REF/{$orderObj->order_id}/"))){
            mkdir(public_path("storage/CRM/Order/REF/{$orderObj->order_id}/"));
        }

        //get the contactPDF Name
        $contractPdf = public_path("storage/CRM/Order/REF/{$orderObj->order_id}/{$contractObj->contract_name}.pdf") ;
        // dd("FF");
        // convert word into PDF with split-Seal
        $pdfFileName = $this->wordToPDF($wordFile['file'],$contractPdf);

        //add PageSeal 骑缝章 into PDF
        $pdfFileName = $this->addPageSeal($pdfFileName,$wordFile['seal']);

        return $pdfFileName;
    }


    protected function getTemplateFile(int $contractId): string {
        $fileName = Contract::find($contractId)->contract_file;

        $path = storage_path('contractTemplates/');

        if (Storage::disk('contract')->exists($fileName)) {
            return $path . $fileName;
        } else {
            throw new ContractException("合同模板文件不存在!");
        }
    }

    protected function replaceDummySeal(string $dummySeal, int $firmId):string {
        $firmSeal = storage_path("firms/{$firmId}/seal/{$firmId}.png");
        $realSeal = storage_path("firms/{$firmId}/seal/{$dummySeal}");
        copy($firmSeal, $realSeal);
        return $realSeal;
    }

    protected function wordToPDF(string $wordFile,string $pdfFile){
        $pdf = new office2pdf();
        $TmpPDF= storage_path('contractTemplates/PDF').uniqid().'.pdf';
        if($pdf->run($wordFile,$TmpPDF)){
            unlink($wordFile);
            copy($TmpPDF,$pdfFile);
            unlink($TmpPDF);
            return $pdfFile;
        }
        return false;
    }

    protected function addPageSeal(string $PDFFile, string $seal) :string {
        $pdf = new Fpdi();
        $totalPageNum = $pdf->setSourceFile($PDFFile);
        if($totalPageNum==1){
            return $PDFFile;
        }
        $sealSlices = $this->sliceSeal($totalPageNum, $seal);

        for ($pageNum = 1; $pageNum <= $totalPageNum; $pageNum++) {
            //import a page
            $templateId = $pdf->importPage($pageNum);
            //get pdf size
            $size = $pdf->getTemplateSize($templateId);

            //create a page(landscape or portrait depending on the imported page size

            $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

            //use the template page
            $pdf->useTemplate($templateId);
            //place the graphics
            $pdf->image($sealSlices[$pageNum], ($size['width'] - $sealSlices['width']), 35, $sealSlices['width'],$sealSlices['height']);
            unlink($sealSlices[$pageNum]);
        }
        try{
            $pdf->Output("F", $PDFFile);
            return $PDFFile;
        }catch (\Throwable $throwable){
            dd($throwable->getMessage());
        }
    }

    protected function sliceSeal(int $slice, string $sealImg): array {
        $sourceImg = imagecreatefrompng($sealImg);
        list($width, $height) = getimagesize($sealImg);
        $targetImgWidth = ceil($width / $slice);
        $slicedSeal = array();
        //将公章转换为在PDF上显示的的最高为50的图片,
        $slicedSeal['width'] = $targetImgWidth * 50 /$height;
        $slicedSeal['height'] = 50;

        for ($i = 1; $i <= $slice; $i++) {
            $targetImg = imagecreatetruecolor($targetImgWidth, $height);
            imagealphablending($targetImg, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
            imagesavealpha($targetImg, true); //这里很重要,意思是不要丢了$thumb图像的透明色
            $slicedSeal[$i] = storage_path('contractTemplates\\') . uniqid() . ".png";

            $sourceX = 0 + $targetImgWidth * ($i - 1);
            $sourceY = 0;
            $sourceW = $targetImgWidth;
            $sourceH = $height;
            imagecopy($targetImg, $sourceImg, 0, 0, $sourceX, $sourceY, $sourceW, $sourceH);
            imagepng($targetImg, $slicedSeal[$i]);
        }
        return $slicedSeal;
    }

    protected function getStaffInfo(int $staffId) {
        try {
            return Staff::find($staffId);
        } catch (\Exception $e) {
            echo "获取员工信息失败:400 " . $e->getMessage();
            return false;
        }
    }

    protected function toChineseNumber(float $ns):string {

        static $cnums = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"),
        $cnyunits = array("", "圆", "角", "分"),
        $grees = array("", "拾", "佰", "仟", "万", "拾", "佰", "仟", "亿");
        $moneyArr = explode(".", $ns, 2);
        $ns1 = $moneyArr[0];
        $ns2 = key_exists(1, $moneyArr) ? $moneyArr[1] : '00';
        $ns2 = array_filter(array($ns2[1], $ns2[0])); //转为数组

        $arrayTemp = $this->_cny_map_unit(str_split($ns1), $grees);

        //die();


        $ret = array_merge($ns2, array(implode("", $arrayTemp), "")); //处理整数

        $arrayTemp = $this->_cny_map_unit($ret, $cnyunits);

        $ret = implode("", array_reverse($arrayTemp));    //处理小数

        $CHY = str_replace(array_keys($cnums), $cnums, $ret);


        \preg_replace()

        while(strpos($CHY,"零零")){
            $CHY = str_replace("零零","零", $CHY);
        }



        return $CHY;
    }

    private function _cny_map_unit(array $list, array $units):array {

        $ul = count($units);
        $xs = array();
        foreach (array_reverse($list) as $x) {
            $l = count($xs);


            if ($x != "0" || !($l % 4)){
                $n = ($x == '0' ? '' : $x) . ($units[($l) % $ul]);
            }else{
                $n = is_numeric($xs[0]) ? $x : '';
            }
            array_unshift($xs, $n);
        }

        return $xs;
    }

    protected function getContractInfo(int $contractId) {
        try {
            return Contract::find($contractId);
        } catch (\Exception $e) {
            echo "获取合同信息失败: " . $e->getMessage();
            return false;
        }
    }

    protected function convertPaymentDetails(String $paymentJson):string {
        $payments = json_decode($paymentJson, true);
        $str = '';
        foreach ($payments as $key => $value) {
            $str .= "{$key}:{$value}
";//这个为在word里面换行用得到, 不能留空格
        }
        return $str;
    }

    protected function getCarts(int $orderId, array $serviceIds) {
        return Cart::where('order_id', '=', $orderId)
            ->whereIn('service_id', $serviceIds)
            ->get();
    }

    protected function getOrderInfo(int $orderId) {
        try {
            $obj = Order::find($orderId);
            $obj->orderDate = date("Y-m-d", strtotime($obj->created_at));
            $obj->contactNumber = date("Ymd", strtotime($obj->created_at)) . sprintf("%'.09d\n", $obj->order_id);

            return $obj;
        } catch (\Exception $e) {
            echo "获取订单信息失败: " . $e->getMessage();
            return false;
        }
    }

    protected function getFirmInfo(int $firmId) {
        try {
            return Firm::find($firmId);
        } catch (\Exception $e) {
            echo "获取子公司信息失败: " . $e->getMessage();
            return false;
        }
    }

    abstract protected function processTemplate(int $orderId, array $serviceIds, array $orderInfo): string;

}
