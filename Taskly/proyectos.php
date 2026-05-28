<?php
require_once 'Conexion.php';
require_once 'Tarea.php'; 
require_once 'Miembro.php'; 

$conexionObj = new Conexion();
$db = $conexionObj->conectar();

$tareaControlador = new Tarea($db, '', null, '', null);
$miembroControlador = new Miembro($db);

// [Tu lógica de Procesar Formulario se mantiene exactamente igual aquí]
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear_tarea') {
    $encargado = !empty($_POST['usuario_id']) ? $_POST['usuario_id'] : null;
    $fecha = !empty($_POST['fecha_entrega']) ? $_POST['fecha_entrega'] : null;
    $nuevaTarea = new Tarea($db, $_POST['descripcion'], $encargado, $_POST['estatus'], $fecha);
    if ($nuevaTarea->registrar()) { header("Location: proyectos.php?msg=creada"); exit(); }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'editar_tarea') {
    if ($tareaControlador->actualizar($_POST['id_tarea'], $_POST['descripcion'], $_POST['usuario_id'], $_POST['estatus'], $_POST['fecha_entrega'])) {
        header("Location: proyectos.php?msg=actualizada"); exit();
    }
}

$listaTareas = $tareaControlador->obtenerTodas();
$listaUsuarios = $miembroControlador->obtenerTodos(); 

// --- VARIABLES PARA EL HEADER ---
$tituloPagina = "Taskly - Proyecto I";
$paginaActiva = "proyectos"; // Activa el sidebar en proyectos y botón Configurar equipo
$placeholderBuscador = "Buscar tareas...";
include 'includes/header.php'; // <--- INYECTAMOS LA INTERFAZ
?>

<div class="project-card text-dark position-relative mb-4">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1 class="fw-bold mb-1">Proyecto I</h1>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">Fecha de modificación: 13/05/2026</p>
        </div>
    </div>
    
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0 text-muted" style="font-size: 0.85rem;">
            <li class="breadcrumb-item">Mis equipos</li>
            <li class="breadcrumb-item">Equipo I</li>
            <li class="breadcrumb-item">Mis proyectos</li>
            <li class="breadcrumb-item active" aria-current="page">Proyecto I</li>
        </ol>
    </nav>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div class="d-flex gap-2">
        <h4 class="fw-bold mb-0 me-3">Líder: Admin Taskly</h4>
        <a href="proyectos.php" class="btn btn-sm rounded-pill fw-bold px-3"
           style="background:var(--mint);color:var(--primary);">
            <i class="bi bi-folder2-open me-1"></i> Configurar proyectos
        </a>
        <a href="configuracion_equipo.php" class="btn btn-sm rounded-pill fw-bold px-3"
           style="background:var(--primary);color:var(--mint);border:1px solid var(--mint);">
            <i class="bi bi-people-fill me-1"></i> Configurar equipo
        </a>
    </div>
    <input type="text" id="buscadorTareas" class="form-control rounded-pill shadow-sm"
           style="max-width:260px;" placeholder="🔍 Buscar tarea...">
