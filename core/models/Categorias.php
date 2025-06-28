<?php

namespace core\models;

use core\classes\Database;
use core\classes\Store;

class Categorias {
    
    public function listar_categorias() {
        $db = new Database();
        return $db->select("
            SELECT * FROM categorias
            WHERE deleted_at IS NULL
            ORDER BY nome
        ");
    }

    public function get_categoria($id_categoria) {
        $db = new Database();
        $params = [':id' => $id_categoria];
        $categoria = $db->select("
            SELECT * FROM categorias 
            WHERE id_categoria = :id AND deleted_at IS NULL
        ", $params);
        
        return count($categoria) ? $categoria[0] : null;
    }

    public function criar_categoria($dados) {
        $db = new Database();
        
        // Preparar os parâmetros
        $params = [
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao']
        ];

        // Inserir a categoria
        $db->insert("
            INSERT INTO categorias (nome, descricao)
            VALUES (:nome, :descricao)
        ", $params);
    }

    public function atualizar_categoria($id_categoria, $dados) {
        $db = new Database();
        
        // Preparar os parâmetros
        $params = [
            ':id' => $id_categoria,
            ':nome' => $dados['nome'],
            ':descricao' => $dados['descricao']
        ];

        // Atualizar a categoria
        $db->update("
            UPDATE categorias 
            SET nome = :nome,
                descricao = :descricao
            WHERE id_categoria = :id
        ", $params);
    }

    public function eliminar_categoria($id_categoria) {
        $db = new Database();
        
        // Verificar se existem produtos nesta categoria
        $params = [':id' => $id_categoria];
        $produtos = $db->select("
            SELECT COUNT(*) as total 
            FROM produtos 
            WHERE id_categoria = :id AND deleted_at IS NULL
        ", $params);

        if ($produtos[0]->total > 0) {
            return false;
        }

        // Soft delete
        $db->update("
            UPDATE categorias 
            SET deleted_at = NOW()
            WHERE id_categoria = :id
        ", $params);

        return true;
    }
} 