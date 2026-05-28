<?php
// Archivo: validar.php
session_start();

// Importamos la clase Usuario (ella se encargará de llamar a Conexion.php)
require_once __DIR__ . '/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

// Limpiar los datos recibidos del formulario
$email    = trim(htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'));
$password = trim($_POST['password'] ?? '');

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
    $_SESSION['login_error'] = "Por favor completa todos los campos correctamente.";
    header("Location: login.php");
    exit();
}

// ── EL CAMBIO AQUÍ ──
// Llamamos al método de la clase. Ya NO le pasamos ningún arreglo, busca directo en MySQL.
$usuarioEncontrado = Usuario::buscarPorEmail($email);

// Verificaciones de seguridad
if ($usuarioEncontrado === null || !password_verify($password, $usuarioEncontrado->getPassword())) {
    $_SESSION['login_error'] = "Correo o contraseña incorrectos.";
    header("Location: login.php");
    exit();
}

// Si la contraseña coincide, guardamos el objeto en la sesión del servidor
session_regenerate_id(true);

$_SESSION['taskly_user'] = [
    'id'     => $usuarioEncontrado->getId(),
    'nombre' => $usuarioEncontrado->getNombre(),
    'username' => $usuarioEncontrado->getUsername(),
    'email'  => $usuarioEncontrado->getEmail(),
    'rol'    => $usuarioEncontrado->getRol(),
];

$_SESSION['login_time'] = date('d/m/Y H:i:s');
header("Location: dashboard.php");
exit();
