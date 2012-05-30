-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-05-2012 a las 03:18:59
-- Versión del servidor: 5.1.58
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `liderplus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_transporte`
--

DROP TABLE IF EXISTS `empresa_transporte`;
CREATE TABLE IF NOT EXISTS `empresa_transporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `rif` varchar(12) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `telefono_oficina` varchar(11) NOT NULL,
  `telefono_celular` varchar(11) NOT NULL,
  `persona_contacto` varchar(50) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `inactiva` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rif` (`rif`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `empresa_transporte`
--

INSERT INTO `empresa_transporte` (`id`, `nombre`, `rif`, `estado`, `ciudad`, `direccion`, `telefono_oficina`, `telefono_celular`, `persona_contacto`, `fecha_registro`, `inactiva`) VALUES
(4, 'Transporte Tanner C.A', 'J-29986681-4', 'Distrito Capital', 'Caracas', 'Calle El Progreso #09-11\r\nLas Acacias', '02126330015', '04245187562', 'Richard Rivas', '2012-05-29 20:27:00', 0),
(5, 'Transporte 2021, C.A', 'J-29986681-5', 'Bolivariano de Miranda', 'Los Teques', 'Redoma los tequeños.', '02819785655', '04126178042', 'Mirna Hernandez', '2012-05-29 20:29:44', 0);
