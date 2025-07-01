<?php
use core\classes\store;

if (!Store::adminLogado()) {
    Store::redirect('admin_login', true);
    return;
}

// Verificar se existe mensagem de erro ou sucesso
if (isset($_SESSION['erro'])) {
    echo '<div class="alert alert-danger mt-3">' . $_SESSION['erro'] . '</div>';
    unset($_SESSION['erro']);
}

if (isset($_SESSION['sucesso'])) {
    echo '<div class="alert alert-success mt-3">' . $_SESSION['sucesso'] . '</div>';
    unset($_SESSION['sucesso']);
}

// Depuração para verificar se os pedidos estão chegando na view
echo "<!-- Depuração: Número de pedidos: " . (isset($pedidos) ? count($pedidos) : 'variável não definida') . " -->";
if (isset($pedidos) && !empty($pedidos)) {
    echo "<!-- Primeiro pedido: " . json_encode($pedidos[0]) . " -->";
}
?>

<div class="card">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i>
                Lista de Pedidos
            </h5>
            <a href="?a=listar_pedidos" class="btn btn-primary">
                <i class="fas fa-sync me-2"></i>
                Atualizar
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (count($pedidos) == 0) : ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Não existem pedidos cadastrados.
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover" id="tabelaPedidos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $pedido) : ?>
                            <tr>
                                <td><?= $pedido->id_pedido ?></td>
                                <td><?= $pedido->codigo_pedido ?></td>
                                <td>
                                    <?php if (!empty($pedido->nome_completo)) : ?>
                                        <?= $pedido->nome_completo ?>
                                    <?php else : ?>
                                        Cliente #<?= $pedido->id_cliente ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido->data_pedido)) ?></td>
                                <td><?= number_format($pedido->total, 2, ',', '.') ?> €</td>
                                <td>
                                    <span class="badge bg-<?= $this->getStatusColor($pedido->status) ?>">
                                        <?= ucfirst($pedido->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="?a=visualizar_pedido&id=<?= $pedido->id_pedido ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-primary btn-status" 
                                                data-id="<?= $pedido->id_pedido ?>" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#statusModal">
                                            <i class="fas fa-edit"></i>
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

<!-- Modal para atualizar status -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Atualizar Status do Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="formStatus">
                    <input type="hidden" id="id_pedido" name="id_pedido">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pendente">Pendente</option>
                            <option value="processando">Processando</option>
                            <option value="enviado">Enviado</option>
                            <option value="entregue">Entregue</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSalvarStatus">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTables
    $('#tabelaPedidos').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json"
        },
        "pageLength": 10,
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
        "order": [[ 0, "desc" ]] // Ordenar pelo ID em ordem descendente
    });

    // Botão para abrir modal de status
    const btnsStatus = document.querySelectorAll('.btn-status');
    btnsStatus.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('id_pedido').value = this.dataset.id;
        });
    });

    // Botão para salvar status
    document.getElementById('btnSalvarStatus').addEventListener('click', function() {
        const id_pedido = document.getElementById('id_pedido').value;
        const status = document.getElementById('status').value;

        // Enviar requisição AJAX para atualizar status
        fetch('?a=atualizar_status_pedido', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                id_pedido: id_pedido,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recarregar página para mostrar as alterações
                location.reload();
            } else {
                alert('Erro ao atualizar status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar a requisição');
        });
    });
});
</script>

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