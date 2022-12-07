-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-12-2022 a las 11:41:16
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `2223_giraldocarlos`
--
CREATE DATABASE IF NOT EXISTS `2223_giraldocarlos` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `2223_giraldocarlos`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cargo`
--

CREATE TABLE `tbl_cargo` (
  `id_cargo` int(11) NOT NULL,
  `nom_cargo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_cargo`
--

INSERT INTO `tbl_cargo` (`id_cargo`, `nom_cargo`) VALUES
(1, 'Camarero'),
(2, 'Mantenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_empleado`
--

CREATE TABLE `tbl_empleado` (
  `id_empleado` int(11) NOT NULL,
  `nom_empleado` varchar(20) NOT NULL,
  `ape_empleado` varchar(20) NOT NULL,
  `dni_empleado` varchar(9) NOT NULL,
  `password_empleado` text NOT NULL,
  `fk_cargo_empleado` int(11) NOT NULL,
  `email_empleado` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_empleado`
--

INSERT INTO `tbl_empleado` (`id_empleado`, `nom_empleado`, `ape_empleado`, `dni_empleado`, `password_empleado`, `fk_cargo_empleado`, `email_empleado`) VALUES
(12, 'Sergi', 'Garcia', '33011587D', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 'sgarcia@gmail.com'),
(17, 'Carlos', 'Giraldo', '48068415W', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 2, 'cgiraldolozano@gmail.com'),
(18, 'Ricardo', 'Durán', '32771997X', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 'rduran@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_incidencia`
--

CREATE TABLE `tbl_incidencia` (
  `id_inc` int(11) NOT NULL,
  `nom_inc` varchar(200) NOT NULL,
  `estado_inc` tinyint(1) NOT NULL,
  `fk_mesa_inc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_incidencia`
--

INSERT INTO `tbl_incidencia` (`id_inc`, `nom_inc`, `estado_inc`, `fk_mesa_inc`) VALUES
(20, 'pata rota', 1, 23);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_mesa`
--

CREATE TABLE `tbl_mesa` (
  `id_mesa` int(11) NOT NULL,
  `num_mesa` int(2) NOT NULL,
  `estado_mesa` enum('0','1','2') NOT NULL,
  `fk_num_sala` int(11) DEFAULT NULL,
  `capacidad_mesa` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_mesa`
--

INSERT INTO `tbl_mesa` (`id_mesa`, `num_mesa`, `estado_mesa`, `fk_num_sala`, `capacidad_mesa`) VALUES
(3, 3, '0', 1, 6),
(9, 9, '1', 1, 2),
(14, 14, '0', 1, 4),
(15, 15, '0', 1, 4),
(16, 16, '0', 1, 4),
(18, 18, '0', 1, 6),
(19, 19, '0', 1, 6),
(20, 20, '0', 1, 6),
(23, 6, '2', 1, 2),
(26, 1, '0', 1, 4),
(27, 2, '0', 2, 2),
(28, 4, '0', 1, 4),
(30, 7, '0', 3, 10),
(33, 5, '0', 2, 4),
(34, 8, '0', 2, 4),
(35, 10, '0', 2, 4),
(36, 11, '0', 2, 6),
(37, 12, '0', 2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_registro`
--

CREATE TABLE `tbl_registro` (
  `id_registro` int(11) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime DEFAULT NULL,
  `id_mesa` int(11) NOT NULL,
  `id_camarero` int(11) DEFAULT NULL,
  `num_comensales` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_registro`
--

INSERT INTO `tbl_registro` (`id_registro`, `fecha_entrada`, `fecha_salida`, `id_mesa`, `id_camarero`, `num_comensales`) VALUES
(11, '2022-11-10 17:53:14', '2022-11-10 18:17:19', 3, NULL, 2),
(24, '2022-11-29 16:10:30', '2022-11-29 16:33:55', 3, NULL, 2),
(31, '2022-12-01 15:50:44', '2022-12-01 15:50:53', 26, NULL, 1),
(33, '2022-12-07 11:40:20', NULL, 9, 17, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_reserva`
--

CREATE TABLE `tbl_reserva` (
  `id_reserva` int(11) NOT NULL,
  `fk_mesa_reserva` int(11) NOT NULL,
  `nombre_reserva` varchar(100) NOT NULL,
  `comensales_reserva` int(2) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `hora_inicio_reserva` time NOT NULL,
  `hora_final_reserva` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_reserva`
--

INSERT INTO `tbl_reserva` (`id_reserva`, `fk_mesa_reserva`, `nombre_reserva`, `comensales_reserva`, `fecha_reserva`, `hora_inicio_reserva`, `hora_final_reserva`) VALUES
(12, 14, 'Carlos', 4, '2022-12-08', '16:00:00', '16:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_sala`
--

CREATE TABLE `tbl_sala` (
  `id_sala` int(11) NOT NULL,
  `nom_sala` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_sala`
--

INSERT INTO `tbl_sala` (`id_sala`, `nom_sala`) VALUES
(1, 'Principal - 1'),
(2, 'Comedor - 2'),
(3, 'Privada - 3');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_cargo`
--
ALTER TABLE `tbl_cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `fk_cargo_empleado` (`fk_cargo_empleado`);

--
-- Indices de la tabla `tbl_incidencia`
--
ALTER TABLE `tbl_incidencia`
  ADD PRIMARY KEY (`id_inc`),
  ADD KEY `fk_mesa_inc` (`fk_mesa_inc`);

--
-- Indices de la tabla `tbl_mesa`
--
ALTER TABLE `tbl_mesa`
  ADD PRIMARY KEY (`id_mesa`),
  ADD KEY `id_num_sala` (`fk_num_sala`);

--
-- Indices de la tabla `tbl_registro`
--
ALTER TABLE `tbl_registro`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_mesa` (`id_mesa`),
  ADD KEY `id_camarero` (`id_camarero`);

--
-- Indices de la tabla `tbl_reserva`
--
ALTER TABLE `tbl_reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_mesa_reserva` (`fk_mesa_reserva`);

--
-- Indices de la tabla `tbl_sala`
--
ALTER TABLE `tbl_sala`
  ADD PRIMARY KEY (`id_sala`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_cargo`
--
ALTER TABLE `tbl_cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tbl_incidencia`
--
ALTER TABLE `tbl_incidencia`
  MODIFY `id_inc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `tbl_mesa`
--
ALTER TABLE `tbl_mesa`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `tbl_registro`
--
ALTER TABLE `tbl_registro`
  MODIFY `id_registro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `tbl_reserva`
--
ALTER TABLE `tbl_reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tbl_sala`
--
ALTER TABLE `tbl_sala`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  ADD CONSTRAINT `tbl_empleado_ibfk_1` FOREIGN KEY (`fk_cargo_empleado`) REFERENCES `tbl_cargo` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_incidencia`
--
ALTER TABLE `tbl_incidencia`
  ADD CONSTRAINT `tbl_incidencia_ibfk_1` FOREIGN KEY (`fk_mesa_inc`) REFERENCES `tbl_mesa` (`id_mesa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_mesa`
--
ALTER TABLE `tbl_mesa`
  ADD CONSTRAINT `tbl_mesa_ibfk_1` FOREIGN KEY (`fk_num_sala`) REFERENCES `tbl_sala` (`id_sala`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `tbl_registro`
--
ALTER TABLE `tbl_registro`
  ADD CONSTRAINT `tbl_registro_ibfk_2` FOREIGN KEY (`id_mesa`) REFERENCES `tbl_mesa` (`id_mesa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_registro_ibfk_3` FOREIGN KEY (`id_camarero`) REFERENCES `tbl_empleado` (`id_empleado`) ON DELETE SET NULL;

--
-- Filtros para la tabla `tbl_reserva`
--
ALTER TABLE `tbl_reserva`
  ADD CONSTRAINT `tbl_reserva_ibfk_1` FOREIGN KEY (`fk_mesa_reserva`) REFERENCES `tbl_mesa` (`id_mesa`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
