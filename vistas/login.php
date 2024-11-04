<?php
require "../php/login_helper.php";

session_start();

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, "nombre", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

    if (empty($username) || empty($password)) {
        $mensaje = "Por favor, complete todos los campos.";
    } else {
        $loggear = autentificar($username, $password);
        if (!$loggear) {
            $mensaje = "Usuario o contraseña incorrectos";
        } else {
            $_SESSION["id"] = $loggear["id"];
            $_SESSION["username"] = $loggear["username"];
            $_SESSION["email"] = $loggear["email"];
            $_SESSION["nombre"] = $loggear["nombre"];
            $_SESSION["apellidos"] = $loggear["apellidos"];
            $_SESSION["fotoPerfil"] = $loggear["fotoPerfil"];
            header("Location: ../index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesion</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylelogin.css">
    <script src="../js/mensajeError.js"></script>
</head>

<body>
    <div class="card">
        <div class="circ-img">
            <img src="../img/Logo.png" />
        </div>
        <h1>Engineer's parts</h1>
        <form class="ingresos" action="login.php" method="post">
            <?php if (!empty($mensaje)): ?>
                <p style="color: red;"><?= htmlspecialchars($mensaje) ?></p>
            <?php endif; ?>
            <input type="text" placeholder="Usuario..." name="nombre" id="nombre">
            <input type="password" placeholder="Contraseña..." name="password" id="password">
            <div class="cont-btn">
                <button type="submit">Iniciar Sesión</button>
            </div>
        </form>
        <p>¿Aún no tienes cuenta? <a href="registrarse.php">Regístrate</a></p>
    </div>
</body>


</html>