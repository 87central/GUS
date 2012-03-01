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
  CONSTRAINT `customer_user` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer`
--

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
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_log`
--


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
  CONSTRAINT `job_customer` FOREIGN KEY (`CUSTOMER_ID`) REFERENCES `customer` (`ID`) ON UPDATE NO ACTION,
  CONSTRAINT `job_leader` FOREIGN KEY (`LEADER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_print` FOREIGN KEY (`PRINT_ID`) REFERENCES `print` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_printer` FOREIGN KEY (`PRINTER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `job_status` FOREIGN KEY (`STATUS`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job`
--


--
-- Table structure for table `job_fee`
--

DROP TABLE IF EXISTS `job_fee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_fee` (
  `FEE_ID` int(11) NOT NULL,
  `JOB_ID` int(11) NOT NULL,
  `VALUE` float DEFAULT NULL COMMENT 'The actual cost of the fee, which may be negative to indicate a discount.',
  PRIMARY KEY (`FEE_ID`,`JOB_ID`),
  KEY `fee_field` (`FEE_ID`),
  KEY `fee_job` (`JOB_ID`),
  CONSTRAINT `fee_field` FOREIGN KEY (`FEE_ID`) REFERENCES `lookup` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fee_job` FOREIGN KEY (`JOB_ID`) REFERENCES `job` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_fee`
--


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
  `PRODUCT_ORDER_ID` int(11) DEFAULT NULL,
  `QUANTITY` int(11) DEFAULT NULL,
  `PRICE` decimal(5,2) DEFAULT NULL,
  `APPROVAL_DATE` datetime DEFAULT NULL,
  `APPROVAL_USER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `line_job` (`JOB_ID`),
  KEY `line_approver` (`APPROVAL_USER`),
  KEY `line_product` (`PRODUCT_ID`),
  KEY `line_product1` (`PRODUCT_ID`),
  KEY `line_product_order` (`PRODUCT_ORDER_ID`),
  CONSTRAINT `line_approver` FOREIGN KEY (`APPROVAL_USER`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `line_job` FOREIGN KEY (`JOB_ID`) REFERENCES `job` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `line_product1` FOREIGN KEY (`PRODUCT_ID`) REFERENCES `product` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `line_product_order` FOREIGN KEY (`PRODUCT_ORDER_ID`) REFERENCES `product_order` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_line`
--


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
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup`
--

LOCK TABLES `lookup` WRITE;
/*!40000 ALTER TABLE `lookup` DISABLE KEYS */;
INSERT INTO `lookup` VALUES (1,'Red',NULL,NULL,'Color',1),(2,'Blue',NULL,NULL,'Color',1),(3,'Green',NULL,NULL,'Color',1),(4,'Small',NULL,NULL,'Size',1),(5,'Medium',NULL,NULL,'Size',1),(6,'Large',NULL,NULL,'Size',1),(7,'Shirt',NULL,NULL,'Style',1),(8,'Skirt',NULL,NULL,'Style',1),(9,'Army',NULL,NULL,'Style',1),(10,'Due Date','CHtml::link($assoc->NAME, array(\'job/update\', \'id\'=>$assoc->ID)).\' for \'.CHtml::link($assoc->CUSTOMER->FIRST, array(\'customer/view\', \'id\'=>$assoc->CUSTOMER->ID)).\' is due on \'.$this->DATE',NULL,'Event',0),(11,'Print Date','CHtml::link($assoc->NAME, array(\'job/update\', \'id\'=>$assoc->ID)).\' for \'.CHtml::link($assoc->CUSTOMER->FIRST, array(\'customer/view\', \'id\'=>$assoc->CUSTOMER->ID)).\' is scheduled to be printed by \'.($this->assigned->ID == Yii::app()->user->id ? \'you\' : $this->assigned->FIRST).\' on \'.$this->DATE',NULL,'Event',0),(12,'Pickup Date','CHtml::link($assoc->NAME, array(\'job/update\', \'id\'=>$assoc->ID)).\' is scheduled to be picked up by \'.CHtml::link($assoc->CUSTOMER->FIRST, array(\'customer/view\', \'id\'=>$assoc->CUSTOMER->ID)).\' on \'.$this->DATE',NULL,'Event',0),(13,'Default',NULL,NULL,'Role',0),(14,'Customer',NULL,NULL,'Role',0),(15,'Admin',NULL,NULL,'Role',0),(16,'In Stock',NULL,NULL,'ProductStatus',0),(17,'On Order',NULL,NULL,'ProductStatus',0),(18,'Backordered',NULL,NULL,'ProductStatus',0),(19,'Out of Stock',NULL,NULL,'ProductStatus',0),(20,'Created',NULL,NULL,'OrderStatus',0),(21,'Ordered',NULL,NULL,'OrderStatus',0),(22,'Arrived',NULL,NULL,'OrderStatus',0),(23,'Order Created','(Yii::app()->user->id == $this->USER_ID ? \'You\' : $this->USER->FIRST).\' created an order, \'.CHtml::link($assoc->name, array(\'order/update\', \'id\'=>$assoc->ID)).\' on \'.$this->DATE',NULL,'Event',0),(24,'Order Placed','\'Your order, \'.CHtml::link($assoc->name, array(\'order/update\', \'id\'=>$assoc->ID)).\', was placed on \'.$this->DATE',NULL,'Event',0),(25,'Order Arrived','\'Your order, \'.CHtml::link($assoc->name, array(\'order/update\', \'id\'=>$assoc->ID)).\', arrived on \'.$this->DATE',NULL,'Event',0),(26,'Created',NULL,NULL,'JobStatus',0),(27,'Paid',NULL,NULL,'JobStatus',0),(28,'Scheduled',NULL,NULL,'JobStatus',0),(29,'Completed',NULL,NULL,'JobStatus',0),(30,'Canceled',NULL,NULL,'JobStatus',0),(31,'Invoice Sent',NULL,NULL,'JobStatus',0),(32,'Placeholder',NULL,NULL,'ProductStatus',0),(33,'Athletic Grey',NULL,NULL,'Color',0),(34,'X-Small',NULL,NULL,'Size',0),(35,'Small',NULL,NULL,'Size',0),(36,'Medium',NULL,NULL,'Size',0),(37,'Large',NULL,NULL,'Size',0),(38,'X-Large',NULL,NULL,'Size',0),(39,'2X-Large',NULL,NULL,'Size',0),(40,'3X-Large',NULL,NULL,'Size',0),(41,'Tr401',NULL,NULL,'Style',1),(42,'Tri-Black',NULL,NULL,'Color',0),(43,'AA2001',NULL,NULL,'Style',1),(44,'T-Shirt',NULL,NULL,'Style',0),(45,'V-Neck',NULL,NULL,'Style',0),(46,'Hoodie',NULL,NULL,'Style',0),(47,'Unisex X-Small',NULL,NULL,'Size',0),(48,'Unisex Small',NULL,NULL,'Size',0),(49,'Unisex Medium',NULL,NULL,'Size',0),(50,'Unisex Large',NULL,NULL,'Size',0),(51,'Unisex X-Large',NULL,NULL,'Size',0),(52,'Unisex Size Run',NULL,NULL,'Size',1),(53,'Women\'s Size Run',NULL,NULL,'Size',1),(54,'2T',NULL,NULL,'Size',1),(55,'3T',NULL,NULL,'Size',1),(56,'4T',NULL,NULL,'Size',1),(57,'6T',NULL,NULL,'Size',1),(58,'8',NULL,NULL,'Size',1),(59,'10',NULL,NULL,'Size',1),(60,'12',NULL,NULL,'Size',1),(61,'Kids Size Run',NULL,NULL,'Size',1),(62,'Toddler Size Run',NULL,NULL,'Size',1),(63,'Red',NULL,NULL,'Color',0),(64,'Cranberry',NULL,NULL,'Color',0),(65,'Fuscia',NULL,NULL,'Color',0),(66,'Blue',NULL,NULL,'Color',0),(67,'White',NULL,NULL,'Color',0),(68,'Tri-Coffee',NULL,NULL,'Color',0),(69,'Deleted',NULL,0,'ProductStatus',0),(70,'DR401',NULL,NULL,'Style',1),(71,'Black',NULL,NULL,'Color',0),(72,'Navy',NULL,NULL,'Color',0),(73,'Unisex XX-Large',NULL,NULL,'Size',0),(74,'Unisex XXX-Large',NULL,NULL,'Size',0),(75,'Women\'s X-Small',NULL,NULL,'Size',0),(76,'Women\'s Small',NULL,NULL,'Size',0),(77,'Women\'s Medium',NULL,NULL,'Size',0),(78,'Women\'s Large',NULL,NULL,'Size',0),(79,'Women\'s X-Large',NULL,NULL,'Size',0),(80,'Women\'s XX-Large',NULL,NULL,'Size',0),(81,'0-3M',NULL,NULL,'Size',0),(82,'3-6M',NULL,NULL,'Size',0),(83,'6-12M',NULL,NULL,'Size',0),(84,'12-18M',NULL,NULL,'Size',0),(85,'18-24M',NULL,NULL,'Size',0),(86,'2 Yrs',NULL,NULL,'Size',0),(87,'4 Yrs',NULL,NULL,'Size',0),(88,'6 Yrs',NULL,NULL,'Size',0),(89,'8 Yrs',NULL,NULL,'Size',0),(90,'10 Yrs',NULL,NULL,'Size',0),(91,'12 Yrs',NULL,NULL,'Size',0),(92,'Youth Small',NULL,NULL,'Size',1),(93,'Youth Medium',NULL,NULL,'Size',1),(94,'Youth Large',NULL,NULL,'Size',1),(95,'Youth X-Large',NULL,NULL,'Size',1),(96,'Youth X-Small',NULL,NULL,'Size',0),(97,'Youth Small',NULL,NULL,'Size',0),(98,'Youth Medium',NULL,NULL,'Size',0),(99,'Youth Large',NULL,NULL,'Size',0),(100,'Youth X-Large',NULL,NULL,'Size',0),(101,'Athletic Blue',NULL,NULL,'Color',0),(102,'Tri-Cranbery',NULL,NULL,'Color',0),(103,'Tri-Evergreen',NULL,NULL,'Color',0),(104,'Tri-Indigo',NULL,NULL,'Color',0),(105,'Tri-Orchid',NULL,NULL,'Color',0),(106,'Image',NULL,NULL,'ArtFileType',0),(107,'Design',NULL,NULL,'ArtFileType',0),(108,'Shipping Fee',NULL,NULL,'JobFeeType',0),(109,'Destination Fee',NULL,NULL,'JobFeeType',1),(110,'Tax Rate','{\"part\":false, \"default\":6}',NULL,'JobFeeType',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--


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
  `MOCK_UP` varchar(200) DEFAULT NULL,
  `COST` decimal(5,2) DEFAULT NULL,
  `APPROVAL_DATE` datetime DEFAULT NULL,
  `APPROVAL_USER` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `print_user` (`APPROVAL_USER`),
  CONSTRAINT `print_user` FOREIGN KEY (`APPROVAL_USER`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `print`
--


--
-- Table structure for table `print_art`
--

DROP TABLE IF EXISTS `print_art`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `print_art` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PRINT_ID` int(11) NOT NULL,
  `USER_ID` int(11) DEFAULT NULL COMMENT 'The ID of the user who uploaded the art',
  `FILE_TYPE` int(11) NOT NULL COMMENT 'Just a lookup that indicates whether or not the file is an image.',
  `FILE` varchar(200) DEFAULT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  `TIMESTAMP` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The date of record creation.',
  PRIMARY KEY (`ID`),
  KEY `art_print` (`PRINT_ID`),
  KEY `art_user` (`USER_ID`),
  KEY `art_file_type` (`FILE_TYPE`),
  CONSTRAINT `art_file_type` FOREIGN KEY (`FILE_TYPE`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `art_print` FOREIGN KEY (`PRINT_ID`) REFERENCES `print` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `art_user` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `print_art`
--


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
  `VENDOR_ID` int(11) DEFAULT NULL,
  `VENDOR_ITEM_ID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `product_status` (`STATUS`),
  KEY `product_color` (`COLOR`),
  KEY `product_style` (`STYLE`),
  KEY `product_size` (`SIZE`),
  KEY `product_vendor` (`VENDOR_ID`),
  CONSTRAINT `product_color` FOREIGN KEY (`COLOR`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_size` FOREIGN KEY (`SIZE`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_status` FOREIGN KEY (`STATUS`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_style` FOREIGN KEY (`STYLE`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `product_vendor` FOREIGN KEY (`VENDOR_ID`) REFERENCES `vendor` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=634 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--


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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_order`
--


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
  `ROLE` varchar(4) DEFAULT '0001' COMMENT 'bit field. first bit indicates administrator, second indicates lead, third indicates customer, fourth indicates default.',
  PRIMARY KEY (`ID`),
  KEY `role_lookup` (`ROLE`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--



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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendor`
--


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-02-21 11:20:39
