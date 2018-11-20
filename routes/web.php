<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
include "admin/adminRouters.php";
Route::get('/', function () {
    return view('welcome');
});

//Route::get('test',function(){return view('home/test/test');});

Route::group(['namespace'=>'home'],function(){
    
Route::get('test',['uses'=>'TestController@index']);
Route::post('test',['uses'=>'testController@test']);
}
);



