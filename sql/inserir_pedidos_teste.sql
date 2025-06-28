-- Usar a base de dados existente
USE `php_store`;

-- Inserir dados de teste na tabela de pedidos
-- Verificar primeiro se os clientes existem (id_cliente = 11, 14, 15)

-- Pedido do cliente ID 11 (Daniel)
INSERT INTO `pedidos` (
  `id_cliente`, `codigo_pedido`, `status`, 
  `endereco`, `cidade`, `codigo_postal`, 
  `telefone`, `metodo_pagamento`, `total`, `data_pedido`
) VALUES (
  11, 'ABC12345', 'pendente', 
  'Rua da Oliveira', 'Olhão', '8700-123', 
  '939495969', 'cartão', 299.99, NOW() - INTERVAL 2 DAY
);

-- Recuperar o ID do pedido inserido
SET @pedido1 = LAST_INSERT_ID();

-- Inserir itens para o pedido 1
INSERT INTO `pedido_itens` (
  `id_pedido`, `id_produto`, `quantidade`, `preco`
) VALUES 
(@pedido1, 1, 1, 199.99),
(@pedido1, 2, 1, 100.00);

-- Pedido do cliente ID 14 (Usuário Teste)
INSERT INTO `pedidos` (
  `id_cliente`, `codigo_pedido`, `status`, 
  `endereco`, `cidade`, `codigo_postal`, 
  `telefone`, `metodo_pagamento`, `total`, `data_pedido`
) VALUES (
  14, 'DEF67890', 'processando', 
  'Rua Exemplo, 123', 'Cidade Exemplo', '1000-123', 
  '999999999', 'paypal', 450.00, NOW() - INTERVAL 5 DAY
);

-- Recuperar o ID do pedido inserido
SET @pedido2 = LAST_INSERT_ID();

-- Inserir itens para o pedido 2
INSERT INTO `pedido_itens` (
  `id_pedido`, `id_produto`, `quantidade`, `preco`
) VALUES 
(@pedido2, 1, 2, 199.99),
(@pedido2, 3, 1, 50.02);

-- Pedido do cliente ID 15 (Daniel Salvador)
INSERT INTO `pedidos` (
  `id_cliente`, `codigo_pedido`, `status`, 
  `endereco`, `cidade`, `codigo_postal`, 
  `telefone`, `metodo_pagamento`, `total`, `data_pedido`
) VALUES (
  15, 'GHI13579', 'entregue', 
  'Rua da Cerca', 'Olhão', '8700-456', 
  '963228574', 'transferência', 1250.50, NOW() - INTERVAL 15 DAY
);

-- Recuperar o ID do pedido inserido
SET @pedido3 = LAST_INSERT_ID();

-- Inserir itens para o pedido 3
INSERT INTO `pedido_itens` (
  `id_pedido`, `id_produto`, `quantidade`, `preco`
) VALUES 
(@pedido3, 2, 5, 100.00),
(@pedido3, 3, 2, 50.02),
(@pedido3, 1, 3, 199.99);

-- Pedido adicional do cliente ID 15 (mais recente)
INSERT INTO `pedidos` (
  `id_cliente`, `codigo_pedido`, `status`, 
  `endereco`, `cidade`, `codigo_postal`, 
  `telefone`, `metodo_pagamento`, `total`, `data_pedido`
) VALUES (
  15, 'JKL24680', 'enviado', 
  'Rua da Cerca', 'Olhão', '8700-456', 
  '963228574', 'cartão', 599.98, NOW() - INTERVAL 1 DAY
);

-- Recuperar o ID do pedido inserido
SET @pedido4 = LAST_INSERT_ID();

-- Inserir itens para o pedido 4
INSERT INTO `pedido_itens` (
  `id_pedido`, `id_produto`, `quantidade`, `preco`
) VALUES 
(@pedido4, 1, 3, 199.99); 