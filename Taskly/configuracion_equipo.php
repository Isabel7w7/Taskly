<?php
require_once 'Conexion.php';
require_once 'Miembro.php';

$conexionObj = new Conexion();
$db = $conexionObj->conectar();
$miembroControlador = new Miembro($db);

// [Tu lógica CRUD de backend se mantiene idéntica]
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nuevoMiembro = new Miembro($db, $_POST['nombre'], $_POST['correo'], $_POST['rol']);
    if ($nuevoMiembro->registrar()) { header("Location: configuracion_equipo.php?mensaje=registrado"); exit(); }
}
if (isset($_GET['eliminar'])) {
    if ($miembroControlador->eliminar($_GET['eliminar'])) { header("Location: configuracion_equipo.php?mensaje=eliminado"); exit(); }
}
if (isset($_GET['actualizar_id']) && isset($_GET['nuevo_rol'])) {
    if ($miembroControlador->actualizarRol($_GET['nuevo_rol'], $_GET['actualizar_id'])) { header("Location: configuracion_equipo.php?mensaje=rol_actualizado"); exit(); }
}

$listaMiembros = $miembroControlador->obtenerTodos();

// --- VARIABLES PARA EL HEADER ---
$tituloPagina = "Taskly - Configuración de Equipo";
$paginaActiva = "equipo"; // Activa el sidebar en miembros y botón Administrar Miembros
$placeholderBuscador = "Buscar miembro por nombre o rol...";
include 'includes/header.php'; // <--- INYECTAMOS LA INTERFAZ
?>

<?php if(isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        ¡Acción procesada con éxito!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-2" style="color: var(--primary);">Configuración del equipo</h1>
        <div class="d-flex gap-2">
            <a href="configuracion_equipo.php" class="btn btn-sm rounded-pill fw-bold px-3"
               style="background:var(--mint);color:var(--primary);">
                <i class="bi bi-people-fill me-1"></i> Configurar equipo
            </a>
            <a href="proyecto.php" class="btn btn-sm rounded-pill fw-bold px-3"
               style="background:var(--primary);color:var(--mint);border:1px solid var(--mint);">
                <i class="bi bi-folder2-open me-1"></i> Configurar proyectos
            </a>
        </div>
    </div>
    <input type="text" id="buscadorMiembros" class="form-control rounded-pill shadow-sm"
           style="max-width:260px;" placeholder="🔍 Buscar miembro...">
</div>
<div class="table-responsive table-custom p-0 mb-4">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th class="ps-4">Nombre</th>
                <th>Rol</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($listaMiembros)): ?>
                <?php foreach($listaMiembros as $m): 
                    $iniciales = strtoupper(substr($m['nombre'], 0, 2));
                ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle"><?= $iniciales ?></div>
                            <div>
                                <span class="fw-medium d-block"><?= htmlspecialchars($m['nombre']) ?></span>
                                <small class="text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($m['correo']) ?></small>
                            </div>
                        </div>
                    </td>
                    <td style="width: 35%;">
                        <select class="form-select border-0 bg-transparent fw-bold text-muted shadow-none" onchange="cambiarRolInstancia(this.value, <?= $m['id'] ?>)">
                            <option value="Analista" <?= (isset($m['rol']) && $m['rol'] == 'Analista') ? 'selected' : '' ?>>Rol: ANALISTA</option>
                            <option value="Programador" <?= (isset($m['rol']) && $m['rol'] == 'Programador') ? 'selected' : '' ?>>Rol: PROGRAMADOR</option>
                            <option value="Diseñador" <?= (isset($m['rol']) && $m['rol'] == 'Diseñador') ? 'selected' : '' ?>>Rol: DISEÑADOR</option>
                            <option value="Documentador" <?= (isset($m['rol']) && $m['rol'] == 'Documentador') ? 'selected' : '' ?>>Rol: DOCUMENTADOR</option>
                            <option value="Betatester" <?= (isset($m['rol']) && $m['rol'] == 'Betatester') ? 'selected' : '' ?>>Rol: BETATESTER</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-4">
                            <div class="text-center action-icon" data-bs-toggle="modal" data-bs-target="#modalAsignar">
                                <i class="bi bi-clipboard-check fs-4"></i><br><small style="font-size: 0.7rem;">Asignar</small>
                            </div>
                            <div class="text-center action-icon" onclick="abrirModalProgreso(60)">
                                <i class="bi bi-eye fs-4"></i><br><small style="font-size: 0.7rem;">Ver</small>
                            </div>
                            <div class="text-center action-icon text-danger" onclick="confirmarEliminacion(<?= $m['id'] ?>)">
                                <i class="bi bi-person-x fs-4"></i><br><small style="font-size: 0.7rem;">Eliminar</small>
                            </div>
                        </div>
                    </td>
                    <!-- MODAL: Confirmar Eliminar -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">¿Eliminar miembro?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Esta acción no se puede deshacer.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">Eliminar</a>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Ver progreso -->
<div class="modal fade" id="modalVer" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Progreso del miembro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p id="textoPorcentaje" class="fw-bold mb-2">0% Completado</p>
        <div class="progress">
          <div id="barraPorcentaje" class="progress-bar bg-success" role="progressbar" style="width:0%"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Asignar tarea -->
<div class="modal fade" id="modalAsignar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Asignar tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label fw-bold">Descripción de la tarea</label>
        <input type="text" class="form-control" placeholder="Escribe la tarea...">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary">Asignar</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Añadir miembro -->
<div class="modal fade" id="modalAñadir" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Añadir miembro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="configuracion_equipo.php">
          <input type="hidden" name="accion" value="crear">
          <div class="mb-3">
            <label class="form-label fw-bold">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Correo</label>
            <input type="email" name="correo" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Rol</label>
            <select name="rol" class="form-select">
              <option value="Analista">Analista</option>
              <option value="Programador">Programador</option>
              <option value="Diseñador">Diseñador</option>
              <option value="Documentador">Documentador</option>
              <option value="Betatester">Betatester</option>
            </select>
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Registrar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center py-4 text-muted">No hay miembros en el equipo todavía.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    <button class="btn btn-secondary rounded-pill px-4 py-2 text-dark bg-secondary-subtle border-0 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAñadir">
        Añadir miembro <i class="bi bi-plus-circle ms-2"></i>
    </button>
</div>


<script>
    function cambiarRolInstancia(nuevoRol, idUsuario) {
        window.location.href = `configuracion_equipo.php?actualizar_id=${idUsuario}&nuevo_rol=${nuevoRol}`;
    }

    function confirmarEliminacion(idUsuario) {
        var modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
        document.getElementById('btnConfirmarEliminar').href = `configuracion_equipo.php?eliminar=${idUsuario}`;
        modal.show();
    }

    function abrirModalProgreso(porcentaje) {
        document.getElementById('textoPorcentaje').innerText = porcentaje + '% Completado';
        document.getElementById('barraPorcentaje').style.width = porcentaje + '%';
        if(porcentaje > 5) {
            document.getElementById('barraPorcentaje').innerText = porcentaje + '%';
        } else {
            document.getElementById('barraPorcentaje').innerText = '';
        }
        var modal = new bootstrap.Modal(document.getElementById('modalVer'));
        modal.show();
    }
    document.getElementById('buscadorMiembros').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
<?php include 'includes/footer.php'; // <--- CIERRE DE INTERFAZ Y SCRIPTS GLOBALES ?>

</body>
</html>