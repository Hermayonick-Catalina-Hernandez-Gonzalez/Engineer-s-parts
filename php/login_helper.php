<?php
function autentificar($username, $password) {
    include("connection.php");  // Conexión a la base de datos

    // Verificar que se hayan recibido los datos
    if (!$username) {
        if(!$password){
            error_log("Falta usuario y contraseña.");
            return false;
        }else{
            error_log("Faltan datos de usuario.");
            return false;
        }
    }

    // Consulta para obtener los datos del usuario
    $sqlCmd = "SELECT * FROM usuarios WHERE username = ?";
    $sqlParams = [$username];

    $stmt = $connection->prepare($sqlCmd);
    $stmt->execute($sqlParams);
    $result = $stmt->fetchAll();  // Obtener el resultado de la consulta

    // Si no existe el usuario, retorna false
    if (!$result) {
        error_log("No se encontró el usuario con username: $username");
        return false;
    }

    $usuario = $result[0];  // Tomar el primer resultado (usuario)

    // Verificar la contraseña con el salt
    $passwordMasSalt = $password . $usuario["password_salt"];  // Concatenar el salt con la contraseña
    $passwordEncrypted = hash("sha512", $passwordMasSalt);  // Generar el hash de la contraseña

    // Debugging: Verificar el hash generado
    error_log("Hash generado: " . $passwordEncrypted);
    error_log("Hash almacenado en la base de datos: " . $usuario["password_encrypted"]);

    // Compara la contraseña ingresada con la almacenada
    if ($usuario["password_encrypted"] !== $passwordEncrypted) {
        error_log("Contraseña no coincide.");
        return false;
    }

    // Si todo es correcto, retorna los datos del usuario
    return [
        "id" => $usuario['id'],
        "username" => $usuario["username"],
        "email" => $usuario["email"],
        "nombre" => $usuario["nombre"],
        "apellidos" => $usuario["apellidos"],
    ];
}

