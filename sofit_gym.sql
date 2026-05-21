-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-05-2026 a las 01:36:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sofit_gym`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `analisis_energetico`
--

CREATE TABLE `analisis_energetico` (
  `id_analisis` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` date NOT NULL,
  `calorias_consumidas` int(11) DEFAULT NULL,
  `calorias_gastadas_estimadas` int(11) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `recomendacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_clase`
--

CREATE TABLE `asistencia_clase` (
  `id_asistencia` int(11) NOT NULL,
  `id_clase` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `asistio` tinyint(1) DEFAULT 1,
  `fecha_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia_gimnasio`
--

CREATE TABLE `asistencia_gimnasio` (
  `id_asistencia` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo` enum('Entrada','Salida') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencia_gimnasio`
--

INSERT INTO `asistencia_gimnasio` (`id_asistencia`, `cedula_cliente`, `fecha`, `tipo`) VALUES
(4, 'V-11111111', '2026-05-17 12:12:12', 'Entrada'),
(6, 'V-22222222', '2026-05-18 12:12:12', 'Entrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clase`
--

CREATE TABLE `clase` (
  `id_clase` int(11) NOT NULL,
  `cedula_trabajador` varchar(15) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cupos_ocupados` int(11) DEFAULT 0,
  `capacidad_maxima` int(11) NOT NULL,
  `estado` enum('Programado','En curso','Finalizado','Cancelado') DEFAULT 'Programado',
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clase`
--

INSERT INTO `clase` (`id_clase`, `cedula_trabajador`, `nombre`, `descripcion`, `cupos_ocupados`, `capacidad_maxima`, `estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 'V-00000002', 'Yoga', NULL, 0, 20, 'Programado', '2026-04-26 10:00:00', '2026-04-26 11:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cedula_cliente` varchar(15) NOT NULL,
  `id_membresia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cedula_cliente`, `id_membresia`) VALUES
('V-22222222', 12),
('V-33333333', 18),
('V-11111111', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_asistente`
--

CREATE TABLE `consulta_asistente` (
  `id_consulta` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `cedula_cliente` varchar(15) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo` text DEFAULT NULL,
  `pregunta` text DEFAULT NULL,
  `respuesta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejercicio`
--

CREATE TABLE `ejercicio` (
  `id_ejercicio` int(11) NOT NULL,
  `id_dificultad` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `grupo_muscular` varchar(100) DEFAULT NULL,
  `equipo_requerido` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `codigo_equipo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `estado` enum('Operativo','Mantenimiento','Fuera de Servicio') DEFAULT 'Operativo',
  `ubicacion` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`codigo_equipo`, `nombre`, `tipo`, `estado`, `ubicacion`, `activo`) VALUES
('EQ-001', 'Cinta de correr', 'Cardio', 'Operativo', NULL, 1),
('OOM-3285', 'Plancha', 'Diagnostico', 'Mantenimiento', 'Salon', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_membresia`
--

CREATE TABLE `estado_membresia` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `estado_membresia`
--

INSERT INTO `estado_membresia` (`id_estado`, `nombre`) VALUES
(1, 'Activo'),
(2, 'Vencido'),
(3, 'Moroso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_trabajador`
--

CREATE TABLE `horario_trabajador` (
  `id_horario` int(11) NOT NULL,
  `cedula_trabajador` varchar(15) NOT NULL,
  `dia_semana` varchar(15) DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion_clase`
--

CREATE TABLE `inscripcion_clase` (
  `id_inscripcion` int(11) NOT NULL,
  `id_clase` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `estado` enum('Activo','Cancelado') DEFAULT 'Activo',
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inscripcion_clase`
--

INSERT INTO `inscripcion_clase` (`id_inscripcion`, `id_clase`, `cedula_cliente`, `estado`, `fecha`) VALUES
(1, 1, 'V-11111111', 'Activo', '2026-04-26 20:03:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_equipo`
--

CREATE TABLE `mantenimiento_equipo` (
  `id_mantenimiento` int(11) NOT NULL,
  `codigo_equipo` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('Preventivo','Correctivo') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `tecnico` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mantenimiento_equipo`
--

INSERT INTO `mantenimiento_equipo` (`id_mantenimiento`, `codigo_equipo`, `fecha`, `tipo`, `descripcion`, `costo`, `tecnico`) VALUES
(1, 'EQ-001', '2026-03-15', 'Preventivo', 'Lubricación y calibración', NULL, NULL),
(2, 'EQ-001', '2026-05-20', 'Correctivo', 'kj', 5555.00, 'jkj');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `membresia`
--

CREATE TABLE `membresia` (
  `id_membresia` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 3,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `membresia`
--

INSERT INTO `membresia` (`id_membresia`, `id_tipo`, `id_estado`, `fecha_inicio`, `fecha_fin`) VALUES
(1, 1, 2, '2026-05-01', '2026-05-31'),
(2, 2, 2, '2026-03-01', '2026-05-30'),
(3, 1, 2, '2026-04-01', '2026-04-30'),
(4, 1, 1, '2026-05-24', '2026-05-30'),
(5, 1, 1, '2026-05-17', '2026-05-30'),
(6, 1, 1, '2026-05-17', '2026-05-30'),
(7, 1, 1, '2026-05-18', '2026-05-30'),
(8, 1, 1, '2026-05-17', '2026-05-30'),
(9, 1, 2, '2026-05-18', '2026-06-17'),
(10, 2, 2, '2026-05-18', '2026-08-16'),
(11, 1, 2, '2026-05-17', '2026-06-16'),
(12, 2, 1, '2026-05-17', '2026-08-15'),
(13, 1, 2, '2026-05-17', '2026-06-16'),
(14, 1, 2, '2026-05-17', '2026-06-16'),
(15, 1, 2, '2026-05-18', '2026-06-17'),
(16, 1, 2, '2026-05-18', '2026-06-17'),
(17, 1, 2, '2026-05-18', '2026-06-17'),
(18, 1, 1, '2026-05-18', '2026-06-17'),
(19, 1, 1, '2026-05-18', '2026-06-17'),
(20, 1, 1, '2026-05-17', '2026-05-30'),
(21, 1, 1, '2026-05-19', '2026-05-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL,
  `id_tipo_notificacion` int(11) NOT NULL,
  `id_tipo_canal` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` enum('Pendiente','Enviado','Fallido') DEFAULT 'Pendiente',
  `fecha_programada` datetime DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `comprobante_url` varchar(255) DEFAULT NULL,
  `estado` enum('Pagado','Pendiente','Atrasado') DEFAULT 'Pagado',
  `fecha_pago` date NOT NULL,
  `fecha_vencimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`id_pago`, `cedula_cliente`, `monto`, `metodo_pago`, `comprobante_url`, `estado`, `fecha_pago`, `fecha_vencimiento`) VALUES
(1, 'V-11111111', 30.00, 'Efectivo', NULL, 'Pagado', '2026-05-01', '2026-05-31'),
(2, 'V-22222222', 80.00, 'Transferencia', NULL, 'Atrasado', '2026-03-01', '2026-05-30'),
(3, 'V-33333333', 30.00, 'Efectivo', NULL, 'Atrasado', '2026-04-01', '2026-04-30'),
(4, 'V-33333333', 5.00, 'Efectivo', '', 'Pagado', '2026-05-18', '2026-06-17'),
(5, 'V-22222222', 5.00, 'Efectivo', '', 'Pagado', '2026-05-18', '2026-08-16'),
(7, 'V-22222222', 4.00, 'Efectivo', '', 'Pagado', '2026-05-17', '2026-08-15'),
(13, 'V-33333333', 5.00, 'Efectivo', '', 'Pagado', '2026-05-18', '2026-06-17'),
(14, 'V-11111111', 5.00, 'Efectivo', '', 'Pagado', '2026-05-18', '2026-06-17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cedula_persona` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`cedula_persona`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `fecha_nacimiento`, `fecha_registro`, `activo`) VALUES
('325325', 'asfas', 'fas', 'hola@gmail.com', '2323632', 'fa', '2026-05-21', '2026-05-22 00:37:59', 1),
('V-00000001', 'Carlos', 'Pérez', 'carlos@sofit.com', '0412-4471891', NULL, '2026-05-21', '2026-05-22 01:29:49', 1),
('V-00000002', 'Ana', 'Gómez', 'ana@sofit.com', '0426-2142141', NULL, '2026-05-21', '2026-05-22 01:29:17', 1),
('V-11111111', 'María', 'Torres', 'maria@example.com', '0412-1234567', NULL, '2026-05-17', '2026-05-18 18:14:28', 1),
('V-11111898', 'll', 'fsfas', 'hola@gmail.com', '0412-3253252', 'jk', '2026-05-17', '2026-05-17 23:52:14', 1),
('V-12421421', 'asfsafXXD', 'f', 'hola@gmail.com', '0412-2421412', 'asf', '2026-05-18', '2026-05-19 01:40:57', 1),
('V-12521555', 'SSS', 'ff', 'hola@gmail.com', '0412-4212512', 'asfas', '2026-05-19', '2026-05-17 04:51:33', 1),
('V-13131412', 'asasf', 'asgas', 'hola@gmail.com', '0424-2152151', 'asfasf', '2026-05-18', '2026-05-17 20:50:21', 1),
('V-22222222', 'Luis', 'Martínez', 'luis@example.com', '0412-7654321', NULL, '2026-05-17', '2026-05-20 07:11:03', 1),
('V-25125152', 'afas', 'saf', 'gasgsaas@gmail.com', '0412-2152152', 'asfa', '2026-05-22', '2026-05-17 00:53:17', 1),
('V-31114255', 'asf', 'asf', 'hola@gmail.com', '0412-4471891', 'safasf', '2026-05-20', '2026-05-20 05:28:44', 1),
('V-33333333', 'Juan', 'Garcia', 'moroso@test.com', '0412-4471891', NULL, '2026-05-15', '2026-05-17 04:45:03', 1),
('V-42142155', 'HOLA', 'fa', 'hola@gmail.com', '0412-2141241', 'asaf', '2026-05-22', '2026-05-22 01:25:04', 1),
('V-93682363', 'Pan', 'Waos', 'gasgsaas@gmail.com', '0412-2521512', 'asfas', '2026-05-17', '2026-05-17 00:52:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `codigo_producto` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_minimo` int(11) DEFAULT 0,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `unidad_medida` varchar(20) DEFAULT 'unidad',
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`codigo_producto`, `nombre`, `categoria`, `precio_venta`, `stock_minimo`, `stock_actual`, `unidad_medida`, `activo`) VALUES
('1313131', 'asfasfasfas', 'Suplementos', 4444.00, 5, 0, 'unidad', 1),
('PROT001', 'Proteína Whe', '', 45.00, 0, 19, 'unidad', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina`
--

CREATE TABLE `rutina` (
  `id_rutina` int(11) NOT NULL,
  `id_dificultad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `objetivo` text DEFAULT NULL,
  `duracion_semanas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rutina`
--

INSERT INTO `rutina` (`id_rutina`, `id_dificultad`, `nombre`, `descripcion`, `objetivo`, `duracion_semanas`) VALUES
(1, 1, 'Fuerza Básica', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutina_asignada`
--

CREATE TABLE `rutina_asignada` (
  `id_asignacion` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('Activa','Completada','Cancelada') DEFAULT 'Activa',
  `progreso` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rutina_asignada`
--

INSERT INTO `rutina_asignada` (`id_asignacion`, `cedula_cliente`, `id_rutina`, `fecha_asignacion`, `fecha_inicio`, `fecha_fin`, `estado`, `progreso`) VALUES
(1, 'V-33333333', 1, '2026-05-21', '2026-05-20', '2026-05-31', 'Activa', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_fisico`
--

CREATE TABLE `seguimiento_fisico` (
  `id_seguimiento` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` date DEFAULT NULL,
  `altura_cm` decimal(5,2) DEFAULT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `cintura_cm` decimal(5,2) DEFAULT NULL,
  `cadera_cm` decimal(5,2) DEFAULT NULL,
  `pecho_cm` decimal(5,2) DEFAULT NULL,
  `muslo_cm` decimal(5,2) DEFAULT NULL,
  `hombros_cm` decimal(5,2) DEFAULT NULL,
  `pantorrilla_cm` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seguimiento_fisico`
--

INSERT INTO `seguimiento_fisico` (`id_seguimiento`, `cedula_cliente`, `fecha`, `altura_cm`, `peso_kg`, `cintura_cm`, `cadera_cm`, `pecho_cm`, `muslo_cm`, `hombros_cm`, `pantorrilla_cm`) VALUES
(3, 'V-11111111', '2026-05-17', 2.00, 4.00, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 'V-22222222', '2026-05-20', 111.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento_nutricional`
--

CREATE TABLE `seguimiento_nutricional` (
  `id_seguimiento` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` date DEFAULT NULL,
  `proteinas_g` decimal(5,2) DEFAULT NULL,
  `carbohidratos_g` decimal(5,2) DEFAULT NULL,
  `grasas_g` decimal(5,2) DEFAULT NULL,
  `calorias_diarias` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seguimiento_nutricional`
--

INSERT INTO `seguimiento_nutricional` (`id_seguimiento`, `cedula_cliente`, `fecha`, `proteinas_g`, `carbohidratos_g`, `grasas_g`, `calorias_diarias`) VALUES
(3, 'V-11111111', '2026-05-17', 112.40, 325.30, 326.60, 757.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_canal`
--

CREATE TABLE `tipo_canal` (
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_canal`
--

INSERT INTO `tipo_canal` (`id_tipo`, `nombre`) VALUES
(1, 'App'),
(2, 'Email'),
(3, 'WhatsApp');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_dificultad`
--

CREATE TABLE `tipo_dificultad` (
  `id_dificultad` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_dificultad`
--

INSERT INTO `tipo_dificultad` (`id_dificultad`, `nombre`) VALUES
(1, 'Principiante'),
(2, 'Intermedio'),
(3, 'Avanzado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_membresia`
--

CREATE TABLE `tipo_membresia` (
  `id_tipo` int(11) NOT NULL COMMENT '1=Mensual,2=Trimestral,3=Anual',
  `nombre` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_membresia`
--

INSERT INTO `tipo_membresia` (`id_tipo`, `nombre`, `monto`) VALUES
(1, 'Mensual', 30.00),
(2, 'Trimestral', 80.00),
(3, 'Anual', 300.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_notificacion`
--

CREATE TABLE `tipo_notificacion` (
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_notificacion`
--

INSERT INTO `tipo_notificacion` (`id_tipo`, `nombre`) VALUES
(1, 'Pago vencimiento'),
(2, 'Recordatorio clase'),
(3, 'Promoción'),
(4, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_rol`
--

CREATE TABLE `tipo_rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_rol`
--

INSERT INTO `tipo_rol` (`id_rol`, `nombre`) VALUES
(1, 'Gerente'),
(2, 'Entrenador'),
(3, 'Recepcionista'),
(4, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `cedula_trabajador` varchar(15) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `trabajador`
--

INSERT INTO `trabajador` (`cedula_trabajador`, `id_rol`, `salario`, `fecha_contratacion`) VALUES
('V-00000001', 1, 5.00, '2026-05-21'),
('V-00000002', 2, 5.00, '2026-05-22'),
('V-42142155', 2, 5.00, '2026-05-21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `cedula_persona` varchar(15) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `ultimo_acceso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_rol`, `cedula_persona`, `usuario`, `contrasena`, `ultimo_acceso`) VALUES
(1, 2, 'V-00000001', 'carlos.perez', 'admin123', NULL),
(2, 2, 'V-00000002', 'ana.gomez', 'ana123', NULL),
(3, 4, 'V-11111111', 'luis.martinez', 'cliente123', NULL),
(4, 4, 'V-33333333', 'cliente.moroso', 'moroso123', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE `venta_producto` (
  `id_venta` int(11) NOT NULL,
  `codigo_producto` varchar(20) NOT NULL,
  `cedula_cliente` varchar(15) DEFAULT NULL,
  `cantidad_vendida` decimal(10,2) DEFAULT NULL,
  `monto_total` varchar(100) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `venta_producto`
--

INSERT INTO `venta_producto` (`id_venta`, `codigo_producto`, `cedula_cliente`, `cantidad_vendida`, `monto_total`, `metodo_pago`, `fecha`) VALUES
(1, 'PROT001', 'V-11111111', 45.00, NULL, 'Efectivo', '2026-04-26 02:55:55');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `analisis_energetico`
--
ALTER TABLE `analisis_energetico`
  ADD PRIMARY KEY (`id_analisis`),
  ADD KEY `cedula_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `asistencia_clase`
--
ALTER TABLE `asistencia_clase`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_clase` (`id_clase`),
  ADD KEY `cedula_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `asistencia_gimnasio`
--
ALTER TABLE `asistencia_gimnasio`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `cedula_cliente` (`cedula_cliente`),
  ADD KEY `idx_asistencias_fecha` (`fecha`);

--
-- Indices de la tabla `clase`
--
ALTER TABLE `clase`
  ADD PRIMARY KEY (`id_clase`),
  ADD KEY `cedula_trabajador` (`cedula_trabajador`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cedula_cliente`),
  ADD KEY `id_membresia` (`id_membresia`);

--
-- Indices de la tabla `consulta_asistente`
--
ALTER TABLE `consulta_asistente`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_consultas_fecha` (`fecha`);

--
-- Indices de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD PRIMARY KEY (`id_ejercicio`),
  ADD KEY `id_dificultad` (`id_dificultad`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`codigo_equipo`);

--
-- Indices de la tabla `estado_membresia`
--
ALTER TABLE `estado_membresia`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `horario_trabajador`
--
ALTER TABLE `horario_trabajador`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `cedula_trabajador` (`cedula_trabajador`);

--
-- Indices de la tabla `inscripcion_clase`
--
ALTER TABLE `inscripcion_clase`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD UNIQUE KEY `uk_cliente_clase` (`cedula_cliente`,`id_clase`),
  ADD KEY `id_clase` (`id_clase`);

--
-- Indices de la tabla `mantenimiento_equipo`
--
ALTER TABLE `mantenimiento_equipo`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `codigo_equipo` (`codigo_equipo`);

--
-- Indices de la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD PRIMARY KEY (`id_membresia`),
  ADD KEY `id_tipo` (`id_tipo`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `cedula_cliente` (`cedula_cliente`),
  ADD KEY `id_tipo_notificacion` (`id_tipo_notificacion`),
  ADD KEY `id_tipo_canal` (`id_tipo_canal`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `cedula_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula_persona`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`codigo_producto`);

--
-- Indices de la tabla `rutina`
--
ALTER TABLE `rutina`
  ADD PRIMARY KEY (`id_rutina`),
  ADD KEY `id_dificultad` (`id_dificultad`);

--
-- Indices de la tabla `rutina_asignada`
--
ALTER TABLE `rutina_asignada`
  ADD PRIMARY KEY (`id_asignacion`),
  ADD KEY `cedula_cliente` (`cedula_cliente`),
  ADD KEY `id_rutina` (`id_rutina`);

--
-- Indices de la tabla `seguimiento_fisico`
--
ALTER TABLE `seguimiento_fisico`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `cedula_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `seguimiento_nutricional`
--
ALTER TABLE `seguimiento_nutricional`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `cedula_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `tipo_canal`
--
ALTER TABLE `tipo_canal`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_dificultad`
--
ALTER TABLE `tipo_dificultad`
  ADD PRIMARY KEY (`id_dificultad`);

--
-- Indices de la tabla `tipo_membresia`
--
ALTER TABLE `tipo_membresia`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_notificacion`
--
ALTER TABLE `tipo_notificacion`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipo_rol`
--
ALTER TABLE `tipo_rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`cedula_trabajador`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `cedula_persona` (`cedula_persona`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `cedula_cliente` (`cedula_cliente`),
  ADD KEY `idx_ventas_fecha` (`fecha`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `analisis_energetico`
--
ALTER TABLE `analisis_energetico`
  MODIFY `id_analisis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia_clase`
--
ALTER TABLE `asistencia_clase`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencia_gimnasio`
--
ALTER TABLE `asistencia_gimnasio`
  MODIFY `id_asistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `clase`
--
ALTER TABLE `clase`
  MODIFY `id_clase` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `consulta_asistente`
--
ALTER TABLE `consulta_asistente`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  MODIFY `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripcion_clase`
--
ALTER TABLE `inscripcion_clase`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mantenimiento_equipo`
--
ALTER TABLE `mantenimiento_equipo`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `membresia`
--
ALTER TABLE `membresia`
  MODIFY `id_membresia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `rutina`
--
ALTER TABLE `rutina`
  MODIFY `id_rutina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rutina_asignada`
--
ALTER TABLE `rutina_asignada`
  MODIFY `id_asignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `seguimiento_fisico`
--
ALTER TABLE `seguimiento_fisico`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `seguimiento_nutricional`
--
ALTER TABLE `seguimiento_nutricional`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipo_canal`
--
ALTER TABLE `tipo_canal`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_dificultad`
--
ALTER TABLE `tipo_dificultad`
  MODIFY `id_dificultad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `analisis_energetico`
--
ALTER TABLE `analisis_energetico`
  ADD CONSTRAINT `analisis_energetico_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencia_clase`
--
ALTER TABLE `asistencia_clase`
  ADD CONSTRAINT `asistencia_clase_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencia_clase_ibfk_2` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistencia_gimnasio`
--
ALTER TABLE `asistencia_gimnasio`
  ADD CONSTRAINT `asistencia_gimnasio_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `clase`
--
ALTER TABLE `clase`
  ADD CONSTRAINT `clase_ibfk_1` FOREIGN KEY (`cedula_trabajador`) REFERENCES `trabajador` (`cedula_trabajador`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_membresia`) REFERENCES `membresia` (`id_membresia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `consulta_asistente`
--
ALTER TABLE `consulta_asistente`
  ADD CONSTRAINT `consulta_asistente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ejercicio`
--
ALTER TABLE `ejercicio`
  ADD CONSTRAINT `ejercicio_ibfk_1` FOREIGN KEY (`id_dificultad`) REFERENCES `tipo_dificultad` (`id_dificultad`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `horario_trabajador`
--
ALTER TABLE `horario_trabajador`
  ADD CONSTRAINT `horario_trabajador_ibfk_1` FOREIGN KEY (`cedula_trabajador`) REFERENCES `trabajador` (`cedula_trabajador`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `inscripcion_clase`
--
ALTER TABLE `inscripcion_clase`
  ADD CONSTRAINT `inscripcion_clase_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripcion_clase_ibfk_2` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `mantenimiento_equipo`
--
ALTER TABLE `mantenimiento_equipo`
  ADD CONSTRAINT `mantenimiento_equipo_ibfk_1` FOREIGN KEY (`codigo_equipo`) REFERENCES `equipo` (`codigo_equipo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `membresia`
--
ALTER TABLE `membresia`
  ADD CONSTRAINT `membresia_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_membresia` (`id_tipo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `membresia_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado_membresia` (`id_estado`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacion_ibfk_2` FOREIGN KEY (`id_tipo_notificacion`) REFERENCES `tipo_notificacion` (`id_tipo`),
  ADD CONSTRAINT `notificacion_ibfk_3` FOREIGN KEY (`id_tipo_canal`) REFERENCES `tipo_canal` (`id_tipo`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutina`
--
ALTER TABLE `rutina`
  ADD CONSTRAINT `rutina_ibfk_1` FOREIGN KEY (`id_dificultad`) REFERENCES `tipo_dificultad` (`id_dificultad`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutina_asignada`
--
ALTER TABLE `rutina_asignada`
  ADD CONSTRAINT `rutina_asignada_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rutina_asignada_ibfk_2` FOREIGN KEY (`id_rutina`) REFERENCES `rutina` (`id_rutina`) ON DELETE CASCADE;

--
-- Filtros para la tabla `seguimiento_fisico`
--
ALTER TABLE `seguimiento_fisico`
  ADD CONSTRAINT `seguimiento_fisico_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seguimiento_nutricional`
--
ALTER TABLE `seguimiento_nutricional`
  ADD CONSTRAINT `seguimiento_nutricional_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD CONSTRAINT `trabajador_ibfk_1` FOREIGN KEY (`cedula_trabajador`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trabajador_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `tipo_rol` (`id_rol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`cedula_persona`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `tipo_rol` (`id_rol`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `producto` (`codigo_producto`) ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
