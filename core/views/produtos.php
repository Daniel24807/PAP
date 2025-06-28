<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h4 class="mb-0">Gestão de Produtos</h4>
                    <a href="?a=admin_produto_novo" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Produto
                    </a>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Lista de Produtos -->
                    <div class="table-responsive p-3">
                        <table id="tabelaProdutos" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Categoria</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Stock</th>
                                    <th>Imagem</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($produtos as $produto): ?>
                                <tr>
                                    <td><?= $produto->id_produto ?></td>
                                    <td><?= $produto->id_categoria ?></td>
                                    <td><?= $produto->nome ?></td>
                                    <td><?= substr($produto->descricao, 0, 50) . (strlen($produto->descricao) > 50 ? '...' : '') ?></td>
                                    <td><?= $produto->stock ?></td>
                                    <td>
                                        <?php if(!empty($produto->imagem)): ?>
                                            <img src="public/assets/images/gyeon/<?= $produto->imagem ?>" alt="<?= $produto->nome ?>" class="img-produto">
                                        <?php else: ?>
                                            <img src="public/assets/images/no-image.png" alt="Sem imagem" class="img-produto">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="?a=admin_produto_editar&id=<?= $produto->id_produto ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                           onclick="confirmarExclusao('?a=admin_produto_eliminar&id=<?= $produto->id_produto ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.img-produto {
    object-fit: cover;
    width: 50px;
    height: 50px;
    border-radius: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTables
    $('#tabelaProdutos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "pageLength": 10,
        "responsive": true,
        "order": [[ 0, "asc" ]]
    });
});

function confirmarExclusao(url) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        window.location.href = url;
    }
}
</script>
 