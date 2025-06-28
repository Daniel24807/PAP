<?php if (isset($_SESSION['login_sucesso'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: "Login realizado com sucesso!",
            icon: "success",
            draggable: false
        });
    </script>
    <?php unset($_SESSION['login_sucesso']); ?>
<?php endif; ?>

<div class="container">
    <div class="row my-5">
        <div class="col-sm-6 offset-sm-3">
            <h3 class="text-center">Confirmação do email!</h3>
            <p> Foi enviado um email com o link, para confirmação da sua
                Conta</p>
            <p> Verifique se o email aparece na sua Conta ou se foi para a
                pasta do SPAM</p>
            <div class="my-5"><a href="?a=inicio" class="btn btn-primary text-center">Voltar</a></div>
        </div>
    </div>
</div>