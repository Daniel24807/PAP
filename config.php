<?php

// Evita inclusão duplicada
if (!defined('APP_NAME')) {
    define("APP_NAME", "ProShine Algarve");
    define("APP_VERSION", "1.0.0");

    // MYSQL 
    define("MYSQL_SERVER", "localhost");
    define("MYSQL_DATABASE", "php_store");
    define("MYSQL_USER", "root");
    define("MYSQL_PASS", "");
    define('MYSQL_CHARSET', 'utf8');

    // EMAIL
    define("EMAIL_HOST", "smtp.gmail.com");
    define("EMAIL_FROM", "salvadordaniel248@gmail.com");
    define("EMAIL_PASS", "ipuu miyy jkbe pmpd");
    define("EMAIL_PORT", 465);

    // URL Base do sistema (ajustada para o caminho correto)
    define('BASE_URL', 'http://localhost:8080/');
}