<?php
// Verificar se existe cliente na sessão
if (!isset($_SESSION['cliente'])) {
    core\classes\Store::redirect();
    return;
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Alterar Senha</h3>
            
            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['erro'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['sucesso'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['sucesso'] ?>
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
                    <a href="?a=minha_conta" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-circle me-2"></i> Minha Conta
                    </a>
                    <a href="?a=alterar_senha" class="list-group-item list-group-item-action active">
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
                    <h5 class="mb-0">Alterar Senha</h5>
                </div>
                <div class="card-body">
                    <form action="?a=alterar_senha_submit" method="post">
                        <div class="mb-3">
                            <label for="senha_atual" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" id="senha_atual" name="senha_atual" required>
                        </div>
                        <div class="mb-3">
                            <label for="nova_senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
                            <div class="form-text">A senha deve ter pelo menos 6 caracteres.</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Alterar Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['show_sweet_alert']) && $_SESSION['show_sweet_alert']): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '<?= $_SESSION['sucesso'] ?>',
        confirmButtonColor: '#6a329f',
        customClass: {
            confirmButton: 'alert-button'
        }
    });
});
</script>
<?php 
unset($_SESSION['show_sweet_alert']);
endif; 
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        const novaSenha = document.getElementById('nova_senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;
        
        if (novaSenha.length < 6) {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: 'A senha deve ter pelo menos 6 caracteres.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6a329f',
                customClass: {
                    confirmButton: 'alert-button'
                }
            });
            return;
        }
        
        if (novaSenha !== confirmarSenha) {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: 'As senhas não coincidem.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6a329f',
                customClass: {
                    confirmButton: 'alert-button'
                }
            });
            return;
        }
    });
});
</script> 