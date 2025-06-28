<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1><?= isset($categoria) ? 'Editar Categoria' : 'Nova Categoria' ?></h1>
            
            <form action="?a=<?= isset($categoria) ? 'admin_categoria_atualizar' : 'admin_categoria_criar' ?>" method="post">
                <?php if(isset($categoria)): ?>
                    <input type="hidden" name="id_categoria" value="<?= $categoria->id_categoria ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Categoria</label>
                            <input type="text" class="form-control" id="nome" name="nome" required
                                   value="<?= isset($categoria) ? $categoria->nome : '' ?>">
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= isset($categoria) ? $categoria->descricao : '' ?></textarea>
                        </div>

                        <!-- Botões -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <?= isset($categoria) ? 'Atualizar' : 'Criar' ?> Categoria
                            </button>
                            <a href="?a=admin_categorias" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 