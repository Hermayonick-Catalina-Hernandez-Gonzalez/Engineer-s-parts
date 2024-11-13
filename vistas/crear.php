<?php
// Requiere una sesión activa y conexión a la base de datos
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario que inició sesión
$usuario_id = $usuarioID;

// Consulta para obtener la información del usuario desde la base de datos
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$usuario_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario tiene una foto de perfil
if (!empty($usuario['foto_perfil'])) {
    // Si `foto_perfil` es un campo BLOB, usa la ruta de la imagen
    $imagen_usuario = "../fotos_perfil/" . htmlspecialchars($usuario['foto_perfil']);
} else {
    // Imagen predeterminada en caso de que no tenga foto de perfil
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
            <img src="../img/Logo.png" alt="Logo">
            <span>Engineer's parts</span>
        </div>
        <nav>
            <!-- Navegación -->
            <a href="../index.php"><span>Inicio</span></a>
            <a href="../vistas/buscador.html"><span>Buscador</span></a>
            <a href="../vistas/crear.php"><span>Crear</span></a>
            <a href="../vistas/inventario.php"><span>Inventario</span></a>
        </nav>
        <!-- Icono de usuario con menú desplegable -->
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
        <form action="../php/guardar_archivo.php" id="formCrear" enctype="multipart/form-data" method="post">
            <div class="subir">
                <div class="subir-foto">
                    <input type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                    <div class="foto-preview" onclick="document.getElementById('foto').click();"></div>
                    <button type="button" id="seleccionar-foto">Seleccionar foto</button>
                </div>

                <div class="usuario-publicacion">
                    <div class="usuario">
                        <!-- Muestra la foto de perfil y el nombre de usuario -->
                        <img src="<?php echo $imagen_usuario; ?>" alt="Foto usuario">
                        <span><?php echo htmlspecialchars($usuario['username']); ?></span>
                    </div>

                    <!-- Campo para el nombre del producto -->
                    <div class="nombre input">
                        <label>Nombre del Producto</label>
                        <input type="text" name="nombre_producto" placeholder="Nombre del Producto" required>
                    </div>

                    <!-- Selección de estado del producto -->
                    <div class="estado">
                        <label>Estado</label>
                        <label><input type="radio" name="estado" value="nuevo" required> Nuevo</label>
                        <label><input type="radio" name="estado" value="seminuevo"> Seminuevo</label>
                        <label><input type="radio" name="estado" value="usado"> Usado</label>
                    </div>

                    <!-- Campo de precio -->
                    <div class="precio">
                        <label>Precio</label>
                        <input type="text" name="precio" placeholder="$" required>
                    </div>

                    <!-- Descripción del producto -->
                    <textarea class="descripcion" name="descripcion" placeholder="Descripción..."></textarea>
                    <button class="publicar" type="submit">Publicar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Script para el menú desplegable -->
    <script src="../js/scriptCrear.js"></script>
    <script>
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }
        // Cerrar el menú desplegable al hacer clic fuera de él
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
    </script>

</body>

</html>
