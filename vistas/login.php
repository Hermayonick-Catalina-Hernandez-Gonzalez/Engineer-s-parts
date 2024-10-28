<?php
require "../php/login_helper.php";

session_start();

if($_POST) {
    $username = filter_input(INPUT_POST, "nombre");
    $password = filter_input(INPUT_POST, "password");
    
    $loggear = autentificar($username, $password);
    if(!$loggear) {
        $mensaje = "Usuario o contraseña incorrectos";
    } else {
        $_SESSION["id"] = $loggear["id"];
        $_SESSION["username"] = $loggear["username"];
        $_SESSION["email"] = $loggear["email"];
        $_SESSION["nombre"] = $loggear["nombre"];
        $_SESSION["apellidos"] = $loggear["apellidos"];
        $_SESSION["fotoPerfil"] = $loggear["rutaPerfil"];
        $_SESSION["rol"] = $loggear["rol"]; // Guardar el rol del usuario en la sesión
        header("Location: ../index.php");
        exit(); 
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
        <h1>UPVBOOKS</h1>
        <div class="circ-img">
            <img src="../img/Logo.png" />
        </div>
        <form class="ingresos" action="login.php" method="post">
            <p id="mensaje"><?php if(isset($mensaje)) echo $mensaje; ?></p>
            <label>Usuario:</label>
            <input type="text" placeholder="Usuario..." name="nombre" id="nombre">
            <label>Contraseña:</label>
            <input type="password" placeholder="Contraseña..." name="password" id="password">
            
            <div class="cont-btn">
                <button type="submit">Iniciar</button>
            </div>
        </form>
        <p>Aun no tienes cuenta? <a href="registrarse.php">Regístrate</a></p>
    </div>
</body>
</html>
