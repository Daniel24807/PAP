<?php
// Verificar se o produto existe
if (!isset($produto)) {
    Store::redirect();
    return;
}
?>

<div class="container py-5">
    <div class="row">
        <!-- Imagem do Produto -->
        <div class="col-md-6">
            <div class="product-image-container">
                <?php if(!empty($produto->imagem)): ?>
                    <img src="public/assets/images/produtos/<?= $produto->imagem ?>" alt="<?= $produto->nome ?>" class="img-fluid rounded shadow">
                <?php else: ?>
                    <img src="public/assets/images/produtos/no-image.png" alt="Sem imagem" class="img-fluid rounded shadow">
                <?php endif; ?>
            </div>
        </div>

        <!-- Detalhes do Produto -->
        <div class="col-md-6">
            <div class="product-details">
                <h1 class="mb-3"><?= $produto->nome ?></h1>
                
                <?php if(isset($produto->categoria_nome) && !empty($produto->categoria_nome)): ?>
                    <p class="category-badge">
                        <span class="badge bg-secondary"><?= $produto->categoria_nome ?></span>
                    </p>
                <?php endif; ?>
                
                <div class="price-container my-4">
                    <h2 class="price"><?= number_format($produto->preco, 2, ',', '.') ?>€</h2>
                    <?php if($produto->stock > 0): ?>
                        <span class="badge bg-success">Em Stock</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Esgotado</span>
                    <?php endif; ?>
                </div>
                
                <div class="description mb-4">
                    <h4>Descrição</h4>
                    <p><?= $produto->descricao ?></p>
                </div>
                
                <?php if($produto->stock > 0): ?>
                    <div class="quantity-selector mb-4">
                        <h4>Quantidade</h4>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" id="decrease-qty">-</button>
                            <input type="number" class="form-control text-center" id="product-quantity" value="1" min="1" max="<?= $produto->stock ?>">
                            <button class="btn btn-outline-secondary" type="button" id="increase-qty">+</button>
                        </div>
                        <small class="text-muted">Disponível: <?= $produto->stock ?> unidades</small>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-primary btn-lg w-100 mb-2 add-to-cart" data-product-id="<?= $produto->id_produto ?>">
                            <i class="fas fa-shopping-cart me-2"></i>Adicionar ao Carrinho
                        </button>
                        <a href="?a=loja" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Voltar para a Loja
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este produto está temporariamente indisponível.
                    </div>
                    <a href="?a=loja" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left me-2"></i>Voltar para a Loja
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.product-image-container {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.product-details {
    padding: 1rem;
}

.price {
    color: #6a1b9a;
    font-weight: bold;
}

.quantity-selector .form-control {
    max-width: 80px;
}

.category-badge {
    margin-bottom: 1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Controles de quantidade
    const decreaseBtn = document.getElementById('decrease-qty');
    const increaseBtn = document.getElementById('increase-qty');
    const quantityInput = document.getElementById('product-quantity');
    const maxStock = <?= $produto->stock ?>;
    
    if (decreaseBtn && increaseBtn && quantityInput) {
        decreaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value < maxStock) {
                quantityInput.value = value + 1;
            }
        });
        
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > maxStock) {
                this.value = maxStock;
            }
        });
    }
    
    // Adicionar ao carrinho
    const addToCartBtn = document.querySelector('.add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(quantityInput.value);
            
            // Aqui você pode implementar a lógica para adicionar ao carrinho
            // Por exemplo, usando AJAX para enviar para o servidor
            
            // Exemplo de feedback visual
            Swal.fire({
                title: 'Produto adicionado!',
                text: 'O produto foi adicionado ao seu carrinho.',
                icon: 'success',
                confirmButtonText: 'Continuar Comprando',
                showCancelButton: true,
                cancelButtonText: 'Ver Carrinho'
            }).then((result) => {
                if (!result.isConfirmed) {
                    window.location.href = '?a=carrinho';
                }
            });
        });
    }
});
</script> 