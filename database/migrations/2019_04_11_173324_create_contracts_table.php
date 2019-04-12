<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('contract_id');
            $table->string('contract_name')->unique()->comment('合同的名称,用于生成的合同文件的命名');
            $table->string('contract_file')->nullable()->comment('合同模板的文件名');
            $table->longText('contract_services')->nullable()->comment('合同模板适用于的服务类型:service_id,service_id,....');
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
        Schema::dropIfExists('contracts');
    }
}
