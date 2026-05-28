<?php
session_start();
require_once 'Conexion.php';

// Verificamos que los datos vengan del formulario y que el usuario tenga sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_equipo']) && isset($_SESSION['taskly_user'])) {
    
    $nombre_equipo = trim($_POST['nombre_equipo']);
    $usuario_id = $_SESSION['taskly_user']['id'];
    $fecha_actual = date('Y-m-d');

    if (!empty($nombre_equipo)) {
        $conexion_db = new Conexion();
        $pdo = $conexion_db->conectar();
        
        if ($pdo) {
            try {
                // Iniciamos transacción para insertar en las dos tablas de forma segura
                $pdo->beginTransaction();

                // 1. Insertamos el nuevo equipo
                $sql_equipo = "INSERT INTO equipo (nombre_equipo, fecha_creacion) VALUES (?, ?)";
                $stmt_equipo = $pdo->prepare($sql_equipo);
                $stmt_equipo->execute([$nombre_equipo, $fecha_actual]);
                
                // Obtenemos el ID del equipo que se acaba de crear
                $id_equipo_nuevo = $pdo->lastInsertId();

                // 2. Vinculamos al usuario con ese equipo en la tabla integrante
                $sql_integrante = "INSERT INTO integrante (rol_en_equipo, id_usuario, id_equipo) VALUES (?, ?, ?)";
                $stmt_integrante = $pdo->prepare($sql_integrante);
                $stmt_integrante->execute(['Administrador', $usuario_id, $id_equipo_nuevo]);

                // Confirmamos los cambios
                $pdo->commit();
            } catch (PDOException $e) {
                // Si algo falla, deshacemos los cambios
                $pdo->rollBack();
                echo "Error: " . $e->getMessage();
                exit();
            }
        }
    }
}

// 3. Devolvemos al usuario al dashboard instantáneamente
header("Location: dashboard.php");
exit();
?>