-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema db_starClean
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_starClean
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_starClean` DEFAULT CHARACTER SET utf8 ;
USE `db_starClean` ;

-- -----------------------------------------------------
-- Table `db_starClean`.`Administrador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Administrador` (
  `id` INT NOT NULL,
  `nome` VARCHAR(45) NOT NULL,
  `sobrenome` VARCHAR(45) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Cliente` (
  `id` INT NOT NULL,
  `nome` VARCHAR(45) NOT NULL,
  `sobrenome` VARCHAR(45) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `cpf` VARCHAR(14) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;

 
-- -----------------------------------------------------
-- Table `db_starClean`.`Prestador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Prestador` (
  `id` INT NOT NULL,
  `nome_razão_social` VARCHAR(100) NOT NULL,
  `sobrenome_nome_fantasia` VARCHAR(100) NOT NULL,
  `cpf_cnpj` VARCHAR(18) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `especialidade` VARCHAR(100) NOT NULL,
  `descricao` TEXT NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Administrador_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `cpf_cnpj_UNIQUE` (`cpf_cnpj` ASC) ,
  INDEX `fk_Prestador_Administrador1_idx` (`Administrador_id` ASC) ,
  CONSTRAINT `fk_Prestador_Administrador1`
    FOREIGN KEY (`Administrador_id`)
    REFERENCES `db_starClean`.`Administrador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Endereco` (
  `id` INT NOT NULL,
  `Cliente_id` INT NOT NULL,
  `Prestador_id` INT NOT NULL,
  `cep` VARCHAR(9) NOT NULL,
  `logradouro` VARCHAR(250) NULL,
  `bairro` VARCHAR(45) NULL,
  `cidade` VARCHAR(45) NULL,
  `uf` CHAR(2) NULL,
  `numero` VARCHAR(10) NULL,
  `complemento` VARCHAR(100) NULL,
  `criado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_Endereco_Cliente_idx` (`Cliente_id` ASC) ,
  INDEX `fk_Endereco_Prestador1_idx` (`Prestador_id` ASC) ,
  CONSTRAINT `fk_Endereco_Cliente`
    FOREIGN KEY (`Cliente_id`)
    REFERENCES `db_starClean`.`Cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Endereco_Prestador1`
    FOREIGN KEY (`Prestador_id`)
    REFERENCES `db_starClean`.`Prestador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Servico`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Servico` (
  `id` INT NOT NULL,
  `titulo` VARCHAR(100) NOT NULL,
  `descricao` TEXT NOT NULL,
  `preco` DOUBLE NOT NULL,
  `duracao_estimada` TIME NULL,
  `Administrador_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Servico_Administrador1_idx` (`Administrador_id` ASC) ,
  CONSTRAINT `fk_Servico_Administrador1`
    FOREIGN KEY (`Administrador_id`)
    REFERENCES `db_starClean`.`Administrador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Agendamento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Agendamento` (
  `id` INT NOT NULL,
  `Cliente_id` INT NOT NULL,
  `Prestador_id` INT NOT NULL,
  `Servico_id` INT NOT NULL,
  `Endereco_id` INT NOT NULL,
  `data` DATE NULL,
  `hora` TIME NULL,
  `status` ENUM('pendente', 'realizado', 'cancelado') NULL,
  `observacoes` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Agendamento_Cliente1_idx` (`Cliente_id` ASC) ,
  INDEX `fk_Agendamento_Prestador1_idx` (`Prestador_id` ASC) ,
  INDEX `fk_Agendamento_Endereco1_idx` (`Endereco_id` ASC) ,
  INDEX `fk_Agendamento_Servico1_idx` (`Servico_id` ASC) ,
  CONSTRAINT `fk_Agendamento_Cliente1`
    FOREIGN KEY (`Cliente_id`)
    REFERENCES `db_starClean`.`Cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Agendamento_Prestador1`
    FOREIGN KEY (`Prestador_id`)
    REFERENCES `db_starClean`.`Prestador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Agendamento_Endereco1`
    FOREIGN KEY (`Endereco_id`)
    REFERENCES `db_starClean`.`Endereco` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Agendamento_Servico1`
    FOREIGN KEY (`Servico_id`)
    REFERENCES `db_starClean`.`Servico` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Avaliacao_prestador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Avaliacao_prestador` (
  `id` INT NOT NULL,
  `Cliente_id` INT NOT NULL,
  `Prestador_id` INT NOT NULL,
  `nota` INT NULL,
  `comentario` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Avaliacao_prestador_Prestador1_idx` (`Prestador_id` ASC) ,
  INDEX `fk_Avaliacao_prestador_Cliente1_idx` (`Cliente_id` ASC) ,
  CONSTRAINT `fk_Avaliacao_prestador_Prestador1`
    FOREIGN KEY (`Prestador_id`)
    REFERENCES `db_starClean`.`Prestador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Avaliacao_prestador_Cliente1`
    FOREIGN KEY (`Cliente_id`)
    REFERENCES `db_starClean`.`Cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Avaliacao_servico`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Avaliacao_servico` (
  `id` INT NOT NULL,
  `Cliente_id` INT NOT NULL,
  `Servico_id` INT NOT NULL,
  `nota` INT NULL,
  `comentario` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Avaliacao_servico_Servico1_idx` (`Servico_id` ASC) ,
  INDEX `fk_Avaliacao_servico_Cliente1_idx` (`Cliente_id` ASC) ,
  CONSTRAINT `fk_Avaliacao_servico_Servico1`
    FOREIGN KEY (`Servico_id`)
    REFERENCES `db_starClean`.`Servico` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Avaliacao_servico_Cliente1`
    FOREIGN KEY (`Cliente_id`)
    REFERENCES `db_starClean`.`Cliente` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_starClean`.`Disponibilidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_starClean`.`Disponibilidade` (
  `id` INT NOT NULL,
  `Prestador_id` INT NOT NULL,
  `data` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `status` ENUM('livre', 'ocupado') NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Disponibilidade_Prestador1_idx` (`Prestador_id` ASC) ,
  CONSTRAINT `fk_Disponibilidade_Prestador1`
    FOREIGN KEY (`Prestador_id`)
    REFERENCES `db_starClean`.`Prestador` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
