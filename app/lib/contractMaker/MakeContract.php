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
use App\Model\Order;
use App\lib\contractMaker\LogoTransferContractMaker;
use App\lib\contractMaker\LogoCaseContractMaker;

class MakeContract
{
    private $orderObj;
    private $cartObj;
    private $contractArr;

    public function makeContract(Int $orderId)
    {

        $this->init($orderId);
        $contractServiceArr = $this->getContractServiceArray();
        return $this->getContractMaker($orderId, $contractServiceArr);
    }

    private function getContractMaker(int $orderId, array $contractServiceArr): bool
    {

        $pdfContract = array();
        foreach ($contractServiceArr as $contractId => $serviceIds) {

            switch ($contractId) {
                case env('LOGOAPPLY'):
                    $contractMaker = new LogoApplyContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;
                case env('LOGOEXTEN'):

                    $contractMaker = new LogoExtenContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;
                case env('LOGOCHANGE'):
                    $contractMaker = new LogoChangeContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;

                case env('LOGOTRANSF'):
                    $contractMaker = new LogoTransferContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;

                case env('LOGOCASE'):
                    $contractMaker = new LogoCaseContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;

                case env('PATCASE'):
                    $contractMaker = new PatCaseContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;

                case env('COPYRIGHTCASE'):
                    $contractMaker = new CopyRightContractMaker();
                    $pdfContract[] = $contractMaker->make($orderId, $contractId, $serviceIds);
                    break;

                default:
                    dd("合同选择错误!!");
            }
        }

        return sizeof($pdfContract) == sizeof($contractServiceArr);
    }

    private function getContractServiceArray()
    {

        $contractServiceArr = array();
        foreach ($this->contractArr as $contractId => $services) {
            $contractServiceArr[$contractId] = array_intersect($this->cartObj->Arr, $services);
        }
        return array_filter($contractServiceArr);
    }

    private function init($orderId)
    {
        //get order info
        $this->orderObj = $this->getOrderInfo($orderId);
        //get cart info
        $this->cartObj = $this->getCartInfo($orderId);

        //get contract Info
        $this->contractArr = $this->getContractArr();
    }

    private function getOrderInfo($orderId)
    {
        return Order::find($orderId);
    }

    private function getCartInfo($orderId)
    {
        $carts = Cart::where('order_id', '=', $orderId)->groupBy('service_id')->get();
        $cartArr = array();
        foreach ($carts as $cart) {
            $cartArr[] = $cart->service_id;
        }
        $carts->Arr = $cartArr;
        return $carts;
    }

    private function getContractArr()
    {
        $contractObj = Contract::all();
        $contractArr = array();
        foreach ($contractObj as $contract) {
            $contractArr[$contract->contract_id] = $contract->contract_services;
        }
        return $contractArr;
    }
}
