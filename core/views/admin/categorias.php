<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Gestão de Categorias</h1>
            
            <!-- Botão Nova Categoria -->
            <div class="my-3">
                <a href="?a=admin_categoria_nova" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nova Categoria
                </a>
            </div>

            <!-- Lista de Categorias -->
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Produtos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categorias as $categoria): ?>
                    <tr>
                        <td><?= $categoria->id_categoria ?></td>
                        <td><?= $categoria->nome ?></td>
                        <td><?= $categoria->descricao ?></td>
                        <td><?= $categoria->total_produtos ?? 0 ?></td>
                        <td>
                            <a href="?a=admin_categoria_editar&id=<?= $categoria->id_categoria ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?a=admin_categoria_eliminar&id=<?= $categoria->id_categoria ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Tem certeza que deseja eliminar esta categoria?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 