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
            <h3 class="mb-4">Alterar Morada</h3>
            
            <?php if (isset($_SESSION['erro'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['erro'] ?>
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
                    <a href="?a=alterar_senha" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Alterar Senha
                    </a>
                    <a href="?a=alterar_morada" class="list-group-item list-group-item-action active">
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
                    <h5 class="mb-0">Alterar Morada</h5>
                </div>
                <div class="card-body">
                    <form action="?a=alterar_morada_submit" method="post">
                        <div class="mb-3">
                            <label for="morada" class="form-label">Morada</label>
                            <input type="text" class="form-control" id="morada" name="morada" value="<?= $cliente->morada ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" value="<?= $cliente->cidade ?>" required>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">Alterar Morada</button>
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
        const morada = document.getElementById('morada').value;
        const cidade = document.getElementById('cidade').value;
        
        if (morada.trim() === '') {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: 'Por favor, preencha o campo Morada.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#6a329f',
                customClass: {
                    confirmButton: 'alert-button'
                }
            });
            return;
        }
        
        if (cidade.trim() === '') {
            e.preventDefault();
            Swal.fire({
                title: '',
                text: 'Por favor, preencha o campo Cidade.',
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