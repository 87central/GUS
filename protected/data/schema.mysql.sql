-- MySQL dump 10.13  Distrib 5.5.9, for Win32 (x86)
--
-- Host: localhost    Database: gus
-- ------------------------------------------------------
-- Server version	5.5.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `gus`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `gus` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `gus`;

--
-- Table structure for table `authassignment`
--

DROP TABLE IF EXISTS `authassignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authassignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` int(11) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `authassignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authassignment`
--

LOCK TABLES `authassignment` WRITE;
/*!40000 ALTER TABLE `authassignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `authassignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authitem`
--

DROP TABLE IF EXISTS `authitem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authitem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authitem`
--

LOCK TABLES `authitem` WRITE;
/*!40000 ALTER TABLE `authitem` DISABLE KEYS */;
/*!40000 ALTER TABLE `authitem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authitemchild`
--

DROP TABLE IF EXISTS `authitemchild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `authitemchild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `authitemchild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `authitemchild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `authitem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authitemchild`
--

LOCK TABLES `authitemchild` WRITE;
/*!40000 ALTER TABLE `authitemchild` DISABLE KEYS */;
/*!40000 ALTER TABLE `authitemchild` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `credit_card`
--

DROP TABLE IF EXISTS `credit_card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `credit_card` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMER_ID` int(11) NOT NULL,
  `NAME` varchar(45) DEFAULT NULL COMMENT 'A name which customers can use to select the credit card.',
  `NUMBER` varchar(45) DEFAULT NULL,
  `EXPIRATION` varchar(45) DEFAULT NULL,
  `ZIP` varchar(45) DEFAULT NULL,
  `SEC_CODE` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `cc_customer` (`CUSTOMER_ID`),
  CONSTRAINT `cc_customer` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customer` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `credit_card`
--

LOCK TABLES `credit_card` WRITE;
/*!40000 ALTER TABLE `credit_card` DISABLE KEYS */;
/*!40000 ALTER TABLE `credit_card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` int(11) NOT NULL,
  `COMPANY` varchar(45) DEFAULT NULL,
  `NOTES` text,
  `TERMS` text,
  PRIMARY KEY (`ID`),
  KEY `customer_user` (`USER_ID`),
  CONSTRAINT `customer_user` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

LOCK TABLES `customer` WRITE;
/*!40000 ALTER TABLE `customer` DISABLE KEYS */;

/*!40000 ALTER TABLE `customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_log`
--

DROP TABLE IF EXISTS `event_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_log` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OBJECT_ID` int(11) NOT NULL,
  `EVENT_ID` int(11) NOT NULL,
  `DATE` datetime DEFAULT NULL,
  `TIMESTAMP` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `USER_ID` int(11) DEFAULT NULL,
  `USER_ASSIGNED` int(11) DEFAULT NULL,
  `COMMENTS` text,
  `OBJECT_TYPE` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `log_event` (`EVENT_ID`),
  KEY `log_user` (`USER_ID`),
  KEY `log_assigned_user` (`USER_ASSIGNED`),
  CONSTRAINT `log_assigned_user` FOREIGN KEY (`USER_ASSIGNED`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `log_event` FOREIGN KEY (`EVENT_ID`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `log_user` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_log`
--

LOCK TABLES `event_log` WRITE;
/*!40000 ALTER TABLE `event_log` DISABLE KEYS */;

/*!40000 ALTER TABLE `event_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job`
--

DROP TABLE IF EXISTS `job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CUSTOMER_ID` int(11) NOT NULL,
  `LEADER_ID` int(11) DEFAULT NULL,
  `PRINTER_ID` int(11) DEFAULT NULL,
  `PRINT_ID` int(11) DEFAULT NULL,
  `NAME` varchar(45) DEFAULT NULL,
  `DESCRIPTION` text,
  `NOTES` text,
  `ISSUES` text,
  `RUSH` tinyint(1) DEFAULT '0',
  `SET_UP_FEE` decimal(5,2) DEFAULT '0.00',
  `SCORE` int(11) DEFAULT NULL,
  `QUOTE` decimal(6,2) DEFAULT '0.00',
  `STATUS` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `job_customer` (`CUSTOMER_ID`),
  KEY `job_leader` (`LEADER_ID`),
  KEY `job_printer` (`PRINTER_ID`),
  KEY `job_print` (`PRINT_ID`),
  KEY `job_status` (`STATUS`),
  CONSTRAINT `job_status` FOREIGN KEY (`STATUS`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_customer` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customer` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_leader` FOREIGN KEY (`LEADER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_print` FOREIGN KEY (`PRINT_ID`) REFERENCES `print` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_printer` FOREIGN KEY (`PRINTER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job`
--

LOCK TABLES `job` WRITE;
/*!40000 ALTER TABLE `job` DISABLE KEYS */;

