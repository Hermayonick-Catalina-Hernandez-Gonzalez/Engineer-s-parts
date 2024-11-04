<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario que inició sesión
$usuario_id = $usuarioID;

// Obtener la información del usuario de la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario tiene una foto de perfil guardada
if (!empty($usuario['foto_perfil'])) {
  // Si el campo foto_perfil contiene el nombre de un archivo, cargar desde la carpeta fotos_perfil
  $imagen_usuario = "../fotos_perfil/" . $usuario['foto_perfil'];
} else {
  // Imagen predeterminada si no hay foto de perfil
  $imagen_usuario = "../img/default_perfil.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear Publicación</title>
  <link rel="icon" href="../img/Logo.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/stylesCrear.css">
</head>

<body>
  <header class="navbar">
    <div class="logo">
      <a href="../index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
        <img src="../img/Logo.png" alt="Logo">
        <span>Engineer's parts</span>
      </a>
    </div>
    <nav>
      <a href="../index.php"><span>Inicio</span></a>
      <a href="../vistas/buscador.html"><span>Buscador</span></a>
      <a href="../vistas/crear.php"><span>Crear</span></a>
    </nav>
    <div class="user-icon">
      <img src="../img/usuario.png" alt="Usuario" onclick="toggleDropdown()">
      <div class="dropdown-content" id="dropdownMenu">
        <a href="../vistas/perfil.php">Perfil</a>
        <a href="../vistas/editarperfil.php">Editar perfil</a>
        <a href="../php/logout.php">Cerrar sesión</a>
      </div>
    </div>
  </header>

  <div class="contenedor">
    <form action="#" id="formCrear" enctype="multipart/form-data" method="post">
      <div class="subir">
        <!-- Vista previa de la imagen -->
        <div class="subir-foto">
          <div class="foto-preview" onclick="document.getElementById('foto').click();">
            <img id="preview" src="#" alt="Previsualización de la imagen" style="display: none; width: 100px; height: auto; border-radius: 50%;">
          </div>
          <input type="file" id="foto" name="foto" accept="image/*" style="display: none;" onchange="previewImage(event)">
          <button type="button" id="seleccionar-foto">Seleccionar Foto</button>
        </div>

        <!-- Información del usuario y detalles del producto -->
        <div class="usuario-publicacion">
          <div class="usuario">
            <!-- Mostrar la foto de perfil del usuario conectado -->
            <img src="<?php echo htmlspecialchars($imagen_usuario); ?>" alt="Foto usuario" style="border-radius: 50%; width: 50px; height: 50px;">
            <span><?php echo htmlspecialchars($usuario['username']); ?></span>
          </div>
          <div class="estado">
            <label>Estado</label>
            <label><input type="radio" name="estado" value="nuevo"> Nuevo</label>
            <label><input type="radio" name="estado" value="seminuevo"> Seminuevo</label>
            <label><input type="radio" name="estado" value="usado"> Usado</label>
          </div>

          <div class="precio">
            <label>Precio</label>
            <input type="text" placeholder="$">
          </div>
          <textarea class="descripcion" placeholder="Descripción..."></textarea>
          <button class="publicar" type="submit">Publicar</button>
        </div>
      </div>
    </form>
  </div>

  <script src="../js/scriptCrear.js"></script>

  <script>
    function toggleDropdown() {
      document.getElementById("dropdownMenu").classList.toggle("show");
    }

    window.onclick = function(event) {
      if (!event.target.matches('.user-icon img')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
            openDropdown.classList.remove('show');
          }
        }
      }
    }

    function previewImage(event) {
      const reader = new FileReader();
      reader.onload = function() {
        const preview = document.getElementById("preview");
        preview.src = reader.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
</body>

</html>