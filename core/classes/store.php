<?php

namespace core\classes;

class store
{
    public static function Layout($estruturas, $dados = NULL)
    {
        if (!is_array($estruturas)) {
            throw new \Exception("Erro: O parametro passado para a funcao Layout() deve ser um array.");
        }

        if (!empty($dados) && is_array($dados)) {
            extract($dados);
        }

        foreach ($estruturas as $estrutura) {
            include(__DIR__ . "/../views/{$estrutura}.php");
        }
    }

    public static function Layout_Admin($estruturas, $dados = NULL)
    {
        if (!is_array($estruturas)) {
            throw new \Exception("Erro: O parametro passado para a funcao Layout() deve ser um array.");
        }

        if (!empty($dados) && is_array($dados)) {
            extract($dados);
        }

        foreach ($estruturas as $estrutura) {
            include(__DIR__ . "/../views/admin/{$estrutura}.php");
        }
    }

    public static function clienteLogado()
    {
        return isset($_SESSION['cliente']);
    }

    public static function criarHash($num_caraters = 12)
    {
        $chars =
            '01234567890123456789abcdefghijlmnopqrstuvwxyzabcdefghijlmnopqrstuvwxyzABCDEFGH
IJLMNOPQRSTUVWXYZABCDEFGHIJLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, $num_caraters);
    }

    public static function redirect($rota = '', $admin = false)
    {
        // Faz o redirecionamento para a URL desejada (rota)
        if (!$admin) {
            header("Location: " . self::generateUrl($rota));
        } else {
            header("Location: " . self::generateUrl($rota, true));
        }
    }

    public static function generateUrl($rota = '', $admin = false)
    {
        // Remove espaços e caracteres especiais da BASE_URL
        $base = str_replace(' ', '%20', BASE_URL);
        
        if (!$admin) {
            return $base . '/index.php' . ($rota ? '?a=' . $rota : '');
        } else {
            return $base . '/admin/index.php' . ($rota ? '?a=' . $rota : '');
        }
    }

    public static function printData($data, $die = true)
    {
        if (is_array($data) || is_object($data)) {
            echo "<pre>";
            print_r($data);
        } else {
            echo "<pre>";
            echo $data;
        }
        if ($die) {
            die("FIM");
        }
    }

    //============================ Admin Logado ============================
    public static function adminLogado()
    {
        return isset($_SESSION['admin']);
    }

    public static function getBaseUrl()
    {
        return str_replace(' ', '%20', BASE_URL);
    }
}
