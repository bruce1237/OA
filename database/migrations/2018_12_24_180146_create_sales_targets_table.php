<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->unique()->comment("员工号");
            $table->string('month')->comment("月份");
            $table->decimal('target',9,2)->nullable()->default(0)->comment("当月目标");
            $table->decimal('achieved',9,2)->comment("完成额度");
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
        Schema::dropIfExists('sales_targets');
    }
}
