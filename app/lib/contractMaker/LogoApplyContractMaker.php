<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-15
 * Time: 10:10
 * Used:
 */

namespace App\Lib\contractMaker;


use App\Lib\office2pdf\office2pdf;
use App\Model\Contract;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class LogoApplyContractMaker extends ContractMaker {

    public function make(int $orderId, int $contractId, array $serviceIds): bool {
        $orderObj = $this->getOrderInfo($orderId);

        $contractObj = $this->getContractInfo($contractId);
        $firmObj = $this->getFirmInfo($orderObj->order_firm_id);
        $staffObj = $this->getStaffInfo($orderObj->order_staff_id);
        $orderInfo = array_merge($firmObj->toArray(), $orderObj->toArray(), $staffObj->toArray());
        $wordFile['file'] = $this->processTemplate($contractObj, $orderId, $serviceIds, $orderInfo);
        $wordFile['seal'] = storage_path("firms/{$firmObj->firm_id}/seal/{$firmObj->firm_id}.png");
        //todo: convert word into PDF with split-Seal
        $pdf = new office2pdf();
        $pdfFileName = storage_path('contractTemplate/PDF').uniqid().'.pdf';
        $pdf->run($wordFile['file'],$pdfFileName);

        $pdfFile=storage_path('contractTemplates/TMP5cb58ec41ee2d.pdf');

//        $this->addPageSeal($wordFile['file'],$wordFile['seal']);
        $this->addPageSeal($pdfFile,$wordFile['seal']);

        return true;
    }


    private function processTemplate($contractObj, int $orderId, array $serviceIds, array $orderInfo) {

        $templateFile = storage_path("contractTemplates\\{$contractObj->contract_file}");
        $word = new PhpWord();

        $templateProcessor = new TemplateProcessor($templateFile);
        $templateWriter = IOFactory::createWriter($word, 'HTML');
        $cartObj = $this->getCarts($orderId, $serviceIds);
        $cartDetails = $this->restructureCarts($cartObj);


        $sourceImgName = "image3.png";
        $firmId = $this->getFirmInfo($orderId)->firm_id;

        $firmSeal = storage_path("firms/{$firmId}/seal/{$firmId}.png");
        $newSeal = storage_path("firms/{$firmId}/seal/{$sourceImgName}");
        copy($firmSeal, $newSeal);

        $templateProcessor->setImageValueC($sourceImgName, $newSeal);

        $orderInfo['order_payment_method_details'] = $this->convertPaymentDetails($orderInfo['order_payment_method_details']);
        $orderInfo['order_totalCHN'] = $this->toChineseNumber($cartDetails['total']);
        $orderInfo['order_total'] = $cartDetails['total'];
        unset($cartDetails['total']);
        $rows = sizeof($cartDetails['name']);


        $templateProcessor->cloneRow('service_type', $rows);


        for ($i = 1; $i <= $rows; $i++) {
            $orderInfo['service_name#' . $i] = $cartDetails['name'][$i - 1];
            $orderInfo['service_type#' . $i] = $cartDetails['type'][$i - 1];
            $orderInfo['service_attr#' . $i] = $cartDetails['attr'][$i - 1];
            $orderInfo['service_price#' . $i] = $cartDetails['price'][$i - 1];
        }


        foreach ($orderInfo as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $newFileName = storage_path("contractTemplates\\TMP" . uniqid() . ".doc");

        $templateProcessor->saveAs($newFileName);
        return $newFileName;

    }

    private function restructureCarts($cartObj) {
        $serviceName = $cartObj[0]['service_name'];
        $restructureArr = array();

        $priceTotal = 0;

        foreach ($cartObj as $key => $cart) {
            if ($serviceName == $cart->service_name) {
                $priceTotal += $cart->service_price;
            } else {
                $priceTotal = 0;
                $serviceName = $cart->service_name;
            }
            $restructureArr[$cart->service_name][$key] = $cart;
        }

        $arr = array();

        foreach ($restructureArr as $key => $value) {
            $value = array_values($value);
            $price = 0;
            foreach ($value as $kk => $vv) {

                $arr[$value[$kk]->service_category][$key]['attr'] = '';
                $arr[$value[$kk]->service_category][$key]['price'] = 0;
            }

            $cate = "";

            foreach ($value as $v) {


                $attributes = json_decode($v->service_attributes, true);
                foreach ($attributes as $attribute) {
                    if ($attribute['name'] == "类别") {
                        $cate .= $attribute['value'] . "类, ";
                        $arr[$v->service_category][$key]['attr'] .= $attribute['value'] . "类, ";
                    }
                }
                $price += $v->service_price;
                $arr[$v->service_category][$key]['price'] += $v->service_price;
                $arr[$v->service_category][$key]['name'] = $v->service_name;
                $arr[$v->service_category][$key]['type'] = $v->service_category;
            }
        }

        $orderDetailArray = [
            'type' => [],
            'name' => [],
            'attr' => [],
            'price' => [],

        ];
        $total = 0;
        foreach ($arr as $cart) {
            foreach ($cart as $item) {
                array_push($orderDetailArray['type'], $item['type']);
                array_push($orderDetailArray['name'], $item['name']);
                array_push($orderDetailArray['attr'], $item['attr']);
                array_push($orderDetailArray['price'], $item['price']);
                $total += $item['price'];
            }
        }
        $orderDetailArray['total'] = $total;
        return $orderDetailArray;
    }

    protected function makeCartDetailsTable(PhpWord $word, int $orderId, array $serviceIds) {
        $total = 0;
        $cartObj = $this->getCarts($orderId, $serviceIds);

        $cartArr = $this->restructureCarts($cartObj);


        $totalCHN = $this->toChineseNumber($total);

        $result['table'] = $table;
        $result['total'] = $total;
        $result['totalCHN'] = $totalCHN;


        return $result;

    }

    protected function getTemplateFile(): string {
        $fileName = Contract::find(env('LOGOAPPLY'))->contract_file;
        $path = storage_path('contractTemplates/');
        if (storage_path('contractTemplates/')->exists($fileName)) {
            return $path . $fileName;
        } else {
            throw new ContractException("合同模板文件不存在!");
        }
    }


}
