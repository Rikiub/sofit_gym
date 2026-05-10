<?php
$this->pushCss('/assets/base/parciales/sidebar/sidebar.css');
$this->pushJs('/assets/base/parciales/sidebar/sidebar.js');
?>

<aside class="sidebar" id="sidebar">
	<div class="sidebar-header">
		<div class="logo-container">
			<i class="fas fa-dumbbell logo-icon"></i>
			<h2>Sofit<span>GYM</span></h2>
		</div>
		<button class="sidebar-toggle" id="sidebarToggle" aria-label="Colapsar menú">
			<i class="fa-chevron-left fas"></i>
		</button>
	</div>

	<nav class="sidebar-nav">
		<a href="/" class="active"><i class="fas fa-home"></i> <span>Inicio</span></a>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-id-card"></i> <span>Gestionar Clientes e Inscripciones</span>
			</summary>
			<div class="group-items">
				<a href="/clientes"><i class="fas fa-user-plus"></i> <span>Registro de clientes</span></a>
				<a href="clientes_estado.php"><i class="fas fa-tag"></i> <span>Estado de inscripción</span></a>
				<a href="clientes_historial_pagos.php"><i class="fas fa-history"></i> <span>Historial de pagos</span></a>
				<a href="clientes_medidas.php"><i class="fas fa-chart-line"></i> <span>Medidas biométricas y nutrición</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-chalkboard-user"></i> <span>Gestionar Trabajadores y Clases</span>
			</summary>
			<div class="group-items">
				<a href="trabajadores_registrar.php"><i class="fas fa-user-tie"></i> <span>Registro de trabajadores</span></a>
				<a href="horarios_clases.php"><i class="fas fa-calendar-week"></i> <span>Horarios, clases grupales y cupos</span></a>
				<a href="asignar_cliente_clase.php"><i class="fas fa-user-check"></i> <span>Asignación de clientes a clases</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-coins"></i> <span>Gestionar Facturación y Control de Pagos</span>
			</summary>
			<div class="group-items">
				<a href="facturacion_automatica.php"><i class="fas fa-calculator"></i> <span>Automatización de vencimiento y recibos</span></a>
				<a href="facturacion_panel.php"><i class="fas fa-chart-pie"></i> <span>Panel de ingresos y morosidad</span></a>
				<a href="facturacion_notificaciones.php"><i class="fas fa-bell"></i> <span>Notificaciones (WhatsApp/Email)</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-fingerprint"></i> <span>Controlar Asistencia</span>
			</summary>
			<div class="group-items">
				<a href="asistencia_registrar.php"><i class="fas fa-edit"></i> <span>Registro entrada/salida</span></a>
				<a href="asistencia_metricas.php"><i class="fas fa-chart-line"></i> <span>Métricas de ocupación</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-microchip"></i> <span>Gestionar Equipos y Maquinaria</span>
			</summary>
			<div class="group-items">
				<a href="equipos_mantenimiento.php"><i class="fas fa-tools"></i> <span>Registro de mantenimiento</span></a>
				<a href="equipos_historial.php"><i class="fas fa-history"></i> <span>Historial de mantenimiento</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-boxes"></i> <span>Gestionar Productos</span>
			</summary>
			<div class="group-items">
				<a href="productos_stock.php"><i class="fas fa-boxes"></i> <span>Control de stock y demanda</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-dumbbell"></i> <span>Gestionar Rutinas de Entrenamiento</span>
			</summary>
			<div class="group-items">
				<a href="rutinas_disenar.php"><i class="fas fa-pen-ruler"></i> <span>Diseñar planes de entrenamiento</span></a>
				<a href="rutinas_asignar.php"><i class="fas fa-user-check"></i> <span>Asignar rutinas a clientes</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-robot"></i> <span>Consultar Asistente de Entrenamiento</span>
			</summary>
			<div class="group-items">
				<a href="asistente_chat.php"><i class="fas fa-comments"></i> <span>Interfaz de chat con IA</span></a>
				<a href="asistente_tendencias.php"><i class="fas fa-chart-line"></i> <span>Tendencias y sugerencias</span></a>
			</div>
		</details>

		<div class="sidebar-divider" role="separator"></div>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-shield-alt"></i> <span>Gestión de Auditoría y Seguridad</span>
			</summary>
			<div class="group-items">
				<a href="seguridad.php"><i class="fas fa-lock"></i> <span>Seguridad (Login/MD5/Captcha)</span></a>
				<a href="roles_permisos.php"><i class="fas fa-users-cog"></i> <span>Roles y Permisos</span></a>
				<a href="bitacora.php"><i class="fas fa-history"></i> <span>Bitácora del Sistema</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-database"></i> <span>Gestión de Soporte y Datos</span>
			</summary>
			<div class="group-items">
				<a href="reportes.php"><i class="fas fa-chart-bar"></i> <span>Reportes Estadísticos</span></a>
				<a href="mantenimiento_bd.php"><i class="fas fa-database"></i> <span>Mantenimiento de BD</span></a>
				<a href="ayuda.php"><i class="fas fa-question-circle"></i> <span>Ayuda Interactiva</span></a>
				<a href="componente_inteligente.php"><i class="fas fa-brain"></i> <span>Componente Inteligente (IO/PERT)</span></a>
			</div>
		</details>
	</nav>

	<div class="sidebar-footer">
		<i class="fas fa-cog"></i> <span>Soporte y configuración</span>
	</div>
</aside>