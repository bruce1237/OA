<?php
namespace App\lib\contractMaker;



class LogoTransferContractMaker extends ContractMaker
{
    protected $wordDummySealName = "image1.png";
    protected $contractTemplate = 5;

    // protected function processTemplate(int $orderId, array $serviceIds, array $orderInfo): string {

    //     //get word template file;

    //     $templateFile = $this->getTemplateFile(env('LOGOCHANGE'));

    //     $templateProcessor = new TemplateProcessor($templateFile);
    //     // new TemplateProcessor($templateFile);

    //     //replace the dummySeal to realSeal
    //     $realSeal = $this->replaceDummySeal(self::wordDummySealName, $orderInfo['order_firm_id']);
    //     $templateProcessor->setImageValueC(self::wordDummySealName, $realSeal);


    //     $cartObj = $this->getCarts($orderId, $serviceIds);
    //     $cartDetails = $this->restructureCarts($cartObj);
    //     $orderInfo['order_payment_method_details'] = $this->convertPaymentDetails($orderInfo['order_payment_method_details']);
    //     $orderInfo['order_totalCHN'] = $this->toChineseNumber($cartDetails['total']);
    //     $orderInfo['order_total'] = $cartDetails['total'];
    //     unset($cartDetails['total']);

    //     //find out how many records need insert to the word table
    //     $rows = sizeof($cartDetails['name']);

    //     //clone the Row
    //     $templateProcessor->cloneRow('service_type', $rows);

    //     //resture the order info CART parts
    //     for ($i = 1; $i <= $rows; $i++) {
    //         $orderInfo['service_name#' . $i] = $cartDetails['name'][$i - 1];
    //         $orderInfo['service_type#' . $i] = $cartDetails['type'][$i - 1];
    //         $orderInfo['service_attr#' . $i] = $cartDetails['attr'][$i - 1];
    //         $orderInfo['service_price#' . $i] = $cartDetails['price'][$i - 1];
    //     }


    //     //assign info into word template
    //     foreach ($orderInfo as $key => $value) {
    //         $templateProcessor->setValue($key, $value);
    //     }
    //     $newFileName = storage_path("contractTemplates\\TMP" . uniqid() . ".docx");
    //     $templateProcessor->saveAs($newFileName);
    //     return $newFileName;
    // }

    /**
     * restructureCarts
     *
     * @param  mixed $cartObj
     *
     * @return void
     * re-construct the cart to the following structure,
     * as the following structure are used for the
     * word template
     * target format:
     * "type" => array:3 [
     *     0 => "商标注册"
     *     1 => "商标注册"
     *     2 => "商标注册"
     * ]
     * "name" => array:3 [
     *     0 => "audi"
     *     1 => "BMW"
     *     2 => "BenZ"
     * ]
     * "attr" => array:3 [
     *     0 => "35类, "
     *     1 => "35类, "
     *     2 => "35类, "
     * ]
     * "price" => array:3 [
     *     0 => 980.0
     *     1 => 980.0
     *     2 => 950.0
     * ]
     * "total" => 2910.0
     * ]
     */
    protected function restructureCarts($cartObj): array
    {
        $orderDetailArray = [
            'type' => [],
            'name' => [],
            'attr' => [],
            'price' => [],
            'total' => 0,
        ];



        foreach ($cartObj as $cart) {
            $attrs = json_decode($cart->service_attributes, true);
            $attrStr = '';
            foreach ($attrs as $attr) {
                $attrStr .= $attr['name'] . ": " . $attr['value'] . PHP_EOL;
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
