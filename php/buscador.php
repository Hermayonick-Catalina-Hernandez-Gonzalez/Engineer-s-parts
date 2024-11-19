<?php
require "../php/sesion_requerida.php"; // Verificar que el usuario haya iniciado sesión antes de continuar
require "../php/connection.php"; // Conexión con la base de datos

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['id'];

// Obtener el texto de búsqueda del cuerpo de la solicitud POST
$texto_busqueda = filter_input(INPUT_POST, "texto", FILTER_SANITIZE_STRING);

// Construir la consulta SQL, mostrando todos los productos si no hay texto de búsqueda
// Consulta SQL de búsqueda
// Se busca cualquier coincidencia en la columna "nombre_producto" de la tabla "fotos"
// utilizando el texto ingresado por el usuario como filtro
if (!empty($texto_busqueda)) {
    // Consulta a la vista 'fotos_v' con texto de búsqueda
    $sql = "SELECT * FROM fotos_v WHERE nombre_archivo LIKE :texto";
    $params = [':texto' => "%$texto_busqueda%"];
} else {
    // Consulta a la vista 'fotos_v' para mostrar todos los productos en venta
    $sql = "SELECT * FROM fotos_v";
    $params = [];
}

// Ejecutar la consulta
$stmt = $connection->prepare($sql);
$stmt->execute($params);

// Mostrar resultados
if ($stmt->rowCount() > 0) {
    echo "<div class='grid-container'>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagen_producto = "../fotos/" . $row['secure_id'] . "." . $row['extension'];
        $publicacion_url = "../vistas/publicaciones_especifica.php?id=" . $row['id'];

        echo "<div class='grid-item'>";
        echo "<a href='" . $publicacion_url . "'><img src='" . $imagen_producto . "' alt='" . htmlspecialchars($row['nombre_archivo']) . "'/></a>";
        echo "<div class='detalle'><strong>Producto:</strong> <a href='" . $publicacion_url . "'>" . htmlspecialchars($row['nombre_archivo']) . "</a></div>";
        echo "<div class='detalle'><strong>Precio:</strong> $" . htmlspecialchars($row['precio']) . "</div>";
        echo "<div class='detalle'><strong>Descripción:</strong> " . htmlspecialchars($row['descripcion']) . "</div>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Producto no encontrado.";
}

