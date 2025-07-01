-- Script para atualizar o caminho das imagens na tabela produtos
-- Adicionar o prefixo 'assets/images/produtos/' a todos os nomes de arquivos de imagem

UPDATE produtos 
SET imagem = CONCAT('assets/images/produtos/', imagem)
WHERE imagem NOT LIKE 'assets/images/produtos/%';

-- Verificar os resultados após a atualização
SELECT id_produto, nome, imagem FROM produtos; 