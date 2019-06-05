<?php
namespace App\Lib\contractMaker;


use PhpOffice\PhpWord\TemplateProcessor;

class PatCaseContractMaker extends ContractMaker
{
    protected $wordDummySealName = "image1.png";
    protected $contractTemplate = 7;

    public function make(int $orderId, int $contractId, array $serviceIds): string
    {

        // for the patent case, need addional pdf contract: confidential contract,
        // so make the confident contract
        $this->privateMaker($orderId, $contractId, $serviceIds);
        

        return parent::make($orderId, $contractId, $serviceIds);
    }

    /**
     * make the confidential contract for the patent contract ONLY
     *
     * @param integer $orderId
     * @param integer $contractId
     * @param array $serviceIds
     * @return string
     */
    private function privateMaker(int $orderId, int $contractId, array $serviceIds): string
    {
        $orderObj = $this->getOrderInfo($orderId);
        $staffObj = $this->getStaffInfo($orderObj->order_staff_id);
        $firmObj = $this->getFirmInfo($orderObj->order_firm_id);

        $orderInfo = array_merge($orderObj->toArray(), $staffObj->toArray(), $firmObj->toArray());




        // processing the Confidential contract
        $templateFile = storage_path('contractTemplates/confidential.docx');





        // assign info into the tamplatefile 
        $confidentContractFilePathName = $this->processConfidentialContract($templateFile, $orderInfo);
        //set the contactPDF Name
        $confidentPDF = public_path("storage/CRM/Order/REF/{$orderObj->order_id}/专利保密协议.pdf");

        // get contract seal by firm
        $contractSeal =  storage_path("firms/{$firmObj->firm_id}/seal/{$firmObj->firm_id}.png");


        //check if the order Reference folder exist,
        if (!is_dir(public_path("storage/CRM/Order/REF/{$orderObj->order_id}/"))) {
            mkdir(public_path("storage/CRM/Order/REF/{$orderObj->order_id}/"));
        }

        // convert word into pdf with Split-Seal

        $pdfFileName = $this->wordToPDF($confidentContractFilePathName, $confidentPDF);

        // add the pageSeal

        $this->addPageSeal($pdfFileName, $contractSeal, $firmObj->firm_id);
        return $pdfFileName;
    }




    private function processConfidentialContract($templateFile, array $data): string
    {

        $templateProcessor = new TemplateProcessor($templateFile);

        // replace dummySeal in the template file
        $realSeal = $this->replaceDummySeal("image1.png", $data['order_firm_id']);
        $templateProcessor->setImageValueC("image1.png", $realSeal);

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $newFileName = storage_path("contractTemplates\\TMP" . uniqid() . ".docx");
        $templateProcessor->saveAs($newFileName);

        return $newFileName;
    }

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
