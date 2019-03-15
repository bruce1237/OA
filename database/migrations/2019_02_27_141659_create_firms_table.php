<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firms', function (Blueprint $table) {
            $table->increments('firm_id')->comment('子公司ID');
            $table->string('firm_name',50)->nullable()->comment('子公司名称');
            $table->string('firm_unique_id',50)->nullable()->comment('统一社会信用代码号');
            $table->string('firm_contact',50)->nullable()->comment('子公司联系人');
            $table->string('firm_tel',20)->nullable()->comment('子公司联系电话');
            $table->string("firm_address",200)->nullable()->comment('子公司地址');
            $table->string("firm_post_code",200)->nullable()->comment('子公司邮编');
            $table->string('firm_account',50)->nullable()->comment('子公司账号');
            $table->string('firm_account_address',200)->nullable()->comment('子公司开户行地址');
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
        Schema::dropIfExists('firms');
    }
}
