<?php
/**
 * ============================================================
 * LOGOUT.PHP — Taskly
 * ============================================================
 */

// Paso 1 — Reanudar la sesión (necesario para destruirla)
session_start();

// Paso 2 — Vaciar todas las variables de sesión
$_SESSION = [];

// Paso 3 — Eliminar la cookie de sesión del navegador del usuario
// Esto garantiza que aunque session_destroy() falle, la cookie
// queda invalidada en el lado del cliente.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),   // "PHPSESSID" por defecto
        '',               // Valor vacío
        time() - 42000,   // Fecha en el pasado → el navegador la borra
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Paso 4 — Destruir la sesión en el servidor
session_destroy();

// Paso 5 — Redirigir al login con un parámetro opcional de mensaje
// El parámetro ?logout=1 le indica a login.php que muestre
// un mensaje de "Has cerrado sesión correctamente".
header("Location: login.php?logout=1");
exit();
?>
