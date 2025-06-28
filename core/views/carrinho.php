<div class="container mt-4">
    <h3>Carrinho de Compras</h3>

    <div class="cart-wrapper">
        <!-- Lista de Produtos -->
        <div class="cart-list">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th class="text-center" width="140">Quantidade</th>
                        <th class="text-end" width="120">Preço</th>
                        <th width="50"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Template do Item -->
                    <tr class="cart-item" id="cart-template" style="display: none;">
                        <td>
                            <div class="product-info">
                                <img src="" alt="" class="product-img">
                                <span class="product-name"></span>
                            </div>
                        </td>
                        <td>
                            <div class="quantity-control">
                                <button class="btn-qty decrease-quantity">-</button>
                                <input type="text" class="qty-input" value="1" readonly>
                                <button class="btn-qty increase-quantity">+</button>
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="price"></span>
                        </td>
                        <td>
                            <button class="btn-remove remove-btn">×</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Carrinho Vazio -->
            <div id="empty-cart" class="empty-cart">
                <p>O seu carrinho está vazio</p>
                <a href="?a=loja" class="btn-shop">Continuar Comprando</a>
            </div>
        </div>

        <!-- Resumo -->
        <div class="cart-summary">
            <div class="summary-box">
                <div class="total-row">
                    <strong>Total:</strong>
                    <strong id="total">0,00€</strong>
                </div>
                <button class="btn-checkout" id="checkout-button">
                    Finalizar Compra
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.cart-wrapper {
    display: flex;
    gap: 2rem;
    margin-top: 2rem;
}

.cart-list {
    flex: 1;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    padding: 1rem;
    border-bottom: 1px solid #eee;
    font-weight: 500;
    color: #666;
}

.cart-item {
    border-bottom: 1px solid #eee;
}

.cart-item td {
    padding: 1rem;
    vertical-align: middle;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.product-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 4px;
}

.product-name {
    font-weight: 500;
    color: #333;
}

