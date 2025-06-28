<?php

namespace core\models;

use core\classes\Database;
use core\classes\Store;
use Exception;

class AdminModel
{
    public function validar_login($admin, $password)
    {
        try {
            $bd = new Database();
            $parametros = [
                ':utilizador' => $admin
            ];

            $resultados = $bd->select("
                SELECT * 
                FROM admins 
                WHERE utilizador = :utilizador
            ", $parametros);

            if (!is_array($resultados) || count($resultados) != 1) {
                return false;
            }

            $utilizador = $resultados[0];
            
            // Verificar a senha usando password_verify
            if (password_verify($password, $utilizador->senha)) {
                return $utilizador;
            }

            return false;

        } catch (Exception $e) {
            return false;
        }
    }

    public function get_admin_data($id_admin)
    {
        $bd = new Database();
        $parametros = [
            ':id_admin' => $id_admin
        ];

        $resultados = $bd->select("SELECT * FROM admins WHERE id_admin = :id_admin", $parametros);
        
        if (!is_array($resultados) || count($resultados) != 1) {
            throw new Exception("Administrador não encontrado.");
        }
        
        return $resultados[0];
    }

    public function validar_senha($id_admin, $senha_atual)
    {
        $bd = new Database();
        $parametros = [
            ':id_admin' => $id_admin
        ];

        $resultados = $bd->select("SELECT * FROM admins WHERE id_admin = :id_admin", $parametros);
        
        if (!is_array($resultados) || count($resultados) != 1) {
            throw new Exception("Administrador não encontrado.");
        }

        $admin = $resultados[0];
        return password_verify($senha_atual, $admin->senha);
    }

    public function atualizar_perfil($id_admin)
    {
        $bd = new Database();
        
        // Verificar se o email já existe para outro admin
        $parametros = [
            ':utilizador' => strtolower(trim($_POST['text_utilizador'])),
            ':id_admin' => $id_admin
        ];

        $resultados = $bd->select("SELECT * FROM admins WHERE utilizador = :utilizador AND id_admin != :id_admin", $parametros);
        if (is_array($resultados) && count($resultados) > 0) {
            throw new Exception("Este email já está sendo usado por outro administrador.");
        }

        // Se uma nova senha foi fornecida
        if (!empty($_POST['text_nova_senha'])) {
            if (strlen($_POST['text_nova_senha']) < 6) {
                throw new Exception("A nova senha deve ter no mínimo 6 caracteres.");
            }

            $parametros = [
                ':id_admin' => $id_admin,
                ':utilizador' => strtolower(trim($_POST['text_utilizador'])),
                ':senha' => password_hash($_POST['text_nova_senha'], PASSWORD_DEFAULT)
            ];

            $bd->update("UPDATE admins SET 
                utilizador = :utilizador,
                senha = :senha,
                updated_at = NOW()
                WHERE id_admin = :id_admin", $parametros);
        } else {
            $parametros = [
                ':id_admin' => $id_admin,
                ':utilizador' => strtolower(trim($_POST['text_utilizador']))
            ];

            $bd->update("UPDATE admins SET 
                utilizador = :utilizador,
                updated_at = NOW()
                WHERE id_admin = :id_admin", $parametros);
        }
    }
    
    public function atualizar_email($id_admin, $novo_email)
    {
        $bd = new Database();
        
        // Validar o formato do email
        if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("O formato do email é inválido.");
        }
        
        // Verificar se o email já existe para outro admin
        $parametros = [
            ':utilizador' => strtolower(trim($novo_email)),
            ':id_admin' => $id_admin
        ];

        $resultados = $bd->select("SELECT * FROM admins WHERE utilizador = :utilizador AND id_admin != :id_admin", $parametros);
        if (is_array($resultados) && count($resultados) > 0) {
            throw new Exception("Este email já está sendo usado por outro administrador.");
        }

        // Atualizar o email
        $parametros = [
            ':id_admin' => $id_admin,
            ':utilizador' => strtolower(trim($novo_email))
        ];

        $bd->update("UPDATE admins SET 
            utilizador = :utilizador,
            updated_at = NOW()
            WHERE id_admin = :id_admin", $parametros);
            
        // Atualizar a sessão com o novo email
        $_SESSION['admin_utilizador'] = strtolower(trim($novo_email));
    }
    
    public function atualizar_senha($id_admin, $nova_senha)
    {
        $bd = new Database();
        
        // Validar o tamanho da senha
        if (strlen($nova_senha) < 6) {
            throw new Exception("A nova senha deve ter no mínimo 6 caracteres.");
        }

        // Atualizar a senha
        $parametros = [
            ':id_admin' => $id_admin,
            ':senha' => password_hash($nova_senha, PASSWORD_DEFAULT)
        ];

        $bd->update("UPDATE admins SET 
            senha = :senha,
            updated_at = NOW()
            WHERE id_admin = :id_admin", $parametros);
    }

    public function buscar_admin_por_id($id_admin)
    {
        try {
            $bd = new Database();
            $parametros = [
                ':id_admin' => $id_admin
            ];

            $resultados = $bd->select("SELECT * FROM admins WHERE id_admin = :id_admin", $parametros);
            
            if (!is_array($resultados) || count($resultados) != 1) {
                throw new Exception("Administrador não encontrado.");
            }
            
            return $resultados[0];
        } catch (Exception $e) {
            // Registrar o erro
            error_log("Erro ao buscar administrador: " . $e->getMessage());
            return false;
        }
    }
}
