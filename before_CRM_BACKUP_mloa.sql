# Host: localhost  (Version: 5.5.53)
# Date: 2019-02-27 16:43:15
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "admins"
#

CREATE TABLE `admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT '员工ID',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工名字名字',
  `staff_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工编号',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '密码',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_staff_no_unique` (`staff_no`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "admins"
#

INSERT INTO `admins` VALUES (1,1,'张艺凡33','ML303','$2y$10$ttyzkSYx11cVYPACT4ep3uhj3KQcleZE6zsPB1Rfo30quyHBpxnha','2019-02-26 14:04:01',NULL,NULL,'2019-02-26 14:04:01',NULL),(2,2,'OA管理员','ML100','$2y$10$fIlY99M1Pt1nG0TTD.iKduY8FGVBmOIUh.49UmV93HCnEzwyVyNOC','2019-02-26 13:50:20','jC2Vdc3e3G24gjku2ZjK4Mvj8yMNdeyKKd8NqB0bG1PAAxcBTfByHYPeRIHf','2018-12-14 06:18:02','2018-12-14 06:18:02',NULL),(3,5,'周小飞','ML102','$2y$10$f6UVdlfjKCpxh1jokbPFyeoBJGu2dAioXJJu20HSVXOznWkqZ/Dku','2019-02-13 16:41:19','9PgMMprnRMzOotMeaY00Ow91xgnGx7JD94FCQIvd5kMPTi9GncJ0EFGt1cjp','2018-12-25 11:16:16','2018-12-25 11:16:16',NULL),(4,7,'崔巧飞','ML202','$2y$10$BkhyOiVb05NkP/jG154Wu.EJlpLKBPIrejvRp4EaqJwcNHKPF8oce','2018-12-27 09:58:26',NULL,'2018-12-27 09:58:26','2018-12-27 09:58:26',NULL);

#
# Structure for table "controllers"
#

CREATE TABLE `controllers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position_id` int(11) DEFAULT '0',
  `controller` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '控制器名称',
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '控制器的作用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "controllers"
#

INSERT INTO `controllers` VALUES (1,0,'AccessController',NULL,NULL,NULL,NULL),(2,0,'homeController',NULL,NULL,NULL,NULL),(3,0,'HRController',NULL,NULL,NULL,NULL),(4,0,'MenuController',NULL,NULL,NULL,NULL),(5,0,'SalesController',NULL,NULL,NULL,NULL),(6,7,'AccessController',NULL,'2018-12-28 09:56:35','2018-12-28 09:56:35',NULL),(7,7,'homeController',NULL,'2018-12-28 09:56:55','2018-12-28 09:56:55',NULL),(8,7,'MenuController',NULL,'2018-12-28 10:40:39','2018-12-28 10:40:39',NULL),(9,7,'test',NULL,'2018-12-28 11:04:13','2018-12-28 11:06:57','2018-12-28 11:06:57'),(10,7,'hrController',NULL,'2018-12-28 11:32:05','2018-12-28 11:32:05',NULL),(11,0,'LoginController',NULL,NULL,NULL,NULL),(12,7,'loginController',NULL,'2018-12-28 16:21:07','2018-12-28 16:21:07',NULL),(13,8,'loginController',NULL,'2018-12-28 16:22:10','2018-12-28 16:22:10',NULL),(14,8,'homeController',NULL,'2018-12-28 16:23:29','2018-12-28 16:23:29',NULL),(15,8,'hrController',NULL,'2018-12-28 16:24:33','2018-12-28 16:24:33',NULL),(16,4,'homeController',NULL,'2019-02-13 14:15:39','2019-02-13 14:15:39',NULL),(17,4,'LoginController',NULL,'2019-02-13 16:41:02','2019-02-13 16:41:02',NULL),(18,4,'MenuController',NULL,'2019-02-13 16:49:39','2019-02-13 16:50:34','2019-02-13 16:50:34');

#
# Structure for table "departments"
#

CREATE TABLE `departments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depart_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '部门名称',
  `depart_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL COMMENT '部门状态，0不可以用，1可用',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "departments"
#

INSERT INTO `departments` VALUES (1,'总经理部','0',NULL,'2018-12-03 07:12:39','2018-12-03 07:12:39'),(2,'法务部','0',NULL,'2018-12-03 07:12:46','2018-12-03 07:12:46'),(3,'人事部','0',NULL,'2018-12-03 07:12:54','2018-12-28 15:09:53'),(4,'财务部','0',NULL,'2018-12-03 07:13:00','2018-12-03 07:13:00'),(5,'研发部','0',NULL,'2018-12-03 07:13:12','2018-12-03 07:13:12'),(6,'业务一部','0',NULL,'2018-12-03 07:13:30','2018-12-03 07:13:30'),(7,'业务二部','0',NULL,'2018-12-03 07:13:37','2018-12-28 15:50:28'),(8,'32123','0','2018-12-18 07:50:21','2018-12-18 02:43:31','2018-12-18 07:50:21'),(9,'444','0','2018-12-28 15:09:53','2018-12-18 02:45:54','2018-12-28 15:09:53'),(10,'10999','0','2018-12-28 15:09:53','2018-12-18 02:52:15','2018-12-28 15:09:53'),(11,'33333','0','2018-12-18 07:50:21','2018-12-18 02:52:34','2018-12-18 07:50:21'),(13,'33333','0','2018-12-18 07:50:21','2018-12-18 03:06:17','2018-12-18 07:50:21'),(16,'12','0','2018-12-28 15:50:04','2018-12-18 02:52:54','2018-12-28 15:50:04');

#
# Structure for table "functions"
#

