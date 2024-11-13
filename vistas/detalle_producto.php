<?php
require "../php/connection.php";

// Obtener el ID del producto
$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($productId) {
    $sql = "SELECT * FROM fotos WHERE id = :id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($producto) {
        echo "<h2>Detalles del Producto</h2>";
        echo "<img src='../fotos/" . htmlspecialchars($producto['secure_id'] . "." . $producto['extension']) . "' alt='" . htmlspecialchars($producto['nombre_producto']) . "'>";
        echo "<div><strong>Producto:</strong> " . htmlspecialchars($producto['nombre_producto']) . "</div>";
        echo "<div><strong>Precio:</strong> $" . htmlspecialchars($producto['precio']) . "</div>";
        echo "<div><strong>Descripción:</strong> " . htmlspecialchars($producto['descripcion']) . "</div>";
        echo "<div><strong>Estado:</strong> " . htmlspecialchars($producto['estado']) . "</div>";
    } else {
        echo "Producto no encontrado.";
    }
} else {
    echo "ID de producto inválido.";
}
?>
