-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: sofit_gym
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `analisis_energetico`
--

DROP TABLE IF EXISTS `analisis_energetico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analisis_energetico` (
  `id_analisis` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` date NOT NULL,
  `calorias_consumidas` int(11) DEFAULT NULL,
  `calorias_gastadas_estimadas` int(11) DEFAULT NULL,
  `balance` int(11) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `recomendacion` text DEFAULT NULL,
  PRIMARY KEY (`id_analisis`),
  KEY `cedula_cliente` (`cedula_cliente`),
  CONSTRAINT `analisis_energetico_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analisis_energetico`
--

LOCK TABLES `analisis_energetico` WRITE;
/*!40000 ALTER TABLE `analisis_energetico` DISABLE KEYS */;
/*!40000 ALTER TABLE `analisis_energetico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistencia_clase`
--

DROP TABLE IF EXISTS `asistencia_clase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia_clase` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_clase` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `asistio` tinyint(1) DEFAULT 1,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_asistencia`),
  KEY `id_clase` (`id_clase`),
  KEY `cedula_cliente` (`cedula_cliente`),
  CONSTRAINT `asistencia_clase_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`) ON DELETE CASCADE,
  CONSTRAINT `asistencia_clase_ibfk_2` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia_clase`
--

LOCK TABLES `asistencia_clase` WRITE;
/*!40000 ALTER TABLE `asistencia_clase` DISABLE KEYS */;
/*!40000 ALTER TABLE `asistencia_clase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asistencia_gimnasio`
--

DROP TABLE IF EXISTS `asistencia_gimnasio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asistencia_gimnasio` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo` enum('Entrada','Salida') NOT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `cedula_cliente` (`cedula_cliente`),
  KEY `idx_asistencias_fecha` (`fecha`),
  CONSTRAINT `asistencia_gimnasio_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asistencia_gimnasio`
--

LOCK TABLES `asistencia_gimnasio` WRITE;
/*!40000 ALTER TABLE `asistencia_gimnasio` DISABLE KEYS */;
INSERT INTO `asistencia_gimnasio` VALUES (4,'V-11111111','2026-05-17 12:12:12','Entrada'),(6,'V-22222222','2026-05-18 12:12:12','Entrada'),(9,'V-33333333','2026-05-21 12:12:12','Entrada'),(10,'V-33333333','2026-05-23 12:12:12','Entrada'),(11,'V-11111111','2026-05-24 12:12:12','Entrada');
/*!40000 ALTER TABLE `asistencia_gimnasio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clase`
--

DROP TABLE IF EXISTS `clase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clase` (
  `id_clase` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_trabajador` varchar(15) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `cupos_ocupados` int(11) DEFAULT 0,
  `capacidad_maxima` int(11) NOT NULL,
  `estado` enum('Programado','En curso','Finalizado','Cancelado') DEFAULT 'Programado',
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  PRIMARY KEY (`id_clase`),
  KEY `cedula_trabajador` (`cedula_trabajador`),
  CONSTRAINT `clase_ibfk_1` FOREIGN KEY (`cedula_trabajador`) REFERENCES `trabajador` (`cedula_trabajador`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clase`
--

LOCK TABLES `clase` WRITE;
/*!40000 ALTER TABLE `clase` DISABLE KEYS */;
INSERT INTO `clase` VALUES (1,'V-00000002','Yoga',NULL,0,20,'Programado','2026-04-26 10:00:00','2026-04-26 11:00:00');
/*!40000 ALTER TABLE `clase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `cedula_cliente` varchar(15) NOT NULL,
  `id_membresia` int(11) NOT NULL,
  PRIMARY KEY (`cedula_cliente`),
  KEY `id_membresia` (`id_membresia`),
  CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cliente_ibfk_2` FOREIGN KEY (`id_membresia`) REFERENCES `membresia` (`id_membresia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES ('V-22222222',12),('V-11111111',19),('V-33333333',24),('V-21215215',26);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consulta_asistente`
--

DROP TABLE IF EXISTS `consulta_asistente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consulta_asistente` (
  `id_consulta` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `cedula_cliente` varchar(15) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `tipo` text DEFAULT NULL,
  `pregunta` text DEFAULT NULL,
  `respuesta` text DEFAULT NULL,
  PRIMARY KEY (`id_consulta`),
  KEY `id_usuario` (`id_usuario`),
  KEY `idx_consultas_fecha` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consulta_asistente`
--

LOCK TABLES `consulta_asistente` WRITE;
/*!40000 ALTER TABLE `consulta_asistente` DISABLE KEYS */;
/*!40000 ALTER TABLE `consulta_asistente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ejercicio`
--

DROP TABLE IF EXISTS `ejercicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ejercicio` (
  `id_ejercicio` int(11) NOT NULL AUTO_INCREMENT,
  `id_dificultad` int(11) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `grupo_muscular` varchar(100) DEFAULT NULL,
  `equipo_requerido` text DEFAULT NULL,
  PRIMARY KEY (`id_ejercicio`),
  KEY `id_dificultad` (`id_dificultad`),
  CONSTRAINT `ejercicio_ibfk_1` FOREIGN KEY (`id_dificultad`) REFERENCES `tipo_dificultad` (`id_dificultad`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ejercicio`
--

LOCK TABLES `ejercicio` WRITE;
/*!40000 ALTER TABLE `ejercicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `ejercicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipo`
--

DROP TABLE IF EXISTS `equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipo` (
  `codigo_equipo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `estado` enum('Operativo','Mantenimiento','Fuera de Servicio') DEFAULT 'Operativo',
  `ubicacion` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`codigo_equipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipo`
--

LOCK TABLES `equipo` WRITE;
/*!40000 ALTER TABLE `equipo` DISABLE KEYS */;
INSERT INTO `equipo` VALUES ('EQ-001','Cinta de correr','Cardio','Operativo',NULL,1),('OOM-3285','Plancha','Diagnostico','Mantenimiento','Salon',1);
/*!40000 ALTER TABLE `equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_membresia`
--

DROP TABLE IF EXISTS `estado_membresia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_membresia` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_membresia`
--

LOCK TABLES `estado_membresia` WRITE;
/*!40000 ALTER TABLE `estado_membresia` DISABLE KEYS */;
INSERT INTO `estado_membresia` VALUES (1,'Activo'),(2,'Vencido'),(3,'Moroso');
/*!40000 ALTER TABLE `estado_membresia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horario_trabajador`
--

DROP TABLE IF EXISTS `horario_trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horario_trabajador` (
  `id_horario` int(11) NOT NULL,
  `cedula_trabajador` varchar(15) NOT NULL,
  `dia_semana` varchar(15) DEFAULT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `cedula_trabajador` (`cedula_trabajador`),
  CONSTRAINT `horario_trabajador_ibfk_1` FOREIGN KEY (`cedula_trabajador`) REFERENCES `trabajador` (`cedula_trabajador`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horario_trabajador`
--

LOCK TABLES `horario_trabajador` WRITE;
/*!40000 ALTER TABLE `horario_trabajador` DISABLE KEYS */;
/*!40000 ALTER TABLE `horario_trabajador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscripcion_clase`
--

DROP TABLE IF EXISTS `inscripcion_clase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripcion_clase` (
  `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT,
  `id_clase` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `estado` enum('Activo','Cancelado') DEFAULT 'Activo',
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_inscripcion`),
  UNIQUE KEY `uk_cliente_clase` (`cedula_cliente`,`id_clase`),
  KEY `id_clase` (`id_clase`),
  CONSTRAINT `inscripcion_clase_ibfk_1` FOREIGN KEY (`id_clase`) REFERENCES `clase` (`id_clase`) ON DELETE CASCADE,
  CONSTRAINT `inscripcion_clase_ibfk_2` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripcion_clase`
--

LOCK TABLES `inscripcion_clase` WRITE;
/*!40000 ALTER TABLE `inscripcion_clase` DISABLE KEYS */;
INSERT INTO `inscripcion_clase` VALUES (1,1,'V-11111111','Activo','2026-04-26 20:03:02');
/*!40000 ALTER TABLE `inscripcion_clase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mantenimiento_equipo`
--

DROP TABLE IF EXISTS `mantenimiento_equipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mantenimiento_equipo` (
  `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_equipo` varchar(20) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('Preventivo','Correctivo') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `tecnico` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_mantenimiento`),
  KEY `codigo_equipo` (`codigo_equipo`),
  CONSTRAINT `mantenimiento_equipo_ibfk_1` FOREIGN KEY (`codigo_equipo`) REFERENCES `equipo` (`codigo_equipo`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mantenimiento_equipo`
--

LOCK TABLES `mantenimiento_equipo` WRITE;
/*!40000 ALTER TABLE `mantenimiento_equipo` DISABLE KEYS */;
INSERT INTO `mantenimiento_equipo` VALUES (1,'EQ-001','2026-03-15','Preventivo','Lubricación y calibración',NULL,NULL),(6,'EQ-001','2026-05-23','Preventivo','asf',99999999.99,'asf');
/*!40000 ALTER TABLE `mantenimiento_equipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `membresia`
--

DROP TABLE IF EXISTS `membresia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `membresia` (
  `id_membresia` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 3,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_membresia`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_estado` (`id_estado`),
  CONSTRAINT `membresia_ibfk_1` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_membresia` (`id_tipo`) ON UPDATE CASCADE,
  CONSTRAINT `membresia_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado_membresia` (`id_estado`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `membresia`
--

LOCK TABLES `membresia` WRITE;
/*!40000 ALTER TABLE `membresia` DISABLE KEYS */;
INSERT INTO `membresia` VALUES (1,1,2,'2026-05-01','2026-05-31'),(2,2,2,'2026-03-01','2026-05-30'),(3,1,2,'2026-04-01','2026-04-30'),(4,1,1,'2026-05-24','2026-05-30'),(5,1,1,'2026-05-17','2026-05-30'),(6,1,1,'2026-05-17','2026-05-30'),(7,1,1,'2026-05-18','2026-05-30'),(8,1,1,'2026-05-17','2026-05-30'),(9,1,2,'2026-05-18','2026-06-17'),(10,2,2,'2026-05-18','2026-08-16'),(11,1,2,'2026-05-17','2026-06-16'),(12,2,1,'2026-05-17','2026-08-15'),(13,1,2,'2026-05-17','2026-06-16'),(14,1,2,'2026-05-17','2026-06-16'),(15,1,2,'2026-05-18','2026-06-17'),(16,1,2,'2026-05-18','2026-06-17'),(17,1,2,'2026-05-18','2026-06-17'),(18,1,2,'2026-05-18','2026-06-17'),(19,1,1,'2026-05-18','2026-06-17'),(20,1,1,'2026-05-17','2026-05-30'),(21,1,1,'2026-05-19','2026-05-30'),(22,1,2,'2026-05-22','2026-06-21'),(23,1,1,'2026-05-21','2026-05-30'),(24,1,1,'2026-05-22','2026-06-21'),(25,1,1,'2026-05-22','2026-05-30'),(26,1,1,'2026-05-23','2026-05-30');
/*!40000 ALTER TABLE `membresia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificacion`
--

DROP TABLE IF EXISTS `notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificacion` (
  `id_notificacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_tipo_notificacion` int(11) NOT NULL,
  `id_tipo_canal` int(11) NOT NULL,
  `cedula_cliente` varchar(15) NOT NULL,
  `mensaje` text NOT NULL,
  `estado` enum('Pendiente','Enviado','Fallido') DEFAULT 'Pendiente',
  `fecha_programada` datetime DEFAULT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`),
  KEY `cedula_cliente` (`cedula_cliente`),
  KEY `id_tipo_notificacion` (`id_tipo_notificacion`),
  KEY `id_tipo_canal` (`id_tipo_canal`),
  CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE,
  CONSTRAINT `notificacion_ibfk_2` FOREIGN KEY (`id_tipo_notificacion`) REFERENCES `tipo_notificacion` (`id_tipo`),
  CONSTRAINT `notificacion_ibfk_3` FOREIGN KEY (`id_tipo_canal`) REFERENCES `tipo_canal` (`id_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificacion`
--

LOCK TABLES `notificacion` WRITE;
/*!40000 ALTER TABLE `notificacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_cliente` varchar(15) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `comprobante_url` varchar(255) DEFAULT NULL,
  `estado` enum('Pagado','Pendiente','Atrasado') DEFAULT 'Pagado',
  `fecha_pago` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  PRIMARY KEY (`id_pago`),
  KEY `cedula_cliente` (`cedula_cliente`),
  CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
INSERT INTO `pago` VALUES (1,'V-11111111',30.00,'Efectivo',NULL,'Pagado','2026-05-01','2026-05-31'),(2,'V-22222222',80.00,'Transferencia',NULL,'Atrasado','2026-03-01','2026-05-30'),(3,'V-33333333',30.00,'Efectivo',NULL,'Atrasado','2026-04-01','2026-04-30'),(4,'V-33333333',5.00,'Efectivo','','Pagado','2026-05-18','2026-06-17'),(5,'V-22222222',5.00,'Efectivo','','Pagado','2026-05-18','2026-08-16'),(7,'V-22222222',4.00,'Efectivo','','Pagado','2026-05-17','2026-08-15'),(13,'V-33333333',5.00,'Efectivo','','Pagado','2026-05-18','2026-06-17'),(14,'V-11111111',5.00,'Efectivo','','Pagado','2026-05-18','2026-06-17'),(15,'V-33333333',5.00,'Efectivo','','Pagado','2026-05-22','2026-06-21'),(16,'V-33333333',6.00,'Efectivo','','Pagado','2026-05-22','2026-06-21');
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `cedula_persona` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`cedula_persona`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES ('325325','asfas','fas','hola@gmail.com','2323632','fa','2026-05-21','2026-05-22 00:37:59',1),('V-00000001','Carlos','Pérez','carlos@sofit.com','0412-4471891',NULL,'2026-05-21','2026-05-25 02:14:56',1),('V-00000002','Ana','Gómez','ana@sofit.com','0426-2142141',NULL,'2026-05-21','2026-05-22 01:29:17',1),('V-11111111','María','Torres','maria@example.com','0412-1234567',NULL,'2026-05-17','2026-05-18 18:14:28',1),('V-11111898','ll','fsfas','hola@gmail.com','0412-3253252','jk','2026-05-17','2026-05-17 23:52:14',1),('V-12421421','asfsafXXD','f','hola@gmail.com','0412-2421412','asf','2026-05-18','2026-05-19 01:40:57',1),('V-12521555','SSS','ff','hola@gmail.com','0412-4212512','asfas','2026-05-19','2026-05-17 04:51:33',1),('V-13131412','asasf','asgas','hola@gmail.com','0424-2152151','asfasf','2026-05-18','2026-05-17 20:50:21',1),('V-21215215','Carlos','fasf','hola@gmail.com','0412-2141241','asaf','2026-05-23','2026-05-23 20:42:12',1),('V-22222222','Luis','Martínez','luis@example.com','0412-7654321',NULL,'2026-05-17','2026-05-24 18:38:20',1),('V-22222224','Paola','fasf','hola@gmail.com','0412-1242142','asfasf','2026-05-22','2026-05-22 22:01:55',1),('V-25125152','afas','saf','gasgsaas@gmail.com','0412-2152152','asfa','2026-05-22','2026-05-17 00:53:17',1),('V-31114255','asf','asf','hola@gmail.com','0412-4471891','safasf','2026-05-20','2026-05-20 05:28:44',1),('V-31492771','LOL','faf','hola@gmail.com','0412-1412453','asf','2026-05-21','2026-05-22 04:05:11',1),('V-32523523','saf','asf','hola@gmail.com','0412-1421412','asfa','2026-05-23','2026-05-24 05:07:31',1),('V-33333333','Juan','Garcia','moroso@test.com','0412-4471891',NULL,'2026-05-15','2026-05-17 04:45:03',1),('V-42142155','XD','fa','hola@gmail.com','0412-2141241','asaf','2026-05-22','2026-05-24 05:07:46',1),('V-93682363','Pan','Waos','gasgsaas@gmail.com','0412-2521512','asfas','2026-05-17','2026-05-17 00:52:10',1);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `codigo_producto` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock_minimo` int(11) DEFAULT 0,
  `stock_actual` int(11) NOT NULL DEFAULT 0,
  `unidad_medida` varchar(20) DEFAULT 'unidad',
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`codigo_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES ('1313131','asfasfasfas','Suplementos',4444.00,5,10,'unidad',1),('PROT001','Proteína Whe','',45.00,0,19,'unidad',0);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rutina`
--

DROP TABLE IF EXISTS `rutina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rutina` (
  `id_rutina` int(11) NOT NULL AUTO_INCREMENT,
  `id_dificultad` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `objetivo` text DEFAULT NULL,
  `duracion_semanas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_rutina`),
  KEY `id_dificultad` (`id_dificultad`),
  CONSTRAINT `rutina_ibfk_1` FOREIGN KEY (`id_dificultad`) REFERENCES `tipo_dificultad` (`id_dificultad`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rutina`
--

LOCK TABLES `rutina` WRITE;
/*!40000 ALTER TABLE `rutina` DISABLE KEYS */;
INSERT INTO `rutina` VALUES (1,1,'Fuerza Básica',NULL,NULL,NULL);
/*!40000 ALTER TABLE `rutina` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rutina_asignada`
--

DROP TABLE IF EXISTS `rutina_asignada`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rutina_asignada` (
  `id_asignacion` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_cliente` varchar(15) NOT NULL,
  `id_rutina` int(11) NOT NULL,
  `fecha_asignacion` date NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('Activa','Completada','Cancelada') DEFAULT 'Activa',
  `progreso` decimal(5,2) DEFAULT 0.00,
  PRIMARY KEY (`id_asignacion`),
  KEY `cedula_cliente` (`cedula_cliente`),
  KEY `id_rutina` (`id_rutina`),
  CONSTRAINT `rutina_asignada_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rutina_asignada_ibfk_2` FOREIGN KEY (`id_rutina`) REFERENCES `rutina` (`id_rutina`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rutina_asignada`
--

LOCK TABLES `rutina_asignada` WRITE;
/*!40000 ALTER TABLE `rutina_asignada` DISABLE KEYS */;
INSERT INTO `rutina_asignada` VALUES (1,'V-33333333',1,'2026-05-21','2026-05-20','2026-05-31','Activa',0.00);
/*!40000 ALTER TABLE `rutina_asignada` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimiento_fisico`
--

DROP TABLE IF EXISTS `seguimiento_fisico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimiento_fisico` (
  `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` date DEFAULT NULL,
  `altura_cm` decimal(5,2) DEFAULT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `cintura_cm` decimal(5,2) DEFAULT NULL,
  `cadera_cm` decimal(5,2) DEFAULT NULL,
  `pecho_cm` decimal(5,2) DEFAULT NULL,
  `muslo_cm` decimal(5,2) DEFAULT NULL,
  `hombros_cm` decimal(5,2) DEFAULT NULL,
  `pantorrilla_cm` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_seguimiento`),
  KEY `cedula_cliente` (`cedula_cliente`),
  CONSTRAINT `seguimiento_fisico_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimiento_fisico`
--

LOCK TABLES `seguimiento_fisico` WRITE;
/*!40000 ALTER TABLE `seguimiento_fisico` DISABLE KEYS */;
INSERT INTO `seguimiento_fisico` VALUES (3,'V-11111111','2026-05-17',2.00,4.00,NULL,NULL,NULL,NULL,NULL,NULL),(14,'V-22222222','2026-05-20',111.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,'V-22222222','2026-05-24',210.00,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `seguimiento_fisico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimiento_nutricional`
--

DROP TABLE IF EXISTS `seguimiento_nutricional`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimiento_nutricional` (
  `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_cliente` varchar(15) NOT NULL,
  `fecha` date DEFAULT NULL,
  `proteinas_g` decimal(5,2) DEFAULT NULL,
  `carbohidratos_g` decimal(5,2) DEFAULT NULL,
  `grasas_g` decimal(5,2) DEFAULT NULL,
  `calorias_diarias` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_seguimiento`),
  KEY `cedula_cliente` (`cedula_cliente`),
  CONSTRAINT `seguimiento_nutricional_ibfk_1` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimiento_nutricional`
--

LOCK TABLES `seguimiento_nutricional` WRITE;
/*!40000 ALTER TABLE `seguimiento_nutricional` DISABLE KEYS */;
INSERT INTO `seguimiento_nutricional` VALUES (3,'V-11111111','2026-05-17',112.40,325.30,326.60,757.00);
/*!40000 ALTER TABLE `seguimiento_nutricional` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_canal`
--

DROP TABLE IF EXISTS `tipo_canal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_canal` (
  `id_tipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_canal`
--

LOCK TABLES `tipo_canal` WRITE;
/*!40000 ALTER TABLE `tipo_canal` DISABLE KEYS */;
INSERT INTO `tipo_canal` VALUES (1,'App'),(2,'Email'),(3,'WhatsApp');
/*!40000 ALTER TABLE `tipo_canal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_dificultad`
--

DROP TABLE IF EXISTS `tipo_dificultad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_dificultad` (
  `id_dificultad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_dificultad`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_dificultad`
--

LOCK TABLES `tipo_dificultad` WRITE;
/*!40000 ALTER TABLE `tipo_dificultad` DISABLE KEYS */;
INSERT INTO `tipo_dificultad` VALUES (1,'Principiante'),(2,'Intermedio'),(3,'Avanzado');
/*!40000 ALTER TABLE `tipo_dificultad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_membresia`
--

DROP TABLE IF EXISTS `tipo_membresia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_membresia` (
  `id_tipo` int(11) NOT NULL COMMENT '1=Mensual,2=Trimestral,3=Anual',
  `nombre` varchar(100) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_membresia`
--

LOCK TABLES `tipo_membresia` WRITE;
/*!40000 ALTER TABLE `tipo_membresia` DISABLE KEYS */;
INSERT INTO `tipo_membresia` VALUES (1,'Mensual',30.00),(2,'Trimestral',80.00),(3,'Anual',300.00);
/*!40000 ALTER TABLE `tipo_membresia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_notificacion`
--

DROP TABLE IF EXISTS `tipo_notificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tipo_notificacion` (
  `id_tipo` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_notificacion`
--

LOCK TABLES `tipo_notificacion` WRITE;
/*!40000 ALTER TABLE `tipo_notificacion` DISABLE KEYS */;
INSERT INTO `tipo_notificacion` VALUES (1,'Pago vencimiento'),(2,'Recordatorio clase'),(3,'Promoción'),(4,'Otro');
/*!40000 ALTER TABLE `tipo_notificacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trabajador`
--

DROP TABLE IF EXISTS `trabajador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trabajador` (
  `cedula_trabajador` varchar(15) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `fecha_contratacion` date DEFAULT NULL,
  PRIMARY KEY (`cedula_trabajador`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `trabajador_ibfk_1` FOREIGN KEY (`cedula_trabajador`) REFERENCES `persona` (`cedula_persona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trabajador`
--

LOCK TABLES `trabajador` WRITE;
/*!40000 ALTER TABLE `trabajador` DISABLE KEYS */;
INSERT INTO `trabajador` VALUES ('V-00000001',1,50.00,'2026-05-21'),('V-00000002',2,5.00,'2026-05-22');
/*!40000 ALTER TABLE `trabajador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta_producto`
--

DROP TABLE IF EXISTS `venta_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta_producto` (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_producto` varchar(20) NOT NULL,
  `cedula_cliente` varchar(15) DEFAULT NULL,
  `cantidad_vendida` decimal(10,2) DEFAULT NULL,
  `monto_total` varchar(100) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id_venta`),
  KEY `codigo_producto` (`codigo_producto`),
  KEY `cedula_cliente` (`cedula_cliente`),
  KEY `idx_ventas_fecha` (`fecha`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `producto` (`codigo_producto`) ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta_producto`
--

LOCK TABLES `venta_producto` WRITE;
/*!40000 ALTER TABLE `venta_producto` DISABLE KEYS */;
INSERT INTO `venta_producto` VALUES (1,'PROT001','V-11111111',45.00,NULL,'Efectivo','2026-04-26 02:55:55');
/*!40000 ALTER TABLE `venta_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'sofit_gym'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-25 13:27:01
