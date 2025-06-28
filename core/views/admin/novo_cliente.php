<?php
use core\classes\Store;

if (!Store::adminLogado()) {
    Store::redirect('admin_login', true);
    return;
}
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-plus me-2"></i>
                Novo Cliente
            </h5>
            <a href="?a=clientes" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="?a=cliente_adicionar_submit" method="post" id="form_novo_cliente">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nome_completo" class="form-label">Nome Completo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nome_completo" id="nome_completo" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="morada" class="form-label">Morada</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                        <input type="text" name="morada" id="morada" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                        <input type="text" name="cidade" id="cidade" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="telefone" id="telefone" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="senha" class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="senha" id="senha" class="form-control" required minlength="6">
                    </div>
                    <small class="text-muted">Mínimo 6 caracteres</small>
                </div>
                <div class="col-md-6">
                    <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required minlength="6">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-user-shield me-2"></i>Status do Cliente</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch d-flex align-items-center">
                        <input class="form-check-input me-3" type="checkbox" name="activo" id="activo" 
                            style="width: 3em; height: 1.5em;" checked>
                        <div>
                            <label class="form-check-label fw-bold fs-5" for="activo">
                                Cliente <span class="text-success">Ativo</span>
                            </label>
                            <p class="text-muted mb-0 small">
                                O cliente pode acessar a loja e fazer compras.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Salvar Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form_novo_cliente');
    const statusCheckbox = document.getElementById('activo');
    const statusLabel = statusCheckbox.closest('.form-check').querySelector('.fw-bold span');
    const statusDescription = statusCheckbox.closest('.form-check').querySelector('.text-muted');
    
    // Função para atualizar o texto do status
    function atualizarStatusTexto() {
        if (statusCheckbox.checked) {
            statusLabel.textContent = 'Ativo';
            statusLabel.className = 'text-success';
            statusDescription.textContent = 'O cliente pode acessar a loja e fazer compras.';
        } else {
            statusLabel.textContent = 'Inativo';
            statusLabel.className = 'text-danger';
            statusDescription.textContent = 'O cliente não pode acessar a loja nem fazer compras.';
        }
    }
    
    // Adicionar evento para atualizar o texto quando o status mudar
    statusCheckbox.addEventListener('change', atualizarStatusTexto);
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar se as senhas coincidem
        const senha = document.getElementById('senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;
        
        if (senha !== confirmarSenha) {
            Swal.fire({
                title: 'Erro!',
                text: 'As senhas não coincidem',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
            return;
        }
        
        // Confirmar adição do cliente
        Swal.fire({
            title: 'Confirmar adição',
            text: 'Deseja adicionar este cliente?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, adicionar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script> 