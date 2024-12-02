
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `if0_37785517_proyecto_final`
--


--
-- Estructura de tabla para la tabla `alojamientos`
--

CREATE TABLE `alojamientos` (
  `idAlojamiento` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `latitud` varchar(100) NOT NULL,
  `longitud` varchar(100) NOT NULL,
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idAlojamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=4;



--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `codPais` varchar(5) NOT NULL,
  `codArea` varchar(5) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `dni` varchar(10) NOT NULL,
  `sexo` varchar(10) NOT NULL,
  `fechaDeNacimiento` date NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idCliente`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=4;


--
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `idConsulta` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idUsuario` int(5) UNSIGNED NOT NULL,
  `idCliente` int(5) UNSIGNED NOT NULL,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `idPaqueteFechaSalida` int(5) UNSIGNED NOT NULL,
  `idOrigen` int(5) UNSIGNED NOT NULL,
  `idAlojamiento` int(5) UNSIGNED NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `total` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Total de la venta',
  `traslado` TINYINT(1) NOT NULL DEFAULT 0,
  `estado` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Abierto | C=Cerrado | V=Vendido',
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idConsulta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=17;



--
-- Estructura de tabla para la tabla `consulta_contacto_adicional`
--

CREATE TABLE `consulta_contacto_adicional` (
  `idContactoAdicional` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `contacto` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idContactoAdicional`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=24;



--
-- Estructura de tabla para la tabla `consulta_mensajes`
--

CREATE TABLE `consulta_mensajes` (
  `idMensaje` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `idUsuarioMensaje` int(5) UNSIGNED NOT NULL COMMENT 'Puede ser el cliente, usuario del sistema o el sistema',
  `mensaje` text NOT NULL,
  `tipo` char(5) NOT NULL COMMENT 'C=Cliente | U=Usuario | S=sitema',
  `isNotaInterna` TINYINT(1) NOT NULL DEFAULT 0,
  `typeMessageSistem` char(1) NOT NULL DEFAULT 'N' COMMENT 'Solo para mensajes del sistema\r\nN=normal | I=info | W=warning | D=danger',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idMensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=27;


--
-- Estructura de tabla para la tabla `consulta_pasajeros`
--

CREATE TABLE `consulta_pasajeros` (
  `idPasajero` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `apellido` varchar(150) NOT NULL,
  `sexo` char(1) NOT NULL,
  `fechaDeNacimiento` date NOT NULL,
  `observaciones` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idPasajero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=21;



--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `idEvento` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idUsuario` int(5) UNSIGNED NOT NULL,
  `fechaInicio` datetime NOT NULL,
  `fechaFin` datetime NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(50) NOT NULL COMMENT 'Por si en algún momento necesito darle estados o etiquetas',
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idEvento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=11;


--
-- Estructura de tabla para la tabla `origenes`
--

CREATE TABLE `origenes` (
  `idOrigen` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idOrigen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=8;



--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `shortName1` char(2) DEFAULT NULL COMMENT 'iso3166a1',
  `shortName2` char(3) DEFAULT NULL COMMENT 'iso3166a2',
  `nombre` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci AUTO_INCREMENT=241;


--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `idPaquete` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idProvincia` int(5) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `subtitulo` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `noches` int(2) UNSIGNED NOT NULL,
  `capacidad` int(5) UNSIGNED NOT NULL,
  `pension` varchar(255) NOT NULL,
  `imagen` varchar(100) NOT NULL COMMENT 'imagen principal',
  `banner` varchar(100) NOT NULL,
  `horaSalida` time NOT NULL,
  `horaLlegada` time NOT NULL,
  `fechaInicioPublicacion` date NOT NULL,
  `fechaFinPublicacion` date NOT NULL,
  `precio` varchar(20) NOT NULL,
  `traslado` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Indica si el transporte pasa a buscar al cliente',
  `tipo` char(1) NOT NULL DEFAULT 'E' COMMENT 'E=Excursiones | P=Paquetes',
  `descripcion` longtext DEFAULT NULL,
  `itinerario` longtext DEFAULT NULL,
  `equipo` longtext DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idPaquete`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=23;

--
-- Estructura de tabla para la tabla `paquetes_fechas_salida`
--

CREATE TABLE `paquetes_fechas_salida` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `hasRecorrido` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Es para indicar si ya tiene una salida cargada',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=58;


--
-- Estructura de tabla para la tabla `paquetes_galeria`
--

CREATE TABLE `paquetes_galeria` (
  `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `path` varchar(100) NOT NULL COMMENT 'nombre del archivo',
  `orden` int(2) NOT NULL DEFAULT 1000,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=21;

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `idProvincia` int(2) UNSIGNED NOT NULL  AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `capital` varchar(100) NOT NULL,
  `IATA` varchar(20) NOT NULL,
  PRIMARY KEY (`idProvincia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=25;



--
-- Estructura de tabla para la tabla `recorridos`
--

CREATE TABLE `recorridos` (
  `idRecorrido` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `idUsuario` int(11) NOT NULL COMMENT 'guía',
  `fecha` date NOT NULL,
  `total` varchar(20) NOT NULL DEFAULT '0',
  `pasajeros` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `totalAlojamientoConsulta` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `created_by_idUsuario` int(5) UNSIGNED NOT NULL COMMENT 'Usuario que creo el recorrido',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idRecorrido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=7;


--
-- Estructura de tabla para la tabla `recorrido_mensajes`
--

CREATE TABLE `recorrido_mensajes` (
  `idMensaje` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idRecorrido` int(5) UNSIGNED NOT NULL,
  `idUsuario` int(5) UNSIGNED NOT NULL COMMENT 'Puede ser el cliente, usuario del sistema o el sistema',
  `mensaje` text NOT NULL,
  `tipo` char(5) NOT NULL COMMENT 'C=Cliente | U=Usuario',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idMensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=8;


--
-- Estructura de tabla para la tabla `recorrido_tramos`
--

CREATE TABLE `recorrido_tramos` (
  `idRecorridoTramo` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idRecorrido` int(5) UNSIGNED NOT NULL,
  `idAlojamiento` int(5) UNSIGNED NOT NULL,
  `pax` varchar(10) NOT NULL,
  `orden` int(2) UNSIGNED NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'P' COMMENT 'P=Pendiente | M=Tramo marcado',
  `tipo` char(1) NOT NULL DEFAULT 'O' COMMENT 'O=origen | D=destino | P=parada',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idRecorridoTramo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=28;


--
-- Estructura de tabla para la tabla `recorrido_tramo_pasajeros`
--

CREATE TABLE `recorrido_tramo_pasajeros` (
  `idRecorridoTramoPasajero` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idRecorrido` int(5) UNSIGNED NOT NULL,
  `idRecorridoTramo` int(5) UNSIGNED NOT NULL,
  `idConsultaPasajero` int(5) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idRecorridoTramoPasajero`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=31;



--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `codPais` char(5) NOT NULL,
  `codArea` char(5) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `tipo` int(2) UNSIGNED NOT NULL COMMENT '0=admin | 1=vendedor | 2=guia',
  `nacionalidad` varchar(100) DEFAULT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `sexo` varchar(20) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=activo | I=inactivo',
  `eliminado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci AUTO_INCREMENT=7;




--
-- Eliminación de registros de las tablas
--

DELETE FROM alojamientos;
DELETE FROM clientes;
DELETE FROM consultas;
DELETE FROM consulta_contacto_adicional;
DELETE FROM consulta_mensajes;
DELETE FROM consulta_pasajeros;
DELETE FROM eventos;
DELETE FROM origenes;
DELETE FROM paises;
DELETE FROM paquetes;
DELETE FROM paquetes_fechas_salida;
DELETE FROM paquetes_galeria;
DELETE FROM provincias;
DELETE FROM recorridos;
DELETE FROM recorrido_mensajes;
DELETE FROM recorrido_tramos;
DELETE FROM recorrido_tramo_pasajeros;
DELETE FROM usuarios;




--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `email`, `password`, `codPais`, `codArea`, `telefono`, `tipo`, `nacionalidad`, `dni`, `sexo`, `fechaNacimiento`, `descripcion`, `estado`, `eliminado`, `created_at`) VALUES
(1, 'Admin', 'turApp', 'admin@turapp.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '54', '11', '66255340', 0, 'argentina', '40393222', 'masculino', '1997-04-21', '&lt;p&gt;&lt;span class=&quot;ql-size-large&quot;&gt;Testing 1&lt;/span&gt;&lt;/p&gt;&lt;p&gt;Prueba de &lt;strong style=&quot;color: rgb(255, 153, 0);&quot;&gt;textarea editable&lt;/strong&gt;&lt;/p&gt;&lt;ul&gt;&lt;li&gt;&lt;span style=&quot;background-color: rgb(255, 255, 0);&quot;&gt;asdD&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;em&gt;hsdh&lt;/em&gt;&lt;/li&gt;&lt;li&gt;&lt;u&gt;sdh&lt;/u&gt;&lt;/li&gt;&lt;li&gt;&lt;s&gt;sdh&lt;/s&gt;&lt;/li&gt;&lt;/ul&gt;&lt;p class=&quot;ql-align-right&quot;&gt;09/10/2024 | 12:20hs&lt;/p&gt;', 'A', 0, '2024-09-30 15:38:51'),
(2, 'rick', 'sanchez', 'rick@turapp.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '54', '11', '12312312', 0, 'Argentina', '34786534', 'masculino', '1985-04-04', '&lt;p&gt;&lt;/p&gt;', 'A', 0, '2024-10-03 19:31:29'),
(3, 'morty', 'sanchez', 'morty@turapp.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '54', '11', '23423423', 1, NULL, NULL, NULL, NULL, NULL, 'A', 0, '2024-10-03 19:36:20'),
(6, 'otto', 'mann', 'otto@turapp.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '51', '22', '12312312', 2, 'Argentina', '34754097', 'masculino', '1981-07-28', '&lt;p&gt;🎸 &lt;strong&gt;Otto Mann&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;🚍 &lt;strong&gt;Chofer del autob&uacute;s escolar en Springfield&lt;/strong&gt;&lt;/p&gt;&lt;p&gt;🎶 Amante del rock pesado y la vida relajada, Otto es un esp&iacute;ritu libre que vive al ritmo de su m&uacute;sica favorita. Con su larga melena negra, gorra rosada y auriculares siempre a mano, combina su estilo despreocupado con una conducci&oacute;n... digamos, &lt;em&gt;arriesgada&lt;/em&gt;. Aunque no es el m&aacute;s responsable, su actitud &amp;quot;cool&amp;quot; lo hace un favorito entre los ni&ntilde;os. 🤘&lt;/p&gt;&lt;p&gt;👉 &lt;strong&gt;Cita favorita:&lt;/strong&gt; &amp;quot;&iexcl;El autob&uacute;s no se maneja solo... bueno, a veces s&iacute;!&amp;quot;&lt;/p&gt;', 'A', 0, '2024-11-13 10:21:36');




--
-- Volcado de datos para la tabla `alojamientos`
--

INSERT INTO `alojamientos` (`idAlojamiento`, `nombre`, `direccion`, `descripcion`, `latitud`, `longitud`, `eliminado`, `created_at`) VALUES
(1, 'Sheraton Hotel & Convention Center', 'San Martín 1225, Retiro, Buenos Aires, C1001, Argentina', 'hotel de 5 estrellas', '-34.59339177324998', '-58.37265998125076', 0, '2024-10-28 15:25:54'),
(2, 'Hotel Ramada', 'Ramada Hotel, San Martín 450, Vicente Lopez, Buenos Aires Province B1638, Argentina', 'Hotel de 3 estrellas con canchas de tenis y cercanía a la costa', '-34.519216286029526', '-58.47304433584213', 0, '2024-11-04 15:54:27'),
(3, 'Four Seasons - Buenos aires', 'The Cielo Spa, Four Seasons, Buenos Aires, C1010, Argentina', 'Hotel de 5 estreñas', '-34.59060743930146', '-58.38261902332306', 0, '2024-11-26 07:34:05');

-- --------------------------------------------------------



--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`idCliente`, `nombre`, `apellido`, `email`, `password`, `codPais`, `codArea`, `telefono`, `nacionalidad`, `dni`, `sexo`, `fechaDeNacimiento`, `estado`, `eliminado`, `created_at`) VALUES
(1, 'saul', 'zarate', 'saul@gmail.com', '3387d29ee8f82f96f50c39bbe114a0e80a76ff17', '54', '11', '66255340', '13', '40393222', 'M', '1997-04-21', 'A', 0, '2024-10-21 14:41:57'),
(2, 'pepe', 'argento', 'pepe.argento@gmail.com', '642b544364bc5b4cf76b39987531262077d8938a', '54', '11', '12312312', '13', '40123123', 'M', '2002-11-01', 'A', 0, '2024-11-04 14:20:34'),
(3, 'sofia', 'martinez', 'sofi.martinez@gmail.com', '467146a1d312071738ef5866c3f881973a82b9cc', '54', '11', '63136798', '13', '36281492', 'F', '1991-02-05', 'A', 0, '2024-11-13 15:54:40');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `consultas`
--

INSERT INTO `consultas` (`idConsulta`, `idUsuario`, `idCliente`, `idPaquete`, `idPaqueteFechaSalida`, `idOrigen`, `idAlojamiento`, `asunto`, `total`, `traslado`, `estado`, `eliminado`, `updated_at`, `created_at`) VALUES
(9, 3, 1, 13, 8, 3, 1, 'testing 1234', '840000', 1, 'V', 0, '2024-11-08 14:54:26', '2024-10-31 20:03:29'),
(11, 2, 2, 17, 5, 5, 2, 'Vacaciones de verano', '0', 1, 'C', 0, '2024-11-13 16:12:40', '2024-11-04 15:55:41'),
(12, 3, 2, 13, 8, 6, 2, 'Testing 234', '840000', 1, 'V', 0, '2024-11-12 19:35:00', '2024-11-12 19:29:56'),
(15, 3, 3, 13, 8, 6, 0, 'Consulta por córdoba', '420000', 0, 'V', 0, '2024-11-13 16:04:52', '2024-11-13 16:02:22'),
(16, 2, 2, 17, 5, 6, 0, 'Consulta por carlos paz', '0', 0, 'A', 0, '2024-11-13 16:14:17', '2024-11-13 16:14:17');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `consulta_contacto_adicional`
--

INSERT INTO `consulta_contacto_adicional` (`idContactoAdicional`, `idConsulta`, `descripcion`, `contacto`, `created_at`) VALUES
(11, 9, 'ig', '@test', '2024-10-31 20:03:29'),
(12, 9, 'face', '@test2', '2024-10-31 20:03:29'),
(14, 11, 'instagram', '@test', '2024-10-31 20:03:29'),
(18, 11, 'linkedin', 'linkedin.com/in/test-1234', '2024-11-05 15:40:45'),
(23, 15, 'ig', '@sofi.martinez.ft', '2024-11-13 16:02:22');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `consulta_mensajes`
--

INSERT INTO `consulta_mensajes` (`idMensaje`, `idConsulta`, `idUsuarioMensaje`, `mensaje`, `tipo`, `isNotaInterna`, `typeMessageSistem`, `created_at`) VALUES
(1, 9, 3, '<p>Primer mensaje de la consulta</p>', 'U', 0, 'N', '2024-11-06 14:12:20'),
(2, 9, 2, '<p>Segundo mensaje de la consulta</p>', 'U', 1, 'N', '2024-11-06 14:13:10'),
(3, 9, 1, '<p>Respuesta de cliente</p>', 'C', 0, 'N', '2024-11-06 14:15:40'),
(4, 9, 0, 'Se agregaron fechas de salidas al paquete', 'S', 0, 'N', '2024-11-06 14:30:15'),
(5, 9, 1, '<p>Hay cupos para 5 personas ?</p>', 'C', 0, 'N', '2024-11-06 14:50:55'),
(6, 9, 1, '<p>Si, tenemos disponibilidad para 5 personas en la fecha del 20/01/2025</p><p></p><p>Te recuerdo que tenes que abonar el 10% del total como valor de la seña</p><p></p><p>Saludos</p>', 'U', 0, 'N', '2024-11-06 14:57:07'),
(7, 9, 1, '<p><span class=\"ql-size-huge\">test</span></p><p>Prueba de <u>mensaje con estilos</u></p><ol><li>asd</li><li>asg</li><li>sag</li></ol><p><s>asdasdasd</s></p><p></p><p><u>20/03/2025</u></p>', 'U', 0, 'N', '2024-11-07 10:58:57'),
(8, 9, 1, '<p>test link </p><p><a href=\"https://google.com\" rel=\"noopener noreferrer\" target=\"_blank\">google.com</a></p>', 'U', 1, 'N', '2024-11-07 11:02:07'),
(12, 9, 0, '\r\n                                <p class=\"m-0\">Venta realizada por: Admin TurApp (admin)</p>\r\n                                <h4 class=\"display-5 fs-3 m-0\">$840.000<small class=\"text-secondary fs-5\">/total</small></h4>\r\n                            ', 'S', 0, 'N', '2024-11-08 14:54:27'),
(13, 12, 1, '<p>Consulta de prueba</p>', 'U', 0, 'N', '2024-11-12 19:31:52'),
(14, 12, 1, '<p>Agregué a juan y fernanda como pasajeros</p>', 'U', 1, 'N', '2024-11-12 19:33:09'),
(15, 12, 0, '\r\n                                <p class=\"m-0\">Venta realizada por: Admin TurApp (admin)</p>\r\n                                <h4 class=\"display-5 fs-3 m-0\">$840.000<small class=\"text-secondary fs-5\">/total</small></h4>\r\n                            ', 'S', 0, 'N', '2024-11-12 19:35:01'),
(16, 15, 1, '<p>Viaja sola con el perro</p>', 'U', 1, 'N', '2024-11-13 16:04:46'),
(17, 15, 0, '\r\n                                <p class=\"m-0\">Venta realizada por: Admin TurApp (admin)</p>\r\n                                <h4 class=\"display-5 fs-3 m-0\">$420.000<small class=\"text-secondary fs-5\">/total</small></h4>\r\n                            ', 'S', 0, 'N', '2024-11-13 16:04:53'),
(18, 11, 1, '<p>Cierro la consulta por inactividad</p>', 'U', 1, 'N', '2024-11-13 16:12:35'),
(19, 11, 0, '<p class=\"m-0\">Consulta cerrada por el usuario: Admin TurApp (admin)</p>', 'S', 0, 'N', '2024-11-13 16:12:41'),
(20, 16, 2, '<p>Cual es el costo para 2 personas?</p><p>Hay alguna promoción si voy en familia (4 personas) ?</p><p></p><p>Saludos</p>', 'C', 0, 'N', '2024-11-18 17:08:33'),
(21, 16, 1, '<p>Hola Pepe,</p><p>El costo por persona es de $490.000</p><p>No hacemos descuentos por cantidad de personas, el precio siempre es el mismo</p><p></p><p></p><p>Saludos</p>', 'U', 0, 'N', '2024-11-18 17:10:11'),
(22, 16, 2, '<p>Perfecto. </p><p>Puedo llevar una mascota ? </p>', 'C', 0, 'N', '2024-11-18 17:15:49'),
(23, 16, 1, '<p>No permitimos mascotas</p>', 'U', 0, 'N', '2024-11-18 17:18:17'),
(24, 16, 2, '<p>Hola Pepe</p><p></p><p>Solo permitimos mascotas de menos de 10kg</p><p></p><p>Saludos</p>', 'U', 0, 'N', '2024-11-18 17:20:34'),
(25, 16, 2, '<p>Hola Rick</p><p>Gracias por responder, mi perrito pesa 15kg, no lo voy a poder llevar 😥</p><p></p><p>De todas formas, muchas gracias por avisarme!</p>', 'C', 0, 'N', '2024-11-18 17:21:40'),
(26, 16, 1, '<p>Por nada!</p><p></p><p>Me mantengo al tanto por si tenes otra consulta/duda</p><p></p><p>Saludos</p>', 'U', 0, 'N', '2024-11-29 14:41:58');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `consulta_pasajeros`
--

INSERT INTO `consulta_pasajeros` (`idPasajero`, `idConsulta`, `nombre`, `apellido`, `sexo`, `fechaDeNacimiento`, `observaciones`, `created_at`) VALUES
(11, 9, 'rick', 'sanchez', 'M', '2010-10-15', 'Observaciones rick', '2024-10-31 20:03:29'),
(12, 9, 'morty', 'sanchez', 'M', '1999-12-01', '', '2024-10-31 20:03:29'),
(17, 12, 'juan', 'perez', 'M', '2001-12-12', '', '2024-11-12 19:32:15'),
(18, 12, 'fernanda', 'sanchez', 'F', '2005-03-04', '', '2024-11-12 19:32:42'),
(19, 15, 'sofia', 'martinez', 'F', '1991-01-11', '', '2024-11-13 16:02:22'),
(20, 16, 'pepe', 'argento', 'M', '2002-03-21', '', '2024-11-13 16:14:17');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`idEvento`, `idUsuario`, `fechaInicio`, `fechaFin`, `titulo`, `descripcion`, `tipo`, `eliminado`, `created_at`) VALUES
(1, 1, '2024-10-23 10:00:00', '2024-10-23 12:00:00', 'Evento 1', '<h1>Comentario de prueba</h1>\r\n<p>Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. No sólo sobrevivió 500 años, sino que tambien ingresó como texto de relleno en documentos electrónicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>', '', 0, '2024-10-22 14:00:34'),
(2, 1, '2024-10-23 14:00:00', '2024-10-23 16:00:00', 'Evento 2', '<h1>Reunión con Rick Sanchez</h1>\r\n<p>Fue popularizado en los 60s con la creación de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y más recientemente con software de autoedición, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.</p>', '', 0, '2024-10-22 14:03:54'),
(4, 1, '2024-10-24 09:00:00', '2024-10-25 10:00:00', 'Primer evento', '<p><span class=\"ql-size-large\">Testing de nuevo evento</span></p><p>Prueba de la creación de un nuevo evento.</p>', '', 0, '2024-10-22 22:03:13'),
(5, 1, '2024-10-25 10:30:00', '2024-10-25 11:30:00', 'prueba 2', '<p>fdghyflhgñhuj</p>', '', 0, '2024-10-23 14:15:49'),
(6, 3, '2024-10-25 16:00:00', '2024-10-25 18:00:00', 'prueba 3', '<p>hjfdsjdfj</p>', '', 0, '2024-10-23 14:15:49'),
(8, 1, '2024-11-18 09:00:00', '2024-11-18 09:30:00', 'Reunión con turismocity (B2B)', '<p>Temas</p><ul><li>agencias de turismo interesadas</li><li>agencias del tipo B2B interesadas</li><li>beneficios del convenio</li></ul><p></p><p>Próxima reunión</p>', '', 0, '2024-11-13 09:58:32'),
(9, 1, '2024-11-21 13:00:00', '2024-11-21 14:00:00', 'Prueba de producción', '<p>Prueba 1234</p>', '', 0, '2024-11-26 07:49:46'),
(10, 1, '2024-11-16 19:00:00', '2024-11-16 19:35:00', 'Prueba 1234', '<p>Prueba desde el celular</p>', '', 0, '2024-11-29 14:36:21');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `origenes`
--

INSERT INTO `origenes` (`idOrigen`, `nombre`, `estado`, `eliminado`, `created_at`) VALUES
(3, 'Facebook | Campaña de vacaciones de verano - anticipadas', 'A', 0, '2024-10-29 11:44:57'),
(4, 'Sitio web', 'A', 0, '2024-11-04 15:50:31'),
(5, 'Instagram | Campaña 1 - Preventa 1', 'A', 0, '2024-11-04 15:52:47'),
(6, 'Panel administrativo', 'A', 0, '2024-11-12 19:28:44'),
(7, 'google adds | campaña #112', 'A', 0, '2024-11-26 07:32:24');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `paises`
--

INSERT INTO `paises` (`id`, `shortName1`, `shortName2`, `nombre`) VALUES
(1, 'AF', 'AFG', 'Afganistán'),
(2, 'AX', 'ALA', 'Islas Gland'),
(3, 'AL', 'ALB', 'Albania'),
(4, 'DE', 'DEU', 'Alemania'),
(5, 'AD', 'AND', 'Andorra'),
(6, 'AO', 'AGO', 'Angola'),
(7, 'AI', 'AIA', 'Anguilla'),
(8, 'AQ', 'ATA', 'Antártida'),
(9, 'AG', 'ATG', 'Antigua y Barbuda'),
(10, 'AN', 'ANT', 'Antillas Holandesas'),
(11, 'SA', 'SAU', 'Arabia Saudí'),
(12, 'DZ', 'DZA', 'Argelia'),
(13, 'AR', 'ARG', 'Argentina'),
(14, 'AM', 'ARM', 'Armenia'),
(15, 'AW', 'ABW', 'Aruba'),
(16, 'AU', 'AUS', 'Australia'),
(17, 'AT', 'AUT', 'Austria'),
(18, 'AZ', 'AZE', 'Azerbaiyán'),
(19, 'BS', 'BHS', 'Bahamas'),
(20, 'BH', 'BHR', 'Bahréin'),
(21, 'BD', 'BGD', 'Bangladesh'),
(22, 'BB', 'BRB', 'Barbados'),
(23, 'BY', 'BLR', 'Bielorrusia'),
(24, 'BE', 'BEL', 'Bélgica'),
(25, 'BZ', 'BLZ', 'Belice'),
(26, 'BJ', 'BEN', 'Benin'),
(27, 'BM', 'BMU', 'Bermudas'),
(28, 'BT', 'BTN', 'Bhután'),
(29, 'BO', 'BOL', 'Bolivia'),
(30, 'BA', 'BIH', 'Bosnia y Herzegovina'),
(31, 'BW', 'BWA', 'Botsuana'),
(32, 'BV', 'BVT', 'Isla Bouvet'),
(33, 'BR', 'BRA', 'Brasil'),
(34, 'BN', 'BRN', 'Brunéi'),
(35, 'BG', 'BGR', 'Bulgaria'),
(36, 'BF', 'BFA', 'Burkina Faso'),
(37, 'BI', 'BDI', 'Burundi'),
(38, 'CV', 'CPV', 'Cabo Verde'),
(39, 'KY', 'CYM', 'Islas Caimán'),
(40, 'KH', 'KHM', 'Camboya'),
(41, 'CM', 'CMR', 'Camerún'),
(42, 'CA', 'CAN', 'Canadá'),
(43, 'CF', 'CAF', 'República Centroafricana'),
(44, 'TD', 'TCD', 'Chad'),
(45, 'CZ', 'CZE', 'República Checa'),
(46, 'CL', 'CHL', 'Chile'),
(47, 'CN', 'CHN', 'China'),
(48, 'CY', 'CYP', 'Chipre'),
(49, 'CX', 'CXR', 'Isla de Navidad'),
(50, 'VA', 'VAT', 'Ciudad del Vaticano'),
(51, 'CC', 'CCK', 'Islas Cocos'),
(52, 'CO', 'COL', 'Colombia'),
(53, 'KM', 'COM', 'Comoras'),
(54, 'CD', 'COD', 'República Democrática del Congo'),
(55, 'CG', 'COG', 'Congo'),
(56, 'CK', 'COK', 'Islas Cook'),
(57, 'KP', 'PRK', 'Corea del Norte'),
(58, 'KR', 'KOR', 'Corea del Sur'),
(59, 'CI', 'CIV', 'Costa de Marfil'),
(60, 'CR', 'CRI', 'Costa Rica'),
(61, 'HR', 'HRV', 'Croacia'),
(62, 'CU', 'CUB', 'Cuba'),
(63, 'DK', 'DNK', 'Dinamarca'),
(64, 'DM', 'DMA', 'Dominica'),
(65, 'DO', 'DOM', 'República Dominicana'),
(66, 'EC', 'ECU', 'Ecuador'),
(67, 'EG', 'EGY', 'Egipto'),
(68, 'SV', 'SLV', 'El Salvador'),
(69, 'AE', 'ARE', 'Emiratos Árabes Unidos'),
(70, 'ER', 'ERI', 'Eritrea'),
(71, 'SK', 'SVK', 'Eslovaquia'),
(72, 'SI', 'SVN', 'Eslovenia'),
(73, 'ES', 'ESP', 'España'),
(74, 'UM', 'UMI', 'Islas ultramarinas de Estados Unidos'),
(75, 'US', 'USA', 'Estados Unidos'),
(76, 'EE', 'EST', 'Estonia'),
(77, 'ET', 'ETH', 'Etiopía'),
(78, 'FO', 'FRO', 'Islas Feroe'),
(79, 'PH', 'PHL', 'Filipinas'),
(80, 'FI', 'FIN', 'Finlandia'),
(81, 'FJ', 'FJI', 'Fiyi'),
(82, 'FR', 'FRA', 'Francia'),
(83, 'GA', 'GAB', 'Gabón'),
(84, 'GM', 'GMB', 'Gambia'),
(85, 'GE', 'GEO', 'Georgia'),
(86, 'GS', 'SGS', 'Islas Georgias del Sur y Sandwich del Sur'),
(87, 'GH', 'GHA', 'Ghana'),
(88, 'GI', 'GIB', 'Gibraltar'),
(89, 'GD', 'GRD', 'Granada'),
(90, 'GR', 'GRC', 'Grecia'),
(91, 'GL', 'GRL', 'Groenlandia'),
(92, 'GP', 'GLP', 'Guadalupe'),
(93, 'GU', 'GUM', 'Guam'),
(94, 'GT', 'GTM', 'Guatemala'),
(95, 'GF', 'GUF', 'Guayana Francesa'),
(96, 'GN', 'GIN', 'Guinea'),
(97, 'GQ', 'GNQ', 'Guinea Ecuatorial'),
(98, 'GW', 'GNB', 'Guinea-Bissau'),
(99, 'GY', 'GUY', 'Guyana'),
(100, 'HT', 'HTI', 'Haití'),
(101, 'HM', 'HMD', 'Islas Heard y McDonald'),
(102, 'HN', 'HND', 'Honduras'),
(103, 'HK', 'HKG', 'Hong Kong'),
(104, 'HU', 'HUN', 'Hungría'),
(105, 'IN', 'IND', 'India'),
(106, 'ID', 'IDN', 'Indonesia'),
(107, 'IR', 'IRN', 'Irán'),
(108, 'IQ', 'IRQ', 'Iraq'),
(109, 'IE', 'IRL', 'Irlanda'),
(110, 'IS', 'ISL', 'Islandia'),
(111, 'IL', 'ISR', 'Israel'),
(112, 'IT', 'ITA', 'Italia'),
(113, 'JM', 'JAM', 'Jamaica'),
(114, 'JP', 'JPN', 'Japón'),
(115, 'JO', 'JOR', 'Jordania'),
(116, 'KZ', 'KAZ', 'Kazajstán'),
(117, 'KE', 'KEN', 'Kenia'),
(118, 'KG', 'KGZ', 'Kirguistán'),
(119, 'KI', 'KIR', 'Kiribati'),
(120, 'KW', 'KWT', 'Kuwait'),
(121, 'LA', 'LAO', 'Laos'),
(122, 'LS', 'LSO', 'Lesotho'),
(123, 'LV', 'LVA', 'Letonia'),
(124, 'LB', 'LBN', 'Líbano'),
(125, 'LR', 'LBR', 'Liberia'),
(126, 'LY', 'LBY', 'Libia'),
(127, 'LI', 'LIE', 'Liechtenstein'),
(128, 'LT', 'LTU', 'Lituania'),
(129, 'LU', 'LUX', 'Luxemburgo'),
(130, 'MO', 'MAC', 'Macao'),
(131, 'MK', 'MKD', 'ARY Macedonia'),
(132, 'MG', 'MDG', 'Madagascar'),
(133, 'MY', 'MYS', 'Malasia'),
(134, 'MW', 'MWI', 'Malawi'),
(135, 'MV', 'MDV', 'Maldivas'),
(136, 'ML', 'MLI', 'Malí'),
(137, 'MT', 'MLT', 'Malta'),
(138, 'FK', 'FLK', 'Islas Malvinas'),
(139, 'MP', 'MNP', 'Islas Marianas del Norte'),
(140, 'MA', 'MAR', 'Marruecos'),
(141, 'MH', 'MHL', 'Islas Marshall'),
(142, 'MQ', 'MTQ', 'Martinica'),
(143, 'MU', 'MUS', 'Mauricio'),
(144, 'MR', 'MRT', 'Mauritania'),
(145, 'YT', 'MYT', 'Mayotte'),
(146, 'MX', 'MEX', 'México'),
(147, 'FM', 'FSM', 'Micronesia'),
(148, 'MD', 'MDA', 'Moldavia'),
(149, 'MC', 'MCO', 'Mónaco'),
(150, 'MN', 'MNG', 'Mongolia'),
(151, 'MS', 'MSR', 'Montserrat'),
(152, 'MZ', 'MOZ', 'Mozambique'),
(153, 'MM', 'MMR', 'Myanmar'),
(154, 'NA', 'NAM', 'Namibia'),
(155, 'NR', 'NRU', 'Nauru'),
(156, 'NP', 'NPL', 'Nepal'),
(157, 'NI', 'NIC', 'Nicaragua'),
(158, 'NE', 'NER', 'Níger'),
(159, 'NG', 'NGA', 'Nigeria'),
(160, 'NU', 'NIU', 'Niue'),
(161, 'NF', 'NFK', 'Isla Norfolk'),
(162, 'NO', 'NOR', 'Noruega'),
(163, 'NC', 'NCL', 'Nueva Caledonia'),
(164, 'NZ', 'NZL', 'Nueva Zelanda'),
(165, 'OM', 'OMN', 'Omán'),
(166, 'NL', 'NLD', 'Países Bajos'),
(167, 'PK', 'PAK', 'Pakistán'),
(168, 'PW', 'PLW', 'Palau'),
(169, 'PS', 'PSE', 'Palestina'),
(170, 'PA', 'PAN', 'Panamá'),
(171, 'PG', 'PNG', 'Papúa Nueva Guinea'),
(172, 'PY', 'PRY', 'Paraguay'),
(173, 'PE', 'PER', 'Perú'),
(174, 'PN', 'PCN', 'Islas Pitcairn'),
(175, 'PF', 'PYF', 'Polinesia Francesa'),
(176, 'PL', 'POL', 'Polonia'),
(177, 'PT', 'PRT', 'Portugal'),
(178, 'PR', 'PRI', 'Puerto Rico'),
(179, 'QA', 'QAT', 'Qatar'),
(180, 'GB', 'GBR', 'Reino Unido'),
(181, 'RE', 'REU', 'Reunión'),
(182, 'RW', 'RWA', 'Ruanda'),
(183, 'RO', 'ROU', 'Rumania'),
(184, 'RU', 'RUS', 'Rusia'),
(185, 'EH', 'ESH', 'Sahara Occidental'),
(186, 'SB', 'SLB', 'Islas Salomón'),
(187, 'WS', 'WSM', 'Samoa'),
(188, 'AS', 'ASM', 'Samoa Americana'),
(189, 'KN', 'KNA', 'San Cristóbal y Nevis'),
(190, 'SM', 'SMR', 'San Marino'),
(191, 'PM', 'SPM', 'San Pedro y Miquelón'),
(192, 'VC', 'VCT', 'San Vicente y las Granadinas'),
(193, 'SH', 'SHN', 'Santa Helena'),
(194, 'LC', 'LCA', 'Santa Lucía'),
(195, 'ST', 'STP', 'Santo Tomé y Príncipe'),
(196, 'SN', 'SEN', 'Senegal'),
(197, 'CS', 'SCG', 'Serbia y Montenegro'),
(198, 'SC', 'SYC', 'Seychelles'),
(199, 'SL', 'SLE', 'Sierra Leona'),
(200, 'SG', 'SGP', 'Singapur'),
(201, 'SY', 'SYR', 'Siria'),
(202, 'SO', 'SOM', 'Somalia'),
(203, 'LK', 'LKA', 'Sri Lanka'),
(204, 'SZ', 'SWZ', 'Suazilandia'),
(205, 'ZA', 'ZAF', 'Sudáfrica'),
(206, 'SD', 'SDN', 'Sudán'),
(207, 'SE', 'SWE', 'Suecia'),
(208, 'CH', 'CHE', 'Suiza'),
(209, 'SR', 'SUR', 'Surinam'),
(210, 'SJ', 'SJM', 'Svalbard y Jan Mayen'),
(211, 'TH', 'THA', 'Tailandia'),
(212, 'TW', 'TWN', 'Taiwán'),
(213, 'TZ', 'TZA', 'Tanzania'),
(214, 'TJ', 'TJK', 'Tayikistán'),
(215, 'IO', 'IOT', 'Territorio Británico del Océano Índico'),
(216, 'TF', 'ATF', 'Territorios Australes Franceses'),
(217, 'TL', 'TLS', 'Timor Oriental'),
(218, 'TG', 'TGO', 'Togo'),
(219, 'TK', 'TKL', 'Tokelau'),
(220, 'TO', 'TON', 'Tonga'),
(221, 'TT', 'TTO', 'Trinidad y Tobago'),
(222, 'TN', 'TUN', 'Túnez'),
(223, 'TC', 'TCA', 'Islas Turcas y Caicos'),
(224, 'TM', 'TKM', 'Turkmenistán'),
(225, 'TR', 'TUR', 'Turquía'),
(226, 'TV', 'TUV', 'Tuvalu'),
(227, 'UA', 'UKR', 'Ucrania'),
(228, 'UG', 'UGA', 'Uganda'),
(229, 'UY', 'URY', 'Uruguay'),
(230, 'UZ', 'UZB', 'Uzbekistán'),
(231, 'VU', 'VUT', 'Vanuatu'),
(232, 'VE', 'VEN', 'Venezuela'),
(233, 'VN', 'VNM', 'Vietnam'),
(234, 'VG', 'VGB', 'Islas Vírgenes Británicas'),
(235, 'VI', 'VIR', 'Islas Vírgenes de los Estados Unidos'),
(236, 'WF', 'WLF', 'Wallis y Futuna'),
(237, 'YE', 'YEM', 'Yemen'),
(238, 'DJ', 'DJI', 'Yibuti'),
(239, 'ZM', 'ZMB', 'Zambia'),
(240, 'ZW', 'ZWE', 'Zimbabue');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `paquetes`
--

INSERT INTO `paquetes` (`idPaquete`, `idProvincia`, `titulo`, `subtitulo`, `destino`, `noches`, `capacidad`, `pension`, `imagen`, `banner`, `horaSalida`, `horaLlegada`, `fechaInicioPublicacion`, `fechaFinPublicacion`, `precio`, `traslado`, `tipo`, `descripcion`, `itinerario`, `equipo`, `estado`, `eliminado`, `created_at`) VALUES
(13, 4, 'finde xl en Villa Gral. Belgrano', 'paquete familiar', 'Villa General belgrano', 0, 20, 'Almuerzo, cena', 'uploads/paquetes/13/670d61b73daf4-2024_10_14-15_23_51.jpg', 'uploads/paquetes/13/670d61b73e3a8-2024_10_14-15_23_51.jpg', '09:00:00', '18:00:00', '2024-10-14', '2024-10-20', '420000', 1, 'E', NULL, NULL, NULL, 'A', 0, '2024-10-14 15:23:51');
INSERT INTO `paquetes` (`idPaquete`, `idProvincia`, `titulo`, `subtitulo`, `destino`, `noches`, `capacidad`, `pension`, `imagen`, `banner`, `horaSalida`, `horaLlegada`, `fechaInicioPublicacion`, `fechaFinPublicacion`, `precio`, `traslado`, `tipo`, `descripcion`, `itinerario`, `equipo`, `estado`, `eliminado`, `created_at`) VALUES
(17, 4, 'Feriado en Villa Carlos Paz', 'Incluye deportes extremos', 'Carlos Paz', 0, 10, 'Desayuno, almuerzo, merienda', 'uploads/paquetes/17/670dc407e20c6-2024_10_14-22_23_19.jpg', 'uploads/paquetes/17/670d640a8af2d-2024_10_14-15_33_46.jpg', '09:00:00', '20:00:00', '2024-10-14', '2024-10-20', '490000', 0, 'E', '<h2><span class=\"ql-size-large\">TALLER DE AUTORESCATE EN ESCALADA</span></h2><h2><span class=\"ql-size-large\">HERRAMIENTAS PARA ESCALAR EN LA NATURALEZA CON SEGURIDAD</span></h2><p></p><h3><strong>¿Te gustaría saber como solucionar situaciones que nos pueden suceder escalando?</strong></h3><p>Este taller esta pensado y planificado para que puedas incorporar <strong>conocimientos esenciales para escalar en la naturaleza,</strong> ya sea <strong>roca deportiva, tradicional o hielo con autonomía y seguridad.</strong></p><p></p><p><strong>El objetivo es que cada participante pueda afrontar problemáticas comunes, incorporando maniobras técnicas y herramientas con recursos mínimos básicos que siempre deberíamos tener en nuestro arnés.</strong></p><ul><li>En este taller vamos a pasar <strong>2 días y 1 noches</strong> trabajando en un <strong>sector de escalada deportiva en roca</strong>.</li><li><strong>Practicarás múltiples maniobras técnicas de seguridad aplicadas a la resolución de problemas en la vertical</strong>.</li></ul><p></p><p>Todo esto de la mano de un equipo de <strong>guías/escaladores profesionales habilitados y con basta experiencia </strong>que te brindarán la asistencia y seguridad necesaria en todo momento.</p>', '<p><span class=\"ql-size-huge\">Planificación de la excursión</span></p><p></p><p>Aunque hacemos todo lo posible para cumplir con el itinerario del taller, el mismo está sujeto a cambios por numerosas razones fuera de nuestro control, incluidas las condiciones climáticas y del terreno. Los contenidos programados serán adaptados para responder a las necesidades del grupo que conforma el taller, así cada quien va a poder realizar las actividades de acuerdo a su nivel de experiencia teniendo en cuenta los pre-requisitos solicitados.</p><p></p><p><span class=\"ql-size-large\">Teórico</span></p><ul><li>Introducción: Accidentes en la vertical, estadísticas y estudio de casos reales</li><li>Nudos, usos y diferencias: machard, prusik, mula, dinámico, dinámico doble, Dufour, Valdostano, corazón.</li><li>Kit básico de autorescate y botiquín.</li></ul><p></p><p><span class=\"ql-size-large\">Práctica</span></p><ul><li>Como bajarnos de una vía sin abandonar material, en caso de no llegar a la cadena.</li><li>Armado de reuniones seguras con equipo móvil.</li><li>Asegurar a uno o dos segundos.</li><li>Rappel en tándem y en contrapeso.</li><li>Ascenso por cuerda fija.</li><li>Traspaso de asegurar al segundo a bajarlo con dinámico.</li><li>Ascenso y descenso por cuerdas tensas.</li><li>Salto de nudo en rappel y en estación.</li><li>Poleas para ascenso de cargas.</li><li>Asistencia al segundo de cuerda.</li></ul><p></p>', '<h3><span class=\"ql-size-huge\">Lista de equipo requerido</span></h3><p>La clave para tu comodidad durante un viaje activo, es usar capas (ver <a href=\"https://www.huka.com.ar/conceptos-basicos-de-vestimenta-por-capas/\" rel=\"noopener noreferrer\" target=\"_blank\">Conceptos básicos de la vestimenta por capas</a>). Para obtener la máxima comodidad con un peso mínimo, necesitas capas versátiles que combinen aislamiento, ventilación y protección contra la intemperie.</p><p></p><p>Por eso, creamos esta lista para ayudarte a elegir el equipo y traer solo lo necesario. El clima y otras condiciones en el terreno dictarán qué ropa y equipo deberemos usar cada día.</p><p></p><p><img src=\"data:image/png;base64,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\"></p><p></p><h3><strong>EQUIPO TÉCNICO PERSONAL</strong></h3><ul><li>Pédulas</li><li>Arnés</li><li>Casco</li><li>Descensor tipo Reverso</li><li>Cabo de anclaje</li><li>3 x 1,5/2 mts de cordín de 6/7 mm</li><li>6 mosquetón con seguro</li><li>12 cintas express (por cordada)</li><li>Cinta cosida de 60 y 120 cm (por cordada)</li><li>Cuerda de 60m (por cordada)</li></ul><p></p><h3><strong>MOCHILA Y ACAMPE</strong></h3><ul><li>Mochila mediana (32 a 40 litros)</li><li>Bolsa de dormir abrigada (ver <a href=\"https://www.huka.com.ar/bolsa-de-dormir/\" rel=\"noopener noreferrer\" target=\"_blank\">Todo lo que necesitas saber sobre Bolsas de Dormir</a>)</li><li>Aislante</li><li>Carpa</li></ul><p></p><h3><strong>INDUMENTARIA</strong></h3><ul><li>Botas de trekking / zapatillas de aproximación</li><li>Pantalón de trekking</li><li>Interior térmico completo</li><li>Abrigo polar</li><li>Campera de abrigo</li><li>2/3 Pares de medias (al menos una de abrigo)</li><li>Gorro de sol/abrigo</li><li>Anteojos de sol categoría 3 o 4</li></ul><p></p><h3><strong>ELEMENTOS DE HIGIENE PERSONAL</strong></h3><ul><li>Papel higiénico, pañuelos y/o toallitas húmedas</li><li>Bolsa chica personal para basura</li></ul><p></p><h3><strong>VARIOS</strong></h3><ul><li>Linterna frontal con baterías</li><li>Pantalla solar, protector labial</li><li>Medicamentos personales</li><li>Botellas para cargar agua de 2 litros como mínimo</li><li>Termo de acero (por cordada)</li></ul><p></p><h2><strong>¿NECESITAS EQUIPO PARA EL TALLER?</strong></h2><p><strong>¡Alquila algo genial!</strong> Ofrecemos<strong> tarifas competitivas</strong> y equipo de calidad para nuestras salidas.</p>', 'A', 0, '2024-10-14 15:33:46');
INSERT INTO `paquetes` (`idPaquete`, `idProvincia`, `titulo`, `subtitulo`, `destino`, `noches`, `capacidad`, `pension`, `imagen`, `banner`, `horaSalida`, `horaLlegada`, `fechaInicioPublicacion`, `fechaFinPublicacion`, `precio`, `traslado`, `tipo`, `descripcion`, `itinerario`, `equipo`, `estado`, `eliminado`, `created_at`) VALUES
(18, 22, 'Bariloche & Lagos patagónicos', 'Verano 2025', 'Bariloche', 0, 15, 'almuerzo', 'uploads/paquetes/18/6740c86e2b289-57665.jpg', 'uploads/paquetes/18/6740c86e2be80-167390.jpg', '09:00:00', '18:00:00', '2024-11-22', '2024-11-29', '80000', 1, 'E', '<p><span class=\"ql-size-large\">General</span></p><p>Bus semicama | Unidades confort plus.</p><p>Receptivo a cargo de unidades especiales para trayectos de montaña &amp; nieve.</p><p>Alojamiento en Hoteles Exclusivos y Céntricos.</p><p>Media Pensión: desayuno + cena | sin bebidas</p><p></p><p><span class=\"ql-size-large\">NOTAS IMPORTANTES</span></p><p>El tour tiene fecha tope de saldo: 15 días antes de la fecha de salida seleccionada en su reserva (pasado dicho plazo se dará de baja a la reserva sin derecho a reembolso o reclamo alguno).</p><p>NO incluye entradas a Museos, Parques Nacionales, medios de elevación, actividades de montaña, etc</p><p>No incluye canon turístico BUS 2024 ($ 7500 se abona al momento de embarcar)</p><p>No incluye Honorarios guías locales / parques nacionales BUS ($ 7500 se abona al momento de embarcar)</p><p>La documentación (DNI/Pasaportes/Visas/Permisos de Menores) es exclusiva responsabilidad de los pasajeros. La misma debe estar en regla y en excelente estado para poder transitar dentro de nuestras fronteras o salir del país. En caso de no tenerla correctamente, los gastos que existieran correrán por cuenta de los pasajeros, quedando la empresa exenta de responsabilidad alguna.</p><p>Gastos adm $ 2900 | Gas emb $ 2900</p><p></p><p><span class=\"ql-size-large\">Medios de Pago</span></p><p>Precio de lista = equivalente a la sumatoria de cuotas indicadas en pago con tdc.</p><p>Pagos con tarjetas de débito / bancarios = aplica 90 % bonificación sobre precio de lista.</p><p>Pagos efectivo billete = aplica 100 % bonificación sobre precio de lista</p>', '<p><span class=\"ql-size-large\">Itinerario del día</span></p><ul><li>Salida a la mañana / día y noche en viaje.</li><li>Lagos Ruta 40 + Playa Bonita: aguas transparentes y vista única / Cena.</li><li>Circuito Chico &amp; Punto Panorámico + Cerro Campanario &amp; Mirador Llao Llao + Cerro CATEDRAL/ Cena</li><li>check out / tiempo libre para compras y paseo por Costanera del Lago / Regreso / Noche en Viaje</li></ul><p></p><p>La empresa se reserva el derecho de alterar y / o modificar itinerarios y excursiones contratados ante cuestiones de diagramación y/o causas de fuerza mayor y / o caso fortuito que impidan el normal cumplimiento del mismo: corte de rutas x nevadas, huracanes, inundaciones, reprogramación de eventos y / o cualquier otro factor que pueda significar un riesgo o una falta de servicio para el pasajero.</p>', NULL, 'A', 0, '2024-11-22 15:07:42'),
(19, 8, 'San Rafael aventura', 'verano 2025', 'San Rafael', 0, 18, 'almuerzo merienda', 'uploads/paquetes/19/6740cbff6da2b-170136.jpg', 'uploads/paquetes/19/6740cbff6dd79-145038.jpg', '08:30:00', '19:00:00', '2024-12-01', '2024-11-29', '120000', 1, 'E', '<p><span class=\"ql-size-large\">General</span></p><p>Excursión a Valle Grande / Margen del Atuel.</p><p>Excursión a Dique Los Reyunos</p><p>Visita a Bodega con degustación de variedades.</p><p></p><p><span class=\"ql-size-large\">NOTAS IMPORTANTES</span></p><p>El tour tiene fecha tope de saldo: 15 días antes de la fecha de salida seleccionada en su reserva (pasado dicho plazo se dará de baja a la reserva sin derecho a reembolso o reclamo alguno).</p><p>NO incluye entradas a Museos, Parques Nacionales, medios de elevación, actividades de montaña, etc</p><p>No incluye canon turístico BUS 2024 ($ 7500 se abona al momento de embarcar)</p><p>No incluye Honorarios guías locales / parques nacionales BUS ($ 7500 se abona al momento de embarcar)</p><p>La documentación (DNI/Pasaportes/Visas/Permisos de Menores) es exclusiva responsabilidad de los pasajeros. La misma debe estar en regla y en excelente estado para poder transitar dentro de nuestras fronteras o salir del país. En caso de no tenerla correctamente, los gastos que existieran correrán por cuenta de los pasajeros, quedando la empresa exenta de responsabilidad alguna.</p><p>Gastos adm $ 2900 | Gas emb $ 2900</p><p></p><h3><span class=\"ql-size-large\">Medios de Pago </span></h3><p>Precio de lista = equivalente a la sumatoria de cuotas indicadas en pago con tdc.</p><p>Pagos con tarjetas de débito / bancarios = aplica 90 % bonificación sobre precio de lista.</p><p>Pagos efectivo billete = aplica 100 % bonificación sobre precio de lista</p>', '<p><span class=\"ql-size-large\">ITINERARIO DE ACTIVIDADES</span></p><ul><li>Salida x la tarde / noche en viaje.</li><li>Visita a Bodega con degustación de variedades / Check In Hoteles / Excursión a Valle Grande - Margen del Atuel</li><li>Excursión a Valle Grande - Margen del Atuel / Raffting Aventura x Río Atuel</li><li>check out / Excursión al Dique Los Reyunos /. Regreso 15 00 hs.</li><li>Llegada / fin de los servicios.</li></ul><p></p><p><span class=\"ql-size-large\">EXCURSIONES</span></p><p>Excursión a Valle Grande / Margen del Atuel.</p><p>Excursión a Dique Los Reyunos</p><p>Visita a Bodega con degustación de variedades.</p>', '<p><strong>EQUIPO GENERAL</strong></p><ul><li>Mochila chica para trekking (30 a 55 litros)</li><li>Bastones de trekking (opcional)</li><li>Bolsa de dormir.</li><li><strong>Apto Físico *** (Su médico de cabecera debe indicar que se encuentra apto para realizar esta actividad).</strong></li></ul><p></p><p><strong>Equipo incluido en el curso</strong>:</p><ul><li>Arnes, casco, zapatillas de escalada (algunos números)</li></ul><p></p><p><strong>INDUMENTARIA</strong></p><ul><li>medias,</li><li>1 par de zapatillas–botitas de trekking (en condiciones)</li><li>1 segundo calzado de recambio liviano. (para usar en los domos)</li><li>pantalón largo de trekking (NO JEAN)</li><li>1 pantalón largo de abrigo o calza.</li><li>mudas de ropa interior.</li><li>remeras y/o camisas (una de manga larga)</li><li>abrigos (buzo de polar, ó pulover de lana)</li><li>1 equipo impermeable (campera)</li><li>1 sombrero-gorro para el sol (con solapas) y otro de abrigo</li><li>1 pañuelo para cubrir el cuello del sol</li><li>Guantes de abrigo y bufanda o cuello polar.</li><li>Gorro de abrigo</li></ul><p></p><p><strong>VARIOS</strong></p><ul><li>Linterna frontal con baterías.</li><li>Lentes para el sol.</li><li>Pantalla solar, Protector labial</li><li>Papel higiénico. Pañuelos. Toallitas para bebé.</li></ul><p></p><p><strong>COCINA</strong></p><ul><li>Recipientes para agua para 2 litros como mínimo (puede ser botella plástica)</li></ul><p> </p><p><strong>OPCIONALES, no obligatorio (ver espacio y peso)</strong></p><ul><li>Cinta SILVER TAPE, para ampollas y reparaciones.</li><li>Mate, bombilla y yerba.Termo de acero.</li><li>Asistencia al Viajero (Sugerida / Obligaría si no cuentan con Obra Social.***)</li></ul><p></p>', 'A', 0, '2024-11-22 15:22:55'),
(20, 19, 'CATARATAS del IGUAZU', 'Enero 2025', 'Cataratas del iguazu', 0, 30, 'desayuno, almuerzo', 'uploads/paquetes/20/6740cf75a6518-147198.jpg', 'uploads/paquetes/20/6740cf75a6956-9415.jpg', '08:00:00', '18:00:00', '2024-11-22', '2024-12-31', '160000', 0, 'E', NULL, NULL, NULL, 'A', 1, '2024-11-22 15:37:41'),
(21, 19, 'CATARATAS del IGUAZU', 'Enero 2025', 'Cataratas del iguazu', 0, 30, 'desayuno, almuerzo', 'uploads/paquetes/21/6740d0e9ab820-147198.jpg', 'uploads/paquetes/21/6740d0e9abbcb-9415.jpg', '08:00:00', '18:00:00', '2024-11-22', '2024-12-31', '160000', 0, 'E', NULL, NULL, NULL, 'A', 1, '2024-11-22 15:43:53'),
(22, 19, 'CATARATAS del IGUAZU', 'Enero 2025', 'Cataratas del iguazu', 0, 30, 'desayuno, almuerzo', 'uploads/paquetes/22/6740d1330619c-147198.jpg', 'uploads/paquetes/22/6740d13306acc-9415.jpg', '08:00:00', '18:00:00', '2024-11-22', '2024-12-31', '160000', 0, 'E', '<p><span class=\"ql-size-large\">Detalle General</span></p><p>Programa Completo.</p><p> Bus CAMA | Unidades confort plus.</p><p> Hotelería de “primera línea” con piscina (lado argentino)</p><p> Media Pensión: Desayuno &amp; Cena. No incluye bebidas.</p><p></p><p><span class=\"ql-size-large\">Excursiones</span></p><p>Cataratas Argentinas / incluye los 3 circuitos + trencito a la Garganta del Diablo.</p><p> La ARIPUCA.</p><p> Minas de piedras semi preciosas Wanda.</p><p> Ruinas Jesuíticas De San Ignacio.</p><p> Paseo en BALSA x el RIO</p><p> No incluye entradas.</p><p></p><p><span class=\"ql-size-large\">NOTAS IMPORTANTES</span></p><p>El tour tiene fecha tope de saldo: 15 días antes de la fecha de salida seleccionada en su reserva (pasado dicho plazo se dará de baja a la reserva sin derecho a reembolso o reclamo alguno).</p><p>NO incluye entradas a Museos, Parques Nacionales, medios de elevación, actividades de montaña, etc</p><p>No incluye canon turístico BUS 2024 ($ 7500 se abona al momento de embarcar)</p><p>No incluye Honorarios guías locales / parques nacionales BUS ($ 7500 se abona al momento de embarcar)</p><p>La documentación (DNI/Pasaportes/Visas/Permisos de Menores) es exclusiva responsabilidad de los pasajeros. La misma debe estar en regla y en excelente estado para poder transitar dentro de nuestras fronteras o salir del país. En caso de no tenerla correctamente, los gastos que existieran correrán por cuenta de los pasajeros, quedando la empresa exenta de responsabilidad alguna.</p><p>Gastos adm $ 2900 | Gas emb $ 2900</p><p></p><p></p><p><span class=\"ql-size-large\">Medios de Pago </span></p><p>Precio de lista = equivalente a la sumatoria de cuotas indicadas en pago con tdc.</p><p>Pagos con tarjetas de débito / bancarios = aplica 90 % bonificación sobre precio de lista.</p><p>Pagos efectivo billete = aplica 100 % bonificación sobre precio de lista</p>', '<p><span class=\"ql-size-large\">Excursiones</span></p><p>Cataratas Argentinas / incluye los 3 circuitos + trencito a la Garganta del Diablo.</p><p> La ARIPUCA.</p><p> Minas de piedras semi preciosas Wanda.</p><p> Ruinas Jesuíticas De San Ignacio.</p><p> Paseo en BALSA x el RIO</p><p> No incluye entradas.</p><p></p><p><span class=\"ql-size-large\">ITINERARIO DE ACTIVIDADES</span></p><ul><li>Ruinas De San Ignacio / Check In / Paseo en BALSA x el RIO / Cena.</li><li>Desayuno / Cataratas Argentinas (Full Day) / La Aripuca / Cena</li><li>Desayuno / Check Out / Minas De Wanda / Regreso 14 hs.</li><li>Llegada orígen a la tarde / Fin de los servicios.</li></ul><p></p><p></p><p> La empresa se reserva el derecho de alterar y / o modificar itinerarios y excursiones contratados ante cuestiones de diagramación y/o causas de fuerza mayor y / o caso fortuito que impidan el normal cumplimiento del mismo: corte de rutas x nevadas, huracanes, inundaciones, reprogramación de eventos y / o cualquier otro factor que pueda significar un riesgo o una falta de servicio para el pasajero.</p>', NULL, 'A', 0, '2024-11-22 15:45:07');

-- --------------------------------------------------------



--
-- Volcado de datos para la tabla `paquetes_fechas_salida`
--

INSERT INTO `paquetes_fechas_salida` (`id`, `idPaquete`, `fecha`, `hasRecorrido`, `created_at`) VALUES
(2, 17, '2025-01-20', 0, '2024-10-14 15:44:52'),
(3, 17, '2025-01-21', 0, '2024-10-14 15:44:52'),
(4, 17, '2024-11-09', 0, '2024-10-15 17:01:09'),
(5, 17, '2024-12-20', 0, '2024-10-15 17:01:09'),
(8, 13, '2025-01-20', 1, '2024-10-15 17:22:12'),
(9, 13, '2024-11-14', 0, '2024-11-13 14:30:37'),
(10, 13, '2024-11-15', 0, '2024-11-13 14:30:37'),
(11, 17, '2024-11-14', 0, '2024-11-13 14:31:00'),
(12, 17, '2024-11-13', 0, '2024-11-13 15:01:00'),
(13, 18, '2025-01-04', 0, '2024-11-22 15:14:58'),
(14, 18, '2025-01-05', 0, '2024-11-22 15:14:58'),
(15, 18, '2025-01-11', 0, '2024-11-22 15:14:58'),
(16, 18, '2025-01-12', 0, '2024-11-22 15:14:58'),
(17, 18, '2025-01-18', 0, '2024-11-22 15:14:58'),
(18, 18, '2025-01-19', 0, '2024-11-22 15:14:58'),
(19, 18, '2025-01-25', 0, '2024-11-22 15:14:58'),
(20, 18, '2025-01-26', 0, '2024-11-22 15:14:58'),
(21, 18, '2025-02-01', 0, '2024-11-22 15:14:58'),
(22, 18, '2025-02-02', 0, '2024-11-22 15:14:58'),
(23, 18, '2025-02-08', 0, '2024-11-22 15:14:58'),
(24, 18, '2025-02-09', 0, '2024-11-22 15:14:58'),
(25, 18, '2025-02-15', 0, '2024-11-22 15:14:58'),
(26, 18, '2025-02-16', 0, '2024-11-22 15:14:58'),
(27, 18, '2025-02-22', 0, '2024-11-22 15:14:58'),
(28, 18, '2025-02-23', 0, '2024-11-22 15:14:58'),
(30, 19, '2025-01-04', 0, '2024-11-22 15:25:36'),
(31, 19, '2025-01-05', 0, '2024-11-22 15:25:36'),
(32, 19, '2025-01-11', 0, '2024-11-22 15:25:36'),
(33, 19, '2025-01-12', 0, '2024-11-22 15:25:36'),
(34, 19, '2025-01-18', 0, '2024-11-22 15:25:36'),
(35, 19, '2025-01-19', 0, '2024-11-22 15:25:36'),
(36, 19, '2025-01-25', 0, '2024-11-22 15:25:36'),
(37, 19, '2025-01-26', 0, '2024-11-22 15:25:36'),
(38, 19, '2025-02-01', 0, '2024-11-22 15:25:36'),
(39, 19, '2025-02-02', 0, '2024-11-22 15:25:36'),
(40, 19, '2025-02-08', 0, '2024-11-22 15:25:36'),
(41, 19, '2025-02-09', 0, '2024-11-22 15:25:36'),
(50, 22, '2025-01-04', 0, '2024-11-22 15:45:07'),
(51, 22, '2025-01-05', 0, '2024-11-22 15:45:07'),
(52, 22, '2025-01-11', 0, '2024-11-22 15:45:07'),
(53, 22, '2025-01-12', 0, '2024-11-22 15:45:07'),
(54, 22, '2025-01-18', 0, '2024-11-22 15:45:07'),
(55, 22, '2025-01-19', 0, '2024-11-22 15:45:07'),
(56, 22, '2025-01-25', 0, '2024-11-22 15:45:07'),
(57, 22, '2025-01-26', 0, '2024-11-22 15:45:07');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `paquetes_galeria`
--

INSERT INTO `paquetes_galeria` (`id`, `idPaquete`, `path`, `orden`, `created_at`) VALUES
(1, 17, 'uploads/paquetes/17/galeria/67115df639926-2024_10_17-15_56_54.mp4', 3, '2024-10-17 15:56:54'),
(2, 17, 'uploads/paquetes/17/galeria/67115df639e99-2024_10_17-15_56_54.jpg', 1, '2024-10-17 15:56:54'),
(7, 17, 'uploads/paquetes/17/galeria/67116a098ed5c-97983.jpg', 2, '2024-10-17 16:48:25'),
(8, 18, 'uploads/paquetes/18/galeria/6740c9e5f207a-311345.jpg', 1, '2024-11-22 15:13:57'),
(9, 18, 'uploads/paquetes/18/galeria/6740c9e5f290f-12605.jpg', 4, '2024-11-22 15:13:57'),
(10, 18, 'uploads/paquetes/18/galeria/6740c9e5f2fb7-337607.png', 2, '2024-11-22 15:13:57'),
(11, 18, 'uploads/paquetes/18/galeria/6740c9e5f3220-338789.png', 3, '2024-11-22 15:13:57'),
(12, 19, 'uploads/paquetes/19/galeria/6740cde176bff-90186.jpg', 1000, '2024-11-22 15:30:57'),
(13, 19, 'uploads/paquetes/19/galeria/6740cde177004-78197.jpg', 1000, '2024-11-22 15:30:57'),
(14, 19, 'uploads/paquetes/19/galeria/6740cde177313-13128.jpg', 1000, '2024-11-22 15:30:57'),
(15, 19, 'uploads/paquetes/19/galeria/6740cde1775e9-46731.jpg', 1000, '2024-11-22 15:30:57'),
(16, 22, 'uploads/paquetes/22/galeria/6740d2552561f-127446.jpg', 3, '2024-11-22 15:49:57'),
(17, 22, 'uploads/paquetes/22/galeria/6740d255259ce-8946.jpg', 1, '2024-11-22 15:49:57'),
(18, 22, 'uploads/paquetes/22/galeria/6740d2552c022-35597.jpeg', 4, '2024-11-22 15:49:57'),
(19, 22, 'uploads/paquetes/22/galeria/6740d2552c407-10448.jpg', 2, '2024-11-22 15:49:57');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`idProvincia`, `nombre`, `capital`, `IATA`) VALUES
(1, 'Ciudad Autónoma de Buenos Aires', '', 'CABA'),
(2, 'Buenos Aires', 'La Plata', 'BA'),
(3, 'Catamarca', 'San Fernando del Valle de Catamarca', 'CA'),
(4, 'Córdoba', 'Córdoba', 'CB'),
(5, 'Corrientes', 'Corrientes', 'CR'),
(6, 'Entre Ríos', 'Paraná', 'ER'),
(7, 'Jujuy', 'San Salvador de Jujuy', 'JY'),
(8, 'Mendoza', 'Mendoza', 'MZ'),
(9, 'La Rioja', 'La Rioja', 'LR'),
(10, 'Salta', 'Salta', 'SA'),
(11, 'San Juan', 'San Juan', 'SJ'),
(12, 'San Luis', 'San Luis', 'SL'),
(13, 'Santa Fe', 'Santa Fe', 'SF'),
(14, 'Santiago del Estero', 'Santiago del Estero', 'SE'),
(15, 'Tucumán', 'San Miguel de Tucumán', 'TU'),
(16, 'Chaco', 'Resistencia', 'CH'),
(17, 'Chubut', 'Rawson', 'CT'),
(18, 'Formosa', 'Formosa', 'FO'),
(19, 'Misiones', 'Posadas', 'MI'),
(20, 'Neuquén', 'Neuquén', 'NQ'),
(21, 'La Pampa', 'Santa Rosa', 'LP'),
(22, 'Río Negro', 'Viedma', 'RN'),
(23, 'Santa Cruz', 'Río Gallegos', 'SC'),
(24, 'Tierra del Fuego', 'Ushuaia', 'TF');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `recorridos`
--

INSERT INTO `recorridos` (`idRecorrido`, `idPaquete`, `idUsuario`, `fecha`, `total`, `pasajeros`, `totalAlojamientoConsulta`, `created_by_idUsuario`, `created_at`) VALUES
(4, 13, 6, '2025-01-20', '2100000', 5, 2, 1, '2024-11-13 12:20:19');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `recorrido_mensajes`
--

INSERT INTO `recorrido_mensajes` (`idMensaje`, `idRecorrido`, `idUsuario`, `mensaje`, `tipo`, `created_at`) VALUES
(1, 4, 3, '<p>Hola a todos</p><p>A que hora sale del punto de partida ?</p>', 'C', '2024-11-19 13:28:10'),
(2, 4, 2, '<p>Hola gente</p>\r\n<p>A las 9hs sale del punto de partida!</p>', 'C', '2024-11-19 13:29:59'),
(3, 4, 6, '<p>Hola gente, ¿cómo están?</p>\r\n<p>El horario de salida es a las 9am desde el punto de partida indicado con anterioridad</p>\r\n<p></p>\r\n<p>Saludos</p>', 'U', '2024-11-19 13:42:39'),
(4, 4, 3, '<p>Buenos días Otto,</p><p></p><p>Gracias por avisar!</p>', 'C', '2024-11-19 13:48:43'),
(5, 4, 6, '<p>De nada!</p>\r\n<p>Recuerden llevar todo el equipo necesario para la excursión, como comida, ropa, protector, etc</p>', 'U', '2024-11-19 13:59:30'),
(6, 4, 3, '<p>Todo listo por acá</p>', 'C', '2024-11-19 14:17:24'),
(7, 4, 1, '<p>Perfecto!</p><p>Los demás pueden confirmar cuando quiera  pero no es necesario</p><p></p><p>Saludos</p>', 'U', '2024-11-19 15:45:59');

-- --------------------------------------------------------

--
-- Volcado de datos para la tabla `recorrido_tramos`
--

INSERT INTO `recorrido_tramos` (`idRecorridoTramo`, `idRecorrido`, `idAlojamiento`, `pax`, `orden`, `estado`, `tipo`, `updated_at`, `created_at`) VALUES
(24, 4, 0, '1', 1, 'P', 'O', '2024-11-15 17:24:49', '2024-11-15 17:24:49'),
(25, 4, 1, '2', 2, 'P', 'P', '2024-11-15 17:24:49', '2024-11-15 17:24:49'),
(26, 4, 2, '2', 3, 'P', 'P', '2024-11-15 17:24:49', '2024-11-15 17:24:49'),
(27, 4, 0, '0', 4, 'P', 'D', '2024-11-15 17:24:49', '2024-11-15 17:24:49');

-- --------------------------------------------------------


--
-- Volcado de datos para la tabla `recorrido_tramo_pasajeros`
--

INSERT INTO `recorrido_tramo_pasajeros` (`idRecorridoTramoPasajero`, `idRecorrido`, `idRecorridoTramo`, `idConsultaPasajero`, `created_at`) VALUES
(26, 4, 24, 19, '2024-11-15 17:24:49'),
(27, 4, 25, 11, '2024-11-15 17:24:49'),
(28, 4, 25, 12, '2024-11-15 17:24:49'),
(29, 4, 26, 17, '2024-11-15 17:24:49'),
(30, 4, 26, 18, '2024-11-15 17:24:49');

-- --------------------------------------------------------