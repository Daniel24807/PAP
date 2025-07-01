                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Inicialização do DataTables para tabelas simples
        $(document).ready(function() {
            // Verifica se a tabela não tem ID específico antes de aplicar o DataTable genérico
            $('.table:not(#tabelaProdutos):not(#tabelaPedidos)').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/pt-BR.json'
                }
            });
        });

        // Função para confirmar exclusão
        function confirmarExclusao(url) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não poderá ser revertida!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        // Função para mostrar mensagem de sucesso
        function mostrarSucesso(mensagem) {
            Swal.fire({
                title: 'Sucesso!',
                text: mensagem,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Função para mostrar mensagem de erro
        function mostrarErro(mensagem) {
            Swal.fire({
                title: 'Erro!',
                text: mensagem,
                icon: 'error'
            });
        }
    </script>
</body>
</html>