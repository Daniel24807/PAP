<?php
use core\classes\store;

if (!Store::adminLogado()) {
    Store::redirect('admin_login', true);
    return;
}
?>

<div class="card">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-box me-2"></i>
            <?= isset($produto) ? 'Editar Produto' : 'Novo Produto' ?>
        </h5>
    </div>
    <div class="card-body">
            <form action="?a=<?= isset($produto) ? 'admin_produto_atualizar' : 'admin_produto_criar' ?>" method="post" enctype="multipart/form-data">
                <?php if(isset($produto)): ?>
                    <input type="hidden" name="id_produto" value="<?= $produto->id_produto ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" required
                                   value="<?= isset($produto) ? $produto->nome : '' ?>">
                        </div>

                        <!-- Categoria -->
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <select class="form-select" id="categoria" name="id_categoria">
                                <option value="">Selecione uma categoria</option>
                                <?php foreach($categorias as $categoria): ?>
                                    <option value="<?= $categoria->id_categoria ?>" 
                                            <?= (isset($produto) && $produto->id_categoria == $categoria->id_categoria) ? 'selected' : '' ?>>
                                        <?= $categoria->nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Preço -->
                        <div class="mb-3">
                            <label for="preco" class="form-label">Preço</label>
                            <input type="number" class="form-control" id="preco" name="preco" step="0.01" required
                                   value="<?= isset($produto) ? $produto->preco : '' ?>">
                        </div>

                        <!-- Stock -->
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" required
                                   value="<?= isset($produto) ? $produto->stock : '' ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= isset($produto) ? $produto->descricao : '' ?></textarea>
                        </div>

                        <!-- Imagem -->
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem</label>
                            <?php if(isset($produto) && !empty($produto->imagem)): ?>
                                <div class="mb-2">
                                    <img src="assets/images/produtos/<?= $produto->imagem ?>" 
                                         alt="<?= $produto->nome ?>" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                        <?= isset($produto) ? 'Atualizar' : 'Criar' ?> Produto
                    </button>
                <a href="?a=admin_produtos" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar
                </a>
                </div>
            </form>
        </div>
    </div>

<?php if (isset($_SESSION['erro'])) : ?>
    <script>
        mostrarErro('<?= $_SESSION['erro'] ?>');
    </script>
    <?php unset($_SESSION['erro']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['sucesso'])) : ?>
    <script>
        mostrarSucesso('<?= $_SESSION['sucesso'] ?>');
    </script>
    <?php unset($_SESSION['sucesso']); ?>
<?php endif; ?> 