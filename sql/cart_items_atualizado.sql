-- Usar a base de dados existente
USE `php_store`;

-- Tabela de itens do carrinho
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabela de pedidos
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
  CONSTRAINT `FK_pedidos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabela de itens do pedido
CREATE TABLE IF NOT EXISTS `pedido_itens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pedido` int(11) NOT NULL,
  `id_produto` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_pedido_itens_pedido` (`id_pedido`),
  KEY `FK_pedido_itens_produtos` (`id_produto`),
  CONSTRAINT `FK_pedido_itens_pedidos` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE,
  CONSTRAINT `FK_pedido_itens_produtos` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`id_produto`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; 