<?php
// Verificar se existe cliente na sessão
if (!isset($pedidos)) {
    core\classes\Store::redirect();
    return;
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Meus Pedidos</h3>
            
            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['erro'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['sucesso'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <!-- Menu lateral -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="?a=minha_conta" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-circle me-2"></i> Minha Conta
                    </a>
                    <a href="?a=alterar_senha" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Alterar Senha
                    </a>
                    <a href="?a=alterar_morada" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2"></i> Alterar Morada
                    </a>
                    <a href="?a=meus_pedidos" class="list-group-item list-group-item-action active">
                        <i class="fas fa-box me-2"></i> Meus Pedidos
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Conteúdo principal -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Histórico de Pedidos</h5>
                </div>
                <div class="card-body">
                    <?php if (count($pedidos) == 0): ?>
                        <p class="text-center">Você ainda não realizou nenhum pedido.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Data</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pedidos as $pedido): ?>
                                        <tr>
                                            <td><?= $pedido->codigo_pedido ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($pedido->data_pedido)) ?></td>
                                            <td>
                                                <?php if ($pedido->status == 'pendente'): ?>
                                                    <span class="badge bg-warning text-dark">Pendente</span>
                                                <?php elseif ($pedido->status == 'processando'): ?>
                                                    <span class="badge bg-info text-dark">Processando</span>
                                                <?php elseif ($pedido->status == 'enviado'): ?>
                                                    <span class="badge bg-primary">Enviado</span>
                                                <?php elseif ($pedido->status == 'entregue'): ?>
                                                    <span class="badge bg-success">Entregue</span>
                                                <?php elseif ($pedido->status == 'cancelado'): ?>
                                                    <span class="badge bg-danger">Cancelado</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?= ucfirst($pedido->status) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= number_format($pedido->total, 2, ',', '.') ?> €</td>
                                            <td>
                                                <?php if ($pedido->status == 'cancelado'): ?>
                                                    <span class="text-danger">Cancelado</span>
                                                <?php elseif ($pedido->status != 'entregue'): ?>
                                                    <button type="button" class="btn btn-purple" 
                                                       onclick="confirmarCancelamento(<?= $pedido->id_pedido ?>)">
                                                        CANCELAR ENVIO
                                                    </button>
                                                <?php else: ?>
                                                    <a href="?a=pedido_confirmado&id=<?= $pedido->id_pedido ?>" class="btn btn-purple">
                                                        <i class="fas fa-eye"></i> DETALHES
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-purple {
    background-color: #6a1b9a;
    color: white;
    font-weight: 500;
}

.btn-purple:hover {
    background-color: #4a148c;
    color: white;
}
</style>

<script>
function confirmarCancelamento(id_pedido) {
    Swal.fire({
        title: 'Cancelar pedido',
        text: 'Deseja realmente cancelar este pedido?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#6a1b9a',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, cancelar',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?a=cancelar_pedido&id=' + id_pedido;
        }
    });
}
</script> 