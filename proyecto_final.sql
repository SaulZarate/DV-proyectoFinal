-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2024 a las 16:30:24
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
-- Base de datos: `proyecto_final`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alojamientos`
--

CREATE TABLE `alojamientos` (
  `idAlojamiento` int(5) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `latitud` varchar(100) NOT NULL,
  `longitud` varchar(100) NOT NULL,
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int(5) UNSIGNED NOT NULL,
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
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas`
--

CREATE TABLE `consultas` (
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `idUsuario` int(5) UNSIGNED NOT NULL,
  `idCliente` int(5) UNSIGNED NOT NULL,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `idPaqueteFechaSalida` int(5) UNSIGNED NOT NULL,
  `idOrigen` int(5) UNSIGNED NOT NULL,
  `idAlojamiento` int(5) UNSIGNED NOT NULL,
  `asunto` varchar(255) NOT NULL,
  `total` varchar(50) NOT NULL DEFAULT '0' COMMENT 'Total de la venta',
  `traslado` int(1) NOT NULL DEFAULT 0,
  `estado` char(1) NOT NULL DEFAULT 'A' COMMENT 'A=Abierto | C=Cerrado | V=Vendido',
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_contacto_adicional`
--

CREATE TABLE `consulta_contacto_adicional` (
  `idContactoAdicional` int(5) UNSIGNED NOT NULL,
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `contacto` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_mensajes`
--

CREATE TABLE `consulta_mensajes` (
  `idMensaje` int(5) UNSIGNED NOT NULL,
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `idUsuarioMensaje` int(5) UNSIGNED NOT NULL COMMENT 'Puede ser el cliente, usuario del sistema o el sistema',
  `mensaje` text NOT NULL,
  `tipo` char(5) NOT NULL COMMENT 'C=Cliente | U=Usuario | S=sitema',
  `isNotaInterna` int(1) NOT NULL DEFAULT 0,
  `typeMessageSistem` char(1) NOT NULL DEFAULT 'N' COMMENT 'Solo para mensajes del sistema\r\nN=normal | I=info | W=warning | D=danger',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta_pasajeros`
--

CREATE TABLE `consulta_pasajeros` (
  `idPasajero` int(5) UNSIGNED NOT NULL,
  `idConsulta` int(5) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `apellido` varchar(150) NOT NULL,
  `sexo` char(1) NOT NULL,
  `fechaDeNacimiento` date NOT NULL,
  `observaciones` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `idEvento` int(5) UNSIGNED NOT NULL,
  `idUsuario` int(5) UNSIGNED NOT NULL,
  `fechaInicio` datetime NOT NULL,
  `fechaFin` datetime NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `descripcion` text NOT NULL,
  `tipo` varchar(50) NOT NULL COMMENT 'Por si en algún momento necesito darle estados o etiquetas',
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `origenes`
--

CREATE TABLE `origenes` (
  `idOrigen` int(5) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paises`
--

CREATE TABLE `paises` (
  `id` int(11) UNSIGNED NOT NULL,
  `shortName1` char(2) DEFAULT NULL COMMENT 'iso3166a1',
  `shortName2` char(3) DEFAULT NULL COMMENT 'iso3166a2',
  `nombre` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes`
--

CREATE TABLE `paquetes` (
  `idPaquete` int(5) UNSIGNED NOT NULL,
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
  `traslado` int(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Indica si el transporte pasa a buscar al cliente',
  `tipo` char(1) NOT NULL DEFAULT 'E' COMMENT 'E=Excursiones | P=Paquetes',
  `descripcion` longtext DEFAULT NULL,
  `itinerario` longtext DEFAULT NULL,
  `equipo` longtext DEFAULT NULL,
  `estado` char(1) NOT NULL DEFAULT 'A',
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes_fechas_salida`
--

CREATE TABLE `paquetes_fechas_salida` (
  `id` int(10) UNSIGNED NOT NULL,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paquetes_galeria`
--

CREATE TABLE `paquetes_galeria` (
  `id` int(5) UNSIGNED NOT NULL,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `path` varchar(100) NOT NULL COMMENT 'nombre del archivo',
  `orden` int(2) NOT NULL DEFAULT 1000,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `idProvincia` int(2) UNSIGNED NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `capital` varchar(100) NOT NULL,
  `IATA` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(5) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'sha1',
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
  `eliminado` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  ADD PRIMARY KEY (`idAlojamiento`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- Indices de la tabla `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`idConsulta`);

--
-- Indices de la tabla `consulta_contacto_adicional`
--
ALTER TABLE `consulta_contacto_adicional`
  ADD PRIMARY KEY (`idContactoAdicional`);

--
-- Indices de la tabla `consulta_mensajes`
--
ALTER TABLE `consulta_mensajes`
  ADD PRIMARY KEY (`idMensaje`);

--
-- Indices de la tabla `consulta_pasajeros`
--
ALTER TABLE `consulta_pasajeros`
  ADD PRIMARY KEY (`idPasajero`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`idEvento`);

--
-- Indices de la tabla `origenes`
--
ALTER TABLE `origenes`
  ADD PRIMARY KEY (`idOrigen`);

--
-- Indices de la tabla `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  ADD PRIMARY KEY (`idPaquete`);

--
-- Indices de la tabla `paquetes_fechas_salida`
--
ALTER TABLE `paquetes_fechas_salida`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paquetes_galeria`
--
ALTER TABLE `paquetes_galeria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`idProvincia`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alojamientos`
--
ALTER TABLE `alojamientos`
  MODIFY `idAlojamiento` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consultas`
--
ALTER TABLE `consultas`
  MODIFY `idConsulta` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consulta_contacto_adicional`
--
ALTER TABLE `consulta_contacto_adicional`
  MODIFY `idContactoAdicional` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consulta_mensajes`
--
ALTER TABLE `consulta_mensajes`
  MODIFY `idMensaje` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consulta_pasajeros`
--
ALTER TABLE `consulta_pasajeros`
  MODIFY `idPasajero` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `idEvento` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `origenes`
--
ALTER TABLE `origenes`
  MODIFY `idOrigen` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paises`
--
ALTER TABLE `paises`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes`
--
ALTER TABLE `paquetes`
  MODIFY `idPaquete` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes_fechas_salida`
--
ALTER TABLE `paquetes_fechas_salida`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paquetes_galeria`
--
ALTER TABLE `paquetes_galeria`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `provincias`
--
ALTER TABLE `provincias`
  MODIFY `idProvincia` int(2) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
