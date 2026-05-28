# Taskly — Documentación del Proyecto

Taskly es una aplicación web de gestión de proyectos y equipos de trabajo, desarrollada en PHP con MySQL. Permite a los usuarios crear equipos, asignar tareas, gestionar miembros y llevar el seguimiento del avance de cada proyecto.

---

## Tecnologías utilizadas

- **Backend:** PHP 8+ (POO con clases y PDO)
- **Base de datos:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5, Bootstrap Icons, Google Fonts (Poppins)
- **Sesiones:** PHP Sessions nativas

---

## Estructura de la base de datos

Definida en `SQL_Script.sql`. Contiene cuatro tablas:

| Tabla | Descripción |
|---|---|
| `users` | Usuarios registrados (id, nombre, username, email, password, rol) |
| `equipo` | Equipos de trabajo (id_equipo, nombre_equipo, fecha_creacion) |
| `integrante` | Relación usuario–equipo con rol dentro del equipo |
| `tareas` | Tareas asociadas a usuarios (descripcion, estatus, fecha_entrega) |

---

## Descripción de archivos

### Configuración y conexión

#### `Conexion.php`
Clase PHP responsable de establecer la conexión a la base de datos mediante PDO. Define el host, nombre de la base de datos (`taskly_db`), usuario y contraseña. Retorna el objeto `PDO` configurado con manejo de excepciones y codificación UTF-8.

#### `SQL_Script.sql`
Script SQL para crear e inicializar la base de datos `Taskly_DB`. Crea las cuatro tablas principales (`users`, `equipo`, `integrante`, `tareas`) con sus respectivas claves primarias, foráneas y restricciones de unicidad.

---

### Autenticación

#### `login.php`
Página pública de inicio de sesión. Muestra el formulario de acceso (correo + contraseña). Incluye protección inversa: si el usuario ya tiene sesión activa, redirige automáticamente al dashboard. Muestra mensajes flash de error (credenciales incorrectas) y de cierre de sesión exitoso provenientes de `$_SESSION`.

#### `validar.php`
Procesador POST del formulario de login. Recibe el correo y la contraseña, utiliza `Usuario::buscarPorEmail()` para localizar al usuario en la BD y `password_verify()` para validar la contraseña hasheada. Si las credenciales son correctas, regenera el ID de sesión, guarda los datos del usuario en `$_SESSION['taskly_user']` y redirige al dashboard.

#### `register.php`
Procesador POST del formulario de registro. Valida que todos los campos estén presentes y que el correo tenga formato válido. Verifica que el correo no esté ya registrado en la BD, hashea la contraseña con `password_hash()` e inserta el nuevo usuario. Redirige a `login.php` con un mensaje de éxito.

#### `logout.php`
Destruye la sesión del usuario de forma segura: vacía `$_SESSION`, invalida la cookie de sesión en el navegador del cliente y llama a `session_destroy()`. Redirige a `login.php` con el parámetro `?logout=1`.

---

### Modelos (Clases PHP)

#### `Usuario.php`
Clase que representa a un usuario del sistema. Contiene propiedades privadas (id, nombre, username, email, password, rol) con sus respectivos getters. El método estático `buscarPorEmail($email)` realiza una consulta preparada a la BD y, si encuentra el registro, retorna un objeto `Usuario` instanciado con los datos reales.

#### `Miembro.php`
Clase para gestionar los miembros del equipo directamente sobre la tabla `users`. Implementa las operaciones CRUD:
- `registrar()` — Inserta un nuevo miembro.
- `obtenerTodos()` — Retorna todos los usuarios ordenados por ID descendente.
- `actualizarRol($nuevoRol, $idUsuario)` — Actualiza el rol de un usuario.
- `eliminar($idUsuario)` — Elimina un usuario por su ID.

#### `Tarea.php`
Clase para gestionar las tareas del proyecto sobre la tabla `tareas`. Implementa las operaciones CRUD:
- `registrar()` — Crea una nueva tarea con usuario asignado, estatus y fecha de entrega.
- `obtenerTodas()` — Retorna todas las tareas con JOIN a `users` para incluir nombre y rol del encargado.
- `obtenerPorUsuario($usuario_id)` — Filtra las tareas de un usuario específico.
- `actualizar(...)` — Modifica descripción, encargado, estatus y fecha de entrega.
- `eliminar($id)` — Elimina una tarea por su ID.

---

### Vistas / Páginas principales

