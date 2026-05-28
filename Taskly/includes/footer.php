</div> </div> <div class="modal fade" id="modalCrearEquipo" tabindex="-1" aria-labelledby="modalCrearEquipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background-color: var(--primary); color: white; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title" id="modalCrearEquipoLabel">Nuevo Equipo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="procesar_equipo.php" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nombre_equipo" class="form-label" style="color: var(--primary); font-weight: 600;">Nombre del Equipo</label>
                        <input type="text" class="form-control" id="nombre_equipo" name="nombre_equipo" placeholder="Ej: Proyecto Final Web" required style="border-radius: 8px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #e5e5e5; color: var(--primary); font-weight: 600; border-radius: 8px;">Cancelar</button>
                    <button type="submit" name="crear_equipo" class="btn" style="background-color: var(--mint); color: var(--primary); font-weight: 600; border-radius: 8px;">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevaActividad" tabindex="-1" aria-labelledby="modalNuevaActividadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="background-color: var(--primary); color: white; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title" id="modalNuevaActividadLabel"><i class="bi bi-list-check me-2"></i>Nueva Actividad</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="procesar_actividad.php" method="POST">
                <div class="modal-body p-4">
                    
                    <input type="hidden" id="id_equipo_oculto" name="id_equipo">

                    <div class="mb-3">
                        <label for="descripcion_tarea" class="form-label" style="color: var(--primary); font-weight: 600;">Descripción de la tarea</label>
                        <textarea class="form-control" id="descripcion_tarea" name="descripcion" rows="3" placeholder="Ej: Terminar el diseño del dashboard" required style="border-radius: 8px;"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_entrega" class="form-label" style="color: var(--primary); font-weight: 600;">Fecha de entrega</label>
                        <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" required style="border-radius: 8px;">
                    </div>

                </div>
                <div class="modal-footer" style="border-top: none;">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #e5e5e5; color: var(--primary); font-weight: 600; border-radius: 8px;">Cancelar</button>
                    <button type="submit" name="crear_actividad" class="btn" style="background-color: var(--mint); color: var(--primary); font-weight: 600; border-radius: 8px;">Guardar Actividad</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Lógica interactiva para el menú responsive (Hamburguesa)
    const menuBtn = document.getElementById('menuBtn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (menuBtn && sidebar && overlay) {
        menuBtn.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebar.classList.remove('show');
        });
    }

    // Lógica para preparar y abrir el modal de Nueva Actividad
    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('modalNuevaActividad');
        
        if (modalElement) {
            const modalActividad = new bootstrap.Modal(modalElement);
            const inputEquipoOculto = document.getElementById('id_equipo_oculto');

            document.querySelectorAll('.btn-new-activity').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault(); 
                    e.stopPropagation(); 

                    const idEquipo = btn.getAttribute('data-equipo-id');
                    if (inputEquipoOculto) {
                        inputEquipoOculto.value = idEquipo;
                    }

                    modalActividad.show();
                });
            });
        }
    });
</script>
</body>
</html>