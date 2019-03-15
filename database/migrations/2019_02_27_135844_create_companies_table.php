<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('company_id')->comment('客户公司ID');
            $table->integer('company_client_id')->comment('客户ID');
            $table->foreign('company_client_id')->references('clients')->on('client_id')->comment('客户ID');
            $table->string('company_name',50)->unllable()->comment('客户公司名称');
            $table->string('company_address',200)->unllable()->comment('客户公司地址');
            $table->string('company_post_code',20)->unllable()->comment('客户公司邮编');
            $table->string('company_website',200)->unllable()->comment('客户公司网址');
            $table->string('company_tax_id',50)->unllable()->comment('客户公司纳税识别号');
            $table->string('company_account_number',50)->unllable()->comment('客户公司开户银行账号');
            $table->string('company_account_address',200)->unllable()->comment('客户公司开户银行地址');
            $table->enum('company_status',[0,1])->unllable()->comment('客户公司状态:0禁用,1正常');
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
        Schema::dropIfExists('companies');
    }
}
