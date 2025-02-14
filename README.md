Anotações

http://localhost:8888/index.php

mariadb -u admin -padmin

CREATE DATABASE usuarios02;



USE usuarios01;

CREATE TABLE usuarios ( id INT AUTO_INCREMENT PRIMARY KEY, usuario VARCHAR(50) NOT NULL UNIQUE, senha VARCHAR(50) NOT NULL );

INSERT INTO usuarios (usuario, senha) VALUES ('admin', 'admin'), ('lobo_guara', 'lobo_guara');

ALTER TABLE usuarios ADD COLUMN recuperar_senha TINYINT(1) DEFAULT 0;

UPDATE usuarios SET data_solicitacao = NOW() WHERE recuperar_senha = 1 AND data_solicitacao IS NULL;

CREATE TABLE pontos ( id INT AUTO_INCREMENT PRIMARY KEY, usuario VARCHAR(50) NOT NULL, data_hora DATETIME NOT NULL );

ALTER TABLE pontos ADD COLUMN tipo ENUM('Entrada', 'Saída') NOT NULL;

ALTER TABLE pontos ADD COLUMN foto VARCHAR(255) NULL;

CREATE TABLE pedidos_revisao ( id INT AUTO_INCREMENT PRIMARY KEY, ponto_id INT NOT NULL, usuario VARCHAR(50) NOT NULL, data_hora_atual DATETIME NOT NULL, nova_data_hora DATETIME NOT NULL, justificativa TEXT, status ENUM('Pendente', 'Aceito', 'Rejeitado') DEFAULT 'Pendente', data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP );

ALTER TABLE usuarios ADD COLUMN tipo ENUM('admin', 'usuario') DEFAULT 'usuario';

ALTER TABLE usuarios ADD COLUMN data_solicitacao DATETIME NULL;