/*!40000 ALTER TABLE `job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_line`
--

DROP TABLE IF EXISTS `job_line`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_line` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `JOB_ID` int(11) DEFAULT NULL,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRICE` decimal(5,2) DEFAULT NULL,
  `APPROVAL_DATE` datetime DEFAULT NULL,
  `APPROVAL_USER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `line_job` (`JOB_ID`),
  KEY `line_approver` (`APPROVAL_USER`),
  KEY `line_product` (`PRODUCT_ID`),
  KEY `line_product1` (`PRODUCT_ID`),
  CONSTRAINT `line_approver` FOREIGN KEY (`APPROVAL_USER`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `line_job` FOREIGN KEY (`JOB_ID`) REFERENCES `job` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `line_product1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_line`
--

LOCK TABLES `job_line` WRITE;
/*!40000 ALTER TABLE `job_line` DISABLE KEYS */;

/*!40000 ALTER TABLE `job_line` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup`
--

DROP TABLE IF EXISTS `lookup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TEXT` varchar(60) DEFAULT NULL,
  `EXTENDED` text,
  `POSITION` int(11) DEFAULT NULL,
  `TYPE` varchar(45) NOT NULL,
  `DELETED` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `LOOKUP_TYPE` (`TYPE`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup`
--

LOCK TABLES `lookup` WRITE;
/*!40000 ALTER TABLE `lookup` DISABLE KEYS */;
INSERT INTO `lookup` VALUES (10,'Due Date','CHtml::link($assoc->NAME, array(\'job/update\', \'id\'=>$assoc->ID)).\' for \'.CHtml::link($assoc->CUSTOMER->FIRST, array(\'customer/view\', \'id\'=>$assoc->CUSTOMER->ID)).\' is due on \'.$this->DATE',NULL,'Event',0),(11,'Print Date','CHtml::link($assoc->NAME, array(\'job/update\', \'id\'=>$assoc->ID)).\' for \'.CHtml::link($assoc->CUSTOMER->FIRST, array(\'customer/view\', \'id\'=>$assoc->CUSTOMER->ID)).\' is scheduled to be printed by \'.($this->assigned->ID == Yii::app()->user->id ? \'you\' : $this->assigned->FIRST).\' on \'.$this->DATE',NULL,'Event',0),(12,'Pickup Date','CHtml::link($assoc->NAME, array(\'job/update\', \'id\'=>$assoc->ID)).\' is scheduled to be picked up by \'.CHtml::link($assoc->CUSTOMER->FIRST, array(\'customer/view\', \'id\'=>$assoc->CUSTOMER->ID)).\' on \'.$this->DATE',NULL,'Event',0),(13,'Default',NULL,NULL,'Role',0),(14,'Customer',NULL,NULL,'Role',0),(15,'Admin',NULL,NULL,'Role',0),(16,'In Stock',NULL,NULL,'ProductStatus',0),(17,'On Order',NULL,NULL,'ProductStatus',0),(18,'Backordered',NULL,NULL,'ProductStatus',0),(19,'Out of Stock',NULL,NULL,'ProductStatus',0),(20,'Created',NULL,NULL,'OrderStatus',0),(21,'Ordered',NULL,NULL,'OrderStatus',0),(22,'Arrived',NULL,NULL,'OrderStatus',0),(23,'Order Created','(Yii::app()->user->id == $this->USER_ID ? \'You\' : $this->USER->FIRST).\' created an order, \'.CHtml::link($assoc->name, array(\'order/update\', \'id\'=>$assoc->ID)).\' on \'.$this->DATE',NULL,'Event',0),(24,'Order Placed','\'Your order, \'.CHtml::link($assoc->name, array(\'order/update\', \'id\'=>$assoc->ID)).\', was placed on \'.$this->DATE',NULL,'Event',0),(25,'Order Arrived','\'Your order, \'.CHtml::link($assoc->name, array(\'order/update\', \'id\'=>$assoc->ID)).\', arrived on \'.$this->DATE',NULL,'Event',0),(26,'Created',NULL,NULL,'JobStatus',0),(27,'Paid',NULL,NULL,'JobStatus',0),(28,'Scheduled',NULL,NULL,'JobStatus',0),(29,'Completed',NULL,NULL,'JobStatus',0),(30,'Canceled',NULL,NULL,'JobStatus',0),(31,'Invoice Sent',NULL,NULL,'JobStatus',0),(32,'Placeholder',NULL,NULL,'ProductStatus',0);
/*!40000 ALTER TABLE `lookup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EXTERNAL_ID` varchar(60) DEFAULT NULL,
  `VENDOR_ID` int(11) DEFAULT NULL,
  `DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `order_vendor` (`VENDOR_ID`),
  KEY `order_status` (`STATUS`),
  CONSTRAINT `order_status` FOREIGN KEY (`STATUS`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `order_vendor` FOREIGN KEY (`VENDOR_ID`) REFERENCES `vendor` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;

/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `print`
--

DROP TABLE IF EXISTS `print`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `print` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FRONT_PASS` int(4) DEFAULT NULL,
  `BACK_PASS` int(4) DEFAULT NULL,
  `SLEEVE_PASS` int(4) DEFAULT NULL,
  `ART` varchar(200) DEFAULT NULL,
  `COST` decimal(5,2) DEFAULT NULL,
  `APPROVAL_DATE` datetime DEFAULT NULL,
  `APPROVAL_USER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `print_user` (`APPROVAL_USER`),
  CONSTRAINT `print_user` FOREIGN KEY (`APPROVAL_USER`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `print`
--

LOCK TABLES `print` WRITE;
/*!40000 ALTER TABLE `print` DISABLE KEYS */;

