<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->increments('visit_id')->comment('回访记录ID');
            $table->integer('visit_client_id')->comment('回访的客户ID');
            $table->foreign('visit_client_id')->references('clients')->on('client_id');
            $table->integer('visit_by_staff_id')->comment('回访客户的员工ID');
            $table->foreign('visit_by_staff_id')->references('staff')->on('staff_id');
            $table->integer('visit_status')->comment('回访状态');
            $table->foreign('visit_status')->references('visit_status')->on('visit_status_id')->comment('回访状态');
            $table->string('visit_records')->comment('回访记录');
            $table->date('visit_next_date')->comment('下次回访日期');
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
        Schema::dropIfExists('visits');
    }
}
