<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogoSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logo_sellers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('sellers name');
            $table->string('tel')->nullable()->comment('sellers telephone no');
            $table->string('wx')->nullable()->comment('sellers weixin no');
            $table->string('mobile')->nullable()->comment('sellers mobile');
            $table->string('address')->nullable()->comment('sellers office address');
            $table->string('post_code',15)->nullable()->comment('sellers post code');
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
        Schema::dropIfExists('logo_sellers');
    }
}
