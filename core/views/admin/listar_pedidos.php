<?php
// Verificar se existe mensagem de erro ou sucesso
if (isset($_SESSION['erro'])) {
    echo '<div class="alert alert-danger mt-3">' . $_SESSION['erro'] . '</div>';
    unset($_SESSION['erro']);
}

if (isset($_SESSION['sucesso'])) {
    echo '<div class="alert alert-success mt-3">' . $_SESSION['sucesso'] . '</div>';
    unset($_SESSION['sucesso']);
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <h4 class="mb-0">Gestão de Pedidos</h4>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Formulário de pesquisa -->
                    <div class="px-4 pt-4">
                        <form action="?a=listar_pedidos" method="GET" class="row align-items-center">
                            <input type="hidden" name="a" value="listar_pedidos">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="pesquisa" class="form-control" placeholder="Pesquisar por nome do cliente..." value="<?= isset($pesquisa) ? $pesquisa : '' ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Pesquisar
                                    </button>
                                </div>
                            </div>
                            <?php if (!empty($pesquisa)): ?>
                            <div class="col-md-2">
                                <a href="?a=listar_pedidos" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpar
                                </a>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>

                    <!-- Tabela de pedidos com DataTables -->
                    <div class="table-responsive p-4">
                        <?php if (empty($pedidos)): ?>
                            <div class="alert alert-info">
                                <?= !empty($pesquisa) ? 'Nenhum pedido encontrado para o cliente "' . $pesquisa . '".' : 'Nenhum pedido registrado.' ?>
                            </div>
                        <?php else: ?>
                            <table id="tabelaPedidos" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nº Pedido</th>
                                        <th>ID Cliente</th>
                                        <th>Nome Cliente</th>
                                        <th>Morada</th>
                                        <th>Faturação</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedidos as $pedido): ?>
                                        <tr>
                                            <td><?= $pedido->codigo_pedido ?></td>
                                            <td><?= $pedido->id_cliente ?></td>
                                            <td><?= $pedido->nome ?></td>
                                            <td><?= $pedido->endereco ?>, <?= $pedido->cidade ?></td>
                                            <td><?= number_format($pedido->total, 2, ',', '.') ?> €</td>
                                            <td>
                                                <span class="badge bg-<?= $this->getStatusColor($pedido->status) ?>">
                                                    <?= ucfirst($pedido->status) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?a=visualizar_pedido&id=<?= $pedido->id_pedido ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detalhes
                                                </a>
                                                <button class="btn btn-sm btn-primary btn-status" data-id="<?= $pedido->id_pedido ?>" data-bs-toggle="modal" data-bs-target="#statusModal">
                                                    <i class="fas fa-edit"></i> Status
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
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
        "responsive": true,
        "order": [[ 0, "desc" ]] // Ordenar pela primeira coluna (código de pedido) em ordem descendente
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
                // Fechar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('statusModal'));
                modal.hide();
                
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