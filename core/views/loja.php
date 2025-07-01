<!-- Filtros e Categorias -->
<div class="filters-section py-4 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <select class="form-select" id="categoria">
                    <option value="">Todas as Categorias</option>
                    <?php if(isset($categorias) && count($categorias) > 0): ?>
                        <?php foreach($categorias as $categoria): ?>
                            <option value="<?= $categoria->id_categoria ?>" <?= isset($categoria_atual) && $categoria_atual->id_categoria == $categoria->id_categoria ? 'selected' : '' ?>>
                                <?= $categoria->nome ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="preco">
                    <option value="">Todos os Preços</option>
                    <option value="0-20">Até 20,00€</option>
                    <option value="20-50">20,00€ - 50,00€</option>
                    <option value="50+">Acima de 50,00€</option>
                </select>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchInput" placeholder="Procurar produtos...">
                    <button class="btn btn-primary" type="button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Produtos -->
<div class="products-section py-5 mb-5">
    <div class="container">
        <div class="row g-4" id="productsContainer">
            <?php if(isset($produtos) && count($produtos) > 0): ?>
                <?php foreach($produtos as $produto): ?>
                    <div class="col-md-3 product-item" data-category="<?= $produto->id_categoria ?>" data-price="<?= $produto->preco ?>" data-product-id="<?= $produto->id_produto ?>">
                        <div class="product-card">
                            <div class="product-image">
                                <?php if(!empty($produto->imagem)): ?>
                                    <?php
                                    // Verificar o formato do caminho da imagem
                                    $imagem = $produto->imagem;
                                    
                                    // Se o caminho já incluir "assets/images", usar o caminho completo
                                    if (strpos($imagem, 'assets/images/') !== false) {
                                        $caminho_imagem = "public/assets/images/produtos/{$imagem}";
                                    } 
                                    ?>
                                    <img src="<?= $caminho_imagem ?>" alt="<?= $produto->nome ?>" class="img-fluid">
                                <?php else: ?>
                                    <img src="public/assets/images/no-image.png" alt="Sem imagem" class="img-fluid">
                                <?php endif; ?>
                            </div>
                            <div class="product-content">
                                <h4><?= $produto->nome ?></h4>
                                <p class="description"><?= substr($produto->descricao, 0, 100) ?>...</p>
                                <p class="price"><?= number_format($produto->preco, 2, ',', '.') ?>€</p>
                                <div class="d-grid">
                                    <a href="?a=produto&id=<?= $produto->id_produto ?>" class="btn btn-primary">Ver Detalhes</a>
                                    <button class="btn btn-success mt-2 add-to-cart" data-product-id="<?= $produto->id_produto ?>">
                                        <i class="fas fa-shopping-cart me-2"></i>Adicionar ao Carrinho
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Não existem produtos disponíveis no momento.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal do Produto -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="modal-product-image">
                            <img src="" alt="" class="img-fluid" id="modalProductImage">
                            <img src="" alt="" class="img-fluid" id="modalProductImage2" style="display: none;">
                            <div class="modal-image-controls">
                                <button class="btn btn-light" id="prevImage">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="btn btn-light" id="nextImage">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modal-product-details">
                            <h3 id="modalProductTitle"></h3>
                            <p class="modal-description" id="modalProductDescription"></p>
                            <p class="modal-price" id="modalProductPrice"></p>
                            <div class="modal-product-info">
                                <h4>Características:</h4>
                                <ul id="modalProductFeatures">
                                    <li>Produto testado por profissionais</li>
                                    <li>Alta qualidade</li>
                                    <li>Resultado garantido</li>
                                </ul>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg" id="modalAddToCart">
                                    Adicionar ao Carrinho
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos Base */
.form-select, .form-control {
    border-radius: 25px;
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
}

.input-group .form-control {
    border-radius: 25px 0 0 25px;
}

.input-group .btn {
    border-radius: 0 25px 25px 0;
}

/* Cards de Produtos */
.product-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    height: 300px;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 1rem;
    transition: transform 0.3s ease;
}

.product-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-content h4 {
    color: #2c3e50;
    font-size: 1.1rem;
    min-height: 40px;
    margin-bottom: 0.5rem;
}

.description {
    color: #666;
    font-size: 0.9rem;
    flex: 1;
    min-height: 60px;
    margin-bottom: 1rem;
}

