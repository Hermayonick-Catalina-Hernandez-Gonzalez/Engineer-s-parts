<?php
function autentificar($username, $password) {
    include("connection.php");  // Conexión a la base de datos

    try {
        // Verificar que el usuario exista
        $sqlCmd = "SELECT * FROM usuarios WHERE username = :username AND activo = 1";
        $stmt = $connection->prepare($sqlCmd);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra el usuario
        if (!$usuario) {
            error_log("No se encontró el usuario con username: $username");
            return false;
        }

        // Generar el hash de la contraseña ingresada para comparar
        $passwordMasSalt = $password . $usuario["password_salt"];
        $passwordEncrypted = hash("sha512", $passwordMasSalt);

        // Verificar si el hash de la contraseña ingresada coincide con el almacenado
        if ($usuario["password_encrypted"] !== $passwordEncrypted) {
            error_log("Contraseña no coincide.");
            return false;
        }

        // Retornar datos del usuario si las credenciales son correctas
        return [
            "id" => $usuario['id'],
            "username" => $usuario["username"],
            "email" => $usuario["email"],
            "nombre" => $usuario["nombre"],
            "apellidos" => $usuario["apellidos"],
            "fotoPerfil" => $usuario["foto_perfil"],
        ];
    } catch (PDOException $e) {
        error_log("Error en la autenticación: " . $e->getMessage());
        return false;
    }
}
?>
