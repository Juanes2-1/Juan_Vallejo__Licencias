-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-04-2025 a las 16:32:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `album`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `album`
--

CREATE TABLE `album` (
  `Id_Album` int(11) NOT NULL,
  `Documento` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `album`
--

INSERT INTO `album` (`Id_Album`, `Documento`) VALUES
(1, '1016052623'),
(2, '987654321');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carta`
--

CREATE TABLE `carta` (
  `CodigoBarras` varchar(100) NOT NULL,
  `NombrePersonaje` varchar(100) NOT NULL,
  `NivelPersonaje` int(11) NOT NULL,
  `Equipo` varchar(100) NOT NULL,
  `Imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carta`
--

INSERT INTO `carta` (`CodigoBarras`, `NombrePersonaje`, `NivelPersonaje`, `Equipo`, `Imagen`) VALUES
('CB001', 'Dragon Rojo', 5, 'Fuego', 'dragon_rojo.png'),
('CB002', 'Caballero Azul', 3, 'Hielo', 'caballero_azul.png'),
('CB003', 'Hechicera Oscura', 4, 'Sombra', 'hechicera_oscura.png'),
('CB004', 'Guerrero Solar', 2, 'Luz', 'guerrero_solar.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallealbum`
--

CREATE TABLE `detallealbum` (
  `Id_Detalle` int(11) NOT NULL,
  `Id_Album` int(11) DEFAULT NULL,
  `CodigoBarras` varchar(100) DEFAULT NULL,
  `FechaAdquisicion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detallealbum`
--

INSERT INTO `detallealbum` (`Id_Detalle`, `Id_Album`, `CodigoBarras`, `FechaAdquisicion`) VALUES
(1, 1, 'CB001', '2025-04-23 11:10:17'),
(2, 1, 'CB002', '2025-04-23 11:10:17'),
(3, 1, 'CB003', '2025-04-23 11:10:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `Nit` varchar(20) NOT NULL,
  `NombreEmpresa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`Nit`, `NombreEmpresa`) VALUES
('800123456-7', 'Cartas Legendarias S.A.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `Id_Estado` int(11) NOT NULL,
  `Estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`Id_Estado`, `Estado`) VALUES
(1, 'Activa'),
(2, 'Inactiva'),
(3, 'Suspendida');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `licencia`
--

CREATE TABLE `licencia` (
  `Id_Licencia` int(11) NOT NULL,
  `Id_Tipo` int(11) DEFAULT NULL,
  `Id_Estado` int(11) DEFAULT NULL,
  `Nit` varchar(20) DEFAULT NULL,
  `FechaIni` date NOT NULL,
  `FechaFin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `licencia`
--

INSERT INTO `licencia` (`Id_Licencia`, `Id_Tipo`, `Id_Estado`, `Nit`, `FechaIni`, `FechaFin`) VALUES
(1, 2, 1, '800123456-7', '2025-01-01', '2025-12-31'),
(2, 1, 2, '800123456-7', '2024-01-01', '2024-12-31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `Id_Rol` int(11) NOT NULL,
  `NombreRol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`Id_Rol`, `NombreRol`) VALUES
(1, 'Administrador'),
(2, 'Jugador'),
(3, 'SuperAdmi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo`
--

CREATE TABLE `tipo` (
  `Id_Tipo` int(11) NOT NULL,
  `TipoLicencia` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo`
--

INSERT INTO `tipo` (`Id_Tipo`, `TipoLicencia`) VALUES
(1, 'Free'),
(2, 'Premium'),
(3, 'Free'),
(4, 'Premium');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Documento` varchar(20) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Correo` varchar(100) NOT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Id_Rol` int(11) DEFAULT NULL,
  `Nit` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Documento`, `Nombre`, `Apellido`, `Correo`, `Contrasena`, `Id_Rol`, `Nit`) VALUES
('1016010239', 'juan', 'Vallejo', 'esteban@gmail.com', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', NULL, NULL),
('1016052623', 'Alejopro90', 'Vallejo', 'sssgh@gmail.com', '12345678', 3, '800123456-7'),
('123456789', 'Juan', 'Pérez', 'juanp@example.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 2, '800123456-7'),
('93399829', 'Gabypro90', 'Devia', 'deysyc@gmail.com', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 2, '800123456-7'),
('987654321', 'Ana', 'López', 'ana.lopez@example.com', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 1, '800123456-7');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`Id_Album`),
  ADD KEY `Documento` (`Documento`);

--
-- Indices de la tabla `carta`
--
ALTER TABLE `carta`
  ADD PRIMARY KEY (`CodigoBarras`);

--
-- Indices de la tabla `detallealbum`
--
ALTER TABLE `detallealbum`
  ADD PRIMARY KEY (`Id_Detalle`),
  ADD UNIQUE KEY `Id_Album` (`Id_Album`,`CodigoBarras`),
  ADD KEY `CodigoBarras` (`CodigoBarras`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`Nit`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`Id_Estado`);

--
-- Indices de la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD PRIMARY KEY (`Id_Licencia`),
  ADD KEY `Id_Tipo` (`Id_Tipo`),
  ADD KEY `Id_Estado` (`Id_Estado`),
  ADD KEY `Nit` (`Nit`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`Id_Rol`);

--
-- Indices de la tabla `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`Id_Tipo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Documento`),
  ADD UNIQUE KEY `Correo` (`Correo`),
  ADD KEY `Id_Rol` (`Id_Rol`),
  ADD KEY `Nit` (`Nit`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `album`
--
ALTER TABLE `album`
  MODIFY `Id_Album` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detallealbum`
--
ALTER TABLE `detallealbum`
  MODIFY `Id_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=689;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `Id_Estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `licencia`
--
ALTER TABLE `licencia`
  MODIFY `Id_Licencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `Id_Rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tipo`
--
ALTER TABLE `tipo`
  MODIFY `Id_Tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`Documento`) REFERENCES `usuario` (`Documento`);

--
-- Filtros para la tabla `detallealbum`
--
ALTER TABLE `detallealbum`
  ADD CONSTRAINT `detallealbum_ibfk_1` FOREIGN KEY (`Id_Album`) REFERENCES `album` (`Id_Album`),
  ADD CONSTRAINT `detallealbum_ibfk_2` FOREIGN KEY (`CodigoBarras`) REFERENCES `carta` (`CodigoBarras`);

--
-- Filtros para la tabla `licencia`
--
ALTER TABLE `licencia`
  ADD CONSTRAINT `licencia_ibfk_1` FOREIGN KEY (`Id_Tipo`) REFERENCES `tipo` (`Id_Tipo`),
  ADD CONSTRAINT `licencia_ibfk_2` FOREIGN KEY (`Id_Estado`) REFERENCES `estado` (`Id_Estado`),
  ADD CONSTRAINT `licencia_ibfk_3` FOREIGN KEY (`Nit`) REFERENCES `empresa` (`Nit`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Id_Rol`) REFERENCES `rol` (`Id_Rol`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`Nit`) REFERENCES `empresa` (`Nit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
