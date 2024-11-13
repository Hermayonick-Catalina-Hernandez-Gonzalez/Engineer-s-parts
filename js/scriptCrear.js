// Mostrar una vista previa de la foto seleccionada
document.getElementById('foto').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const fotoPreview = document.querySelector('.foto-preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            fotoPreview.style.backgroundImage = `url('${e.target.result}')`;
            fotoPreview.style.backgroundSize = 'cover';
        };
        reader.readAsDataURL(file);
    } else {
        fotoPreview.style.backgroundImage = '';
    }
});

// Activar el clic en el botón de seleccionar foto
document.getElementById('seleccionar-foto').addEventListener('click', function () {
    document.getElementById('foto').click();
});

// Validar el envío del formulario y enviar datos mediante fetch
document.getElementById('formCrear').addEventListener("submit", function (e) {
    e.preventDefault();

    // Confirmar si el usuario desea publicar la foto
    if (!confirm("¿Quieres publicar esta foto?")) {
        return false;
    }

    const formData = new FormData(this);

    fetch('../php/guardar_archivo.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.errores && data.errores.length > 0) {
            alert("Errores: " + data.errores.join(", "));
        } else if (data.success) {
            alert(data.mensaje || "Registro guardado exitosamente");
            window.location.href = "../vistas/perfil.php"; // Redirigir después de la confirmación de éxito
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Error al registrar el producto. Intenta de nuevo.");
    });
});
