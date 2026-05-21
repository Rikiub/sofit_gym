<?php
$this->pushCss('components/sidebar/sidebar.css');
$this->pushJs('components/sidebar/sidebar.js');
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
		<a href="?pagina=clientes" class="active"><i class="fas fa-home"></i> <span>Inicio</span></a>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-id-card"></i> <span>Gestionar Clientes e Inscripciones</span>
			</summary>
			<div class="group-items">
				<a href="?page=clientes"><i class="fas fa-user-plus"></i> <span>Registro de clientes</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-chalkboard-user"></i> <span>Gestionar Trabajadores y Clases</span>
			</summary>
			<div class="group-items">
				<a href="?page=trabajadores"><i class="fas fa-user-tie"></i> <span>Registro de trabajadores</span></a>
				<a href="?page=horarios"><i class="fas fa-calendar-week"></i> <span>Horarios, clases grupales y cupos</span></a>
				<a href="?page=clases"><i class="fas fa-user-check"></i> <span>Asignación de clientes a clases</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-coins"></i> <span>Gestionar Facturación y Control de Pagos</span>
			</summary>
			<div class="group-items">
				<a href="?page=facturacion"><i class="fas fa-calculator"></i> <span>Automatización de vencimiento y recibos</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-fingerprint"></i> <span>Controlar Asistencia</span>
			</summary>
			<div class="group-items">
				<a href="?page=asistencia"><i class="fas fa-edit"></i> <span>Registro entrada/salida</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-microchip"></i> <span>Gestionar Equipos y Maquinaria</span>
			</summary>
			<div class="group-items">
				<a href="?page=equipos"><i class="fas fa-tools"></i> <span>Registro de equipos</span></a>
				<a href="?page=equiposMantenimiento"><i class="fas fa-history"></i> <span>Historial de mantenimientos</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-boxes"></i> <span>Gestionar Productos</span>
			</summary>
			<div class="group-items">
				<a href="?page=productos"><i class="fas fa-boxes"></i> <span>Control de stock y demanda</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-dumbbell"></i> <span>Gestionar Rutinas de Entrenamiento</span>
			</summary>
			<div class="group-items">
				<a href="?page=rutinas&action=index"><i class="fas fa-pen-ruler"></i> <span>Diseñar planes de entrenamiento</span></a>
				<a href="?page=rutinas&action=asignadas"><i class="fas fa-user-check"></i> <span>Asignar rutinas a clientes</span></a>
			</div>
		</details>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-robot"></i> <span>Consultar Asistente de Entrenamiento</span>
			</summary>
			<div class="group-items">
				<a href="?page=asistente"><i class="fas fa-comments"></i> <span>Consultar asistente inteligente</span></a>
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