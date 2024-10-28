<?php

require "sesion.php";

if(!$usuarioAutenticado) {
    header("Location: ./vistas/login.php");
    exit();
}

?>