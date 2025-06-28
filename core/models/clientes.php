<?php

namespace core\models;

use core\classes\Database;
use core\classes\Store;

class Clientes
{

    public function Cliente_pesquisar_id($id)
    {
        //verifica se ja existe outra conta com o msm EMAIL
        //verifica na bd se existe um cliente com esse EMAIL
        //é criado o namespace da database
        //parametro por exemplo :email podia ser e: PDO
        //este meetodo evite injeçaõ por SQL
        $bd = new Database();

        $parametros = [
            ':id' => strtolower(trim($id)),
        ];

       
        $resultados= $bd->select("SELECT * FROM clientes WHERE id_cliente = :id",$parametros);

        // store::printData($resultados);

        return $resultados[0];
    }
    public function verificar_email_registado($email)
    {
        $bd = new Database();
        $parametros = [':email' => strtolower(trim($_POST['text_email']))];
        $resultados = $bd->select('SELECT email FROM clientes WHERE email = :email', $parametros);

        return is_array($resultados) && count($resultados) > 0;
    }

    public function registrar_cliente()
    {
        try {
            $bd = new Database();
            $purl = Store::criarHash();

            $parametros = [
                ':email' => strtolower(trim($_POST['text_email'])),
                ':senha' => password_hash($_POST['text_senha_1'], PASSWORD_DEFAULT),
                ':nome_completo' => trim($_POST['text_nome_completo']),
                ':morada' => trim($_POST['text_morada']),
                ':cidade' => trim($_POST['text_cidade']),
                ':telefone' => trim($_POST['text_telefone']),
                ':purl' => $purl,
                ':ativo' => 0,
            ];

            $resultado = $bd->insert("
                INSERT INTO clientes (
                    email, 
                    senha, 
                    nome_completo, 
                    morada, 
                    cidade, 
                    telefone, 
                    purl, 
                    activo, 
                    created_at, 
                    updated_at
                ) VALUES (
                    :email, 
                    :senha, 
                    :nome_completo, 
                    :morada, 
                    :cidade, 
                    :telefone, 
                    :purl, 
                    :ativo, 
                    NOW(), 
                    NOW()
                )", $parametros);
            
            if ($resultado) {
                return $purl;
            } else {
                error_log("Erro ao registrar cliente: Falha na inserção");
                return false;
            }
        } catch (\Exception $e) {
            error_log("Erro ao registrar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function validar_email($purl)
    {
        $bd = new Database();

        $parametros = [
            ':purl' => $purl
        ];
        
        $resultados = $bd->select('SELECT * FROM clientes WHERE purl = :purl', $parametros);
       


        if (!is_array($resultados) || count($resultados) != 1) {
            return false;
        }

        $id_cliente = $resultados[0]->id_cliente;

        $parametros = [
            ':id_cliente' => $id_cliente
        ];

        $bd->update("UPDATE clientes SET purl = NULL, activo = 1, updated_at = NOW() WHERE id_cliente = :id_cliente", $parametros);
      
        return true;
    }

    public function validar_login($utilizador, $password)
    {
        $bd = new Database();
        $parametros = [
            ':utilizador' => $utilizador
        ];



        $resultados = $bd->select("SELECT * FROM clientes WHERE email = :utilizador AND activo = 1 AND deleted_at IS NULL", $parametros);

        if (!is_array($resultados) || count($resultados) != 1) {
            return false;
        }

        $utilizador = $resultados[0];

        return password_verify($password, $utilizador->senha) ? $utilizador : false;
    }

    public function lista_clientes()
    {
        $bd = new Database();
        $resultados = $bd->select("SELECT * FROM clientes");
        return is_array($resultados) ? $resultados : [];
    }

    public function cliente_apagar_hard($id)
    {
        //verifica na bd se existe outro cliente com o mesmo email
        $bd = new Database;

        $parametros = [
            ':id' => strtolower(trim($id)),
        ];

        $bd->delete("DELETE FROM clientes WHERE id_cliente = :id",$parametros);
        //echo $bd;
        return;
    }

    public function atualizar_cliente($id)
    {
        $bd = new Database();
        
        $parametros = [
            ':id' => $id,
            ':email' => strtolower(trim($_POST['text_email'])),
            ':nome_completo' => trim($_POST['text_nome_completo']),
            ':morada' => trim($_POST['text_morada']),
            ':cidade' => trim($_POST['text_cidade']),
            ':telefone' => trim($_POST['text_telefone']),
            ':activo' => isset($_POST['check_activo']) ? 1 : 0
        ];

        // Se uma nova senha foi fornecida, atualiza a senha
        if (!empty($_POST['text_senha_1'])) {
            $parametros[':senha'] = password_hash($_POST['text_senha_1'], PASSWORD_DEFAULT);
            $bd->update("UPDATE clientes SET 
                email = :email,
                senha = :senha,
                nome_completo = :nome_completo,
                morada = :morada,
                cidade = :cidade,
                telefone = :telefone,
                activo = :activo,
                updated_at = NOW()
                WHERE id_cliente = :id", $parametros);
        } else {
            $bd->update("UPDATE clientes SET 
                email = :email,
                nome_completo = :nome_completo,
                morada = :morada,
                cidade = :cidade,
                telefone = :telefone,
                activo = :activo,
                updated_at = NOW()
                WHERE id_cliente = :id", $parametros);
        }
    }

    /**
     * Verifica se um email já existe na base de dados
     * @param string $email
     * @return bool
     */
    public function verificar_email_existe($email)
    {
        $db = new Database();
        $parametros = [
            ':email' => strtolower(trim($email))
        ];
        $resultados = $db->select("SELECT id_cliente FROM clientes WHERE email = :email", $parametros);
        return count($resultados) != 0;
    }

    /**
     * Adiciona um novo cliente à base de dados
     * @param array $dados
     * @return bool
     */
    public function adicionar_cliente($dados)
    {
        $db = new Database();
        
        $parametros = [
            ':nome_completo' => $dados['nome_completo'],
            ':email' => strtolower(trim($dados['email'])),
            ':senha' => $dados['senha'],
            ':morada' => $dados['morada'],
            ':cidade' => $dados['cidade'],
            ':telefone' => $dados['telefone'],
            ':activo' => $dados['activo'],
            ':created_at' => date('Y-m-d H:i:s')
        ];
        
        $db->insert("
            INSERT INTO clientes (nome_completo, email, senha, morada, cidade, telefone, activo, created_at) 
            VALUES (:nome_completo, :email, :senha, :morada, :cidade, :telefone, :activo, :created_at)
        ", $parametros);
        
        return true;
    }

    /**
     * Busca um cliente pelo ID
     * @param int $id_cliente
     * @return object|bool
     */
    public function buscar_cliente($id_cliente)
    {
        $db = new Database();
        $parametros = [
            ':id_cliente' => $id_cliente
        ];
        $resultados = $db->select("SELECT * FROM clientes WHERE id_cliente = :id_cliente", $parametros);
        
        if (count($resultados) == 0) {
            return false;
        }
        
        return $resultados[0];
    }

    /**
     * Atualiza um cliente na base de dados (versão para admin)
     * @param array $dados
     * @return bool
     */
    public function atualizar_cliente_admin($dados)
    {
        $db = new Database();
        
        // Se a senha foi alterada
        if (isset($dados['senha'])) {
            $parametros = [
                ':id_cliente' => $dados['id_cliente'],
                ':nome_completo' => $dados['nome_completo'],
                ':email' => strtolower(trim($dados['email'])),
                ':senha' => $dados['senha'],
                ':morada' => $dados['morada'],
                ':cidade' => $dados['cidade'],
                ':telefone' => $dados['telefone'],
                ':activo' => $dados['activo'],
                ':updated_at' => date('Y-m-d H:i:s')
            ];
            
            $db->update("
                UPDATE clientes SET 
                    nome_completo = :nome_completo,
                    email = :email,
                    senha = :senha,
                    morada = :morada,
                    cidade = :cidade,
                    telefone = :telefone,
                    activo = :activo,
                    updated_at = :updated_at
                WHERE id_cliente = :id_cliente
            ", $parametros);
        } else {
            $parametros = [
                ':id_cliente' => $dados['id_cliente'],
                ':nome_completo' => $dados['nome_completo'],
                ':email' => strtolower(trim($dados['email'])),
                ':morada' => $dados['morada'],
                ':cidade' => $dados['cidade'],
                ':telefone' => $dados['telefone'],
                ':activo' => $dados['activo'],
                ':updated_at' => date('Y-m-d H:i:s')
            ];
            
            $db->update("
                UPDATE clientes SET 
                    nome_completo = :nome_completo,
                    email = :email,
                    morada = :morada,
                    cidade = :cidade,
                    telefone = :telefone,
                    activo = :activo,
                    updated_at = :updated_at
                WHERE id_cliente = :id_cliente
            ", $parametros);
        }
        
        return true;
    }

    public function verificar_token_valido($token) {
        $bd = new Database();
        $params = [
            ':token' => $token
        ];

        $resultados = $bd->select("
            SELECT * FROM clientes 
            WHERE reset_token = :token 
            AND reset_token_expira > NOW() 
            AND deleted_at IS NULL
        ", $params);

        return is_array($resultados) && count($resultados) == 1;
    }

    public function atualizar_senha_com_token($token, $nova_senha) {
        $db = new Database();
        
        // Verificar se o token existe e não expirou
        $parametros = [':token' => $token];
        $resultados = $db->select("
            SELECT id_cliente 
            FROM clientes 
            WHERE reset_token = :token 
            AND reset_token_expira > NOW()
        ", $parametros);
        
        if (count($resultados) == 0) {
            return false;
        }
        
        $id_cliente = $resultados[0]->id_cliente;
        
        // Atualizar a senha e limpar o token
        $parametros = [
            ':id_cliente' => $id_cliente,
            ':senha' => password_hash($nova_senha, PASSWORD_DEFAULT)
        ];
        
        $db->update("
            UPDATE clientes 
            SET senha = :senha,
                reset_token = NULL,
                reset_token_expira = NULL,
                updated_at = NOW()
            WHERE id_cliente = :id_cliente
        ", $parametros);
        
        return true;
    }
    
    /**
     * Busca os dados completos de um cliente
     * @param int $id_cliente
     * @return object|bool
     */
    public function buscar_dados_cliente($id_cliente)
    {
        $db = new Database();
        
        $parametros = [':id_cliente' => $id_cliente];
        $resultados = $db->select("
            SELECT * 
            FROM clientes 
            WHERE id_cliente = :id_cliente 
            AND activo = 1 
            AND deleted_at IS NULL
        ", $parametros);
        
        return count($resultados) > 0 ? $resultados[0] : false;
    }
    
    /**
     * Altera a senha de um cliente
     * @param int $id_cliente
     * @param string $senha_atual
     * @param string $nova_senha
     * @return bool
     */
    public function alterar_senha($id_cliente, $senha_atual, $nova_senha)
    {
        $db = new Database();
        
        // Verificar se a senha atual está correta
        $parametros = [':id_cliente' => $id_cliente];
        $resultados = $db->select("
            SELECT senha 
            FROM clientes 
            WHERE id_cliente = :id_cliente 
            AND activo = 1 
            AND deleted_at IS NULL
        ", $parametros);
        
        if (count($resultados) == 0) {
            return false;
        }
        
        if (!password_verify($senha_atual, $resultados[0]->senha)) {
            return false;
        }
        
        // Atualizar a senha
        $parametros = [
            ':id_cliente' => $id_cliente,
            ':senha' => password_hash($nova_senha, PASSWORD_DEFAULT)
        ];
        
        $db->update("
            UPDATE clientes 
            SET senha = :senha,
                updated_at = NOW()
            WHERE id_cliente = :id_cliente
        ", $parametros);
        
        return true;
    }
    
    /**
     * Altera a morada de um cliente
     * @param int $id_cliente
     * @param string $morada
     * @param string $cidade
     * @return bool
     */
    public function alterar_morada($id_cliente, $morada, $cidade)
    {
        $db = new Database();
        
        $parametros = [
            ':id_cliente' => $id_cliente,
            ':morada' => $morada,
            ':cidade' => $cidade
        ];
        
        $db->update("
            UPDATE clientes 
            SET morada = :morada,
                cidade = :cidade,
                updated_at = NOW()
            WHERE id_cliente = :id_cliente
        ", $parametros);
        
        return true;
    }
}
