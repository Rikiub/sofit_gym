-- ======================================================
-- BASE DE DATOS COMPLETA PARA SOFIT GYM
-- Incluye todas las tablas + datos de prueba + cliente moroso V-33333333
-- ======================================================

-- Eliminar la base de datos si existe y crearla limpia
DROP DATABASE IF EXISTS sofit_gym;
CREATE DATABASE sofit_gym CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sofit_gym;

-- ========== 1. TABLAS CATÁLOGO ==========
CREATE TABLE tipo_rol (
  id_rol INT(11) NOT NULL PRIMARY KEY,
  nombre VARCHAR(100)
);

CREATE TABLE tipo_dificultad (
  id_dificultad INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100)
);

CREATE TABLE tipo_membresia (
  id_tipo INT(11) NOT NULL PRIMARY KEY COMMENT '1=Mensual,2=Trimestral,3=Anual',
  nombre VARCHAR(100) NOT NULL,
  monto DECIMAL(10,2) NOT NULL
);

CREATE TABLE estado_membresia (
  id_estado INT(11) NOT NULL PRIMARY KEY,
  nombre VARCHAR(100)
);

CREATE TABLE tipo_canal (
  id_tipo INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100)
);

CREATE TABLE tipo_notificacion (
  id_tipo INT(11) NOT NULL PRIMARY KEY,
  nombre VARCHAR(100)
);

-- ========== 2. TABLA PERSONA ==========
CREATE TABLE persona (
  cedula_persona VARCHAR(15) NOT NULL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  correo VARCHAR(100),
  telefono VARCHAR(20),
  direccion TEXT,
  fecha_nacimiento DATE,
  fecha_registro DATETIME,
  activo TINYINT(1) NOT NULL DEFAULT 1
);

-- ========== 3. TABLA MEMBRESIA ==========
CREATE TABLE membresia (
  id_membresia INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_tipo INT(11) NOT NULL,
  id_estado INT(11) NOT NULL DEFAULT 3,
  fecha_inicio DATE,
  fecha_fin DATE,
  FOREIGN KEY (id_tipo) REFERENCES tipo_membresia(id_tipo) ON UPDATE CASCADE,
  FOREIGN KEY (id_estado) REFERENCES estado_membresia(id_estado) ON UPDATE CASCADE
);

-- ========== 4. TABLA CLIENTE ==========
CREATE TABLE cliente (
  cedula_cliente VARCHAR(15) NOT NULL PRIMARY KEY,
  id_membresia INT(11) NOT NULL,
  FOREIGN KEY (cedula_cliente) REFERENCES persona(cedula_persona) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_membresia) REFERENCES membresia(id_membresia) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ========== 5. TABLA TRABAJADOR ==========
CREATE TABLE trabajador (
  cedula_trabajador VARCHAR(15) NOT NULL PRIMARY KEY,
  id_rol INT(11) NOT NULL,
  salario DECIMAL(10,2),
  fecha_contratacion DATE,
  FOREIGN KEY (cedula_trabajador) REFERENCES persona(cedula_persona) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_rol) REFERENCES tipo_rol(id_rol) ON UPDATE CASCADE
);

-- ========== 6. TABLA USUARIO ==========
CREATE TABLE usuario (
  id_usuario INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_rol INT(11) NOT NULL,
  cedula_persona VARCHAR(15) NOT NULL,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  ultimo_acceso DATETIME,
  FOREIGN KEY (cedula_persona) REFERENCES persona(cedula_persona) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_rol) REFERENCES tipo_rol(id_rol) ON UPDATE CASCADE
);

-- ========== 7. TABLA PAGO ==========
CREATE TABLE pago (
  id_pago INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_cliente VARCHAR(15) NOT NULL,
  monto DECIMAL(10,2) NOT NULL,
  metodo_pago VARCHAR(50),
  comprobante_url VARCHAR(255),
  estado ENUM('Pagado','Pendiente','Atrasado') DEFAULT 'Pagado',
  fecha_pago DATE NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON UPDATE CASCADE
);

