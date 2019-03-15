<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('payment_method_id')->comment('付款方式ID');
            $table->integer('firm_id')->comment('对应的子公司ID');
            $table->foreign('firm_id')->references('firm')->on('firm_id');
            $table->string('payment_method_name',20)->comment('付款方式名称');
            $table->longText('payment_method_attributes')->comment('用来存储付款方式的详细信息的json字符串');
            $table->enum('payment_method_status',[0,1])->default(1)->comment('付款方式的状态');
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
        Schema::dropIfExists('payment_methods');
    }
}
