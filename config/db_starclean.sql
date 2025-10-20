-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20/10/2025
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_starclean`
--
CREATE DATABASE IF NOT EXISTS `db_starclean` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_starclean`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `sobrenome` varchar(45) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` enum('adminmaster','adminusuario','adminmoderador') NOT NULL DEFAULT 'adminusuario',
  `receber_notificacoes_email` tinyint(1) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`id`, `nome`, `sobrenome`, `email`, `password`, `tipo`, `receber_notificacoes_email`, `criado_em`, `atualizado_em`) VALUES
(1, 'Aylla Leticia dos Santos', 'Vieira', 'ayllasantosdf@hotmail.com', '$2y$10$zNX6FS1uuyWGJMaZCMUP/eImpO/mi.mm/sKrcODJfcjTGXnVzMDfe', 'adminmaster', 1, '2025-10-06 22:37:24', '2025-10-06 22:37:24'),
(2, 'StarClean', 'Serviços', 'starclean.prest.servicos@gmail.com', '$2y$10$jA7qjUoRmFJ.Ri6YL4V8GufisxsNyKvxDm87wIj6mQkbeeReU0CwO', 'adminmaster', 1, '2025-10-06 23:07:13', '2025-10-06 23:07:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `sobrenome` varchar(45) NOT NULL,
  `email` varchar(150) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `password` varchar(255) NOT NULL,
  `receber_notificacoes_email` tinyint(1) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `sobrenome`, `email`, `data_nascimento`, `telefone`, `cpf`, `password`, `receber_notificacoes_email`, `criado_em`, `atualizado_em`) VALUES
(2, 'Allana', 'Larissa', 'allanalarissa5@gmail.com', '2005-03-30', '61991817265', '07746900119', '$2y$10$oHT.UKpgN.4.R7IpldA9WuQjv3jzGtrrw4Lpz8HbfhjiuhK26dtje', 1, '2025-10-07 02:48:05', '2025-10-07 02:48:05'),
(3, 'teste', 'hoje', 'teste@teste.com', '2000-02-10', '61991933774', '12345678912', '$2y$10$2q0O2bv8shxIdc8tSHesSeqadSbhKHj6dDI.PSOzXrvXi7MZOy31y', 1, '2025-10-07 22:45:05', '2025-10-07 22:45:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `prestador`
--

CREATE TABLE `prestador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_razao_social` varchar(100) NOT NULL,
  `sobrenome_nome_fantasia` varchar(100) NOT NULL,
  `cpf_cnpj` varchar(18) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `receber_notificacoes_email` tinyint(1) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `administrador_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `cpf_cnpj_UNIQUE` (`cpf_cnpj`),
  KEY `fk_prestador_administrador_idx` (`administrador_id`),
  CONSTRAINT `fk_prestador_administrador` FOREIGN KEY (`administrador_id`) REFERENCES `administrador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


--
-- Despejando dados para a tabela `prestador`
--

INSERT INTO `prestador` (`id`, `nome_razao_social`, `sobrenome_nome_fantasia`, `cpf_cnpj`, `email`, `telefone`, `especialidade`, `password`, `descricao`, `receber_notificacoes_email`, `criado_em`, `atualizado_em`, `administrador_id`) VALUES
(1, 'Leticia', 'Santos', '071.818.111-50', 'jeleticiasantosdf@gmail.com', '6130428546', 'Limpeza de Ambientes Pequenos', '$2y$10$clSfuBRszNZyO4tTpaDLzOQ7ggVLuyaqDxHIqpnetbXTfsjYii2WO', 'Profissional do ramo de limpeza de casas, pequenas e minimalistas desde o ano de 2016, com foco em impermeabilização garantindo que sua casa fique mais limpa, por mais tempo.', 1, '2025-10-07 02:24:18', '2025-10-07 02:24:18', 1),
(2, 'teste 1', 'teste 1', '12345678978', 'testeprest@teste.com', '61991933778', 'Limpeza de Ambientes Pequenos', '$2y$10$eAH9d3r3Fy.jEK9td7w1AubLAwr6DfMqLGKsO2efcWmOBeMgSA24q', 'casa', 1, '2025-10-07 22:46:58', '2025-10-07 22:46:58', 1),
(3, 'Professor Cristiano', 'teste 1', '12345678989', 'cristiano@teste.com', '61991933776', 'Professor', '$2y$10$ktyn0m3j7Y4xGaLs9ySUSugjDwyldhqJiB89da5u7mJ3UfBUIHHPe', 'Teste', 1, '2025-10-07 23:38:58', '2025-10-07 23:38:58', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `prestador_id` int(11) DEFAULT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(250) NOT NULL,
  `bairro` varchar(45) NOT NULL,
  `cidade` varchar(45) NOT NULL,
  `uf` char(2) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_endereco_cliente_idx` (`cliente_id`),
  KEY `fk_endereco_prestador_idx` (`prestador_id`),
  CONSTRAINT `fk_endereco_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_endereco_prestador` FOREIGN KEY (`prestador_id`) REFERENCES `prestador` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao_estimada` time DEFAULT NULL,
  `prestador_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_servico_prestador_idx` (`prestador_id`),
  CONSTRAINT `fk_servico_prestador` FOREIGN KEY (`prestador_id`) REFERENCES `prestador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`id`, `titulo`, `descricao`, `preco`, `duracao_estimada`, `prestador_id`) VALUES
