<?php
require "../php/sesion_requerida.php";
require "../php/connection.php";
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
// Consulta para obtener todos los productos con el estado "Vendido"
$sql = "SELECT * FROM fotos WHERE status = 'Vendido' AND eliminado = 0";
$stmt = $connection->prepare($sql);
$stmt->execute();
$productosVendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario - Productos Vendidos</title>
    <link rel="icon" href="../img/Logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/stylesPerfil.css">
    <link rel="stylesheet" href="../css/stylesInventario.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
<body>
    <header class="navbar">
        <div class="logo">
            <a href="../index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="../img/Logo.png" alt="Logo">
                <span>Engineer's parts</span>
            </a>
        </div>
        <nav>
            <a href="../index.php"><span>Inicio</span></a>
            <a href="./buscador.html"><span>Buscador</span></a>
            <a href="./crear.php"><span>Crear</span></a>
            <a href="./inventario.php"><span>Inventario</span></a>
        </nav>
        <div class="user-icon">
            <img src="../img/usuario.png" alt="Usuario" onclick="toggleDropdown()">
            <div class="dropdown-content" id="dropdownMenu">
                <a href="./perfil.php">Perfil</a>
                <a href="./editarperfil.php">Editar perfil</a>
                <a href="../php/logout.php">Cerrar sesión</a>
            </div>
        </div>
    </header>
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
    <div class="tabla-contenedor">
        <h2>Inventario - Productos Vendidos</h2>
        
        <table id="productosVendidosTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Fecha de Publicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productosVendidos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                        <td><?= htmlspecialchars($producto['precio']) ?></td>
                        <td><?= htmlspecialchars($producto['fecha_subido']) ?></td>
                        <td>
                            <span class="accion eliminar" data-id="<?= $producto['id'] ?>">Eliminar</span> | 
                            <span class="accion devolver" data-id="<?= $producto['id'] ?>">Devolver</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
    <script>
        $(document).ready(function() {
            $('#productosVendidosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Spanish.json"
                },
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                "pageLength": 10
            });
        });
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
        // Función para eliminar una publicación
        $(document).on('click', '.eliminar', function() {
            var id = $(this).data('id');
            if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
                $.ajax({
                    url: '../php/eliminar_producto.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response === 'success') {
                            alert('Producto eliminado correctamente');
                            location.reload();
                        } else {
                            alert('Error al eliminar el producto');
                        }
                    }
                });
            }
        });
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
        // Función para devolver una publicación a "En venta"
        $(document).on('click', '.devolver', function() {
            var id = $(this).data('id');
            if (confirm('¿Estás seguro de que deseas devolver este producto a En venta?')) {
                $.ajax({
                    url: '../php/devolver_producto.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response === 'success') {
                            alert('Producto devuelto a En venta');
                            location.reload();
                        } else {
                            alert('Error al devolver el producto');
                        }
                    }
                });
            }
        });
<<<<<<< HEAD

=======
>>>>>>> 11b1403 (Todo junto)
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.user-icon img')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 11b1403 (Todo junto)
