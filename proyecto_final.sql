-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2024 a las 19:54:52
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
-- Estructura de tabla para la tabla `recorridos`
--

CREATE TABLE `recorridos` (
  `idRecorrido` int(5) UNSIGNED NOT NULL,
  `idPaquete` int(5) UNSIGNED NOT NULL,
  `idUsuario` int(11) NOT NULL COMMENT 'guía',
  `fecha` date NOT NULL,
  `total` varchar(20) NOT NULL DEFAULT '0',
  `pasajeros` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `totalAlojamientoConsulta` int(2) UNSIGNED NOT NULL DEFAULT 0,
  `created_by_idUsuario` int(5) UNSIGNED NOT NULL COMMENT 'Usuario que creo el recorrido',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recorridos`
--

INSERT INTO `recorridos` (`idRecorrido`, `idPaquete`, `idUsuario`, `fecha`, `total`, `pasajeros`, `totalAlojamientoConsulta`, `created_by_idUsuario`, `created_at`) VALUES
(4, 13, 6, '2025-01-20', '2100000', 5, 2, 1, '2024-11-13 12:20:19');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `recorridos`
--
ALTER TABLE `recorridos`
  ADD PRIMARY KEY (`idRecorrido`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `recorridos`
--
ALTER TABLE `recorridos`
  MODIFY `idRecorrido` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
