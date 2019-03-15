<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_status', function (Blueprint $table) {
            $table->increments('visit_status_id')->comment('回访记录状态ID');
            $table->string('visit_status_name')->comment('回访记录状态');
            $table->enum('visit_status',[0,1])->default(1)->comment('回访记录状态的状态');
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
        Schema::dropIfExists('visit_status');
    }
}
