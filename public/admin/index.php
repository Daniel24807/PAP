<?php

// Carrega o autoloader do Composer
require_once '../../vendor/autoload.php';

use core\classes\database;
use core\classes\store;

// Inicia a sessão
session_start();

// Carrega as configurações
require_once '../../config.php';

// Carrega o sistema de rotas do admin
require_once '../../core/rotas_admin.php';