-- ========== 8. TABLA NOTIFICACION ==========
CREATE TABLE notificacion (
  id_notificacion INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_tipo_notificacion INT(11) NOT NULL,
  id_tipo_canal INT(11) NOT NULL,
  cedula_cliente VARCHAR(15) NOT NULL,
  mensaje TEXT NOT NULL,
  estado ENUM('Pendiente','Enviado','Fallido') DEFAULT 'Pendiente',
  fecha_programada DATETIME,
  fecha_envio DATETIME,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON DELETE CASCADE,
  FOREIGN KEY (id_tipo_notificacion) REFERENCES tipo_notificacion(id_tipo),
  FOREIGN KEY (id_tipo_canal) REFERENCES tipo_canal(id_tipo)
);

-- ========== 9. TABLAS DE CLASES Y ASISTENCIA (resumidas) ==========
CREATE TABLE clase (
  id_clase INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_trabajador VARCHAR(15) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  cupos_ocupados INT(11) DEFAULT 0,
  capacidad_maxima INT(11) NOT NULL,
  estado ENUM('Programado','En curso','Finalizado','Cancelado') DEFAULT 'Programado',
  fecha_inicio DATETIME NOT NULL,
  fecha_fin DATETIME NOT NULL,
  FOREIGN KEY (cedula_trabajador) REFERENCES trabajador(cedula_trabajador) ON UPDATE CASCADE
);

CREATE TABLE asistencia_clase (
  id_asistencia INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_clase INT(11) NOT NULL,
  cedula_cliente VARCHAR(15) NOT NULL,
  asistio TINYINT(1) DEFAULT 1,
  fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_clase) REFERENCES clase(id_clase) ON DELETE CASCADE,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON UPDATE CASCADE
);

CREATE TABLE inscripcion_clase (
  id_inscripcion INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_clase INT(11) NOT NULL,
  cedula_cliente VARCHAR(15) NOT NULL,
  estado ENUM('Activo','Cancelado') DEFAULT 'Activo',
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uk_cliente_clase (cedula_cliente, id_clase),
  FOREIGN KEY (id_clase) REFERENCES clase(id_clase) ON DELETE CASCADE,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON UPDATE CASCADE
);

CREATE TABLE asistencia_gimnasio (
  id_asistencia INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_cliente VARCHAR(15) NOT NULL,
  fecha DATETIME NOT NULL,
  tipo ENUM('Entrada','Salida') NOT NULL,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON UPDATE CASCADE,
  KEY idx_asistencias_fecha (fecha)
);

-- ========== 10. OTRAS TABLAS (equipos, rutinas, productos, etc.) ==========
CREATE TABLE equipo (
  codigo_equipo VARCHAR(20) NOT NULL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  tipo VARCHAR(50),
  estado ENUM('Operativo','Mantenimiento','Fuera de Servicio') DEFAULT 'Operativo',
  ubicacion VARCHAR(100),
  activo TINYINT(1) DEFAULT 1
);

CREATE TABLE mantenimiento_equipo (
  id_mantenimiento INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  codigo_equipo VARCHAR(20) NOT NULL,
  fecha DATE NOT NULL,
  tipo ENUM('Preventivo','Correctivo') NOT NULL,
  descripcion TEXT,
  costo DECIMAL(10,2),
  tecnico VARCHAR(100),
  FOREIGN KEY (codigo_equipo) REFERENCES equipo(codigo_equipo) ON UPDATE CASCADE
);

CREATE TABLE rutina (
  id_rutina INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_dificultad INT(11) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  objetivo TEXT,
  duracion_semanas INT(11),
  FOREIGN KEY (id_dificultad) REFERENCES tipo_dificultad(id_dificultad) ON UPDATE CASCADE
);

CREATE TABLE ejercicio (
  id_ejercicio INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_dificultad INT(11),
  nombre VARCHAR(100),
  descripcion TEXT,
  grupo_muscular VARCHAR(100),
  equipo_requerido TEXT,
  FOREIGN KEY (id_dificultad) REFERENCES tipo_dificultad(id_dificultad) ON UPDATE CASCADE
);

