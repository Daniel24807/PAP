<?php

namespace core\models;

use core\classes\Database;
use core\classes\Store;

class Produtos {
    
    public function listar_produtos() {
        $db = new Database();
        return $db->select("
            SELECT p.*, c.nome as categoria_nome 
            FROM produtos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.deleted_at IS NULL
            ORDER BY p.nome
        ");
    }

    public function get_produto($id_produto) {
        $db = new Database();
        $params = [':id' => $id_produto];
        $produto = $db->select("
            SELECT * FROM produtos 
            WHERE id_produto = :id AND deleted_at IS NULL
        ", $params);
        
        return count($produto) ? $produto[0] : null;
    }

    public function get_imagem_produto($id_produto) {
        $db = new Database();
        $params = [':id' => $id_produto];
        $resultado = $db->select("
            SELECT imagem, imagem_tipo 
            FROM produtos 
            WHERE id_produto = :id AND deleted_at IS NULL
        ", $params);
        
        return count($resultado) ? $resultado[0] : null;
    }

    public function criar_produto($dados) {
        $db = new Database();
        
        // Preparar os parâmetros
        $params = [
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'],
            ':preco' => $dados['preco'],
            ':stock' => $dados['stock'],
            ':id_categoria' => $dados['id_categoria']
        ];

        // Se houver imagem
        if(isset($dados['imagem'])) {
            $params[':imagem'] = $dados['imagem'];
            $campos_imagem = ", imagem = :imagem";
        } else {
            $campos_imagem = "";
        }

        // Inserir o produto
        $db->insert("
            INSERT INTO produtos (nome, descricao, preco, stock, id_categoria $campos_imagem)
            VALUES (:nome, :descricao, :preco, :stock, :id_categoria" . ($campos_imagem ? ", :imagem" : "") . ")
        ", $params);
    }

    public function atualizar_produto($id_produto, $dados) {
        $db = new Database();
        
        // Preparar os parâmetros
        $params = [
            ':id' => $id_produto,
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao'],
            ':preco' => $dados['preco'],
            ':stock' => $dados['stock'],
            ':id_categoria' => $dados['id_categoria']
        ];

        // Se houver imagem
        if(isset($dados['imagem'])) {
            $params[':imagem'] = $dados['imagem'];
            $campos_imagem = ", imagem = :imagem";
        } else {
            $campos_imagem = "";
        }

        try {
            // Atualizar o produto
            return $db->update("
                UPDATE produtos 
                SET nome = :nome,
                    descricao = :descricao,
                    preco = :preco,
                    stock = :stock,
                    id_categoria = :id_categoria,
                    updated_at = NOW()
                    $campos_imagem
                WHERE id_produto = :id
            ", $params);
        } catch (\Exception $e) {
            error_log("Erro ao atualizar produto (ID: $id_produto): " . $e->getMessage());
            throw $e;
        }
    }

    public function eliminar_produto($id_produto) {
        $db = new Database();
        $params = [':id' => $id_produto];
        
        try {
            // Soft delete
            return $db->update("
                UPDATE produtos 
                SET deleted_at = NOW()
                WHERE id_produto = :id
            ", $params);
        } catch (\Exception $e) {
            error_log("Erro ao eliminar produto (ID: $id_produto): " . $e->getMessage());
            throw $e;
        }
    }

    public function produtos_por_categoria($id_categoria) {
        $db = new Database();
        $params = [':id_categoria' => $id_categoria];
        return $db->select("
            SELECT p.*, c.nome as categoria_nome 
            FROM produtos p
            LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
            WHERE p.id_categoria = :id_categoria AND p.deleted_at IS NULL
            ORDER BY p.nome
        ", $params);
    }
} 