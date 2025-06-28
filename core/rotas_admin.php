<?php
use core\classes\store;

// Carrega as configurações se ainda não foram carregadas
if (!defined('APP_NAME')) {
    require_once '../config.php';
}

$rotas = [
    'inicio' => 'admin@index',
    'admin_login' => 'admin@admin_login',
    'admin_login_submit' => 'admin@admin_login_submit',
    'admin_logout' => 'admin@admin_logout', 
    'clientes' => 'admin@listar_clientes',
    'cliente_editar' => 'admin@cliente_editar',
    'cliente_editar_submit' => 'admin@cliente_editar_submit',
    'cliente_apagar_Hard' => 'admin@cliente_apagar_Hard',
    'cliente_apagar_Hard_confirm' => 'admin@cliente_apagar_Hard_confirm',
    'novo_cliente' => 'admin@novo_cliente',
    'cliente_adicionar_submit' => 'admin@cliente_adicionar_submit',
    
    // Perfil do Administrador
    'perfil_admin' => 'admin@perfil_admin',
    'atualizar_perfil_admin' => 'admin@atualizar_perfil_admin',

    # Admin
    'listar_clientes' => 'admin@listar_clientes',
    'admin_produtos' => 'admin@produtos',
    'admin_produto_novo' => 'admin@produto_novo',
    'admin_produto_criar' => 'admin@produto_criar',
    'admin_produto_editar' => 'admin@produto_editar',
    'admin_produto_atualizar' => 'admin@produto_atualizar',
    'admin_produto_eliminar' => 'admin@produto_eliminar',
    'admin_categorias' => 'admin@categorias',
    'admin_categoria_nova' => 'admin@categoria_nova',
    'admin_categoria_criar' => 'admin@categoria_criar',
    'admin_categoria_editar' => 'admin@categoria_editar',
    'admin_categoria_atualizar' => 'admin@categoria_atualizar',
    'admin_categoria_eliminar' => 'admin@categoria_eliminar',
    
    # Pedidos
    'listar_pedidos' => 'admin@listar_pedidos',
    'visualizar_pedido' => 'admin@visualizar_pedido',
    'atualizar_status_pedido' => 'admin@atualizar_status_pedido',
];


$acao = 'inicio';

if (isset($_GET['a'])) {
    if (!key_exists($_GET['a'], $rotas)) {
        $acao = 'inicio';
    } else {
        $acao = $_GET['a'];
    } 
}

$partes = explode('@', $rotas[$acao]);

$controller = 'core\\controllers\\' . ucfirst($partes[0]);

$metodo = $partes[1];

$ctr = new $controller();

$ctr -> $metodo();

?>