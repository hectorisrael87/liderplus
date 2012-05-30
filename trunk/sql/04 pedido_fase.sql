-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 30-05-2012 a las 04:07:55
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
-- Estructura de tabla para la tabla `pedido_fase`
--

CREATE TABLE IF NOT EXISTS `pedido_fase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `fase_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `fase_id` (`fase_id`),
  KEY `pedido_id` (`pedido_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pedido_fase`
--


--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `pedido_fase`
--
ALTER TABLE `pedido_fase`
  ADD CONSTRAINT `pedido_fase_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `pedido_fase_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`),
  ADD CONSTRAINT `pedido_fase_ibfk_2` FOREIGN KEY (`fase_id`) REFERENCES `fase` (`id`);
