<?php

namespace core\models;

use core\classes\Database;
use core\classes\Store;

class Carrinho
{
    /**
     * Obtém todos os itens do carrinho de um usuário
     * @param int $id_cliente ID do cliente
     * @return array Itens do carrinho
     */
    public function obter_carrinho($id_cliente)
    {
        $bd = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente
        ];

        $sql = "SELECT ci.*, p.nome, p.preco, p.imagem 
                FROM cart_items ci
                INNER JOIN produtos p ON ci.product_id = p.id_produto
                WHERE ci.user_id = :id_cliente
                ORDER BY ci.created_at DESC";

        $resultados = $bd->select($sql, $parametros);
        
        // Mapear os resultados para o formato esperado pela aplicação
        $itens = [];
        if ($resultados) {
            foreach ($resultados as $item) {
                $itens[] = [
                    'id_produto' => $item->product_id,
                    'quantidade' => $item->quantity,
                    'nome' => $item->nome,
                    'preco' => $item->preco,
                    'imagem' => $item->imagem
                ];
            }
        }
        
        return $itens;
    }

    /**
     * Adiciona um produto ao carrinho
     * @param int $id_cliente ID do cliente
     * @param int $id_produto ID do produto
     * @param int $quantidade Quantidade do produto
     * @return bool
     */
    public function adicionar_produto($id_cliente, $id_produto, $quantidade = 1)
    {
        // Verificar se o produto já existe no carrinho
        $bd = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente,
            ':id_produto' => $id_produto
        ];

        $resultados = $bd->select("SELECT * FROM cart_items WHERE user_id = :id_cliente AND product_id = :id_produto", $parametros);

        // Se o produto já existe no carrinho, atualiza a quantidade
        if (count($resultados) > 0) {
            $parametros = [
                ':id_cliente' => $id_cliente,
                ':id_produto' => $id_produto,
                ':quantidade' => $quantidade
            ];

            return $bd->update("UPDATE cart_items SET quantity = quantity + :quantidade WHERE user_id = :id_cliente AND product_id = :id_produto", $parametros);
        } 
        // Senão, adiciona um novo item
        else {
            $parametros = [
                ':id_cliente' => $id_cliente,
                ':id_produto' => $id_produto,
                ':quantidade' => $quantidade
            ];

            return $bd->insert("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:id_cliente, :id_produto, :quantidade)", $parametros);
        }
    }

    /**
     * Atualiza a quantidade de um produto no carrinho
     * @param int $id_cliente ID do cliente
     * @param int $id_produto ID do produto
     * @param int $quantidade Nova quantidade
     * @return bool
     */
    public function atualizar_quantidade($id_cliente, $id_produto, $quantidade)
    {
        $bd = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente,
            ':id_produto' => $id_produto,
            ':quantidade' => $quantidade
        ];

        return $bd->update("UPDATE cart_items SET quantity = :quantidade WHERE user_id = :id_cliente AND product_id = :id_produto", $parametros);
    }

    /**
     * Remove um produto do carrinho
     * @param int $id_cliente ID do cliente
     * @param int $id_produto ID do produto
     * @return bool
     */
    public function remover_produto($id_cliente, $id_produto)
    {
        $bd = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente,
            ':id_produto' => $id_produto
        ];

        return $bd->delete("DELETE FROM cart_items WHERE user_id = :id_cliente AND product_id = :id_produto", $parametros);
    }

    /**
     * Limpa o carrinho do cliente
     * @param int $id_cliente ID do cliente
     * @return bool
     */
    public function limpar_carrinho($id_cliente)
    {
        $bd = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente
        ];

        return $bd->delete("DELETE FROM cart_items WHERE user_id = :id_cliente", $parametros);
    }

    /**
     * Conta o número de itens no carrinho
     * @param int $id_cliente ID do cliente
     * @return int
     */
    public function contar_itens($id_cliente)
    {
        $bd = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente
        ];

        $resultados = $bd->select("SELECT SUM(quantity) as total FROM cart_items WHERE user_id = :id_cliente", $parametros);
        
        return $resultados && isset($resultados[0]->total) ? $resultados[0]->total : 0;
    }

    /**
     * Sincroniza o carrinho local com o carrinho do banco de dados
     * @param int $id_cliente ID do cliente
     * @param array $carrinho_local Carrinho local
     * @return bool
     */
    public function sincronizar_carrinho($id_cliente, $carrinho_local)
    {
        // Primeiro limpa o carrinho do cliente
        $this->limpar_carrinho($id_cliente);

        // Depois adiciona os itens do carrinho local
        if (empty($carrinho_local)) {
            return true;
        }

        $bd = new Database();
        $sucesso = true;

        foreach ($carrinho_local as $item) {
            $parametros = [
                ':id_cliente' => $id_cliente,
                ':id_produto' => isset($item['id_produto']) ? $item['id_produto'] : $item['id'],
                ':quantidade' => isset($item['quantidade']) ? $item['quantidade'] : $item['quantity']
            ];

            $resultado = $bd->insert("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (:id_cliente, :id_produto, :quantidade)", $parametros);
            
            if (!$resultado) {
                $sucesso = false;
            }
        }

        return $sucesso;
    }
} 