USE `fundle_db`;


/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lookup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODE` int(11) DEFAULT NULL,
  `NAME` varchar(45) DEFAULT NULL,
  `TYPE` varchar(45) DEFAULT NULL,
  `POSITION` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `setting_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

CREATE TABLE `setting_category` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(45) DEFAULT NULL,
  `IMAGE` varchar(200) DEFAULT NULL,
  `VISIBILITY` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `SETTING_CATEGORY_VISIBILITY` (`VISIBILITY`),
  CONSTRAINT `SETTING_CATEGORY_VISIBILITY` FOREIGN KEY (`VISIBILITY`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

COMMIT;

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CATEGORY_ID` int(11) DEFAULT NULL,
  `NAME` varchar(45) DEFAULT NULL,
  `FORMATTER` varchar(45) DEFAULT NULL,
  `IMAGE` varchar(200) DEFAULT NULL,
  `VISIBILITY` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `SETTING_SETTING_CATEGORY` (`CATEGORY_ID`),
  KEY `SETTING_VISIBILITY` (`VISIBILITY`),
  CONSTRAINT `SETTING_VISIBILITY` FOREIGN KEY (`VISIBILITY`) REFERENCES `lookup` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `SETTING_SETTING_CATEGORY` FOREIGN KEY (`CATEGORY_ID`) REFERENCES `setting_category` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `setting_category`
--

--
-- Table structure for table `setting_entry`
--

DROP TABLE IF EXISTS `setting_entry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting_entry` (
  `USER_ID` int(11) DEFAULT NULL,
  `SETTING_ID` int(11) DEFAULT NULL,
  `VALUE` varchar(75) DEFAULT NULL,
  `DATE_ENTERED` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `SETTING_RECORD_SETTING` (`SETTING_ID`),
  KEY `SETTING_RECORD_USER` (`USER_ID`),
  CONSTRAINT `SETTING_RECORD_SETTING` FOREIGN KEY (`SETTING_ID`) REFERENCES `setting` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `SETTING_RECORD_USER` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT INTO `lookup` (`ID`, `CODE`, `NAME`, `TYPE`, `POSITION`) VALUES
(1, 1, 'Creator', 'ParticipationType', 0),
(2, 2, 'Invitee', 'ParticipationType', 0),
(3, 3, 'Fundlemaster', 'ParticipationType', 0),
(4, 1, 'Accept', 'ResponseStatus', 0),
(5, 2, 'Deny', 'ResponseStatus', 0),
(6, 3, 'Not Responded', 'ResponseStatus', 0),
(7, 1, 'Created', 'Status', 0),
(8, 2, 'Approved', 'Status', 0),
(9, 3, 'Canceled', 'Status', 0),
(10, 1, 'Visible to everyone', 'Visibility', 0),
(11, 2, 'Visible only to you', 'Visibility', 0),
(12, 3, 'Not visible', 'Visibility', 0);

ALTER TABLE `fundle_db`.`fundle_user` DROP FOREIGN KEY `fk_FUNDLE_USER_RESPONSE_STATUS1` ;

ALTER TABLE `fundle_db`.`fundle_user` 

  ADD CONSTRAINT `fk_FUNDLE_USER_RESPONSE_STATUS1`

  FOREIGN KEY (`RESPONSE_STATUS_ID` )

  REFERENCES `fundle_db`.`response_status` (`RESPONSE_STATUS_ID` )

  ON DELETE NO ACTION

  ON UPDATE CASCADE;
  
ALTER TABLE `fundle_db`.`fundle` DROP FOREIGN KEY `fk_FUNDLE_STATUS1` ;

ALTER TABLE `fundle_db`.`fundle` 

  ADD CONSTRAINT `fk_FUNDLE_STATUS1`

  FOREIGN KEY (`STATUS_ID` )

  REFERENCES `fundle_db`.`status` (`STATUS_ID` )

  ON DELETE NO ACTION

  ON UPDATE CASCADE;

UPDATE `response_status`
SET RESPONSE_STATUS_ID = 4 WHERE RESPONSE_STATUS_ID = 1;

UPDATE `response_status`
SET RESPONSE_STATUS_ID = 5 WHERE RESPONSE_STATUS_ID = 2;

UPDATE `response_status`
SET RESPONSE_STATUS_ID = 6 WHERE RESPONSE_STATUS_ID = 3;

UPDATE `status`
SET STATUS_ID = 7 WHERE STATUS_ID = 1;

UPDATE `status`
SET STATUS_ID = 8 WHERE STATUS_ID = 2;

UPDATE `status`
SET STATUS_ID = 9 WHERE STATUS_ID = 3;

INSERT INTO `setting` (`ID`, `CATEGORY_ID`, `NAME`, `FORMATTER`, `IMAGE`, `VISIBILITY`) VALUES
(1, NULL, 'dwolla_auth', 'raw', NULL, 12),
(2, NULL, 'dwolla_auth_code', 'raw', NULL, 12);