CREATE TABLE rutina_asignada (
  id_asignacion INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_cliente VARCHAR(15) NOT NULL,
  id_rutina INT(11) NOT NULL,
  fecha_asignacion DATE NOT NULL,
  fecha_inicio DATE,
  fecha_fin DATE,
  estado ENUM('Activa','Completada','Cancelada') DEFAULT 'Activa',
  progreso DECIMAL(5,2) DEFAULT 0.00,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_rutina) REFERENCES rutina(id_rutina) ON DELETE CASCADE
);

CREATE TABLE seguimiento_fisico (
  id_seguimiento INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_cliente VARCHAR(15) NOT NULL,
  fecha DATE,
  altura_cm DECIMAL(5,2),
  peso_kg DECIMAL(5,2),
  cintura_cm DECIMAL(5,2),
  cadera_cm DECIMAL(5,2),
  pecho_cm DECIMAL(5,2),
  muslo_cm DECIMAL(5,2),
  hombros_cm DECIMAL(5,2),
  pantorrilla_cm DECIMAL(5,2),
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE seguimiento_nutricional (
  id_seguimiento INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_cliente VARCHAR(15) NOT NULL,
  fecha DATE,
  proteinas_g DECIMAL(5,2),
  carbohidratos_g DECIMAL(5,2),
  grasas_g DECIMAL(5,2),
  calorias_diarias DECIMAL(5,2),
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE producto (
  codigo_producto VARCHAR(20) NOT NULL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  categoria VARCHAR(50),
  precio_venta DECIMAL(10,2) NOT NULL,
  stock_minimo INT(11) DEFAULT 0,
  stock_actual INT(11) NOT NULL DEFAULT 0,
  unidad_medida VARCHAR(20) DEFAULT 'unidad',
  activo TINYINT(1) DEFAULT 1
);

CREATE TABLE venta_producto (
  id_venta INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  codigo_producto VARCHAR(20) NOT NULL,
  cedula_cliente VARCHAR(15),
  cantidad_vendida DECIMAL(10,2),
  monto_total VARCHAR(100),
  metodo_pago VARCHAR(50),
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (codigo_producto) REFERENCES producto(codigo_producto) ON UPDATE CASCADE,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON UPDATE CASCADE,
  KEY idx_ventas_fecha (fecha)
);

CREATE TABLE analisis_energetico (
  id_analisis INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cedula_cliente VARCHAR(15) NOT NULL,
  fecha DATE NOT NULL,
  calorias_consumidas INT(11),
  calorias_gastadas_estimadas INT(11),
  balance INT(11),
  diagnostico TEXT,
  recomendacion TEXT,
  FOREIGN KEY (cedula_cliente) REFERENCES cliente(cedula_cliente) ON UPDATE CASCADE
);

CREATE TABLE horario_trabajador (
  id_horario INT(11) NOT NULL PRIMARY KEY,
  cedula_trabajador VARCHAR(15) NOT NULL,
  dia_semana VARCHAR(15),
  hora_entrada TIME,
  hora_salida TIME,
  FOREIGN KEY (cedula_trabajador) REFERENCES trabajador(cedula_trabajador) ON UPDATE CASCADE
);

CREATE TABLE consulta_asistente (
  id_consulta INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT(11),
  cedula_cliente VARCHAR(15),
  fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
  tipo TEXT,
  pregunta TEXT,
  respuesta TEXT,
  FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
  KEY idx_consultas_fecha (fecha)
);

-- ======================================================
-- DATOS DE PRUEBA (incluyendo cliente moroso V-33333333)
-- ======================================================

-- Catálogos
INSERT INTO tipo_rol (id_rol, nombre) VALUES (1, 'Gerente'), (2, 'Entrenador'), (3, 'Recepcionista'), (4, 'Cliente');
INSERT INTO tipo_dificultad (nombre) VALUES ('Principiante'), ('Intermedio'), ('Avanzado');
INSERT INTO tipo_membresia (id_tipo, nombre, monto) VALUES (1, 'Mensual', 30.00), (2, 'Trimestral', 80.00), (3, 'Anual', 300.00);
INSERT INTO estado_membresia (id_estado, nombre) VALUES (1, 'Activo'), (2, 'Vencido'), (3, 'Moroso');
INSERT INTO tipo_canal (nombre) VALUES ('App'), ('Email'), ('WhatsApp');
INSERT INTO tipo_notificacion (id_tipo, nombre) VALUES (1, 'Pago vencimiento'), (2, 'Recordatorio clase'), (3, 'Promoción'), (4, 'Otro');

-- Personas (clientes y trabajadores)
INSERT INTO persona (cedula_persona, nombre, apellido, correo, telefono, activo) VALUES
('V-11111111', 'María', 'Torres', 'maria@example.com', '04121234567', 1),
('V-22222222', 'Luis', 'Martínez', 'luis@example.com', '04127654321', 1),
('V-33333333', 'Cliente', 'Moroso', 'moroso@test.com', '04129999999', 1),   -- NUEVO CLIENTE MOROSO
('T-00000001', 'Carlos', 'Pérez', 'carlos@sofit.com', NULL, 1),
('T-00000002', 'Ana', 'Gómez', 'ana@sofit.com', NULL, 1);

-- Membresías
INSERT INTO membresia (id_tipo, id_estado, fecha_inicio, fecha_fin) VALUES
(1, 1, '2026-05-01', '2026-05-31'),   -- Activa (María)
(2, 2, '2026-03-01', '2026-05-30'),   -- Vencida? pero 2026-05-30 es futuro, no vencida. La dejamos como referencia
(1, 2, '2026-04-01', '2026-04-30');   -- NUEVO: Membresía vencida para el moroso (fecha_fin anterior a hoy)

-- Clientes
INSERT INTO cliente (cedula_cliente, id_membresia) VALUES
('V-11111111', 1),
('V-22222222', 2),
('V-33333333', 3);   -- Asignamos la membresía vencida

-- Trabajadores
INSERT INTO trabajador (cedula_trabajador, id_rol) VALUES
('T-00000001', 1),
('T-00000002', 2);

-- Usuarios
INSERT INTO usuario (id_rol, cedula_persona, usuario, contrasena) VALUES
(2, 'T-00000001', 'carlos.perez', 'admin123'),
(2, 'T-00000002', 'ana.gomez', 'ana123'),
(4, 'V-11111111', 'luis.martinez', 'cliente123'),
(4, 'V-33333333', 'cliente.moroso', 'moroso123');   -- Usuario para el moroso

-- Pagos de ejemplo
INSERT INTO pago (cedula_cliente, monto, metodo_pago, estado, fecha_pago, fecha_vencimiento) VALUES
('V-11111111', 30.00, 'Efectivo', 'Pagado', '2026-05-01', '2026-05-31'),
('V-22222222', 80.00, 'Transferencia', 'Atrasado', '2026-03-01', '2026-05-30'),
('V-33333333', 30.00, 'Efectivo', 'Atrasado', '2026-04-01', '2026-04-30');   -- Pago atrasado con vencimiento pasado

-- Producto y venta de ejemplo
INSERT INTO producto (codigo_producto, nombre, precio_venta, stock_actual) VALUES
('PROT001', 'Proteína Whey', 45.00, 15);
INSERT INTO venta_producto (codigo_producto, cedula_cliente, cantidad_vendida, metodo_pago, fecha) VALUES
('PROT001', 'V-11111111', 45.00, 'Efectivo', '2026-04-26 02:55:55');

-- Clase de ejemplo
INSERT INTO clase (cedula_trabajador, nombre, capacidad_maxima, fecha_inicio, fecha_fin) VALUES
('T-00000002', 'Yoga', 20, '2026-04-26 10:00:00', '2026-04-26 11:00:00');

-- Inscripción a clase
INSERT INTO inscripcion_clase (id_clase, cedula_cliente, fecha) VALUES
(1, 'V-11111111', '2026-04-26 20:03:02');

-- Equipo y mantenimiento
INSERT INTO equipo (codigo_equipo, nombre, tipo) VALUES ('EQ-001', 'Cinta de correr', 'Cardio');
INSERT INTO mantenimiento_equipo (codigo_equipo, fecha, tipo, descripcion) VALUES
('EQ-001', '2026-03-15', 'Preventivo', 'Lubricación y calibración');

-- Rutina de ejemplo
INSERT INTO rutina (id_dificultad, nombre) VALUES (1, 'Fuerza Básica');