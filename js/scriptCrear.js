document.getElementById('foto').addEventListener('change', function (event) {
  const file = event.target.files[0];
  const fotoPreview = document.querySelector('.foto-preview');
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      fotoPreview.style.backgroundImage = `url(${e.target.result})`;
      fotoPreview.style.backgroundSize = 'cover';
    };
    reader.readAsDataURL(file);
  } else {
    fotoPreview.style.backgroundImage = '';
  }
});

document.getElementById('seleccionar-foto').addEventListener('click', function () {
  document.getElementById('foto').click();
});

document.getElementById('formCrear').addEventListener("submit", function (e) {
  e.preventDefault();

  if (!confirm("¿Quieres publicar esta foto?")) {
    return false;
  }

  var formData = new FormData(this);

  $.ajax({
    type: "post",
    url: "../php/guardar_archivo.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      window.location.href = "../vistas/perfil.php";
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error('Error:', textStatus);
    }
  });
});