.price {
    color: #2c3e50;
    font-weight: bold;
    font-size: 1.2rem;
    margin: 0.5rem 0;
}

.d-grid {
    margin-top: auto;
}

/* Filtros */
.product-item {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

.product-item.hidden {
    display: none !important;
}

/* Modal */
.modal-product-image {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 15px;
    height: 400px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-product-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    position: absolute;
    transition: transform 0.3s ease;
}

.modal-product-image img#modalProductImage2 {
    transform: translateX(100%);
}

.modal-image-controls {
    position: absolute;
    bottom: 1rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 1rem;
    z-index: 10;
}

.modal-image-controls .btn {
    background: rgba(255, 255, 255, 0.8);
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
}

.modal-image-controls .btn i {
    color: #2c3e50;
}

.modal-image-controls .btn:hover i {
    color: #3498db;
}

/* Botões */
.btn-primary {
    background: #2c3e50;
    border: none;
    padding: 0.8rem;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #3498db;
    transform: translateY(-2px);
}

/* Responsividade */
@media (max-width: 768px) {
    .product-image {
        height: 250px;
    }
}

@media (max-width: 576px) {
    .product-image {
        height: 200px;
    }
}

.produtos-wrapper {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
    padding: 2rem;
    margin-bottom: 3rem;
}

.produto-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 1rem;
    display: flex;
    flex-direction: column;
}

.produto-imagem {
    width: 100%;
    height: 200px;
    object-fit: contain;
    margin-bottom: 1rem;
}

