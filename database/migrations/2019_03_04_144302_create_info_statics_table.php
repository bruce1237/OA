<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoStaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_statics', function (Blueprint $table) {
            $table->increments('info_static_id')->comment('主键');
            $table->integer('staff_id')->comment('员工ID');
            $table->foreign('staff_id')->references('staff')->on('staff_id');
            $table->integer('client_id')->comment('客户ID');
            $table->foreign('client_id')->references('client_id')->on('client_id');
            $table->string('info_source')->nullable()->comment('信息来源');
            $table->date('assigned_at')->comment('信息分配日期');
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
        Schema::dropIfExists('info_statics');
    }
}
