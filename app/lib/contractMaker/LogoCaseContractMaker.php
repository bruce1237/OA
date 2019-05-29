<?php
namespace App\lib\contractMaker;

use App\Lib\contractMaker\ContractMaker;
use PhpOffice\PhpWord\TemplateProcessor;

class LogoCaseContractMaker extends ContractMaker
{
    private const wordDummySealName = "image2.png";

    protected function processTemplate(int $orderId, array $serviceIds, array $orderInfo): string
    {

        //ge word template file;
        $templateFile = $this->getTemplateFile(env('LOGOCASE'));

        $templatePrcessor = new TemplateProcessor($templateFile);

        // replace the dummySeal to realSeal
        $realSeal = $this->replaceDummySeal(self::wordDummySealName, $orderInfo['order_firm_id']);
        $templatePrcessor->setImageValueC(self::wordDummySealName, $realSeal);

        $cartObj = $this->getCarts($orderId, $serviceIds);
        $cartDetails = $this->restructureCarts($cartObj);

        $orderInfo['order_payment_method_details'] = $this->convertPaymentDetails($orderInfo['order_payment_method_details']);
        $orderInfo['order_totalCHN'] = $this->toChineseNumber($cartDetails['total']);
        $orderInfo['order_total'] = $cartDetails['total'];
        unset($cartDetails['total']);

        // find out how many records need insert to the word table
        $rows = sizeof($cartDetails['name']);

        // clone the row
        $templatePrcessor->cloneRow('service_type', $rows);

        // resturce the order info CART parts
        for ($i = 1; $i <= $rows; $i++) {
            $orderInfo['service_name#' . $i] = $cartDetails['name'][$i - 1];
            $orderInfo['service_type#' . $i] = $cartDetails['type'][$i - 1];
            $orderInfo['service_attr#' . $i] = $cartDetails['attr'][$i - 1];
            $orderInfo['service_price#'. $i] = $cartDetails['price'][$i -1];
        }


        //assign info into word template
        foreach($orderInfo as $key => $value){
            $templatePrcessor->setValue($key,$value);
        }

        $newFileName = storage_path("contractTemplates\\TMP" . uniqid() . ".docx");
        $templatePrcessor->saveAs($newFileName);
        return $newFileName;
    }

    /**
     * restructureCarts
     *
     * @param  mixed $cartObj
     *
     * @return void
     */
    protected function restructureCarts($cartObj){
        $orderDetailArray = [
            'type' => [],
            'name' => [],
            'attr' => [],
            'price'=> [],
            'total'=>0,
        ];

        foreach ($cartObj as $cart) {
            $attrs = json_decode($cart->service_attributes, true);
            $attrStr ='';
            foreach ($attrs as $attr) {
                $attrStr .= $attr['name'].": ".$attr['value'].PHP_EOL;
            }

            array_push($orderDetailArray['type'], $cart->service_category);
            array_push($orderDetailArray['name'], $cart->service_name);
            array_push($orderDetailArray['price'], $cart->service_price);
            array_push($orderDetailArray['attr'], $attrStr);
            $orderDetailArray['total'] += $cart->service_price;
        }

        return $orderDetailArray;

     }
}