</div>    
    <div class="table-responsive table-custom p-0 mb-4">
        <table class="table table-hover align-middle mb-0 text-center">
            <thead class="table-light">
                <tr>
                    <th class="text-start ps-4">Descripción de la tarea</th>
                    <th>Encargado</th>
                    <th>Estatus</th>
                    <th>Entrega</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($listaTareas)): ?>
                    <?php foreach($listaTareas as $tarea): 
                        $iniciales = $tarea['encargado_nombre'] ? strtoupper(substr($tarea['encargado_nombre'], 0, 2)) : '??';
                        $claseBadge = 'badge-porhacer';
                        if($tarea['estatus'] == 'Terminado' || $tarea['estatus'] == 'Completado') $claseBadge = 'badge-terminado';
                        if($tarea['estatus'] == 'En progreso') $claseBadge = 'badge-progreso';
                        $fechaFormateada = $tarea['fecha_entrega'] ? date('d/m/y', strtotime($tarea['fecha_entrega'])) : '--/--/--';
                    ?>
                    <tr>
                        <td class="text-start ps-4">
                            <div class="fw-medium text-dark text-wrap" style="max-width: 300px;"><?= htmlspecialchars($tarea['descripcion']) ?></div>
                        </td>
                        <td>
                            <div class="d-inline-flex align-items-center gap-2">
                                <div class="avatar-circle bg-secondary text-white"><?= $iniciales ?></div>
                                <small class="fw-bold text-muted"><?= htmlspecialchars($tarea['encargado_rol'] ?? 'Sin asignar') ?></small>
                            </div>
                        </td>
                        <td><span class="badge-status <?= $claseBadge ?>"><?= htmlspecialchars($tarea['estatus']) ?></span></td>
                        <td class="fw-bold text-muted" style="font-size: 0.9rem;"><?= $fechaFormateada ?></td>
                        <td>
                            <button class="btn btn-link text-dark p-0" onclick="abrirModalEditar(<?= $tarea['id'] ?>, '<?= addslashes($tarea['descripcion']) ?>', '<?= $tarea['estatus'] ?>', '<?= $tarea['fecha_entrega'] ?>', '<?= $tarea['usuario_id'] ?>')">
                                <i class="bi bi-pencil-fill fs-5"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="py-4 text-muted">No hay tareas registradas en este proyecto.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        <button class="btn btn-white bg-white text-dark rounded-pill fw-bold px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaTarea">
            <i class="bi bi-plus-lg me-2 text-dark"></i> Nueva tarea
        </button>
    </div>
</div>
<!-- MODAL: Nueva Tarea -->
<div class="modal fade" id="modalNuevaTarea" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Nueva Tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="proyectos.php">
          <input type="hidden" name="accion" value="crear_tarea">
          <div class="mb-3">
            <label class="form-label fw-bold">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Encargado</label>
            <select name="usuario_id" class="form-select">
              <option value="">Sin asignar</option>
              <?php foreach($listaUsuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Estatus</label>
            <select name="estatus" class="form-select">
              <option value="Por hacer">Por hacer</option>
              <option value="En progreso">En progreso</option>
              <option value="Terminado">Terminado</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Fecha de entrega</label>
            <input type="date" name="fecha_entrega" class="form-control">
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Crear tarea</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Editar Tarea -->
<div class="modal fade" id="modalEditarTarea" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Editar Tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="proyectos.php">
          <input type="hidden" name="accion" value="editar_tarea">
          <input type="hidden" name="id_tarea" id="edit_id_tarea">
          <div class="mb-3">
            <label class="form-label fw-bold">Descripción</label>
            <textarea name="descripcion" id="edit_descripcion" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Encargado</label>
            <select name="usuario_id" id="edit_usuario_id" class="form-select">
              <option value="">Sin asignar</option>
              <?php foreach($listaUsuarios as $u): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Estatus</label>
            <select name="estatus" id="edit_estatus" class="form-select">
              <option value="Por hacer">Por hacer</option>
              <option value="En progreso">En progreso</option>
              <option value="Terminado">Terminado</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold">Fecha de entrega</label>
            <input type="date" name="fecha_entrega" id="edit_fecha_entrega" class="form-control">
          </div>
          <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'includes/footer.php'; // <--- CIERRE DE INTERFAZ Y SCRIPTS GLOBALES ?>

<script>
    function abrirModalEditar(id, descripcion, estatus, fecha_entrega, usuario_id) {
        document.getElementById('edit_id_tarea').value = id;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_estatus').value = estatus;
        document.getElementById('edit_fecha_entrega').value = fecha_entrega;
        document.getElementById('edit_usuario_id').value = usuario_id;
        
        var modal = new bootstrap.Modal(document.getElementById('modalEditarTarea'));
        modal.show();
    }
    document.getElementById('buscadorTareas').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>