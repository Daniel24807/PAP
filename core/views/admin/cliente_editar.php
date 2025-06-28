<?php
use core\classes\Store;

if (!Store::adminLogado()) {
    Store::redirect('admin_login', true);
    return;
}

// Verificar se existe cliente
if (!isset($cliente) || empty($cliente)) {
    $_SESSION['erro'] = 'Cliente não encontrado!';
    Store::redirect('clientes', true);
    return;
}
?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-user-edit me-2"></i>
                Editar Cliente
            </h5>
            <a href="?a=clientes" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Voltar
            </a>
        </div>
    </div>
    <div class="card-body">
        <form action="?a=cliente_editar_submit" method="post" id="form_editar_cliente">
            <input type="hidden" name="id_cliente" value="<?= $cliente->id_cliente ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nome_completo" class="form-label">Nome Completo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nome_completo" id="nome_completo" class="form-control" 
                            value="<?= $cliente->nome_completo ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" 
                            value="<?= $cliente->email ?>" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="morada" class="form-label">Morada</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                        <input type="text" name="morada" id="morada" class="form-control" 
                            value="<?= $cliente->morada ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                        <input type="text" name="cidade" id="cidade" class="form-control" 
                            value="<?= $cliente->cidade ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="telefone" id="telefone" class="form-control" 
                            value="<?= $cliente->telefone ?>">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-light">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="alterar_senha" id="alterar_senha">
                        <label class="form-check-label" for="alterar_senha">
                            Alterar senha do cliente
                        </label>
                    </div>
                </div>
                <div class="card-body senha-container" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="senha" id="senha" class="form-control" 
                                    minlength="6" disabled>
                            </div>
                            <small class="text-muted">Mínimo 6 caracteres</small>
                        </div>
                        <div class="col-md-6">
                            <label for="confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="confirmar_senha" id="confirmar_senha" 
                                    class="form-control" minlength="6" disabled>
                            </div>
                        </div>
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
                            style="width: 3em; height: 1.5em;"
                            <?= $cliente->activo ? 'checked' : '' ?>>
                        <div>
                            <label class="form-check-label fw-bold fs-5" for="activo">
                                Cliente <?= $cliente->activo ? '<span class="text-success">Ativo</span>' : '<span class="text-danger">Inativo</span>' ?>
                            </label>
                            <p class="text-muted mb-0 small">
                                <?= $cliente->activo ? 
                                    'O cliente pode acessar a loja e fazer compras.' : 
                                    'O cliente não pode acessar a loja nem fazer compras.' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form_editar_cliente');
    const alterarSenhaCheckbox = document.getElementById('alterar_senha');
    const senhaContainer = document.querySelector('.senha-container');
    const senhaInput = document.getElementById('senha');
    const confirmarSenhaInput = document.getElementById('confirmar_senha');
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
    
    // Função para habilitar/desabilitar o formulário de senha
    alterarSenhaCheckbox.addEventListener('change', function() {
        senhaContainer.style.display = this.checked ? 'block' : 'none';
        senhaInput.disabled = !this.checked;
        confirmarSenhaInput.disabled = !this.checked;
        
        if (this.checked) {
            senhaInput.setAttribute('required', 'required');
            confirmarSenhaInput.setAttribute('required', 'required');
        } else {
            senhaInput.removeAttribute('required');
            confirmarSenhaInput.removeAttribute('required');
        }
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar se as senhas coincidem quando a opção de alterar senha está marcada
        if (alterarSenhaCheckbox.checked) {
            const senha = senhaInput.value;
            const confirmarSenha = confirmarSenhaInput.value;
            
            if (senha !== confirmarSenha) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'As senhas não coincidem',
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }
        }
        
        // Confirmar edição do cliente
        Swal.fire({
            title: 'Confirmar alterações',
            text: 'Deseja salvar as alterações deste cliente?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, salvar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script> 