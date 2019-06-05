<?php
namespace App\Lib\contractMaker;


class CopyRightContractMaker extends ContractMaker{
    protected $wordDummySealName = "image1.png";
    protected $contractTemplate = 8;
    

    protected function restructureCarts($cartObj) :array
    {
        
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