#### `dashboard.php`
Página principal tras iniciar sesión (ruta privada). Verifica la sesión activa, muestra un saludo dinámico según la hora del día, y lista los equipos a los que pertenece el usuario autenticado (consulta con JOIN entre `equipo` e `integrante`). Desde aquí el usuario puede navegar a un equipo o crear uno nuevo. Requiere `includes/header.php` y `includes/footer.php`.

#### `proyecto.php`
Página de detalle de un equipo específico, accesible vía `?id_equipo={id}`. Verifica que el equipo exista y que el usuario tenga sesión activa. Consulta las tareas del usuario para ese equipo y las muestra en una tabla con estatus, fecha de entrega e íconos de acción. Implementa su propia interfaz HTML completa (no usa el header compartido).

#### `VerProyecto.php`
Vista alternativa/detallada de un proyecto. Incluye su propia estructura HTML con topbar, sidebar y layout de pantalla completa usando la paleta de colores de Taskly. Está orientada a mostrar el contenido de un proyecto con más detalle visual.

#### `proyectos.php`
Página de gestión de tareas para un proyecto (versión con header compartido). Permite crear nuevas tareas y editar las existentes mediante modales de Bootstrap. Lista todas las tareas con su encargado, estatus, fecha de entrega y opciones de edición. Usa las clases `Tarea` y `Miembro` para todas las operaciones de BD.

#### `configuracion_equipo.php`
Página de administración de los miembros de un equipo. Muestra la lista de integrantes con su nombre, correo y rol editable mediante un `<select>` en línea. Permite añadir nuevos miembros, cambiar roles, ver el progreso y eliminar integrantes mediante modales de Bootstrap. Incluye un buscador en tiempo real con JavaScript. Usa la clase `Miembro` para todas las operaciones.

#### `Perfil.php`
Página privada del perfil del usuario. Muestra los datos actuales de la sesión (nombre, correo, rol) y presenta un formulario para actualizar la información personal. Requiere sesión activa y utiliza el header/footer compartido.

#### `actualizarPerfil.php`
Procesador POST del formulario de perfil. Valida que nombre y rol no estén vacíos, ejecuta un `UPDATE` sobre la tabla `users` con PDO y, si tiene éxito, actualiza también los datos almacenados en `$_SESSION['taskly_user']`. Redirige a `Perfil.php?success=1`.

#### `Pagina_de_Pagos.html`
Página estática de planes y suscripciones. Presenta dos opciones: el plan **Básico** (gratuito, hasta 3 equipos) y el plan **Pro** ($10/mes, equipos ilimitados y funcionalidades avanzadas). Incluye un botón de regreso al dashboard. No requiere PHP ni sesión.

---

### Procesadores de formulario

#### `procesar_equipo.php`
Procesador POST para la creación de un nuevo equipo. Valida que la petición sea POST y que exista sesión activa. Usa una transacción PDO para insertar primero el equipo en la tabla `equipo` y luego vincular al usuario creador como `Administrador` en la tabla `integrante`. Si algo falla, ejecuta `rollBack()`. Redirige al dashboard al terminar.

---

## Flujo general de la aplicación

```
login.php  →  validar.php  →  dashboard.php
                                   │
                    ┌──────────────┼──────────────┐
                    ▼              ▼              ▼
              proyecto.php   configuracion   Perfil.php
              (tareas por    _equipo.php     (editar datos)
               equipo)       (miembros)
                    │
                    └──► VerProyecto.php
                         proyectos.php

dashboard.php  ──►  procesar_equipo.php  (crear equipo)
Perfil.php     ──►  actualizarPerfil.php (guardar cambios)
cualquier pág  ──►  logout.php           (cerrar sesión)
```

---

## Seguridad implementada

- Contraseñas hasheadas con `password_hash()` / verificadas con `password_verify()`.
- Consultas preparadas con PDO en todos los accesos a la BD (prevención de inyección SQL).
- Salidas HTML escapadas con `htmlspecialchars()` (prevención de XSS).
- Regeneración del ID de sesión tras autenticación exitosa (`session_regenerate_id(true)`).
- Rutas privadas protegidas verificando `$_SESSION['taskly_user']` al inicio de cada página.
- Transacciones PDO en operaciones de múltiples tablas (creación de equipo).

---

## Instalación

1. Importar `SQL_Script.sql` en MySQL para crear la base de datos y las tablas.
2. Editar `Conexion.php` con las credenciales correctas de tu servidor MySQL.
3. Colocar todos los archivos en la carpeta raíz del servidor web (p.ej. `htdocs` en XAMPP).
4. Asegurarse de que exista la carpeta `includes/` con los archivos `header.php` y `footer.php`.
5. Acceder a `login.php` en el navegador para comenzar.
