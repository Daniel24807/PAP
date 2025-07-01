<?php
namespace core\classes;

use Exception;
use PDO;
use PDOException;

class Database
{
    // gestao da base de dados
    private $ligacao;

    private function ligar()
    {
        $this->ligacao = new PDO(
            'mysql:' .
                'host=' . MYSQL_SERVER . ';' .
                'dbname=' . MYSQL_DATABASE . ';' .
                'charset=' . MYSQL_CHARSET,
            MYSQL_USER,
            MYSQL_PASS,
            array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_PERSISTENT => false
            )
        );
        $this->ligacao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    //====================================================================================================
    private function desligar()
    {
        // fechar a ligacao
        $this->ligacao = null;
    }
    //====================================================================================================
    // Significa que podemos passar parametros ou nao 
    public function select($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match('/^SELECT/i', $sql)) {
            throw new Exception("Base de Dados - Metodo SELECT apenas aceita instrucoes SELECT.");
        }
        // Executa funcao de pesquisa de SQL
        $this->ligar();
        // todos os selects vao ter resultados
        $resultado = null;

        try {
            //Comunicacao com a bd
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                
                // Log para depuração
                error_log("SQL preparado: " . $sql);
                error_log("Parâmetros: " . print_r($parametros, true));
                
                // Verificar se os parâmetros são um array simples (sem chaves)
                $param_posicional = is_array($parametros) && array_keys($parametros) === range(0, count($parametros) - 1);
                
                if ($param_posicional) {
                    // Parâmetros posicionais (?)
                    error_log("Usando parâmetros posicionais");
                    $executar->execute($parametros);
                } else {
                    // Parâmetros nomeados (:param)
                    error_log("Usando parâmetros nomeados");
                    $executar->execute($parametros);
                }
                
                $resultado = $executar->fetchAll(\PDO::FETCH_CLASS);
                error_log("Número de registros retornados: " . count($resultado));
            } else {
                error_log("Executando consulta sem parâmetros: " . $sql);
                $executar = $this->ligacao->query($sql);
                $executar->execute();
                $resultado = $executar->fetchAll(\PDO::FETCH_CLASS);
                error_log("Número de registros retornados: " . count($resultado));
            }
        } catch (\PDOException $e) {
            error_log("Erro na consulta SQL: " . $e->getMessage());
            error_log("SQL: " . $sql);
            if (!empty($parametros)) {
                error_log("Parâmetros: " . print_r($parametros, true));
            }
            return false;
        }
        $this->desligar();
        // devolve o resultado 
        return $resultado;
    }
    //====================================================================================================
    // Insert tambem vai ter o meu sql e ter parametros
    public function insert($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match('/^INSERT/i', $sql)) {
            throw new Exception("Base de Dados - Metodo INSERT apenas aceita instrucoes INSERT.");
        }
        // Executa funcao de pesquisa de SQL
        $this->ligar();

        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $resultado = $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $resultado = $executar->execute();
            }
            $this->desligar();
            return $resultado;
        } catch (\PDOException $e) {
            $this->desligar();
            error_log("Erro na inserção SQL: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Parâmetros: " . print_r($parametros, true));
            return false;
        }
    }
    //====================================================================================================
    // Update tambem vai ter o meu sql e ter parametros
    public function update($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match('/^UPDATE/i', $sql)) {
            throw new Exception("Base de Dados - Metodo UPDATE apenas aceita instrucoes UPDATE.");
        }
        // Executa funcao de pesquisa de SQL
        $this->ligar();
        // todos os selects vao ter resultados
        $resultado = null;

        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
            $this->desligar();
            return true;
        } catch (\PDOException $e) {
            $this->desligar();
            error_log("Erro na atualização SQL: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Parâmetros: " . print_r($parametros, true));
            return false;
        }
    }
    //====================================================================================================
    // Delete tambem vai ter o meu sql e ter parametros
    public function delete($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (!preg_match('/^DELETE/i', $sql)) {
            throw new Exception("Base de Dados - Metodo DELETE apenas aceita instrucoes DELETE.");
        }
        // Executa funcao de pesquisa de SQL
        $this->ligar();
        // todos os selects vao ter resultados
        $resultado = null;

        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
            $this->desligar();
            return true;
        } catch (\PDOException $e) {
            $this->desligar();
            error_log("Erro na exclusão SQL: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Parâmetros: " . print_r($parametros, true));
            return false;
        }
    }
    //====================================================================================================
    // STATEMENT tambem vai ter o meu sql e ter parametros
    public function statement($sql, $parametros = null)
    {
        $sql = trim($sql);

        if (preg_match('/^SELECT|^INSERT|^UPDATE|^DELETE/i', $sql)) {
            throw new Exception("Base de Dados - Metodo STATEMENT apenas aceita instrucoes SELECT, INSERT, UPDATE e DELETE.");
        }
        // Executa funcao de pesquisa de SQL
        $this->ligar();
        try {
            if (!empty($parametros)) {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute($parametros);
            } else {
                $executar = $this->ligacao->prepare($sql);
                $executar->execute();
            }
            $this->desligar();
            return true;
        } catch (\PDOException $e) {
            $this->desligar();
            error_log("Erro na execução SQL: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Parâmetros: " . print_r($parametros, true));
            return false;
        }
    }
    
    //====================================================================================================
    // Retorna o ID do último registro inserido
    public function lastInsertId()
    {
        // Verifica se a conexão está aberta
        if (!$this->ligacao) {
            $this->ligar();
            $id = $this->ligacao->lastInsertId();
            $this->desligar();
            return $id;
        }
        
        return $this->ligacao->lastInsertId();
    }
}
