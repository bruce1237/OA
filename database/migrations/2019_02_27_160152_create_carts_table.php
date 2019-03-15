<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('cart_id')->comment('订单明细ID');
            $table->integer('cart_order_id')->comment('订单ID');
            $table->foreign('cart_order_id')->references('orders')->on('order_id');
            $table->string('cart_ref',50)->nullable()->comment('订单明细对应的唯一号码,由第三方权威机构给出');
            $table->string('cart_name',50)->comment('订单明细名称');
            $table->integer('cart_service_id')->comment('订单明细对应的服务类型');
            $table->foreign('cart_service_id')->references('services')->on('service_id')->comment('订单明细对应的服务类型');
            $table->string('logo_category',2)->nullable()->commnet('如果为商标的话, 商标的类别');
            $table->enum('cart_taxable',[0,1,2])->default(0)->comment('是否需要对方公司开具的发票');
            $table->string('tax_number',20)->nullable()->comment('收到的对方公司开具的发票的发票号');
            $table->date('tax_received_date')->nullable()->comment('收到对方公司开具的发票的日期');
            $table->longText('cart_attribute')->nullable()->comment('订单明细的信息属性');
            $table->decimal('cart_cost',11,2)->nullable()->comment('订单明细的成本');
            $table->decimal('cart_price',11,2)->nullable()->comment('订单明细的销售价格');
            $table->decimal('cart_profit',11,2)->nullable()->comment('订单明细的利润=价格-成本');
            $table->longText('cart_memo')->nullable()->comment('订单明细备注');
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
        Schema::dropIfExists('carts');
    }
}
