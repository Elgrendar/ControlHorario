-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 12-02-2021 a las 17:34:56
-- Versión del servidor: 8.0.23-0ubuntu0.20.04.1
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `controlhorario`
--
CREATE DATABASE IF NOT EXISTS `controlhorario` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `controlhorario`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencias`
--

DROP TABLE IF EXISTS `ausencias`;
CREATE TABLE IF NOT EXISTS `ausencias` (
  `idAusencia` int NOT NULL AUTO_INCREMENT,
  `idUsuario` int NOT NULL,
  `idSolicitud` int DEFAULT NULL,
  `f_inicio` date NOT NULL,
  `f_fin` date DEFAULT NULL,
  `causa` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idAusencia`),
  KEY `FK_Usuario-ausencia` (`idUsuario`),
  KEY `FK_solicitud-ausencia` (`idSolicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

DROP TABLE IF EXISTS `documentos`;
CREATE TABLE IF NOT EXISTS `documentos` (
  `idDocumento` int NOT NULL AUTO_INCREMENT,
  `idAusencia` int NOT NULL,
  `nombre` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idDocumento`),
  KEY `FK_documentosAusencia` (`idAusencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE IF NOT EXISTS `mensajes` (
  `idMensaje` int NOT NULL AUTO_INCREMENT,
  `idOrigen` int NOT NULL,
  `idDestinatario` int NOT NULL,
  `asunto` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `mensaje` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT '0',
  `fechaEnvio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechaLectura` datetime DEFAULT NULL,
  PRIMARY KEY (`idMensaje`),
  KEY `FK_Origen` (`idOrigen`),
  KEY `FK_Destinatario` (`idDestinatario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

DROP TABLE IF EXISTS `registros`;
CREATE TABLE IF NOT EXISTS `registros` (
  `idRegistro` int NOT NULL AUTO_INCREMENT,
  `idUsuario` int NOT NULL,
  `hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `observaciones` varchar(128) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`idRegistro`),
  KEY `FK_Usuario_Registro` (`idUsuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `idRol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Responsable RRHH'),
(3, 'Jefe Departamento'),
(4, 'Trabajador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

DROP TABLE IF EXISTS `solicitudes`;
CREATE TABLE IF NOT EXISTS `solicitudes` (
  `idSolicitud` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NOT NULL,
  `tipo` enum('Vacaciones','Compensación','Asuntos Propios','Otros') CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Vacaciones',
  `f_inicio` date NOT NULL,
  `f_fin` date NOT NULL,
  `aprobado_departamento` tinyint(1) NOT NULL DEFAULT '0',
  `aprobado_rrhh` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idSolicitud`),
  KEY `FK_Usuario-Solicitud` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariorol`
--

DROP TABLE IF EXISTS `usuariorol`;
CREATE TABLE IF NOT EXISTS `usuariorol` (
  `idusuarioRol` int NOT NULL AUTO_INCREMENT,
  `idusuario` int NOT NULL,
  `idRol` int NOT NULL,
  `permitido` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idusuarioRol`),
  KEY `FK_Usuario` (`idusuario`),
  KEY `FK_Rol` (`idRol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuariorol`
--

INSERT INTO `usuariorol` (`idusuarioRol`, `idusuario`, `idRol`, `permitido`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 1),
(3, 1, 3, 1),
(4, 1, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `idUsuario` int NOT NULL AUTO_INCREMENT,
  `dni` varchar(9) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `f_alta` date NOT NULL,
  `f_baja` date DEFAULT NULL,
  `nombre` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `apellido1` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `apellido2` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `sexo` enum('Sin definir','Hombre','Mujer') CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Sin definir',
  `f_nacimiento` date DEFAULT NULL,
  `tarjeta` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `foto` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'sinimagen.png',
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `trabajando` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(256) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `idResponsable` int DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  UNIQUE KEY `email` (`email`),
  KEY `FK_Responsable` (`idResponsable`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `dni`, `f_alta`, `f_baja`, `nombre`, `apellido1`, `apellido2`, `sexo`, `f_nacimiento`, `tarjeta`, `foto`, `email`, `trabajando`, `password`, `idResponsable`) VALUES
(1, NULL, '2009-12-25', NULL, 'Administrador', 'Administrador', NULL, 'Sin definir', NULL, NULL, 'sinimagen.png', 'admin@localhost.com', 0, '$2y$10$dwz3aOpluh6vMoxIGeSxfuxP0zNFQAUYO1CsoRl1krrI9OkB7r5Qi', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `versiones`
--

DROP TABLE IF EXISTS `versiones`;
CREATE TABLE IF NOT EXISTS `versiones` (
  `idVersiones` int NOT NULL AUTO_INCREMENT,
  `versionPrograma` varchar(11) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `versionBBDD` varchar(11) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `pluginreq` longtext CHARACTER SET utf8 COLLATE utf8_spanish2_ci,
  `fechaPublicacion` date DEFAULT NULL,
  PRIMARY KEY (`idVersiones`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `versiones`
--

INSERT INTO `versiones` (`idVersiones`, `versionPrograma`, `versionBBDD`, `pluginreq`, `fechaPublicacion`) VALUES
(1, '0.1.1', '0.1.1', NULL, '2021-02-12');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ausencias`
--
ALTER TABLE `ausencias`
  ADD CONSTRAINT `FK_solicitud-ausencia` FOREIGN KEY (`idSolicitud`) REFERENCES `solicitudes` (`idSolicitud`),
  ADD CONSTRAINT `FK_Usuario-ausencia` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `FK_documentosAusencia` FOREIGN KEY (`idAusencia`) REFERENCES `ausencias` (`idAusencia`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `FK_Destinatario` FOREIGN KEY (`idDestinatario`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `FK_Origen` FOREIGN KEY (`idOrigen`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `FK_Usuario_Registro` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `FK_Usuario-Solicitud` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD CONSTRAINT `FK_Rol` FOREIGN KEY (`idRol`) REFERENCES `roles` (`idRol`),
  ADD CONSTRAINT `FK_Usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `FK_Responsable` FOREIGN KEY (`idResponsable`) REFERENCES `usuarios` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
