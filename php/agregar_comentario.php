<?php
require "./sesion_requerida.php";
require "./connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['foto_id'], $_POST['comentario'])) {
    $foto_id = $_POST['foto_id'];
    $comentario = trim($_POST['comentario']);
    $usuario_id = $_SESSION['id']; // Asegúrate de que el ID del usuario está en la sesión

    if (!empty($comentario)) {
        $sql = "INSERT INTO comentarios (foto_id, usuario_id, comentario) VALUES (:foto_id, :usuario_id, :comentario)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':foto_id', $foto_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':comentario', $comentario);
        $stmt->execute();
    }
}

// Redirigir de vuelta a la página de inicio después de agregar el comentario
header("Location: ../index.php");
exit;
?>