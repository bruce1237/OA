<?php

use Illuminate\Database\Seeder;

class LogoLengthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $length =["1个","2个","3个","4个","5个","6个","6个以上"];

        foreach ($length as $value){
            \Illuminate\Support\Facades\DB::table('logo_length')->insert(['name_length'=>$value]);
        }
    }
}
