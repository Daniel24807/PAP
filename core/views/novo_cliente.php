<div class="container py-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="text-center mb-4">Registar novo cliente</h3>
            <form action="?a=criar_cliente" method="post">
                <!-- Email -->
                <div class="mb-3">
                    <label for="text_email" class="form-label">Email</label>
                    <input type="email" name="text_email" id="text_email" class="form-control" required>
                </div>
                
                <!-- Senha -->
                <div class="mb-3">
                    <label for="text_senha_1" class="form-label">Senha</label>
                    <input type="password" name="text_senha_1" id="text_senha_1" class="form-control" required>
                </div>
                
                <!-- Confirmar Senha -->
                <div class="mb-3">
                    <label for="text_senha_2" class="form-label">Confirmar Senha</label>
                    <input type="password" name="text_senha_2" id="text_senha_2" class="form-control" required>
                </div>
                
                <!-- Nome Completo -->
                <div class="mb-3">
                    <label for="text_nome_completo" class="form-label">Nome Completo</label>
                    <input type="text" name="text_nome_completo" id="text_nome_completo" class="form-control" required>
                </div>
                
                <!-- Morada -->
                <div class="mb-3">
                    <label for="text_morada" class="form-label">Morada</label>
                    <input type="text" name="text_morada" id="text_morada" class="form-control" required>
                </div>
                
                <!-- Cidade -->
                <div class="mb-3">
                    <label for="text_cidade" class="form-label">Cidade</label>
                    <input type="text" name="text_cidade" id="text_cidade" class="form-control" required>
                </div>
                
                <!-- Telefone -->
                <div class="mb-4">
                    <label for="text_telefone" class="form-label">Telefone</label>
                    <input type="text" name="text_telefone" id="text_telefone" class="form-control" required>
                </div>
                
                <!-- Submit -->
                <div class="mb-3">
                    <input type="submit" value="Criar Conta" class="btn btn-primary w-100">
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.container {
    margin-bottom: 6rem !important;
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
</style>

<?php
if (isset($_SESSION['erro'])) : ?>
    <div class="alert alert-danger text-center p-2" role="alert">
        <?= $_SESSION['erro'] ?>
        <?php unset($_SESSION['erro']); ?>
    </div>
<?php endif;
?>