<?php
// Verificar se existe cliente na sessão
if (!isset($cliente)) {
    core\classes\Store::redirect();
    return;
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Minha Conta</h3>
            
            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['erro'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['sucesso'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['sucesso']); ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <!-- Menu lateral -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="?a=minha_conta" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user-circle me-2"></i> Minha Conta
                    </a>
                    <a href="?a=alterar_senha" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Alterar Senha
                    </a>
                    <a href="?a=alterar_morada" class="list-group-item list-group-item-action">
                        <i class="fas fa-home me-2"></i> Alterar Morada
                    </a>
                    <a href="?a=meus_pedidos" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i> Meus Pedidos
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Conteúdo principal -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dados Pessoais</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Nome:</div>
                        <div class="col-md-9"><?= $cliente->nome_completo ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Email:</div>
                        <div class="col-md-9"><?= $cliente->email ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Morada:</div>
                        <div class="col-md-9"><?= $cliente->morada ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Cidade:</div>
                        <div class="col-md-9"><?= $cliente->cidade ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Código Postal:</div>
                        <div class="col-md-9"><?= isset($cliente->codigo_postal) ? $cliente->codigo_postal : 'Não definido' ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Telefone:</div>
                        <div class="col-md-9"><?= $cliente->telefone ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Data de Registro:</div>
                        <div class="col-md-9"><?= date('d/m/Y', strtotime($cliente->created_at)) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 