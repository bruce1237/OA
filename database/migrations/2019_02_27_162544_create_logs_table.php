<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('log_id')->comment('日志ID');
            $table->integer('log_ref_id')->nullable()->comment('日志对应的ID:3');
            $table->string('log_ref_key')->nullable()->comment('日志对应的属性:client_id');
            $table->string('log_ref_field')->nullable()->comment('日志对应的表:clients');
            $table->longText('log_detail')->nullable()->comment('日志的信息内容JSon格式');
            $table->string('log_created_by')->nullable()->comment('激活日志的操作人');
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
        Schema::dropIfExists('logs');
    }
}
