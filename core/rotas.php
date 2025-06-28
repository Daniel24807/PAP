<?php

// Carrega as configurações se ainda não foram carregadas
if (!defined('APP_NAME')) {
    require_once '../config.php';
}

$rotas = [
    'inicio' => 'main@index',
    
    # Loja
    'loja' => 'loja@index',
    'categoria' => 'loja@categoria',
    'produto' => 'loja@produto',

    # Cliente
    'novo_cliente' => 'main@novo_cliente',
    'criar_cliente' => 'main@criar_cliente',
    'confirmar_email' => 'main@confirmar_email',
    'confirmar_email_sucesso' => 'main@confirmar_email_sucesso',

    # Login
    'login' => 'main@login',
    'login_submit' => 'main@login_submit',
    'logout' => 'main@logout',

    # Carrinho
    'carrinho' => 'main@carrinho',
    'carrinho_adicionar' => 'main@carrinho_adicionar',
    'carrinho_atualizar' => 'main@carrinho_atualizar',
    'carrinho_remover' => 'main@carrinho_remover',
    'carrinho_limpar' => 'main@carrinho_limpar',
    'carrinho_sincronizar' => 'main@carrinho_sincronizar',
    'carrinho_finalizar' => 'main@carrinho_finalizar',
    'carrinho_obter' => 'main@carrinho_obter',
    'processar_pedido' => 'main@processar_pedido',
    'pedido_confirmado' => 'main@pedido_confirmado',
    'pedido_sucesso' => 'main@pedido_sucesso',

    # Conta do cliente
    'minha_conta' => 'main@minha_conta',
    'alterar_senha' => 'main@alterar_senha',
    'alterar_senha_submit' => 'main@alterar_senha_submit',
    'alterar_morada' => 'main@alterar_morada',
    'alterar_morada_submit' => 'main@alterar_morada_submit',
    'meus_pedidos' => 'main@meus_pedidos',

    'sobre_nos' => 'main@sobre_nos',

    // Rota para cancelar pedido
    'cancelar_pedido' => 'main@cancelar_pedido',
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

$ctr->$metodo();

?>
