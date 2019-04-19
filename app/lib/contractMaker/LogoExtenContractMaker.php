<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-19
 * Time: 9:17
 * Used:
 */

namespace App\Lib\contractMaker;


use App\Model\Cart;
use PhpOffice\PhpWord\TemplateProcessor;

class LogoExtenContractMaker extends ContractMaker {
    private const wordDummySealName = "image2.png";

    protected function processTemplate(int $orderId, array $serviceIds, array $orderInfo): string {

        //get word template file;

        $templateFile = $this->getTemplateFile(env('LOGOAPPLY'));

        $templateProcessor = new TemplateProcessor($templateFile);

        //replace the dummySeal to realSeal
        $realSeal = $this->replaceDummySeal(self::wordDummySealName, $orderInfo['order_firm_id']);
        $templateProcessor->setImageValueC(self::wordDummySealName, $realSeal);


        $cartObj = $this->getCarts($orderId, $serviceIds);
        $cartDetails = $this->restructureCarts($cartObj);
        $orderInfo['order_payment_method_details'] = $this->convertPaymentDetails($orderInfo['order_payment_method_details']);
        $orderInfo['order_totalCHN'] = $this->toChineseNumber($cartDetails['total']);
        $orderInfo['order_total'] = $cartDetails['total'];
        unset($cartDetails['total']);


        //find out how many records need insert to the word table
        $rows = sizeof($cartDetails['name']);

        //clone the Row
        $templateProcessor->cloneRow('service_type', $rows);

        //resture the order info CART parts
        for ($i = 1; $i <= $rows; $i++) {
            $orderInfo['service_name#' . $i] = $cartDetails['name'][$i - 1];
            $orderInfo['service_type#' . $i] = $cartDetails['type'][$i - 1];
            $orderInfo['service_attr#' . $i] = $cartDetails['attr'][$i - 1];
            $orderInfo['service_price#' . $i] = $cartDetails['price'][$i - 1];
        }


        //assign info into word template
        foreach ($orderInfo as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $newFileName = storage_path("contractTemplates\\TMP" . uniqid() . ".docx");

        $templateProcessor->saveAs($newFileName);
        return $newFileName;

    }
    private function restructureCarts($cartObj){

    }
}
