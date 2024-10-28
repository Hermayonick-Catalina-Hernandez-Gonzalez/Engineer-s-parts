<?php
$nombreServidor = "localhost";
$userBD = "root";
$password = "";
$nombreBD = "Eparts";

try {
    $connection = new PDO("mysql:host=$nombreServidor;dbname=$nombreBD;charset=utf8", $userBD, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Redirigir a una página de error en caso de que la conexión falle
    header('Location: ../vistas/servidorNotConnected.html');
    exit;
}
?>
