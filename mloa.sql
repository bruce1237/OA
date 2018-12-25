# Host: localhost  (Version: 5.5.53)
# Date: 2018-12-25 11:35:15
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "admins"
#

INSERT INTO `admins` VALUES (1,1,'张艺凡','ML003','$2y$10$ttyzkSYx11cVYPACT4ep3uhj3KQcleZE6zsPB1Rfo30quyHBpxnha','2018-12-25 11:18:35','XKCmKe360xRQ0IAF4SlsYaLAVIeD5sB3YJp46cuEjxQkTIODQu2bV5ebtw0M',NULL,'2018-12-24 16:08:39',NULL),(2,2,'OA管理员','ML100','$2y$10$fIlY99M1Pt1nG0TTD.iKduY8FGVBmOIUh.49UmV93HCnEzwyVyNOC','2018-12-21 16:28:32','G4vtIYqVHxOeQ408J6kuVX2uiNkoxZrs5zrh56Mwk8qDu3t4teWqTA2B6Mda','2018-12-14 06:18:02','2018-12-14 06:18:02',NULL),(3,5,'周小飞','ML102','$2y$10$f6UVdlfjKCpxh1jokbPFyeoBJGu2dAioXJJu20HSVXOznWkqZ/Dku','2018-12-25 11:16:16',NULL,'2018-12-25 11:16:16','2018-12-25 11:16:16',NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "departments"
#

INSERT INTO `departments` VALUES (1,'总经理部','0',NULL,'2018-12-03 07:12:39','2018-12-03 07:12:39'),(2,'法务部','0',NULL,'2018-12-03 07:12:46','2018-12-03 07:12:46'),(3,'人事部','0',NULL,'2018-12-03 07:12:54','2018-12-03 07:12:54'),(4,'财务部','0',NULL,'2018-12-03 07:13:00','2018-12-03 07:13:00'),(5,'研发部','0',NULL,'2018-12-03 07:13:12','2018-12-03 07:13:12'),(6,'业务一部','0',NULL,'2018-12-03 07:13:30','2018-12-03 07:13:30'),(7,'业务二部','0',NULL,'2018-12-03 07:13:37','2018-12-03 07:13:37'),(8,'32123','0','2018-12-18 07:50:21','2018-12-18 02:43:31','2018-12-18 07:50:21'),(9,'444','0','2018-12-18 07:50:21','2018-12-18 02:45:54','2018-12-18 07:50:21'),(10,'33333','0','2018-12-18 07:50:21','2018-12-18 02:52:15','2018-12-18 07:50:21'),(11,'33333','0','2018-12-18 07:50:21','2018-12-18 02:52:34','2018-12-18 07:50:21'),(12,'33333','0','2018-12-18 07:50:21','2018-12-18 02:52:54','2018-12-18 07:50:21'),(13,'33333','0','2018-12-18 07:50:21','2018-12-18 03:06:17','2018-12-18 07:50:21'),(14,'333333333','0','2018-12-18 07:50:21','2018-12-18 03:08:06','2018-12-18 07:50:21');

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "menus"
#

INSERT INTO `menus` VALUES (1,'人事管理',8,0,'&xe6a0;',NULL,'2018-12-17 08:52:42','2018-12-17 08:52:42'),(2,'桌面',8,0,'&xe6a0;','2018-12-18 05:51:00','2018-12-18 05:50:46','2018-12-18 05:51:00'),(3,'Home',8,0,'&xe6a0;','2018-12-18 06:00:24','2018-12-18 05:52:51','2018-12-18 06:00:24'),(4,'aa',8,0,'&xe6a0;','2018-12-18 07:40:10','2018-12-18 06:02:27','2018-12-18 07:40:10'),(5,'home',8,0,'&xe6a0;','2018-12-18 07:07:12','2018-12-18 07:02:40','2018-12-18 07:07:12'),(6,'销售',4,0,'&xe6a0;',NULL,'2018-12-25 11:18:15','2018-12-25 11:18:15');

#
# Structure for table "migrations"
#

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "migrations"
#

INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_staff_table',1),(2,'2014_10_12_000000_create_users_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(8,'2018_11_16_070446_create_logins_table',1),(17,'2018_11_15_072541_create_staff_achievement_table',2),(18,'2018_11_15_073157_create_staff_family_table',2),(19,'2018_11_15_073601_create_staff_wok_exp_table',2),(20,'2018_11_15_073944_create_staff_edu_history_table',2),(25,'2018_11_19_064009_create_department_table',5),(30,'2018_08_21_082619_create_admins_table',6),(31,'2018_08_22_125141_create_logos_table',6),(32,'2018_08_22_125949_create_logo_flows_table',6),(33,'2018_08_22_125959_create_logo_goods_table',6),(34,'2018_08_22_130007_create_logo_sellers_table',6),(35,'2018_08_23_061135_create_logo_cates_table',6),(36,'2018_08_23_063622_create_logo_type_table',6),(37,'2018_08_23_063644_create_logo_length_table',6),(38,'2018_11_19_021644_create_staff_table',6),(39,'2018_11_19_064009_create_departments_table',6),(40,'2018_11_19_084401_create_positions_table',6),(41,'2018_11_23_015715_create_sub_menus_table',6),(42,'2018_11_23_015723_create_menus_table',6);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "staff"
#

INSERT INTO `staff` VALUES (1,'ML003','张艺凡','0','ML_5c0744fc4c791.jpg','汉','河南','2002-11-30','1','党员','良好','18954677546','18454677534','yifan@milu.com','17744566479','17744899537','艺凡老公','17745477896','17744577456','河南省洛阳市洛龙区世贸中心1116','411221200211301415','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:','家人','获奖时间:\n证书/荣誉:\n颁发机关:','旅游','非常好','很好',8,2500.00,5.00,'1','2016-11-30','MLHT001','2015-10-29','2017-10-29',NULL,'3',0,'0','1','2018-12-05 03:24:44','2018-12-24 16:08:39',NULL),(2,'ML100','OA管理员','1','ML_5c134ae99f0b5.jpg','汉','河南省洛阳市','1988-08-08','1','无','良好','18736277539','18736277539','18736277539@163.com','无','无','无','无','无','河南省洛阳市洛龙区世贸中心1106室','411221198808088808','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:','家庭成员太多','获奖时间:\n证书/荣誉:\n颁发机关:','很广','不错','很好',7,1000.00,10.00,'1','2018-07-10','01000110','2018-07-10','2028-07-10',NULL,'1',0,'100','1','2018-12-14 06:17:13','2018-12-14 06:17:48',NULL),(3,'ml123','王鹏毅','1','ML_5c219360add36.jpg','汉','河南','1992-11-21','0','党员','良好','18745244759',NULL,NULL,'157454774451',NULL,'鹏毅网','鹏毅网','18745477542',NULL,'4112411992112114015','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,6,NULL,NULL,'1','2016-01-01',NULL,NULL,NULL,NULL,'2',0,'3','1','2018-12-25 10:18:08','2018-12-25 10:18:55',NULL),(4,'ML101','王浩亮','1','ML_5c219eb86d578.jpg','汉','河南','1992-01-01','0','党员','良好','18455633215',NULL,NULL,'18455633215',NULL,'老王','父子','18455633215',NULL,'411221199201010011','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,6,NULL,NULL,'1','2018-01-01',NULL,NULL,NULL,NULL,'6',0,'3','1','2018-12-25 11:06:32','2018-12-25 11:06:32',NULL),(5,'ML102','周小飞','1','ML_5c219f99c6a3b.jpg','汉','河南','1991-01-01','0','党员','良好','184745744445',NULL,NULL,'184745744445',NULL,'老周','父子','184745744445',NULL,'4112211991010100112','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,4,NULL,NULL,'1','2018-07-01',NULL,NULL,NULL,NULL,'6',4,'0','1','2018-12-25 11:10:17','2018-12-25 11:10:17',NULL),(6,'ML104','吴寅生','1','ML_5c21a025bdb08.jpg','汉','河南','1992-01-01','0','党员','良好','18745477845',NULL,NULL,'18745477845',NULL,'老吴','父子','18745477845',NULL,'4112211992010100115','起止时间:\n毕业院校:\n专业:\n证明人:\n联系方式:','起止时间:\n单位名称:\n职位:\n离职原因:\n证明人:\n联系方式:',NULL,'获奖时间:\n证书/荣誉:\n颁发机关:',NULL,NULL,NULL,3,NULL,NULL,'1','2018-07-01',NULL,NULL,NULL,NULL,'6',4,'0','1','2018-12-25 11:12:37','2018-12-25 11:12:37',NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "sub_menus"
#

INSERT INTO `sub_menus` VALUES (1,'人事列表','admin/hr',0,1,NULL,'2018-12-17 08:52:57','2018-12-18 01:42:30',NULL),(2,'桌面','admin/home',0,1,NULL,'2018-12-18 05:51:21','2018-12-18 06:00:21','2018-12-18 06:00:21'),(3,'home','admin/home',0,3,NULL,'2018-12-18 05:53:02','2018-12-18 06:00:24','2018-12-18 06:00:24'),(4,'cc','admin/OAMenu',0,4,NULL,'2018-12-18 06:02:33','2018-12-18 07:40:10','2018-12-18 07:40:10'),(5,'admin/home','admin/home',0,5,NULL,'2018-12-18 07:02:51','2018-12-18 07:07:12','2018-12-18 07:07:12'),(6,'销售',NULL,0,6,NULL,'2018-12-25 11:18:22','2018-12-25 11:18:22',NULL);

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

