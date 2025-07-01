<?php

namespace core\models;

use core\classes\Database;
use core\classes\Store;
use Exception;
use PDO;
use PDOException;

class Pedidos
{
    /**
     * Cria um novo pedido na base de dados
     */
    public function criar_pedido($id_cliente, $endereco, $cidade, $codigo_postal, $telefone, $metodo_pagamento)
    {
        $db = new Database();
        
        try {
            // Obter itens do carrinho
            $carrinho = new Carrinho();
            $itens_carrinho = $carrinho->obter_carrinho($id_cliente);
            
            if (empty($itens_carrinho)) {
                throw new Exception("Carrinho vazio");
            }
            
            // Calcular valor total
            $total = 0;
            foreach ($itens_carrinho as $item) {
                $total += $item['preco'] * $item['quantidade'];
            }
            
            // Gerar código do pedido único
            $codigo_pedido = $this->gerar_codigo_pedido();
            
            // Inserir pedido
            $params = [
                ':id_cliente' => $id_cliente,
                ':codigo_pedido' => $codigo_pedido,
                ':status' => 'pendente',
                ':endereco' => $endereco,
                ':cidade' => $cidade,
                ':codigo_postal' => $codigo_postal,
                ':telefone' => $telefone,
                ':metodo_pagamento' => $metodo_pagamento,
                ':total' => $total
            ];
            
            $resultado_insert = $db->insert("
                INSERT INTO pedidos (
                    id_cliente, codigo_pedido, status, 
                    endereco, cidade, codigo_postal, telefone,
                    metodo_pagamento, total, data_pedido
                ) VALUES (
                    :id_cliente, :codigo_pedido, :status,
                    :endereco, :cidade, :codigo_postal, :telefone,
                    :metodo_pagamento, :total, NOW()
                )
            ", $params);
            
            if (!$resultado_insert) {
                throw new Exception("Erro ao inserir o pedido");
            }
            
            // Obter o ID do pedido inserido consultando pelo código do pedido
            $params = [':codigo' => $codigo_pedido];
            $resultado = $db->select("SELECT id_pedido FROM pedidos WHERE codigo_pedido = :codigo", $params);
            
            if (empty($resultado)) {
                throw new Exception("Erro ao obter o ID do pedido");
            }
            
            $id_pedido = $resultado[0]->id_pedido;
            
            // Inserir itens do pedido
            foreach ($itens_carrinho as $item) {
                $params = [
                    ':id_pedido' => $id_pedido,
                    ':id_produto' => $item['id_produto'],
                    ':quantidade' => $item['quantidade'],
                    ':preco' => $item['preco']
                ];
                
                $db->insert("
                    INSERT INTO pedido_itens (
                        id_pedido, id_produto, quantidade, preco
                    ) VALUES (
                        :id_pedido, :id_produto, :quantidade, :preco
                    )
                ", $params);
            }
            
            // Limpar o carrinho
            $carrinho->limpar_carrinho($id_cliente);
            
            return [
                'status' => true,
                'id_pedido' => $id_pedido,
                'codigo_pedido' => $codigo_pedido
            ];
            
        } catch (Exception $e) {
            return [
                'status' => false,
                'mensagem' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Gera um código único para o pedido
     */
    private function gerar_codigo_pedido($tamanho = 8)
    {
        $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $codigo = '';
        
        for ($i = 0; $i < $tamanho; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        // Verificar se o código já existe
        $db = new Database();
        $params = [':codigo' => $codigo];
        $resultado = $db->select("SELECT id_pedido FROM pedidos WHERE codigo_pedido = :codigo", $params);
        
        if (count($resultado) > 0) {
            // Se já existe, gerar outro código
            return $this->gerar_codigo_pedido($tamanho);
        }
        
        return $codigo;
    }
    
    /**
     * Obter detalhes de um pedido específico
     */
    public function obter_pedido($id_pedido)
    {
        $db = new Database();
        
        // Obter dados do pedido
        $params = [':id_pedido' => $id_pedido];
        $pedido = $db->select("
            SELECT p.*, c.nome, c.email
            FROM pedidos p
            INNER JOIN clientes c ON p.id_cliente = c.id_cliente
            WHERE p.id_pedido = :id_pedido
        ", $params);
        
        if (empty($pedido)) {
            return false;
        }
        
        // Obter itens do pedido
        $itens = $db->select("
            SELECT pi.*, pr.nome, pr.imagem
            FROM pedido_itens pi
            INNER JOIN produtos pr ON pi.id_produto = pr.id_produto
            WHERE pi.id_pedido = :id_pedido
        ", $params);
        
        return [
            'pedido' => $pedido[0],
            'itens' => $itens
        ];
    }
    
    /**
     * Listar pedidos de um cliente específico
     */
    public function listar_pedidos_cliente($id_cliente)
    {
        $db = new Database();
        
        $params = [':id_cliente' => $id_cliente];
        $pedidos = $db->select("
            SELECT id_pedido, codigo_pedido, status, total, data_pedido
            FROM pedidos
            WHERE id_cliente = :id_cliente
            ORDER BY data_pedido DESC
        ", $params);
        
        return $pedidos;
    }
    
    /**
     * Listar todos os pedidos (para admin)
     */
    public function listar_todos_pedidos()
    {
        $db = new Database();
        
        // Consulta SQL simples para obter todos os pedidos com o nome do cliente
        $sql = "SELECT p.*, c.nome_completo 
                FROM pedidos p 
                LEFT JOIN clientes c ON p.id_cliente = c.id_cliente 
                ORDER BY p.data_pedido DESC";
        
        return $db->select($sql);
    }
    
    /**
     * Atualizar status de um pedido
     */
    public function atualizar_status_pedido($id_pedido, $status)
    {
        $db = new Database();
        
        $params = [
            ':id_pedido' => $id_pedido,
            ':status' => $status
        ];
        
        $db->update("
            UPDATE pedidos
            SET status = :status
            WHERE id_pedido = :id_pedido
        ", $params);
        
        return true;
    }
    
    /**
     * Buscar pedidos por nome do cliente
     */
    public function buscar_pedidos_por_nome($nome)
    {
        $db = new Database();
        
        // Primeiro, buscar os IDs dos clientes que correspondem ao nome
        $params = [':nome' => '%' . $nome . '%'];
        $clientes = $db->select("
            SELECT id_cliente, nome_completo 
            FROM clientes 
            WHERE nome_completo LIKE :nome
        ", $params);
        
        if (empty($clientes)) {
            return [];
        }
        
        // Extrair os IDs dos clientes
        $ids_clientes = [];
        $mapa_clientes = [];
        foreach ($clientes as $cliente) {
            $ids_clientes[] = $cliente->id_cliente;
            $mapa_clientes[$cliente->id_cliente] = $cliente->nome_completo;
        }
        
        // Criar placeholders para a consulta IN
        $placeholders = implode(',', array_fill(0, count($ids_clientes), '?'));
        
        // Buscar pedidos dos clientes encontrados
        $params = $ids_clientes;
        $sql = "
            SELECT * 
            FROM pedidos 
            WHERE id_cliente IN ($placeholders)
            ORDER BY data_pedido DESC
        ";
        
        $pedidos = $db->select($sql, $params);
        
        if (empty($pedidos)) {
            return [];
        }
        
        // Adicionar o nome do cliente a cada pedido
        foreach ($pedidos as &$pedido) {
            if (isset($mapa_clientes[$pedido->id_cliente])) {
                $pedido->nome = $mapa_clientes[$pedido->id_cliente];
            } else {
                $pedido->nome = "Cliente #" . $pedido->id_cliente;
            }
        }
        
        return $pedidos;
    }
    
    /**
     * Contar o número total de pedidos
     */
    public function contar_pedidos()
    {
        $db = new Database();
        
        $resultado = $db->select("SELECT COUNT(*) as total FROM pedidos");
        
        if ($resultado && isset($resultado[0]->total)) {
            return $resultado[0]->total;
        }
        
        return 0;
    }
} 