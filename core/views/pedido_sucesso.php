<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="success-icon mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="mb-4">Pedido Finalizado com Sucesso!</h2>
                    <p class="lead mb-4">O seu pedido foi processado e será preparado para envio em breve.</p>
                    
                    <?php if(isset($codigo_pedido)): ?>
                    <div class="order-info mb-4">
                        <p class="mb-1">Código do Pedido:</p>
                        <h4 class="mb-3"><?= $codigo_pedido ?></h4>
                        <p class="small text-muted">Guarde este código para acompanhar o seu pedido</p>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mt-5">
                        <a href="?a=loja" class="btn btn-primary btn-lg px-5">Continuar Comprando</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    color: #28a745;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.order-info {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Limpar o carrinho local
    localStorage.removeItem('cart');
    
    // Atualizar o badge do carrinho para zero
    if (typeof updateCartBadge === 'function') {
        updateCartBadge(0);
    }
});
</script> 