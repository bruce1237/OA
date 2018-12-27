<?php
Route::get('/', ['uses' => 'admin\loginController@loginForm']);
Route::get('/login', ['uses' => 'admin\loginController@loginForm']);
Route::post('/admin/login', ['uses' => 'admin\loginController@login']);

Route::view('/admin/denied','admin/denied');

Route::group(['prefix' => '/admin', 'namespace' => 'admin', 'middleware' => 'adminCheck'], function () {



    Route::get('home',['uses' => 'HomeController@index']);
    Route::get('/', ['uses' => 'HomeController@index']);
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


    /*****************delete staff info **********************/
    Route::delete('staff/{staff_id?}',['uses'=>'hrController@delStaff']);

    /*****************get staff login info **********************/
    Route::get('getStaffLoginInfo/{staff_id?}',['uses'=>'hrController@getStaffLoginInfo']);

    /*****************save/modify staff login info **********************/
    Route::post('saveStaffLoginInfo',['uses'=>'hrController@saveStaffLoginInfo']);






    /*********************************************/
    /*****************OA Menu Index***************/
    /*********************************************/
    Route::get('OAMenu',['uses'=>'menuController@index']);

    /*****************OA Menu New**********************/
    Route::post('newMenu',['uses'=>'menuController@newMenu']);

    /*****************OA Menu New**********************/
    Route::post('MenuList',['uses'=>'menuController@menuList']);

    /*****************OA Menu New**********************/
    Route::post('getSubMenu',['uses'=>'menuController@submenuList']);

    /*****************OA Menu New**********************/
    Route::post('addSubmenu',['uses'=>'menuController@addSubmenu']);

    /*****************OA Menu New**********************/
    Route::post('menuOrder',['uses'=>'menuController@menuOrder']);

    /*****************OA Menu Del**********************/
    Route::delete('delMenu',['uses'=>'menuController@delMenu']);

    /*****************OA submenu Add Url**********************/
    Route::post('addUrl',['uses'=>'menuController@addUrl']);

    /*****************OA get submenuURL access Control**********************/
//    Route::post('readAccess',['uses'=>'menuController@readAccess']);

    /*****************OA get submenuURL access Control**********************/
//    Route::post('addAccess',['uses'=>'menuController@addAccess']);



    /*********************************************/
    /*****************OA Access Control***********/
    /*********************************************/

    Route::get('AccessControl',['uses'=>'accessController@index']);
    Route::post('getControllers',['uses'=>'accessController@getControllers']);
    Route::post('addController',['uses'=>'accessController@addController']);
    Route::post('getFuncs', ['uses'=>'accessController@getFuncs']);
    Route::post('addFunc',['uses'=>'accessController@addFunc']);
    Route::post('addCommonControllerFuncs',['uses'=>'accessController@addCommonControllerFuncs']);
    Route::post('getAllControllers',['uses'=>'accessController@getAllControllers']);
    Route::post('getControllerFuncs',['uses'=>'accessController@getControllerFuncs']);


    /*********************************************/
    /*****************Sales**********************/
    /*******************************************/

    /*****************Add Daily Sales**********************/
    Route::post("addSales",['uses'=>'salesController@addSales']);


    /*************************-*******************/
    /*****************Desktop********************/
    /*******************************************/
    Route::post('addToDo',['uses'=>'homeController@addToDo']);

    Route::post('delToDo',['uses' =>'homeController@delToDo']);





















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
