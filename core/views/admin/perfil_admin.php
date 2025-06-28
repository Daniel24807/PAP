<?php
// Definir a aba ativa
$aba_ativa = isset($_GET['tab']) ? $_GET['tab'] : 'senha';

// Desativar temporariamente o Xdebug se necessário
if (function_exists('ini_set')) {
    ini_set('xdebug.mode', 'off');
    ini_set('xdebug.connect_timeout_ms', '0');
}
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-user-shield me-2"></i>Perfil do Administrador</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="?a=inicio">Início</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Perfil</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php
    // Apresentar eventuais erros ou mensagens de sucesso
    if (isset($_SESSION['erro'])) {
        echo '<div class="alert alert-danger mb-4"><i class="fas fa-exclamation-circle me-2"></i>' . $_SESSION['erro'] . '</div>';
        unset($_SESSION['erro']);
    }
    if (isset($_SESSION['sucesso'])) {
        echo '<div class="alert alert-success mb-4"><i class="fas fa-check-circle me-2"></i>' . $_SESSION['sucesso'] . '</div>';
        unset($_SESSION['sucesso']);
    }
    ?>
    
    <div class="row">
        <!-- Menu Lateral -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configurações</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="?a=perfil_admin&tab=senha" class="list-group-item list-group-item-action <?= $aba_ativa == 'senha' ? 'active' : '' ?>">
                        <i class="fas fa-key me-2"></i>Alterar Senha
                    </a>
                    <a href="?a=perfil_admin&tab=email" class="list-group-item list-group-item-action <?= $aba_ativa == 'email' ? 'active' : '' ?>">
                        <i class="fas fa-envelope me-2"></i>Alterar Email
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informações</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary text-white rounded p-3 me-3">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                        <div>
                            <h6 class="mb-1"><?= $admin->utilizador ?></h6>
                            <span class="badge bg-success">Administrador</span>
                        </div>
                    </div>
                    <div class="small text-muted">
                        <p><i class="fas fa-clock me-2"></i>Último acesso: <?= isset($admin->last_login) ? date('d/m/Y H:i', strtotime($admin->last_login) - 3600) : date('d/m/Y H:i', time() - 3600) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conteúdo Principal -->
        <div class="col-md-9">
            <?php if ($aba_ativa == 'senha'): ?>
                <!-- Alterar Senha -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Alterar Senha</h5>
                    </div>
                    <div class="card-body">
                        <form action="?a=atualizar_perfil_admin&tipo=senha" method="post" id="form_senha">
                            <div class="bg-light p-4 rounded mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="text_senha_atual" class="form-label">Senha Atual</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" name="text_senha_atual" id="text_senha_atual" class="form-control" 
                                                required minlength="6"
                                                placeholder="Digite sua senha atual">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="text_nova_senha" class="form-label">Nova Senha</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" name="text_nova_senha" id="text_nova_senha" class="form-control"
                                                required minlength="6"
                                                placeholder="Insira a nova senha">
                                        </div>
                                        <small class="text-muted">Mínimo 6 caracteres</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="text_confirmar_senha" class="form-label">Confirmar Nova Senha</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                            <input type="password" name="text_confirmar_senha" id="text_confirmar_senha" class="form-control"
                                                required minlength="6"
                                                placeholder="Confirme a nova senha">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Alterar Senha
                                    </button>
                                    <a href="?a=perfil_admin" class="btn btn-secondary ms-2">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($aba_ativa == 'email'): ?>
                <!-- Alterar Email -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Alterar Email</h5>
                    </div>
                    <div class="card-body">
                        <form action="?a=atualizar_perfil_admin&tipo=email" method="post" id="form_email">
                            <div class="bg-light p-4 rounded mb-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="text_utilizador" class="form-label">Novo Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input type="email" name="text_utilizador" id="text_utilizador" class="form-control" 
                                                value="<?= $admin->utilizador ?>" required
                                                placeholder="Digite seu novo email">
                                        </div>
                                        <small class="text-muted">Este email será usado para fazer login do admin</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="text_senha_atual" class="form-label">Senha Atual</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" name="text_senha_atual" id="text_senha_atual" class="form-control" 
                                                required minlength="6"
                                                placeholder="Digite a sua senha atual para confirmar">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Atualizar Email
                                    </button>
                                    <a href="?a=perfil_admin" class="btn btn-secondary ms-2">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Script para o formulário de alteração de senha
if (document.getElementById('form_senha')) {
    document.getElementById('form_senha').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevenir envio do formulário
        
        // Validar se as senhas novas coincidem
        let novaSenha = document.getElementById('text_nova_senha').value;
        let confirmarSenha = document.getElementById('text_confirmar_senha').value;
        
        if (novaSenha !== confirmarSenha) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'As senhas não coincidem!'
            });
            return;
        }

        // Confirmar alterações usando SweetAlert2
        Swal.fire({
            title: 'Confirmar alteração',
            text: 'Tem certeza que deseja alterar sua senha?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, alterar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar via AJAX em vez de submit normal
                let formData = new FormData(this);
                
                // Mostrar loading
                Swal.fire({
                    title: 'Processando...',
                    text: 'Alterando sua senha',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Fazer a requisição AJAX
                fetch('?a=atualizar_perfil_admin&tipo=senha', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Senha alterada com sucesso!'
                        }).then(() => {
                            window.location.href = '?a=perfil_admin';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Não foi possível alterar a senha. Tente novamente.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao processar sua solicitação.'
                    });
                });
            }
        });
    });
}

// Script para o formulário de alteração de email
if (document.getElementById('form_email')) {
    document.getElementById('form_email').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevenir envio do formulário
        
        // Confirmar alterações usando SweetAlert2
        Swal.fire({
            title: 'Confirmar alteração',
            text: 'Tem certeza que deseja alterar seu email?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, alterar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar via AJAX em vez de submit normal
                let formData = new FormData(this);
                
                // Mostrar loading
                Swal.fire({
                    title: 'Processando...',
                    text: 'Alterando seu email',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Fazer a requisição AJAX
                fetch('?a=atualizar_perfil_admin&tipo=email', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Email alterado com sucesso!'
                        }).then(() => {
                            window.location.href = '?a=perfil_admin';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Não foi possível alterar o email. Tente novamente.'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Ocorreu um erro ao processar sua solicitação.'
                    });
                });
            }
        });
    });
}
</script> 