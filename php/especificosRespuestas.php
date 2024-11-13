<?php
require "./connection.php";
require "./sesion_requerida.php";

// Verificar si se han recibido los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['comentario_id'], $_POST['respuesta'], $_POST['foto_id'])) {
        echo "Datos inválidos: No se recibieron los campos necesarios.";
        exit;
    }

    $comentario_id = $_POST['comentario_id'];
    $respuesta = trim($_POST['respuesta']);
    $usuario_id = $_SESSION['id'];
    $foto_id = $_POST['foto_id'];

    // Validar que la respuesta no esté vacía
    if (!empty($respuesta)) {
        // Preparar la consulta para insertar la respuesta en la base de datos
        $sql = "INSERT INTO respuestas (comentario_id, usuario_id, respuesta) VALUES (:comentario_id, :usuario_id, :respuesta)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':comentario_id', $comentario_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':respuesta', $respuesta);

        if ($stmt->execute()) {
            // Redirigir de vuelta a la página de detalles de la publicación
            header("Location: ../vistas/publicaciones_especifica.php?id=$foto_id");
            exit();
        } else {
            echo "Error al guardar la respuesta.";
        }
    } else {
        echo "La respuesta no puede estar vacía.";
    }
} else {
    echo "Método de solicitud no válido.";
}
