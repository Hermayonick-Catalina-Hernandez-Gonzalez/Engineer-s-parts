<?php

session_start();

$usuarioAutenticado = false;
$usuarioID = NULL;
$usuario = NULL;
$email = NULL;
$nombre = NULL;
$apellidos = NULL;
$nombreCompleto = NULL;

if (isset($_SESSION["id"])) {
    $usuarioAutenticado = true;
    $usuarioID = $_SESSION["id"];
    $usuario = $_SESSION["username"];
    $email = $_SESSION["email"];
    $nombre = $_SESSION["nombre"];
    $apellidos = $_SESSION["apellidos"];
    $fotoPerfil = $_SESSION["fotoPerfil"];
    $nombreCompleto = $apellidos ? "$nombre $apellidos" : $nombre;
}

// Podemos obtener la dirección IP del client que realizó la petición usando
// las variables globales del server que están en el assoc array $_SERVER
$REQUEST_IP_ADDRESS = "";  // Aquí tendremos el valor de la IP del client
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $REQUEST_IP_ADDRESS = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    // Si se está usando un web server como reverse proxy, la dirección IP de
    // origen se obtiene aquí
    $REQUEST_IP_ADDRESS = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $REQUEST_IP_ADDRESS = $_SERVER['REMOTE_ADDR'];
}
