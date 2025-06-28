<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Login</h3>
                    
                    <?php
                    if (empty($_SESSION['csrf_token'])) {
                        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    }
                    $csrf_token = $_SESSION['csrf_token'];
                    ?>

                    <form action="?a=login_submit" method="post">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="text_utilizador" class="form-label">Email</label>
                            <input type="email" name="text_utilizador" id="text_utilizador" class="form-control" required>
                        </div>
                        
                        <!-- Senha -->
                        <div class="mb-4">
                            <label for="text_password" class="form-label">Senha</label>
                            <input type="password" name="text_password" id="text_password" class="form-control" required>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary w-100">Entrar</button>
                        </div>

                        <!-- Links -->
                        <div class="text-center">
                            <a href="?a=recuperar_senha" class="text-decoration-none">Esqueceu sua senha?</a>
                            <div class="mt-3">
                                Não tem uma conta? <a href="?a=novo_cliente" class="text-decoration-none">Registe-se</a>
                            </div>
                        </div>
                    </form>

                    <?php if(isset($_SESSION['erro'])): ?>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '<?= htmlspecialchars($_SESSION['erro']) ?>',
                            });
                        </script>
                        <?php unset($_SESSION['erro']) ?>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['sucesso'])): ?>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: '<?= htmlspecialchars($_SESSION['sucesso']) ?>',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        </script>
                        <?php unset($_SESSION['sucesso']) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    border-radius: 10px;
}

.btn-primary {
    background-color: #2c3e50;
    border: none;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #3498db;
    transform: translateY(-2px);
}

.form-control {
    border-radius: 5px;
    padding: 10px 15px;
}

.form-label {
    color: #2c3e50;
    font-weight: 500;
}

a {
    color: #3498db;
}

a:hover {
    color: #2c3e50;
}
</style> 