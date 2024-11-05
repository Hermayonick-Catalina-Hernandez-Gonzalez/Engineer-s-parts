<?php
// Conexión a la base de datos
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $publicacionId = $_POST['id'];

    try {
        // Actualizar el estado de la publicación en la tabla fotos
        $stmt = $connection->prepare("DELETE FROM fotos WHERE id = ?");

        $stmt->execute([$publicacionId]);

        echo 'success';
    } catch (PDOException $e) {
        // Capturar el error y mostrarlo
        echo 'error: ' . $e->getMessage();
    }
}
?>
