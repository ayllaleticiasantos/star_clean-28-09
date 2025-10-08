-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/10/2025 às 02:59
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
CREATE SCHEMA IF NOT EXISTS `db_starClean` DEFAULT CHARACTER SET utf8 ;
USE `db_starClean` ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `sobrenome` varchar(45) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tipo` varchar(25) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`id`, `nome`, `sobrenome`, `email`, `password`, `tipo`, `criado_em`, `atualizado_em`) VALUES
(1, 'Aylla Leticia dos Santos', 'Vieira', 'ayllasantosdf@hotmail.com', '$2y$10$zNX6FS1uuyWGJMaZCMUP/eImpO/mi.mm/sKrcODJfcjTGXnVzMDfe', NULL, '2025-10-06 22:37:24', '2025-10-06 22:37:24'),
(2, 'StarClean', 'Serviços', 'starclean.prest.servicos@gmail.com', '$2y$10$jA7qjUoRmFJ.Ri6YL4V8GufisxsNyKvxDm87wIj6mQkbeeReU0CwO', 'admin', '2025-10-06 23:07:13', '2025-10-06 23:07:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamento`
--

CREATE TABLE `agendamento` (
  `id` int(11) NOT NULL,
  `Cliente_id` int(11) NOT NULL,
  `Prestador_id` int(11) NOT NULL,
  `Servico_id` int(11) NOT NULL,
  `Endereco_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `status` enum('pendente','realizado','cancelado') NOT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao_prestador`
--

CREATE TABLE `avaliacao_prestador` (
  `id` int(11) NOT NULL,
  `Cliente_id` int(11) NOT NULL,
  `Prestador_id` int(11) NOT NULL,
  `nota` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacao_servico`
--

CREATE TABLE `avaliacao_servico` (
  `id` int(11) NOT NULL,
  `Cliente_id` int(11) NOT NULL,
  `Servico_id` int(11) NOT NULL,
  `nota` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `sobrenome` varchar(45) NOT NULL,
  `email` varchar(150) NOT NULL,
  `data_nascimento` date NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `password` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `sobrenome`, `email`, `data_nascimento`, `telefone`, `cpf`, `password`, `criado_em`, `atualizado_em`) VALUES
(1, 'Jaisla', 'Costa', 'jaislacosta2@gmail.com', '2005-09-11', '', '00011122235', '$2y$10$I0ZThOuYiwOSf3E4kFEute4nlhfZRpxZKSnGcyDH7YDGtQTPsB6Ru', '2025-10-07 00:43:18', '2025-10-07 00:43:18'),
(2, 'Allana', 'Larissa', 'allanalarissa5@gmail.com', '2005-03-30', '61991817265', '07746900119', '$2y$10$oHT.UKpgN.4.R7IpldA9WuQjv3jzGtrrw4Lpz8HbfhjiuhK26dtje', '2025-10-07 02:48:05', '2025-10-07 02:48:05'),
(3, 'teste', 'hoje', 'teste@teste.com', '2000-02-10', '61991933774', '12345678912', '$2y$10$2q0O2bv8shxIdc8tSHesSeqadSbhKHj6dDI.PSOzXrvXi7MZOy31y', '2025-10-07 22:45:05', '2025-10-07 22:45:05');

-- --------------------------------------------------------

--
-- Estrutura para tabela `disponibilidade`
--

CREATE TABLE `disponibilidade` (
  `id` int(11) NOT NULL,
  `Prestador_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `status` enum('livre','ocupado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `endereco`
--

CREATE TABLE `endereco` (
  `id` int(11) NOT NULL,
  `Cliente_id` int(11) NOT NULL,
  `Prestador_id` int(11) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(250) DEFAULT NULL,
  `bairro` varchar(45) DEFAULT NULL,
  `cidade` varchar(45) DEFAULT NULL,
  `uf` char(2) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `prestador`
--

CREATE TABLE `prestador` (
  `id` int(11) NOT NULL,
  `nome_razão_social` varchar(100) NOT NULL,
  `sobrenome_nome_fantasia` varchar(100) NOT NULL,
  `cpf_cnpj` varchar(18) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Administrador_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `prestador`
--

INSERT INTO `prestador` (`id`, `nome_razão_social`, `sobrenome_nome_fantasia`, `cpf_cnpj`, `email`, `telefone`, `especialidade`, `password`, `descricao`, `criado_em`, `atualizado_em`, `Administrador_id`) VALUES
(1, 'Leticia', 'Santos', '071.818.111-50', 'jeleticiasantosdf@gmail.com', '6130428546', 'Limpeza de Ambientes Pequenos', '$2y$10$clSfuBRszNZyO4tTpaDLzOQ7ggVLuyaqDxHIqpnetbXTfsjYii2WO', 'Profissional do ramo de limpeza de casas, pequenas e minimalistas desde o ano de 2016, com foco em impermeabilização garantindo que sua casa fique mais limpa, por mais tempo.', '2025-10-07 02:24:18', '2025-10-07 02:24:18', 1),
(2, 'teste 1', 'teste 1', '12345678978', 'testeprest@teste.com', '61991933778', 'Limpeza de Ambientes Pequenos', '$2y$10$eAH9d3r3Fy.jEK9td7w1AubLAwr6DfMqLGKsO2efcWmOBeMgSA24q', 'casa', '2025-10-07 22:46:58', '2025-10-07 22:46:58', 1),
(3, 'Professor Cristiano', 'teste 1', '12345678989', 'cristiano@teste.com', '61991933776', 'Professor', '$2y$10$ktyn0m3j7Y4xGaLs9ySUSugjDwyldhqJiB89da5u7mJ3UfBUIHHPe', 'Teste', '2025-10-07 23:38:58', '2025-10-07 23:38:58', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `servico`
--

CREATE TABLE `servico` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `preco` double NOT NULL,
  `duracao_estimada` time DEFAULT NULL,
  `prestador_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `servico`
--

INSERT INTO `servico` (`id`, `titulo`, `descricao`, `preco`, `duracao_estimada`, `prestador_id`) VALUES
(13, 'limpeza doméstica', 'limpeza doméstica geral', 150, NULL, 2),
(14, 'passar', 'tesste', 50, NULL, 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Índices de tabela `agendamento`
--
ALTER TABLE `agendamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Agendamento_Cliente1_idx` (`Cliente_id`),
  ADD KEY `fk_Agendamento_Prestador1_idx` (`Prestador_id`),
  ADD KEY `fk_Agendamento_Endereco1_idx` (`Endereco_id`),
  ADD KEY `fk_Agendamento_Servico1_idx` (`Servico_id`);

--
-- Índices de tabela `avaliacao_prestador`
--
ALTER TABLE `avaliacao_prestador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Avaliacao_prestador_Prestador1_idx` (`Prestador_id`),
  ADD KEY `fk_Avaliacao_prestador_Cliente1_idx` (`Cliente_id`);

--
-- Índices de tabela `avaliacao_servico`
--
ALTER TABLE `avaliacao_servico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Avaliacao_servico_Servico1_idx` (`Servico_id`),
  ADD KEY `fk_Avaliacao_servico_Cliente1_idx` (`Cliente_id`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Índices de tabela `disponibilidade`
--
ALTER TABLE `disponibilidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Disponibilidade_Prestador1_idx` (`Prestador_id`);

--
-- Índices de tabela `endereco`
--
ALTER TABLE `endereco`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Endereco_Cliente_idx` (`Cliente_id`),
  ADD KEY `fk_Endereco_Prestador1_idx` (`Prestador_id`);

--
-- Índices de tabela `prestador`
--
ALTER TABLE `prestador`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `cpf_cnpj_UNIQUE` (`cpf_cnpj`),
  ADD KEY `fk_Prestador_Administrador1_idx` (`Administrador_id`);

--
-- Índices de tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_servico_prestador1_idx` (`prestador_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `agendamento`
--
ALTER TABLE `agendamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avaliacao_prestador`
--
ALTER TABLE `avaliacao_prestador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `avaliacao_servico`
--
ALTER TABLE `avaliacao_servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `disponibilidade`
--
ALTER TABLE `disponibilidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `endereco`
--
ALTER TABLE `endereco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `prestador`
--
ALTER TABLE `prestador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamento`
--
ALTER TABLE `agendamento`
  ADD CONSTRAINT `fk_Agendamento_Cliente1` FOREIGN KEY (`Cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Agendamento_Endereco1` FOREIGN KEY (`Endereco_id`) REFERENCES `endereco` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Agendamento_Prestador1` FOREIGN KEY (`Prestador_id`) REFERENCES `prestador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Agendamento_Servico1` FOREIGN KEY (`Servico_id`) REFERENCES `servico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avaliacao_prestador`
--
ALTER TABLE `avaliacao_prestador`
  ADD CONSTRAINT `fk_Avaliacao_prestador_Cliente1` FOREIGN KEY (`Cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Avaliacao_prestador_Prestador1` FOREIGN KEY (`Prestador_id`) REFERENCES `prestador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `avaliacao_servico`
--
ALTER TABLE `avaliacao_servico`
  ADD CONSTRAINT `fk_Avaliacao_servico_Cliente1` FOREIGN KEY (`Cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Avaliacao_servico_Servico1` FOREIGN KEY (`Servico_id`) REFERENCES `servico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `disponibilidade`
--
ALTER TABLE `disponibilidade`
  ADD CONSTRAINT `fk_Disponibilidade_Prestador1` FOREIGN KEY (`Prestador_id`) REFERENCES `prestador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `endereco`
--
ALTER TABLE `endereco`
  ADD CONSTRAINT `fk_Endereco_Cliente` FOREIGN KEY (`Cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Endereco_Prestador1` FOREIGN KEY (`Prestador_id`) REFERENCES `prestador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `prestador`
--
ALTER TABLE `prestador`
  ADD CONSTRAINT `fk_Prestador_Administrador1` FOREIGN KEY (`Administrador_id`) REFERENCES `administrador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `servico`
--
ALTER TABLE `servico`
  ADD CONSTRAINT `fk_servico_prestador1` FOREIGN KEY (`prestador_id`) REFERENCES `prestador` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
