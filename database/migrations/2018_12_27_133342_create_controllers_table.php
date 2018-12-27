<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControllersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controllers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position_id')->default(0)->comment('职位id');
            $table->string('controller')->comment('控制器名称');
            $table->string('comment')->nullable()->comment('控制器的作用');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('controllers');
    }
}
