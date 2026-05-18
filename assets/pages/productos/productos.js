// productos.js
document.addEventListener('DOMContentLoaded', function() {
    // ==================== ELEMENTOS DE LA INTERFAZ ====================
    const formRegistrar = document.getElementById('formRegistrarProducto');
    const tablaBody = document.getElementById('tablaProductosBody');
    const buscarInput = document.getElementById('buscarProductoInput');
    const btnBuscar = document.getElementById('btnBuscarProducto');
    let currentSearchTerm = buscarInput ? buscarInput.value : '';

    // Instancia de Modales de Bootstrap 5
    const ajustarStockModal = new bootstrap.Modal(document.getElementById('ajustarStockModal'));
    const editarProductoModal = new bootstrap.Modal(document.getElementById('editarProductoModal'));
    const eliminarProductoModal = new bootstrap.Modal(document.getElementById('eliminarProductoModal'));

    // ==================== TOAST NOTIFICACIONES ====================
    function showMessage(message, type = 'success') {
        const toast = document.getElementById('toastMessage');
        if (!toast) return;
        toast.textContent = message;
        toast.className = ''; // Limpiar clases anteriores
        toast.classList.add(type);
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 3000);
    }

    // ==================== CARGAR / BUSCAR PRODUCTOS ====================
    function cargarProductos(termino) {
        currentSearchTerm = termino;
        fetch(`?page=productos&action=buscarAjax&ajax=buscar_productos&termino=${encodeURIComponent(termino)}`)
            .then(response => response.json())
            .then(data => actualizarTabla(data))
            .catch(error => {
                console.error('Error cargando productos:', error);
                showMessage('❌ Error al conectar con el catálogo de productos.', 'error');
            });
    }

    function actualizarTabla(productos) {
        if (!tablaBody) return;
        if (productos.length === 0) {
            tablaBody.innerHTML = '<tr><td colspan="7" class="text-center py-3 text-muted">No se encontraron productos en este momento.</td></tr>';
            return;
        }

        let html = '';
        productos.forEach(p => {
            // Calcular badge de estado de stock en base a reglas de stock mínimo
            let claseStock = 'stock-ok';
            const stockActual = parseInt(p.stock_actual);
            const stockMinimo = parseInt(p.stock_minimo);

            if (stockActual <= 0) {
                claseStock = 'stock-peligro';
            } else if (stockActual <= stockMinimo) {
                claseStock = 'stock-alerta';
            }

            // Formatear precio de venta
            const precioVenta = parseFloat(p.precio_venta).toFixed(2);

            html += `
                <tr data-codigo="${escapeHtml(p.codigo_producto)}">
                    <td><strong>${escapeHtml(p.codigo_producto)}</strong></td>
                    <td>${escapeHtml(p.nombre)}</td>
                    <td><span class="badge bg-secondary">${escapeHtml(p.categoria || 'Sin categoría')}</span></td>
                    <td>$${precioVenta}</td>
                    <td>
                        <span class="badge-stock ${claseStock}">
                            ${stockActual} <small class="text-muted">/ ${stockMinimo}</small>
                        </span>
                    </td>
                    <td>${escapeHtml(p.unidad_medida)}</td>
                    <td>
                        <div class="acciones-botones justify-content-end">
                            <!-- Movimiento Rápido Stock -->
                            <button class="btn btn-sm btn-outline-success ajustar-stock-btn btn-sm-custom" 
                                    data-codigo="${escapeHtml(p.codigo_producto)}" 
                                    data-nombre="${escapeHtml(p.nombre)}" 
                                    data-stock="${stockActual}">
                                <i class="fas fa-plus-minus"></i> Stock
                            </button>
                            <!-- Editar -->
                            <button class="btn btn-sm btn-warning editar-prod-btn btn-sm-custom" 
                                    data-codigo="${escapeHtml(p.codigo_producto)}" 
                                    data-nombre="${escapeHtml(p.nombre)}" 
                                    data-categoria="${escapeHtml(p.categoria || '')}" 
                                    data-precio="${p.precio_venta}" 
                                    data-minimo="${stockMinimo}" 
                                    data-unidad="${escapeHtml(p.unidad_medida)}">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <!-- Eliminar -->
                            <button class="btn btn-sm btn-danger eliminar-prod-btn btn-sm-custom" 
                                    data-codigo="${escapeHtml(p.codigo_producto)}" 
                                    data-nombre="${escapeHtml(p.nombre)}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tablaBody.innerHTML = html;
        // Reasignar manejadores de eventos a los botones recién inyectados
        asignarEventosBotones();
    }

    function buscarProductos() {
        const termino = buscarInput.value.trim();
        cargarProductos(termino);
    }

    if (btnBuscar) {
        btnBuscar.addEventListener('click', buscarProductos);
    }
    if (buscarInput) {
        buscarInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarProductos();
            }
        });
    }

    // ==================== REGISTRAR PRODUCTO ====================
    if (formRegistrar) {
        formRegistrar.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(formRegistrar);

            fetch('?page=productos&action=crear', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    formRegistrar.reset();
                    // Valores iniciales por defecto de stock
                    document.getElementById('prod_stock_actual').value = 0;
                    document.getElementById('prod_stock_minimo').value = 5;
                    document.getElementById('prod_unidad').value = 'unidad';
                    cargarProductos(currentSearchTerm);
                } else {
                    showMessage(data.message, 'error');
                }
            })
            .catch(() => showMessage('❌ Error de conexión al registrar producto', 'error'));
        });
    }

    // ==================== MODAL AJUSTAR STOCK ====================
    function abrirAjustarStockModal(e) {
        const btn = e.currentTarget;
        const codigo = btn.getAttribute('data-codigo');
        const nombre = btn.getAttribute('data-nombre');
        const stock = btn.getAttribute('data-stock');

        document.getElementById('ajuste_codigo').value = codigo;
        document.getElementById('ajuste_nombre_prod').innerText = nombre;
        document.getElementById('ajuste_stock_actual').innerText = stock;
        document.getElementById('ajuste_cantidad').value = 1;
        document.getElementById('tipo_entrada').checked = true;

        ajustarStockModal.show();
    }

    document.getElementById('guardarAjusteStock').addEventListener('click', () => {
        const codigo = document.getElementById('ajuste_codigo').value;
        const cantidadInput = parseInt(document.getElementById('ajuste_cantidad').value);
        const tipoAjuste = document.querySelector('input[name="tipo_ajuste"]:checked').value;

        if (isNaN(cantidadInput) || cantidadInput <= 0) {
            showMessage('⚠️ Por favor ingrese una cantidad válida mayor que cero.', 'error');
            return;
        }

        // Si es salida, la variación enviada al backend debe ser negativa
        const cantidadFinal = tipoAjuste === 'salida' ? -cantidadInput : cantidadInput;

        const formData = new FormData();
        formData.append('codigo_producto', codigo);
        formData.append('cantidad', cantidadFinal);

        fetch('?page=productos&action=actualizarStock', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                ajustarStockModal.hide();
                cargarProductos(currentSearchTerm);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(() => showMessage('❌ Error de conexión al actualizar el stock.', 'error'));
    });

    // ==================== MODAL EDITAR PRODUCTO ====================
    function abrirEditarModal(e) {
        const btn = e.currentTarget;
        const codigo = btn.getAttribute('data-codigo');
        const nombre = btn.getAttribute('data-nombre');
        const categoria = btn.getAttribute('data-categoria');
        const precio = btn.getAttribute('data-precio');
        const minimo = btn.getAttribute('data-minimo');
        const unidad = btn.getAttribute('data-unidad');

        document.getElementById('edit_prod_codigo').value = codigo;
        document.getElementById('edit_prod_nombre').value = nombre;
        document.getElementById('edit_prod_categoria').value = categoria;
        document.getElementById('edit_prod_precio').value = precio;
        document.getElementById('edit_prod_minimo').value = minimo;
        document.getElementById('edit_prod_unidad').value = unidad;

        editarProductoModal.show();
    }

    document.getElementById('guardarCambiosProducto').addEventListener('click', () => {
        const codigo = document.getElementById('edit_prod_codigo').value;
        const nombre = document.getElementById('edit_prod_nombre').value;
        const categoria = document.getElementById('edit_prod_categoria').value;
        const precio = document.getElementById('edit_prod_precio').value;
        const minimo = document.getElementById('edit_prod_minimo').value;
        const unidad = document.getElementById('edit_prod_unidad').value;

        if (!nombre || !precio || !minimo || !unidad) {
            showMessage('⚠️ Todos los campos son requeridos para guardar.', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('codigo_producto', codigo);
        formData.append('nombre', nombre);
        formData.append('categoria', categoria);
        formData.append('precio_venta', precio);
        formData.append('stock_minimo', minimo);
        formData.append('unidad_medida', unidad);

        fetch('?page=productos&action=editar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                editarProductoModal.hide();
                cargarProductos(currentSearchTerm);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(() => showMessage('❌ Error de conexión al actualizar producto.', 'error'));
    });

    // ==================== MODAL ELIMINAR PRODUCTO ====================
    function abrirEliminarModal(e) {
        const btn = e.currentTarget;
        const codigo = btn.getAttribute('data-codigo');
        const nombre = btn.getAttribute('data-nombre');

        document.getElementById('eliminar_codigo').value = codigo;
        document.getElementById('eliminar_nombre_prod').innerText = nombre;

        eliminarProductoModal.show();
    }

    document.getElementById('confirmarEliminarProducto').addEventListener('click', () => {
        const codigo = document.getElementById('eliminar_codigo').value;

        const formData = new FormData();
        formData.append('codigo_producto', codigo);
        formData.append('fisico', 'false'); // Se realiza borrado lógico por defecto para respetar el historial de ventas

        fetch('?page=productos&action=eliminar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                eliminarProductoModal.hide();
                cargarProductos(currentSearchTerm);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(() => showMessage('❌ Error de conexión al intentar eliminar el producto.', 'error'));
    });

    // ==================== ASIGNACIÓN DE EVENTOS ====================
    function asignarEventosBotones() {
        document.querySelectorAll('.ajustar-stock-btn').forEach(btn => {
            btn.addEventListener('click', abrirAjustarStockModal);
        });
        document.querySelectorAll('.editar-prod-btn').forEach(btn => {
            btn.addEventListener('click', abrirEditarModal);
        });
        document.querySelectorAll('.eliminar-prod-btn').forEach(btn => {
            btn.addEventListener('click', abrirEliminarModal);
        });
    }

    // ==================== AUXILIARES ====================
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[m] || m));
    }

    // Carga inicial y primera asignación de listeners
    asignarEventosBotones();
});