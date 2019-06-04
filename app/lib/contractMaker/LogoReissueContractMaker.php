<?php
namespace App\Lib\contractMaker;

class LogoReissueContractMaker extends ContractMaker
{

    protected function restructureCarts($cartObj)
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
            array_push($orderDetailArray['price'], $cart->service->price);
            array_push($orderDetailArray['attr'], $attrStr);
            $orderDetailArray['total'] += $cart->service_price;
        }


        return $orderDetailArray;
    }
}
