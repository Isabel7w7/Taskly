<?php
/**
 * ============================================================
 * DASHBOARD.PHP — Taskly
 * ============================================================
 * Página privada del dashboard. Solo accesible si existe
 * una sesión activa ($_SESSION['taskly_user']).
 */

// Iniciar el motor de sesiones antes de cualquier salida HTML.

session_start();
require_once 'Conexion.php';

// Proteger la página (ruta privada) 
if (!isset($_SESSION['taskly_user'])) {
    header("Location: login.php");
    exit();
}

// Leer los datos del usuario desde la sesión
$usuario      = $_SESSION['taskly_user'];
$usuario_id   = $usuario['id']; // <-- EXTRAEMOS EL ID DEL USUARIO DESDE TU SESIÓN
$nombre       = htmlspecialchars($usuario['nombre']);   // Prevenir XSS
$email        = htmlspecialchars($usuario['email']);
$rol          = htmlspecialchars($usuario['rol'] ?? 'usuario');
$login_time   = $_SESSION['login_time'] ?? 'Desconocido';

// ── Saludo dinámico según la hora del servidor ───────────────
$hora = (int) date('H');
if ($hora < 12)       $saludo = "Buenos días";
elseif ($hora < 18)   $saludo = "Buenas tardes";
else                  $saludo = "Buenas noches";

require_once __DIR__ . '/Tarea.php';
//$listaTareas = Tarea::obtenerPorUsuario($usuario_id);

// ════════════════════════════════════════════════════════════════
// ── LÓGICA DE BASE DE DATOS PARA EQUIPOS ────────────────────────
// ════════════════════════════════════════════════════════════════
$conexion_db = new Conexion();
$pdo = $conexion_db->conectar();

// 1. PROCESAR LA CREACIÓN DE UN NUEVO EQUIPO (Si se envió el formulario)
// ── LÓGICA: OBTENER LOS EQUIPOS DEL USUARIO ──────────────────
$conexion_db = new Conexion();
$pdo = $conexion_db->conectar();
$mis_equipos = []; // Aquí guardaremos los equipos

if ($pdo) {
    try {
        // Buscamos los equipos a los que pertenece el usuario logueado
        $sql = "SELECT e.id_equipo, e.nombre_equipo, e.fecha_creacion, i.rol_en_equipo 
                FROM equipo e 
                INNER JOIN integrante i ON e.id_equipo = i.id_equipo 
                WHERE i.id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario['id']]); // Usamos el ID del usuario de la sesión
        $mis_equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_mensaje = "Error al cargar equipos: " . $e->getMessage();
    }
}
// 2. OBTENER LOS EQUIPOS DEL USUARIO ACTUAL
$mis_equipos = [];
if ($pdo) {
    try {
        $sql_mis_equipos = "SELECT e.id_equipo, e.nombre_equipo, e.fecha_creacion, i.rol_en_equipo 
                            FROM equipo e 
                            INNER JOIN integrante i ON e.id_equipo = i.id_equipo 
                            WHERE i.id_usuario = ?";
        $stmt_mis_equipos = $pdo->prepare($sql_mis_equipos);
        $stmt_mis_equipos->execute([$usuario_id]);
        $mis_equipos = $stmt_mis_equipos->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error_mensaje = "Error al cargar equipos: " . $e->getMessage();
    }
}
// ════════════════════════════════════════════════════════════════

// Incluimos el encabezado común (Header, Navbar y Sidebar)
// Como lo incluimos hasta aquí abajo, el archivo header.php ya podrá 
// leer la variable $mis_equipos y pintar la lista.
require_once __DIR__ . '/includes/header.php';
?>

