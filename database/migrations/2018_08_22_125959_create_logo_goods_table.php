<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogoGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logo_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('goods_name')->nullable()->comment('logo applied goods category name');
            $table->string('goods_code')->nullable()->comment('logo applied goods category code');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logo_goods');
    }
}
