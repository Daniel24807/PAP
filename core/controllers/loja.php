<?php

namespace core\controllers;

use core\classes\store;
use core\models\Produtos;
use core\models\Categorias;

class Loja
{
    // A classe Loja pode ser usada para implementar funcionalidades específicas da loja

    // Método para exibir a página principal da loja com todos os produtos
    public function index()
    {
        // Buscar todos os produtos
        $produtos_model = new Produtos();
        $produtos = $produtos_model->listar_produtos();

        // Buscar todas as categorias
        $categorias_model = new Categorias();
        $categorias = $categorias_model->listar_categorias();

        // Preparar dados para a view
        $dados = [
            'produtos' => $produtos,
            'categorias' => $categorias
        ];

        // Carregar a view
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'loja',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    // Método para exibir produtos por categoria
    public function categoria()
    {
        // Verificar se foi fornecido um ID de categoria
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            Store::redirect();
            return;
        }

        $id_categoria = $_GET['id'];

        // Buscar produtos da categoria
        $produtos_model = new Produtos();
        $produtos = $produtos_model->produtos_por_categoria($id_categoria);

        // Buscar todas as categorias
        $categorias_model = new Categorias();
        $categorias = $categorias_model->listar_categorias();
        $categoria_atual = $categorias_model->get_categoria($id_categoria);

        if (!$categoria_atual) {
            Store::redirect();
            return;
        }

        // Preparar dados para a view
        $dados = [
            'produtos' => $produtos,
            'categorias' => $categorias,
            'categoria_atual' => $categoria_atual
        ];

        // Carregar a view
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'loja',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }

    // Método para exibir detalhes de um produto
    public function produto()
    {
        // Verificar se foi fornecido um ID de produto
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            Store::redirect();
            return;
        }

        $id_produto = $_GET['id'];

        // Buscar detalhes do produto
        $produtos_model = new Produtos();
        $produto = $produtos_model->get_produto($id_produto);

        if (!$produto) {
            Store::redirect();
            return;
        }

        // Buscar todas as categorias para o menu
        $categorias_model = new Categorias();
        $categorias = $categorias_model->listar_categorias();

        // Preparar dados para a view
        $dados = [
            'produto' => $produto,
            'categorias' => $categorias
        ];

        // Carregar a view
        Store::Layout([
            'layouts/html_header',
            'layouts/header',
            'produto_detalhe',
            'layouts/footer',
            'layouts/html_footer'
        ], $dados);
    }
}
