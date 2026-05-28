<?php
// 1. LÓGICA DE CONTROL (Top del archivo)
session_start();
require_once 'Conexion.php';

// Proteger la ruta: si no hay sesión, al login
if (!isset($_SESSION['taskly_user'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['taskly_user']['id'];

// Obtener el ID del equipo desde la URL (ej: proyecto.php?id_equipo=1)
// Si no viene ningún ID, lo redirigimos al dashboard para que elija uno
if (!isset($_GET['id_equipo'])) {
    header("Location: dashboard.php");
    exit();
}

$id_equipo = intval($_GET['id_equipo']);

// Conectar a la base de datos
$conexion_db = new Conexion();
$pdo = $conexion_db->conectar();

$datos_equipo = null;
$lista_tareas = [];

if ($pdo) {
    try {
        // 1. Validar que el equipo exista y obtener su nombre
        $sql_equipo = "SELECT nombre_equipo FROM equipo WHERE id_equipo = ?";
        $stmt_eq = $pdo->prepare($sql_equipo);
        $stmt_eq->execute([$id_equipo]);
        $datos_equipo = $stmt_eq->fetch(PDO::FETCH_ASSOC);

        if (!$datos_equipo) {
            // Si el equipo no existe en la BD, regresamos al dashboard
            header("Location: dashboard.php");
            exit();
        }

        // 2. Traer las tareas de la base de datos correspondientes a este usuario
        // NOTA: Como en tu script SQL la tabla 'tareas' no tiene 'id_equipo', por ahora 
        // traeremos las tareas globales de este usuario, pero filtradas o listas para mostrarse.
        $sql_tareas = "SELECT id, descripcion, estatus, fecha_entrega FROM tareas WHERE usuario_id = ? ORDER BY fecha_entrega ASC";
        $stmt_tar = $pdo->prepare($sql_tareas);
        $stmt_tar->execute([$usuario_id]);
        $lista_tareas = $stmt_tar->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error al consultar los datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Mis Proyectos</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary:   #102340;
            --mint:      #AEE5CD;
            --white:     #ffffff;
            --mint-soft: #d4f0e4;
            --mint-dark: #7ecfaa;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f7f4;
            color: var(--primary);
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* ══ TOPBAR ══ */
        .topbar {
            background: var(--primary);
            padding: 12px 28px;
            display: flex; justify-content: space-between; align-items: center;
            flex-shrink: 0;
        }

        .logo {
            display: flex; align-items: center; gap: 10px;
            font-weight: 800; font-size: 1.1rem;
            color: var(--white); text-decoration: none; letter-spacing: 1.5px;
        }

        .logo .dot { width: 30px; height: 30px; background: var(--mint); border-radius: 50%; }

        .topbar-icons { display: flex; gap: 10px; }

        .icon-btn {
            width: 36px; height: 36px;
            background: rgba(174,229,205,0.15); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--mint); text-decoration: none; font-size: 1rem;
            border: 1px solid rgba(174,229,205,0.2);
            transition: background 0.2s, color 0.2s;
        }

        .icon-btn:hover { background: var(--mint); color: var(--primary); }

        /* ══ LAYOUT ══ */
        .main-wrapper { display: flex; flex: 1; overflow: hidden; }

        /* ══ SIDEBAR ══ */
        .sidebar {
            width: 230px; background: var(--primary);
            padding: 28px 16px; display: flex; flex-direction: column;
            flex-shrink: 0; overflow-y: auto;
        }

        .nav-item {
            display: flex; align-items: center; gap: 13px;
            padding: 11px 14px; color: rgba(255,255,255,0.75);
            text-decoration: none; font-weight: 600; border-radius: 10px;
            margin-bottom: 4px; font-size: 0.9rem;
            transition: background 0.2s, color 0.2s;
        }

        .nav-item i { color: rgba(174,229,205,0.7); }

        .nav-item:hover, .nav-item.active {
            background: rgba(174,229,205,0.18); color: var(--white);
        }

        .nav-item:hover i, .nav-item.active i { color: var(--mint); }

        .sidebar-divider { border-top: 1px solid rgba(174,229,205,0.2); margin: 20px 0; }

        .teams-label {
            font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px;
            padding-left: 14px; margin-bottom: 12px; color: var(--mint); opacity: 0.8;
        }

        .btn-create-team {
            background: var(--mint); color: var(--primary); border: none;
            border-radius: 10px; padding: 10px 14px; font-weight: 700;
            font-size: 0.85rem; width: 100%; display: flex; align-items: center;
            gap: 8px; margin-bottom: 14px; cursor: pointer; transition: background 0.2s;
        }

        .btn-create-team:hover { background: var(--mint-dark); }

        .team-list { list-style: none; padding: 0; }

        .team-list li {
            padding: 9px 14px; font-weight: 600; font-size: 0.88rem;
            border-radius: 8px; cursor: pointer; color: rgba(255,255,255,0.65);
            transition: background 0.2s, color 0.2s;
        }

        .team-list li:hover { background: rgba(174,229,205,0.1); color: var(--white); }
        .team-list li.active-team { background: rgba(174,229,205,0.15); color: var(--mint); }

        /* ══ CONTENT ══ */
        .content { flex: 1; overflow-y: auto; display: flex; flex-direction: column; }

        /* ══ SUBHEADER ══ */
        .subheader {
            background: var(--white); padding: 14px 36px;
            display: flex; align-items: center; gap: 12px;
            border-bottom: 2px solid var(--mint-soft); flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(16,35,64,0.06);
        }

        .btn-action {
            background: var(--mint-soft); color: var(--primary);
            border: 1.5px solid var(--mint); border-radius: 10px;
            padding: 9px 18px; font-family: 'Poppins', sans-serif;
            font-weight: 600; font-size: 0.84rem; cursor: pointer;
            display: flex; align-items: center; gap: 7px;
            transition: background 0.2s;
        }

        .btn-action:hover { background: var(--mint); }

        .search-wrap { margin-left: auto; position: relative; }

        .search-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%); color: #aaa; font-size: 0.9rem;
        }

        #searchInput {
            background: var(--white); border: 1.5px solid #e0e0e0;
            border-radius: 10px; padding: 9px 16px 9px 36px;
            font-family: 'Poppins', sans-serif; font-size: 0.84rem;
            width: 230px; outline: none; color: var(--primary);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        #searchInput:focus {
            border-color: var(--mint);
            box-shadow: 0 0 0 3px rgba(174,229,205,0.4);
        }

        /* ══ MAIN AREA ══ */
        .main-area { padding: 34px 36px; flex: 1; }

        /* Page header */
        .page-header {
            display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 2rem; font-weight: 800; color: var(--primary);
        }

        /* Botón Crear proyecto — igual al de la imagen: #102340 con texto blanco */
        .btn-crear-proyecto {
            background: var(--primary); color: var(--white); border: none;
            border-radius: 12px; padding: 12px 26px;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.9rem;
            display: flex; align-items: center; gap: 8px; cursor: pointer;
            box-shadow: 0 4px 14px rgba(16,35,64,0.25);
            transition: background 0.2s, transform 0.15s;
        }

        .btn-crear-proyecto:hover { background: #1a3a60; transform: translateY(-2px); }

        /* ══ GRID ══ */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        /* ══ CARD — mismo estilo de la imagen ══ */
        .project-card {
            background: var(--white);
            border-radius: 16px;
            padding: 28px 24px 22px;
            border: 1.5px solid #e8f5ef;
            position: relative; overflow: hidden;
            transition: transform 0.22s, box-shadow 0.22s;
            animation: fadeUp 0.35s ease both;
        }

        /* franja superior degradado igual a la imagen */
        .project-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--mint));
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .project-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(16,35,64,0.10);
        }

        .project-card h3 {
            font-size: 1rem; font-weight: 700;
            text-align: center; margin-bottom: 18px; color: var(--primary);
        }

        .member-row {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 8px; font-size: 0.85rem; color: var(--primary);
        }

        .member-row i { font-size: 1.3rem; color: var(--primary); }

        /* Badge +4 igual al de la imagen: fondo #102340 texto mint */
        .badge-extra {
            background: var(--primary); color: var(--mint);
            font-size: 0.68rem; font-weight: 700;
            padding: 2px 9px; border-radius: 20px; margin-left: 4px;
        }

        /* ══ BARRA DE PROGRESO — igual imagen ══ */
        .progress-wrap { margin: 18px 0 6px; }

        .progress-track {
            height: 11px; border-radius: 20px;
            background: var(--mint-soft); overflow: hidden;
        }

        /* barra rellena: azul oscuro como en la imagen */
        .progress-fill {
            height: 100%; border-radius: 20px;
            background: var(--primary);
            transition: width 0.7s ease;
        }

        .progress-label {
            font-size: 0.75rem; font-weight: 600;
            color: var(--mint-dark); margin-top: 6px;
        }

        /* ══ BTN VER PROYECTO — igual imagen: fondo mint-soft, texto oscuro ══ */
        .btn-ver {
            display: block; width: 100%; margin-top: 18px;
            background: var(--mint-soft); color: var(--primary);
            border: none; border-radius: 10px; padding: 11px;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.88rem;
            cursor: pointer; transition: background 0.2s, color 0.2s;
        }

        .btn-ver:hover { background: var(--mint); }

        /* Sin resultados */
        .no-results {
            display: none; grid-column: 1/-1;
            text-align: center; padding: 60px 20px;
            color: #7a9ab8; font-size: 0.95rem;
        }

        .no-results i { font-size: 3rem; margin-bottom: 12px; display: block; color: var(--mint-dark); }

        /* ══ MODAL CREAR ══ */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(16,35,64,0.6); z-index: 1000;
            align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }

        .modal-overlay.show { display: flex; animation: fadeBg 0.2s ease; }
        @keyframes fadeBg { from{opacity:0}to{opacity:1} }

        .modal-box {
            background: var(--white); border-radius: 22px; width: 100%; max-width: 470px;
            overflow: hidden; box-shadow: 0 30px 70px rgba(16,35,64,0.3);
            animation: slideUp 0.25s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(28px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .modal-header {
            background: var(--primary); padding: 24px 30px 20px; position: relative;
        }

        .modal-header h2 { font-size: 1.15rem; font-weight: 800; color: var(--white); margin-bottom: 3px; }
        .modal-header p  { font-size: 0.78rem; color: rgba(174,229,205,0.8); }

        .modal-close {
            position: absolute; top: 16px; right: 16px;
            background: rgba(174,229,205,0.15); border: 1px solid rgba(174,229,205,0.3);
            border-radius: 8px; width: 30px; height: 30px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; color: var(--mint); transition: background 0.2s;
        }

        .modal-close:hover { background: rgba(255,255,255,0.15); }

        .modal-body { padding: 26px 30px 30px; }

        .form-group { margin-bottom: 15px; }

        .form-group label {
            display: block; font-size: 0.8rem; font-weight: 700;
            color: var(--primary); margin-bottom: 6px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%; background: #f0f7f4;
            border: 1.5px solid var(--mint); border-radius: 10px;
            padding: 10px 14px; font-family: 'Poppins', sans-serif;
            font-size: 0.84rem; color: var(--primary); outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(174,229,205,0.4);
        }

        .form-group textarea { resize: vertical; min-height: 75px; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .modal-actions { display: flex; gap: 12px; margin-top: 20px; }

        .btn-cancel {
            flex: 1; background: #f0f7f4; color: var(--primary);
            border: 1.5px solid var(--mint); border-radius: 10px; padding: 11px;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.87rem;
            cursor: pointer; transition: background 0.2s;
        }

        .btn-cancel:hover { background: var(--mint-soft); }

        .btn-submit {
            flex: 2; background: var(--primary); color: var(--white); border: none;
            border-radius: 10px; padding: 11px;
            font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 0.87rem;
            cursor: pointer; transition: background 0.2s, transform 0.15s;
        }

        .btn-submit:hover { background: #1a3a60; transform: translateY(-1px); }

        /* ══ TOAST ══ */
        .toast-msg {
            display: none; position: fixed; bottom: 28px; right: 28px;
            background: var(--primary); color: var(--white); padding: 13px 20px;
            border-radius: 12px; font-size: 0.85rem; font-weight: 600;
            z-index: 2000; border-left: 4px solid var(--mint);
            box-shadow: 0 8px 28px rgba(16,35,64,0.3);
        }

        .toast-msg.show { display: block; animation: toastIn 0.3s ease; }

        @keyframes toastIn {
            from { transform: translateY(14px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .toast-msg i { margin-right: 8px; color: var(--mint); }

        @media (max-width: 768px) {
            .projects-grid { grid-template-columns: 1fr; }
            .main-wrapper  { flex-direction: column; }
            .sidebar       { width: 100%; }
            .main-area     { padding: 20px 16px; }
        }
    </style>
</head>
<body>

<!-- ══ TOPBAR — tu plantilla ══ -->
<header class="topbar">
    <a href="dashboard.php" class="logo">
        <div class="dot"></div> TASKLY
    </a>
    <div class="topbar-icons">
        <a href="#" class="icon-btn" title="Notificaciones"><i class="bi bi-bell-fill"></i></a>
        <a href="#" class="icon-btn" title="Configuración"><i class="bi bi-gear-fill"></i></a>
        <a href="perfil.php" class="icon-btn" title="Mi perfil"><i class="bi bi-person-fill"></i></a>
    </div>
</header>

<div class="main-wrapper">

    <!-- ══ SIDEBAR — tu plantilla ══ -->
    <aside class="sidebar">
        <a href="proyectos.php" class="nav-item active">
            <i class="bi bi-folder2-open"></i> Mis proyectos
        </a>
        <a href="dashboard.php" class="nav-item">
            <i class="bi bi-clock-history"></i> Actividades
        </a>
        <a href="dashboard.php" class="nav-item">
            <i class="bi bi-people-fill"></i> Mis equipos
        </a>

        <div class="sidebar-divider"></div>

        <p class="teams-label">EQUIPOS</p>
        <button class="btn-create-team"><i class="bi bi-plus"></i> Crear Equipo</button>
        <ul class="team-list">
            <li class="active-team">Equipo 1</li>
        </ul>

        <div class="mt-auto pt-4">
            <a href="logout.php" class="nav-item" style="color:#ff6b6b">
                <i class="bi bi-box-arrow-left" style="color:#ff6b6b"></i> Cerrar sesión
            </a>
        </div>
    </aside>

    <!-- ══ CONTENT ══ -->
    <div class="content">

        <!-- Subheader — igual imagen -->
        <div class="subheader">
            <button class="btn-action" onclick="window.location.href='proyectos.php'">
                <i class="bi bi-people"></i> Administrar miembros
            </button>
            <button class="btn-action" onclick="window.location.href='configuracion_equipo.php'">
                <i class="bi bi-gear"></i> Configurar equipo
            </button>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar proyectos...">
            </div>
        </div>

        <!-- Main -->
        <div class="main-area">

            <div class="page-header">
                <h1 class="mb-4" style="font-weight: 800; color: var(--primary);">
                    <i class="bi bi-folder2-open me-2"></i> Proyectos de: <?= htmlspecialchars($datos_equipo['nombre_equipo']) ?>
                </h1>                
                <button class="btn-crear-proyecto" onclick="openModal()">
                    + &nbsp;Crear proyecto
                </button>
            </div>

            <!-- ══ CARDS — diseño exacto de la imagen ══ -->
            <div class="projects-grid" id="projectsGrid">
    <?php if (!empty($lista_tareas)): ?>
        <?php foreach ($lista_tareas as $tarea): ?>
            <div class="project-card">
                <h3><?= htmlspecialchars($tarea['descripcion']) ?></h3>
                <div class="member-row">
                    <i class="bi bi-calendar-event"></i> Entrega: <?= htmlspecialchars($tarea['fecha_entrega']) ?>
                </div>
                <div class="member-row">
                    <i class="bi bi-info-circle"></i> Estado: 
                    <span class="badge <?php 
                        echo $tarea['estatus'] === 'Completada' ? 'bg-success' : 'bg-warning text-dark'; 
                    ?>">
                        <?= htmlspecialchars($tarea['estatus']) ?>
                    </span>
                </div>
                
                <div class="progress-wrap mt-3">
                    <div class="progress-track">
                        <div class="progress-fill" style="width: <?= $tarea['estatus'] === 'Completada' ? '100%' : '20%' ?>;"></div>
                    </div>
                </div>
                <button class="btn-ver mt-3" onclick="window.location.href='ver-proyecto.php?id_tarea=<?= $tarea['id'] ?>'">
                    Ver actividad
                </button>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12" id="noResults" style="grid-column: 1 / -1;">
            <div style="background-color: var(--mint-soft); color: var(--primary); padding: 20px; border-radius: 12px; border: 1px solid var(--mint); text-align: center;">
                <i class="bi bi-info-circle-fill me-2"></i> No hay actividades registradas para este equipo. ¡Crea una desde el Dashboard!
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- ══ MODAL CREAR PROYECTO ══ -->
<div class="modal-overlay" id="modalOverlay" onclick="handleOverlay(event)">
    <div class="modal-box">
        <div class="modal-header">
            <button class="modal-close" onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
            <h2><i class="bi bi-folder-plus me-2"></i>Nuevo Proyecto</h2>
            <p>Completa la información para crear tu proyecto</p>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Nombre del proyecto *</label>
                <input type="text" id="proyectoNombre" placeholder="Ej: App móvil, Rediseño web…">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea id="proyectoDesc" placeholder="¿De qué trata este proyecto?"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Equipo</label>
                    <select id="proyectoEquipo">
                        <option value="">Seleccionar…</option>
                        <option>Equipo 1</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha límite</label>
                    <input type="date" id="proyectoFecha">
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button class="btn-submit" onclick="crearProyecto()">
                    <i class="bi bi-check2 me-1"></i> Crear proyecto
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-msg" id="toast">
    <i class="bi bi-check-circle-fill"></i><span id="toastText"></span>
</div>

<script>
    // ── Buscador ──
    document.getElementById('searchInput').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;
        document.querySelectorAll('.project-card').forEach(card => {
            const match = card.dataset.name.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        const noRes = document.getElementById('noResults');
        noRes.style.display = (visible === 0 && q !== '') ? 'block' : 'none';
        if (visible === 0 && q !== '')
            document.getElementById('noResultsQuery').textContent = this.value;
    });

    // ── Modal ──
    function openModal() {
        document.getElementById('modalOverlay').classList.add('show');
        setTimeout(() => document.getElementById('proyectoNombre').focus(), 100);
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('show');
        ['proyectoNombre','proyectoDesc','proyectoEquipo','proyectoFecha']
            .forEach(id => document.getElementById(id).value = '');
    }

    function handleOverlay(e) {
        if (e.target === document.getElementById('modalOverlay')) closeModal();
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // ── Crear proyecto ──
    let counter = 4;

    function crearProyecto() {
        const nombre = document.getElementById('proyectoNombre').value.trim();
        if (!nombre) {
            const el = document.getElementById('proyectoNombre');
            el.style.borderColor = '#e74c3c'; el.focus();
            setTimeout(() => el.style.borderColor = '', 1500);
            return;
        }

        counter++;
        const pid = 'p' + counter;

        const card = document.createElement('div');
        card.className = 'project-card';
        card.dataset.name = nombre.toLowerCase();
        card.innerHTML = `
            <h3>${nombre}</h3>
            <div class="member-row"><i class="bi bi-person-circle"></i> Lider</div>
            <div class="member-row"><i class="bi bi-people-fill"></i> Programador <span class="badge-extra">+1</span></div>
            <div class="progress-wrap">
                <div class="progress-track">
                    <div class="progress-fill" id="bar-${pid}" style="width:0%"></div>
                </div>
                <p class="progress-label" id="lbl-${pid}">0/0 completadas</p>
            </div>
            <button class="btn-ver" onclick="window.location.href='ver-proyecto.php'">Ver proyecto</button>
        `;

        const grid = document.getElementById('projectsGrid');
        grid.insertBefore(card, document.getElementById('noResults'));
        closeModal();
        showToast(`"${nombre}" creado correctamente`);
    }

    // ── Toast ──
    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toastText').textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3200);
    }
</script>

</body>
</html>