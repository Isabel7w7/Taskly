<?php

session_start();

require_once __DIR__ . '/Conexion.php';

if (!isset($_SESSION['taskly_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: perfil.php");
    exit();
}

// Obtener datos
$id = $_SESSION['taskly_user']['id'];

$nombre = trim($_POST['nombre'] ?? '');
$rol = trim($_POST['rol'] ?? '');

if (empty($nombre) || empty($rol)) {
    die("Todos los campos son obligatorios.");
}

try {

    $database = new Conexion();
    $conn = $database->conectar();

    // UPDATE
    $query = "UPDATE users
          SET nombre = :nombre,
              rol = :rol
          WHERE id = :id";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {

        // Actualizar también la sesión
        $_SESSION['taskly_user']['nombre'] = $nombre;
        $_SESSION['taskly_user']['rol'] = $rol;

        header("Location: perfil.php?success=1");
        exit();
    } else {

        echo "Error al actualizar.";
    }
} catch (PDOException $e) {

    echo "Error: " . $e->getMessage();
}
