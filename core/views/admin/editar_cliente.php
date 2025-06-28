<?php
use core\classes\store;

if (!Store::adminLogado()) {
    Store::redirect('admin_login', true);
    return;
}
?>

<div class="card">
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
        <form action="?a=atualizar_cliente&id=<?= $cliente->id_cliente ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <!-- email -->
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="text_email" placeholder="Email" value="<?= $cliente->email ?>" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Nome Completo -->
                    <div class="form-group mb-3">
                        <label class="form-label">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="text_nome_completo" placeholder="Nome Completo" value="<?= $cliente->nome_completo ?>" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Morada -->
                    <div class="form-group mb-3">
                        <label class="form-label">Morada</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="text_morada" placeholder="Morada" value="<?= $cliente->morada ?>" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Cidade -->
                    <div class="form-group mb-3">
                        <label class="form-label">Cidade</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-city"></i>
                            </span>
                            <input type="text" name="text_cidade" placeholder="Cidade" value="<?= $cliente->cidade ?>" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- Telefone -->
                    <div class="form-group mb-3">
                        <label class="form-label">Telefone</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text" name="text_telefone" placeholder="Telefone" value="<?= $cliente->telefone ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Status -->
                    <div class="form-group mb-3">
                        <label class="form-label">Status</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-toggle-on"></i>
                            </span>
                            <select name="text_activo" class="form-select">
                                <option value="1" <?= $cliente->activo == 1 ? 'selected' : '' ?>>Ativo</option>
                                <option value="0" <?= $cliente->activo == 0 ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Atualizar Cliente
                </button>
            </div>

            <?php if (isset($_SESSION['erro'])) : ?>
                <script>
                    mostrarErro('<?= $_SESSION['erro'] ?>');
                </script>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>
        </form>
    </div>
</div> 