-- Usar a base de dados existente
USE `php_store`;

-- Inserir produtos
INSERT INTO `produtos` (`id_produto`, `id_categoria`, `nome`, `descricao`, `preco`, `stock`, `imagem`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'Total Remover', 'Foi desenvolvido para remover tudo das superfícies externas dos veículos sem danificá-las. Perfeito para preparar carros recém-saídos de fábrica antes de aplicar um coating ou para remover revestimentos antigos.', 19.90, 50, 'assets/images/gyeon/gyeon-total-remover.jpg', NOW(), NOW(), NULL),
(2, NULL, 'Selante Hidrofóbico Instantâneo em Spray', 'O Gyeon Q²M WetCoat é um selante spray de alta performance que oferece proteção instantânea, efeito hidrofóbico e brilho intenso.', 14.90, 30, 'assets/images/gyeon/gyeon-wet-coat.jpg', NOW(), NOW(), NULL),
(3, NULL, 'Limpeza de Vidros', 'Limpa-vidros de alta qualidade para carros. Remove resíduos de óleo, manchas, gordura, impressões digitais, sujidade em geral e vestígios de insetos.', 10.95, 45, 'assets/images/gyeon/gyeon-glass.jpg', NOW(), NOW(), NULL),
(4, NULL, 'Limpeza de Couro', 'Kit Limpeza Couro é uma solução completa para o couro do carro que combina o produto Q²M LeatherCleaner e o Gyeon Q²M LeatherCoat.', 39.95, 15, 'assets/images/gyeon/gyeon-leather-set-strong.jpg', NOW(), NOW(), NULL); 