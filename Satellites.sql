
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema werfl9_cnam_pro
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema werfl9_cnam_pro
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `werfl9_cnam_pro` DEFAULT CHARACTER SET utf8 ;
USE `werfl9_cnam_pro` ;

-- -----------------------------------------------------
-- Table `werfl9_cnam_pro`.`cnamcp09_categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `werfl9_cnam_pro`.`cnamcp09_categories` ;

CREATE TABLE IF NOT EXISTS `werfl9_cnam_pro`.`cnamcp09_categories` (
  `ID_categorie` INT NOT NULL AUTO_INCREMENT,
  `LIBL_categorie` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`ID_categorie`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `werfl9_cnam_pro`.`cnamcp09_articles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `werfl9_cnam_pro`.`cnamcp09_articles` ;

CREATE TABLE IF NOT EXISTS `werfl9_cnam_pro`.`cnamcp09_articles` (
  `ID_article` INT NOT NULL AUTO_INCREMENT,
  `TITRE_article` VARCHAR(100) NOT NULL,
  `CONTENT_article` BLOB NOT NULL,
  `DATE_article` DATETIME NOT NULL,
  `ID_categorie` INT NULL DEFAULT NULL,
  PRIMARY KEY (`ID_article`),
  INDEX `fk_articles_categories_idx` (`ID_categorie` ASC),
  CONSTRAINT `fk_articles_categories`
    FOREIGN KEY (`ID_categorie`)
    REFERENCES `werfl9_cnam_pro`.`cnamcp09_categories` (`ID_categorie`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `werfl9_cnam_pro`.`cnamcp09_utilisateurs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `werfl9_cnam_pro`.`cnamcp09_utilisateurs` ;

CREATE TABLE IF NOT EXISTS `werfl9_cnam_pro`.`cnamcp09_utilisateurs` (
  `ID_utilisateur` INT NOT NULL AUTO_INCREMENT,
  `LOGIN_utilisateur` VARCHAR(20) NOT NULL,
  `MDP_utilisateur` VARCHAR(32) NOT NULL,
  `NOM_utilisateur` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID_utilisateur`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `werfl9_cnam_pro`.`cnamcp09_commentaires`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `werfl9_cnam_pro`.`cnamcp09_commentaires` ;

CREATE TABLE IF NOT EXISTS `werfl9_cnam_pro`.`cnamcp09_commentaires` (
  `ID_commentaire` INT NOT NULL AUTO_INCREMENT,
  `NOM_commentaire` VARCHAR(100) NOT NULL,
  `CONTENT_commentaire` BLOB NOT NULL,
  `DATE_commentaire` DATETIME NOT NULL,
  `articles_ID_article` INT NOT NULL,
  PRIMARY KEY (`ID_commentaire`, `articles_ID_article`),
  INDEX `fk_commentaires_articles1_idx` (`articles_ID_article` ASC),
  CONSTRAINT `fk_commentaires_articles`
    FOREIGN KEY (`articles_ID_article`)
    REFERENCES `werfl9_cnam_pro`.`cnamcp09_articles` (`ID_article`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `werfl9_cnam_pro`.`cnamcp09_messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `werfl9_cnam_pro`.`cnamcp09_messages` ;

CREATE TABLE IF NOT EXISTS `werfl9_cnam_pro`.`cnamcp09_messages` (
  `ID_message` INT NOT NULL AUTO_INCREMENT,
  `NOM_message` VARCHAR(45) NOT NULL,
  `EMAIL_message` VARCHAR(45) NOT NULL,
  `OBJET_message` VARCHAR(200) NOT NULL,
  `TEXT_message` BLOB NOT NULL,
  `DATE_message` DATETIME NOT NULL,
  PRIMARY KEY (`ID_message`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


--
-- Contenu de la table `cnamcp09_utilisateurs`
--

INSERT INTO `cnamcp09_utilisateurs` (`ID_utilisateur`, `LOGIN_utilisateur`, `MDP_utilisateur`, `COURS_nfa`) VALUES
(1, 'emilien', 'azerty', 'nfa_021'),
(2, 'herve', 'azerty', 'nfa_021');
