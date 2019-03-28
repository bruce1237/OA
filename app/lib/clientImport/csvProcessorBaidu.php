<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-28
 * Time: 11:10
 */

namespace App\Lib\clientImport;


class csvProcessorBaidu extends csvProcessor
{
    public function __construct($FullFilePathName, $infoSourceId, $firmId)
    {
        parent::__construct($FullFilePathName, $infoSourceId, $firmId);
        $this->headerLine = 3;
        $this->colTitle = [
            '姓名' => 'client_name',
            '手机号' => 'client_mobile',
            '商标名称' => 'client_enquiries',
            '日期' => 'enquiry_date'];

    }

}
