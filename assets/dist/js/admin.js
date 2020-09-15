
function toastup(status = "failed", message = "Gagal menyimpan", timer = 1500) {
    Swal.fire({
        position: 'top-end',
        icon: status,
        title: message,
        showConfirmButton: false,
        timer: timer
    });
}