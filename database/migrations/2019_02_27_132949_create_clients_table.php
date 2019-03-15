<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('client_id')->comment('客户ID');
            $table->string('client_name',50)->nullable()->comment('客户姓名');
            $table->string('client_address',200)->nullable()->comment('客户地址');
            $table->string('client_post_code',20)->nullable()->comment('客户邮编');
            $table->char('client_belongs_company',2)->nullable()->comment('客户隶属公司:米鹿,知点');
            $table->string('client_mobile',11)->unique()->comment('客户手机');
            $table->string('client_tel',20)->nullable()->comment('客户电话');
            $table->string('client_wechat',20)->nullable()->comment('客户微信');
            $table->string('client_qq',20)->nullable()->comment('客户QQ');
            $table->string('client_email',20)->nullable()->comment('客户电邮');
            $table->integer('client_assign_to')->comment('客户对应员工ID');
            $table->foreign('client_assign_to')->references('staff')->on('staff_id')->comment('客户对应员工ID');
            $table->date('client_assign_date')->comment('信息分配的日期');
            $table->integer('client_added_by')->comment('客户添加员工ID');
            $table->foreign('client_added_by')->references('staff')->on('staff_id')->comment('客户添加员工ID');
            $table->integer('client_level')->default(0)->nullable()->comment('客户等级即客户成交单数');
            $table->enum('client_new_enquiries',[0,1])->default(1)->nullable()->comment('是否有新的诉求:0没有,1有');
            $table->longText('client_enquiries')->nullable()->comment('诉求内容');
            $table->string('client_source',20)->nullable()->comment('客户来源:抖音,百度...');
            $table->enum('client_status',[0,1,2,])->default(1)->nullable()->comment('客户状态:0停用1正常2锁定:用于修改用户信息后等待主管/经理的审批期间');
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
        Schema::dropIfExists('clients');
    }
}
