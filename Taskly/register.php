<?php
// Archivo: register.php

session_start();

// Importamos la conexión
require_once __DIR__ . '/Conexion.php';

// Verificar que venga desde formulario POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Obtener y limpiar datos
$nombre = trim($_POST['nombre'] ?? '');
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validaciones básicas
if (
    empty($nombre) ||
    empty($username) ||
    empty($email) ||
    empty($password)
) {
    die("Todos los campos son obligatorios.");
}

// Validar correo
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Correo inválido.");
}

try {

    // Crear conexión
    $database = new Conexion();
    $conn = $database->conectar();
    // Verificar si el correo ya existe
    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        die("El correo ya está registrado.");
    }

    // Encriptar contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $query = "INSERT INTO users
    (nombre, username, email, password, rol)
    VALUES
    (:nombre, :username, :email, :password, 'usuario')";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    // Ejecutar
    if ($stmt->execute()) {

        $_SESSION['register_success'] = "Cuenta creada correctamente.";

        header("Location: login.php");
        exit();

    } else {

        echo "Error al registrar usuario.";

    }

} catch (PDOException $e) {

    echo "Error de base de datos: " . $e->getMessage();

}
?>