(13, 'limpeza doméstica', 'limpeza doméstica geral', 150.00, NULL, 2),
(14, 'passar', 'tesste', 50.00, NULL, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `prestador_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `endereco_id` int(11) NOT NULL,
  `data_agendamento` date NOT NULL,
  `hora_agendamento` time NOT NULL,
  `status` enum('pendente','realizado','cancelado') NOT NULL DEFAULT 'pendente',
  `observacoes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_agendamento_cliente_idx` (`cliente_id`),
  KEY `fk_agendamento_prestador_idx` (`prestador_id`),
  KEY `fk_agendamento_servico_idx` (`servico_id`),
  KEY `fk_agendamento_endereco_idx` (`endereco_id`),
  CONSTRAINT `fk_agendamento_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_agendamento_endereco` FOREIGN KEY (`endereco_id`) REFERENCES `endereco` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_agendamento_prestador` FOREIGN KEY (`prestador_id`) REFERENCES `prestador` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_agendamento_servico` FOREIGN KEY (`servico_id`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Outras Tabelas (Avaliação, Disponibilidade, etc.)
--

CREATE TABLE `avaliacao_prestador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `prestador_id` int(11) NOT NULL,
  `nota` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_avaliacao_prestador_cliente_idx` (`cliente_id`),
  KEY `fk_avaliacao_prestador_prestador_idx` (`prestador_id`),
  CONSTRAINT `fk_avaliacao_prestador_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_avaliacao_prestador_prestador` FOREIGN KEY (`prestador_id`) REFERENCES `prestador` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `avaliacao_servico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `servico_id` int(11) NOT NULL,
  `nota` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_avaliacao_servico_cliente_idx` (`cliente_id`),
  KEY `fk_avaliacao_servico_servico_idx` (`servico_id`),
  CONSTRAINT `fk_avaliacao_servico_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_avaliacao_servico_servico` FOREIGN KEY (`servico_id`) REFERENCES `servico` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `disponibilidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prestador_id` int(11) NOT NULL,
  `data_disponivel` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `status` enum('livre','ocupado') NOT NULL DEFAULT 'livre',
  PRIMARY KEY (`id`),
  KEY `fk_disponibilidade_prestador_idx` (`prestador_id`),
  CONSTRAINT `fk_disponibilidade_prestador` FOREIGN KEY (`prestador_id`) REFERENCES `prestador` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `redefinicao_senha`
--

CREATE TABLE `redefinicao_senha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `token` varchar(255) NOT NULL,
  `data_expiracao` datetime NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `token_idx` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;