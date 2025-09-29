-- Cria banco de dados do sistema
CREATE DATABASE IF NOT EXISTS bd_star_clean CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_star_clean;

-- CLIENTES
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);

-- PRESTADORES
CREATE TABLE IF NOT EXISTS prestadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255),
    especialidade VARCHAR(100),
    descricao TEXT
);

-- SERVIÇOS
CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestador_id INT,
    titulo VARCHAR(100),
    descricao TEXT,
    preco DECIMAL(10,2),
    FOREIGN KEY (prestador_id) REFERENCES prestadores(id)
);

-- DISPONIBILIDADE
CREATE TABLE IF NOT EXISTS disponibilidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestador_id INT,
    data DATE,
    hora TIME,
    status ENUM('livre', 'ocupado') DEFAULT 'livre',
    FOREIGN KEY (prestador_id) REFERENCES prestadores(id)
);

-- AGENDAMENTOS
CREATE TABLE IF NOT EXISTS agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    servico_id INT,
    data DATE,
    hora TIME,
    status ENUM('pendente', 'realizado', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (servico_id) REFERENCES servicos(id)
);

-- AVALIAÇÕES
CREATE TABLE IF NOT EXISTS avaliacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agendamento_id INT,
    nota INT,
    comentario TEXT,
    FOREIGN KEY (agendamento_id) REFERENCES agendamentos(id)
);

-- ADMINISTRADORES
CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);
