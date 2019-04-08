<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->increments('order_status_id')->comment('订单状态ID');
            $table->string('order_status_name',50)->comment('订单状态的名字');
            $table->enum('order_status',[0,1])->default(1)->comment('订单状态的状态');
            $table->enum('order_status_category',[1,2,3])->default(3)->comment('订单状态的类别: 1:合法性审批,2:有效性审批,3:状态更新');
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
        Schema::dropIfExists('order_status');
    }
}
