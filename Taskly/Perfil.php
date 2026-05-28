<?php

session_start();

if (!isset($_SESSION['taskly_user'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['taskly_user'];

$nombre = htmlspecialchars($usuario['nombre']);
$email  = htmlspecialchars($usuario['email']);
$rol    = htmlspecialchars($usuario['rol']);

$tituloPagina = "Taskly | Perfil";
$paginaActiva = "perfil";

include 'includes/header.php';

?>
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

        /* ══ PERFIL CARD ══ */
        .perfil-card {
            background: var(--white);
            border-radius: 22px;
            padding: 40px 44px;
            max-width: 780px;
            width: 100%;
            margin: 0 auto;
            box-shadow: 0 8px 32px rgba(16, 35, 64, 0.09);
            border: 2px solid var(--mint-soft);
            position: relative;
            overflow: hidden;
        }

        /* franja superior decorativa */
        .perfil-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--mint));
        }

        .perfil-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .perfil-divider {
            border: none;
            border-top: 2px solid var(--mint-soft);
            margin-bottom: 32px;
        }

        /* ══ AVATAR + FORM ══ */
        .perfil-body {
            display: flex;
            gap: 36px;
            align-items: flex-start;
        }

        /* Avatar */
        .avatar-wrap {
            position: relative;
            flex-shrink: 0;
        }

        .avatar-circle {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #1e4a80 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--mint);
            border: 4px solid var(--mint);
            overflow: hidden;
            cursor: pointer;
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .avatar-circle i {
            transition: opacity 0.2s;
        }

        /* botón cámara sobre el avatar */
        .avatar-cam {
            position: absolute;
            bottom: 4px;
            right: 4px;
            width: 30px;
            height: 30px;
            background: var(--mint);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: var(--primary);
            cursor: pointer;
            border: 2px solid var(--white);
            transition: background 0.2s, transform 0.15s;
        }

        .avatar-cam:hover {
            background: var(--mint-dark);
            transform: scale(1.1);
        }

        /* input oculto para subir foto */
        #fotoInput {
            display: none;
        }

        .avatar-note {
            font-size: 0.7rem;
            color: #9ab0c0;
            text-align: center;
            margin-top: 8px;
            max-width: 110px;
        }

        /* ══ FORMULARIO ══ */
        .perfil-form {
            flex: 1;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            background: #f0f7f4;
            border: 1.5px solid var(--mint);
            border-radius: 12px;
            padding: 11px 16px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.87rem;
            color: var(--primary);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(174, 229, 205, 0.4);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #9ab0c0;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 110px;
        }

        /* ══ ACCIONES ══ */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid var(--mint-soft);
        }

        .btn-cancelar {
            background: var(--white);
            color: var(--primary);
            border: 1.5px solid var(--mint);
            border-radius: 12px;
            padding: 11px 28px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-cancelar:hover {
            background: var(--mint-soft);
            color: var(--primary);
        }

        .btn-guardar {
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 12px;
            padding: 11px 28px;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(16, 35, 64, 0.22);
            transition: background 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-guardar i {
            color: var(--mint);
        }

        .btn-guardar:hover {
            background: #1a3a60;
            transform: translateY(-1px);
        }

        /* ══ TOAST ══ */
        .toast-msg {
            display: none;
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: var(--primary);
            color: var(--white);
            padding: 13px 20px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            z-index: 2000;
            border-left: 4px solid var(--mint);
            box-shadow: 0 8px 28px rgba(16, 35, 64, 0.3);
        }

        .toast-msg.show {
            display: block;
            animation: toastIn 0.3s ease;
        }

        @keyframes toastIn {
            from {
                transform: translateY(14px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .toast-msg i {
            margin-right: 8px;
            color: var(--mint);
        }

        /* ══ HAMBURGER BUTTON ══ */
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

        /* Overlay para cerrar sidebar en móvil */
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

        /* ══ TABLET (≤ 992px): sidebar solo iconos ══ */
        @media (max-width: 992px) {
            .sidebar {
                width: 68px;
                padding: 28px 10px;
                overflow: visible;
            }

            .sidebar .nav-item span,
            .sidebar .teams-label,
            .sidebar .btn-create-team span,
            .sidebar .team-list,
            .sidebar .avatar-note {
                display: none;
            }

            .nav-item {
                justify-content: center;
                padding: 11px;
                gap: 0;
            }

            .btn-create-team {
                justify-content: center;
                padding: 10px;
                width: 100%;
            }

            .btn-create-team i {
                margin: 0;
                font-size: 1.1rem;
            }

            .main-area {
                padding: 30px 24px;
            }
        }

        /* ══ TABLET PEQUEÑA (≤ 768px): sidebar oculto, hamburger ══ */
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -260px;
                width: 230px !important;
                height: 100%;
                z-index: 200;
                padding: 28px 16px;
                transition: left 0.3s ease;
            }

            /* restaurar textos del sidebar en modo drawer */
            .sidebar .nav-item span,
            .sidebar .teams-label,
            .sidebar .btn-create-team span,
            .sidebar .team-list {
                display: unset;
            }

            .nav-item {
                justify-content: flex-start;
                padding: 11px 14px;
                gap: 13px;
            }

            .btn-create-team {
                justify-content: flex-start;
                padding: 10px 14px;
            }

            .btn-create-team i {
                font-size: 1rem;
            }

            .sidebar.open {
                left: 0;
            }

            .main-wrapper {
                flex-direction: column;
            }

            .main-area {
                padding: 20px 16px;
            }

            .perfil-card {
                padding: 28px 20px;
            }

            .perfil-body {
                flex-direction: column;
                align-items: center;
            }

            .perfil-form {
                width: 100%;
            }
        }

        /* ══ MÓVIL (≤ 480px) ══ */
        @media (max-width: 480px) {
            .topbar {
                padding: 10px 16px;
            }

            .logo {
                font-size: 1rem;
            }

            .main-area {
                padding: 14px 10px;
            }

            .perfil-card {
                padding: 22px 14px;
                border-radius: 16px;
            }

            .perfil-title {
                font-size: 1.4rem;
            }

            .avatar-circle {
                width: 90px;
                height: 90px;
                font-size: 2.4rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn-cancelar,
            .btn-guardar {
                width: 100%;
                justify-content: center;
            }

            .toast-msg {
                bottom: 14px;
                right: 14px;
                left: 14px;
                text-align: center;
            }
        }
    </style>

<!-- ══ CONTENT ══ -->
        <div class="content">
            <div class="main-area">

                <div class="perfil-card">
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="dashboard.php" class="text-decoration-none" style="color: var(--primary); font-size: 1.6rem;" title="Regresar al Dashboard">
        <i class="bi bi-arrow-left-short"></i>
    </a>
    <h1 class="m-0" style="font-size: 1.8rem; font-weight: 700;">Mi Perfil</h1>
</div>                    <hr class="perfil-divider">

                    <div class="perfil-body">

                        <!-- Avatar -->
                        <div class="avatar-wrap">
                            <div class="avatar-circle" onclick="document.getElementById('fotoInput').click()" title="Cambiar foto">
                                <img id="avatarImg" src="" alt="Foto de perfil">
                                <i class="bi bi-person-fill" id="avatarIcon"></i>
                            </div>
                            <div class="avatar-cam" onclick="document.getElementById('fotoInput').click()" title="Cambiar foto">
                                <i class="bi bi-camera-fill"></i>
                            </div>
                            <input type="file" id="fotoInput" accept="image/*">
                            <p class="avatar-note">Toca para cambiar tu foto</p>
                        </div>

                        <!-- Formulario -->
                        <form class="perfil-form" method="POST" action="actualizarPerfil.php">

                            <div class=" form-group">
                                <label>Nombre</label>
                                <input type="text"
                                    id="nombre"
                                    name="nombre"
                                    value="<?= $nombre ?>">
                            </div>

                            <div class="form-group">
                                <label>rol</label>
                                <input type="text" 
                                id="rol" name="rol" 
                                value="<?= $rol ?>">
                            </div>

                            <div class="form-group">
                                <label>Número de teléfono</label>
                                <input type="tel" id="telefono" placeholder="Ej: 461 123 4567"
                                    oninput="this.value=this.value.replace(/[^0-9 ]/g,'')">
                            </div>

                            <div class="form-group">
                                <label>Bibliografía</label>
                                <textarea id="bio" placeholder="Cuéntanos un poco sobre ti…"></textarea>
                            </div>

                            <div class="form-actions">
                                <a href="dashboard.php" class="btn-cancelar">Cancelar</a>
                                <button type="submit" class="btn-guardar">
                                    <i class="bi bi-check2"></i> Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

<?php include 'includes/footer.php'; ?>