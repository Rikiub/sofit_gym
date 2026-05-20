// rutinas.js - Control del CRUD para Rutinas y Asignación de Rutinas de Sofit Gym
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // NOTIFICACIONES TOAST (Mismo estilo que asistencia)
    // ==========================================
    function showMessage(message, type = 'success') {
        const toast = document.getElementById('toastMessage');
        if (!toast) return;
        toast.textContent = message;
        toast.className = type;
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    // Auxiliar para escapar HTML y prevenir ataques XSS
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m] || m));
    }

    // =========================================================================
    // I. MÓDULO: GESTIÓN DE RUTINAS BASE (rutinas.php)
    // =========================================================================
    const formRegistroRutina = document.getElementById('formRegistroRutina');
    const tablaRutinasBody = document.getElementById('tablaRutinasBody');
    const searchInputRutinas = document.getElementById('searchInputRutinas');
    const btnBuscarRutina = document.getElementById('btnBuscarRutina');

    // Modales de Rutina
    const editarRutinaModalEl = document.getElementById('editarRutinaModal');
    const eliminarRutinaModalEl = document.getElementById('eliminarRutinaModal');
    const editarRutinaModal = editarRutinaModalEl ? new bootstrap.Modal(editarRutinaModalEl) : null;
    const eliminarRutinaModal = eliminarRutinaModalEl ? new bootstrap.Modal(eliminarRutinaModalEl) : null;

    let currentRutinasSearch = '';

    if (formRegistroRutina) {
        // Registrar nueva rutina
        formRegistroRutina.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(formRegistroRutina);

            fetch('?page=rutinas&action=registrar_rutina', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('✅ ' + data.message, 'success');
                    formRegistroRutina.reset();
                    cargarRutinas(currentRutinasSearch);
                } else {
                    showMessage('❌ Error: ' + data.message, 'error');
                }
            })
            .catch(() => showMessage('❌ Error de conexión al servidor.', 'error'));
        });

        // Buscar Rutinas
        function buscarRutinas() {
            const termino = searchInputRutinas.value.trim();
            cargarRutinas(termino);
        }

        if (btnBuscarRutina) {
            btnBuscarRutina.addEventListener('click', buscarRutinas);
        }
        if (searchInputRutinas) {
            searchInputRutinas.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    buscarRutinas();
                }
            });
        }

        // Cargar rutinas base por AJAX
        function cargarRutinas(termino) {
            currentRutinasSearch = termino;
            fetch(`?page=rutinas&action=buscar_rutinas_ajax&ajax=buscar_rutinas&termino=${encodeURIComponent(termino)}`)
                .then(response => response.json())
                .then(data => actualizarTablaRutinas(data))
                .catch(console.error);
        }

        function actualizarTablaRutinas(rutinas) {
            if (!tablaRutinasBody) return;
            if (rutinas.length === 0) {
                tablaRutinasBody.innerHTML = '<tr><td colspan="5" class="text-center">No se encontraron rutinas en el catálogo.</td></tr>';
                return;
            }

            let html = '';
            rutinas.forEach(r => {
                const duracionStr = r.duracion_semanas ? `${r.duracion_semanas} semanas` : '—';
                const objetivoStr = r.objetivo ? escapeHtml(r.objetivo) : '—';
                const difClase = r.nombre_dificultad ? r.nombre_dificultad.toLowerCase().replace('ñ', 'n') : 'principiante';
                
                html += `
                    <tr data-id="${r.id_rutina}">
                        <td><strong>${escapeHtml(r.nombre)}</strong></td>
                        <td>
                            <span class="dificultad-badge dif-${difClase}">
                                ${escapeHtml(r.nombre_dificultad)}
                            </span>
                        </td>
                        <td>${duracionStr}</td>
                        <td>${objetivoStr}</td>
                        <td>
                            <div class="acciones-botones">
                                <button class="btn btn-sm btn-warning editar-rutina-btn" 
                                        data-id="${r.id_rutina}" 
                                        data-nombre="${escapeHtml(r.nombre)}"
                                        data-dificultad="${r.id_dificultad}"
                                        data-duracion="${r.duracion_semanas || ''}"
                                        data-objetivo="${escapeHtml(r.objetivo || '')}"
                                        data-descripcion="${escapeHtml(r.descripcion || '')}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-danger eliminar-rutina-btn" 
                                        data-id="${r.id_rutina}"
                                        data-nombre="${escapeHtml(r.nombre)}">
                                    <i class="fas fa-trash-alt"></i> Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tablaRutinasBody.innerHTML = html;
            vincularEventosTablaRutinas();
        }

        function vincularEventosTablaRutinas() {
            document.querySelectorAll('.editar-rutina-btn').forEach(btn => {
                btn.addEventListener('click', abrirEditarRutinaModal);
            });
            document.querySelectorAll('.eliminar-rutina-btn').forEach(btn => {
                btn.addEventListener('click', abrirEliminarRutinaModal);
            });
        }

        // Abrir Modal de Edición de Rutina
        function abrirEditarRutinaModal(e) {
            const btn = e.currentTarget;
            document.getElementById('edit_id_rutina').value = btn.getAttribute('data-id');
            document.getElementById('edit_nombre_rutina').value = btn.getAttribute('data-nombre');
            document.getElementById('edit_id_dificultad').value = btn.getAttribute('data-dificultad');
            document.getElementById('edit_duracion_semanas').value = btn.getAttribute('data-duracion');
            document.getElementById('edit_objetivo').value = btn.getAttribute('data-objetivo');
            document.getElementById('edit_descripcion').value = btn.getAttribute('data-descripcion');
            editarRutinaModal.show();
        }

        // Guardar cambios de Rutina Base
        const btnGuardarEdicionRutina = document.getElementById('guardarEdicionRutina');
        if (btnGuardarEdicionRutina) {
            btnGuardarEdicionRutina.addEventListener('click', function() {
                const id = document.getElementById('edit_id_rutina').value;
                const nombre = document.getElementById('edit_nombre_rutina').value.trim();
                const dificultad = document.getElementById('edit_id_dificultad').value;
                const duracion = document.getElementById('edit_duracion_semanas').value;
                const objetivo = document.getElementById('edit_objetivo').value.trim();
                const descripcion = document.getElementById('edit_descripcion').value.trim();

                if (!nombre || !dificultad) {
                    showMessage('⚠️ El nombre y la dificultad son obligatorios.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('id_rutina', id);
                formData.append('nombre', nombre);
                formData.append('id_dificultad', dificultad);
                formData.append('duracion_semanas', duracion);
                formData.append('objetivo', objetivo);
                formData.append('descripcion', descripcion);

                fetch('?page=rutinas&action=editar_rutina', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('✅ ' + data.message, 'success');
                        editarRutinaModal.hide();
                        cargarRutinas(currentRutinasSearch);
                    } else {
                        showMessage('❌ Error: ' + data.message, 'error');
                    }
                })
                .catch(() => showMessage('❌ Error de conexión', 'error'));
            });
        }

        // Abrir Modal de Confirmación de Eliminación
        function abrirEliminarRutinaModal(e) {
            const btn = e.currentTarget;
            document.getElementById('delete_id_rutina').value = btn.getAttribute('data-id');
            document.getElementById('delete_nombre_rutina_txt').innerText = btn.getAttribute('data-nombre');
            eliminarRutinaModal.show();
        }

        // Confirmar eliminación
        const btnConfirmarEliminarRutina = document.getElementById('confirmarEliminarRutina');
        if (btnConfirmarEliminarRutina) {
            btnConfirmarEliminarRutina.addEventListener('click', function() {
                const id = document.getElementById('delete_id_rutina').value;
                const formData = new FormData();
                formData.append('id_rutina', id);

                fetch('?page=rutinas&action=eliminar_rutina', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('🗑️ Rutina eliminada de catálogo.', 'success');
                        eliminarRutinaModal.hide();
                        cargarRutinas(currentRutinasSearch);
                    } else {
                        showMessage('❌ Error: ' + data.message, 'error');
                    }
                })
                .catch(() => showMessage('❌ Error de conexión.', 'error'));
            });
        }

        // Inicializar eventos en la carga inicial de rutinas base
        vincularEventosTablaRutinas();
    }

    // =========================================================================
    // II. MÓDULO: ASIGNACIÓN DE RUTINAS A CLIENTES (rutinasAsignadas.php)
    // =========================================================================
    const formAsignarRutina = document.getElementById('formAsignarRutina');
    const searchClientAsignar = document.getElementById('searchClientAsignar');
    const clientesTablaAsignacionBody = document.querySelector('#clientesTablaAsignacion tbody');
    const cedulaClienteAsignacion = document.getElementById('cedula_cliente_asignacion');
    const labelClienteAsignarText = document.getElementById('cliente_selected_text_asignar');

    // Modales de Asignación
    const clienteModalAsignacionEl = document.getElementById('clienteModalAsignacion');
    const clienteModalAsignacion = clienteModalAsignacionEl ? new bootstrap.Modal(clienteModalAsignacionEl) : null;
    const editarAsignacionModalEl = document.getElementById('editarAsignacionModal');
    const editarAsignacionModal = editarAsignacionModalEl ? new bootstrap.Modal(editarAsignacionModalEl) : null;
    const eliminarAsignacionModalEl = document.getElementById('eliminarAsignacionModal');
    const eliminarAsignacionModal = eliminarAsignacionModalEl ? new bootstrap.Modal(eliminarAsignacionModalEl) : null;

    if (formAsignarRutina) {
        // --- BUSCADOR AJAX DE CLIENTES PARA ASIGNAR (Mismo comportamiento que asistencia.js) ---
        function cargarClientesParaAsignar(termino) {
            fetch(`?page=asistencia&action=buscar_clientes_ajax&ajax=buscar_clientes&termino=${encodeURIComponent(termino)}`)
                .then(response => response.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="5" class="text-center text-muted">Ningún cliente coincide con la búsqueda.</td></tr>';
                    } else {
                        data.forEach(c => {
                            html += `
                                <tr data-cedula="${c.cedula_persona}" data-nombre="${escapeHtml(c.nombre)}">
                                    <td>${escapeHtml(c.cedula_persona)}</td>
                                    <td>${escapeHtml(c.nombre)}</td>
                                    <td>${escapeHtml(c.correo || '—')}</td>
                                    <td>${escapeHtml(c.telefono || '—')}</td>
                                    <td><button class="btn btn-select-client seleccionar-cliente-asig-btn">Seleccionar</button></td>
                                </tr>
                            `;
                        });
                    }
                    clientesTablaAsignacionBody.innerHTML = html;
                })
                .catch(console.error);
        }

        let searchTimeout;
        if (searchClientAsignar) {
            searchClientAsignar.addEventListener('keyup', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => cargarClientesParaAsignar(searchClientAsignar.value), 400);
            });
        }

        if (clienteModalAsignacionEl) {
            clienteModalAsignacionEl.addEventListener('show.bs.modal', () => cargarClientesParaAsignar(''));
        }

        if (clientesTablaAsignacionBody) {
            clientesTablaAsignacionBody.addEventListener('click', function(e) {
                const btn = e.target.closest('.seleccionar-cliente-asig-btn');
                if (!btn) return;
                const row = btn.closest('tr');
                const cedula = row.getAttribute('data-cedula');
                const nombre = row.getAttribute('data-nombre');

                cedulaClienteAsignacion.value = cedula;
                labelClienteAsignarText.innerText = nombre;
                clienteModalAsignacion.hide();
            });
        }

        // --- ENVIAR NUEVA ASIGNACIÓN ---
        formAsignarRutina.addEventListener('submit', function(e) {
            e.preventDefault();
            const cedula = cedulaClienteAsignacion.value;
            if (!cedula) {
                showMessage('⚠️ Debe seleccionar un cliente antes de guardar.', 'error');
                return;
            }

            const formData = new FormData(formAsignarRutina);
            fetch('?page=rutinas&action=asignar_rutina', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('✅ ' + data.message, 'success');
                    // Resetear formulario
                    formAsignarRutina.reset();
                    labelClienteAsignarText.innerText = 'Seleccione cliente';
                    cedulaClienteAsignacion.value = '';
                    // Recargar la página o recargar la tabla de asignaciones mediante recarga suave (recargar la página en este caso es más seguro para Plates)
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    showMessage('❌ Error: ' + data.message, 'error');
                }
            })
            .catch(() => showMessage('❌ Error de conexión al servidor.', 'error'));
        });

        // --- FILTRAR / BUSCAR ASIGNACIONES ---
        const searchInputAsignaciones = document.getElementById('searchInputAsignaciones');
        const btnBuscarAsignacion = document.getElementById('btnBuscarAsignacion');
        const tablaAsignacionesBody = document.getElementById('tablaAsignacionesBody');

        function filtrarAsignaciones() {
            const termino = searchInputAsignaciones.value.trim().toLowerCase();
            const filas = tablaAsignacionesBody.querySelectorAll('tr');
            
            filas.forEach(fila => {
                const textoFila = fila.innerText.toLowerCase();
                if (textoFila.includes(termino)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        if (btnBuscarAsignacion) {
            btnBuscarAsignacion.addEventListener('click', filtrarAsignaciones);
        }
        if (searchInputAsignaciones) {
            searchInputAsignaciones.addEventListener('keyup', filtrarAsignaciones);
        }

        // --- MODAL EDITAR ASIGNACIÓN ---
        function abrirEditarAsignacionModal(e) {
            const btn = e.currentTarget;
            document.getElementById('edit_id_asignacion').value = btn.getAttribute('data-id');
            document.getElementById('edit_cedula_cliente_asignado').value = btn.getAttribute('data-cedula');
            document.getElementById('edit_nombre_cliente_asignado').value = btn.getAttribute('data-nombre-cliente');
            document.getElementById('edit_id_rutina_asignacion').value = btn.getAttribute('data-rutina');
            document.getElementById('edit_fecha_asignacion').value = btn.getAttribute('data-fecha-asig');
            document.getElementById('edit_fecha_inicio').value = btn.getAttribute('data-fecha-ini') || '';
            document.getElementById('edit_fecha_fin').value = btn.getAttribute('data-fecha-fin') || '';
            document.getElementById('edit_estado_asignacion').value = btn.getAttribute('data-estado');
            document.getElementById('edit_progreso').value = Math.round(parseFloat(btn.getAttribute('data-progreso')) || 0);

            editarAsignacionModal.show();
        }

        // Guardar cambios en la edición de asignaciones
        const btnGuardarEdicionAsignacion = document.getElementById('guardarEdicionAsignacion');
        if (btnGuardarEdicionAsignacion) {
            btnGuardarEdicionAsignacion.addEventListener('click', function() {
                const idAsignacion = document.getElementById('edit_id_asignacion').value;
                const cedula = document.getElementById('edit_cedula_cliente_asignado').value;
                const rutina = document.getElementById('edit_id_rutina_asignacion').value;
                const fAsig = document.getElementById('edit_fecha_asignacion').value;
                const fIni = document.getElementById('edit_fecha_inicio').value;
                const fFin = document.getElementById('edit_fecha_fin').value;
                const estado = document.getElementById('edit_estado_asignacion').value;
                const progreso = document.getElementById('edit_progreso').value;

                if (!rutina || !fAsig) {
                    showMessage('⚠️ La rutina y la fecha de asignación son obligatorias.', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('id_asignacion', idAsignacion);
                formData.append('cedula_cliente', cedula);
                formData.append('id_rutina', rutina);
                formData.append('fecha_asignacion', fAsig);
                formData.append('fecha_inicio', fIni);
                formData.append('fecha_fin', fFin);
                formData.append('estado', estado);
                formData.append('progreso', progreso);

                fetch('?page=rutinas&action=editar_asignacion', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('✅ ' + data.message, 'success');
                        editarAsignacionModal.hide();
                        // Actualizar en caliente o recargar para consistencia de datos de Plate
                        setTimeout(() => { location.reload(); }, 1000);
                    } else {
                        showMessage('❌ Error: ' + data.message, 'error');
                    }
                })
                .catch(() => showMessage('❌ Error de conexión al servidor.', 'error'));
            });
        }

        // --- MODAL ELIMINAR ASIGNACIÓN ---
        function abrirEliminarAsignacionModal(e) {
            const btn = e.currentTarget;
            document.getElementById('delete_id_asignacion').value = btn.getAttribute('data-id');
            document.getElementById('delete_cliente_asignacion_txt').innerText = btn.getAttribute('data-cliente');
            document.getElementById('delete_rutina_asignacion_txt').innerText = btn.getAttribute('data-rutina');
            eliminarAsignacionModal.show();
        }

        const btnConfirmarEliminarAsignacion = document.getElementById('confirmarEliminarAsignacion');
        if (btnConfirmarEliminarAsignacion) {
            btnConfirmarEliminarAsignacion.addEventListener('click', function() {
                const idAsignacion = document.getElementById('delete_id_asignacion').value;
                const formData = new FormData();
                formData.append('id_asignacion', idAsignacion);

                fetch('?page=rutinas&action=eliminar_asignacion', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('🗑️ Asignación cancelada con éxito.', 'success');
                        eliminarAsignacionModal.hide();
                        setTimeout(() => { location.reload(); }, 1000);
                    } else {
                        showMessage('❌ Error: ' + data.message, 'error');
                    }
                })
                .catch(() => showMessage('❌ Error de conexión.', 'error'));
            });
        }

        // Vincular eventos iniciales a los botones de acción en la tabla de asignaciones
        function vincularEventosTablaAsignaciones() {
            document.querySelectorAll('.editar-asignacion-btn').forEach(btn => {
                btn.addEventListener('click', abrirEditarAsignacionModal);
            });
            document.querySelectorAll('.eliminar-asignacion-btn').forEach(btn => {
                btn.addEventListener('click', abrirEliminarAsignacionModal);
            });
        }

        vincularEventosTablaAsignaciones();
    }
});