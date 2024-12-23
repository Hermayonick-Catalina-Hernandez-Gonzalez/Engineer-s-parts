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

if (!empty($usuario['foto_perfil'])) {
    // Convertir el BLOB en base64 si `foto_perfil` es un campo BLOB
    $imagen_usuario = "../fotos_perfil/" . htmlspecialchars($usuario['foto_perfil']);
} else {
    // Imagen predeterminada si no hay foto de perfil
    $imagen_usuario = "./fotos_perfil/perfil.png";
}

// Consulta para obtener el número de publicaciones
$sql_publicaciones = "SELECT COUNT(*) AS num_publicaciones FROM fotos WHERE usuario_subio_id = ? AND eliminado = 0";
$stmt_publicaciones = $connection->prepare($sql_publicaciones);
$stmt_publicaciones->execute([$usuario_id]);
$resultado_publicaciones = $stmt_publicaciones->fetch(PDO::FETCH_ASSOC);
$num_publicaciones = $resultado_publicaciones['num_publicaciones'];


// Consulta para obtener las publicaciones del usuario
$sql_publicaciones_usuario = "SELECT * FROM fotos WHERE usuario_subio_id = ? AND eliminado = 0";
$stmt_publicaciones_usuario = $connection->prepare($sql_publicaciones_usuario);
$stmt_publicaciones_usuario->execute([$usuario_id]);
$publicaciones_usuario = $stmt_publicaciones_usuario->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylesPerfil.css">
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
            <a href="../index.php">Inicio</a>
            <a href="./buscador.html">Buscador</a>
            <a href="./crear.php">Crear</a>
            <a href="../vistas/inventario.php"><span>Inventario</span></a>
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
        <div class="perfil">
            <div class="foto-usuario">
                <!-- Usar la variable $imagen_usuario para mostrar la imagen dinámica -->
                <img src="<?php echo htmlspecialchars($imagen_usuario); ?>" alt="Foto de Usuario">
            </div>

            <div class="info-usuario">
                <div class="nombre-usuario"><?php echo htmlspecialchars($usuario['username']); ?></div>
                <button class="editar-perfil"><a href="./editarperfil.php">Editar</a></button>
            </div>

            <div class="datos-usuario">
                <span class="informacion-detallada"><?php echo $num_publicaciones; ?> publicaciones</span>
            </div>

            <div class="galeria">
                <?php foreach ($publicaciones_usuario as $publicacion) : ?>
                    <div class="publicacion">
                        <img src="../fotos/<?php echo htmlspecialchars($publicacion['secure_id'] . "." . $publicacion['extension']); ?>" alt="Publicación">
                        <!-- Daniel Ledezma -->
                        <span class="estado boton-vendido" data-id="<?php echo htmlspecialchars($publicacion['id']); ?>">
                            <?php echo htmlspecialchars($publicacion['status'] === 'Vendido' ? 'Vendido' : 'En venta'); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

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
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Daniel Ledezma
        $(document).on('click', '.boton-vendido', function() {
            var publicacionId = $(this).data('id');
            var botonVendido = $(this);

            // Verificar si el estado ya es "Vendido"
            if (botonVendido.text().trim() === 'Vendido') {
                alert("Esta publicación ya ha sido vendida.");
                return; // No permite realizar ninguna acción si ya está vendida
            }
            // Mostrar una alerta de confirmación antes de marcar como vendido
            var confirmacion = confirm("¿Deseas marcar como vendido?");
            if (confirmacion) {
                // Si el usuario selecciona "Sí", procede con la solicitud AJAX
                $.ajax({
                    url: '../php/marcar_vendido.php',
                    type: 'POST',
                    data: {
                        id: publicacionId
                    },
                    success: function(response) {
                        if (response === 'success') {
                            // Refrescar la página después de marcar como vendido
                            location.reload();
                        } else {
                            alert('Error al marcar como vendido: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error de AJAX: ' + error);
                    }
                });
            }
        });
    </script>

</body>

</html>