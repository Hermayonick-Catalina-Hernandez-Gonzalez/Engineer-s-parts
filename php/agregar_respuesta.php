<?php
require "./connection.php";
require "./sesion_requerida.php";

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario_id'], $_POST['respuesta'])) {
    // Obtener los datos del formulario
    $comentario_id = $_POST['comentario_id'];
    $respuesta = trim($_POST['respuesta']);
    $usuario_id = $_SESSION['id']; // Asegúrate de que el ID del usuario está en la sesión

    // Validar que la respuesta no esté vacía
    if (!empty($respuesta)) {
        // Preparar la consulta para insertar la respuesta en la base de datos
        $sql = "INSERT INTO respuestas (comentario_id, usuario_id, respuesta) VALUES (:comentario_id, :usuario_id, :respuesta)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':comentario_id', $comentario_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':respuesta', $respuesta);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir de regreso a la página principal o a la publicación
            header("Location: ../index.php");
            exit;
        } else {
            echo "Error al guardar la respuesta.";
        }
    } else {
        echo "La respuesta no puede estar vacía.";
    }
} else {
    echo "Datos inválidos.";
}
?>
