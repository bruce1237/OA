<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('service_id')->comment('产品服务ID');
            $table->string('service_name',50)->comment('产品服务名称');
            $table->decimal('service_cost',11,2)->comment('产品服务成本(元)');
            $table->integer('service_parent_id')->default(0)->comment('产品服务父类ID, 如果为0则为主类,没有父类');
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
        Schema::dropIfExists('services');
    }
}
