<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #102340;
            --mint: #AEE5CD;
            --white: #ffffff;
            --mint-soft: #d4f0e4;
            --mint-dark: #7ecfaa;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--white);
            text-decoration: none;
            letter-spacing: 1.5px;
        }

        .logo .dot {
            width: 30px;
            height: 30px;
            background: var(--mint);
            border-radius: 50%;
        }

        .topbar-icons {
            display: flex;
            gap: 10px;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            background: rgba(174, 229, 205, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--mint);
            text-decoration: none;
            font-size: 1rem;
            border: 1px solid rgba(174, 229, 205, 0.2);
            transition: background 0.2s, color 0.2s;
        }

        .icon-btn:hover,
        .icon-btn.active-icon {
            background: var(--mint);
            color: var(--primary);
        }

        /* ══ LAYOUT ══ */
        .main-wrapper {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* ══ SIDEBAR ══ */
        .sidebar {
            width: 230px;
            background: var(--primary);
            padding: 28px 16px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 11px 14px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 4px;
            font-size: 0.9rem;
            transition: background 0.2s, color 0.2s;
        }

        .nav-item i {
            color: rgba(174, 229, 205, 0.7);
        }

        .nav-item:hover {
            background: rgba(174, 229, 205, 0.12);
            color: var(--white);
        }

        .nav-item:hover i {
            color: var(--mint);
        }

        .sidebar-divider {
            border-top: 1px solid rgba(174, 229, 205, 0.2);
            margin: 20px 0;
        }

        .teams-label {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            padding-left: 14px;
            margin-bottom: 12px;
            color: var(--mint);
            opacity: 0.8;
        }

        .btn-create-team {
            background: var(--mint);
            color: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 0.85rem;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-create-team:hover {
            background: var(--mint-dark);
        }

        .team-list {
            list-style: none;
            padding: 0;
        }

        .team-list li {
            padding: 9px 14px;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: 8px;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.65);
            transition: background 0.2s, color 0.2s;
        }

        .team-list li:hover {
            background: rgba(174, 229, 205, 0.1);
            color: var(--white);
        }

        /* ══ CONTENT ══ */
        .content {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        /* ══ MAIN AREA ══ */
        .main-area {
            padding: 40px 50px;
            flex: 1;
        }

        /* ══ HAMBURGER BUTTON (MÓVIL) ══ */
        .hamburger {
            display: none;
            background: rgba(174, 229, 205, 0.15);
            border: 1px solid rgba(174, 229, 205, 0.2);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            color: var(--mint);
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .hamburger:hover {
            background: var(--mint);
            color: var(--primary);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 100;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* RESPONSIVE TABLET Y MÓVIL */
        @media (max-width: 992px) {
            .sidebar { width: 68px; padding: 28px 10px; overflow: visible; }
            .sidebar .nav-item span, .sidebar .teams-label, .sidebar .btn-create-team span, .sidebar .team-list { display: none; }
            .nav-item, .btn-create-team { justify-content: center; padding: 11px; gap: 0; }
            .main-area { padding: 30px 24px; }
        }

        @media (max-width: 768px) {
            .hamburger { display: flex; }
            .sidebar { position: fixed; top: 0; left: -260px; width: 230px !important; height: 100%; z-index: 200; transition: left 0.3s ease; }
            .sidebar .nav-item span, .sidebar .teams-label, .sidebar .btn-create-team span, .sidebar .team-list { display: unset; }
            .nav-item, .btn-create-team { justify-content: flex-start; gap: 13px; }
            .sidebar.open { left: 0; }
            .main-wrapper { flex-direction: column; }
            .main-area { padding: 20px 16px; }
        }
    </style>
</head>

<body>

    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="hamburger" id="menuBtn" title="Menú"><i class="bi bi-list"></i></button>
            <a href="proyectos.php" class="logo">
                <div class="dot"></div> TASKLY
            </a>
        </div>
        <div class="topbar-icons">
            <a href="#" class="icon-btn" title="Notificaciones"><i class="bi bi-bell-fill"></i></a>
            <a href="configuracion_equipo.php" class="icon-btn" title="Configuración"><i class="bi bi-gear-fill"></i></a>
            <a href="perfil.php" class="icon-btn" title="Mi perfil"><i class="bi bi-person-fill"></i></a>
        </div>
    </header>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-wrapper">

        <aside class="sidebar" id="sidebar">
            <a href="proyecto.php" class="nav-item">
                <i class="bi bi-folder2-open"></i> <span>Mis proyectos</span>
            </a>
            <a href="#" class="nav-item">
                <i class="bi bi-clock-history"></i> <span>Actividades</span>
            </a>
            <a href="configuracion_equipo.php" class="nav-item">
                <i class="bi bi-people-fill"></i> <span>Mis equipos</span>
            </a>

            <div class="sidebar-divider"></div>

            <p class="teams-label">EQUIPOS</p>
<button class="btn-create-team" data-bs-toggle="modal" data-bs-target="#modalCrearEquipo">
    <i class="bi bi-plus"></i> <span>Crear Equipo</span>
</button>            
<ul class="team-list">
                <li>Equipo 1</li>
            </ul>

            <div class="mt-auto pt-4">
    <a href="logout.php" class="nav-item" style="color:#ff6b6b; margin-bottom: 8px;">
        <i class="bi bi-box-arrow-left" style="color:#ff6b6b"></i> <span>Salir</span>
    </a>
    
    <a href="Pagina de Pagos.html" class="nav-item" style="color: var(--mint);">
        <i class="bi bi-credit-card-2-front" style="color: var(--mint);"></i> <span>Suscripciones</span>
    </a>
</div>
        </aside>

        <div class="content">
            <div class="main-area">