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

-- A despejar dados para tabela php_store.admins: ~1 rows (aproximadamente)
INSERT INTO `admins` (`id_admin`, `utilizador`, `senha`, `created_at`, `updated_at`) VALUES
	(1, 'admin@admin.com', '$2y$10$05uAZ6bEzwmUtavXGS3A3.xJXvTNQdPfrJpHmzTJ78MSZjZrqRa/W', '2025-04-20 23:55:39', '2025-04-20 23:58:38');

-- A despejar estrutura para tabela php_store.cart_items
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_produto` (`user_id`,`product_id`),
  KEY `idx_cart_items_cliente` (`user_id`),
  KEY `FK_cart_items_produtos` (`product_id`),
  CONSTRAINT `FK_cart_items_clientes` FOREIGN KEY (`user_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE,
  CONSTRAINT `FK_cart_items_produtos` FOREIGN KEY (`product_id`) REFERENCES `produtos` (`id_produto`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.cart_items: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela php_store.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.categorias: ~7 rows (aproximadamente)
INSERT INTO `categorias` (`id_categoria`, `nome`, `descricao`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Interior - Tecidos', 'Produtos para limpeza e proteção de tecidos interiores', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL),
	(2, 'Interior - Couro', 'Produtos para limpeza e proteção de couro', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL),
	(3, 'Interior - Vidros', 'Produtos para limpeza de vidros interiores', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL),
	(4, 'Exterior - Rodas', 'Produtos para limpeza e proteção de rodas', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL),
	(5, 'Exterior - Pintura', 'Produtos para limpeza e proteção de pintura', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL),
	(6, 'Exterior - Vidros', 'Produtos para limpeza e proteção de vidros exteriores', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL),
	(7, 'Exterior - Cerâmica para Discos', 'Produtos cerâmicos para proteção de discos', '2025-06-27 19:06:40', '2025-06-27 19:06:40', NULL);

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

-- A despejar dados para tabela php_store.clientes: ~3 rows (aproximadamente)
INSERT INTO `clientes` (`id_cliente`, `email`, `senha`, `nome_completo`, `morada`, `cidade`, `telefone`, `purl`, `activo`, `created_at`, `updated_at`, `deleted_at`, `reset_token`, `reset_token_expira`) VALUES
	(11, 'blablablableblebleblublubluabulubububub@gmail.com', '$2y$10$vCrzhOuiQoho0k9d/vQvaO8X16r.xaqhH3/.KYjCe9txMiWX19loO', 'Daniel', 'Rua da Oliveira', 'Olhão', '939495969', 'lt28sAFFLoH0', 1, '2025-04-12 18:59:08', '2025-04-12 21:44:58', NULL, NULL, NULL),
	(14, 'teste@teste.com', '$2y$10$wH6Qw1Qw1Qw1Qw1Qw1Qw1uQw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Qw1Q', 'Utilizador Teste', 'Rua Exemplo, 123', 'Cidade Exemplo', '999999999', NULL, 1, '2025-06-26 17:01:00', '2025-06-26 17:01:00', NULL, NULL, NULL),
	(15, 'daniel.salvador.2408@gmail.com', '$2y$10$RReHwA.mv4ju8ClCgywJm.TgfbCwJzPgA7sNR4IXI7DNsEivG/9Za', 'Daniel Salvador', 'Vale de Gralhassass', 'Olhão', '963228574', NULL, 1, '2025-06-26 20:20:28', '2025-06-27 22:16:50', NULL, NULL, NULL);

-- A despejar estrutura para tabela php_store.pedidos
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(10) unsigned NOT NULL,
  `codigo_pedido` varchar(8) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pendente',
  `endereco` varchar(255) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `codigo_postal` varchar(20) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `metodo_pagamento` varchar(20) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pedido`),
  UNIQUE KEY `codigo_pedido` (`codigo_pedido`),
  KEY `idx_pedidos_cliente` (`id_cliente`),
  KEY `idx_pedidos_status` (`status`),
  KEY `idx_pedidos_data` (`data_pedido`),
  CONSTRAINT `FK_pedidos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.pedidos: ~6 rows (aproximadamente)
INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `codigo_pedido`, `status`, `endereco`, `cidade`, `codigo_postal`, `telefone`, `metodo_pagamento`, `total`, `data_pedido`) VALUES
	(1, 11, 'ABC12345', 'pendente', 'Rua da Oliveira', 'Olhão', '8700-123', '939495969', 'cartão', 299.99, '2025-06-25 17:35:08'),
	(2, 14, 'DEF67890', 'processando', 'Rua Exemplo, 123', 'Cidade Exemplo', '1000-123', '999999999', 'paypal', 450.00, '2025-06-22 17:35:08'),
	(4, 15, 'JKL24680', 'cancelado', 'Rua da Cerca', 'Olhão', '8700-456', '963228574', 'cartão', 599.98, '2025-06-26 17:35:08'),
	(5, 15, 'BHJ5XJTW', 'cancelado', 'qwert', 'Olhao', '1233', '963228574', 'transferencia', 10.95, '2025-06-27 18:13:34'),
	(6, 15, 'GHI751PW', 'cancelado', 'qwert', 'Olhao', '1233', '963228574', 'transferencia', 69.60, '2025-06-27 18:29:27'),
	(7, 15, 'D94UCFIB', 'pendente', 'qwert', 'Olhao', 'SADAS', '963228574', 'transferencia', 14.90, '2025-06-27 21:19:32');

-- A despejar estrutura para tabela php_store.pedido_itens
CREATE TABLE IF NOT EXISTS `pedido_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pedido_itens_pedido` (`id_pedido`),
  KEY `FK_pedido_itens_produtos` (`id_produto`),
  KEY `idx_pedido_itens_produto` (`id_produto`),
  CONSTRAINT `FK_pedido_itens_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pedido_itens_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.pedido_itens: ~9 rows (aproximadamente)
INSERT INTO `pedido_itens` (`id`, `id_pedido`, `id_produto`, `quantidade`, `preco`) VALUES
	(1, 1, 1, 1, 199.99),
	(2, 1, 2, 1, 100.00),
	(3, 2, 1, 2, 199.99),
	(4, 2, 3, 1, 50.02),
	(8, 4, 1, 3, 199.99),
	(9, 5, 3, 1, 10.95),
	(10, 6, 2, 2, 14.90),
	(11, 6, 1, 2, 19.90),
	(12, 7, 2, 1, 14.90);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- A despejar dados para tabela php_store.produtos: ~4 rows (aproximadamente)
INSERT INTO `produtos` (`id_produto`, `id_categoria`, `nome`, `descricao`, `preco`, `stock`, `imagem`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, NULL, 'Total Remover 1', 'Foi desenvolvido para remover tudo das superfícies externas dos veículos sem danificá-las. Perfeito para preparar carros recém-saídos de fábrica antes de aplicar um coating ou para remover revestimentos antigos.', 19.90, 50, 'total-remover.jpg', '2025-06-26 21:02:24', '2025-06-27 23:47:08', NULL),
	(2, NULL, 'Selante Hidrofóbico Instantâneo em Spray', 'O Gyeon Q²M WetCoat é um selante spray de alta performance que oferece proteção instantânea, efeito hidrofóbico e brilho intenso.', 14.90, 30, 'selante-hidrofobico-instantaneo-em-spray.jpg', '2025-06-26 21:02:24', '2025-06-26 21:02:24', NULL),
	(3, NULL, 'Limpeza de Vidros', 'Limpa-vidros de alta qualidade para carros. Remove resíduos de óleo, manchas, gordura, impressões digitais, sujidade em geral e vestígios de insetos.', 10.95, 45, 'limpeza-de-vidros.jpg', '2025-06-26 21:02:24', '2025-06-26 21:02:24', NULL),
	(4, NULL, 'Limpeza de Couro', 'Kit Limpeza Couro é uma solução completa para o couro do carro que combina o produto Q²M LeatherCleaner e o Gyeon Q²M LeatherCoat.', 39.95, 15, 'limpeza-de-couro.jpg', '2025-06-26 21:02:24', '2025-06-27 23:17:56', NULL);

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
