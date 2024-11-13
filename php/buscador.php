<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['id'];

// Obtener el texto de búsqueda del cuerpo de la solicitud POST
$texto_busqueda = filter_input(INPUT_POST, "texto", FILTER_SANITIZE_STRING);

// Construir la consulta SQL, mostrando todos los productos si no hay texto de búsqueda
if (!empty($texto_busqueda)) {
    $sql = "SELECT * FROM fotos WHERE nombre_producto LIKE :texto";
    $params = [':texto' => "%$texto_busqueda%"];
} else {
    $sql = "SELECT * FROM fotos"; // Mostrar todos los productos
    $params = [];
}

// Ejecutar la consulta SQL
$stmt = $connection->prepare($sql);
$stmt->execute($params);

if ($stmt->rowCount() > 0) {
    echo "<div class='grid-container'>"; // Inicia el contenedor de la cuadrícula
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $imagen_producto = "../fotos/" . $row['secure_id'] . "." . $row['extension'];
        $publicacion_url = "../vistas/publicaciones_especifica.php?id=" . $row['id'];

        echo "<div class='grid-item'>"; // Inicia un elemento de cuadrícula

        // Enlace en la imagen
        echo "<a href='" . $publicacion_url . "'><img src='" . $imagen_producto . "' alt='" . htmlspecialchars($row['nombre_producto']) . "'/></a>";
        
        // Enlace en el nombre del producto
        echo "<div class='detalle'><strong>Producto:</strong> <a href='" . $publicacion_url . "'>" . htmlspecialchars($row['nombre_producto']) . "</a></div>";
        
        // Detalles adicionales (sin enlace)
        echo "<div class='detalle'><strong>Precio:</strong> $" . htmlspecialchars($row['precio']) . "</div>";
        echo "<div class='detalle'><strong>Descripción:</strong> " . htmlspecialchars($row['descripcion']) . "</div>";
        echo "<div class='detalle'><strong>Estado:</strong> " . htmlspecialchars($row['estado']) . "</div>";
        
        echo "</div>"; // Cierra el elemento de cuadrícula
    }
    echo "</div>"; // Cierra el contenedor de la cuadrícula
} else {
    echo "Producto no encontrado.";
}
?>