.produto-info {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.produto-titulo {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.produto-descricao {
    color: #666;
    margin-bottom: 1rem;
    flex-grow: 1;
}

.produto-preco {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.btn-adicionar {
    width: 100%;
    padding: 0.8rem;
    background: #2c3e50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-adicionar:hover {
    background: #3498db;
}

.products-section {
    padding-bottom: 5rem !important;
}

/* Estilos personalizados para SweetAlert2 */
.swal2-popup.swal2-toast {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    background-color: #fff;
    border-left: 4px solid #1a3b6d;
    max-width: 350px;
    width: auto;
}

.swal2-popup.swal2-toast .swal2-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1a3b6d;
    margin-bottom: 0.5rem;
    text-align: left;
}

.swal2-popup.swal2-toast .swal2-html-container {
    text-align: left;
    margin: 0;
    padding: 0;
}

.swal2-popup.swal2-toast .swal2-icon {
    margin: 0 0.5rem 0 0;
    width: 2em;
    height: 2em;
}

.swal2-popup.swal2-toast .swal2-icon .swal2-icon-content {
    font-size: 1.5rem;
}

.swal2-popup.swal2-toast.swal2-icon-success {
    border-left-color: #28a745;
}

.swal2-popup.swal2-toast.swal2-icon-error {
    border-left-color: #dc3545;
}

.swal2-timer-progress-bar {
    background: rgba(26, 59, 109, 0.3);
    height: 3px;
    bottom: 0;
}

.swal2-popup.swal2-toast .swal2-actions {
    margin-top: 0.75rem;
    flex-wrap: nowrap;
    gap: 0.5rem;
}

.swal2-popup.swal2-toast .swal2-styled.swal2-confirm,
.swal2-popup.swal2-toast .swal2-styled.swal2-cancel {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const categoriaSelect = document.getElementById('categoria');
    const precoSelect = document.getElementById('preco');
    const productItems = document.querySelectorAll('.product-item');

    // Filtrar produtos
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedCategory = categoriaSelect.value;
        const selectedPrice = precoSelect.value;

        productItems.forEach(item => {
            const title = item.querySelector('h4').textContent.toLowerCase();
            const description = item.querySelector('.description').textContent.toLowerCase();
            const categories = item.dataset.category.split(',');
            const price = parseFloat(item.dataset.price);

            // Verificar filtro de pesquisa
            const matchesSearch = searchTerm === '' || 
                title.includes(searchTerm) || 
                description.includes(searchTerm);

            // Verificar filtro de categoria
            const matchesCategory = selectedCategory === '' || 
                categories.includes(selectedCategory);

            // Verificar filtro de preço
            let matchesPrice = true;
            if (selectedPrice) {
                const priceValue = parseFloat(item.dataset.price);
                switch(selectedPrice) {
                    case '0-20': 
                        matchesPrice = priceValue <= 20;
                        break;
                    case '20-50': 
                        matchesPrice = priceValue > 20 && priceValue <= 50;
                        break;
                    case '50+': 
                        matchesPrice = priceValue > 50;
                        break;
                }
            }

            // Aplicar filtros
            if (matchesSearch && matchesCategory && matchesPrice) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });

        // Verificar se há produtos visíveis
        const visibleProducts = document.querySelectorAll('.product-item:not(.hidden)');
        const noResultsMessage = document.getElementById('no-results-message');
        
        if (visibleProducts.length === 0) {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.id = 'no-results-message';
                message.className = 'col-12 text-center py-5';
                message.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-search me-2"></i>
                        Nenhum produto encontrado com os filtros selecionados.
                    </div>
                `;
                document.getElementById('productsContainer').appendChild(message);
            }
        } else if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }

    // Abrir modal do produto
    function openProductModal(productCard) {
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        const productItem = productCard.closest('.product-item');
        const productId = productItem.dataset.productId || '1';
        const title = productCard.querySelector('h4').textContent;
        const description = productCard.querySelector('.description').textContent;
        const price = productCard.querySelector('.price').textContent;
        const image = productCard.querySelector('img').src;
        
        // Configurar segunda imagem para produtos específicos
        let image2 = '';
        const hasSecondImage = title === "Selante Hidrofóbico Instantâneo em Spray" || title === "Limpeza de Vidros";
        
        if (hasSecondImage) {
            image2 = title === "Selante Hidrofóbico Instantâneo em Spray" 
                ? "assets/images/gyeon/gyeon-wet-coat-2.jpg" 
                : "assets/images/gyeon/gyeon-glass-2.jpg";
        }

        // Atualizar elementos do modal
        document.getElementById('modalProductTitle').textContent = title;
        document.getElementById('modalProductDescription').textContent = description;
        document.getElementById('modalProductPrice').textContent = price;
        document.getElementById('modalProductImage').src = image;
        document.getElementById('modalProductImage2').src = image2;
        
        // Armazenar o ID do produto no botão de adicionar ao carrinho
        document.getElementById('modalAddToCart').dataset.productId = productId;
        
        // Configurar visibilidade dos controles
        document.getElementById('modalProductImage2').style.display = hasSecondImage ? 'block' : 'none';
        document.querySelector('.modal-image-controls').style.display = hasSecondImage ? 'flex' : 'none';

        // Resetar posição das imagens
        document.getElementById('modalProductImage').style.transform = 'translateX(0)';
        document.getElementById('modalProductImage2').style.transform = 'translateX(100%)';

        modal.show();
    }

    // Event Listeners
    searchInput.addEventListener('input', filterProducts);
    searchButton.addEventListener('click', filterProducts);
    categoriaSelect.addEventListener('change', filterProducts);
    precoSelect.addEventListener('change', filterProducts);
    searchInput.addEventListener('keypress', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterProducts();
        }
    });

    // Eventos do modal
    document.querySelectorAll('.product-image').forEach(image => {
        image.addEventListener('click', () => openProductModal(image.closest('.product-card')));
    });

    document.getElementById('prevImage').addEventListener('click', () => {
        if (document.getElementById('modalProductImage2').style.display === 'block') {
            document.getElementById('modalProductImage').style.transform = 'translateX(0)';
            document.getElementById('modalProductImage2').style.transform = 'translateX(100%)';
        }
    });

    document.getElementById('nextImage').addEventListener('click', () => {
        if (document.getElementById('modalProductImage2').style.display === 'block') {
            document.getElementById('modalProductImage').style.transform = 'translateX(-100%)';
            document.getElementById('modalProductImage2').style.transform = 'translateX(0)';
        }
    });

    document.getElementById('modalAddToCart').addEventListener('click', () => {
        const productId = document.getElementById('modalAddToCart').dataset.productId;
        const title = document.getElementById('modalProductTitle').textContent;
        const price = parseFloat(document.getElementById('modalProductPrice').textContent.replace('€', ''));
        const image = document.getElementById('modalProductImage').src;
        
        // Usar a função addToCart com o ID do produto
        addToCart(productId, title, price, image);
        
        // Fechar modal
        bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
    });

    // Adicionar evento aos botões "Adicionar ao Carrinho" dos cards
    document.querySelectorAll('.product-card .btn-primary').forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const card = button.closest('.product-card');
            const title = card.querySelector('h4').textContent;
            const price = parseFloat(card.querySelector('.price').textContent.replace('€', ''));
            const image = card.querySelector('img').src;
            
            // Usar a função addToCart com um ID fictício baseado no título
            // Idealmente, cada produto deveria ter um ID real no banco de dados
            const productId = card.closest('.product-item').dataset.productId || 
                             title.toLowerCase().replace(/[^a-z0-9]/g, '-');
            
            addToCart(productId, title, price, image);
        });
    });

    // Atualizar contador do carrinho ao carregar a página
    document.addEventListener('DOMContentLoaded', () => {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const cartBadge = document.querySelector('.navegacao .badge');
        if (cartBadge) {
            cartBadge.textContent = cart.reduce((total, item) => total + item.quantity, 0);
        }
    });
});

function addToCart(productId, title, price, image) {
    // Verificar se o usuário está logado
    const isLoggedIn = <?= \core\classes\Store::clienteLogado() ? 'true' : 'false' ?>;
    
    if (isLoggedIn) {
        // Se estiver logado, enviar para o servidor
        const formData = new FormData();
        formData.append('id_produto', productId);
        formData.append('quantidade', 1);
        
        fetch('?a=carrinho_adicionar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'sucesso') {
                // Atualizar o badge do carrinho
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(data.total_itens);
                }
                
                // Feedback visual com SweetAlert2
                Swal.fire({
                    title: 'Adicionado!',
                    html: `<div class="d-flex align-items-center">
                            <img src="${image}" alt="${title}" class="me-3" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                            <div>
                                <div class="fw-bold">${title}</div>
                                <div class="small text-success">Produto adicionado ao carrinho</div>
                            </div>
                          </div>`,
                    icon: 'success',
                    showCancelButton: true,
                    cancelButtonText: 'Continuar Comprando',
                    confirmButtonText: 'Ver Carrinho',
                    confirmButtonColor: '#1a3b6d',
                    cancelButtonColor: '#6c757d',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-right',
                    width: 'auto',
                    padding: '1rem',
                    showClass: {
                        popup: 'animate__animated animate__fadeInRight animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutRight animate__faster'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '?a=carrinho';
                    }
                });
            } else {
                // Erro com SweetAlert2
                Swal.fire({
                    title: 'Erro',
                    text: 'Erro ao adicionar produto: ' + data.mensagem,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#1a3b6d'
                });
            }
        })
        .catch(error => {
            console.error('Erro ao adicionar produto:', error);
            // Erro com SweetAlert2
            Swal.fire({
                title: 'Erro',
                text: 'Ocorreu um erro ao adicionar o produto ao carrinho.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#1a3b6d'
            });
        });
    } else {
        // Se não estiver logado, salvar no localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Verificar se o produto já existe no carrinho
        const existingProduct = cart.find(item => item.id === productId);
        
        if (existingProduct) {
            existingProduct.quantity += 1;
        } else {
            cart.push({
                id: productId,
                title: title,
                price: price,
                image: image,
                quantity: 1
            });
        }
        
        // Salvar no localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Atualizar o badge do carrinho
        if (typeof updateCartBadge === 'function') {
            updateCartBadge();
        }
        
        // Feedback visual com SweetAlert2
        Swal.fire({
            title: 'Adicionado!',
            html: `<div class="d-flex align-items-center">
                    <img src="${image}" alt="${title}" class="me-3" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    <div>
                        <div class="fw-bold">${title}</div>
                        <div class="small text-success">Produto adicionado ao carrinho</div>
                    </div>
                  </div>`,
            icon: 'success',
            showCancelButton: true,
            cancelButtonText: 'Continuar Comprando',
            confirmButtonText: 'Ver Carrinho',
            confirmButtonColor: '#1a3b6d',
            cancelButtonColor: '#6c757d',
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-right',
            width: 'auto',
            padding: '1rem',
            showClass: {
                popup: 'animate__animated animate__fadeInRight animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutRight animate__faster'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '?a=carrinho';
            }
        });
    }
}
</script>