CREATE TABLE `functions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `controller_id` int(11) NOT NULL DEFAULT '0' COMMENT '控制器ID',
  `function` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '方法名',
  `comment` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '方法的作用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=158 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "functions"
#

INSERT INTO `functions` VALUES (29,1,'index','显示权限控制主界面',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(30,1,'getControllers','获取指定职位的控制器',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(31,1,'addController','添加指定职位的控制器',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(32,1,'getFuncs','获取指定控制器下的所有方法和当前控制器的名字',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(33,1,'addFunc','为指定的控制器添加方法',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(34,1,'addCommonControllerFuncs','为权限控制总表添加控制器和方法',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(35,1,'getAllControllers','获取权限总表中的控制器',NULL,'2018-12-28 11:07:55','2018-12-28 11:07:55'),(36,2,'index','显示桌面页面',NULL,NULL,NULL),(37,2,'getMenuList','获取登录用户的菜单列表',NULL,NULL,NULL),(38,2,'birthDayReminder','员工生日提醒',NULL,NULL,NULL),(39,2,'addToDo','添加待办事项',NULL,NULL,NULL),(40,2,'getToDoList','获取当前用户的待办事项',NULL,NULL,NULL),(41,2,'delToDo','删除待办事项',NULL,NULL,NULL),(42,3,'index','显示人事主页面',NULL,NULL,NULL),(43,3,'newDepart','添加部门',NULL,NULL,NULL),(44,3,'modifyDepart','修改部门',NULL,NULL,NULL),(45,3,'newPosition','添加职位',NULL,NULL,NULL),(46,3,'modifyPosition','修改职位',NULL,NULL,NULL),(47,3,'getManagers','获取经理列表',NULL,NULL,NULL),(48,3,'getStaffLevel','获取员工的行政级别',NULL,NULL,NULL),(49,3,'newStaff','添加新员工',NULL,NULL,NULL),(50,3,'staff','获取指定员工的信息',NULL,NULL,NULL),(51,3,'delStaff','删除指定员工',NULL,NULL,NULL),(52,3,'getStaffLoginInfo','获取登录信息',NULL,NULL,NULL),(53,3,'saveStaffLoginInfo','设置员工的登录信息',NULL,NULL,NULL),(54,4,'index','显示菜单的主页',NULL,NULL,NULL),(55,4,'menuList','获取菜单列表',NULL,NULL,NULL),(56,4,'newMenu','新建菜单(指定职位)',NULL,NULL,NULL),(57,4,'submenuList','获取指定菜单的子菜单',NULL,NULL,NULL),(58,4,'addSubmenu','添加指定菜单的子菜单',NULL,NULL,NULL),(59,4,'menuOrder','调整菜单的顺序',NULL,NULL,NULL),(60,4,'delMenu','删除菜单',NULL,NULL,NULL),(61,4,'addUrl','为子菜单添加URL链接',NULL,NULL,NULL),(62,5,'addSales','添加业绩',NULL,NULL,NULL),(63,5,'updateSalesTarget','更新业绩',NULL,NULL,NULL),(64,5,'monthlySales','月业绩统计',NULL,NULL,NULL),(65,6,'index',NULL,'2018-12-28 09:56:47','2018-12-28 09:56:47',NULL),(66,6,'getControllers',NULL,'2018-12-28 09:57:09','2018-12-28 09:57:09',NULL),(67,6,'addController',NULL,'2018-12-28 09:57:12','2018-12-28 09:57:12',NULL),(68,6,'getFuncs',NULL,'2018-12-28 09:57:19','2018-12-28 09:57:19',NULL),(69,6,'addFunc',NULL,'2018-12-28 09:57:24','2018-12-28 09:57:24',NULL),(70,6,'addCommonControllerFuncs',NULL,'2018-12-28 09:57:29','2018-12-28 09:57:29',NULL),(71,6,'getAllControllers',NULL,'2018-12-28 09:57:36','2018-12-28 09:57:36',NULL),(72,7,'index',NULL,'2018-12-28 09:57:45','2018-12-28 09:57:45',NULL),(73,7,'getMenuList',NULL,'2018-12-28 09:59:21','2018-12-28 09:59:21',NULL),(74,7,'birthDayReminder',NULL,'2018-12-28 09:59:28','2018-12-28 09:59:28',NULL),(75,7,'addToDo',NULL,'2018-12-28 09:59:34','2018-12-28 09:59:34',NULL),(76,7,'getToDoList',NULL,'2018-12-28 09:59:39','2018-12-28 09:59:39',NULL),(77,7,'delToDo',NULL,'2018-12-28 09:59:47','2018-12-28 09:59:47',NULL),(78,8,'index',NULL,'2018-12-28 10:40:49','2018-12-28 10:40:49',NULL),(79,8,'menuList',NULL,'2018-12-28 10:40:53','2018-12-28 10:40:53',NULL),(80,8,'newMenu',NULL,'2018-12-28 10:41:57','2018-12-28 10:41:57',NULL),(81,8,'submenuList',NULL,'2018-12-28 10:42:07','2018-12-28 10:42:07',NULL),(82,8,'addSubmenu',NULL,'2018-12-28 10:42:14','2018-12-28 10:42:14',NULL),(83,8,'menuOrder',NULL,'2018-12-28 10:42:20','2018-12-28 10:42:20',NULL),(84,8,'delMenu',NULL,'2018-12-28 10:42:24','2018-12-28 10:42:24',NULL),(85,8,'addUrl',NULL,'2018-12-28 10:42:29','2018-12-28 10:42:29',NULL),(86,9,'test1',NULL,'2018-12-28 11:04:20','2018-12-28 11:06:57','2018-12-28 11:06:57'),(87,9,'test2',NULL,'2018-12-28 11:04:23','2018-12-28 11:06:48','2018-12-28 11:06:48'),(88,6,'delCF',NULL,'2018-12-28 11:04:43','2018-12-28 11:04:43',NULL),(89,1,'index','显示权限控制主界面',NULL,NULL,NULL),(90,1,'getControllers','获取指定职位的控制器',NULL,NULL,NULL),(91,1,'addController','添加指定职位的控制器',NULL,NULL,NULL),(92,1,'getFuncs','获取指定控制器下的所有方法和当前控制器的名字',NULL,NULL,NULL),(93,1,'addFunc','为指定的控制器添加方法',NULL,NULL,NULL),(94,1,'addCommonControllerFuncs','为权限控制总表添加控制器和方法',NULL,NULL,NULL),(95,1,'getAllControllers','获取权限总表中的控制器',NULL,NULL,NULL),(96,1,'delCF','删除指定的控制器和或方法',NULL,NULL,NULL),(97,10,'index',NULL,'2018-12-28 11:32:12','2018-12-28 11:32:12',NULL),(98,6,'test',NULL,'2018-12-28 13:58:45','2018-12-28 13:58:48','2018-12-28 13:58:48'),(99,6,'test',NULL,'2018-12-28 13:59:12','2018-12-28 13:59:14','2018-12-28 13:59:14'),(100,10,'modifyDepart',NULL,'2018-12-28 15:05:22','2018-12-28 15:05:22',NULL),(101,10,'getManagers',NULL,'2018-12-28 15:47:54','2019-02-26 13:52:35','2019-02-26 13:52:35'),(102,11,'loginForm','显示登录界面',NULL,NULL,NULL),(103,11,'login','登录',NULL,NULL,NULL),(104,11,'info','获取登录用的姓名和邮件信息',NULL,NULL,NULL),(105,11,'changePwd','修改登录密码',NULL,NULL,NULL),(106,11,'logout','登出',NULL,NULL,NULL),(107,12,'loginForm',NULL,'2018-12-28 16:21:40','2019-02-26 13:52:45','2019-02-26 13:52:45'),(108,12,'info',NULL,'2018-12-28 16:21:45','2019-02-26 13:52:52','2019-02-26 13:52:52'),(109,12,'login',NULL,'2018-12-28 16:21:48','2019-02-26 13:52:53','2019-02-26 13:52:53'),(110,12,'changePwd',NULL,'2018-12-28 16:21:55','2019-02-26 13:52:54','2019-02-26 13:52:54'),(111,12,'logout',NULL,'2018-12-28 16:22:00','2019-02-26 13:52:54','2019-02-26 13:52:54'),(112,13,'logout',NULL,'2018-12-28 16:22:14','2018-12-28 16:22:14',NULL),(113,13,'changePwd',NULL,'2018-12-28 16:22:20','2018-12-28 16:22:20',NULL),(114,13,'login',NULL,'2018-12-28 16:22:26','2018-12-28 16:22:26',NULL),(115,13,'info',NULL,'2018-12-28 16:22:30','2018-12-28 16:22:30',NULL),(116,13,'loginForm',NULL,'2018-12-28 16:22:33','2018-12-28 16:22:33',NULL),(117,10,'staff',NULL,'2018-12-28 16:22:56','2018-12-28 16:22:56',NULL),(118,14,'index',NULL,'2018-12-28 16:23:37','2018-12-28 16:23:37',NULL),(119,14,'getMenuList',NULL,'2018-12-28 16:23:42','2018-12-28 16:23:42',NULL),(120,14,'birthDayRemionder',NULL,'2018-12-28 16:23:49','2018-12-28 16:23:49',NULL),(121,14,'addToDo',NULL,'2018-12-28 16:23:53','2018-12-28 16:23:53',NULL),(122,14,'getToDoList',NULL,'2018-12-28 16:23:58','2018-12-28 16:23:58',NULL),(123,14,'delToDo',NULL,'2018-12-28 16:24:02','2018-12-28 16:24:02',NULL),(124,15,'index',NULL,'2018-12-28 16:24:51','2018-12-28 16:24:51',NULL),(125,15,'newDepart',NULL,'2018-12-28 16:24:58','2018-12-28 16:24:58',NULL),(126,15,'modifyDepart',NULL,'2018-12-28 16:25:03','2018-12-28 16:25:03',NULL),(127,15,'newPosition',NULL,'2018-12-28 16:25:07','2018-12-28 16:25:07',NULL),(128,15,'modifyPosition',NULL,'2018-12-28 16:25:12','2018-12-28 16:25:12',NULL),(129,15,'getManagers',NULL,'2018-12-28 16:25:16','2018-12-28 16:25:16',NULL),(130,15,'newStaff',NULL,'2018-12-28 16:25:19','2018-12-28 16:25:19',NULL),(131,15,'staff',NULL,'2018-12-28 16:25:22','2018-12-28 16:25:22',NULL),(132,15,'delStaff',NULL,'2018-12-28 16:25:25','2018-12-28 16:25:25',NULL),(133,15,'getStaffLoginInfo',NULL,'2018-12-28 16:25:35','2018-12-28 16:25:35',NULL),(134,15,'saveStaffLoginInfo',NULL,'2018-12-28 16:25:41','2018-12-28 16:25:41',NULL),(135,16,'index',NULL,'2019-02-13 14:15:44','2019-02-13 14:15:44',NULL),(136,16,'index getMenuList',NULL,'2019-02-13 14:15:48','2019-02-13 14:15:48',NULL),(137,17,'logout',NULL,'2019-02-13 16:41:13','2019-02-13 16:41:13',NULL),(138,16,'delToDo',NULL,'2019-02-13 16:47:40','2019-02-13 16:47:40',NULL),(139,16,'addToDo',NULL,'2019-02-13 16:47:47','2019-02-13 16:47:47',NULL),(140,18,'index',NULL,'2019-02-13 16:49:47','2019-02-13 16:50:34','2019-02-13 16:50:34'),(141,18,'menuList',NULL,'2019-02-13 16:50:04','2019-02-13 16:50:34','2019-02-13 16:50:34'),(142,15,'getStaffLevel',NULL,'2019-02-26 13:50:00','2019-02-26 13:50:00',NULL),(143,10,'newStaff',NULL,'2019-02-26 13:51:04','2019-02-26 13:51:04',NULL),(144,10,'getMenuList',NULL,'2019-02-26 13:52:05','2019-02-26 13:52:24','2019-02-26 13:52:24'),(145,10,'birthDayReminder',NULL,'2019-02-26 13:52:07','2019-02-26 13:52:22','2019-02-26 13:52:22'),(146,10,'addToDo',NULL,'2019-02-26 13:52:10','2019-02-26 13:52:21','2019-02-26 13:52:21'),(147,12,'index',NULL,'2019-02-26 13:53:03','2019-02-26 13:53:03',NULL),(148,12,'newDepart',NULL,'2019-02-26 13:53:08','2019-02-26 13:53:08',NULL),(149,12,'modifyDepart',NULL,'2019-02-26 13:53:11','2019-02-26 13:53:11',NULL),(150,12,'newPosition',NULL,'2019-02-26 13:53:13','2019-02-26 13:53:13',NULL),(151,12,'modifyPosition',NULL,'2019-02-26 13:53:16','2019-02-26 13:53:16',NULL),(152,12,'getStaffLevel',NULL,'2019-02-26 13:53:19','2019-02-26 13:53:19',NULL),(153,12,'newStaff',NULL,'2019-02-26 13:53:23','2019-02-26 13:53:23',NULL),(154,12,'staff',NULL,'2019-02-26 13:53:27','2019-02-26 13:53:27',NULL),(155,12,'delStaff',NULL,'2019-02-26 13:53:30','2019-02-26 13:53:30',NULL),(156,12,'getStaffLoginInfo',NULL,'2019-02-26 13:53:33','2019-02-26 13:53:33',NULL),(157,12,'saveStaffLoginInfo',NULL,'2019-02-26 13:53:36','2019-02-26 13:53:36',NULL);

#
# Structure for table "logins"
#

CREATE TABLE `logins` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK 用户ID',
  `staff_id` int(11) NOT NULL COMMENT 'FK 用户员工ID',
  `employee_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户员工编号',
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户登录密码',
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '用户最后一次登录时间',
  `staff_status` enum('0','1') CHARACTER SET utf8 NOT NULL DEFAULT '0' COMMENT '用户状态',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `logins_employee_no_unique` (`employee_no`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logins"
#

INSERT INTO `logins` VALUES (1,1,'ML001','$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm','2018-11-16 15:17:41','1','G5sxBnusMj','2018-11-16 07:17:41','2018-11-16 07:17:41',NULL);

#
# Structure for table "logo_cates"
#

CREATE TABLE `logo_cates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '45类',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logo_cates"
#


#
# Structure for table "logo_flows"
#

CREATE TABLE `logo_flows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `flow_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logo_flows"
#


#
# Structure for table "logo_goods"
#

CREATE TABLE `logo_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` longtext COLLATE utf8_unicode_ci COMMENT 'logo applied goods category name',
  `goods_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'logo applied goods category code',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logo_goods"
#


#
# Structure for table "logo_length"
#

CREATE TABLE `logo_length` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_length` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商标名字长度',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logo_length"
#


#
# Structure for table "logo_sellers"
#

CREATE TABLE `logo_sellers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'sellers name',
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'sellers telephone no',
  `wx` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'sellers weixin no',
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'sellers mobile',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'sellers office address',
  `post_code` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'sellers post code',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logo_sellers"
#


#
# Structure for table "logo_type"
#

CREATE TABLE `logo_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '商标名字类型',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logo_type"
#


#
# Structure for table "logos"
#

CREATE TABLE `logos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reg_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Logo reg number 商标注册号',
  `int_cls` int(11) NOT NULL COMMENT 'international Category serials 商标国际分类',
  `logo_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'logo name 商标名',
  `logo_img` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'logo image 商标图片名',
  `reg_issue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Logo Issue Number 注册公告期号',
  `reg_date` date DEFAULT NULL COMMENT 'logo Issue Date 注册公告日期',
  `agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'agent 商标代理中介',
  `app_date` date DEFAULT NULL COMMENT 'apply Date 申请日期',
  `applicant_cn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'applicant name in chinese 申请人名称中文',
  `applicant_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'applicant name in english 申请人名称英文',
  `applicant_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'applicant id no 申请人身份证号',
  `applicant_share` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'share applicant  共有申请人',
  `address_cn` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'address in chinese 申请人中文',
  `address_en` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'address in english 申请人英文',
  `announcement_date` date DEFAULT NULL COMMENT 'announcement date 初审公告日期',
  `announcement_issue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'announcement issue no 初审公告期号',
  `international_date` date DEFAULT NULL COMMENT 'international reg date 国际注册日期',
  `post_date` date DEFAULT NULL COMMENT 'indicated post date 后期指定日期',
  `private_start` date DEFAULT NULL COMMENT 'private start date 专用权期限开始日期',
  `private_end` date DEFAULT NULL COMMENT 'private end date 专用权期限截止日期',
  `privilege_date` date DEFAULT NULL COMMENT 'privillege date 优先权日期',
  `category` enum('一般','特殊','集体','证明') COLLATE utf8_unicode_ci NOT NULL DEFAULT '一般' COMMENT 'category 商标类型: 一般、特殊、集体、证明',
  `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'specified logo color ',
  `trade_type` enum('转让','授权') COLLATE utf8_unicode_ci NOT NULL DEFAULT '转让' COMMENT 'logo trade type 商标交易类型',
  `price` decimal(12,2) DEFAULT NULL COMMENT 'logo selling price 销售价格',
  `name_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'logo name type 中文+拼音 等',
  `suitable` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'suitable for ecommerence 天猫,京东,亚马逊,聚美优品,蘑菇街',
  `logo_length` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'logo name length 商标名称长度',
  `seller_id` int(10) unsigned DEFAULT NULL COMMENT 'logo seller id 销售人ID',
  `flow_id` int(10) unsigned DEFAULT NULL COMMENT 'logo flow 商标流程',
  `goods_id` int(10) unsigned DEFAULT NULL COMMENT 'logo goods使用商品',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logos_seller_id_foreign` (`seller_id`),
  KEY `logos_flow_id_foreign` (`flow_id`),
  KEY `logos_goods_id_foreign` (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "logos"
#


#
# Structure for table "menus"
#

CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '菜单名称',
  `menu_position` int(11) NOT NULL COMMENT '菜单隶属的职位',
  `rank` int(11) NOT NULL DEFAULT '0' COMMENT '菜单序列',
  `menua_icon` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '&xe6a0;' COMMENT '菜单图标',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "menus"
#

INSERT INTO `menus` VALUES (1,'人事管理',8,0,'&xe6a0;',NULL,'2018-12-17 08:52:42','2018-12-17 08:52:42'),(2,'桌面',8,0,'&xe6a0;','2018-12-18 05:51:00','2018-12-18 05:50:46','2018-12-18 05:51:00'),(3,'Home',8,0,'&xe6a0;','2018-12-18 06:00:24','2018-12-18 05:52:51','2018-12-18 06:00:24'),(4,'aa',8,0,'&xe6a0;','2018-12-18 07:40:10','2018-12-18 06:02:27','2018-12-18 07:40:10'),(5,'home',8,0,'&xe6a0;','2018-12-18 07:07:12','2018-12-18 07:02:40','2018-12-18 07:07:12'),(6,'销售',4,0,'&xe6a0;','2018-12-27 09:42:05','2018-12-25 11:18:15','2018-12-27 09:42:05'),(7,'菜单管理',7,0,'&xe6a0;','2018-12-27 10:44:24','2018-12-27 10:43:56','2018-12-27 10:44:24'),(8,'权限管理',7,0,'&xe6a0;','2018-12-27 10:44:26','2018-12-27 10:44:08','2018-12-27 10:44:26'),(9,'OA管理',7,1,'&xe6a0;',NULL,'2018-12-27 10:44:33','2018-12-28 11:40:52'),(10,'人力资源',7,0,'&xe6a0;',NULL,'2018-12-28 11:31:20','2018-12-28 11:40:52');

#
# Structure for table "migrations"
#

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "migrations"
#

INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_staff_table',1),(2,'2014_10_12_000000_create_users_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(8,'2018_11_16_070446_create_logins_table',1),(17,'2018_11_15_072541_create_staff_achievement_table',2),(18,'2018_11_15_073157_create_staff_family_table',2),(19,'2018_11_15_073601_create_staff_wok_exp_table',2),(20,'2018_11_15_073944_create_staff_edu_history_table',2),(25,'2018_11_19_064009_create_department_table',5),(30,'2018_08_21_082619_create_admins_table',6),(31,'2018_08_22_125141_create_logos_table',6),(32,'2018_08_22_125949_create_logo_flows_table',6),(33,'2018_08_22_125959_create_logo_goods_table',6),(34,'2018_08_22_130007_create_logo_sellers_table',6),(35,'2018_08_23_061135_create_logo_cates_table',6),(36,'2018_08_23_063622_create_logo_type_table',6),(37,'2018_08_23_063644_create_logo_length_table',6),(38,'2018_11_19_021644_create_staff_table',6),(39,'2018_11_19_064009_create_departments_table',6),(40,'2018_11_19_084401_create_positions_table',6),(41,'2018_11_23_015715_create_sub_menus_table',6),(42,'2018_11_23_015723_create_menus_table',6),(43,'2018_12_24_180118_create_sales_table',7),(44,'2018_12_24_180146_create_sales_targets_table',7),(45,'2018_12_26_151128_create_todo_table',8),(46,'2018_12_27_133342_create_controllers_table',9),(47,'2018_12_27_133406_create_functions_table',9);

#
# Structure for table "password_resets"
#

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "password_resets"
#


#
# Structure for table "positions"
#

CREATE TABLE `positions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '职位名称',
  `position_rank` int(11) NOT NULL DEFAULT '0' COMMENT '职位行政等级',
  `position_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1' COMMENT '职位状态',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "positions"
#

INSERT INTO `positions` VALUES (1,'初级顾问',0,'1',NULL,'2018-12-03 07:13:47','2018-12-03 07:13:47'),(2,'中级顾问',0,'1',NULL,'2018-12-03 07:13:53','2018-12-03 07:13:53'),(3,'高级顾问',0,'1',NULL,'2018-12-03 07:13:59','2018-12-03 07:13:59'),(4,'储备主管',0,'1',NULL,'2018-12-03 07:16:04','2018-12-03 07:16:04'),(5,'储备经理',0,'1',NULL,'2018-12-03 07:16:15','2018-12-03 07:16:15'),(6,'部门经理',3,'1',NULL,'2018-12-03 07:16:25','2018-12-03 07:16:25'),(7,'OA管理员',100,'1',NULL,'2018-12-03 07:16:42','2018-12-03 07:18:29'),(8,'人事专员',0,'1',NULL,'2018-12-03 07:17:52','2018-12-03 07:17:52'),(9,'总经理',9,'1',NULL,'2018-12-03 07:18:15','2018-12-03 07:18:15'),(10,'312',0,'1','2018-12-18 07:50:09','2018-12-17 09:30:30','2018-12-18 07:50:09'),(11,'3333',0,'1','2018-12-18 07:50:09','2018-12-18 03:09:01','2018-12-18 07:50:09'),(12,'321',0,'1','2018-12-18 07:50:09','2018-12-18 03:50:43','2018-12-18 07:50:09'),(13,'321',0,'1','2018-12-18 07:50:09','2018-12-18 03:50:49','2018-12-18 07:50:09');

#
# Structure for table "sales"
#

CREATE TABLE `sales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT '员工号',
  `staff_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工姓名',
  `date` date NOT NULL COMMENT '日期',
  `sales` int(9) DEFAULT NULL COMMENT '销售额',
  `department_id` int(11) NOT NULL COMMENT '部门',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "sales"
#

INSERT INTO `sales` VALUES (2,5,'周小飞','2018-12-26',1,6,'2018-12-26 16:45:44',NULL,'2018-12-26 16:45:44'),(3,5,'周小飞','2018-12-10',2720,6,NULL,NULL,NULL),(4,5,'周小飞','2018-12-11',1500,6,NULL,NULL,NULL),(5,5,'周小飞','2018-12-12',2988,6,NULL,NULL,NULL),(6,6,'吴寅生','2018-12-03',1280,6,NULL,NULL,NULL),(7,6,'吴寅生','2018-12-05',1850,6,NULL,NULL,NULL),(8,6,'吴寅生','2018-12-06',1100,6,NULL,NULL,NULL),(9,5,'周小飞','2018-12-13',2300,6,NULL,NULL,NULL),(10,5,'周小飞','2018-12-03',36000,6,NULL,NULL,NULL),(11,5,'周小飞','2018-12-14',1000,6,NULL,NULL,NULL),(12,5,'周小飞','2018-12-17',1100,6,NULL,NULL,NULL),(13,5,'周小飞','2018-12-18',1816,6,NULL,NULL,NULL),(14,5,'周小飞','2018-12-21',550,6,NULL,NULL,NULL),(15,5,'周小飞','2018-12-19',550,6,NULL,NULL,NULL),(16,5,'周小飞','2018-12-24',1500,0,NULL,NULL,NULL),(17,6,'吴寅生','2019-02-03',700,6,NULL,NULL,NULL),(18,6,'吴寅生','2019-02-04',500,6,NULL,NULL,NULL),(19,6,'吴寅生','2019-02-05',1600,6,NULL,NULL,NULL),(20,6,'吴寅生','2019-02-06',550,6,NULL,NULL,NULL),(21,6,'吴寅生','2019-02-07',800,6,NULL,NULL,NULL),(22,6,'吴寅生','2019-02-08',550,6,NULL,NULL,NULL),(23,6,'吴寅生','2019-02-09',820,6,NULL,NULL,NULL),(24,6,'吴寅生','2019-02-10',750,6,NULL,NULL,NULL),(25,6,'吴寅生','2019-02-11',1938,6,NULL,NULL,NULL),(26,6,'吴寅生','2019-02-12',800,6,NULL,NULL,NULL),(27,5,'周小飞','2018-12-26',1,6,'2018-12-26 16:46:01','2018-12-26 16:45:54','2018-12-26 16:46:01'),(28,7,'崔巧飞','2018-12-05',205,7,NULL,NULL,NULL),(29,7,'崔巧飞','2018-12-06',1000,7,NULL,NULL,NULL),(30,7,'崔巧飞','2018-12-07',1200,7,NULL,NULL,NULL),(31,7,'崔巧飞','2018-12-10',10360,7,NULL,NULL,NULL),(32,7,'崔巧飞','2018-12-11',7150,7,NULL,NULL,NULL),(33,7,'崔巧飞','2018-12-12',3244,7,NULL,NULL,NULL),(34,7,'崔巧飞','2018-12-13',7500,7,NULL,NULL,NULL),(35,7,'崔巧飞','2018-12-17',1550,7,NULL,NULL,NULL),(36,7,'崔巧飞','2018-12-18',550,7,NULL,NULL,NULL),(37,7,'崔巧飞','2018-12-19',1350,7,NULL,NULL,NULL),(38,7,'崔巧飞','2018-12-20',1908,7,NULL,NULL,NULL),(39,7,'崔巧飞','2018-12-21',205,7,NULL,NULL,NULL),(40,7,'崔巧飞','2018-12-24',1710,7,NULL,NULL,NULL),(41,7,'崔巧飞','2018-12-26',1488,7,NULL,NULL,NULL),(42,7,'崔巧飞','2018-12-27',3,7,'2018-12-27 10:11:15','2018-12-27 10:10:45','2018-12-27 10:11:15'),(43,7,'崔巧飞','2018-12-27',2,7,'2018-12-27 10:18:02','2018-12-27 10:15:00','2018-12-27 10:18:02'),(44,7,'崔巧飞','2018-12-27',1,7,'2018-12-27 10:19:41','2018-12-27 10:18:35','2018-12-27 10:19:41'),(45,7,'崔巧飞','2018-12-27',1,7,'2018-12-27 10:19:41','2018-12-27 10:22:33','2018-12-27 10:22:33'),(46,7,'崔巧飞','2018-12-27',1,7,'2018-12-27 10:24:12','2018-12-27 10:24:03','2018-12-27 10:24:12');

#
# Structure for table "sales_targets"
#

CREATE TABLE `sales_targets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT '员工号',
  `month` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '月份',
  `target` int(9) DEFAULT NULL COMMENT '当月目标',
  `achieved` int(9) DEFAULT NULL COMMENT '完成额度',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_targets_staff_id_unique` (`staff_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "sales_targets"
#

INSERT INTO `sales_targets` VALUES (1,5,'2018-12',70000,52024,NULL,NULL,'2018-12-26 16:46:01'),(3,6,'2019-02',100000,NULL,NULL,NULL,NULL),(5,7,'2018-12',40000,39420,NULL,NULL,'2018-12-27 10:24:12');

#
# Structure for table "staff"
#

CREATE TABLE `staff` (
  `staff_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PK 员工唯一编号数据库使用',
  `staff_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工编号ML010',
  `staff_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工姓名',
  `staff_gender` enum('0','1') COLLATE utf8_unicode_ci NOT NULL COMMENT '员工性别',
  `staff_photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工照片',
  `staff_nationality` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '汉' COMMENT '员工民族',
  `staff_jiguan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工籍贯',
  `staff_dob` date NOT NULL COMMENT '员工出生日期',
  `staff_marriage` enum('0','1','2','3') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '员工婚姻状况：0未婚，1已婚，2离异，3丧偶',
  `staff_political` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '无' COMMENT '员工政治面貌',
  `staff_healthy` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '良好' COMMENT '员工健康情况',
  `staff_mobile_private` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '员工手机私人',
  `staff_wenxin_private` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工微信私人',
  `staff_email_private` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工邮箱私人',
  `staff_mobile_work` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工手机工作',
  `staff_wenxin_work` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工微信工作',
  `staff_kin_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工紧急联系人姓名',
  `staff_kin_relation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工与紧急联系人关系',
  `staff_kin_mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工紧急联系人手机',
  `staff_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工住址',
  `staff_id_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工身份证号码',
  `staff_edu_history` longtext CHARACTER SET utf8 COMMENT '员工教育经历',
  `staff_work_exp` longtext CHARACTER SET utf8 COMMENT '员工工作经验',
  `staff_family_member` longtext CHARACTER SET utf8 COMMENT '员工家庭成员',
  `staff_achievement` longtext CHARACTER SET utf8 COMMENT '员工成就',
  `staff_hobby` longtext COLLATE utf8_unicode_ci COMMENT '员工个人爱好',
  `staff_assessment` longtext COLLATE utf8_unicode_ci COMMENT '公司主管对员工评价',
  `staff_self_assessment` longtext COLLATE utf8_unicode_ci COMMENT '员工自我评价',
  `position_id` int(11) NOT NULL COMMENT 'FK 员工职位',
  `staff_salary` decimal(10,2) DEFAULT NULL COMMENT '员工工资',
  `staff_commission_rate` decimal(6,2) DEFAULT NULL COMMENT '员工提成',
  `staff_type` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '员工类型：0试用，1正式',
  `staff_join_date` date NOT NULL COMMENT '员工入职日期',
  `staff_contract_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工合同编号',
  `staff_contract_start` date DEFAULT NULL COMMENT '员工合同开始日期',
  `staff_contract_end` date DEFAULT NULL COMMENT '员工合同结束日期',
  `staff_interviewed` int(11) DEFAULT NULL COMMENT '入职面试人',
  `department_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '员工隶属部门',
  `staff_manager` int(11) DEFAULT NULL COMMENT '员工直属领导',
  `staff_level` char(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT '员工等级（0为初级员工，1：一级负责人，2：二级负责人...',
  `staff_status` enum('0','1') COLLATE utf8_unicode_ci NOT NULL COMMENT '员工状态',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`staff_id`),
  UNIQUE KEY `staff_staff_no_unique` (`staff_no`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "staff"
#

INSERT INTO `staff` VALUES (1,'ML303','张艺凡33','0','ML_5c0744fc4c791.jpg','汉','河南','2002-11-30','1','党员','良好','18954677546','18454677534','yifan@milu.com','17744566479','17744899537','艺凡老公','17745477896','17744577456','河南省洛阳市洛龙区世贸中心1116','411221200211301415','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:','家人','获奖时间:\n证书/荣誉:\n颁发机关:','旅游','非常好','很好',8,2500.00,5.00,'1','2016-11-30','MLHT001','2015-10-29','2017-10-29',NULL,'3',0,'0','1','2018-12-05 03:24:44','2019-02-26 14:04:01',NULL),(2,'ML100','OA管理员','1','ML_5c134ae99f0b5.jpg','汉','河南省洛阳市','1988-08-08','1','无','良好','18736277539','18736277539','18736277539@163.com','无','无','无','无','无','河南省洛阳市洛龙区世贸中心1106室','411221198808088808','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:','家庭成员太多','获奖时间:\n证书/荣誉:\n颁发机关:','很广','不错','很好',7,1000.00,10.00,'1','2018-07-10','01000110','2018-07-10','2028-07-10',NULL,'1',0,'100','1','2018-12-14 06:17:13','2018-12-14 06:17:48',NULL),(3,'ml123','王鹏毅','1','ML_5c219360add36.jpg','汉','河南','1992-11-21','0','党员','良好','18745244759',NULL,NULL,'157454774451',NULL,'鹏毅网','鹏毅网','18745477542',NULL,'4112411992112114015','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,6,NULL,NULL,'1','2016-01-01',NULL,NULL,NULL,NULL,'2',0,'3','1','2018-12-25 10:18:08','2018-12-25 10:18:55',NULL),(4,'ML101','王浩亮','1','ML_5c219eb86d578.jpg','汉','河南','1992-01-01','0','党员','良好','18455633215',NULL,NULL,'18455633215',NULL,'老王','父子','18455633215',NULL,'411221199201010011','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,6,NULL,NULL,'1','2018-01-01',NULL,NULL,NULL,NULL,'6',0,'3','1','2018-12-25 11:06:32','2018-12-25 11:06:32',NULL),(5,'ML102','周小飞','1','ML_5c219f99c6a3b.jpg','汉','河南','1991-01-01','0','党员','良好','184745744445',NULL,NULL,'184745744445',NULL,'老周','父子','184745744445',NULL,'4112211991010100112','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,4,NULL,NULL,'1','2018-07-01',NULL,NULL,NULL,NULL,'6',4,'0','1','2018-12-25 11:10:17','2018-12-25 11:10:17',NULL),(6,'ML104','吴寅生','1','ML_5c21a025bdb08.jpg','汉','河南','1992-01-01','0','党员','良好','18745477845',NULL,NULL,'18745477845',NULL,'老吴','父子','18745477845',NULL,'4112211992010100115','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,3,NULL,NULL,'1','2018-07-01',NULL,NULL,NULL,NULL,'6',4,'0','1','2018-12-25 11:12:37','2018-12-25 11:12:37',NULL),(7,'ML202','崔巧飞','0','ML_5c23427998c0a.jpg','汉','河南','1992-01-01','0','党员','良好','18736277539',NULL,NULL,'18745455632',NULL,'老崔','父女','1874544214',NULL,'411221199201011155','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,4,NULL,NULL,'1','2017-07-01',NULL,NULL,NULL,NULL,'7',4,'0','1','2018-12-26 16:53:45','2018-12-26 16:57:29',NULL);

#
# Structure for table "sub_menus"
#

CREATE TABLE `sub_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `submenu_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '子菜单名字',
  `submenu_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '子菜单链接',
  `rank` int(11) NOT NULL DEFAULT '0' COMMENT '子菜单序列',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `submenu_icon` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '子菜单图标',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "sub_menus"
#

INSERT INTO `sub_menus` VALUES (1,'人事列表','admin/hr',0,1,NULL,'2018-12-17 08:52:57','2018-12-18 01:42:30',NULL),(2,'桌面','admin/home',0,1,NULL,'2018-12-18 05:51:21','2018-12-18 06:00:21','2018-12-18 06:00:21'),(3,'home','admin/home',0,3,NULL,'2018-12-18 05:53:02','2018-12-18 06:00:24','2018-12-18 06:00:24'),(4,'cc','admin/OAMenu',0,4,NULL,'2018-12-18 06:02:33','2018-12-18 07:40:10','2018-12-18 07:40:10'),(5,'admin/home','admin/home',0,5,NULL,'2018-12-18 07:02:51','2018-12-18 07:07:12','2018-12-18 07:07:12'),(6,'销售',NULL,0,6,NULL,'2018-12-25 11:18:22','2018-12-27 09:42:04','2018-12-27 09:42:04'),(7,'菜单管理','admin/OAMenu',0,9,NULL,'2018-12-27 10:44:43','2018-12-27 11:21:20',NULL),(8,'权限管理',NULL,0,9,NULL,'2018-12-27 10:44:49','2018-12-27 10:45:22','2018-12-27 10:45:22'),(9,'权限管理','admin/AccessControl',0,9,NULL,'2018-12-27 11:40:05','2018-12-27 11:49:15',NULL),(10,'员工列表','admin/hr',0,10,NULL,'2018-12-28 11:31:32','2018-12-28 11:31:41',NULL);

#
# Structure for table "todo"
#

CREATE TABLE `todo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT '员工ID',
  `event` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '事件',
  `date` date DEFAULT '0000-00-00' COMMENT '提醒日期',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "todo"
#

INSERT INTO `todo` VALUES (1,5,'event','2018-11-04','2019-02-13 16:47:51','2018-12-26 15:20:10','2019-02-13 16:47:51'),(2,5,'event2','2018-12-25',NULL,'2018-12-26 16:14:00','2018-12-27 09:34:07'),(3,5,'event3','2018-12-25',NULL,'2018-12-26 16:14:06','2018-12-27 09:34:01'),(4,5,'event4','2018-12-25',NULL,'2018-12-26 16:14:10','2018-12-27 09:34:06'),(5,5,'event i don\'t even know','2018-12-25',NULL,'2018-12-26 16:14:30','2018-12-27 09:34:08'),(6,5,'event i don\'t even know','2018-12-25',NULL,'2018-12-26 16:14:32','2018-12-27 09:33:51'),(7,5,'event i don\'t even know','2018-12-25',NULL,'2018-12-26 16:14:34','2018-12-27 09:34:04'),(8,5,'event i don\'t even know','2018-12-25',NULL,'2018-12-26 16:14:35','2018-12-27 09:34:00'),(9,1,'123',NULL,'2018-12-27 10:32:06','2018-12-27 10:31:57','2018-12-27 10:32:06'),(10,1,'123','2018-01-02','2018-12-28 16:48:29','2018-12-28 16:28:40','2018-12-28 16:48:29'),(11,2,'321','2018-12-01',NULL,'2018-12-28 16:28:59','2018-12-28 16:28:59'),(12,2,'123','2018-01-12',NULL,'2018-12-28 16:31:46','2018-12-28 16:31:46'),(13,1,'左上角点击新建, 来添加待办事项','2018-12-28',NULL,'2018-12-28 16:48:26','2018-12-28 16:48:26'),(14,5,'33321123123','2018-10-30',NULL,'2019-02-13 16:49:03','2019-02-13 16:49:03');

#
# Structure for table "users"
#

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "users"
#

