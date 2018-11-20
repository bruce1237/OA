<?php

use Illuminate\Database\Seeder;

class LogoTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $logoType =["中文","中文+英文","中文+拼音","中文+英文+图形","图形","英文+图形","英文+数字","英文","中文+数字","中文+图形",];

        foreach ($logoType as $type){
            \Illuminate\Support\Facades\DB::table('logo_type')->insert(['type'=>$type]);

        }

    }
}
