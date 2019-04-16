<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\contractMaker\MakeContract;
use App\Model\phone;
use App\Model\Template;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;


class TestController extends Controller {



    public function tt() {


        $makeContract = new MakeContract();
        $makeContract->makeContract(1);

//        $cart = Cart::find(1);
//
//             $attributes=json_decode($cart->service_attributes,true);
//             dd($attributes);
//             foreach ($attributes as $key =>$attribute){
//                 if($attribute['name']=="类别"){
////                     $cart[$key]['ser']
//                 }
//             }


//        $clients = Client::positions()->where('position_id',1)->get();
//        $user = \App\Model\user::find(1)->phone;
//        $user = phone::find(1)->user;


//        dd($user);

        return view('admin/test/index', ['count' => 10]);

//        $rendererName =  Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = 'D:\website\OA\vendor\phpoffice\phpword\src\PhpWord\Writer\PDF';
//        dd(file_exists('D:\website\OA\public/write.csv'));

//        Settings::setPdfRendererPath($rendererLibraryPath);
//        Settings::setPdfRendererName('TCPDF');
//        Settings::setPdfRenderer($rendererName,$rendererLibraryPath);


//    $template = Contract::find(1);
//    $filename = storage_path('contractTemplates\\'.$template->template_file);
        $filename = storage_path('contractTemplates\tmp333.doc');

        $newfilename = storage_path('contractTemplates\tmp44333.doc');


        /**********************************变量替换***********************/
        //指定模板文件
        $templateProcessor = new TemplateProcessor($filename);
        //通过setValue 方法给模板赋值
        $templateProcessor->setValue('ccfv', "OKOK中国人");
        //保存新word文档
        $templateProcessor->saveAs($newfilename);
        /**********************************完成变量替换***********************/

        $word = new PhpWord();
        $loader = IOFactory::load($newfilename)->getSections();
        $word = IOFactory::load($filename);

        $writer = IOFactory::createWriter($word, 'Word2007');
        $writer->save(storage_path('contractTemplates\tmp44333.PDF'));

        dd($loader);


//        $phpWord = new PhpWord();
//
//        //设置默认样式
//        $phpWord->setDefaultFontName('仿宋');//字体
//        $phpWord->setDefaultFontSize(16);//字号
//
//        //添加页面
//        $section = $phpWord->addSection();
//
//
//        //添加目录
//        $styleTOC  = ['tabLeader' => \PhpOffice\PhpWord\Style\TOC::TAB_LEADER_DOT];
//        $styleFont = ['spaceAfter' => 60, 'name' => 'Tahoma', 'size' => 12];
//        $section->addTOC($styleFont, $styleTOC);
//
//        //默认样式
//        $section->addText('Hello PHP!');
//        $section->addTextBreak();//换行符
//
//        //指定的样式
//        $section->addText(
//            'Hello world!',
//            [
//                'name' => '宋体',
//                'size' => 16,
//                'bold' => true,
//            ]
//        );
//        $section->addTextBreak(5);//多个换行符
//
//        //自定义样式
//        $myStyle = 'myStyle';
//        $phpWord->addFontStyle(
//            $myStyle,
//            [
//                'name' => 'Verdana',
//                'size' => 12,
//                'color' => '1BFF32',
//                'bold' => true,
//                'spaceAfter' => 20,
//            ]
//        );
//        $section->addText('Hello laravel!', $myStyle);
//        $section->addText('Hello Vue.js!', $myStyle);
//        $section->addPageBreak();//分页符
//
//        //添加文本资源
//        $textrun = $section->addTextRun();
//        $textrun->addText('加粗', ['bold' => true]);
//        $section->addTextBreak();//换行符
//        $textrun->addText('倾斜', ['italic' => true]);
//        $section->addTextBreak();//换行符
//        $textrun->addText('字体颜色', ['color' => 'AACC00']);
//
//        //列表
//        $listStyle = ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER];
//        $section->addListItem('List Item I', 0, null, 'listType');
//        $section->addListItem('List Item I.a', 1, null, 'listType');
//        $section->addListItem('List Item I.b', 1, null, 'listType');
//        $section->addListItem('List Item I.c', 2, null, 'listType');
//        $section->addListItem('List Item II', 0, null, 'listType');
//        $section->addListItem('List Item II.a', 1, null, 'listType');
//        $section->addListItem('List Item II.b', 1, null, 'listType');
//
//        //超链接
//        $linkStyle = ['color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE];
//        $phpWord->addLinkStyle('myLinkStyle', $linkStyle);
//        $section->addLink('http://www.baidu.com', '百度一下', 'myLinkStyle');
//        $section->addLink('http://www.baidu.com', null, 'myLinkStyle');
//
//        //添加图片
//        $imageStyle = ['width' => 48, 'height' => 64, 'align' => 'center'];
//        $section->addImage(storage_path('contractTemplates/1.jpg'), $imageStyle);
//        $section->addImage(storage_path('contractTemplates/2.jpg'),$imageStyle);
//
//        //添加标题
//        $phpWord->addTitleStyle(1, ['bold' => true, 'color' => '1BFF32', 'size' => 38, 'name' => 'Verdana']);
//        $section->addTitle('标题1', 1);
//        $section->addTitle('标题2', 1);
//        $section->addTitle('标题3', 1);
//
//        //添加表格
//        $styleTable = [
//            'borderColor' => '006699',
//            'borderSize' => 6,
//            'cellMargin' => 50,
//        ];
//        $styleFirstRow = ['bgColor' => '66BBFF'];//第一行样式
//        $phpWord->addTableStyle('myTable', $styleTable, $styleFirstRow);
//
//        $table = $section->addTable('myTable');
//        $table->addRow(400);//行高400
//        $table->addCell(2000)->addText('学号');
//        $table->addCell(2000)->addText('姓名');
//        $table->addCell(2000)->addText('专业');
//        $table->addRow(400);//行高400
//        $table->addCell(2000)->addText('2015123');
//        $table->addCell(2000)->addText('小明');
//        $table->addCell(2000)->addText('计算机科学与技术');
//        $table->addRow(400);//行高400
//        $table->addCell(2000)->addText('2016789');
//        $table->addCell(2000)->addText('小傻');
//        $table->addCell(2000)->addText('教育学技术');
//
//        //页眉与页脚
//        $header = $section->addHeader();
//        $footer = $section->addFooter();
//        $header->addPreserveText('页眉');
//        $footer->addPreserveText('页脚 - 页数 {PAGE} - {NUMPAGES}.');
//
//        //生成的文档为Word2007
//        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $writer->save(storage_path('contractTemplates\tmp777.doc'));


//Settings::setPdfRendererPath(Tcpdf);
//Settings::setPdfRendererName('TCPDF');
//        $filename = storage_path('contractTemplates\tmp777.doc');
//        $word = IOFactory::load($filename);


//        $writer = IOFactory::createWriter($word,'PDF');
//        $writer->save(storage_path('contractTemplates\tmp999.PDF'));

    }

