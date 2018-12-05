<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('submenu_name')->comment('子菜单名字');
            $table->string('submenu_url')->nullable()->comment('子菜单链接');
            $table->integer('rank')->default(0)->comment('子菜单序列');
            $table->integer('menu_id')->comment('菜单ID');
            $table->string('submenu_icon')->nullable()->comment('子菜单图标');
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
        Schema::dropIfExists('sub_menus');
    }
}
