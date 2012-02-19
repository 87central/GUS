ALTER TABLE `gus`.`customer` DROP FOREIGN KEY `customer_user` ;

ALTER TABLE `gus`.`customer` 

  ADD CONSTRAINT `customer_user`

  FOREIGN KEY (`USER_ID` )

  REFERENCES `gus`.`user` (`ID` )

  ON DELETE CASCADE

  ON UPDATE NO ACTION;

CREATE  TABLE `gus`.`print_art` (

  `ID` INT(11) NOT NULL AUTO_INCREMENT ,

  `PRINT_ID` INT(11) NOT NULL ,

  `USER_ID` INT(11) NULL COMMENT 'The ID of the user who uploaded the art' ,

  `FILE_TYPE` INT(11) NOT NULL COMMENT 'Just a lookup that indicates whether or not the file is an image.' ,

  `FILE` VARCHAR(200) NULL ,

  `DESCRIPTION` VARCHAR(100) NOT NULL ,

  `TIMESTAMP` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The date of record creation.' ,

  PRIMARY KEY (`ID`) ,

  INDEX `art_print` (`PRINT_ID` ASC) ,

  INDEX `art_user` (`USER_ID` ASC) ,

  INDEX `art_file_type` (`FILE_TYPE` ASC) ,

  CONSTRAINT `art_print`

    FOREIGN KEY (`PRINT_ID` )

    REFERENCES `gus`.`print` (`ID` )

    ON DELETE CASCADE

    ON UPDATE NO ACTION,

  CONSTRAINT `art_user`

    FOREIGN KEY (`USER_ID` )

    REFERENCES `gus`.`user` (`ID` )

    ON DELETE SET NULL

    ON UPDATE NO ACTION,

  CONSTRAINT `art_file_type`

    FOREIGN KEY (`FILE_TYPE` )

    REFERENCES `gus`.`lookup` (`ID` )

    ON DELETE NO ACTION

    ON UPDATE NO ACTION);

CREATE  TABLE `gus`.`job_fee` (

  `FEE_ID` INT(11) NOT NULL ,

  `JOB_ID` INT(11) NOT NULL ,

  `VALUE` FLOAT NULL COMMENT 'The actual cost of the fee, which may be negative to indicate a discount.' ,

  PRIMARY KEY (`FEE_ID`, `JOB_ID`) ,

  INDEX `fee_field` (`FEE_ID` ASC) ,

  INDEX `fee_job` (`JOB_ID` ASC) ,

  CONSTRAINT `fee_field`

    FOREIGN KEY (`FEE_ID` )

    REFERENCES `gus`.`lookup` (`ID` )

    ON DELETE CASCADE

    ON UPDATE CASCADE,

  CONSTRAINT `fee_job`

    FOREIGN KEY (`JOB_ID` )

    REFERENCES `gus`.`job` (`ID` )

    ON DELETE CASCADE

    ON UPDATE NO ACTION);
	
INSERT INTO `gus`.`lookup` (`TEXT`, `TYPE`, `DELETED`) VALUES ('Image', 'ArtFileType', 0);

INSERT INTO `gus`.`lookup` (`TEXT`, `TYPE`, `DELETED`) VALUES ('Design', 'ArtFileType', 0);

USE `gus`;

DROP procedure IF EXISTS `MIGRATE_PRINT_FILES`;



DELIMITER $$

USE `gus`$$

CREATE PROCEDURE `gus`.`MIGRATE_PRINT_FILES` ()

BEGIN    

    DECLARE id  INT(11);

    DECLARE art_file VARCHAR(200);

    DECLARE mockup_file VARCHAR(200);

    DECLARE done BOOL DEFAULT FALSE;

    DECLARE fileCursor CURSOR FOR SELECT `print`.`ID`, `ART`, `MOCK_UP` FROM `gus`.`print`;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    

    OPEN fileCursor;

    

    updater: LOOP

        FETCH fileCursor INTO id, art_file, mockup_file;

        IF NOT done THEN

            IF art_file IS NOT NULL AND id IS NOT NULL THEN

                INSERT INTO `print_art`

                (`PRINT_ID`, `USER_ID`, `FILE_TYPE`, `FILE`, `DESCRIPTION`)

                VALUES

                (id, null, 107, art_file, 'Design');

            END IF;

            IF mockup_file IS NOT NULL AND id IS NOT NULL THEN

                INSERT INTO `print_art`

                (`PRINT_ID`, `USER_ID`, `FILE_TYPE`, `FILE`, `DESCRIPTION`)

                VALUES

                (id, null, 106, mockup_file, 'Mock Up');
 
            END IF;
		ELSE
			LEAVE updater;
        END IF;

    END LOOP;

    COMMIT;

END

$$



DELIMITER ;

CALL `gus`.`MIGRATE_PRINT_FILES`();