    public function makeTable() {

        $PHPWord = new PhpWord();
// New portrait section
        $section = $PHPWord->addSection();
        $PHPWord->addFontStyle('rStyle', array('bold' => true, 'color' => '000000', 'size' => 16));
        $PHPWord->addParagraphStyle('pStyle', array('align' => 'center'));
        $section->addText('×××公司招聘信息', 'rStyle', 'pStyle');
        $section->addTextBreak(2);

// Define table style arrays
        $styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);


// Add table style
        $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);

// Add table
        $table = $section->addTable('myOwnTableStyle');
        $fontStyle = array('bold' => true, 'align' => 'center');

// Add more rows / cells
        $table->addRow();
        $table->addCell(2000)->addText("单位名称", $fontStyle);
        $table->addCell(3000)->addText("", $fontStyle);
        $table->addCell(2000)->addText("详细地址", $fontStyle);
        $table->addCell(3000)->addText("", $fontStyle);


        $table->addRow();
        $table->addCell(2000)->addText("场所负责人", $fontStyle);
        $table->addCell(3000)->addText("", $fontStyle);
        $table->addCell(2000)->addText("联系电话", $fontStyle);
        $table->addCell(3000)->addText("", $fontStyle);

        $styleTable2 = array('borderColor' => '006699', 'borderLeftSize' => 6, 'borderRightSize' => 6, 'cellMargin' => 80);
        $fontStyle2 = array('align' => 'center');
// Add table style
        $PHPWord->addTableStyle('myOwnTableStyle2', $styleTable2);
        for ($i = 1; $i <= 5; $i++) {
            $table2 = $section->addTable('myOwnTableStyle2');
            $table2->addRow();
            $table2->addCell(10000)->addText("服务岗位" . $i, $fontStyle);
            $table3 = $section->addTable('myOwnTableStyle');
            $table3->addRow();
            $table3->addCell(2000)->addText("岗位内容", $fontStyle2);
            $table3->addCell(3000)->addText("", $fontStyle2);
            $table3->addCell(2000)->addText("需求数量", $fontStyle2);
            $table3->addCell(3000)->addText("", $fontStyle2);
            $table3->addRow();
            $table3->addCell(2000)->addText("服务时数", $fontStyle2);
            $table3->addCell(3000)->addText("", $fontStyle2);
            $table3->addCell(2000)->addText("服务周期", $fontStyle2);
            $table3->addCell(3000)->addText("", $fontStyle2);
        }
        $styleTable3 = array('borderColor' => '006699', 'borderLeftSize' => 6, 'borderBottomSize' => 6, 'borderRightSize' => 6, 'cellMargin' => 80);
        $fontStyle3 = array('align' => 'center');
        $cellStyle3 = array('borderColor' => '006699', 'borderRightSize' => 6);
// Add table style
        $PHPWord->addTableStyle('myOwnTableStyle3', $styleTable3);
        $table4 = $section->addTable('myOwnTableStyle3');
        $table4->addRow(2000);
        $table4->addCell(3333, $cellStyle3)->addText("本单位意见", $fontStyle3);
        $table4->addCell(3333, $cellStyle3)->addText("主管部门意见", $fontStyle3);
        $table4->addCell(3334)->addText("集团总部意见", $fontStyle3);
//Two enter
        $section->addTextBreak(2);
//Add image
//    $section->addImage('logo.jpg', array('width'=>100, 'height'=>100,'align'=>'right'));

        $objWrite = IOFactory::createWriter($PHPWord, 'Word2007');
        $objWrite->save('index.docx');


    }

}

