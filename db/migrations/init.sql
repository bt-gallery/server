SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `contest` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `contest` ;

-- -----------------------------------------------------
-- Table `contest`.`declarant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`declarant` (
  `id_declarant` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `surname` VARCHAR(255) NULL,
  `patronymic` VARCHAR(255) NULL,
  `email` VARCHAR(320) NULL,
  `phone` VARCHAR(45) NULL,
  PRIMARY KEY (`id_declarant`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`participant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`participant` (
  `id_participant` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NULL,
  `surname` VARCHAR(255) NULL,
  `patronymic` VARCHAR(255) NULL,
  `declarant_id_declarant` INT NOT NULL,
  PRIMARY KEY (`id_participant`, `declarant_id_declarant`),
  INDEX `fk_participant_declarant1_idx` (`declarant_id_declarant` ASC),
  CONSTRAINT `fk_participant_declarant1`
    FOREIGN KEY (`declarant_id_declarant`)
    REFERENCES `contest`.`declarant` (`id_declarant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`vote`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`vote` (
  `id_vote` INT NOT NULL AUTO_INCREMENT COMMENT '	',
  `vote_ip` VARCHAR(45) NULL,
  `vote_agent` VARCHAR(45) NULL,
  `voted_at` VARCHAR(45) NULL,
  PRIMARY KEY (`id_vote`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`competitive_work`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`competitive_work` (
  `id_competitive_work` INT NOT NULL AUTO_INCREMENT,
  `store_path` VARCHAR(255) NULL,
  `web_path` VARCHAR(255) NULL,
  `file_name` VARCHAR(255) NULL,
  PRIMARY KEY (`id_competitive_work`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`contest`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`contest` (
  `id_contest` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_contest`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`contest_has_competitive_work`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`contest_has_competitive_work` (
  `contest_id_contest` INT NOT NULL,
  `competitive_work_id_competitive_work` INT NOT NULL,
  `bet_is_placed` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`contest_id_contest`, `competitive_work_id_competitive_work`),
  INDEX `fk_contest_has_competitive_work_competitive_work1_idx` (`competitive_work_id_competitive_work` ASC),
  INDEX `fk_contest_has_competitive_work_contest_idx` (`contest_id_contest` ASC),
  CONSTRAINT `fk_contest_has_competitive_work_contest`
    FOREIGN KEY (`contest_id_contest`)
    REFERENCES `contest`.`contest` (`id_contest`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contest_has_competitive_work_competitive_work1`
    FOREIGN KEY (`competitive_work_id_competitive_work`)
    REFERENCES `contest`.`competitive_work` (`id_competitive_work`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`participant_has_competitive_work`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`participant_has_competitive_work` (
  `participant_id_participant` INT NOT NULL,
  `competitive_work_id_competitive_work` INT NOT NULL,
  PRIMARY KEY (`participant_id_participant`, `competitive_work_id_competitive_work`),
  INDEX `fk_participant_has_competitive_work_competitive_work1_idx` (`competitive_work_id_competitive_work` ASC),
  INDEX `fk_participant_has_competitive_work_participant1_idx` (`participant_id_participant` ASC),
  CONSTRAINT `fk_participant_has_competitive_work_participant1`
    FOREIGN KEY (`participant_id_participant`)
    REFERENCES `contest`.`participant` (`id_participant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_participant_has_competitive_work_competitive_work1`
    FOREIGN KEY (`competitive_work_id_competitive_work`)
    REFERENCES `contest`.`competitive_work` (`id_competitive_work`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`competitive_work_has_vote`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`competitive_work_has_vote` (
  `competitive_work_id_competitive_work` INT NOT NULL,
  `vote_id_vote` INT NOT NULL,
  PRIMARY KEY (`competitive_work_id_competitive_work`, `vote_id_vote`),
  INDEX `fk_competitive_work_has_vote_vote1_idx` (`vote_id_vote` ASC),
  INDEX `fk_competitive_work_has_vote_competitive_work1_idx` (`competitive_work_id_competitive_work` ASC),
  CONSTRAINT `fk_competitive_work_has_vote_competitive_work1`
    FOREIGN KEY (`competitive_work_id_competitive_work`)
    REFERENCES `contest`.`competitive_work` (`id_competitive_work`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_competitive_work_has_vote_vote1`
    FOREIGN KEY (`vote_id_vote`)
    REFERENCES `contest`.`vote` (`id_vote`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `contest`.`address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`address` (
  `address` INT NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(255) NULL,
  `subject` VARCHAR(255) NULL,
  `area` VARCHAR(255) NULL,
  `city` VARCHAR(255) NULL,
  `street` VARCHAR(255) NULL,
  `building` VARCHAR(10) NULL,
  `appartment` VARCHAR(10) NULL,
  `zip_code` VARCHAR(10) NULL,
  `declarant_id_declarant` INT NOT NULL,
  PRIMARY KEY (`address`, `declarant_id_declarant`),
  INDEX `fk_table1_declarant1_idx` (`declarant_id_declarant` ASC),
  CONSTRAINT `fk_table1_declarant1`
    FOREIGN KEY (`declarant_id_declarant`)
    REFERENCES `contest`.`declarant` (`id_declarant`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `contest`.`queue`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `contest`.`queue` (
  `id_queue` INT NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL,
  `data` TEXT NULL,
  `job` INT NULL,
  `status` VARCHAR(45) NULL,
  PRIMARY KEY (`id_queue`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
