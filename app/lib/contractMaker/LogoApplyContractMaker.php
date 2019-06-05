<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-15
 * Time: 10:10
 * Used:
 */

namespace App\Lib\contractMaker;


class LogoApplyContractMaker extends ContractMaker
{
    protected $wordDummySealName = "image1.png";
    protected $contractTemplate = 1;

    /**
     * @param int $orderId
     * @param array $serviceIds
     * @param array $orderInfo
     * @return string
     * @throws ContractException
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     * Used For:
     */

    // protected function processTemplate(int $orderId, array $serviceIds, array $orderInfo): string
    // {

    //     //get word template file;

    //     $templateFile = $this->getTemplateFile(env('LOGOAPPLY'));

    //     $templateProcessor = new TemplateProcessor($templateFile);

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

    protected function restructureCarts($cartObj): array
    {


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
                    } elseif ($attribute['name'] == "注册号") {
                        $cate .= $attribute['value'] . "类, ";
                        $arr[$v->service_category][$key]['attr'] .= "注册号: " . $attribute['value'] . ", ";
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
            'total' => 0,

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
}
