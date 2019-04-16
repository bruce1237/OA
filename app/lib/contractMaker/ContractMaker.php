<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-12
 * Time: 16:33
 * Used:
 */

namespace App\Lib\contractMaker;


use App\Model\Cart;
use App\Model\Contract;
use App\Model\Firm;
use App\Model\Order;
use App\Model\Staff;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

abstract class ContractMaker {


    protected function addPageSeal(string $PDFFile,string $seal){
        $pdfReader = new \TCPDF();
        $pdfReader->getNumPages();




    }

    protected function getStaffInfo($staffId){
        try{
            return   Staff::find($staffId);
        }catch (\Exception $e){
            echo "获取员工信息失败:400 ".$e->getMessage();
            return false;
        }
    }

    protected function toChineseNumber($ns) {
        static $cnums = array("零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"),
        $cnyunits = array("", "圆", "角", "分"),
        $grees = array("", "拾", "佰", "仟", "万", "拾", "佰", "仟", "亿");
        $moneyArr= explode(".", $ns, 2);
//        list($ns1, $ns2) = explode(".", $ns, 2);
        $ns1= $moneyArr[0];
        $ns2=key_exists(1,$moneyArr)?$moneyArr[1]:'00';
        $ns2 = array_filter(array($ns2[1], $ns2[0])); //转为数组

        $arrayTemp = $this->_cny_map_unit(str_split($ns1), $grees);

        //die();


        $ret = array_merge($ns2, array(implode("", $arrayTemp), "")); //处理整数

        $arrayTemp = $this->_cny_map_unit($ret, $cnyunits);

        $ret = implode("", array_reverse($arrayTemp));    //处理小数

        return str_replace(array_keys($cnums), $cnums, $ret);
    }

    protected function _cny_map_unit($list, $units) {
        $ul = count($units);
        $xs = array();
        foreach (array_reverse($list) as $x) {
            $l = count($xs);


            if ($x != "0" || !($l % 4)) $n = ($x == '0' ? '' : $x) . ($units[($l) % $ul]);
            else $n = is_numeric($xs[0][0]) ? $x : '';
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

    protected function convertPaymentDetails($payments) {
        $payments = json_decode($payments, true);
        $str = '';
        foreach ($payments as $key => $value) {
            $str .= "{$key}:{$value}<w:br />";
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
            $obj =  Order::find($orderId);
            $obj->orderDate = date("Y-m-d",strtotime($obj->created_at));
            $obj->contactNumber=date("Ymd",strtotime($obj->created_at)).sprintf("%'.09d\n",$obj->order_id);

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

    abstract protected function make(int $orderId, int $contractId, array $serviceIds): bool;

    abstract protected function getTemplateFile(): string;
}
