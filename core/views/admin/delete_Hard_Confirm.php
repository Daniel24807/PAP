<?php

use core\classes\store;
?>

<div class="container mt-5">
    <div class="row my-5">
        <div class="col-lg-8 offset-lg-2 col-sm-8 offset-sm-2">
            <div class="new-user-wraper">
                <p class="main-title">Confirmar Apagar Utilizador</p>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" value="<?= $cliente->email ?>" id="email" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="nome_completo" class="form-label">Nome Completo</label>
                    <input type="text" name="nome_completo" value="<?= $cliente->nome_completo ?>" id="nome_completo" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="morada" class="form-label">Morada</label>
                    <input type="text" name="morada" value="<?= $cliente->morada ?>" id="morada" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" name="cidade" value="<?= $cliente->cidade ?>" id="cidade" class="form-control" readonly>
                </div>

                <div class="mb-3 my-4 text-center">
                    <a href="admin?a=clientes">
                        <button type="btn-editar" class="btn-editar">Fechar</button>
                    </a>
                    &emsp;
                    <button class="btn-apagar" id="btn-apagar" data-bs-toggle="modal" data-bs-target="#confirmModal">Apagar Cliente</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tens a certeza que queres eliminar este cliente?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-apagar" data-bs-dismiss="modal">Cancelar</button>
                <a href="?a=cliente_apagar_Hard&id=<?= $cliente->id_cliente ?>">
                    <button class="btn btn-danger">Confirmar</button>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (necessário para o modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
