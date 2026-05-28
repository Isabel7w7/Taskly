<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Ver Proyecto</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            background: rgba(174,229,205,0.15);
            border-radius: 50%;
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
        .nav-item:hover, .nav-item.active { background: rgba(174,229,205,0.18); color: var(--white); }
        .nav-item:hover i, .nav-item.active i { color: var(--mint); }
        .sidebar-divider { border-top: 1px solid rgba(174,229,205,0.2); margin: 20px 0; }
        .teams-label { font-size: 0.7rem; font-weight: 800; letter-spacing: 1.5px; padding-left: 14px; margin-bottom: 12px; color: var(--mint); opacity: 0.8; }
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
            font-weight: 600; font-size: 0.84rem; cursor: pointer; transition: background 0.2s;
        }
        .btn-action:hover { background: var(--mint); }
        .search-wrap { margin-left: auto; position: relative; }
        .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--primary); opacity: 0.4; }
        #searchInput {
            background: #f0f7f4; border: 1.5px solid var(--mint); border-radius: 10px;
            padding: 9px 16px 9px 36px; font-family: 'Poppins', sans-serif;
            font-size: 0.84rem; width: 220px; outline: none; color: var(--primary);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        #searchInput:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(174,229,205,0.5); }

        /* ══ MAIN AREA ══ */
        .main-area { padding: 32px 36px; flex: 1; }

        .page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 28px; }
        .page-header h1 { font-size: 1.9rem; font-weight: 800; color: var(--primary); margin-bottom: 4px; }
        .breadcrumb-text { font-size: 0.75rem; color: #6a8caa; }
        .breadcrumb-text a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .breadcrumb-text a:hover { color: var(--mint-dark); }
        .btn-crear-proyecto {
            background: var(--primary); color: var(--white); border: none;
            border-radius: 12px; padding: 12px 24px; font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 0.88rem; display: flex; align-items: center;
            gap: 8px; cursor: pointer; box-shadow: 0 4px 14px rgba(16,35,64,0.25);
            transition: background 0.2s, transform 0.15s; text-decoration: none;
        }
        .btn-crear-proyecto:hover { background: #1a3a60; transform: translateY(-2px); color: var(--white); }
        .btn-crear-proyecto i { color: var(--mint); }

        /* ══ PROJECT PANEL ══ */
        .project-panel {
            background: var(--white); border-radius: 20px; overflow: hidden;
            box-shadow: 0 8px 32px rgba(16,35,64,0.1); border: 2px solid var(--mint-soft);
        }
        .panel-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1e4a80 100%);
            padding: 22px 28px; display: flex; justify-content: space-between; align-items: center;
        }
        .panel-header-left { display: flex; align-items: center; gap: 14px; }
        .leader-avatar {
            width: 44px; height: 44px; background: var(--mint); border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1rem; color: var(--primary); flex-shrink: 0;
        }
        .panel-header h2 { font-size: 1.1rem; font-weight: 700; color: var(--white); margin: 0; }
        .panel-header .role-tag { font-size: 0.72rem; color: rgba(174,229,205,0.8); margin-top: 2px; }
        .progress-badge {
            background: rgba(174,229,205,0.15); border: 1px solid rgba(174,229,205,0.3);
            border-radius: 20px; padding: 6px 16px; font-size: 0.8rem;
            font-weight: 700; color: var(--mint); display: flex; align-items: center; gap: 6px;
        }
        .panel-info {
            padding: 18px 28px; background: var(--mint-soft);
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid rgba(174,229,205,0.5);
        }
        .panel-info-left { display: flex; align-items: center; gap: 10px; }
        .members-icon { font-size: 1.6rem; color: var(--primary); }
        .panel-info-right { font-size: 0.82rem; font-weight: 700; color: var(--primary); }
        .panel-progress-wrap { padding: 14px 28px 10px; }
        .panel-progress-label { font-size: 0.78rem; font-weight: 700; color: #6a8caa; margin-bottom: 6px; }
        .panel-progress { height: 10px; border-radius: 8px; background: var(--mint-soft); overflow: hidden; }
        .panel-progress-bar { height: 100%; background: linear-gradient(90deg, var(--primary), #1e4a80); border-radius: 8px; width: 60%; }

        /* ══ TABLE ══ */
        .table-wrap { padding: 6px 28px 0; overflow-x: auto; }
        table { width: 100%; border-collapse: separate; border-spacing: 0 6px; font-size: 0.84rem; }
        thead th {
            font-size: 0.72rem; font-weight: 700; color: #7a9ab8;
            letter-spacing: 0.5px; padding: 8px 14px; text-transform: uppercase; background: transparent;
        }
        tbody tr { background: #f8fcfa; border-radius: 10px; transition: background 0.2s, transform 0.15s; }
        tbody tr:hover { background: var(--mint-soft); transform: translateX(2px); }
        tbody td { padding: 14px 14px; color: var(--primary); font-weight: 500; border: none; }
        tbody td:first-child { border-radius: 10px 0 0 10px; font-weight: 600; }
        tbody td:last-child  { border-radius: 0 10px 10px 0; }
        .member-chip { display: inline-flex; align-items: center; gap: 8px; }
        .avatar-sm {
            width: 28px; height: 28px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem; font-weight: 800; color: var(--primary); flex-shrink: 0;
        }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
        .status-done     { background: var(--mint);  color: var(--primary); }
        .status-progress { background: #fff3cd;       color: #856404; }
        .status-todo     { background: #e2e8f0;       color: #475569; }

        .btn-edit {
            background: var(--mint-soft); border: 1.5px solid var(--mint);
            color: var(--primary); border-radius: 8px; width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.9rem; transition: background 0.2s, color 0.2s;
        }
        .btn-edit:hover { background: var(--primary); color: var(--mint); border-color: var(--primary); }

        .panel-footer {
            padding: 18px 28px; display: flex; justify-content: flex-end;
            border-top: 1px solid var(--mint-soft); margin-top: 10px;
        }
        .btn-nueva {
            background: var(--primary); color: var(--white); border: none;
            border-radius: 10px; padding: 11px 22px; font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 0.87rem; display: flex; align-items: center;
            gap: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(16,35,64,0.2);
            transition: background 0.2s, transform 0.15s;
        }
        .btn-nueva i { color: var(--mint); }
        .btn-nueva:hover { background: #1a3a60; transform: translateY(-2px); }

        /* ══ MODALES COMPARTIDOS ══ */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(16,35,64,0.65); z-index: 1000;
            align-items: center; justify-content: center; backdrop-filter: blur(4px);
        }
        .modal-overlay.show { display: flex; animation: fadeBg 0.2s ease; }
        @keyframes fadeBg { from { opacity: 0; } to { opacity: 1; } }

        .modal-box {
            background: var(--white); border-radius: 22px; width: 100%; max-width: 460px;
            overflow: hidden; box-shadow: 0 30px 70px rgba(16,35,64,0.3);
            animation: slideUp 0.25s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(28px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        /* Header del modal estilo "pantalla" como el wireframe */
        .modal-topbar {
            background: var(--primary);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .modal-back-btn {
            width: 36px; height: 36px;
            background: rgba(174,229,205,0.15);
            border: 1.5px solid rgba(174,229,205,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--mint); cursor: pointer; font-size: 1.1rem;
            transition: background 0.2s; flex-shrink: 0;
        }
        .modal-back-btn:hover { background: rgba(174,229,205,0.3); }

        .modal-topbar h2 {
            font-size: 1.1rem; font-weight: 800; color: var(--white); margin: 0;
        }

        .modal-body { padding: 24px 24px 28px; background: #f0f7f4; }

        /* Inputs estilo wireframe: fondo blanco, bordes redondeados grandes */
        .field-group { margin-bottom: 14px; }

        .field-group input,
        .field-group select,
        .field-group textarea {
            width: 100%;
            background: var(--white);
            border: 1.5px solid rgba(174,229,205,0.6);
            border-radius: 14px;
            padding: 13px 18px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            color: var(--primary);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field-group input::placeholder,
        .field-group textarea::placeholder { color: #9ab0c0; }

        .field-group input:focus,
        .field-group select:focus,
        .field-group textarea:focus {
            border-color: var(--mint-dark);
            box-shadow: 0 0 0 3px rgba(174,229,205,0.4);
        }

        .field-group textarea { resize: vertical; min-height: 100px; }

        .btn-hecho {
            width: 100%;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 14px;
            padding: 14px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, transform 0.15s;
            letter-spacing: 0.3px;
        }
        .btn-hecho:hover { background: #1a3a60; transform: translateY(-1px); }

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
            .main-wrapper { flex-direction: column; }
            .sidebar { width: 100%; }
            .main-area { padding: 20px 16px; }
            .table-wrap { padding: 6px 12px 0; }
        }
    </style>
</head>
<body>

<!-- ══ TOPBAR ══ -->
<header class="topbar">
    <a href="dashboard.php" class="logo">
        <div class="dot"></div> TASKLY
    </a>
    <div class="topbar-icons">
        <a href="#" class="icon-btn"><i class="bi bi-bell-fill"></i></a>
        <a href="#" class="icon-btn"><i class="bi bi-gear-fill"></i></a>
        <a href="logout.php" class="icon-btn"><i class="bi bi-person-fill"></i></a>
    </div>
</header>

<div class="main-wrapper">

    <!-- ══ SIDEBAR ══ -->
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

        <div class="subheader">
            <button class="btn-action" onclick="window.location.href='proyectos.php'"><i class="bi bi-people me-1"></i> Administrar miembros</button>
            <button class="btn-action" onclick="window.location.href='configuracion_equipo.php'"><i class="bi bi-gear me-1"></i> Configurar equipo</button>
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar proyectos...">
            </div>
        </div>

        <div class="main-area">

            <div class="page-header">
                <div>
                    <h1>Proyecto 1</h1>
                    <div class="breadcrumb-text">
                        <a href="proyectos.php">Mis equipos</a> &rsaquo;
                        Equipo 1 &rsaquo;
                        <a href="proyectos.php">Mis proyectos</a> &rsaquo;
                        Proyecto 1 &rsaquo; Administrar miembros
                    </div>
                </div>
            </div>

            <!-- Project Panel -->
            <div class="project-panel">

                <div class="panel-header">
                    <div class="panel-header-left">
                        <div class="leader-avatar">AT</div>
                        <div>
                            <h2>Líder: Admin Taskly</h2>
                            <div class="role-tag"><i class="bi bi-shield-check me-1"></i>Administrador del proyecto</div>
                        </div>
                    </div>
                    <div class="progress-badge">
                        <i class="bi bi-check2-circle"></i> 3/5 completadas
                    </div>
                </div>

                <div class="panel-info">
                    <div class="panel-info-left">
                        <i class="bi bi-people-fill members-icon"></i>
                        <div>
                            <div style="font-weight:700;font-size:0.88rem;">Miembros del equipo</div>
                            <div style="font-size:0.75rem;color:#6a8caa;">3 colaboradores activos</div>
                        </div>
                    </div>
                    <div class="panel-info-right">3/5 completadas</div>
                </div>

                <div class="panel-progress-wrap">
                    <div class="panel-progress-label">Progreso general</div>
                    <div class="panel-progress"><div class="panel-progress-bar"></div></div>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Descripción de actividad</th>
                                <th>Encargado</th>
                                <th>Estatus</th>
                                <th>Entrega</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="actividadesTbody">
                            <tr>
                                <td>Definir estructura CRUD de tareas</td>
                                <td><div class="member-chip"><div class="avatar-sm" style="background:#AEE5CD">JD</div> Analista</div></td>
                                <td><span class="status-badge status-done">Terminado</span></td>
                                <td>22/05/26</td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal(this)" title="Editar"
                                        data-desc="Definir estructura CRUD de tareas"
                                        data-fecha="2026-05-22"
                                        data-ndesc="">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Maquetación de vista de detalle</td>
                                <td><div class="member-chip"><div class="avatar-sm" style="background:#fde68a">MG</div> Diseñador</div></td>
                                <td><span class="status-badge status-progress">En Progreso</span></td>
                                <td>22/05/26</td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal(this)" title="Editar"
                                        data-desc="Maquetación de vista de detalle"
                                        data-fecha="2026-05-22"
                                        data-ndesc="">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>Conexión con el backend (API)</td>
                                <td><div class="member-chip"><div class="avatar-sm" style="background:#c7d2fe">MD</div> Programador</div></td>
                                <td><span class="status-badge status-todo">Por hacer</span></td>
                                <td>22/05/26</td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal(this)" title="Editar"
                                        data-desc="Conexión con el backend (API)"
                                        data-fecha="2026-05-22"
                                        data-ndesc="">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer">
                    <button class="btn-nueva" onclick="openNuevaModal()">
                        <i class="bi bi-plus-lg"></i> Nueva actividad
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ══ MODAL: EDITAR TAREA ══ -->
<div class="modal-overlay" id="editModal" onclick="handleOverlay(event,'editModal')">
    <div class="modal-box">
        <div class="modal-topbar">
            <button class="modal-back-btn" onclick="closeModal('editModal')">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h2>Editar tarea (Acciones)</h2>
        </div>
        <div class="modal-body">
            <div class="field-group">
                <input type="text" id="editTitulo" placeholder="Título de tarea">
            </div>
            <div class="field-group">
                <input type="date" id="editFecha">
            </div>
            <div class="field-group">
                <textarea id="editDesc" placeholder="Nueva Descripción"></textarea>
            </div>
            <button class="btn-hecho" onclick="guardarEdicion()">Hecho</button>
        </div>
    </div>
</div>

<!-- ══ MODAL: NUEVA ACTIVIDAD ══ -->
<div class="modal-overlay" id="nuevaModal" onclick="handleOverlay(event,'nuevaModal')">
    <div class="modal-box">
        <div class="modal-topbar">
            <button class="modal-back-btn" onclick="closeModal('nuevaModal')">
                <i class="bi bi-arrow-left"></i>
            </button>
            <h2>Nueva Actividad</h2>
        </div>
        <div class="modal-body">
            <div class="field-group">
                <input type="text" id="actEncargado" placeholder="Encargado">
            </div>
            <div class="field-group">
                <select id="actEstatus">
                    <option value="" disabled selected>Estado</option>
                    <option value="todo">Por hacer</option>
                    <option value="progress">En Progreso</option>
                    <option value="done">Terminado</option>
                </select>
            </div>
            <div class="field-group">
                <input type="date" id="actFecha" placeholder="Fecha de entrega">
            </div>
            <div class="field-group">
                <textarea id="actDesc" placeholder="Descripción"></textarea>
            </div>
            <button class="btn-hecho" onclick="agregarActividad()">Hecho</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="toast-msg" id="toast">
    <i class="bi bi-check-circle-fill"></i><span id="toastText"></span>
</div>

<script>
    // ── Variable para saber qué fila se está editando ──
    let filaEditando = null;

    function actualizarProgreso() {

    // Todas las filas
    const filas = document.querySelectorAll('#actividadesTbody tr');

    // Total actividades
    const total = filas.length;

    // Completadas
    let completadas = 0;

    filas.forEach(fila => {

        const estado = fila.querySelector('.status-badge');

        if (estado.classList.contains('status-done')) {
            completadas++;
        }

    });

    // Porcentaje
    const porcentaje = total > 0
        ? (completadas / total) * 100
        : 0;

    // Actualizar textos
    document.querySelector('.progress-badge').innerHTML =
        `<i class="bi bi-check2-circle"></i> ${completadas}/${total} completadas`;

    document.querySelector('.panel-info-right').textContent =
        `${completadas}/${total} completadas`;

    // Actualizar barra
    document.querySelector('.panel-progress-bar').style.width =
        porcentaje + '%';
}

    // ── Abrir modal EDITAR ──
    function openEditModal(btn) {
        filaEditando = btn.closest('tr');
        document.getElementById('editTitulo').value = btn.dataset.desc || '';
        document.getElementById('editFecha').value  = btn.dataset.fecha || '';
        document.getElementById('editDesc').value   = btn.dataset.ndesc || '';
        document.getElementById('editModal').classList.add('show');
        setTimeout(() => document.getElementById('editTitulo').focus(), 100);
    }

    // ── Guardar edición en la fila ──
    function guardarEdicion() {
        const titulo = document.getElementById('editTitulo').value.trim();
        const fecha  = document.getElementById('editFecha').value;
        const desc   = document.getElementById('editDesc').value.trim();

        if (!titulo) {
            highlight('editTitulo'); return;
        }

        if (filaEditando) {
            const celdas = filaEditando.querySelectorAll('td');
            // Actualizar descripción principal
            celdas[0].textContent = titulo;
            // Actualizar fecha
            if (fecha) {
                const d = new Date(fecha + 'T00:00:00');
                celdas[3].textContent = d.toLocaleDateString('es-MX', {day:'2-digit',month:'2-digit',year:'2-digit'});
            }
            // Guardar desc extra en el botón para futura edición
            const editBtn = filaEditando.querySelector('.btn-edit');
            if (editBtn) {
                editBtn.dataset.desc  = titulo;
                editBtn.dataset.fecha = fecha;
                editBtn.dataset.ndesc = desc;
            }
        }

        closeModal('editModal');
        actualizarProgreso();
        showToast('Tarea actualizada correctamente');
    }

    // ── Abrir modal NUEVA ACTIVIDAD ──
    function openNuevaModal() {
        document.getElementById('nuevaModal').classList.add('show');
        setTimeout(() => document.getElementById('actEncargado').focus(), 100);
    }

    // ── Agregar nueva actividad a la tabla ──
    const avatarColors = ['#AEE5CD','#fde68a','#c7d2fe','#fca5a5','#6ee7b7','#a5b4fc'];
    let colorIdx = 3;

    function agregarActividad() {
        const encargado = document.getElementById('actEncargado').value.trim();
        const estatus   = document.getElementById('actEstatus').value;
        const fecha     = document.getElementById('actFecha').value;
        const desc      = document.getElementById('actDesc').value.trim();

        if (!encargado || !estatus || !desc) {
            if (!encargado) { highlight('actEncargado'); return; }
            if (!estatus)   { highlight('actEstatus');   return; }
            if (!desc)      { highlight('actDesc');      return; }
        }

        const initials = encargado.split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2);
        const color    = avatarColors[colorIdx % avatarColors.length];
        colorIdx++;

        const statusMap = {
            done:     ['Terminado',  'status-done'],
            progress: ['En Progreso','status-progress'],
            todo:     ['Por hacer',  'status-todo']
        };
        const [label, cls] = statusMap[estatus];

        let fechaStr = '—';
        if (fecha) {
            const d = new Date(fecha + 'T00:00:00');
            fechaStr = d.toLocaleDateString('es-MX',{day:'2-digit',month:'2-digit',year:'2-digit'});
        }

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${desc}</td>
            <td><div class="member-chip"><div class="avatar-sm" style="background:${color}">${initials}</div> Miembro</div></td>
            <td><span class="status-badge ${cls}">${label}</span></td>
            <td>${fechaStr}</td>
            <td>
                <button class="btn-edit" onclick="openEditModal(this)" title="Editar"
                    data-desc="${desc}" data-fecha="${fecha}" data-ndesc="">
                    <i class="bi bi-pencil-fill"></i>
                </button>
            </td>
        `;
        document.getElementById('actividadesTbody').appendChild(tr);
        closeModal('nuevaModal');
        actualizarProgreso();
        showToast(`Actividad agregada correctamente`);
    }

    // ── Helpers ──
    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        if (id === 'nuevaModal') {
            ['actEncargado','actFecha','actDesc'].forEach(f => document.getElementById(f).value = '');
            document.getElementById('actEstatus').value = '';
        }
        if (id === 'editModal') filaEditando = null;
    }

    function handleOverlay(e, id) {
        if (e.target === document.getElementById(id)) closeModal(id);
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal('editModal');
            closeModal('nuevaModal');
        }
    });

    function highlight(id) {
        const el = document.getElementById(id);
        el.style.borderColor = '#e74c3c';
        el.focus();
        setTimeout(() => el.style.borderColor = '', 1500);
    }

    function showToast(msg) {
        const t = document.getElementById('toast');
        document.getElementById('toastText').textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    actualizarProgreso();
</script>
</body>
</html>