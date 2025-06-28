<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/fontawesome.min.js"></script>
<script src="assets/js/sweetalert2.all.min.js"></script>

<script>
    // Substituir o alerta nativo por um alerta personalizado
    window.originalAlert = window.alert;
    window.alert = function(message) {
        Swal.fire({
            title: '',
            text: message,
            icon: 'warning',
            confirmButtonText: 'OK',
            confirmButtonColor: '#6a329f',
            customClass: {
                confirmButton: 'alert-button'
            }
        });
    };
</script>
</body>
</html>