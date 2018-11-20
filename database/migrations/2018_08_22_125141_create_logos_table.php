<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reg_no')->comment("Logo reg number 商标注册号");
            $table->integer('int_cls')->comment("international Category serials 商标国际分类");
            $table->string('logo_name')->comment('logo name 商标名');
            $table->string('logo_img')->comment('logo image 商标图片名');
            $table->string('reg_issue')->nullable()->comment("Logo Issue Number 注册公告期号");
            $table->date('reg_date')->nullable()->comment("logo Issue Date 注册公告日期");
            $table->string('agent')->nullable()->comment("agent 商标代理中介");
            $table->date('app_date')->nullable()->comment("apply Date 申请日期");
            $table->string('applicant_cn')->nullable()->comment("applicant name in chinese 申请人名称中文");
            $table->string('applicant_en')->nullable()->comment("applicant name in english 申请人名称英文");
            $table->string('applicant_id')->nullable()->comment('applicant id no 申请人身份证号');
            $table->string('applicant_share')->nullable()->comment("share applicant  共有申请人");
            $table->string('address_cn')->nullable()->comment("address in chinese 申请人中文");
            $table->string('address_en')->nullable()->comment("address in english 申请人英文");
            $table->date('announcement_date')->nullable()->comment('announcement date 初审公告日期');
            $table->string('announcement_issue')->nullable()->comment('announcement issue no 初审公告期号');
            $table->date('international_date')->nullable()->comment('international reg date 国际注册日期');
            $table->date('post_date')->nullable()->comment('indicated post date 后期指定日期');
            $table->date('private_start')->nullable()->comment('private start date 专用权期限开始日期');
            $table->date('private_end')->nullable()->comment('private end date 专用权期限截止日期');
            $table->date('privilege_date')->nullable()->comment('privillege date 优先权日期');
            $table->enum('category',["一般","特殊","集体","证明"])->default("一般")->comment('category 商标类型: 一般、特殊、集体、证明');
            $table->string('color')->nullable()->comment('specified logo color ');
            $table->enum('trade_type',["转让","授权"])->default("转让")->comment("logo trade type 商标交易类型");
            $table->decimal('price',12,2)->nullable()->comment('logo selling price 销售价格');
            $table->string('name_type')->nullable()->comment("logo name type 中文+拼音 等");
            $table->string('suitable')->nullable()->comment("suitable for ecommerence 天猫,京东,亚马逊,聚美优品,蘑菇街");
            $table->string('logo_length')->nullable()->comment("logo name length 商标名称长度");

            $table->integer('seller_id')->nullable()->unsigned()->comment('logo seller id 销售人ID');
            $table->integer('flow_id')->nullable()->unsigned()->comment('logo flow 商标流程');
            $table->integer('goods_id')->nullable()->unsigned()->comment('logo goods使用商品');

            $table->foreign('seller_id')->references('id')->on('logo_sellers')->onUpdate('cascade');
            $table->foreign('flow_id')->references('logo_id')->on('logo_flows')->onDelete('cascade');
            $table->foreign('goods_id')->references('logo_id')->on('logo_goods')->onDelete('cascade');
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
        Schema::dropIfExists('logos');
    }
}
