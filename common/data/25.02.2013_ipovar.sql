/*
SQLyog Ultimate v9.51 
MySQL - 5.0.51a-24+lenny5-log : Database - ipovar
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `gs_auth_assignment` */

DROP TABLE IF EXISTS `gs_auth_assignment`;

CREATE TABLE `gs_auth_assignment` (
  `itemname` varchar(64) collate utf8_unicode_ci NOT NULL,
  `userid` int(12) unsigned NOT NULL,
  `bizrule` text collate utf8_unicode_ci,
  `data` text collate utf8_unicode_ci,
  PRIMARY KEY  (`itemname`,`userid`),
  KEY `fk_auth_assignment_auth_item_idx` (`itemname`),
  KEY `userid` (`userid`),
  CONSTRAINT `fk_auth_assignment_auth_item` FOREIGN KEY (`itemname`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_assignment_user` FOREIGN KEY (`userid`) REFERENCES `gs_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_auth_assignment` */

insert  into `gs_auth_assignment`(`itemname`,`userid`,`bizrule`,`data`) values ('admin','2',NULL,NULL);

/*Table structure for table `gs_auth_item` */

DROP TABLE IF EXISTS `gs_auth_item`;

CREATE TABLE `gs_auth_item` (
  `name` varchar(64) collate utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text collate utf8_unicode_ci,
  `bizrule` text collate utf8_unicode_ci,
  `data` text collate utf8_unicode_ci,
  PRIMARY KEY  (`name`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_auth_item` */

insert  into `gs_auth_item`(`name`,`type`,`description`,`bizrule`,`data`) values ('admin','2',NULL,NULL,NULL);

/*Table structure for table `gs_auth_item_child` */

DROP TABLE IF EXISTS `gs_auth_item_child`;

CREATE TABLE `gs_auth_item_child` (
  `parent` varchar(64) collate utf8_unicode_ci NOT NULL,
  `child` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`parent`,`child`),
  KEY `fk_auth_item_child_parent_auth_item_idx` (`parent`),
  KEY `fk_auth_item_child_child_auth_item_idx` (`child`),
  CONSTRAINT `fk_auth_item_child_child_auth_item` FOREIGN KEY (`child`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_item_child_parent_auth_item` FOREIGN KEY (`parent`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_auth_item_child` */

/*Table structure for table `gs_auth_log` */

DROP TABLE IF EXISTS `gs_auth_log`;

CREATE TABLE `gs_auth_log` (
  `id` int(12) unsigned NOT NULL auto_increment,
  `user_id` int(12) unsigned default NULL,
  `email` varchar(32) collate utf8_unicode_ci default NULL,
  `ip` int(10) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `success` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `fk_auth_log_user` (`user_id`),
  KEY `ip` (`ip`),
  KEY `success_time` (`success`,`time`),
  CONSTRAINT `fk_auth_log_user` FOREIGN KEY (`user_id`) REFERENCES `gs_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_auth_log` */

insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('1','2','admin','1299772196','1361194226','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('2','2','admin','1299772196','1361194392','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('3','2','admin','1299772196','1361194516','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('4','2','admin','1299772196','1361194601','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('5','2','bozhok@ukr.net','1299772196','1361262036','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('6','2','bozhok@ukr.net','1299772196','1361262068','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('7','2','bozhok@ukr.net','1299772196','1361262154','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('8','2','bozhok@ukr.net','1299772196','1361262190','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('9','2','bozhok@ukr.net','1299772196','1361262295','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('10','2','bozhok@ukr.net','1299772196','1361262506','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('11','2','bozhok@ukr.net','1299772196','1361262577','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('12','2','bozhok@ukr.net','1299772196','1361263135','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('13','2','bozhok@ukr.net','1299772196','1361263156','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('14','2','bozhok@ukr.net','1299772196','1361265076','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('15','2','bozhok@ukr.net','1299772196','1361265085','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('16','2','bozhok@ukr.net','1299772196','1361265916','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('17','2','bozhok@ukr.net','1299772196','1361272002','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('18','2','bozhok@ukr.net','1299772196','1361272054','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('19','2','bozhok@ukr.net','1299772196','1361272081','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('20','2','bozhok@ukr.net','1299772196','1361272089','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('21','2','bozhok@ukr.net','1299772196','1361272099','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('22','2','bozhok@ukr.net','1299772196','1361272137','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('23','2','bozhok@ukr.net','1299772196','1361340748','1');
insert  into `gs_auth_log`(`id`,`user_id`,`email`,`ip`,`time`,`success`) values ('24','2','bozhok@ukr.net','1299772196','1361802160','1');

/*Table structure for table `gs_cookware` */

DROP TABLE IF EXISTS `gs_cookware`;

CREATE TABLE `gs_cookware` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `image_id` int(10) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '500',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_cookware-image` (`image_id`),
  CONSTRAINT `FK_gs_cookware-image` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `gs_cookware` */

insert  into `gs_cookware`(`id`,`title`,`image_id`,`status`,`sort`) values ('1','сковорода','27','1','500');
insert  into `gs_cookware`(`id`,`title`,`image_id`,`status`,`sort`) values ('2','дуршлак','28','1','500');

/*Table structure for table `gs_course` */

DROP TABLE IF EXISTS `gs_course`;

CREATE TABLE `gs_course` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `sort` mediumint(9) NOT NULL default '500',
  `status` tinyint(1) NOT NULL default '1',
  `image_id` int(10) default NULL,
  `calories` int(10) default NULL,
  `dishtype_id` int(10) default NULL,
  `dish_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gs_course-imageid` (`image_id`),
  KEY `sort` (`sort`,`status`),
  KEY `FK_gs_course-dishid` (`dish_id`),
  KEY `FK_gs_course-dtypeid` (`dishtype_id`),
  CONSTRAINT `FK_gs_course-dtypeid` FOREIGN KEY (`dishtype_id`) REFERENCES `gs_dishtype` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_gs_course-dishid` FOREIGN KEY (`dish_id`) REFERENCES `gs_dish` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_course-imageid` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `gs_course` */

insert  into `gs_course`(`id`,`title`,`sort`,`status`,`image_id`,`calories`,`dishtype_id`,`dish_id`) values ('1','Борщ','500','1','21','145','6','1');
insert  into `gs_course`(`id`,`title`,`sort`,`status`,`image_id`,`calories`,`dishtype_id`,`dish_id`) values ('2','Суп','500','1',NULL,NULL,NULL,'1');

/*Table structure for table `gs_course_ingredient` */

DROP TABLE IF EXISTS `gs_course_ingredient`;

CREATE TABLE `gs_course_ingredient` (
  `id` int(10) NOT NULL auto_increment,
  `course_id` int(10) NOT NULL,
  `ingredient_id` int(10) NOT NULL,
  `value` int(10) default NULL,
  `sort` mediumint(9) NOT NULL default '500',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_course_ingredient-ingredid` (`ingredient_id`),
  KEY `FK_gs_course_ingredient-courseid` (`course_id`),
  CONSTRAINT `FK_gs_course_ingredient-courseid` FOREIGN KEY (`course_id`) REFERENCES `gs_course` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_course_ingredient-ingredid` FOREIGN KEY (`ingredient_id`) REFERENCES `gs_ingredient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `gs_course_ingredient` */

insert  into `gs_course_ingredient`(`id`,`course_id`,`ingredient_id`,`value`,`sort`) values ('23','1','1','2','500');
insert  into `gs_course_ingredient`(`id`,`course_id`,`ingredient_id`,`value`,`sort`) values ('24','1','2','3','500');
insert  into `gs_course_ingredient`(`id`,`course_id`,`ingredient_id`,`value`,`sort`) values ('25','2','3','22','500');
insert  into `gs_course_ingredient`(`id`,`course_id`,`ingredient_id`,`value`,`sort`) values ('26','2','1','123','500');

/*Table structure for table `gs_delivery` */

DROP TABLE IF EXISTS `gs_delivery`;

CREATE TABLE `gs_delivery` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `detail_text` text,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '500',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `gs_delivery` */

insert  into `gs_delivery`(`id`,`title`,`detail_text`,`status`,`sort`) values ('1','Оплата заказа','При получении заказа. Оплата производится в национальной валюте','1','3');
insert  into `gs_delivery`(`id`,`title`,`detail_text`,`status`,`sort`) values ('2','Доставка на дом','Доставка осуществляется с 10 до 22 часов во все дни кроме воскресенья. Стоимость - 30 грн. Обычно если Вы разместили заказ до 15 часов - мы доставим его в тот же день. В любом случае во время заказа Вы увидите точное время доставки, или сможете указать желаемое. Наши сотрудники не только оперативно доставят заказ по указанному Вами адресу, но и проконсультируют либо ответят на все вопросы. Мы принимаем наличную оплату при получении заказа. При доставке курьер передаст Вам все необходимые документы.','1','2');
insert  into `gs_delivery`(`id`,`title`,`detail_text`,`status`,`sort`) values ('3','Самовызов с нашей кухни','Вы можете забрать заказ самостоятельно из нашей кухни по адресу: Киев, ул. Рижская, 12','1','1');

/*Table structure for table `gs_dish` */

DROP TABLE IF EXISTS `gs_dish`;

CREATE TABLE `gs_dish` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `date_create` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL,
  `detail_text` text,
  `prepare` int(10) default NULL,
  `steps` int(10) default NULL,
  `dishtype_id` int(10) default NULL,
  `price` float(16,2) default '0.00',
  `main` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_dish-dish_typeid` (`dishtype_id`),
  CONSTRAINT `FK_gs_dish-dish_typeid` FOREIGN KEY (`dishtype_id`) REFERENCES `gs_dishtype` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `gs_dish` */

insert  into `gs_dish`(`id`,`title`,`date_create`,`status`,`sort`,`detail_text`,`prepare`,`steps`,`dishtype_id`,`price`,`main`) values ('1','Салат \"Ананас\"','2013-02-13 13:20:10','0','0','Картофель очистить и натереть на крупной терке.\r\nЯйца очистить и порубить.\r\nВетчину нарезать тонкой соломкой.','10','5','4','10.91','1');
insert  into `gs_dish`(`id`,`title`,`date_create`,`status`,`sort`,`detail_text`,`prepare`,`steps`,`dishtype_id`,`price`,`main`) values ('2','Салат \"Белая береза\"','2013-02-19 13:26:25','1','0','',NULL,NULL,'3','0.00','0');

/*Table structure for table `gs_dish_cookware` */

DROP TABLE IF EXISTS `gs_dish_cookware`;

CREATE TABLE `gs_dish_cookware` (
  `id` int(10) NOT NULL auto_increment,
  `dish_id` int(10) NOT NULL,
  `cookware_id` int(10) unsigned NOT NULL,
  `value` int(10) default NULL,
  `sort` mediumint(9) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gs_dish_cookware-dish` (`dish_id`),
  KEY `FK_gs_dish_cookware-coolware` (`cookware_id`),
  CONSTRAINT `FK_gs_dish_cookware-coolware` FOREIGN KEY (`cookware_id`) REFERENCES `gs_cookware` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_dish_cookware-dish` FOREIGN KEY (`dish_id`) REFERENCES `gs_dish` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `gs_dish_cookware` */

/*Table structure for table `gs_dish_image` */

DROP TABLE IF EXISTS `gs_dish_image`;

CREATE TABLE `gs_dish_image` (
  `id` int(10) NOT NULL auto_increment,
  `dish_id` int(10) NOT NULL,
  `image_id` int(10) NOT NULL,
  `sort` mediumint(9) NOT NULL default '555',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_dish_image-dish_id` (`dish_id`),
  KEY `FK_gs_dish_image-image_id` (`image_id`),
  CONSTRAINT `FK_gs_dish_image-dish_id` FOREIGN KEY (`dish_id`) REFERENCES `gs_dish` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_dish_image-image_id` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `gs_dish_image` */

insert  into `gs_dish_image`(`id`,`dish_id`,`image_id`,`sort`) values ('9','1','15','555');
insert  into `gs_dish_image`(`id`,`dish_id`,`image_id`,`sort`) values ('10','2','16','555');
insert  into `gs_dish_image`(`id`,`dish_id`,`image_id`,`sort`) values ('11','2','17','555');

/*Table structure for table `gs_dishtype` */

DROP TABLE IF EXISTS `gs_dishtype`;

CREATE TABLE `gs_dishtype` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `image_id` int(10) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) default '500',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_dishtype-image_id` (`image_id`),
  CONSTRAINT `FK_gs_dishtype-image_id` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

/*Data for the table `gs_dishtype` */

insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('3','Мясо','2','1','1');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('4','Мясо птицы','3','1','2');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('5','Рыба','4','1','3');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('6','Суп','5','1','4');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('7','Паста','6','1','5');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('8','Салат',NULL,'1','500');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('9','Морепродукты',NULL,'1','500');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('10','Десерт',NULL,'1','500');
insert  into `gs_dishtype`(`id`,`title`,`image_id`,`status`,`sort`) values ('11','Овощное',NULL,'1','500');

/*Table structure for table `gs_faq` */

DROP TABLE IF EXISTS `gs_faq`;

CREATE TABLE `gs_faq` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `answer` text,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '500',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `gs_faq` */

insert  into `gs_faq`(`id`,`title`,`answer`,`status`,`sort`) values ('1','А какие продукты вы используете?','Документальная лента Максима Поздоровкина и Майка Лернера «Pussy Riot: панк-молебен» получила специальный приз жюри фестиваля независимого кино Sundance Film Festival. Фильм о девушках, устроивших акцию в Храме Христа Спасителя, прошла в рамках программы мирового документального кино, где было представлено еще 11 фильмов. Sundance Film Festival — один из самых влиятельных смотров независимого кино США основан актером и режиссером Робертом Редфордом и назван в честь его героя в культовом вестерне «Буч Кэссиди и Сандэнс Кид» (1969). Всего в рамках киносмотра было показано 113 полнометражных картин из 32 стран мира. Документальная лента Максима Поздоровкина и Майка Лернера «Pussy Riot: панк-молебен» получила специальный приз жюри фестиваля независимого кино Sundance Film Festival. Фильм о девушках, устроивших акцию в Храме Христа Спасителя, прошла в рамках программы мирового документального кино, где было представлено еще 11 фильмов. Sundance Film Festival — один из самых влиятельных смотров независимого кино США основан актером и режиссером Робертом Редфордом и назван в честь его героя в культовом вестерне «Буч Кэссиди и Сандэнс Кид» (1969). Всего в рамках киносмотра было показано 113 полнометражных картин из 32 стран мира.','1','1');
insert  into `gs_faq`(`id`,`title`,`answer`,`status`,`sort`) values ('2','А можно вернуть протухшие яйца крокодила?','Документальная лента Максима Поздоровкина и Майка Лернера «Pussy Riot: панк-молебен» получила специальный приз жюри фестиваля независимого кино Sundance Film Festival. Фильм о девушках, устроивших акцию в Храме Христа Спасителя, прошла в рамках программы мирового документального кино, где было представлено еще 11 фильмов. Sundance Film Festival — один из самых влиятельных смотров независимого кино США основан актером и режиссером Робертом Редфордом и назван в честь его героя в культовом вестерне «Буч Кэссиди и Сандэнс Кид» (1969). Всего в рамках киносмотра было показано 113 полнометражных картин из 32 стран мира. Документальная лента Максима Поздоровкина и Майка Лернера «Pussy Riot: панк-молебен» получила специальный приз жюри фестиваля независимого кино Sundance Film Festival. Фильм о девушках, устроивших акцию в Храме Христа Спасителя, прошла в рамках программы мирового документального кино, где было представлено еще 11 фильмов. Sundance Film Festival — один из самых влиятельных смотров независимого кино США основан актером и режиссером Робертом Редфордом и назван в честь его героя в культовом вестерне «Буч Кэссиди и Сандэнс Кид» (1969). Всего в рамках киносмотра было показано 113 полнометражных картин из 32 стран мира.','1','2');

/*Table structure for table `gs_file` */

DROP TABLE IF EXISTS `gs_file`;

CREATE TABLE `gs_file` (
  `id` int(10) NOT NULL auto_increment,
  `file` varchar(45) default NULL,
  `path` varchar(66) default NULL,
  `size` int(10) default NULL,
  `width` int(10) default NULL,
  `height` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `gs_file` */

insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('2','img01.png','upload/dishtype/4a','3834','48','42');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('3','img02.png','upload/dishtype/5b','4171','55','44');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('4','img03.png','upload/dishtype/5e','4829','55','51');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('5','img04.png','upload/dishtype/25','5635','70','58');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('6','img05.png','upload/dishtype/6b','4992','69','58');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('7','ico01.png','upload/dishimage/56','1321','40','40');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('8','ico019854.png','upload/dishimage/56','1321','40','40');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('9','img02.png','upload/dishimage/5b','4171','55','44');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('10','ico02.png','upload/dishimage/8a','1476','40','40');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('11','grib.jpg','upload/dishimage/7d','622813','1600','1081');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('12','-.jpg','upload/dishimage/2f','739407','2816','2112');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('13','-4026.jpg','upload/dishimage/2f','3956782','3062','2041');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('14','47.jpg','upload/dishimage/21','115686','1000','750');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('15','-7805.jpg','upload/dishimage/2f','32780','455','390');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('16','-3950.jpg','upload/dishimage/2f','35019','455','390');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('17','474992.jpg','upload/dishimage/21','34678','455','390');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('19','front.jpg','upload/teaser/70','17340','391','390');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('20','ce5ec0cd2a97f4660d538ff396e1fabe.jpg','upload/ingredient/a3','468453','1712','1368');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('21','borsch.jpg','upload/course/77','204040','1199','900');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('23','img_72566100.jpg','upload/step/8d','5532976','4752','3168');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('24','borsch.jpg','upload/step/77','204040','1199','900');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('25','borsch234.jpg','upload/step/77','31099','455','390');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('26','grib.jpg','upload/step/7d','41829','455','390');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('27','img26.png','upload/cookware/1f','5495','201','100');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('28','img27.png','upload/cookware/be','4530','194','98');
insert  into `gs_file`(`id`,`file`,`path`,`size`,`width`,`height`) values ('30','borsch.jpg','upload/page/77','204040','1199','900');

/*Table structure for table `gs_ingredient` */

DROP TABLE IF EXISTS `gs_ingredient`;

CREATE TABLE `gs_ingredient` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(10) NOT NULL default '555',
  `image_id` int(10) default NULL,
  `dimension` varchar(55) default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gs_product-image_id` (`image_id`),
  CONSTRAINT `FK_gs_product-image_id` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `gs_ingredient` */

insert  into `gs_ingredient`(`id`,`title`,`status`,`sort`,`image_id`,`dimension`) values ('1','Помидор','1','555','20','гр');
insert  into `gs_ingredient`(`id`,`title`,`status`,`sort`,`image_id`,`dimension`) values ('2','Сало','1','555',NULL,'л');
insert  into `gs_ingredient`(`id`,`title`,`status`,`sort`,`image_id`,`dimension`) values ('3','Хлеб','1','555',NULL,'кг');

/*Table structure for table `gs_option` */

DROP TABLE IF EXISTS `gs_option`;

CREATE TABLE `gs_option` (
  `id` int(12) unsigned NOT NULL auto_increment,
  `key` varchar(64) collate utf8_unicode_ci NOT NULL,
  `role` varchar(64) collate utf8_unicode_ci default NULL,
  `value` text collate utf8_unicode_ci,
  `title` varchar(256) collate utf8_unicode_ci default NULL,
  `type` varchar(16) character set ascii collate ascii_bin default NULL,
  `serialized` tinyint(1) NOT NULL default '0',
  `i18n` tinyint(1) NOT NULL default '0',
  `group` varchar(64) collate utf8_unicode_ci default NULL,
  `hint` text collate utf8_unicode_ci,
  `sort` mediumint(9) default '500',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `fk_option_auth_item` (`role`),
  KEY `type` (`type`),
  KEY `key_sort` (`key`,`sort`),
  KEY `group` (`group`),
  CONSTRAINT `fk_option_auth_item` FOREIGN KEY (`role`) REFERENCES `gs_auth_item` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_option` */

insert  into `gs_option`(`id`,`key`,`role`,`value`,`title`,`type`,`serialized`,`i18n`,`group`,`hint`,`sort`) values ('1','image.dishimage.image_id','admin','a:1:{s:4:\"size\";s:12:\"455,390,crop\";}','параметры изображения блюда','textArea','1','0','','','500');
insert  into `gs_option`(`id`,`key`,`role`,`value`,`title`,`type`,`serialized`,`i18n`,`group`,`hint`,`sort`) values ('2','image.teaser.image_id','admin','a:1:{s:4:\"size\";s:11:\",390,resize\";}','параметры изображения тизера','textArea','1','0','','','500');
insert  into `gs_option`(`id`,`key`,`role`,`value`,`title`,`type`,`serialized`,`i18n`,`group`,`hint`,`sort`) values ('3','image.step.image_id','admin','a:1:{s:4:\"size\";s:12:\"455,390,crop\";}','параметры изображения шагов','textArea','1','0','','','500');
insert  into `gs_option`(`id`,`key`,`role`,`value`,`title`,`type`,`serialized`,`i18n`,`group`,`hint`,`sort`) values ('4','image.user.image_id','admin','a:1:{s:4:\"size\";s:12:\"172,172,crop\";}','параметры изображения пользователя','textArea','1','0','','','500');

/*Table structure for table `gs_page` */

DROP TABLE IF EXISTS `gs_page`;

CREATE TABLE `gs_page` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `image_id` int(10) default NULL,
  `detail_text` text,
  `sort` mediumint(9) NOT NULL default '500',
  `status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_pages-imageid` (`image_id`),
  CONSTRAINT `FK_gs_pages-imageid` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `gs_page` */

insert  into `gs_page`(`id`,`title`,`image_id`,`detail_text`,`sort`,`status`) values ('1','123','30','test','500','1');

/*Table structure for table `gs_rights` */

DROP TABLE IF EXISTS `gs_rights`;

CREATE TABLE `gs_rights` (
  `itemname` varchar(64) collate utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY  (`itemname`),
  CONSTRAINT `fk_rights_auth_item` FOREIGN KEY (`itemname`) REFERENCES `gs_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_rights` */

/*Table structure for table `gs_step` */

DROP TABLE IF EXISTS `gs_step`;

CREATE TABLE `gs_step` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `preview_text` text,
  `detail_text` text,
  `step` int(10) NOT NULL,
  `advice` text,
  `user_id` int(12) unsigned default NULL,
  `image_id` int(10) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '555',
  `dish_id` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gs_step-dishid` (`dish_id`),
  KEY `FK_gs_step_image` (`image_id`),
  KEY `FK_gs_step-user` (`user_id`),
  CONSTRAINT `FK_gs_step-user` FOREIGN KEY (`user_id`) REFERENCES `gs_user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_gs_step-dishid` FOREIGN KEY (`dish_id`) REFERENCES `gs_dish` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_step_image` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `gs_step` */

insert  into `gs_step`(`id`,`title`,`preview_text`,`detail_text`,`step`,`advice`,`user_id`,`image_id`,`status`,`sort`,`dish_id`) values ('1','test','Preview Text\r\n','Детальная информация\r\n','1','Advice','2','25','1','123','1');
insert  into `gs_step`(`id`,`title`,`preview_text`,`detail_text`,`step`,`advice`,`user_id`,`image_id`,`status`,`sort`,`dish_id`) values ('2','test2','Preview Text\r\n','Детальная информация\r\n','2','Advice','2','26','1','123','1');

/*Table structure for table `gs_teaser` */

DROP TABLE IF EXISTS `gs_teaser`;

CREATE TABLE `gs_teaser` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `link` varchar(255) default NULL,
  `image_id` int(12) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '555',
  PRIMARY KEY  (`id`),
  KEY `FK_gs_teaser-image_id` (`image_id`),
  CONSTRAINT `FK_gs_teaser-image_id` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `gs_teaser` */

insert  into `gs_teaser`(`id`,`title`,`link`,`image_id`,`status`,`sort`) values ('1','123','http://ya.ru','19','1','555');

/*Table structure for table `gs_user` */

DROP TABLE IF EXISTS `gs_user`;

CREATE TABLE `gs_user` (
  `id` int(12) unsigned NOT NULL auto_increment,
  `login` varchar(32) character set ascii collate ascii_bin default NULL,
  `password` varchar(256) collate utf8_unicode_ci default NULL,
  `email` varchar(32) collate utf8_unicode_ci NOT NULL,
  `display_name` varchar(64) collate utf8_unicode_ci default NULL,
  `name` varchar(32) collate utf8_unicode_ci default NULL,
  `phone` varchar(32) collate utf8_unicode_ci default NULL,
  `address` text collate utf8_unicode_ci,
  `status` tinyint(1) NOT NULL default '1',
  `last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `image_id` int(10) default NULL,
  `first_name` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email_type` (`email`),
  UNIQUE KEY `login` (`login`),
  KEY `status` (`status`),
  KEY `FK_gs_user-image` (`image_id`),
  CONSTRAINT `FK_gs_user-image` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `gs_user` */

insert  into `gs_user`(`id`,`login`,`password`,`email`,`display_name`,`name`,`phone`,`address`,`status`,`last_name`,`image_id`,`first_name`) values ('2','admin','$2a$08$vVUv7CtnwC0lP.luj99IzODyzgvy9Sfrouhr9qDit58RRBm5eyofy','bozhok@ukr.net','bozhok@ukr.net','admin',NULL,NULL,'1',NULL,NULL,NULL);
insert  into `gs_user`(`id`,`login`,`password`,`email`,`display_name`,`name`,`phone`,`address`,`status`,`last_name`,`image_id`,`first_name`) values ('3',NULL,NULL,'bozhok2@ukr.net','bozhok2@ukr.net','Повар 1',NULL,NULL,'1',NULL,NULL,NULL);
insert  into `gs_user`(`id`,`login`,`password`,`email`,`display_name`,`name`,`phone`,`address`,`status`,`last_name`,`image_id`,`first_name`) values ('4',NULL,NULL,'bozhok3@ukr.net','bozhok3@ukr.net','Андрей','0931520242',NULL,'1',NULL,NULL,NULL);
insert  into `gs_user`(`id`,`login`,`password`,`email`,`display_name`,`name`,`phone`,`address`,`status`,`last_name`,`image_id`,`first_name`) values ('5',NULL,NULL,'bozhok4@ukr.net','bozhok4@ukr.net','Саша','0931520243',NULL,'1',NULL,NULL,NULL);

/*Table structure for table `gs_user_usertype` */

DROP TABLE IF EXISTS `gs_user_usertype`;

CREATE TABLE `gs_user_usertype` (
  `user_id` int(10) unsigned NOT NULL,
  `usertype_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_id`,`usertype_id`),
  KEY `FK_gs_user_usertype-usertypeid` (`usertype_id`),
  CONSTRAINT `FK_gs_user_usertype-siteid` FOREIGN KEY (`user_id`) REFERENCES `gs_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_user_usertype-usertypeid` FOREIGN KEY (`usertype_id`) REFERENCES `gs_usertype` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `gs_user_usertype` */

insert  into `gs_user_usertype`(`user_id`,`usertype_id`) values ('2','1');
insert  into `gs_user_usertype`(`user_id`,`usertype_id`) values ('3','2');
insert  into `gs_user_usertype`(`user_id`,`usertype_id`) values ('4','3');
insert  into `gs_user_usertype`(`user_id`,`usertype_id`) values ('5','3');

/*Table structure for table `gs_usertype` */

DROP TABLE IF EXISTS `gs_usertype`;

CREATE TABLE `gs_usertype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '500',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `gs_usertype` */

insert  into `gs_usertype`(`id`,`title`,`status`,`sort`) values ('1','Администратор','1','500');
insert  into `gs_usertype`(`id`,`title`,`status`,`sort`) values ('2','Повар','1','500');
insert  into `gs_usertype`(`id`,`title`,`status`,`sort`) values ('3','Менеджер','1','500');

/*Table structure for table `gs_video` */

DROP TABLE IF EXISTS `gs_video`;

CREATE TABLE `gs_video` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `videotype_id` int(10) unsigned NOT NULL,
  `dish_id` int(10) NOT NULL,
  `sort` mediumint(9) NOT NULL default '500',
  `status` tinyint(1) NOT NULL default '1',
  `image_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_gs_video-imageid` (`image_id`),
  KEY `FK_gs_video-dishid` (`dish_id`),
  KEY `FK_gs_video-videotypeid` (`videotype_id`),
  CONSTRAINT `FK_gs_video-dishid` FOREIGN KEY (`dish_id`) REFERENCES `gs_dish` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_gs_video-imageid` FOREIGN KEY (`image_id`) REFERENCES `gs_file` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_gs_video-videotypeid` FOREIGN KEY (`videotype_id`) REFERENCES `gs_videotype` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `gs_video` */

insert  into `gs_video`(`id`,`title`,`url`,`videotype_id`,`dish_id`,`sort`,`status`,`image_id`) values ('1','тест','http://www.youtube.com/watch?v=G1yUP7LbyJM','1','1','500','1',NULL);

/*Table structure for table `gs_videotype` */

DROP TABLE IF EXISTS `gs_videotype`;

CREATE TABLE `gs_videotype` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `status` tinyint(1) NOT NULL default '1',
  `sort` mediumint(9) NOT NULL default '500',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `gs_videotype` */

insert  into `gs_videotype`(`id`,`title`,`status`,`sort`) values ('1','Youtube','1','500');
insert  into `gs_videotype`(`id`,`title`,`status`,`sort`) values ('2','Vimeo','1','500');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
