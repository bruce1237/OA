<?php

namespace App\Http\Controllers\Admin;

use App\Model\Client;
use App\Model\phone;
use App\Model\Template;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Writer\PDF\TCPDF;

class TestController extends Controller
{
    public function tt(){

//        $clients = Client::positions()->where('position_id',1)->get();
//        $user = \App\Model\user::find(1)->phone;
        $user = phone::find(1)->user;


        dd($user);

        return view('admin/test/index',['count'=>10]);

//        $rendererName =  Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = 'D:\website\OA\vendor\phpoffice\phpword\src\PhpWord\Writer\PDF';
//        dd(file_exists('D:\website\OA\public/write.csv'));

//        Settings::setPdfRendererPath($rendererLibraryPath);
//        Settings::setPdfRendererName('TCPDF');
//        Settings::setPdfRenderer($rendererName,$rendererLibraryPath);


//    $template = Template::find(1);
//    $filename = storage_path('contractTemplates\\'.$template->template_file);
//    $filename = storage_path('contractTemplates\tmp333.doc');

//        $newfilename = storage_path('contractTemplates\tmp44333.doc');



        /**********************************变量替换***********************/
//        //指定模板文件
//        $templateProcessor =  new TemplateProcessor($filename);
//        //通过setValue 方法给模板赋值
//        $templateProcessor->setValue('ccf',"OKOK中国人");
//        //保存新word文档
//        $templateProcessor->saveAs($newfilename);
        /**********************************完成变量替换***********************/

//        $word = new PhpWord();
//        $loader = IOFactory::load($newfilename)->getSections();
//        $word = IOFactory::load($filename);

//        $writer = IOFactory::createWriter($word,'PDF');
//        $writer->save(storage_path('contractTemplates\tmp44333.PDF'));

//        dd($loader);


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

}

