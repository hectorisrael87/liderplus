-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 18-06-2012 a las 05:28:56
-- Versión del servidor: 5.1.58
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `liderplus`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura_empresa_transporte`
--

CREATE TABLE IF NOT EXISTS `factura_empresa_transporte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura_id` int(11) NOT NULL,
  `empresa_transporte_id` int(11) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `factura_id` (`factura_id`),
  KEY `empresa_transporte_id` (`empresa_transporte_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `factura_empresa_transporte`
--
ALTER TABLE `factura_empresa_transporte`
  ADD CONSTRAINT `factura_empresa_transporte_ibfk_6` FOREIGN KEY (`empresa_transporte_id`) REFERENCES `empresa_transporte` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `factura_empresa_transporte_ibfk_5` FOREIGN KEY (`factura_id`) REFERENCES `factura` (`id`) ON UPDATE NO ACTION;
