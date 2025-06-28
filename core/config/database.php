<?php

define('MYSQL_SERVER',    'localhost');
define('MYSQL_DATABASE',  'php_store');
define('MYSQL_USER',     'root');
define('MYSQL_PASS',     '');
define('MYSQL_CHARSET',  'utf8');

try {
    $conn = new mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
    
    if ($conn->connect_error) {
        throw new Exception("Erro na conexão: " . $conn->connect_error);
    }
    
    $conn->set_charset(MYSQL_CHARSET);
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
} 