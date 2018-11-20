<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \Illuminate\Support\Facades\DB::table('admins')->insert([
            'name' => str_random(10),
            'email' => 'qq@qq.com',
            'password' => bcrypt('secret')
        ]); //单个导入

        factory(\App\Model\Admin::class,10)->create(); //批量导入

//
        factory(\App\Model\Logo::class,2000)->create();
        factory(\App\Model\LogoSeller::class,30)->create();
        factory(\App\Model\LogoGoods::class,1000)->create();
        factory(\App\Model\LogoFlow::class,1000)->create();


        $category = [
            "第01类-化学原料", "第02类-颜料油漆", "第03类-日化用品", "第04类-燃料油脂", "第05类-医药用品", "第06类-五金金属", "第07类-机械设备", "第08类-手工用具", "第09类-电子仪器", "第10类-医疗器械", "第11类-家用电器", "第12类-运输工具", "第13类-军火烟火", "第14类-珠宝钟表", "第15类-乐器", "第16类-办公文具", "第17类-橡胶制品", "第18类-箱包皮革", "第19类-建筑材料", "第20类-家具用品", "第21类-厨房洁具", "第22类-绳网袋篷", "第23类-纺织纱线", "第24类-布料床单", "第25类-服装鞋帽", "第26类-花边配饰", "第27类-地毯席垫", "第28类-体育玩具", "第29类-食品", "第30类-方便食品", "第31类-水果花木", "第32类-啤酒饮料", "第33类-酒精饮品", "第34类-烟草烟具", "第35类-广告贸易", "第36类-金融物管", "第37类-建筑修理", "第38类-通讯传媒", "第39类-运输旅行", "第40类-材料加工", "第41类-教育娱乐", "第42类-网站设计", "第43类-餐饮酒店", "第44类-医疗园艺", "第45类-社会服务",
        ];
        foreach ($category as $key=>$value){
            \Illuminate\Support\Facades\DB::table('logo_cates')->insert([

               'category_name'=>$value,
            ]);
        }

        $length =["1个","2个","3个","4个","5个","6个","6个以上"];

        foreach ($length as $value){
            \Illuminate\Support\Facades\DB::table('logo_length')->insert(['name_length'=>$value]);
        }

        $logoType =["中文","中文+英文","中文+拼音","中文+英文+图形","图形","英文+图形","英文+数字","英文","中文+数字","中文+图形",];

        foreach ($logoType as $type){
            \Illuminate\Support\Facades\DB::table('logo_type')->insert(['type'=>$type]);

        }

    }
}
