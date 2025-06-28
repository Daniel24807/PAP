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


CREATE TABLE IF NOT EXISTS categorias (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL
);

-- Tabela pordutos
CREATE TABLE IF NOT EXISTS produtos (
  id_produto INT AUTO_INCREMENT PRIMARY KEY,
  id_categoria INT,
  nome VARCHAR(100) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  imagem VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL,
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
); 

-- A despejar estrutura para tabela php_store.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id_admin` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `utilizador` varchar(50) DEFAULT NULL,
  `senha` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.admins: ~1 rows (aproximadamente)
INSERT INTO `admins` (`id_admin`, `utilizador`, `senha`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'admin@admin.com', '$2y$10$zVCGh5sdiNr1ehurBMx4Q.vh9WrznBzPeZ1WUZbYAnPcBmC7vt9D2', '2024-04-18 20:36:24', '2024-04-18 20:36:24', NULL);

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
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.clientes: ~1 rows (aproximadamente)
INSERT INTO `clientes` (`id_cliente`, `email`, `senha`, `nome_completo`, `morada`, `cidade`, `telefone`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(9, 'daniel.salvador.2408@gmail.com', '$2y$10$CzKAcDPLmPjoOPjGaCjgaO7WnHmgY0JH6/jIF0fq4F8NLF.YhmLpy', 'Daniel', 'Rua da Oliveira', 'Olhão', '939495969', NULL, 1, '2025-03-25 14:48:43', '2025-03-25 14:48:58', NULL);


-- categorisa

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
