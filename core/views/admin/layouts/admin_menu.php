<?php if (isset($_SESSION['login_sucesso'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Login realizado com sucesso!",
            icon: "success",
            draggable: false
        });
    </script>
    <?php unset($_SESSION['login_sucesso']); ?>
<?php endif; ?>

<div class="admin-menu">
    <div class="mb-3">
        <a href="?a=inicio" class="menu-item <?= $_GET['a'] == 'inicio' ? 'active' : '' ?>">
            <i class="fas fa-home me-2"></i> Início
        </a>
    </div>
    
    <div class="d-flex flex-wrap justify-content-between menu-grid">
        <div class="menu-grid-item">
            <a href="?a=listar_clientes" class="menu-item <?= $_GET['a'] == 'listar_clientes' ? 'active' : '' ?>">
                <i class="fas fa-users me-2"></i> Clientes
            </a>
        </div>
        <div class="menu-grid-item">
            <a href="?a=listar_pedidos" class="menu-item <?= $_GET['a'] == 'listar_pedidos' ? 'active' : '' ?>">
                <i class="fas fa-shopping-cart me-2"></i> Pedidos
            </a>
        </div>
        <div class="menu-grid-item">
            <a href="?a=admin_produtos" class="menu-item <?= $_GET['a'] == 'admin_produtos' ? 'active' : '' ?>">
                <i class="fas fa-box me-2"></i> Produtos
            </a>
        </div>
        <div class="menu-grid-item">
            <a href="?a=perfil_admin" class="menu-item <?= $_GET['a'] == 'perfil_admin' ? 'active' : '' ?>">
                <i class="fas fa-user-cog me-2"></i> Configurações
            </a>
        </div>
    </div>
</div>

<style>
.admin-menu {
    padding: 1rem;
}

.menu-item {
    display: block;
    padding: 0.75rem 1rem;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.2s ease;
    text-align: center;
}

.menu-item:hover {
    background-color: #f3e5f5;
    color: #6a1b9a;
}

.menu-item.active {
    background-color: #6a1b9a;
    color: white;
    font-weight: 500;
}

.menu-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 15px;
}

.menu-grid-item {
    margin-bottom: 10px;
}
</style>
