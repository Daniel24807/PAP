<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success">
                <h4 class="alert-heading">Pedido Confirmado!</h4>
                <p>Seu pedido foi realizado com sucesso. Obrigado pela sua compra!</p>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Detalhes do Pedido</h5>
                </div>
                <div class="card-body">
                    <p><strong>Código do Pedido:</strong> <?= $pedido['codigo_pedido'] ?></p>
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge <?= $pedido['status'] == 'pendente' ? 'bg-warning' : 'bg-success' ?>">
                            <?= ucfirst($pedido['status']) ?>
                        </span>
                    </p>
                    <p><strong>Método de Pagamento:</strong> <?= ucfirst($pedido['metodo_pagamento']) ?></p>
                    <p><strong>Total:</strong> <?= number_format($pedido['total'], 2) ?>€</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Endereço de Entrega</h5>
                </div>
                <div class="card-body">
                    <p><strong>Endereço:</strong> <?= $pedido['endereco'] ?></p>
                    <p><strong>Cidade:</strong> <?= $pedido['cidade'] ?></p>
                    <p><strong>Código Postal:</strong> <?= $pedido['codigo_postal'] ?></p>
                    <p><strong>Telefone:</strong> <?= $pedido['telefone'] ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Itens do Pedido</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($itens) && is_array($itens) && count($itens) > 0): ?>
                        <?php foreach ($itens as $item): ?>
                            <div class="order-item d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $item['imagem'] ?>" alt="<?= $item['nome'] ?>" class="order-item-img">
                                    <div class="ms-3">
                                        <h6 class="mb-0"><?= $item['nome'] ?></h6>
                                        <small class="text-muted">Quantidade: <?= $item['quantidade'] ?></small>
                                    </div>
                                </div>
                                <div class="order-item-price">
                                    <?= number_format($item['preco'] * $item['quantidade'], 2) ?>€
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nenhum item encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="?a=loja" class="btn btn-primary">Continuar Comprando</a>
                <a href="#" class="btn btn-outline-secondary" onclick="window.print();">Imprimir Pedido</a>
            </div>
        </div>
    </div>
</div>

<style>
.order-item-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
}

@media print {
    .header, .footer, .btn {
        display: none !important;
    }
}
</style> 