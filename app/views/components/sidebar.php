<script type="module">
	import Alpine from "alpinejs";

	Alpine.data("sidebar", () => ({
		collapsed: localStorage.getItem("sidebarCollapsed") === "true",

		toggle() {
			this.collapsed = !this.collapsed;
			localStorage.setItem("sidebarCollapsed", this.collapsed);
		}
	}));
</script>

<aside x-data="sidebar" class="sidebar" :class="{ collapsed }">
	<script>
		// Sincronizar clase 'collapsed' antes de renderizar
		(() => {
			const sidebar = document.currentScript.parentElement;
			const collapsed = localStorage.getItem("sidebarCollapsed") === "true";
			if (collapsed) sidebar.classList.add("collapsed");
		})()
	</script>

	<div class="sidebar-header d-flex justify-content-end">
		<div class="logo-container" style="height: 100px;" x-show="!collapsed" x-transition>
			<img src="assets/logo.webp" class="img-fluid">
		</div>

		<button class="sidebar-toggle" @click="toggle" aria-label="Colapsar menú">
			<i class="fas fa-chevron-left"></i>
		</button>
	</div>

	<nav class="sidebar-nav">
		<a href="?pagina=clientes" class="active"><i class="fas fa-home"></i> <span>Inicio</span></a>

		<a href="?page=clientes" class="nav-single">
			<i class="fas fa-id-card"></i> <span>Gestionar Clientes e Inscripciones</span>
		</a>

		<!-- Multi‑item groups remain as <details> -->
		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-chalkboard-user"></i> <span>Gestionar Trabajadores y Clases</span>
				<i class="fas fa-chevron-down toggle-icon"></i>
			</summary>
			<div class="group-items">
				<a href="?page=trabajadores"><i class="fas fa-user-tie"></i> <span>Registro de trabajadores</span></a>
				<a href="?page=clases"><i class="fas fa-user-check"></i> <span>Horarios de clases</span></a>
			</div>
		</details>

		<a href="?page=facturacion" class="nav-single">
			<i class="fas fa-coins"></i> <span>Gestionar Facturación y Control de Pagos</span>
		</a>

		<a href="?page=asistencia" class="nav-single">
			<i class="fas fa-fingerprint"></i> <span>Controlar Asistencia</span>
		</a>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-microchip"></i> <span>Gestionar Equipos y Maquinaria</span>
				<i class="fas fa-chevron-down toggle-icon"></i>
			</summary>
			<div class="group-items">
				<a href="?page=equipos"><i class="fas fa-tools"></i> <span>Registro de equipos</span></a>
				<a href="?page=equiposMantenimiento"><i class="fas fa-history"></i> <span>Historial de mantenimientos</span></a>
			</div>
		</details>

		<a href="?page=productos" class="nav-single">
			<i class="fas fa-boxes"></i> <span>Gestionar Productos</span>
		</a>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-dumbbell"></i> <span>Gestionar Rutinas de Entrenamiento</span>
				<i class="fas fa-chevron-down toggle-icon"></i>
			</summary>
			<div class="group-items">
				<a href="?page=rutinas&action=index"><i class="fas fa-pen-ruler"></i> <span>Diseñar planes de entrenamiento</span></a>
				<a href="?page=rutinas&action=asignadas"><i class="fas fa-user-check"></i> <span>Asignar rutinas a clientes</span></a>
			</div>
		</details>

		<a href="?page=asistente" class="nav-single">
			<i class="fas fa-robot"></i> <span>Consultar Asistente de Entrenamiento</span>
		</a>

		<div class="sidebar-divider" role="separator"></div>

		<details class="nav-group">
			<summary class="group-title">
				<i class="fas fa-shield-alt"></i> <span>Gestión de Auditoría y Seguridad</span>
				<i class="fas fa-chevron-down toggle-icon"></i>
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
				<i class="fas fa-chevron-down toggle-icon"></i>
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

