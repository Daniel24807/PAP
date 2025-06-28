<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/assets/css/admin.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>
    <?php use core\classes\Store; ?>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark admin-header">
        <div class="container-fluid">
            <a class="navbar-brand" href="?a=inicio">
                <i class="fas fa-shield-alt me-2"></i>
                Painel Administrativo
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (Store::adminLogado()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="?a=admin_logout">
                            <i class="fas fa-sign-out-alt me-1"></i> Sair
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php if (Store::adminLogado()): ?>
            <!-- Sidebar - Mostrado apenas quando o admin está logado -->
            <div class="col-md-3 col-lg-2 px-0 admin-sidebar">
                <div class="d-flex flex-column">
                    <a class="nav-link <?= isset($_GET['a']) && $_GET['a'] == 'inicio' ? 'active' : '' ?>" href="?a=inicio">
                        <i class="fas fa-home me-2"></i>
                        Início
                    </a>
                    <a class="nav-link <?= isset($_GET['a']) && ($_GET['a'] == 'clientes' || $_GET['a'] == 'listar_clientes') ? 'active' : '' ?>" href="?a=listar_clientes">
                        <i class="fas fa-users me-2"></i>
                        Clientes
                    </a>
                    <a class="nav-link <?= isset($_GET['a']) && $_GET['a'] == 'listar_pedidos' ? 'active' : '' ?>" href="?a=listar_pedidos">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Pedidos
                    </a>
                    <a class="nav-link <?= isset($_GET['a']) && $_GET['a'] == 'admin_produtos' ? 'active' : '' ?>" href="?a=admin_produtos">
                        <i class="fas fa-box me-2"></i>
                        Produtos
                    </a>
                    <a class="nav-link <?= isset($_GET['a']) && $_GET['a'] == 'perfil_admin' ? 'active' : '' ?>" href="?a=perfil_admin">
                        <i class="fas fa-user-cog me-2"></i>
                        Configurações
                    </a>
                </div>
            </div>

            <!-- Conteúdo Principal quando logado -->
            <div class="col-md-9 col-lg-10 px-4 py-3">
            <?php else: ?>
            <!-- Conteúdo Principal quando não logado (tela cheia) -->
            <div class="col-12 px-4 py-3">
            <?php endif; ?>
                <div class="fade-in">