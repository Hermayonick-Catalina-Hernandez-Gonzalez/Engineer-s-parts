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
    $sql = "SELECT * FROM fotos WHERE nombre_producto LIKE :texto";
    $params = [':texto' => "%$texto_busqueda%"];
} else {
    $sql = "SELECT * FROM fotos"; // Mostrar todos los productos
    $params = [];
}

// Ejecutar la consulta SQL
$stmt = $connection->prepare($sql);
$stmt->execute($params);

// Si inicia verificando si la consulta regreso algo
if ($stmt->rowCount() > 0) {
    echo "<div class='grid-container'>";// Contenedor de la cuadrícula donde mostraré los productos encontrados
     // Recorrer cada fila de resultados para mostrar la información del producto
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // La ruta de la imagen del producto usando el secure_id y la extensión de la imagen desde la base de datos
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