<style>
	.sidebar {
		--sidebar-bg: rgba(30, 41, 59, 0.85);
		--sidebar-text: #e2e8f0;
		--sidebar-accent: var(--primary-bg);
		--sidebar-muted: #cbd5e1;
		--sidebar-border: rgba(255, 255, 255, 0.1);
		--sidebar-hover: rgba(255, 255, 255, 0.1);
		--sidebar-radius: 12px;

		width: 280px;
		background: var(--sidebar-bg);
		color: var(--sidebar-text);
		display: flex;
		flex-direction: column;
		height: 100%;
		overflow-y: auto;
		border-right: 1px solid var(--sidebar-border);
		transition: width 0.3s ease;
		scrollbar-width: none;

		/* ===== ESTADO COLAPSADO ===== */
		&.collapsed {
			width: 80px;

			.logo-container h2,
			.sidebar-nav a span,
			.group-title span,
			.group-items a span,
			.sidebar-footer span {
				display: none;
			}

			.group-items {
				padding-left: 0;
			}

			.group-title {
				justify-content: center;

				.toggle-icon {
					display: none;
				}
			}

			.sidebar-nav>a,
			.sidebar-nav>a.nav-single {
				justify-content: center;
			}

			.sidebar-nav a i {
				margin: 0;
			}

			.sidebar-toggle i {
				rotate: 180deg;
			}
		}

		/* ===== CABECERA ===== */
		.sidebar-header {
			padding: 1rem;
			border-bottom: 1px solid var(--sidebar-border);
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.sidebar-toggle {
			background: transparent;
			border: none;
			color: #94a3b8;
			cursor: pointer;
			font-size: 0.9rem;
			padding: 6px;
			border-radius: 50%;
			transition: background 0.2s, color 0.2s;
			display: flex;
			align-items: center;
			justify-content: center;

			&:hover {
				background: var(--sidebar-hover);
				color: var(--sidebar-muted);
			}
		}

		/* ===== NAVEGACIÓN ===== */
		.sidebar-nav {
			padding: 0 0.8rem;
			flex: 1;

			>a {
				display: flex;
				align-items: center;
				gap: 12px;
				padding: 0.5rem 0.8rem;
				color: var(--sidebar-text);
				text-decoration: none;
				border-radius: var(--sidebar-radius);
				margin: 0.3rem 0;

				&:hover,
				&.active {
					background: var(--sidebar-accent);
					color: white;
				}
			}

			/* Single‑item link style (mimics group‑title appearance) */
			>a.nav-single {
				font-size: 0.75rem;
				font-weight: 600;
				text-transform: uppercase;
				color: var(--sidebar-muted);
				gap: 15px;
				margin-bottom: 0.4rem;

				>i:first-child {
					min-width: 20px;
					text-align: center;
				}

				span {
					flex: 1;
					white-space: normal;
					line-height: 1.2;
				}

				&:hover {
					background-color: var(--sidebar-hover);
					color: var(--sidebar-muted);
				}
			}
		}

		/* ===== GRUPOS COLAPSABLES (details/summary) ===== */
		.nav-group {
			margin-bottom: 0.4rem;
		}

		.group-title {
			display: flex;
			align-items: center;
			gap: 15px;
			font-size: 0.75rem;
			font-weight: 600;
			text-transform: uppercase;
			color: var(--sidebar-muted);
			padding: 0.5rem 0.8rem;
			cursor: pointer;
			border-radius: var(--sidebar-radius);

			>i:first-child {
				min-width: 20px;
				text-align: center;
			}

			span {
				flex: 1;
				white-space: normal;
				line-height: 1.2;
			}

			&:hover {
				background: var(--sidebar-hover);
			}

			.toggle-icon {
				transition: rotate 0.2s ease;
				font-size: 0.7rem;
			}
		}

		/* Rota el chevrón cuando el acordeón está abierto */
		.nav-group[open] .toggle-icon {
			rotate: 180deg;
		}

		.group-items {
			padding-left: 1.2rem;
			display: flex;
			flex-direction: column;

			a {
				display: flex;
				align-items: center;
				gap: 12px;
				padding: 0.4rem 0.8rem;
				color: var(--sidebar-muted);
				text-decoration: none;
				border-radius: var(--sidebar-radius);
				margin-bottom: 2px;

				&:hover {
					background: var(--sidebar-hover);
					color: var(--sidebar-muted);
				}
			}
		}

		/* ===== FOOTER Y DIVISOR ===== */
		.sidebar-footer {
			padding: 0.8rem;
			border-top: 1px solid var(--sidebar-border);
			font-size: 0.7rem;
			text-align: left;

			i {
				margin-right: 6px;
			}
		}

		.sidebar-divider {
			height: 1px;
			background: var(--sidebar-border);
			margin: 0.6rem 0;
		}
	}
</style>