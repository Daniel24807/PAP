<?php

namespace core\controllers;

use core\classes\Database;
use core\classes\store;
use core\models\clientes;
use core\classes\EnviarEmail;

class Main
{
    public function index()
    {
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'inicio',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    //============================ LOJA ============================

    public function loja()
    {
        // Apresenta a pagina da loja 
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'loja',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }
    //============================ NOVO CLIENTE ============================
    public function novo_cliente()
    {
        if (store::clienteLogado()) {
            $this->index();
            return;
        }

        // Apresenta a pagina de registro de novo cliente
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'novo_cliente',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    //============================ CRIAR CLIENTE ============================
    public function criar_cliente()
    {
        //*********************************************************************

        // Vamos agora verificar se o utilizador já existe
        if (Store::clienteLogado()) {
            $this->index();
            return;
        }
        // Alguém pode querer entrar de forma forçada
        // colocando endereço no browser, não seguindo a sequência
        // do programa
        // Verifica se houve submissão de um formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->index();
            return;
        }

        //*********************************************************************

        // Criação de um novo Cliente
        // 1- Verificar se a password 1 coincide com password 2
        if ($_POST['text_senha_1'] !== $_POST['text_senha_2']) {
            // AS passwords são diferentes
            $_SESSION['erro'] = 'Senhas são Diferentes!!!!';
            $this->novo_cliente();
            return;
        }
        //*********************************************************************

        // verifica se na base de dados existe cliente com o mesmo email
        $cliente = new Clientes();
        if ($cliente->verificar_email_registado($_POST['text_email'])) {
            $_SESSION['erro'] = 'Já existe um Cliente com Esse EMAIL';
            $this->novo_cliente();
            return;
        }
        //*********************************************************************

        //*********************************************************************

        // criar o link purl para enviar por email
        // link será algo tipo "http://localhost/01-LOJA/public/?a=confirmar_email@$purl";"
        // INSERIDO NOVO CLIENTE NA BD E DEVOLVER O PURL 
        $email_cliente = strtolower(trim($_POST['text_email']));
        $purl = $cliente->registrar_cliente();
        
        // Verifica se o cliente foi registrado com sucesso
        if ($purl === false) {
            $_SESSION['erro'] = 'Ocorreu um erro ao registrar o cliente. Por favor, tente novamente.';
            $this->novo_cliente();
            return;
        }
        
        //envio do email para o cliente
        $email = new EnviarEmail();

        $resultado = $email->enviar_email_confirmacao_novo_cliente($email_cliente, $purl);
        if ($resultado === true) {
            store::Layout([
                'layouts/html_header',
                'layouts/header',
                'criar_cliente_sucesso',
                'layouts/footer',
                'layouts/html_footer',
            ]);
            return;
        } else {
            // Mesmo que o email falhe, o cliente já foi registrado
            $_SESSION['erro'] = 'Cliente registrado, mas ocorreu um erro ao enviar o email de confirmação: ' . $resultado;
            store::Layout([
                'layouts/html_header',
                'layouts/header',
                'criar_cliente_sucesso',
                'layouts/footer',
                'layouts/html_footer',
            ]);
            return;
        }
    }

    //============================ CONFIRMAR EMAIL ============================

    public function confirmar_email()
    {

        //*********************************************************************
        // Verifica se o utilizador já está logado
        if (store::clienteLogado()) {
            $this->index();
            return;
        }
        //Se existe na DB string com o purl do utilizador
        if (!isset($_GET['purl'])) {
            $this->index();
            return;
        }
  
        //Verifica se o purl é valido 12 caracteres
        $purl = $_GET['purl'];

        if (strlen($_GET['purl']) != 12) {
            $this->index();
            return;
        }

        $cliente = new Clientes();
        $resultado = $cliente->validar_email($purl);


        if ($resultado) {
            store::Layout([
                'layouts/html_header',
                'layouts/header',
                'confirmar_email_sucesso',
                'layouts/footer',
                'layouts/html_footer',
            ]);
            return;
        } else {
            store::redirect();
        }
    }

    //============================ LOGIN ============================
    public function login()
    {
        //*********************************************************************
        // Verifica se o utilizador já está logado
        if (store::clienteLogado()) {
            $this->index();
            return;
        }
        //*********************************************************************
        // Apresenta a pagina de login
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'login',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    //============================ LOGIN SUBMIT ============================
    public function login_submit()
    {
        // Remover brute force e CSRF
        // Validar campos
        if (
            !isset($_POST['text_utilizador']) ||
            !isset($_POST['text_password']) || !filter_var(
                trim($_POST['text_utilizador']),
                FILTER_VALIDATE_EMAIL
            )
        ) {
            // erro de preenchimento do form
            $_SESSION['erro'] = 'Login Inválido';
            $this->login();
            return;
        }
        // Prepara os dados para o model
        $utilizador = trim(strtolower($_POST['text_utilizador']));
        $password = trim($_POST['text_password']);
        $cliente = new Clientes();
        $resultado = $cliente->validar_login($utilizador, $password);
        if (is_bool($resultado)) {
            $_SESSION['erro'] = 'Password ou Email inválidos.';
            $this->login();
            return;
        } else {
            $_SESSION['cliente'] = $resultado->id_cliente;
            $_SESSION['utilizador'] = $resultado->email;
            $_SESSION['nome_cliente'] = $resultado->nome_completo;
            $_SESSION['sucesso'] = 'Login realizado com sucesso!';
            Store::redirect();
        }
    }

    //============================ LOGOUT ============================
    public function logout()
    {
        // Remove a sessão do cliente
        unset($_SESSION['cliente']);
        
        // Redireciona para a página inicial
        $this->redirecionar('inicio');
    }

    //============================ CARRINHO ============================
    public function carrinho()
    {
        // Apresenta a página do carrinho
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'carrinho',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    //============================ CARRINHO ADICIONAR ============================
    public function carrinho_adicionar()
    {
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se os dados necessários foram enviados
        if (!isset($_POST['id_produto']) || !isset($_POST['quantidade'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos']);
            return;
        }

        $id_produto = $_POST['id_produto'];
        $quantidade = $_POST['quantidade'];

        // Validar quantidade
        if (!is_numeric($quantidade) || $quantidade < 1) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Quantidade inválida']);
            return;
        }

        // Se o cliente estiver logado, salvar no banco de dados
        if (store::clienteLogado()) {
            $id_cliente = $_SESSION['cliente'];
            
            // Instanciar o modelo de carrinho
            require_once(__DIR__ . '/../models/Carrinho.php');
            $carrinho = new \core\models\Carrinho();
            
            // Adicionar o produto ao carrinho
            $resultado = $carrinho->adicionar_produto($id_cliente, $id_produto, $quantidade);
            
            if ($resultado) {
                // Contar itens no carrinho
                $total_itens = $carrinho->contar_itens($id_cliente);
                echo json_encode(['status' => 'sucesso', 'total_itens' => $total_itens]);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao adicionar produto']);
            }
        } else {
            // Se não estiver logado, salvar na sessão
            if (!isset($_SESSION['carrinho'])) {
                $_SESSION['carrinho'] = [];
            }
            
            // Verificar se o produto já existe no carrinho
            $encontrado = false;
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['id_produto'] == $id_produto) {
                    $item['quantidade'] += $quantidade;
                    $encontrado = true;
                    break;
                }
            }
            
            // Se não encontrou, adicionar novo item
            if (!$encontrado) {
                $_SESSION['carrinho'][] = [
                    'id_produto' => $id_produto,
                    'quantidade' => $quantidade
                ];
            }
            
            // Contar itens no carrinho
            $total_itens = count($_SESSION['carrinho']);
            echo json_encode(['status' => 'sucesso', 'total_itens' => $total_itens]);
        }
    }

    //============================ CARRINHO ATUALIZAR ============================
    public function carrinho_atualizar()
    {
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se os dados necessários foram enviados
        if (!isset($_POST['id_produto']) || !isset($_POST['quantidade'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos']);
            return;
        }

        $id_produto = $_POST['id_produto'];
        $quantidade = $_POST['quantidade'];

        // Validar quantidade
        if (!is_numeric($quantidade) || $quantidade < 1) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Quantidade inválida']);
            return;
        }

        // Se o cliente estiver logado, atualizar no banco de dados
        if (store::clienteLogado()) {
            $id_cliente = $_SESSION['cliente'];
            
            // Instanciar o modelo de carrinho
            require_once(__DIR__ . '/../models/Carrinho.php');
            $carrinho = new \core\models\Carrinho();
            
            // Atualizar a quantidade do produto
            $resultado = $carrinho->atualizar_quantidade($id_cliente, $id_produto, $quantidade);
            
            if ($resultado) {
                echo json_encode(['status' => 'sucesso']);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao atualizar quantidade']);
            }
        } else {
            // Se não estiver logado, atualizar na sessão
            if (!isset($_SESSION['carrinho'])) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Carrinho vazio']);
                return;
            }
            
            // Atualizar a quantidade do produto
            foreach ($_SESSION['carrinho'] as &$item) {
                if ($item['id_produto'] == $id_produto) {
                    $item['quantidade'] = $quantidade;
                    echo json_encode(['status' => 'sucesso']);
                    return;
                }
            }
            
            echo json_encode(['status' => 'erro', 'mensagem' => 'Produto não encontrado no carrinho']);
        }
    }

    //============================ CARRINHO REMOVER ============================
    public function carrinho_remover()
    {
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se os dados necessários foram enviados
        if (!isset($_POST['id_produto'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos']);
            return;
        }

        $id_produto = $_POST['id_produto'];

        // Se o cliente estiver logado, remover do banco de dados
        if (store::clienteLogado()) {
            $id_cliente = $_SESSION['cliente'];
            
            // Instanciar o modelo de carrinho
            require_once(__DIR__ . '/../models/Carrinho.php');
            $carrinho = new \core\models\Carrinho();
            
            // Remover o produto do carrinho
            $resultado = $carrinho->remover_produto($id_cliente, $id_produto);
            
            if ($resultado) {
                // Contar itens no carrinho
                $total_itens = $carrinho->contar_itens($id_cliente);
                echo json_encode(['status' => 'sucesso', 'total_itens' => $total_itens]);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao remover produto']);
            }
        } else {
            // Se não estiver logado, remover da sessão
            if (!isset($_SESSION['carrinho'])) {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Carrinho vazio']);
                return;
            }
            
            // Encontrar e remover o produto
            foreach ($_SESSION['carrinho'] as $indice => $item) {
                if ($item['id_produto'] == $id_produto) {
                    unset($_SESSION['carrinho'][$indice]);
                    // Reindexar o array
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
                    
                    // Contar itens no carrinho
                    $total_itens = count($_SESSION['carrinho']);
                    echo json_encode(['status' => 'sucesso', 'total_itens' => $total_itens]);
                    return;
                }
            }
            
            echo json_encode(['status' => 'erro', 'mensagem' => 'Produto não encontrado no carrinho']);
        }
    }

    //============================ CARRINHO LIMPAR ============================
    public function carrinho_limpar()
    {
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Se o cliente estiver logado, limpar no banco de dados
        if (store::clienteLogado()) {
            $id_cliente = $_SESSION['cliente'];
            
            // Instanciar o modelo de carrinho
            require_once(__DIR__ . '/../models/Carrinho.php');
            $carrinho = new \core\models\Carrinho();
            
            // Limpar o carrinho
            $resultado = $carrinho->limpar_carrinho($id_cliente);
            
            if ($resultado) {
                echo json_encode(['status' => 'sucesso']);
            } else {
                echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao limpar carrinho']);
            }
        } else {
            // Se não estiver logado, limpar a sessão
            $_SESSION['carrinho'] = [];
            echo json_encode(['status' => 'sucesso']);
        }
    }

    //============================ CARRINHO SINCRONIZAR ============================
    public function carrinho_sincronizar()
    {
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Cliente não logado']);
            return;
        }

        // Verificar se existe um carrinho na sessão
        if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
            echo json_encode(['status' => 'sucesso', 'mensagem' => 'Não há itens para sincronizar']);
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        $carrinho_local = $_SESSION['carrinho'];
        
        // Instanciar o modelo de carrinho
        require_once(__DIR__ . '/../models/Carrinho.php');
        $carrinho = new \core\models\Carrinho();
        
        // Sincronizar o carrinho
        $resultado = $carrinho->sincronizar_carrinho($id_cliente, $carrinho_local);
        
        if ($resultado) {
            // Limpar o carrinho da sessão após sincronizar
            $_SESSION['carrinho'] = [];
            echo json_encode(['status' => 'sucesso']);
        } else {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao sincronizar carrinho']);
        }
    }

    //============================ CARRINHO OBTER ============================
    public function carrinho_obter()
    {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Cliente não logado']);
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        
        // Instanciar o modelo de carrinho
        require_once(__DIR__ . '/../models/Carrinho.php');
        $carrinho = new \core\models\Carrinho();
        
        // Obter os itens do carrinho
        $itens_carrinho = $carrinho->obter_carrinho($id_cliente);
        
        echo json_encode([
            'status' => 'sucesso',
            'itens' => $itens_carrinho
        ]);
    }

    //============================ CARRINHO FINALIZAR ============================
    public function carrinho_finalizar()
    {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        // Instanciar o modelo de carrinho
        require_once(__DIR__ . '/../models/Carrinho.php');
        $carrinho = new \core\models\Carrinho();
        
        // Obter os itens do carrinho
        $id_cliente = $_SESSION['cliente'];
        $itens_carrinho = $carrinho->obter_carrinho($id_cliente);
        
        if (empty($itens_carrinho)) {
            $_SESSION['erro'] = 'Seu carrinho está vazio.';
            store::redirect('carrinho');
            return;
        }
        
        // Apresenta a página de finalização do carrinho
        $dados = [
            'itens_carrinho' => $itens_carrinho
        ];
        
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'finalizar_compra',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //============================ PROCESSAR PEDIDO ============================
    public function processar_pedido()
    {
        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Cliente não logado']);
            return;
        }

        // Verificar se os dados necessários foram enviados
        if (!isset($_POST['nome']) || !isset($_POST['endereco']) || !isset($_POST['cidade']) || 
            !isset($_POST['codigo_postal']) || !isset($_POST['telefone']) || !isset($_POST['metodo_pagamento'])) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos']);
            return;
        }

        // Obter os dados do formulário
        $nome = trim($_POST['nome']);
        $endereco = trim($_POST['endereco']);
        $cidade = trim($_POST['cidade']);
        $codigo_postal = trim($_POST['codigo_postal']);
        $telefone = trim($_POST['telefone']);
        $metodo_pagamento = trim($_POST['metodo_pagamento']);

        // Validar dados
        if (empty($nome) || empty($endereco) || empty($cidade) || empty($codigo_postal) || empty($telefone)) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos']);
            return;
        }

        // Validar método de pagamento
        $metodos_validos = ['transferencia', 'cartao', 'entrega'];
        if (!in_array($metodo_pagamento, $metodos_validos)) {
            echo json_encode(['status' => 'erro', 'mensagem' => 'Método de pagamento inválido']);
            return;
        }

        // Processar o pedido
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos = new \core\models\Pedidos();
        
        $id_cliente = $_SESSION['cliente'];
        $resultado = $pedidos->criar_pedido(
            $id_cliente,
            $endereco,
            $cidade,
            $codigo_postal,
            $telefone,
            $metodo_pagamento
        );
        
        if ($resultado['status']) {
            // Limpar o carrinho local
            echo json_encode([
                'status' => 'sucesso',
                'id_pedido' => $resultado['id_pedido'],
                'codigo_pedido' => $resultado['codigo_pedido'],
                'limpar_local' => true
            ]);
        } else {
            echo json_encode([
                'status' => 'erro',
                'mensagem' => $resultado['mensagem']
            ]);
        }
    }

    //============================ PEDIDO CONFIRMADO ============================
    public function pedido_confirmado()
    {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        // Verificar se existe o ID do pedido
        if (!isset($_GET['id'])) {
            store::redirect();
            return;
        }

        $id_pedido = $_GET['id'];
        $id_cliente = $_SESSION['cliente'];

        // Obter detalhes do pedido
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos = new \core\models\Pedidos();
        
        $resultado = $pedidos->obter_pedido($id_pedido);
        
        if (!$resultado || $resultado['pedido']['id_cliente'] != $id_cliente) {
            store::redirect();
            return;
        }
        
        // Apresenta a página de confirmação do pedido
        $dados = [
            'pedido' => $resultado['pedido'],
            'itens' => $resultado['itens']
        ];
        
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'pedido_confirmado',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //============================ PEDIDO SUCESSO ============================
    public function pedido_sucesso()
    {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        // Verificar se existe o código do pedido
        $codigo_pedido = isset($_GET['codigo']) ? $_GET['codigo'] : null;
        
        // Apresenta a página de sucesso do pedido
        $dados = [
            'codigo_pedido' => $codigo_pedido
        ];
        
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'pedido_sucesso',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    public function sobre_nos() {
        // Apresenta a página sobre nós
        $dados = [
            'titulo' => 'Sobre Nós'
        ];
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'sobre_nos',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    public function recuperar_senha() {
        // Verifica se já está logado
        if (store::clienteLogado()) {
            $this->index();
            return;
        }

        // Apresenta a página de recuperação de senha
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'recuperar_senha',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    public function recuperar_senha_submit() {
        // Verifica se já está logado
        if (store::clienteLogado()) {
            $this->index();
            return;
        }

        // Verifica se houve submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->index();
            return;
        }

        // Verifica se existe o email
        if (!isset($_POST['text_email']) || empty($_POST['text_email'])) {
            $_SESSION['erro'] = 'Por favor, digite seu email.';
            $this->recuperar_senha();
            return;
        }

        $email = trim(strtolower($_POST['text_email']));

        // Verifica se o email existe na base de dados
        $cliente = new Clientes();
        if (!$cliente->verificar_email_existe($email)) {
            $_SESSION['erro'] = 'O email informado não está cadastrado.';
            $this->recuperar_senha();
            return;
        }

        // Gera token de recuperação
        $token = bin2hex(random_bytes(12));
        $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Salva o token no banco de dados
        $db = new Database();
        $params = [
            ':email' => $email,
            ':token' => $token,
            ':expira' => $expira
        ];

        $db->update("
            UPDATE clientes 
            SET reset_token = :token,
                reset_token_expira = :expira
            WHERE email = :email
        ", $params);

        // Envia o email com o link de recuperação
        $email_sender = new EnviarEmail();
        $resultado = $email_sender->enviar_email_recuperacao_senha($email, $token);

        if ($resultado) {
            $_SESSION['sucesso'] = 'As instruções de recuperação foram enviadas para seu email.';
        } else {
            $_SESSION['erro'] = 'Não foi possível enviar o email de recuperação. Tente novamente.';
        }
        
        $this->recuperar_senha();
    }

    public function redefinir_senha() {
        // Verifica se o usuário já está logado
        if (store::clienteLogado()) {
            $this->index();
            return;
        }

        // Verifica se existe o token
        if (!isset($_GET['token'])) {
            $_SESSION['erro'] = 'Token inválido ou expirado.';
            $this->index();
            return;
        }

        $token = $_GET['token'];

        // Verifica se o token é válido e não expirou
        $cliente = new Clientes();
        if (!$cliente->verificar_token_valido($token)) {
            $_SESSION['erro'] = 'Token inválido ou expirado.';
            $this->index();
            return;
        }

        // Mostra a página de redefinição de senha
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'redefinir_senha',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    public function redefinir_senha_submit() {
        // Verifica se o usuário já está logado
        if (store::clienteLogado()) {
            $this->index();
            return;
        }

        // Verifica se houve submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->index();
            return;
        }

        // Verifica se existe o token
        if (!isset($_POST['token'])) {
            $_SESSION['erro'] = 'Token inválido ou expirado.';
            $this->index();
            return;
        }

        // Verifica se as senhas foram fornecidas
        if (!isset($_POST['text_nova_senha']) || !isset($_POST['text_confirmar_senha'])) {
            $_SESSION['erro'] = 'Por favor, preencha todos os campos.';
            $this->redefinir_senha();
            return;
        }

        // Verifica se as senhas coincidem
        if ($_POST['text_nova_senha'] !== $_POST['text_confirmar_senha']) {
            $_SESSION['erro'] = 'As senhas não coincidem.';
            $this->redefinir_senha();
            return;
        }

        // Verifica se a senha tem pelo menos 6 caracteres
        if (strlen($_POST['text_nova_senha']) < 6) {
            $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres.';
            $this->redefinir_senha();
            return;
        }

        // Atualiza a senha
        $cliente = new Clientes();
        $resultado = $cliente->atualizar_senha_com_token($_POST['token'], $_POST['text_nova_senha']);

        if ($resultado) {
            $_SESSION['sucesso'] = 'Senha redefinida com sucesso. Faça login com sua nova senha.';
            $this->login();
        } else {
            $_SESSION['erro'] = 'Não foi possível redefinir a senha. Tente novamente.';
            $this->redefinir_senha();
        }
    }

    //============================ MINHA CONTA ============================
    public function minha_conta() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        
        // Obter dados do cliente
        $cliente = new Clientes();
        $dados_cliente = $cliente->buscar_dados_cliente($id_cliente);
        
        if (!$dados_cliente) {
            store::redirect();
            return;
        }
        
        // Apresenta a página de minha conta
        $dados = [
            'cliente' => $dados_cliente
        ];
        
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'minha_conta',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //============================ ALTERAR SENHA ============================
    public function alterar_senha() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }
        
        // Apresenta a página de alteração de senha
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'alterar_senha',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    //============================ ALTERAR SENHA SUBMIT ============================
    public function alterar_senha_submit() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se os dados necessários foram enviados
        if (!isset($_POST['senha_atual']) || !isset($_POST['nova_senha']) || !isset($_POST['confirmar_senha'])) {
            $_SESSION['erro'] = 'Preencha todos os campos.';
            $this->alterar_senha();
            return;
        }

        // Verificar se as senhas novas coincidem
        if ($_POST['nova_senha'] !== $_POST['confirmar_senha']) {
            $_SESSION['erro'] = 'As senhas não coincidem.';
            $this->alterar_senha();
            return;
        }

        // Verificar se a senha tem pelo menos 6 caracteres
        if (strlen($_POST['nova_senha']) < 6) {
            $_SESSION['erro'] = 'A senha deve ter no mínimo 6 caracteres.';
            $this->alterar_senha();
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        
        // Atualizar a senha
        $cliente = new Clientes();
        $resultado = $cliente->alterar_senha($id_cliente, $senha_atual, $nova_senha);
        
        if ($resultado) {
            $_SESSION['sucesso'] = 'Senha alterada com sucesso! Sua conta está agora mais segura.';
            $_SESSION['show_sweet_alert'] = true;
        } else {
            $_SESSION['erro'] = 'Não foi possível alterar a senha. Verifique se a senha atual está correta.';
        }
        
        $this->alterar_senha();
    }

    //============================ ALTERAR MORADA ============================
    public function alterar_morada() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        
        // Obter dados do cliente
        $cliente = new Clientes();
        $dados_cliente = $cliente->buscar_dados_cliente($id_cliente);
        
        if (!$dados_cliente) {
            store::redirect();
            return;
        }
        
        // Apresenta a página de alteração de morada
        $dados = [
            'cliente' => $dados_cliente
        ];
        
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'alterar_morada',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //============================ ALTERAR MORADA SUBMIT ============================
    public function alterar_morada_submit() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        // Verificar se é uma requisição POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            store::redirect();
            return;
        }

        // Verificar se os dados necessários foram enviados
        if (!isset($_POST['morada']) || !isset($_POST['cidade'])) {
            $_SESSION['erro'] = 'Preencha todos os campos.';
            $this->alterar_morada();
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        $morada = trim($_POST['morada']);
        $cidade = trim($_POST['cidade']);
        
        // Validar dados
        if (empty($morada) || empty($cidade)) {
            $_SESSION['erro'] = 'Preencha todos os campos.';
            $this->alterar_morada();
            return;
        }
        
        // Atualizar a morada
        $cliente = new Clientes();
        $resultado = $cliente->alterar_morada($id_cliente, $morada, $cidade);
        
        if ($resultado) {
            $_SESSION['sucesso'] = 'Morada atualizada com sucesso! Seus dados foram salvos.';
            $_SESSION['show_sweet_alert'] = true;
        } else {
            $_SESSION['erro'] = 'Não foi possível alterar a morada.';
        }
        
        $this->alterar_morada();
    }

    //============================ MEUS PEDIDOS ============================
    public function meus_pedidos() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        $id_cliente = $_SESSION['cliente'];
        
        // Obter pedidos do cliente
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos = new \core\models\Pedidos();
        $lista_pedidos = $pedidos->listar_pedidos_cliente($id_cliente);
        
        // Apresenta a página de meus pedidos
        $dados = [
            'pedidos' => $lista_pedidos
        ];
        
        store::Layout([
            'layouts/html_header',
            'layouts/header',
            'meus_pedidos',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    //============================ CANCELAR PEDIDO ============================
    public function cancelar_pedido() {
        // Verificar se o cliente está logado
        if (!store::clienteLogado()) {
            store::redirect('login');
            return;
        }

        // Verificar se existe o ID do pedido
        if (!isset($_GET['id'])) {
            $_SESSION['erro'] = 'Pedido não encontrado.';
            store::redirect('meus_pedidos');
            return;
        }

        $id_pedido = $_GET['id'];
        $id_cliente = $_SESSION['cliente'];
        
        // Instanciar o modelo de pedidos
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos = new \core\models\Pedidos();
        
        // Verificar se o pedido pertence ao cliente diretamente no banco de dados
        $db = new \core\classes\Database();
        $params = [
            ':id_pedido' => $id_pedido,
            ':id_cliente' => $id_cliente
        ];
        
        $resultado = $db->select(
            "SELECT id_pedido FROM pedidos WHERE id_pedido = :id_pedido AND id_cliente = :id_cliente", 
            $params
        );
        
        // Verifica se o pedido existe e pertence ao cliente atual
        if (empty($resultado)) {
            $_SESSION['erro'] = 'Pedido não encontrado ou não pertence ao cliente.';
            store::redirect('meus_pedidos');
            return;
        }
        
        // Cancelar o pedido
        $resultado = $pedidos->atualizar_status_pedido($id_pedido, 'cancelado');
        
        if ($resultado) {
            $_SESSION['sucesso'] = 'Pedido cancelado com sucesso.';
        } else {
            $_SESSION['erro'] = 'Não foi possível cancelar o pedido.';
        }
        
        store::redirect('meus_pedidos');
    }
}
