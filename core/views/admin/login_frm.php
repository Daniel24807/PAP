<div class="container">
    <div class="row my-5">
        <div class="col-sm-6 offset-sm-3 col-lg-4 offset-lg-4">
            <div class="card p-4 shadow">
                <div class="text-center mb-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h3>LOGIN DO ADMIN</h3>
                </div>

                <form action="?a=admin_login_submit" method="post">
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="text_utilizador" class="form-control" placeholder="Digite seu email" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Senha:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="text_password" class="form-control" placeholder="Digite sua senha" required>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </div>
                </form>

                <?php if (isset($_SESSION['login_erro'])): ?>
                    <div class="alert alert-danger text-center mt-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= $_SESSION['login_erro'] ?>
                        <?php unset($_SESSION['login_erro']) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['sucesso'])): ?>
                    <div class="alert alert-success text-center mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= $_SESSION['sucesso'] ?>
                        <?php unset($_SESSION['sucesso']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>