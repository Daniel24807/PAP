<div class="container mt-4">
    <h3>Finalizar Compra</h3>

    <div class="row mt-4">
        <!-- Coluna de Informações do Cliente -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Informações de Entrega</h5>
                </div>
                <div class="card-body">
                    <form id="form-endereco">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" required>
                            </div>
                            <div class="col-md-6">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="telefone" name="telefone" required>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Método de Pagamento</h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="metodo_pagamento" id="pagamento_transferencia" value="transferencia" checked>
                        <label class="form-check-label" for="pagamento_transferencia">
                            Transferência Bancária
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="metodo_pagamento" id="pagamento_cartao" value="cartao">
                        <label class="form-check-label" for="pagamento_cartao">
                            Cartão de Crédito/Débito
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="metodo_pagamento" id="pagamento_entrega" value="entrega">
                        <label class="form-check-label" for="pagamento_entrega">
                            Pagamento na Entrega
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna de Resumo do Pedido -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Resumo do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="order-items">
                        <?php if (isset($itens_carrinho) && is_array($itens_carrinho) && count($itens_carrinho) > 0): ?>
                            <?php $total = 0; ?>
                            <?php foreach ($itens_carrinho as $item): ?>
                                <?php $subtotal = $item['preco'] * $item['quantidade']; ?>
                                <?php $total += $subtotal; ?>
                                <div class="order-item d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $item['imagem'] ?>" alt="<?= $item['nome'] ?>" class="order-item-img">
                                        <div class="ms-3">
                                            <h6 class="mb-0"><?= $item['nome'] ?></h6>
                                            <small class="text-muted">Quantidade: <?= $item['quantidade'] ?></small>
                                        </div>
                                    </div>
                                    <div class="order-item-price">
                                        <?= number_format($subtotal, 2) ?>€
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <strong><?= number_format($total, 2) ?>€</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Frete:</span>
                                <strong>Grátis</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total:</span>
                                <strong class="order-total"><?= number_format($total, 2) ?>€</strong>
                            </div>
                            
                            <div class="mt-4">
                                <button id="btn-finalizar" class="btn btn-primary w-100">Finalizar Pedido</button>
                            </div>
                        <?php else: ?>
                            <p>Seu carrinho está vazio.</p>
                            <a href="?a=loja" class="btn btn-primary">Continuar Comprando</a>
                        <?php endif; ?>
                    </div>
                </div>
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

.order-total {
    font-size: 1.2rem;
    color: var(--primary-color);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnFinalizar = document.getElementById('btn-finalizar');
    
    if (btnFinalizar) {
        btnFinalizar.addEventListener('click', function() {
            // Validar formulário
            const form = document.getElementById('form-endereco');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            // Obter dados do formulário
            const formData = new FormData(form);
            
            // Adicionar método de pagamento
            const metodoPagamento = document.querySelector('input[name="metodo_pagamento"]:checked').value;
            formData.append('metodo_pagamento', metodoPagamento);
            
            // Enviar pedido
            fetch('?a=processar_pedido', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'sucesso') {
                    // Limpar o carrinho local
                    if (data.limpar_local) {
                        localStorage.removeItem('cart');
                    }
                    
                    // Redirecionar para a página de sucesso
                    window.location.href = '?a=pedido_sucesso&codigo=' + data.codigo_pedido;
                } else {
                    alert('Erro ao processar pedido: ' + data.mensagem);
                }
            })
            .catch(error => {
                console.error('Erro ao processar pedido:', error);
                alert('Ocorreu um erro ao processar o pedido. Por favor, tente novamente.');
            });
        });
    }
});
</script> 