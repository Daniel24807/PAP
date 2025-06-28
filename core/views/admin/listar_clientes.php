<?php
use core\classes\store;

if (!Store::adminLogado()) {
    Store::redirect('admin_login', true);
    return;
}
?>

<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-users me-2"></i>
                Lista de Clientes
            </h5>
            <a href="?a=novo_cliente" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Novo Cliente
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (count($clientes) == 0) : ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Não existem clientes cadastrados.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Morada</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $index => $cliente) : ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $cliente->nome_completo ?></td>
                                <td><?= $cliente->email ?></td>
                                <td><?= $cliente->morada ?></td>
                                <td>
                                    <?php if ($cliente->activo) : ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Ativo
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Inativo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="?a=cliente_editar&id=<?= $cliente->id_cliente ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmarExclusao('?a=cliente_apagar_Hard&id=<?= $cliente->id_cliente ?>')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
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