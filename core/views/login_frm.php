<div class="container">
    <div class="row">
        <div class="col-sm-6 offset-sm-3"></div>
    </div>
</div>
<div class="container">
    <div class="row my-5">
        <div class="col-sm-4 offset-sm-4">
            <div>
                <h3 class="text-center">LOGIN</h3>

                <?php
                if (empty($_SESSION['csrf_token'])) {
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                }
                $csrf_token = $_SESSION['csrf_token'];
                ?>

                <form action="?a=login_submit" method="post">
                    <div class="my-3">
                        <label>Email:</label>
                        <input type="email" name="text_utilizador" id=""
                            placeholder="Email:" required class="form-control">
                    </div>
                    <div class="my-3">
                        <label>Password:</label>
                        <input type="password" name="text_password" id=""
                            placeholder="Password" required class="form-control">
                    </div>
                    <div class="my-3 text-center">
                        <input type="submit" value="Login" class="btn btn-primary ">
                    </div>
                    <div class="my-3 text-center">
                        Não tens conta criada?
                        <a href="?a=novo_cliente" class="text-center">Clica aqui</a>
                    </div>
                    
                </form>
                <?php if (isset($_SESSION['erro'])) : ?>
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
            </div>
        </div>
    </div>
</div>
