-- --------------------------------------------------------
-- Anfitrião:                    127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- SO do servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para php_store
CREATE DATABASE IF NOT EXISTS `php_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `php_store`;

-- A despejar estrutura para tabela php_store.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `utilizador` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.admins: ~2 rows (aproximadamente)
INSERT INTO `admins` (`id_admin`, `utilizador`, `senha`, `created_at`, `updated_at`) VALUES
	(1, 'admin@admin.com', '$2y$10$05uAZ6bEzwmUtavXGS3A3.xJXvTNQdPfrJpHmzTJ78MSZjZrqRa/W', '2025-04-20 23:55:39', '2025-04-20 23:58:38');

-- A despejar estrutura para tabela php_store.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.categorias: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela php_store.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(250) DEFAULT NULL,
  `nome_completo` varchar(250) DEFAULT NULL,
  `morada` varchar(250) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `purl` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expira` datetime DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.clientes: ~2 rows (aproximadamente)
INSERT INTO `clientes` (`id_cliente`, `email`, `senha`, `nome_completo`, `morada`, `cidade`, `telefone`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`, `reset_token`, `reset_token_expira`) VALUES
	(11, 'blablablableblebleblublubluabulubububub@gmail.com', '$2y$10$vCrzhOuiQoho0k9d/vQvaO8X16r.xaqhH3/.KYjCe9txMiWX19loO', 'Daniel', 'Rua da Oliveira', 'Olhão', '939495969', 'lt28sAFFLoH0', 1, '2025-04-12 18:59:08', '2025-04-12 21:44:58', NULL, NULL, NULL),
	(14, 'teste@teste.com', '$2y$10$wH6Qw1Qw1Qw1Qw1Qw1Qw1uQw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'Utilizador Teste', 'Rua Exemplo, 123', 'Cidade Exemplo', '999999999', NULL, 1, '2025-06-26 17:01:00', '2025-06-26 17:01:00', NULL, NULL, NULL),
	(15, 'daniel.salvador.2408@gmail.com', '$2y$10$5xW60vS.AbbdQbPS6dGd/.Sm4ZF6/2yxfLB0d1Vo6aq7PcCL5RK3K', 'Daniel Salvador', 'Rua da Cerca', 'Olhão', '963228574', NULL, 1, '2025-06-26 20:20:28', '2025-06-26 22:12:59', NULL, NULL, NULL);

-- A despejar estrutura para tabela php_store.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `id_produto` int(11) NOT NULL AUTO_INCREMENT,
  `id_categoria` int(11) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagem` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_produto`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.produtos: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela php_store.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password_hash` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.users: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
