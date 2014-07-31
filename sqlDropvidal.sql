-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-03-2013 a las 10:53:43
-- Versión del servidor: 5.5.29
-- Versión de PHP: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `dropVidal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archiu`
--

CREATE TABLE IF NOT EXISTS `archiu` (
  `ID_ARCHIU` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_ARCHIU` varchar(50) NOT NULL,
  `NOM_BASE_ARCHIU` varchar(50) NOT NULL,
  `TIPUS_ARCHIU` varchar(10) NOT NULL,
  `ID_CARPETA` int(11) NOT NULL,
  `FECHA` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID_ARCHIU`),
  UNIQUE KEY `NOM_BASE_ARCHIU` (`NOM_BASE_ARCHIU`),
  KEY `ID_CARPETA` (`ID_CARPETA`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Volcado de datos para la tabla `archiu`
--

INSERT INTO `archiu` (`ID_ARCHIU`, `NOM_ARCHIU`, `NOM_BASE_ARCHIU`, `TIPUS_ARCHIU`, `ID_CARPETA`, `FECHA`) VALUES
(44, 'barney.png', '1364580178-barney.png', 'image/png', 71, '2013-03-29 18:02:58'),
(45, '63308_l.jpg', '1364580178-63308_l.jpg', 'image/jpeg', 71, '2013-03-29 18:02:59'),
(46, '170154702d.jpg', '1364580179-170154702d.jpg', 'image/jpeg', 71, '2013-03-29 18:02:59'),
(47, 'instantÃ¡nea1.png', '1364637120-instantÃ¡nea1.png', 'image/png', 70, '2013-03-30 09:52:00'),
(48, 'instantÃ¡nea2.png', '1364637121-instantÃ¡nea2.png', 'image/png', 70, '2013-03-30 09:52:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carpeta`
--

CREATE TABLE IF NOT EXISTS `carpeta` (
  `ID_CARPETA` int(11) NOT NULL AUTO_INCREMENT,
  `NOM_CARPETA` varchar(30) NOT NULL,
  `ID_USUARI` int(11) NOT NULL,
  `ID_CARPETA_FORANA` int(11) DEFAULT '0',
  PRIMARY KEY (`ID_CARPETA`),
  KEY `ID_CARPETA_FORANA` (`ID_CARPETA_FORANA`),
  KEY `ID_USUARI` (`ID_USUARI`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

--
-- Volcado de datos para la tabla `carpeta`
--

INSERT INTO `carpeta` (`ID_CARPETA`, `NOM_CARPETA`, `ID_USUARI`, `ID_CARPETA_FORANA`) VALUES
(70, 'soller92@gmail.com', 15, NULL),
(71, 'IMAGENES', 15, 70),
(72, 'JUEGOS', 15, 70),
(73, 'WALLPAPERS', 15, 71),
(74, 'FOTOS', 15, 71);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuari`
--

CREATE TABLE IF NOT EXISTS `usuari` (
  `ID_USUARI` int(11) NOT NULL AUTO_INCREMENT,
  `MAIL` varchar(50) NOT NULL,
  `NOM` varchar(20) NOT NULL,
  `COGNOMS` varbinary(50) NOT NULL,
  `IMAGEN` varchar(100) NOT NULL,
  `CONTRASENYA` varchar(50) NOT NULL,
  PRIMARY KEY (`ID_USUARI`),
  UNIQUE KEY `MAIL` (`MAIL`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `archiu`
--
ALTER TABLE `archiu`
  ADD CONSTRAINT `archiuCarpeta` FOREIGN KEY (`ID_CARPETA`) REFERENCES `carpeta` (`ID_CARPETA`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carpeta`
--
ALTER TABLE `carpeta`
  ADD CONSTRAINT `carpetaCarpeta` FOREIGN KEY (`ID_CARPETA_FORANA`) REFERENCES `carpeta` (`ID_CARPETA`) ON DELETE CASCADE,
  ADD CONSTRAINT `carpetaUSUARI` FOREIGN KEY (`ID_USUARI`) REFERENCES `usuari` (`ID_USUARI`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
