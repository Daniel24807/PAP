<?php
// Verificar se há mensagem de sucesso do login
if (isset($_SESSION['login_sucesso']) && $_SESSION['login_sucesso']) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Bem-vindo!',
                text: 'Login realizado com sucesso!',
                icon: 'success',
                confirmButtonColor: '#3085d6'
            });
        });
    </script>";
    unset($_SESSION['login_sucesso']);
}
?>

<div class="container-fluid p-4">
    <!-- Hero Section -->
    <div class="card shadow-lg mb-4">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-8 p-3">
                    <div class="hero-image">
                        <img src="<?= BASE_URL ?>/assets/images/admin_hero.jpg" class="img-fluid" alt="Lamborghini Vermelha">
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="p-4">
                        <h2 class="display-6 fw-bold mb-3">Painel Administrativo</h2>
                        <p class="lead text-muted">
                            Bem-vindo ao painel de controle. Gerencia o site apartir daqui com facilidade e eficiência.
                        </p>
                        <hr class="my-4">
                        <p class="mb-4">
                            <i class="fas fa-user me-2"></i>Logado como: <strong><?= $_SESSION['admin_utilizador'] ?></strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All Actions in One Row -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h4 class="mb-0">Clientes</h4>
                    </div>
                    <p class="text-muted">Gerencia os clientes registados no site.</p>
                    <a href="?a=listar_clientes" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-right me-2"></i>Acessar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success text-white rounded-circle p-3 me-3">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                        <h4 class="mb-0">Pedidos</h4>
                    </div>
                    <p class="text-muted">Visualiza e gerencia os pedidos da loja.</p>
                    <a href="?a=listar_pedidos" class="btn btn-outline-success">
                        <i class="fas fa-arrow-right me-2"></i>Acessar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded-circle p-3 me-3">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                        <h4 class="mb-0">Produtos</h4>
                    </div>
                    <p class="text-muted">Gerencia o catálogo de produtos da loja.</p>
                    <a href="?a=admin_produtos" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-right me-2"></i>Acessar
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info text-white rounded-circle p-3 me-3">
                            <i class="fas fa-cog fa-2x"></i>
                        </div>
                        <h4 class="mb-0">Configurações</h4>
                    </div>
                    <p class="text-muted">Configura as preferências do sistema.</p>
                    <a href="?a=perfil_admin" class="btn btn-outline-info">
                        <i class="fas fa-arrow-right me-2"></i>Acessar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: none;
}
.bg-primary, .bg-success, .bg-info {
    background: linear-gradient(145deg, var(--bs-primary), var(--bs-primary-dark, #0056b3)) !important;
}
.rounded-circle {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hero-image {
    border-radius: 0;
    box-shadow: none;
    overflow: hidden;
    position: relative;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 400px;
    background-color: #f8f9fa;
}
.hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}
.hero-image:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.05), rgba(0,0,0,0));
    pointer-events: none;
}
</style>