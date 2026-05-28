<?php

/**
 * ============================================================
 * LOGIN.PHP — Taskly
 * ============================================================

 */

// ── Iniciar sesión ANTES de cualquier salida HTML ───────────
session_start();

// ── PROTECCIÓN INVERSA: si ya hay sesión activa, redirigir ──
// $_SESSION es el arreglo superglobal del servidor. Si la clave
// 'taskly_user' ya existe, el usuario YA inició sesión.
if (isset($_SESSION['taskly_user'])) {
    header("Location: dashboard.php");
    exit();
}

// ── Recuperar mensajes de error o éxito de la sesión ────────
// validar.php y logout.php depositan mensajes aquí.
$login_error   = $_SESSION['login_error']   ?? null;
$logout_msg    = isset($_GET['logout'])      ? "Has cerrado sesión correctamente." : null;

// Limpiar el error de sesión una vez que lo leemos (flash message)
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly | Gestión Ágil y Zen</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-blue: #102340;
            --accent-mint: #AEE5CD;
            --bg-white: #ffffff;
            --light-grey: #f4f7f6;
            --mid-grey: #cccccc;
            --text-dark: #333;
            --text-light: #666;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--bg-white);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Botones personalizados */
        .btn-custom {
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-primary-custom {
            background-color: var(--primary-blue);
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background-color: #1a3a6b;
            color: white;
        }

        .btn-mint {
            background-color: var(--accent-mint);
            color: var(--primary-blue);
            padding: 12px 40px;
            border: none;
            font-weight: 600;
            border-radius: 20px;
        }

        .btn-mint:hover {
            filter: brightness(0.9);
        }

        .image-placeholder {
            background: repeating-linear-gradient(45deg, #e0e0e0, #e0e0e0 10px, #d0d0d0 10px, #d0d0d0 20px);
            border-radius: 10px;
            width: 100%;
            height: 100%;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: bold;
        }

        /* Navegación */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .logo span {
            color: var(--accent-mint);
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-btn {
            cursor: pointer;
            font-weight: 600;
            color: var(--primary-blue);
            padding: 8px 20px;
            border-radius: 20px;
            transition: 0.3s;
        }

        .nav-btn:hover {
            background-color: var(--light-grey);
        }

        .nav-btn-register {
            background-color: var(--accent-mint);
        }

        .nav-btn-register:hover {
            filter: brightness(0.9);
            background-color: var(--accent-mint);
        }

        /* Control de visibilidad de secciones */
        .page-section {
            display: none;
        }

        .page-section.active {
            display: block;
        }

        .padding-section {
            padding: 60px 5%;
        }

        /* Hero Section */
        .hero-section {
            background-color: var(--light-grey);
            padding: 80px 5%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url("images/ventajas.png") no-repeat center center fixed;
            background-size: cover;
        }

        .hero-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 80px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .hero-box h2 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        /* Por qué escogernos */
        .why-us {
            padding: 60px 10%;
            background: white;
        }

        .why-us-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background-color: var(--mid-grey);
            border-radius: 15px;
            overflow: hidden;
            min-height: 250px;
        }

        .why-us-text {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .why-us-text h3 {
            text-transform: uppercase;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* Funciones */
        .features-section {
            padding: 60px 10%;
            background-color: var(--light-grey);
        }

        .section-title {
            text-transform: uppercase;
            margin-bottom: 40px;
            font-size: 1.5rem;
            letter-spacing: 1px;
            text-align: center;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background-color: var(--mid-grey);
            padding: 40px 5%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            border-top: 2px solid #bbb;
        }

        .footer-logo-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .footer-logo-brand .circle {
            width: 40px;
            height: 40px;
            background: var(--text-light);
            border-radius: 50%;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li {
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .footer-column ul li a {
            text-decoration: none;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-column ul li a:hover {
            color: var(--primary-blue);
        }

        /* Auth Container */
        .auth-container {
            max-width: 450px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .google-btn {
            width: 100%;
            padding: 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            cursor: pointer;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .why-us-card {
                grid-template-columns: 1fr;
            }
        }

        /* Badge de sesión activa (indicador visual PHP) */
        .session-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .session-badge .dot {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo" onclick="showPage('landing')">
            <i class="bi bi-circle-fill" style="color: #999;"></i> TASK<span>LY</span>
        </div>
        <div class="nav-links">
            <span class="nav-btn" onclick="showPage('login')">Iniciar Sesión</span>
            <span class="nav-btn nav-btn-register" onclick="showPage('register')">Crear Cuenta</span>
        </div>
    </nav>

    <!-- ══════════════════════════════════════════════════════════
     SECCIÓN LANDING
════════════════════════════════════════════════════════════ -->
    <div id="landing" class="page-section active">
        <section class="hero-section">
            <div class="hero-box">
                <h2>Regístrate Ahora</h2>
                <button class="btn-mint" onclick="showPage('register')">Comenzar</button>
            </div>
        </section>

        <section class="container mt-5">
            <h3 class="section-title">Galería de Proyectos</h3>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner rounded" style="box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div class="carousel-item active">
                        <div class="image-placeholder" style="height: 400px;">
                            <img src="Images/Taskly.png" class="d-block w-100">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-placeholder" style="height: 400px;">
                            <img src="Images/Organizacion.png" class="d-block w-100">
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="image-placeholder" style="height: 400px;">
                            <img src="Images/Objetivos.png" class="d-block w-100">
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </section>

        <section class="why-us">
            <div class="why-us-card shadow-sm">
                <div class="why-us-text">
                    <h3>¿Por qué escogernos?</h3>
                    <p>En Taskly entendemos que el tiempo es tu recurso más valioso. Hemos diseñado una plataforma que elimina el ruido visual y se centra en lo que realmente importa: la productividad ágil y zen.</p>
                    <p>Con nosotros, obtienes una herramienta intuitiva que centraliza tu trabajo en equipo, facilita la verificación de actividades y fomenta un entorno digital libre de estrés. ¡Simplifica tu día a día con nosotros!</p>
                </div>
                <div class="image-placeholder border-start border-white">
                    <img src="Images/Tablero de kabal.png" class="d-block w-100">
                </div>
            </div>
        </section>

        <section class="features-section">
            <h3 class="section-title">Disfruta de diferentes funciones</h3>
            <div class="container">
                <div class="accordion shadow-sm" id="accordionFeatures">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false">
                                <i class="bi bi-kanban me-2 text-primary"></i> Tableros Ágiles e Intuitivos
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFeatures">
                            <div class="accordion-body">Visualiza tu flujo de trabajo de principio a fin. Mueve tarjetas, asigna prioridades y mantén a todo tu equipo sincronizado con un simple vistazo.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false">
                                <i class="bi bi-people me-2 text-success"></i> Colaboración en Tiempo Real
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFeatures">
                            <div class="accordion-body">Trabaja en equipo de manera óptima con la verificación de actividades, comentarios integrados y asignación de roles.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false">
                                <i class="bi bi-bar-chart-line me-2 text-warning"></i> Reportes y Métricas
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFeatures">
                            <div class="accordion-body">Mide el progreso de tus proyectos con gráficas sencillas que te ayudan a identificar cuellos de botella y celebrar las metas alcanzadas.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer>
            <div class="footer-logo">
                <div class="footer-logo-brand">
                    <div class="circle"></div> TASKLY
                </div>
                <p class="mt-2 text-muted">Copyright © 2026<br>Taskly Team</p>
            </div>
            <div class="footer-column">
                <h5>Redes sociales</h5>
                <ul>
                    <li><a href="#"><i class="bi bi-instagram"></i> Instagram</a></li>
                    <li><a href="#"><i class="bi bi-facebook"></i> Facebook</a></li>
                    <li><a href="#"><i class="bi bi-twitter-x"></i> X</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h5>Información</h5>
                <ul>
                    <li><a href="#">Novedades</a></li>
                    <li><a href="#">Sobre nosotros</a></li>
                    <li><a href="#">Preguntas frecuentes</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h5>Soporte</h5>
                <ul>
                    <li><a href="#">Contáctanos</a></li>
                    <li><a href="#">La queso</a></li>
                    <li><a href="#">Soporte</a></li>
                </ul>
            </div>
        </footer>
    </div>

    <!-- ══════════════════════════════════════════════════════════
     SECCIÓN REGISTRO (client-side, sin conexión a BD)
════════════════════════════════════════════════════════════ -->
    <section id="register" class="page-section padding-section">
        <div class="auth-container">
            <h2 class="text-center text-primary mb-4 fw-bold">Crear Cuenta</h2>

            <div id="registerMessage" class="alert d-none" role="alert"></div>

            <button class="google-btn">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" width="20" alt="Google">
                Registrarse con Google
            </button>
            <div class="d-flex align-items-center my-4">
                <hr class="flex-grow-1"><span class="mx-3 text-muted">o</span>
                <hr class="flex-grow-1">
            </div>

            <form method="POST" action="register.php">

                <div class="mb-3">
                    <label class="form-label text-muted small">Nombre Completo</label>
                    <input type="text"
                        id="regName"
                        name="nombre"
                        class="form-control"
                        placeholder="Ej. Juan Pérez">
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Nombre de Usuario</label>
                    <input type="text"
                        id="regUsername"
                        name="username"
                        class="form-control"
                        placeholder="Ej. Juanin123">
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Correo Electrónico</label>
                    <input type="text"
                        id="regEmail"
                        name="email"
                        class="form-control"
                        placeholder="correo@ejemplo.com">
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small">Contraseña</label>
                    <input type="password"
                        id="regPassword"
                        name="password"
                        class="form-control"
                        placeholder="Mínimo 8 caracteres">
                </div>

                <button type="submit"
                    class="btn btn-primary-custom btn-custom w-100 mt-2">
                    Crear Cuenta
                </button>

            </form>
            <p class="text-center mt-3 small">
                ¿Ya tienes cuenta? <a href="#" class="text-decoration-none" onclick="showPage('login')">Inicia sesión</a>
            </p>
        </div>
    </section>

    <!-- ══════════════════════════════════════════════════════════
     SECCIÓN LOGIN — Formulario que envía a validar.php
     El action apunta a validar.php para procesamiento en PHP.
════════════════════════════════════════════════════════════ -->
    <section id="login" class="page-section padding-section">
        <div class="auth-container">
            <h2 class="text-center text-primary mb-4 fw-bold">Iniciar Sesión</h2>

            <?php
            /*
         * ── Mensaje de cierre de sesión ─────────────────────
         * Si logout.php redirigió aquí con ?logout=1,
         * mostramos un aviso verde de confirmación.
         */
            if ($logout_msg): ?>
                <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <?= htmlspecialchars($logout_msg) ?>
                </div>
            <?php endif; ?>

            <?php
            /*
         * ── Mensaje de error de validación ──────────────────
         * validar.php guarda el error en $_SESSION['login_error']
         * y redirige aquí. Lo mostramos y luego lo borramos
         * (unset ya se hizo arriba, es un "flash message").
         */
            if ($login_error): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <?= htmlspecialchars($login_error) ?>
                </div>
            <?php endif; ?>

            <!--
            FORMULARIO DE LOGIN
            method="POST"  → los datos viajan en el cuerpo HTTP (no en la URL)
            action="validar.php" → PHP recibe y procesa las credenciales
        -->
            <form method="POST" action="validar.php">

                <div class="mb-3">
                    <label class="form-label text-muted small">Correo Electrónico</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="correo@ejemplo.com"
                        required
                        autocomplete="email">
                    <!-- name="email" → PHP lo lee con $_POST['email'] en validar.php -->
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted small">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="********"
                        required
                        autocomplete="current-password">
                    <!-- name="password" → PHP lo lee con $_POST['password'] en validar.php -->
                </div>

                <!--
                Token CSRF: protege contra ataques Cross-Site Request Forgery.
                Se genera en PHP, se incluye como campo oculto y se verifica
                en validar.php antes de procesar las credenciales.
            -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <button type="submit" class="btn btn-primary-custom btn-custom w-100">Entrar</button>
            </form>

            <div class="d-flex align-items-center my-4">
                <hr class="flex-grow-1"><span class="mx-3 text-muted">o</span>
                <hr class="flex-grow-1">
            </div>
            <button class="google-btn">Iniciar con Google</button>

            <p class="text-center mt-3 small">
                ¿No tienes cuenta? <a href="#" class="text-decoration-none" onclick="showPage('register')">Regístrate</a>
            </p>
        </div>
    </section>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ── Navegación entre secciones ──────────────────────────
        // PHP ya maneja la sesión; aquí solo controlamos la vista
        function showPage(pageId) {
            document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
            document.getElementById(pageId).classList.add('active');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ── Si venimos de ?section=login (error o logout), mostrar login ─
        (function() {
            <?php if ($login_error || $logout_msg): ?>
                showPage('login');
            <?php endif; ?>
        })();

        // ── LÓGICA DE REGISTRO (client-side, sin tocar la BD) ──
        // document.getElementById('registerForm').addEventListener('submit', function(e) {
        //     e.preventDefault();

        //     const name = document.getElementById('regName').value.trim();
        //     const email = document.getElementById('regEmail').value.trim();
        //     const password = document.getElementById('regPassword').value.trim();
        //     const msgBox = document.getElementById('registerMessage');

        //     msgBox.className = 'alert d-none';
        //     msgBox.innerHTML = '';

        //     let errors = [];
        //     if (!name || !email || !password) errors.push("Todos los campos son obligatorios.");
        //     if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push("Correo inválido.");
        //     if (password && password.length < 8) errors.push("La contraseña debe tener al menos 8 caracteres.");

        //     if (errors.length > 0) {
        //         msgBox.innerHTML = errors.join('<br>');
        //         msgBox.classList.remove('d-none');
        //         msgBox.classList.add('alert-danger');
        //     } else {
        //         // En producción esto se enviaría a un register.php
        //         // Por ahora simulamos el registro exitoso
        //         msgBox.innerHTML = "¡Cuenta creada! Redirigiendo...";
        //         msgBox.classList.remove('d-none');
        //         msgBox.classList.add('alert-success');
        //         setTimeout(() => showPage('login'), 1200);
        //     }
        // });
    </script>

    <?php
    /*
 * ── Generar token CSRF al final para la próxima carga ───────
 * Se guarda en $_SESSION para que validar.php pueda compararlo.
 * bin2hex(random_bytes(32)) genera 64 caracteres hexadecimales
 * aleatorios criptográficamente seguros.
 */
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    ?>
</body>

</html>