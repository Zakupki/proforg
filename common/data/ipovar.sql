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
/*Table structure for table `z_course` */

DROP TABLE IF EXISTS `z_course`;

CREATE TABLE `z_course` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `dishid` int(10) NOT NULL,
  `file_name` varchar(55) NOT NULL,
  `calories` int(10) default NULL,
  `active` int(1) NOT NULL default '0',
  `date_create` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `sort` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `FK_z_course-course` (`dishid`),
  CONSTRAINT `FK_z_course-course` FOREIGN KEY (`dishid`) REFERENCES `z_dish` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `z_course` */

/*Table structure for table `z_dish` */

DROP TABLE IF EXISTS `z_dish`;

CREATE TABLE `z_dish` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `detail_text` text,
  `dishtypeid` int(10) default NULL,
  `time` varchar(55) default NULL,
  `steps` varchar(55) default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_z_dish-dishtypeid` (`dishtypeid`),
  CONSTRAINT `FK_z_dish-dishtypeid` FOREIGN KEY (`dishtypeid`) REFERENCES `z_dishtype` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `z_dish` */

/*Table structure for table `z_dishtype` */

DROP TABLE IF EXISTS `z_dishtype`;

CREATE TABLE `z_dishtype` (
  `id` int(10) NOT NULL auto_increment,
  `name` int(55) default NULL,
  `file_name` varchar(255) default NULL,
  `active` int(1) NOT NULL default '0',
  `date_create` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `z_dishtype` */

/*Table structure for table `z_product` */

DROP TABLE IF EXISTS `z_product`;

CREATE TABLE `z_product` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `z_product` */

/*Table structure for table `z_recipe` */

DROP TABLE IF EXISTS `z_recipe`;

CREATE TABLE `z_recipe` (
  `id` int(10) NOT NULL auto_increment,
  `step` int(10) default NULL,
  `preview_text` text,
  `detail_text` text,
  `dishid` int(10) NOT NULL,
  `file_name` varchar(55) default NULL,
  `active` int(1) NOT NULL default '0',
  `userid` int(10) default NULL,
  `advice_text` text,
  PRIMARY KEY  (`id`),
  KEY `FK_z_recipe-dishid` (`dishid`),
  CONSTRAINT `FK_z_recipe-dishid` FOREIGN KEY (`dishid`) REFERENCES `z_dish` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `z_recipe` */

/*Table structure for table `z_user` */

DROP TABLE IF EXISTS `z_user`;

CREATE TABLE `z_user` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `login_attempts` int(10) default NULL,
  `validation_key` varchar(128) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `z_user` */

insert  into `z_user`(`id`,`username`,`password`,`email`,`login_attempts`,`validation_key`) values ('2','orange','123456','bozhok@ukr.net',NULL,'3432b915c57053d3b13fd4cd561629be');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