.quantity-control {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-qty {
    width: 28px;
    height: 28px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}

.qty-input {
    width: 40px;
    height: 28px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    font-size: 14px;
}

.price {
    font-weight: 500;
    color: #333;
}

.btn-remove {
    width: 28px;
    height: 28px;
    border: none;
    background: none;
    font-size: 20px;
    color: #999;
    cursor: pointer;
}

.cart-summary {
    width: 300px;
}

.summary-box {
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.btn-checkout {
    width: 100%;
    padding: 0.8rem;
    border: none;
    background: var(--primary-color);
    color: #fff;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
}

.empty-cart {
    text-align: center;
    padding: 3rem 1rem;
    display: none;
}

.btn-shop {
    display: inline-block;
    padding: 0.8rem 1.5rem;
    background: var(--primary-color);
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .cart-wrapper {
        flex-direction: column;
    }

    .cart-summary {
        width: 100%;
    }

    .cart-table thead {
        display: none;
    }

    .cart-item {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 1rem;
        padding: 1rem;
    }

    .cart-item td {
        padding: 0;
        border: none;
    }

    .product-info {
        grid-column: 1 / -1;
    }

    .quantity-control {
        justify-content: flex-start;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o cliente está logado
    const isLoggedIn = <?= \core\classes\Store::clienteLogado() ? 'true' : 'false' ?>;
    
    // Carregar carrinho
    let cart = [];
    
    if (isLoggedIn) {
        // Se estiver logado, verificar se há itens no localStorage para sincronizar
        const localCart = JSON.parse(localStorage.getItem('cart')) || [];
        
        if (localCart.length > 0) {
            // Sincronizar carrinho local com o banco de dados
            sincronizarCarrinho(localCart);
        } else {
            // Carregar itens do carrinho do banco de dados
            carregarCarrinhoServidor();
        }
    } else {
        // Se não estiver logado, usar o localStorage
        cart = JSON.parse(localStorage.getItem('cart')) || [];
        updateCartDisplay();
    }
    
    // Função para sincronizar o carrinho local com o banco de dados
    function sincronizarCarrinho(localCart) {
        fetch('?a=carrinho_sincronizar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ carrinho: localCart })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                // Limpar carrinho local após sincronização
                localStorage.removeItem('cart');
                // Carregar itens do carrinho do banco de dados
                carregarCarrinhoServidor();
            }
        })
        .catch(error => {
            console.error('Erro ao sincronizar carrinho:', error);
        });
    }
    
    // Função para carregar o carrinho do servidor
    function carregarCarrinhoServidor() {
        // Usar AJAX para obter os itens do carrinho do servidor
        fetch('?a=carrinho_obter', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                cart = data.itens;
                updateCartDisplay();
            }
        })
        .catch(error => {
            console.error('Erro ao carregar carrinho:', error);
        });
    }

    // Atualizar exibição do carrinho
    function updateCartDisplay() {
        const cartTable = document.querySelector('.cart-table tbody');
        const emptyCart = document.getElementById('empty-cart');
        const template = document.getElementById('cart-template');
        
        // Limpar itens existentes (exceto template)
        Array.from(cartTable.children).forEach(child => {
            if (child !== template) {
                child.remove();
            }
        });

        if (cart.length === 0) {
            emptyCart.style.display = 'block';
            document.querySelector('.cart-table').style.display = 'none';
            updateTotals(0);
            return;
        }

        document.querySelector('.cart-table').style.display = 'table';
        emptyCart.style.display = 'none';
        let subtotal = 0;

        cart.forEach((item, index) => {
            const cartItem = template.cloneNode(true);
            cartItem.id = `cart-item-${index}`;
            cartItem.style.display = 'table-row';
            cartItem.dataset.productId = item.id_produto || item.id;

            const image = cartItem.querySelector('.product-img');
            const title = cartItem.querySelector('.product-name');
            const quantity = cartItem.querySelector('.qty-input');
            const price = cartItem.querySelector('.price');

            // Adaptar para funcionar com dados do servidor ou localStorage
            image.src = item.imagem || item.image;
            image.alt = item.nome || item.title;
            title.textContent = item.nome || item.title;
            quantity.value = item.quantidade || item.quantity;
            
            const itemPrice = item.preco || item.price;
            const itemQuantity = item.quantidade || item.quantity;
            const totalPrice = itemPrice * itemQuantity;
            
            price.textContent = `${totalPrice.toFixed(2)}€`;
            subtotal += totalPrice;

            // Eventos dos botões
            cartItem.querySelector('.increase-quantity').addEventListener('click', () => {
                const newQuantity = parseInt(quantity.value) + 1;
                quantity.value = newQuantity;
                
                if (isLoggedIn) {
                    atualizarQuantidadeServidor(item.id_produto, newQuantity);
                } else {
                    item.quantity = newQuantity;
                    updateCart();
                }
            });

            cartItem.querySelector('.decrease-quantity').addEventListener('click', () => {
                if (parseInt(quantity.value) > 1) {
                    const newQuantity = parseInt(quantity.value) - 1;
                    quantity.value = newQuantity;
                    
                    if (isLoggedIn) {
                        atualizarQuantidadeServidor(item.id_produto, newQuantity);
                    } else {
                        item.quantity = newQuantity;
                        updateCart();
                    }
                }
            });

            cartItem.querySelector('.remove-btn').addEventListener('click', () => {
                if (isLoggedIn) {
                    removerProdutoServidor(item.id_produto);
                } else {
                    cart.splice(index, 1);
                    updateCart();
                }
            });

            cartTable.appendChild(cartItem);
        });

        updateTotals(subtotal);
    }

    // Função para atualizar quantidade no servidor
    function atualizarQuantidadeServidor(idProduto, quantidade) {
        const formData = new FormData();
        formData.append('id_produto', idProduto);
        formData.append('quantidade', quantidade);
        
        fetch('?a=carrinho_atualizar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                carregarCarrinhoServidor();
            } else {
                console.error('Erro ao atualizar quantidade:', data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar quantidade:', error);
        });
    }
    
    // Função para remover produto no servidor
    function removerProdutoServidor(idProduto) {
        const formData = new FormData();
        formData.append('id_produto', idProduto);
        
        fetch('?a=carrinho_remover', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                carregarCarrinhoServidor();
                // Atualizar badge do carrinho
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(data.total_itens);
                }
            } else {
                console.error('Erro ao remover produto:', data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro ao remover produto:', error);
        });
    }

    // Atualizar totais
    function updateTotals(subtotal) {
        const total = subtotal;
        document.getElementById('total').textContent = `${total.toFixed(2)}€`;
    }

    // Atualizar carrinho e localStorage
    function updateCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
        // Atualizar badge do carrinho
        if (typeof updateCartBadge === 'function') {
            updateCartBadge(cart.length);
        }
    }

    // Evento do botão Finalizar Compra
    document.getElementById('checkout-button').addEventListener('click', function() {
        if (cart.length === 0) {
            alert('Seu carrinho está vazio!');
            return;
        }
        
        if (isLoggedIn) {
            // Redirecionar para a página de finalização da compra
            window.location.href = '?a=carrinho_finalizar';
        } else {
            // Redirecionar para login
            alert('Faça login para finalizar a compra.');
            window.location.href = '?a=login';
        }
    });
});
</script>