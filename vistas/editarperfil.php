<?php
require "../php/connection.php";
require "../php/sesion_requerida.php";

// Obtener los datos del usuario actual
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $connection->prepare($sql);
$stmt->execute([$usuarioID]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Variables para precargar los datos en el formulario
$nombreUsuario = $result['username'] ?? '';
$correo = $result['email'] ?? '';
$fotoPerfil = $result['foto_perfil'] ?? null; // Foto de perfil actual

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Verificar si las contraseñas coinciden
    if ($password && $password !== $confirm_password) {
        echo "<script>alert('Las contraseñas no coinciden.');</script>";
    } else {
        // Procesar la actualización de la foto de perfil
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $extensiones_permitidas = ["jpg", "jpeg", "png", "gif"];

            if (in_array($extension, $extensiones_permitidas)) {
                $nuevoNombreArchivo = $usuarioID . "_" . $username . "." . $extension;
                $rutaDestino = "../fotos_perfil/" . $nuevoNombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    $fotoPerfil = $nuevoNombreArchivo;
                }
            } else {
                echo "<script>alert('Formato de archivo no permitido. Por favor, seleccione una imagen.');</script>";
            }
        }

        // Construir la consulta de actualización de usuario
        if ($password) {
            // Generar Salt y encriptar la nueva contraseña
            $passwordSalt = bin2hex(random_bytes(32));
            $passwordEncrypted = hash("sha512", $password . $passwordSalt);
            $sql = "UPDATE usuarios SET username = ?, email = ?, foto_perfil = ?, password_encrypted = ?, password_salt = ? WHERE id = ?";
            $params = [$username, $correo, $fotoPerfil, $passwordEncrypted, $passwordSalt, $usuarioID];
        } else {
            $sql = "UPDATE usuarios SET username = ?, email = ?, foto_perfil = ? WHERE id = ?";
            $params = [$username, $correo, $fotoPerfil, $usuarioID];
        }

        $stmt = $connection->prepare($sql);
        $stmt->execute($params);

        // Redirigir al perfil con un mensaje de éxito
        echo "<script>alert('Perfil actualizado correctamente.'); window.location.href = '../vistas/perfil.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylesditarperfil.css">
    <style>
        /* Estilo para hacer la imagen de perfil redonda y centrar el texto del botón */
        #preview {
            display: block;
            margin: 0 auto 10px auto;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #dedede;
        }

        /* Estilos para el campo de subir archivo */
        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
        }

        .file-upload label {
            cursor: pointer;
            background-color: #dedede;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            transition: background-color 0.3s ease;
            text-align: center;
            display: inline-block;
        }

        .file-upload label:hover {
            background-color: #a9a9a9;
        }

        .file-upload input[type="file"] {
            display: none;
        }
    </style>
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

    <div class="contenedor-editar">
        <div class="card">
            <h1>Editar Perfil</h1>
            <form class="ingresos" action="" method="post" enctype="multipart/form-data">
                <div class="file-upload">
                    <input type="file" name="imagen" id="imagen" accept="image/*" onchange="previewImage(event)">
                    <!-- Vista previa de la imagen en un círculo -->
                    <img id="preview" src="<?php echo '../fotos_perfil/' . htmlspecialchars($fotoPerfil); ?>" alt="Previsualización de imagen">
                    <label for="imagen">Cambia tu foto de perfil</label>
                </div>

                <label for="username">Nombre Usuario:</label>
                <input type="text" placeholder="Usuario..." name="username" id="username" value="<?php echo htmlspecialchars($nombreUsuario); ?>" required>

                <label for="correo">Correo electrónico:</label>
                <input type="email" placeholder="Correo electrónico..." id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required>

                <label for="password">Contraseña:</label>
                <input type="password" placeholder="Nueva contraseña..." id="password" name="password">

                <input type="password" placeholder="Confirmar contraseña..." name="confirm_password" id="confirm_password" oninput="checkPasswordMatch()">

                <div class="cont-btn">
                    <button type="button" class="salir" onclick="window.location.href = './perfil.php'">Salir</button>
                    <button type="submit" class="guardar">Modificar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        function checkPasswordMatch() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password");
            if (password === confirmPassword.value) {
                confirmPassword.style.backgroundColor = "#8db600"; // Verde cuando coinciden
            } else {
                confirmPassword.style.backgroundColor = "#FE3939"; // Rojo cuando no coinciden
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
