<?php
require "../php/registro_helper.php";

session_start();
$mensaje = "";

if ($_POST) {
    $email = filter_input(INPUT_POST, "correo", FILTER_VALIDATE_EMAIL);
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
    $confirm_password = filter_input(INPUT_POST, "confirm_password", FILTER_SANITIZE_STRING);

    // Ruta de la foto de perfil predeterminada
    $fotoPerfil = '../img/default_perfil.jpg';
    // Asegúrate de que las contraseñas coincidan
    if ($password != $confirm_password) {
        $mensaje = "Las contraseñas no coinciden.";
    } else {
        $registrar = registrar($email, $username, $password, $fotoPerfil);
        if ($registrar) {
            header('Location: ./login.php');
            exit();
        } else {
            $mensaje = "Ocurrió un error";
        }
    }
}

function esMayorDeEdad($fechaNacimiento)
{
    $fechaNacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($fechaNacimiento)->y;
    return $edad >= 18;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/styleregister.css">
    <script src="../js/validarEdad.js"></script>
</head>

<body>
    <div class="card">
        <div class="circ-img">
            <img src="../img/Logo.png" />
        </div>
        <h1>Engineer's parts</h1>
        <form class="ingresos" action="registrarse.php" method="post" enctype="multipart/form-data">
            <label id="error-label"><?php echo htmlspecialchars($mensaje); ?></label>
            <input type="text" placeholder="Username..." name="username" required>
            <input type="email" placeholder="Correo electrónico..." name="correo" required>
            <input type="password" placeholder="Contraseña..." name="password" required>
            <input type="password" placeholder="Confirmar contraseña..." name="confirm_password" required>
            <div class="cont-btn">
                <button type="submit" class="registrar">Registrarse</button>
                <button type="button" class="salir" onclick="window.location.href = 'login.php'">Regresar</button>
            </div>
        </form>

    </div>

</body>

</html>