<style>
    /* Cards de Equipo */
    .team-card {
        background-color: #ced4da; /* --card-grey */
        border-radius: 15px;
        padding: 25px;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .team-card h3 {
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
    }

    .progress-container { margin-bottom: 15px; }

    .progress {
        height: 15px;
        border-radius: 10px;
        background-color: white;
        border: 1px solid #aaa;
    }

    .progress-bar { background-color: #6c757d; }
    .progress-text { font-size: 0.75rem; font-weight: 600; }

    .team-members {
        display: flex;
        gap: -10px;
        margin-bottom: 20px;
        font-size: 1.5rem;
    }

    .btn-new-activity {
        background-color: white;
        color: #212529;
        border: none;
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 600;
        align-self: center;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: background-color 0.2s;
    }
    
    .btn-new-activity:hover {
        background-color: #f8f9fa;
    }

    /* Tarjeta de bienvenida personalizada */
    .welcome-card {
        background: white;
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .welcome-card .welcome-text h2 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 4px;
    }

    .welcome-card .welcome-text p {
        font-size: 0.82rem;
        color: #6c757d;
        margin: 0;
    }

    /* Badge de sesión activa */
    .session-info {
        background: #eafaf1;
        border: 1px solid #a9dfbf;
        border-radius: 10px;
        padding: 12px 18px;
        font-size: 0.8rem;
    }

    .session-info .dot {
        display: inline-block;
        width: 8px; height: 8px;
        background: #27ae60;
        border-radius: 50%;
        margin-right: 5px;
        animation: blink 1.5s infinite;
    }

    @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

    /* Badge de rol */
    .rol-badge {
        display: inline-block;
        background: #102340; /* --primary-blue */
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 8px;
        vertical-align: middle;
    }
</style>

<div class="welcome-card">
    <div class="welcome-text">
        <h2>
            <?= $saludo ?>, <?= $nombre ?>
            <span class="rol-badge"><?= $rol ?></span>
        </h2>
        <p>
            <i class="bi bi-envelope me-1"></i><?= $email ?> &nbsp;|&nbsp;
            <i class="bi bi-clock me-1"></i>Sesión iniciada: <?= htmlspecialchars($login_time) ?>
        </p>
    </div>

    <div class="session-info">
        <span class="dot"></span>
        <strong>Sesión activa</strong><br>
        <small class="text-muted">
            ID: <?= substr(session_id(), 0, 8) ?>…
            &nbsp;|&nbsp; <?= date('d/m/Y H:i') ?>
        </small>
    </div>
</div>

<h1>Mis Equipos</h1>

<div class="row g-4">
    <?php if (!empty($mis_equipos)): ?>
        <?php foreach ($mis_equipos as $equipo): ?>
            <div class="col-md-6 col-lg-5">
                <div class="team-card">
                    <a href="proyecto.php?id_equipo=<?= $equipo['id_equipo'] ?>" style="text-decoration: none; color: inherit; display: block; flex-grow: 1;">
                        <h3><?= htmlspecialchars($equipo['nombre_equipo']) ?></h3>
                        
                        <div class="progress-container">
                            <div class="progress">
                                <div class="progress-bar" style="width: 0%"></div>
                            </div>
                            <span class="progress-text">0/5 completadas</span>
                        </div>
                        
                        <div class="team-members">
                            <i class="bi bi-person-circle" title="Rol: <?= htmlspecialchars($equipo['rol_en_equipo']) ?>"></i>
                        </div>
                    </a>
                    <button class="btn-new-activity mt-3 w-100" data-equipo-id="<?= $equipo['id_equipo'] ?>">
                        <i class="bi bi-plus"></i> Nueva actividad
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
            
    <?php else: ?>
        <div class="col-12">
            <div style="background-color: var(--mint-soft); color: var(--primary); padding: 20px; border-radius: 12px; border: 1px solid var(--mint);">
                <i class="bi bi-info-circle-fill me-2"></i> Aún no tienes equipos. ¡Haz clic en "Crear Equipo" en el menú lateral para empezar!
            </div>
        </div>
    <?php endif; ?>
</div>

</div><?php
// Incluimos el cierre del layout (Scripts de Bootstrap y cierre de etiquetas)
require_once __DIR__ . '/includes/footer.php';
?>