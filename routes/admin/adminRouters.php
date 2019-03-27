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
    Route::post('/modifyPosition',['uses'=>'hrController@modifyPosition']);


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
    Route::post('delCF',['uses'=>'accessController@delCF']);



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


    /*************************-*******************/
    /*****************infoDepart信息部*************/
    /*******************************************/
    Route::get('crm_info',['uses'=>'infoDepartController@index']);
    Route::post('crm_info_update',['uses'=>'infoDepartController@infoUpdate']);
    Route::get('infoAssign',['uses'=>'infoAssignController@index']);
    Route::post('assignInfo',['uses'=>'infoAssignController@assignInfo']);
    Route::post('newClient',['uses'=>'clientController@addClient']);
    Route::post('infoStatic',['uses'=>'infoAssignController@infoStatic']);
    Route::get('templateManage',['uses'=>'templateController@index']);
    Route::post('modifyTemplate',['uses'=>'templateController@templateModify']);
    Route::post('uploadClientInfoFile',['uses'=>'infoAssignController@uploadClientInfoFile']);


    /*************************-*******************/
    /*****************client客户部*************/
    /*******************************************/
    Route::any('clientManage/{type?}',['uses'=>'clientController@index']);
    Route::post('getClientDetail',['uses'=>'clientController@getClientDetail']);
    Route::post('acknowledgeClient',['uses'=>'clientController@acknowledgeClient']);
    Route::post('modifyClientInfo',['uses'=>'clientController@modifyClientInfo']);
    Route::post('AddClientVisitData',['uses'=>'clientController@AddClientVisitData']);
    Route::post('addCompany',['uses'=>'clientController@addCompany']);
    Route::post('getCompanyInfo',['uses'=>'clientController@getCompanyInfo']);
    Route::post('modifyCompany',['uses'=>'clientController@modifyCompany']);
    Route::post('getClientList',['uses'=>'clientController@getClientList']);
    Route::post('toPool',['uses'=>'clientController@toPool']);
    Route::post('clientQualificationUploads',['uses'=>'clientController@qualificatesUpload']);
    Route::post('rmClientQLFfile',['uses'=>'clientController@rmClentQLFfile']);
    Route::post('rmCompanyQLFfile',['uses'=>'clientController@rmCompanyQLFfile']);
    Route::post('getStaffByDepart',['uses'=>'clientController@getStaffByDepart']);
    Route::post('batchToPool',['uses'=>'clientController@batchToPool']);
    Route::post('batchToAssign',['uses'=>'clientController@batchToAssign']);
    Route::post('abc',['uses'=>'clientController@abc']);



    /*************************-*******************/
    /*****************通知类*************/
    /*******************************************/
    Route::post('notificationCheck',['uses'=>'notificationController@handler']);


    /*************************-*******************/
    /*****************处理审批信息*************/
    /*******************************************/
    Route::get('approval',['uses'=>'approvalController@index']);
    Route::post('approval',['uses'=>'approvalController@process']);

    /*************************-*******************/
    /*****************设置员工业绩目标*************/
    /*******************************************/
    Route::get('setSalesTarget',['uses'=>'staffController@setTargetIndex']);
    Route::post('updateSalesTarget',['uses'=>'staffController@updateSalesTarget']);
    Route::post('getSalesDetails',['uses'=>'staffController@getSalesDetails']);










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
