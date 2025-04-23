<?php
$servidor = "localhost"; // o IP del servidor, ej: 127.0.0.1
$usuario = "root";       // tu usuario de base de datos
$contrasena = "";        // tu contraseña (vacía si estás en local)
$basededatos = "nusidodb"; // el nombre de tu base de datos


$conexion = new mysqli($servidor, $usuario, $contrasena, $basededatos);


if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
} else {
   
}

// Establece charset a UTF-8 para evitar errores con tildes
$conexion->set_charset("utf8mb4");


?>