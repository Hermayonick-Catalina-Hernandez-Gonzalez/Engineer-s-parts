<?php
// Conexión a la base de datos
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $publicacionId = $_POST['id'];

    // Daniel Ledezma
    try {
        // Actualizar el estado de la publicación a "Vendido" en la tabla fotos
        $stmt = $connection->prepare("UPDATE fotos SET status = 'Vendido' WHERE id = ?");
        $stmt->execute([$publicacionId]);

        echo 'success';
    } catch (PDOException $e) {
        // Capturar el error y mostrarlo
        echo 'error: ' . $e->getMessage();
    }
}
?>
