<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar si se recibió el formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_completo = isset($_POST['nombre_completo']) ? $_POST['nombre_completo'] : '';
    $correo = isset($_POST['correo']) ? $_POST['correo'] : '';
    $edad = isset($_POST['edad']) ? $_POST['edad'] : '';
    $lugar = isset($_POST['lugar']) ? $_POST['lugar'] : '';
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';

    if ($nombre_completo && $correo && $edad && $lugar && $direccion) {

        $conexion = new mysqli("localhost", "root", "", "resguardo");

        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        $stmt = $conexion->prepare("INSERT INTO registro (nombre_completo, correo, edad, lugar, direccion) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $nombre_completo, $correo, $edad, $lugar, $direccion);

        if ($stmt->execute()) {
            echo "<script>alert('Registro exitoso'); window.location.href='index.html';</script>";
        } else {
            echo "Error al registrar: " . $stmt->error;
        }

        $stmt->close();
        $conexion->close();
    } else {
        echo "Por favor completa todos los campos.";
    }
} else {
    echo "Acceso no permitido.";
}
?>
