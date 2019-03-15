<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id')->comment('订单号');
            $table->integer('order_client_id')->comment('订单对应客户ID');
            $table->foreign('order_client_id')->references('clients')->on('client_id');
            $table->integer('order_staff_id')->comment('制作订单的员工ID');
            $table->foreign('order_staff_id')->references('staff')->on('staff_id');
            $table->integer('order_type_id')->comment('订单类型:版权,专利,商标...');
            $table->foreign('order_type_id')->references('order_types')->on('order_type_id');
            $table->integer('order_firm_id')->comment('订单隶属的子公司ID');
            $table->foreign('order_firm_id')->references('firms')->on('firm_id');
            $table->decimal('order_total',11,2)->default(0.00)->comment('订单总金额');
            $table->enum('order_tax_type',[0,1,2])->default(0)->comment('订单需要发票的类型:0无票,1普票,2专票');
            $table->string('order_tax_ref',20)->nullable()->comment('订单需要发票的发票号码');
            $table->string('order_company_name',50)->comment('订单对应的对方公司名称');
            $table->string('order_company_address',200)->nullable()->comment('订单对应的对方公司的名称');
            $table->string('order_company_tax_ref',50)->nullable()->comment('订单对应的对方公司的纳税识别号');
            $table->string('order_company_account',50)->nullable()->comment('订单对应的对方公司的开户行');
            $table->string('order_company_account_address',200)->nullable()->comment('订单对应的对方公司的开户行地址');
            $table->string('order_contact_name',20)->nullable()->comment('订单联系人姓名');
            $table->string('order_contact_number',20)->nullable()->comment('订单联系人电话');
            $table->string('order_contact_address',200)->nullable()->comment('订单联系人地址');
            $table->string('order_post_addressee',50)->nullable()->comment('订单邮寄收件人姓名');
            $table->string('order_post_contact',20)->nullable()->comment('订单邮寄收件人电话');
            $table->string('order_post_address')->nullable()->comment('订单邮寄地址');
            $table->string('order_post_code')->nullable()->comment('订单邮寄邮编');
            $table->longText('order_memo')->nullable()->comment('订单备注');
            $table->integer('order_payment_method_id')->nullable()->comment('订单付款方式ID');
            $table->foreign('order_payment_method_id')->references('payment_methods')->on('payment_method_id');
            $table->enum('order_settlement',[0,1,2])->default(0)->comment('订单是否结算工资:0:未结算,1:待结算,2:已结算');
            $table->date('order_settled_date')->nullable()->comment('订单结算日期');
            $table->enum('order_taxable',[0,1,2])->default(0)->comment('订单是否需要对方公司的发票:0:不需要,1需要,2已收到,只有当当前订单下面的所有需要发票的小项都已收到是才变为2');
            $table->integer('order_status_code')->comment('订单当前的状态');
            $table->foreign('order_status_code')->references('order_status')->on('order_status_id');
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
        Schema::dropIfExists('orders');
    }
}
