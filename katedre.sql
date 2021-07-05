/*
SQLyog Community v13.1.5  (64 bit)
MySQL - 10.4.8-MariaDB : Database - katedre
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`katedre` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `katedre`;

/*Table structure for table `katedra` */

DROP TABLE IF EXISTS `katedra`;

CREATE TABLE `katedra` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) DEFAULT NULL,
  `opis` text DEFAULT NULL,
  `sef` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sef` (`sef`),
  CONSTRAINT `katedra_ibfk_1` FOREIGN KEY (`sef`) REFERENCES `profesor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

/*Data for the table `katedra` */

insert  into `katedra`(`id`,`naziv`,`opis`,`sef`) values 
(23,'Teorija sistema','',6),
(24,'Operaciona','',4),
(25,'Matematika','',5),
(26,'Menadzment','afd',8);

/*Table structure for table `profesor` */

DROP TABLE IF EXISTS `profesor`;

CREATE TABLE `profesor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ime` varchar(30) DEFAULT NULL,
  `prezime` varchar(30) DEFAULT NULL,
  `zvanje` bigint(20) DEFAULT NULL,
  `katedra` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `profesor_ibfk_1` (`zvanje`),
  KEY `profesor_ibfk_2` (`katedra`),
  CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`zvanje`) REFERENCES `zvanje` (`id`) ON DELETE SET NULL,
  CONSTRAINT `profesor_ibfk_2` FOREIGN KEY (`katedra`) REFERENCES `katedra` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

/*Data for the table `profesor` */

insert  into `profesor`(`id`,`ime`,`prezime`,`zvanje`,`katedra`) values 
(1,'a','a',1,23),
(2,'Asfdg','Efdsgr',2,26),
(4,'Milan','Martic',5,24),
(5,'Dragan','Djoric',5,25),
(6,'Ana','Poledica',3,23),
(8,'Vladimir','Obradovic',4,26);

/*Table structure for table `zvanje` */

DROP TABLE IF EXISTS `zvanje`;

CREATE TABLE `zvanje` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

/*Data for the table `zvanje` */

insert  into `zvanje`(`id`,`naziv`) values 
(1,'saradnik'),
(2,'asistent'),
(3,'docent'),
(4,'vanredni profesor'),
(5,'redovni profesor');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
