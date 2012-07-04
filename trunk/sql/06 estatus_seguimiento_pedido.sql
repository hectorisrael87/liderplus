-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-07-2012 a las 17:50:59
-- Versión del servidor: 5.1.58
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `liderplus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despacho`
--

DROP TABLE IF EXISTS `despacho`;
CREATE TABLE IF NOT EXISTS `despacho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura_empresa_transporte_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estatus_seguimiento_despacho_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura_empresa_transporte_id` (`factura_empresa_transporte_id`),
  KEY `estatus_seguimiento_despacho_id` (`estatus_seguimiento_despacho_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Estructura de tabla para la tabla `estatus_seguimiento_despacho`
--

DROP TABLE IF EXISTS `estatus_seguimiento_despacho`;
CREATE TABLE IF NOT EXISTS `estatus_seguimiento_despacho` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `estatus_seguimiento_despacho`
--

INSERT INTO `estatus_seguimiento_despacho` (`id`, `descripcion`) VALUES
(1, 'Despacho en Almacen'),
(2, 'Cargado'),
(3, 'En Ruta'),
(4, 'Entregado');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `despacho`
--
ALTER TABLE `despacho`
  ADD CONSTRAINT `despacho_ibfk_1` FOREIGN KEY (`factura_empresa_transporte_id`) REFERENCES `factura_empresa_transporte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `despacho_ibfk_2` FOREIGN KEY (`estatus_seguimiento_despacho_id`) REFERENCES `estatus_seguimiento_despacho` (`id`);
