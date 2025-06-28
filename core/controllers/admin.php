<?php

namespace core\controllers;

use core\classes\Database;
use core\classes\EnviarEmail;
use core\classes\Store;
use core\models\AdminModel;
use core\models\Clientes;
use core\models\Produtos;
use core\models\Categorias;
use Exception;

class Admin
{
    // Utilizador: admin@admin.com
    // Senha: 123456
    public function index()
    {
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // Carregar o layout do Backoffice com a página de clientes
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'inicio', // A view que será carregada
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    // ******************* Método corrigido: listar_clientes() *******************
    public function listar_clientes()
    {
        // Criar instância do modelo Clientes
        $clientesModel = new Clientes();

        // Obter a lista de clientes do banco de dados
        $clientes = $clientesModel->lista_clientes();

        // Enviar os dados para a View
        $data = [
            'clientes' => $clientes
        ];

        // Carregar o layout do Backoffice
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'listar_clientes', // Arquivo de view separado
            'layouts/footer',
            'layouts/html_footer',
        ], $data);
    }

    // ******************* Método admin_login() ****************************
    public function admin_login()
    {
        // Verifica se já existe sessão admin aberta
        if (Store::adminLogado()) {
            Store::redirect('inicio', true);
            return;
        }

        // Apresenta backoffice com o quadro de login
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'login_frm',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    public function admin_logout()
    {
        // Destroi a sessão do administrador e redireciona para a página de login
        session_start();
        session_destroy();
        Store::redirect('admin_login', true);
    }
    // ******************* Método admin_login_submit() ****************************
    public function admin_login_submit()
    {
        // Verifica se já existe sessão
        if (Store::adminLogado()) {
            Store::redirect('inicio', true);
            return;
        }

        // Verifica se é um POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('admin_login', true);
            return;
        }

        // Verifica se os campos existem
        if (!isset($_POST['text_utilizador']) || !isset($_POST['text_password'])) {
            $_SESSION['login_erro'] = 'Todos os campos são obrigatórios';
            Store::redirect('admin_login', true);
            return;
        }

        // Limpa os dados
        $email = trim(strtolower($_POST['text_utilizador']));
        $senha = trim($_POST['text_password']);

        // Valida o email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['login_erro'] = 'Email inválido';
            Store::redirect('admin_login', true);
            return;
        }

        // Valida a senha
        if (strlen($senha) < 6) {
            $_SESSION['login_erro'] = 'A senha deve ter no mínimo 6 caracteres';
            Store::redirect('admin_login', true);
            return;
        }

        // Tenta fazer login
        $admin_model = new AdminModel();
        $resultado = $admin_model->validar_login($email, $senha);

        if (!$resultado) {
            $_SESSION['login_erro'] = 'Email ou senha incorretos';
            Store::redirect('admin_login', true);
            return;
        }

        // Login bem sucedido
        $_SESSION['admin'] = $resultado->id_admin;
        $_SESSION['admin_utilizador'] = $resultado->utilizador;
        Store::redirect('inicio', true);
    }

    // ******************* Método cliente_apagar_hard() ****************************
    public function cliente_apagar_Hard()
    {

        //
        //primeiro fazer validações
        if (!isset($_GET['id'])) {
            Store::redirect('listar_clientes', true);
            //Sai
        }

        //agora pegamos no id
        //e executamos CRUD, a parte do Delete

        $id = $_GET['id'];

        //Instanciar o nosso model
        $cliente = new Clientes();
        $results = $cliente->cliente_apagar_hard($id);
        Store::redirect('listar_clientes', true);
        return;
    }

    // ******************* Método cliente_apagar_hard_confurn() ****************************
    public function cliente_apagar_Hard_confirm()
    {
        //recebe o id do cliente
        //Instancia o modelclientes
        //no model cria um metodo para trazer todos os dados do cliente   
        //Apresentar a view de confirmacao com os dados do cliente

        //primeiro fazer validações
        if (!isset($_GET['id'])) {
            Store::redirect('listar_clientes', true);
            //Sai
        }


        $id = $_GET["id"];


        $Cliente = new Clientes();
        $results = $Cliente->Cliente_pesquisar_id($id);

        $data = [
            'cliente' => $results
        ];

        // store::printData($data);

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'delete_Hard_Confirm',
            'layouts/footer',
            'layouts/html_footer',
        ], $data);
    }

    public function perfil_admin()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // Buscar dados do admin logado
        $admin_model = new AdminModel();
        $admin = $admin_model->buscar_admin_por_id($_SESSION['admin']);

        // Preparar dados para a view
        $dados = [
            'admin' => $admin
        ];

        // Apresentar a view
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'perfil_admin',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    // Função para detectar se é uma requisição AJAX
    private function isAjaxRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
               isset($_GET['ajax']);
    }

    // Função para enviar resposta em formato JSON para requisições AJAX
    private function sendJsonResponse($success, $message, $data = null) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    public function atualizar_perfil_admin() {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            // Verificar se é uma requisição AJAX
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Sessão expirada. Faça login novamente.');
            } else {
                Store::redirect('admin_login', true);
            }
            return;
        }
        
        $tipo = $_GET['tipo'] ?? '';
        
        // Atualizar senha
        if ($tipo == 'senha') {
            // Buscar dados
            $senha_atual = trim($_POST['text_senha_atual']);
            $nova_senha = trim($_POST['text_nova_senha']);
            $conf_nova_senha = trim($_POST['text_confirmar_senha']);
            
            // Verificar se a senha atual está correta
            $admin = new AdminModel();
            if (!$admin->validar_senha($_SESSION['admin'], $senha_atual)) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, 'A senha atual está incorreta.');
                } else {
                    $_SESSION['erro'] = 'A senha atual está incorreta.';
                    Store::redirect('perfil_admin&tab=senha', true);
                }
                return;
            }
            
            // Verificar se as senhas novas coincidem
            if ($nova_senha != $conf_nova_senha) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, 'As senhas não coincidem.');
                } else {
                    $_SESSION['erro'] = 'As senhas não coincidem.';
                    Store::redirect('perfil_admin&tab=senha', true);
                }
                return;
            }
            
            // Atualizar senha
            $admin->atualizar_senha($_SESSION['admin'], $nova_senha);
            
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(true, 'Senha alterada com sucesso.');
            } else {
                $_SESSION['sucesso'] = 'Senha alterada com sucesso.';
                Store::redirect('perfil_admin', true);
            }
        }
        
        // Atualizar email
        else if ($tipo == 'email') {
            // Buscar dados
            $senha_atual = trim($_POST['text_senha_atual']);
            $novo_email = trim($_POST['text_utilizador']);
            
            // Verificar se o novo email é válido
            if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, 'O email informado não é válido.');
                } else {
                    $_SESSION['erro'] = 'O email informado não é válido.';
                    Store::redirect('perfil_admin&tab=email', true);
                }
                return;
            }
            
            // Verificar se a senha está correta
            $admin = new AdminModel();
            if (!$admin->validar_senha($_SESSION['admin'], $senha_atual)) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, 'A senha atual está incorreta.');
                } else {
                    $_SESSION['erro'] = 'A senha atual está incorreta.';
                    Store::redirect('perfil_admin&tab=email', true);
                }
                return;
            }
            
            // Atualizar email
            $admin->atualizar_email($_SESSION['admin'], $novo_email);
            
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(true, 'Email alterado com sucesso.');
            } else {
                $_SESSION['sucesso'] = 'Email alterado com sucesso.';
                Store::redirect('perfil_admin', true);
            }
        }
        
        // Tipo inválido
        else {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Tipo de atualização inválido.');
            } else {
                $_SESSION['erro'] = 'Tipo de atualização inválido.';
                Store::redirect('perfil_admin', true);
            }
        }
    }

    // ******************* Método novo_cliente() ****************************
    public function novo_cliente()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // Apresentar o formulário para adicionar novo cliente
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'novo_cliente',
            'layouts/footer',
            'layouts/html_footer',
        ]);
    }

    // ******************* Método cliente_adicionar_submit() ****************************
    public function cliente_adicionar_submit()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // Verificar se foi feito um pedido POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('novo_cliente', true);
            return;
        }

        // Verificar se senha e confirmação de senha são iguais
        if ($_POST['senha'] !== $_POST['confirmar_senha']) {
            $_SESSION['erro'] = 'As senhas não coincidem!';
            Store::redirect('novo_cliente', true);
            return;
        }

        // Verificar se o email já existe na base de dados
        $cliente = new Clientes();
        if ($cliente->verificar_email_existe($_POST['email'])) {
            $_SESSION['erro'] = 'O email já está registrado!';
            Store::redirect('novo_cliente', true);
            return;
        }

        // Dados do novo cliente
        $dados = [
            'nome_completo' => $_POST['nome_completo'],
            'email' => $_POST['email'],
            'senha' => password_hash($_POST['senha'], PASSWORD_DEFAULT),
            'morada' => $_POST['morada'],
            'cidade' => $_POST['cidade'],
            'telefone' => $_POST['telefone'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Adicionar novo cliente à base de dados
        $resultado = $cliente->adicionar_cliente($dados);
        
        if ($resultado) {
            $_SESSION['sucesso'] = 'Cliente adicionado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao adicionar cliente!';
        }
        
        Store::redirect('clientes', true);
    }

    // ******************* Método cliente_editar() ****************************
    public function cliente_editar()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // Verificar se foi fornecido um ID válido
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            Store::redirect('clientes', true);
            return;
        }

        // Buscar dados do cliente
        $cliente_model = new Clientes();
        $cliente = $cliente_model->buscar_cliente($_GET['id']);

        if (!$cliente) {
            Store::redirect('clientes', true);
            return;
        }

        // Preparar dados para a view
        $dados = [
            'cliente' => $cliente
        ];

        // Apresentar a view
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'cliente_editar',
            'layouts/footer',
            'layouts/html_footer',
        ], $dados);
    }

    // ******************* Método cliente_editar_submit() ****************************
    public function cliente_editar_submit()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }

        // Verificar se foi feito um pedido POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('clientes', true);
            return;
        }

        // Verificar se foi fornecido um ID válido
        if (!isset($_POST['id_cliente']) || !is_numeric($_POST['id_cliente'])) {
            $_SESSION['erro'] = 'ID de cliente inválido!';
            Store::redirect('clientes', true);
            return;
        }

        // Buscar cliente atual
        $clientes = new Clientes();
        $cliente_atual = $clientes->buscar_cliente($_POST['id_cliente']);

        if (!$cliente_atual) {
            $_SESSION['erro'] = 'Cliente não encontrado!';
            Store::redirect('clientes', true);
            return;
        }

        // Verificar se o email foi alterado e já existe na base de dados
        if ($_POST['email'] != $cliente_atual->email) {
            if ($clientes->verificar_email_existe($_POST['email'])) {
                $_SESSION['erro'] = 'O email já está registrado por outro cliente!';
                Store::redirect('cliente_editar&id=' . $_POST['id_cliente'], true);
                return;
            }
        }

        // Verificar se senhas coincidem quando a opção de alterar senha está marcada
        if (isset($_POST['alterar_senha']) && $_POST['alterar_senha'] == 'on') {
            if ($_POST['senha'] !== $_POST['confirmar_senha']) {
                $_SESSION['erro'] = 'As senhas não coincidem!';
                Store::redirect('cliente_editar&id=' . $_POST['id_cliente'], true);
                return;
            }
        }

        // Dados para atualização
        $dados = [
            'id_cliente' => $_POST['id_cliente'],
            'nome_completo' => $_POST['nome_completo'],
            'email' => $_POST['email'],
            'morada' => $_POST['morada'],
            'cidade' => $_POST['cidade'],
            'telefone' => $_POST['telefone'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        // Adicionar senha se foi solicitada alteração
        if (isset($_POST['alterar_senha']) && $_POST['alterar_senha'] == 'on') {
            $dados['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        }

        // Atualizar cliente na base de dados
        $resultado = $clientes->atualizar_cliente_admin($dados);
        
        if ($resultado) {
            $_SESSION['sucesso'] = 'Cliente atualizado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar cliente!';
        }
        
        Store::redirect('clientes', true);
    }

    // ========== PRODUTOS ==========
    public function produtos() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        $produtos = new Produtos();
        $lista_produtos = $produtos->listar_produtos();

        $dados = [
            'produtos' => $lista_produtos
        ];

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'produtos',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    public function produto_novo() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        $categorias = new Categorias();
        $lista_categorias = $categorias->listar_categorias();

        $dados = [
            'categorias' => $lista_categorias
        ];

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'produto_form',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    public function produto_criar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se houve submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('admin_produtos');
            return;
        }

        // Tratar upload da imagem
        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $imagem = $this->tratar_imagem($_FILES['imagem']);
        }

        // Prepara os dados para criar o produto
        $dados = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'],
            'preco' => $_POST['preco'],
            'stock' => $_POST['stock'],
            'id_categoria' => $_POST['id_categoria']
        ];

        if ($imagem) {
            $dados['imagem'] = $imagem;
        }

        // Criar o produto
        $produtos = new Produtos();
        $produtos->criar_produto($dados);

        Store::redirect('admin_produtos');
    }

    public function produto_editar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se existe ID
        if (!isset($_GET['id'])) {
            Store::redirect('admin_produtos');
            return;
        }

        $id_produto = $_GET['id'];

        $produtos = new Produtos();
        $categorias = new Categorias();

        $produto = $produtos->get_produto($id_produto);
        $lista_categorias = $categorias->listar_categorias();

        if (!$produto) {
            Store::redirect('admin_produtos');
            return;
        }

        $dados = [
            'produto' => $produto,
            'categorias' => $lista_categorias
        ];

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'produto_form',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    public function produto_atualizar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se houve submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('admin_produtos');
            return;
        }

        // Tratar upload da imagem
        $imagem = null;
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
            $imagem = $this->tratar_imagem($_FILES['imagem']);
            
            // Se houver uma imagem antiga, exclui ela
            $produtos = new Produtos();
            $produto_atual = $produtos->get_produto($_POST['id_produto']);
            if ($produto_atual && $produto_atual->imagem) {
                $caminho_antigo = 'assets/images/produtos/' . $produto_atual->imagem;
                if (file_exists($caminho_antigo)) {
                    unlink($caminho_antigo);
                }
            }
        }

        // Prepara os dados para atualizar o produto
        $dados = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'],
            'preco' => $_POST['preco'],
            'stock' => $_POST['stock'],
            'id_categoria' => $_POST['id_categoria']
        ];

        if ($imagem) {
            $dados['imagem'] = $imagem;
        }

        // Atualizar o produto
        $produtos = new Produtos();
        $produtos->atualizar_produto($_POST['id_produto'], $dados);

        Store::redirect('admin_produtos');
    }

    public function produto_eliminar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se existe ID
        if (!isset($_GET['id'])) {
            Store::redirect('admin_produtos');
            return;
        }

        // Busca o produto para pegar o nome da imagem
        $produtos = new Produtos();
        $produto = $produtos->get_produto($_GET['id']);

        // Se o produto tiver imagem, exclui ela
        if ($produto && $produto->imagem) {
            $caminho = 'assets/images/produtos/' . $produto->imagem;
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }

        // Elimina o produto
        $produtos->eliminar_produto($_GET['id']);

        Store::redirect('admin_produtos');
    }

    // ========== CATEGORIAS ==========
    public function categorias() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        $categorias = new Categorias();
        $lista_categorias = $categorias->listar_categorias();

        $dados = [
            'categorias' => $lista_categorias
        ];

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'categorias',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    public function categoria_nova() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'categoria_form',
            'layouts/footer',
            'layouts/html_footer'
        ]);
    }

    public function categoria_criar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se houve submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('admin_categorias');
            return;
        }

        // Criar a categoria
        $categorias = new Categorias();
        $categorias->criar_categoria($_POST);

        Store::redirect('admin_categorias');
    }

    public function categoria_editar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se existe ID
        if (!isset($_GET['id'])) {
            Store::redirect('admin_categorias');
            return;
        }

        $categorias = new Categorias();
        $categoria = $categorias->get_categoria($_GET['id']);

        if (!$categoria) {
            Store::redirect('admin_categorias');
            return;
        }

        $dados = [
            'categoria' => $categoria
        ];

        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'categoria_form',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    public function categoria_atualizar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se houve submissão de formulário
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            Store::redirect('admin_categorias');
            return;
        }

        // Atualizar a categoria
        $categorias = new Categorias();
        $categorias->atualizar_categoria($_POST['id_categoria'], $_POST);

        Store::redirect('admin_categorias');
    }

    public function categoria_eliminar() {
        // Verifica se é admin
        if (!Store::adminLogado()) {
            Store::redirect('inicio');
            return;
        }

        // Verifica se existe ID
        if (!isset($_GET['id'])) {
            Store::redirect('admin_categorias');
            return;
        }

        $categorias = new Categorias();
        
        // Tenta eliminar a categoria
        if (!$categorias->eliminar_categoria($_GET['id'])) {
            $_SESSION['erro'] = 'Não é possível eliminar uma categoria que possui produtos.';
        }

        Store::redirect('admin_categorias');
    }

    // ========== FUNÇÕES AUXILIARES ==========
    private function tratar_imagem($file) {
        // Verifica se é uma imagem válida
        $tipo = $file['type'];
        if (!in_array($tipo, ['image/jpeg', 'image/png', 'image/gif'])) {
            return null;
        }

        // Gera um nome único para o arquivo
        $extensao = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nome_arquivo = md5(uniqid(rand(), true)) . '.' . $extensao;

        // Define o caminho onde a imagem será salva
        $caminho = 'assets/images/produtos/' . $nome_arquivo;

        // Move o arquivo para o destino
        if (move_uploaded_file($file['tmp_name'], $caminho)) {
            return $nome_arquivo;
        }

        return null;
    }

    public function get_imagem_produto() {
        // Verifica se existe ID
        if (!isset($_GET['id'])) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $produtos = new Produtos();
        $imagem = $produtos->get_imagem_produto($_GET['id']);

        if (!$imagem || !$imagem->imagem) {
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        // Define o tipo de conteúdo
        header('Content-Type: ' . $imagem->imagem_tipo);
        
        // Envia a imagem
        echo $imagem->imagem;
        exit;
    }

    /**
     * Listar todos os pedidos
     */
    public function listar_pedidos()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }
        
        // Carregar modelo de pedidos
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos_model = new \core\models\Pedidos();
        
        // Verificar se existe uma pesquisa
        $pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';
        
        // Obter pedidos (todos ou filtrados por nome)
        if (!empty($pesquisa)) {
            $pedidos = $pedidos_model->buscar_pedidos_por_nome($pesquisa);
        } else {
            $pedidos = $pedidos_model->listar_todos_pedidos();
        }
        
        // Enviar dados para a view
        $data = [
            'pedidos' => $pedidos,
            'pesquisa' => $pesquisa
        ];
        
        // Carregar o layout do Backoffice
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'listar_pedidos', // View para listar pedidos
            'layouts/footer',
            'layouts/html_footer',
        ], $data);
    }

    /**
     * Visualizar detalhes de um pedido
     */
    public function visualizar_pedido()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }
        
        // Verificar se existe o ID do pedido
        if (!isset($_GET['id'])) {
            Store::redirect('listar_pedidos', true);
            return;
        }
        
        $id_pedido = $_GET['id'];
        
        // Carregar modelo de pedidos
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos_model = new \core\models\Pedidos();
        
        // Obter detalhes do pedido
        $resultado = $pedidos_model->obter_pedido($id_pedido);
        
        if (!$resultado) {
            $_SESSION['erro'] = 'Pedido não encontrado';
            Store::redirect('listar_pedidos', true);
            return;
        }
        
        // Enviar dados para a view
        $data = [
            'pedido' => $resultado['pedido'],
            'itens' => $resultado['itens']
        ];
        
        // Carregar o layout do Backoffice
        Store::Layout_Admin([
            'layouts/html_header',
            'layouts/header',
            'visualizar_pedido', // View para visualizar detalhes do pedido
            'layouts/footer',
            'layouts/html_footer',
        ], $data);
    }

    /**
     * Atualizar status de um pedido
     */
    public function atualizar_status_pedido()
    {
        // Verificar se o admin está logado
        if (!Store::adminLogado()) {
            Store::redirect('admin_login', true);
            return;
        }
        
        // Verificar se é uma requisição AJAX
        if (!$this->isAjaxRequest()) {
            Store::redirect('listar_pedidos', true);
            return;
        }
        
        // Verificar se os dados necessários foram enviados
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['id_pedido']) || !isset($data['status'])) {
            $this->sendJsonResponse(false, 'Dados incompletos');
            return;
        }
        
        $id_pedido = $data['id_pedido'];
        $status = $data['status'];
        
        // Validar status
        $status_validos = ['pendente', 'processando', 'enviado', 'entregue', 'cancelado'];
        if (!in_array($status, $status_validos)) {
            $this->sendJsonResponse(false, 'Status inválido');
            return;
        }
        
        // Carregar modelo de pedidos
        require_once(__DIR__ . '/../models/Pedidos.php');
        $pedidos_model = new \core\models\Pedidos();
        
        // Atualizar status do pedido
        try {
            $pedidos_model->atualizar_status_pedido($id_pedido, $status);
            $this->sendJsonResponse(true, 'Status atualizado com sucesso');
        } catch (Exception $e) {
            $this->sendJsonResponse(false, 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }

    /**
     * Método auxiliar para obter a cor do status do pedido
     */
    public function getStatusColor($status) {
        switch ($status) {
            case 'pendente':
                return 'warning';
            case 'processando':
                return 'info';
            case 'enviado':
                return 'primary';
            case 'entregue':
                return 'success';
            case 'cancelado':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
