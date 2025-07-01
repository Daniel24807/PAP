<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <?php if(isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['sucesso'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['erro'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>
            
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
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Categoria</th>
                                    <th>Preço</th>
                                    <th>Stock</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($produtos) > 0): ?>
                                    <?php foreach($produtos as $produto): ?>
                                    <tr>
                                        <td><?= $produto->id_produto ?></td>
                                        <td>
                                            <?php if(!empty($produto->imagem)): ?>
                                                <?php
                                                // Verificar o formato do caminho da imagem
                                                $imagem = $produto->imagem;
                                                
                                                // Se o caminho já incluir "assets/images", usar o caminho completo
                                                if (strpos($imagem, 'assets/images/') !== false) {
                                                    $partes = explode('assets/images/', $imagem);
                                                    $nome_arquivo = end($partes);
                                                    $caminho_imagem = "../../public/assets/images/produtos_loja/{$nome_arquivo}";
                                                } else {
                                                    // Caso contrário, usar o nome do arquivo diretamente
                                                    $caminho_imagem = "../../public/assets/images/produtos_loja/{$imagem}";
                                                }
                                                ?>
                                                <img src="<?= $caminho_imagem ?>" alt="<?= $produto->nome ?>" class="img-produto">
                                            <?php else: ?>
                                                <img src="../../public/assets/images/no-image.png" alt="Sem imagem" class="img-produto">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $produto->nome ?></td>
                                        <td><?= $produto->categoria_nome ?? 'Sem categoria' ?></td>
                                        <td><?= number_format($produto->preco, 2, ',', '.') ?> €</td>
                                        <td><?= $produto->stock ?></td>
                                        <td>
                                            <a href="?a=admin_produto_editar&id=<?= $produto->id_produto ?>" class="btn btn-sm btn-warning me-1" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Excluir"
                                               onclick="confirmarExclusao(<?= $produto->id_produto ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum produto cadastrado</td>
                                    </tr>
                                <?php endif; ?>
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
    // Destruir a tabela existente se houver
    if ($.fn.DataTable.isDataTable('#tabelaProdutos')) {
        $('#tabelaProdutos').DataTable().destroy();
    }
    
    // Reinicializar a tabela
    $('#tabelaProdutos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "pageLength": 10,
        "responsive": true,
        "order": [[ 0, "asc" ]],
        "destroy": true // Permite reinicialização segura
    });
    
    // Auto-fechar alertas após 5 segundos
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});

function confirmarExclusao(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?a=admin_produto_eliminar&id=' + id;
        }
    });
}
</script>
 