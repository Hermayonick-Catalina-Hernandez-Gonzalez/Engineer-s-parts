<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['id'];

// Obtener el texto de búsqueda del cuerpo de la solicitud POST
$texto_busqueda = filter_input(INPUT_POST, "texto");

// Inicializar la consulta SQL y los parámetros de vinculación
$sql = "SELECT * FROM usuarios WHERE (username LIKE :texto OR nombre LIKE :texto) AND id != :usuario_id";
$params = [
    ':texto' => "%$texto_busqueda%", // Agregar '%' para buscar coincidencias parciales
    ':usuario_id' => $usuario_id
];

// Ejecutar la consulta SQL
$stmt = $connection->prepare($sql);
$stmt->execute($params);

if ($stmt->rowCount() > 0) {
    // Generar el HTML de los resultados
    echo "<ul>"; // Inicia la lista
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li class='perfil-usuario'>"; // Inicia un elemento de lista

        // Mostrar la imagen de perfil si existe
        if (!empty($row["foto_perfil"])) {
            // Suponiendo que el nombre del archivo de imagen de perfil está almacenado en la base de datos
            $imagen_usuario = "../fotos_perfil/" . $row['foto_perfil'];
            echo "<img src='$imagen_usuario' alt='Foto de Usuario' class='foto-usuario'>";
        } else {
            // Imagen predeterminada si no hay foto de perfil
            echo "<img src='../img/default_perfil.jpg' alt='Foto de Usuario' class='foto-usuario'>";
        }

        // Enlace para abrir el perfil en la misma ventana
        echo "<a href='perfilBuscado.php?usuario_id=" . $row['id'] . "'>";
        echo "<span class='nombre-usuario'>" . htmlspecialchars($row['username']) . "</span>";
        echo "</a>";
        echo "</li>"; // Cierra el elemento de lista
    }
    echo "</ul>"; // Cierra la lista
} else {
    echo "Perfil no encontrado.";
}
?>
