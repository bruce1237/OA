<?php
Route::get('/', ['uses' => 'admin\loginController@loginForm']);
Route::get('/login', ['uses' => 'admin\loginController@loginForm']);
Route::post('/admin/login', ['uses' => 'admin\loginController@login']);

Route::group(['prefix' => '/admin', 'namespace' => 'admin', 'middleware' => 'adminCheck'], function () {


    Route::any('test',['uses'=>'logoController@test']);

    Route::get('home',['uses' => 'HomeController@showDashboard']);
    Route::get('/', ['uses' => 'HomeController@showDashboard']);
    Route::get('info',['uses'=>'loginController@info']);
    Route::post('changePwd',['uses'=>'loginController@changePwd']);
    Route::get('logout',['uses'=>'loginController@logout']);

    /*****************HR**********************/
    Route::get('/hr',['uses'=>'hrController@index']);
    Route::post('/newDepart',['uses'=>'hrController@newDepart']);
    Route::post('/modifyDepart',['uses'=>'hrController@modifyDepart']);
    Route::post('/newPosition',['uses'=>'hrController@newPosition']);
    Route::post('/modifyPositions',['uses'=>'hrController@modifyPositions']);


    /*****************get managers list**********************/
    Route::post('/getManagers',['uses'=>'hrController@getManagers']);

    /*****************get staff Level **********************/
    Route::post('/getStaffLevel',['uses'=>'hrController@getStaffLevel']);

    /*****************get staff Level **********************/
    Route::post('/newStaff',['uses'=>'hrController@newStaff']);

    /*****************get staff info **********************/
    Route::get('staff/{staff_id?}',['uses'=>'hrController@staff']);





    /*****************logo controller**********************/

    Route::get('logo',['uses'=>'logoController@index']);
    Route::post('logoSearch',['uses'=>'logoController@searchLogo']);
    Route::post('logoUpdate',['uses'=>'logoController@updateLogo']);
    Route::delete('logoDelete',['uses'=>'logoController@deleteLogo']);
    Route::post('logoNew',['uses'=>'logoController@newLogo']);
    Route::post('logoImport',['uses'=>'logoController@importLogo']);
    Route::post('databaseImport',['uses'=>'logoController@updateDatabase']);

    //logo Es
    Route::get('logoEs',['uses'=>'EsSearchController@index']);
    Route::post('logoEs',['uses'=>'EsSearchController@search']);

    Route::resource('logoSeller','logoSellerController');



    /*****************Excel Export Pics controller**********************/

    Route::get('excelExport','excelController@index');
    Route::post('excelExport','excelController@export');

    Route::get('download', 'excelController@downloadErrorExcelFile');

    Route::post('excelLogoMysqlImport', 'mysqlController@index');



    /*****************Redis  controller**********************/

    Route::get('Redis','redisController@index');

    /*****************ML  controller**********************/

    Route::get('ML','MLController@index');


    /*****************File  controller**********************/
    Route::get('file','fileController@index');


    /********************ARRAY ************************************/
    Route::get('array','arrayController@index');


    /********************STRING ************************************/
    Route::get('string','stringController@index');



    /********************TEST************************************/
    Route::any('test','testController@tt');


});
