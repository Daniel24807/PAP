<?php

use core\classes\store;
?>

<div class="container-fluid navegacao">
    <div class="row">
        <div class="col-6 py-2">
            <a href="?a=inicio">
                <?= APP_NAME ?>
            </a>
        </div>
        <div class="col-6 text-right py-2">
            <a href="?a=inicio" class="nav-item">Início</a>
            <a href="?a=sobre_nos" class="nav-item">Sobre Nós</a>
            <a href="?a=loja" class="nav-item">Loja</a>
            <!-- Verifica se existe cliente na sessão -->
            <?php if (store::clienteLogado()) : ?>
                <div class="dropdown d-inline-block">
                    <a href="#" class="nav-item dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> <?= $_SESSION['utilizador'] ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="?a=minha_conta"><i class="fas fa-user-circle"></i> Minha Conta</a></li>
                        <li><a class="dropdown-item" href="?a=alterar_senha"><i class="fas fa-key"></i> Alterar Senha</a></li>
                        <li><a class="dropdown-item" href="?a=alterar_morada"><i class="fas fa-home"></i> Alterar Morada</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?a=meus_pedidos"><i class="fas fa-box"></i> Meus Pedidos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="?a=logout"><i class="fas fa-sign-out-alt"></i> Terminar Sessão</a></li>
                    </ul>
                </div>
                <a href="?a=carrinho" class="nav-item">
                    <i class="fa-solid fa-cart-shopping"></i> Carrinho
                    <span class="badge bg-warning" id="cart-badge">0</span>
                </a>
            <?php else : ?>
                <a href="?a=login" class="nav-item"><i class="fas fa-sign-in-alt"></i> Iniciar Sessão</a>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<style>
    .navegacao {
        padding: 0;
    }
    
    .navegacao a {
        color: #FFF;
        text-decoration: none;
        margin-right: 15px;
        transition: color 0.3s ease;
    }

    .navegacao a:hover {
        color: #007bff;
    }

    .navegacao .nav-item {
        font-size: 95%;
    }

    .navegacao i {
        margin-right: 5px;
    }

    .navegacao .badge {
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 50%;
        position: relative;
        top: -8px;
        left: -5px;
    }

    .navegacao span.badge {
        background-color: #ffc107;
        color: #000;
    }

    .col-6:first-child a {
        font-size: 100%;
        font-weight: bold;
        color: #FFF;
    }

    .col-6.text-right {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    
    .dropdown-menu {
        background-color: #1a3b6d;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25);
        border: none;
        border-radius: 0.25rem;
        min-width: 200px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
        animation: fadeIn 0.2s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .dropdown-item {
        padding: 0.6rem 1rem;
        color: #ffffff;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    
    .dropdown-item:hover {
        background-color: #2a4b7d;
        color: #ffffff;
        padding-left: 1.2rem;
    }
    
    .dropdown-item i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
        color: #ffffff;
    }
    
    .dropdown-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin: 0.3rem 0;
    }

    .navegacao .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.255em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.3em solid;
        border-right: 0.3em solid transparent;
        border-bottom: 0;
        border-left: 0.3em solid transparent;
        transition: transform 0.2s ease;
    }
    
    .navegacao .dropdown-toggle.show::after {
        transform: rotate(180deg);
    }
</style>

<script>
// Função para atualizar o badge do carrinho
function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        if (count === undefined) {
            // Se o count não foi fornecido, calcular a partir do localStorage
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            count = cart.reduce((total, item) => total + (item.quantity || 1), 0);
        }
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }
}

// Atualizar o badge ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    updateCartBadge();
    
    // Inicializar os dropdowns do Bootstrap
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
});
</script>