/*!40000 ALTER TABLE `print` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `COST` decimal(5,2) DEFAULT NULL,
  `STATUS` int(11) DEFAULT NULL,
  `STYLE` int(11) DEFAULT NULL,
  `COLOR` int(11) DEFAULT NULL,
  `SIZE` int(11) DEFAULT NULL,
  `AVAILABLE` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `product_status` (`STATUS`),
  KEY `product_color` (`COLOR`),
  KEY `product_style` (`STYLE`),
  KEY `product_size` (`SIZE`),
  CONSTRAINT `product_color` FOREIGN KEY (`COLOR`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_size` FOREIGN KEY (`SIZE`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_status` FOREIGN KEY (`STATUS`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_style` FOREIGN KEY (`STYLE`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;

/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_order`
--

DROP TABLE IF EXISTS `product_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_order` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PRODUCT_ID` int(11) DEFAULT NULL,
  `ORDER_ID` int(11) DEFAULT NULL,
  `QUANTITY_ORDERED` int(11) DEFAULT NULL,
  `QUANTITY_ARRIVED` int(11) DEFAULT NULL,
  `COST` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `line_product` (`PRODUCT_ID`),
  KEY `line_order` (`ORDER_ID`),
  CONSTRAINT `line_order` FOREIGN KEY (`ORDER_ID`) REFERENCES `order` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `line_product` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_order`
--

LOCK TABLES `product_order` WRITE;
/*!40000 ALTER TABLE `product_order` DISABLE KEYS */;

/*!40000 ALTER TABLE `product_order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMAIL` varchar(45) DEFAULT NULL,
  `PASSWORD` varchar(45) DEFAULT NULL,
  `FIRST` varchar(45) DEFAULT NULL,
  `LAST` varchar(45) DEFAULT NULL,
  `PHONE` varchar(20) DEFAULT NULL,
  `ROLE` varchar(4) DEFAULT '0001',
  PRIMARY KEY (`ID`),
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'michael.d.naughton@gmail.com','520d8e35c37a612df36313efec568dcc5b657c4e','Mike','Naughton','5157082546','1000');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vendor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) DEFAULT NULL,
  `EMAIL` varchar(45) DEFAULT NULL,
  `PHONE` int(13) DEFAULT NULL,
  `WEBSITE` varchar(200) DEFAULT NULL,
  `CONTACT_NAME` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;

/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-10-10 13:46:47
