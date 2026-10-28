CREATE DATABASE IF NOT EXISTS aula_estoque
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aula_estoque;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco DECIMAL(10,2) UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL DEFAULT 0
);

-- Dados para a aula. A senha do admin é Aula@123 (armazenada como hash).
-- Se o e-mail já existir, preserva os dados e a senha desse usuário.
INSERT INTO usuarios (nome, email, senha)
SELECT 'Administrador', 'admin@estoque.local',
       '$2y$12$I3i98R/vZKN4a2by2azrJOmouSo.LRJO79pydEtSlbiKaYMmZmBpi'
WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE email = 'admin@estoque.local');

-- Insere cada exemplo somente se ainda não houver um produto com esse nome.
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Caderno', 12.50, 20
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Caderno');
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Caneta azul', 2.50, 50
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Caneta azul');
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Lápis', 1.50, 30
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Lápis');
INSERT INTO produtos (nome, preco, quantidade)
SELECT 'Borracha', 2.00, 0
WHERE NOT EXISTS (SELECT 1 FROM produtos WHERE nome = 'Borracha');
