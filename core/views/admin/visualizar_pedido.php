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
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?a=listar_pedidos">Pedidos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalhes do Pedido #<?= $pedido->codigo_pedido ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Informações do Pedido -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Informações do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Código:</div>
                        <div class="col-md-8"><?= $pedido->codigo_pedido ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Data:</div>
                        <div class="col-md-8"><?= date('d/m/Y H:i', strtotime($pedido->data_pedido)) ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            <span class="badge bg-<?= $this->getStatusColor($pedido->status) ?>">
                                <?= ucfirst($pedido->status) ?>
                            </span>
                            <button class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <i class="fas fa-edit"></i> Alterar
                            </button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Total:</div>
                        <div class="col-md-8"><?= number_format($pedido->total, 2, ',', '.') ?> €</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Método de Pagamento:</div>
                        <div class="col-md-8"><?= ucfirst($pedido->metodo_pagamento) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Cliente -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Informações do Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Nome:</div>
                        <div class="col-md-8"><?= $pedido->nome ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8"><?= $pedido->email ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Endereço:</div>
                        <div class="col-md-8"><?= $pedido->endereco ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Cidade:</div>
                        <div class="col-md-8"><?= $pedido->cidade ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Código Postal:</div>
                        <div class="col-md-8"><?= $pedido->codigo_postal ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Telefone:</div>
                        <div class="col-md-8"><?= $pedido->telefone ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Itens do Pedido -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5 class="mb-0">Itens do Pedido</h5>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Imagem</th>
                                    <th>Quantidade</th>
                                    <th>Preço Unitário</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itens as $item): ?>
                                    <tr>
                                        <td><?= $item->nome ?></td>
                                        <td>
                                            <?php if (!empty($item->imagem)): ?>
                                                <img src="assets/images/produtos/<?= $item->imagem ?>" alt="<?= $item->nome ?>" class="img-thumbnail" style="max-width: 50px;">
                                            <?php else: ?>
                                                <span class="text-muted">Sem imagem</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $item->quantidade ?></td>
                                        <td><?= number_format($item->preco, 2, ',', '.') ?> €</td>
                                        <td><?= number_format($item->preco * $item->quantidade, 2, ',', '.') ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td class="fw-bold"><?= number_format($pedido->total, 2, ',', '.') ?> €</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="row">
        <div class="col-12">
            <a href="?a=listar_pedidos" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar para Lista
            </a>
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
                    <input type="hidden" id="id_pedido" name="id_pedido" value="<?= $pedido->id_pedido ?>">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pendente" <?= $pedido->status == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="processando" <?= $pedido->status == 'processando' ? 'selected' : '' ?>>Processando</option>
                            <option value="enviado" <?= $pedido->status == 'enviado' ? 'selected' : '' ?>>Enviado</option>
                            <option value="entregue" <?= $pedido->status == 'entregue' ? 'selected' : '' ?>>Entregue</option>
                            <option value="cancelado" <?= $pedido->status == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
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