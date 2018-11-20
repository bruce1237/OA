<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('staff_id')->comment('PK 员工唯一编号数据库使用');
            $table->string('employee_no')->unique()->comment('员工编号ML010');
            $table->string('staff_name')->comment('员工姓名');
            $table->enum('staff_gender', [0, 1])->comment('员工性别');
            $table->string('staff_photo')->comment('员工照片');
            $table->string('staff_nationality')->default('汉')->comment('员工民族');
            $table->string('staff_jiguan')->nullable()->comment('员工籍贯');
            $table->date('staff_dob')->comment('员工出生日期');
            $table->enum('staff_marrage', [0, 1, 2, 3])->default(0)->comment('员工婚姻状况：0未婚，1已婚，2离异，3丧偶');
            $table->string('staff_political')->default('无')->comment('员工政治面貌');
            $table->string('staff_healthy')->default('良好')->comment('员工健康情况');
            $table->string('staff_mobile_private')->comment('员工手机私人');
            $table->string('staff_wenxin_private')->nullable()->comment('员工微信');
            $table->string('staff_mobile_work')->nullable()->comment('员工手机工作');
            $table->string('staff_wenxin_work')->nullable()->comment('员工微信工作');
            $table->string('staff_email')->nullable()->comment('员工邮箱');
            $table->string('staff_kin_name')->nullable()->comment('员工紧急联系人姓名');
            $table->string('staff_kin_relation')->nullable()->comment('员工与紧急联系人关系');
            $table->string('kin_mobile')->nullable()->comment('员工紧急联系人手机');
            $table->string('staff_address')->nullable()->comment('员工住址');
            $table->string('staff_IDNo')->nullable()->comment('员工身份证号码');
            $table->longText('staff_edu_history')->comment('员工教育经历');
            $table->longText('staff_wok_exp')->comment('员工工作经验');
            $table->longText('staff_family_member')->comment('员工家庭成员');
            $table->longText('staff_achievement')->comment('员工成就');
            $table->longText('staff_hobby')->nullable()->comment('员工个人爱好');
            $table->longText('staff_assessment')->nullable()->comment('公司主管对员工评价');
            $table->longText('staff_self_assessment')->nullable()->comment('员工自我评价');
            $table->integer('role_id')->comment('FK 员工职位');
            $table->decimal('staff_salary', 10, 2)->nullable()->comment('员工工资');
            $table->string('staff_contact_no')->nullable()->comment('员工合同编号');
            $table->date('staff_contact_start')->nullable()->comment('员工合同开始日期');
            $table->date('staff_contact_end')->nullable()->comment('员工合同结束日期');
            $table->integer('staff_interviewed')->nullable()->comment('入职面试人');
            $table->string('department_id')->nullable()->comment('员工隶属部门');
            $table->integer('staff_level')->default(0)->comment('员工等级（0为初级员工，1：一级负责人，2：二级负责人...');
            $table->enum('staff_status',[0,1])->comment('员工状态');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('staff');
    }
}
