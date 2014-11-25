-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 25-11-2014 a las 19:17:57
-- Versión del servidor: 5.6.12-log
-- Versión de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `dbtienda2`
--
CREATE DATABASE IF NOT EXISTS `dbtienda2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dbtienda2`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `up_AgregarStockProducto`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `up_AgregarStockProducto`(vIdSucursal int, 

vIdProducto int, vIdUnidad int, vStock 

numeric(10,2), vIdMovimiento int, vMoneda 

char(1),	vPrecioUnidad numeric(10,2), 

vFecha datetime, vIdUsuario int)
BEGIN
DECLARE vIdUnidadBase INT;
DECLARE vFormula numeric(18,2);
DECLARE vStockBase numeric(18,2);
DECLARE vcount INT;
DECLARE vSaldoAnterior numeric(18,2);
DECLARE vNuevoSaldo numeric(18,2);
DECLARE vIdKardex INT;
DECLARE vImporte numeric(18,2);
DECLARE vEstado char(1);

SET vIdUnidadBase=(SELECT IdUnidadBase FROM 

Producto WHERE IdProducto = vIdProducto);
SET vFormula=(SELECT Formula FROM 

ListaUnidad WHERE IdProducto = vIdProducto 

AND IdUnidad = vIdUnidad AND IdUnidadBase = 

vIdUnidadBase);
SET vStockBase=vStock*vFormula;

SET vcount=(SELECT CASE WHEN COUNT(*) IS 

NULL THEN 0 ELSE COUNT(*) END FROM 

StockProducto WHERE IdProducto = vIdProducto 

AND IdUnidad = vIdUnidad AND IdSucursal = 

vIdSucursal);
IF vcount = 0 THEN
INSERT INTO StockProducto(	

IdStockProducto, IdSucursal, IdProducto, 

IdUnidad, Stock, IdUnidadBase, StockBase) 

VALUES (NULL, vIdSucursal, vIdProducto, 

vIdUnidad, vStock, vIdUnidadBase, vStockBase);


ELSE
	UPDATE StockProducto SET Stock = 

Stock + vStock, StockBase = StockBase + 

vStockBase WHERE IdProducto = vIdProducto 

AND IdUnidad = vIdUnidad AND IdSucursal = 

vIdSucursal;
END IF;

SET vSaldoAnterior = (SELECT SaldoActual 

FROM Kardex WHERE  IdProducto = vIdProducto 

AND IdSucursal = vIdSucursal AND Ultimo = 

'S' ORDER BY IdKardex DESC LIMIT 0,1);
SET vIdKardex = (SELECT IdKardex FROM Kardex 

WHERE  IdProducto = vIdProducto AND 

IdSucursal = vIdSucursal AND Ultimo = 'S' 

ORDER BY IdKardex DESC LIMIT 0,1);

IF vSaldoAnterior IS NULL THEN
	SET vSaldoAnterior = 0;
	SET vIdKardex = 0;
END IF;

UPDATE Kardex SET Ultimo = 'N' WHERE 

IdKardex = vIdKardex;
SET vNuevoSaldo = vSaldoAnterior +  

vStockBase;
SET vImporte = vStock*vPrecioUnidad;
IF SUBSTR(vStock,1,1)= '-' THEN
SET vImporte=-vImporte;
SET vStock=-vStock;
SET vStockBase=-vStockBase;
END IF;
IF (SELECT Estado FROM Movimiento WHERE 

IdMovimiento=vIdMovimiento)='A' THEN
SET vEstado='A';
ELSE 
SET vEstado='N';
END IF;

INSERT INTO KARDEX(IdKardex,
	IdSucursal,
	IdMovimiento,
	IdProducto,
	IdUnidad,
	Cantidad,
	TipoMoneda,
	PrecioUnidad,
	Importe,
	Fecha,
	SaldoAnteriorBase,
	IdUnidadBase,
	CantidadBase,
	SaldoActual,
	IdUsuario,
	Ultimo,
	Estado) VALUES(NULL,
	vIdSucursal,
	vIdMovimiento,
	vIdProducto,
	vIdUnidad,
	vStock,
	vMoneda,
	vPrecioUnidad,
	vImporte,
	vFecha,
	vSaldoAnterior,
	vIdUnidadBase,
	vStockBase,
	vNuevoSaldo,
	vIdUsuario,
	'S',
	vEstado);
END$$

DROP PROCEDURE IF EXISTS `up_BuscarCategoriaProductoArbol`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `up_BuscarCategoriaProductoArbol`()
BEGIN 
Declare vMaxNivel int;
Declare vNivel int;

CREATE TEMPORARY TABLE Categoria_Temp 
(
IdCategoria Int,
Descripcion Varchar( 50 ) ,
IdCategoriaRef Int,
Nivel Int,
Secuencia Int AUTO_INCREMENT PRIMARY KEY ,
CodigoOrden Varchar( 50 ) ,
Abreviatura Varchar( 10 ),
Estado Varchar( 1 )
);

CREATE TEMPORARY TABLE Categoria_Temp2 
(
IdCategoria Int,
Descripcion Varchar( 50 ) ,
IdCategoriaRef Int,
Nivel Int,
Secuencia Int AUTO_INCREMENT PRIMARY KEY ,
CodigoOrden Varchar( 50 ) ,
Abreviatura Varchar( 10 ),
Estado Varchar( 1 )
);

Insert Into Categoria_Temp(IdCategoria, Descripcion, IdCategoriaRef, Nivel, Estado,Abreviatura) Select IdCategoria, Descripcion, IdCategoriaRef, Nivel,Estado,Abreviatura From Categoria Where Nivel=1;
Update Categoria_Temp Set CodigoOrden=Secuencia;

Insert Into Categoria_Temp2(IdCategoria, Descripcion, IdCategoriaRef, Nivel,Estado,Abreviatura) Select IdCategoria, Descripcion, IdCategoriaRef, Nivel,Estado,Abreviatura From Categoria Where Nivel=1;
Update Categoria_Temp2 Set CodigoOrden=Secuencia;

Set vMaxNivel=(Select Max(Nivel) From Categoria);
Set vNivel=2;
While vNivel<=vMaxNivel Do
		Insert Into Categoria_Temp(IdCategoria, Descripcion, IdCategoriaRef, Nivel, CodigoOrden,Estado,Abreviatura) 
		Select TP.IdCategoria, CONCAT(SPACE(vNivel*2),TP.Descripcion), TP.IdCategoriaRef, TP.Nivel, CONCAT(Categoria_Temp2.CodigoOrden,'-',TP.Nivel),TP.Estado,TP.Abreviatura
		From Categoria TP Inner Join Categoria_Temp2 ON TP.IdCategoriaRef = Categoria_Temp2.IdCategoria
		Where TP.Nivel=vNivel;

		Update Categoria_Temp Set CodigoOrden= CONCAT(CodigoOrden,Secuencia) Where Nivel=vNivel;
                                Truncate Categoria_Temp2;
                Insert Into Categoria_Temp2 Select * From Categoria_Temp;

		Set vNivel = vNivel + 1;
End While;

SELECT tem.IdCategoria,tem.Descripcion,tem.IdCategoriaRef,tem.Nivel,tem.Estado,tem.Abreviatura,tem.CodigoOrden,categoria.Descripcion as DescripcionRef FROM(Select * From Categoria_Temp WHERE Estado='N' Order By CodigoOrden Asc)as tem LEFT JOIN categoria ON tem.IdCategoriaRef=categoria.IdCategoria;

DROP TEMPORARY Table Categoria_Temp;
DROP TEMPORARY table Categoria_Temp2;
End$$

--
-- Funciones
--
DROP FUNCTION IF EXISTS `obtenerStock`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `obtenerStock`(vIdProducto int,vIdUnidad int,vIdSucursal int) RETURNS decimal(16,2)
    READS SQL DATA
    DETERMINISTIC
BEGIN 
	DECLARE vStockBase numeric(16,2);
	DECLARE vIdUnidadBase INT;
	DECLARE vFormula numeric(18,2);
	DECLARE vStock numeric(18,2);

	SET vStockBase= (SELECT SUM(StockBase) FROM StockProducto WHERE  IdProducto = vIdProducto AND IdSucursal = vIdSucursal GROUP BY IdProducto);
	SET vIdUnidadBase=(SELECT IdUnidadBase FROM Producto WHERE IdProducto = vIdProducto);
	SET vFormula=(SELECT Formula FROM ListaUnidad WHERE IdProducto = vIdProducto AND IdUnidad = vIdUnidad AND IdUnidadBase = vIdUnidadBase);
	SET vStock = vStockBase / vFormula;
	RETURN CASE WHEN vStock IS NULL THEN 0 ELSE vStock END;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acceso`
--

DROP TABLE IF EXISTS `acceso`;
CREATE TABLE IF NOT EXISTS `acceso` (
  `IdAcceso` int(11) NOT NULL AUTO_INCREMENT,
  `IdTipoUsuario` int(11) NOT NULL,
  `IdOpcionMenu` int(11) NOT NULL,
  PRIMARY KEY (`IdAcceso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=75 ;

--
-- Volcado de datos para la tabla `acceso`
--

INSERT INTO `acceso` (`IdAcceso`, `IdTipoUsuario`, `IdOpcionMenu`) VALUES
(7, 1, 1),
(10, 1, 2),
(11, 1, 3),
(12, 1, 4),
(13, 1, 5),
(14, 1, 6),
(15, 1, 7),
(16, 1, 8),
(18, 1, 10),
(19, 1, 11),
(20, 1, 12),
(21, 1, 13),
(22, 1, 14),
(23, 1, 15),
(24, 1, 9),
(25, 2, 9),
(26, 1, 16),
(27, 1, 17),
(28, 1, 19),
(29, 1, 18),
(30, 2, 18),
(31, 1, 22),
(32, 1, 20),
(33, 1, 21),
(34, 1, 23),
(35, 1, 24),
(36, 1, 25),
(37, 1, 26),
(38, 1, 27),
(39, 1, 28),
(40, 3, 16),
(41, 3, 4),
(42, 2, 4),
(43, 2, 11),
(44, 2, 15),
(45, 2, 27),
(46, 2, 2),
(47, 2, 16),
(48, 2, 7),
(49, 2, 19),
(50, 2, 26),
(51, 2, 24),
(52, 2, 23),
(53, 2, 25),
(54, 2, 21),
(55, 3, 2),
(56, 1, 29),
(57, 1, 30),
(58, 1, 31),
(59, 1, 32),
(60, 1, 33),
(61, 1, 35),
(62, 1, 34),
(64, 6, 2),
(65, 6, 5),
(66, 6, 7),
(70, 6, 8),
(71, 6, 9),
(74, 6, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armario`
--

DROP TABLE IF EXISTS `armario`;
CREATE TABLE IF NOT EXISTS `armario` (
  `IdArmario` int(11) NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Nombre` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `TotalColumnas` int(11) NOT NULL,
  `TotalFilas` int(11) NOT NULL,
  PRIMARY KEY (`IdArmario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `armario`
--

INSERT INTO `armario` (`IdArmario`, `Codigo`, `Nombre`, `TotalColumnas`, `TotalFilas`) VALUES
(1, '001', 'ARMARIO1', 5, 5),
(2, 'ARMAR', 'ARMARIO2', 4, 8),
(3, '002', 'ARMARIO 3', 4, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
CREATE TABLE IF NOT EXISTS `bitacora` (
  `IdBitacora` int(11) NOT NULL AUTO_INCREMENT,
  `IdMovimiento` int(11) NOT NULL,
  `FechaHora` datetime NOT NULL,
  `Comentario` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Idusuario` int(11) NOT NULL,
  `Operacion` varchar(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N' COMMENT 'N->Nuevo,A->Anulacion',
  `IdSucursal` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IdBitacora`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`IdBitacora`, `IdMovimiento`, `FechaHora`, `Comentario`, `Idusuario`, `Operacion`, `IdSucursal`) VALUES
(1, 3, '2014-11-25 00:00:00', 'Cierre de Caja del 2011-12-17', 3, 'N', 1),
(2, 5, '2014-11-25 00:00:00', 'Apertura de Caja del 2014-11-25', 3, 'N', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `IdCategoria` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Abreviatura` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `IdCategoriaRef` int(11) DEFAULT NULL,
  `Nivel` int(11) DEFAULT '1',
  `Estado` char(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`IdCategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`IdCategoria`, `Descripcion`, `Abreviatura`, `IdCategoriaRef`, `Nivel`, `Estado`) VALUES
(1, 'CAMISAS', 'CA', NULL, 1, 'N'),
(2, 'POLOS', 'PO', NULL, 1, 'N'),
(3, 'ZAPATOS', 'ZA', NULL, 1, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `color`
--

DROP TABLE IF EXISTS `color`;
CREATE TABLE IF NOT EXISTS `color` (
  `IdColor` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Codigo` varchar(7) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdColor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `color`
--

INSERT INTO `color` (`IdColor`, `Nombre`, `Codigo`, `Estado`) VALUES
(1, 'BLANCO', '#FFFFFF', 'N'),
(2, 'NEGRO', '#000000', 'N'),
(3, 'VERDE', '#004000', 'N'),
(4, 'AMARILLO', '', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptopago`
--

DROP TABLE IF EXISTS `conceptopago`;
CREATE TABLE IF NOT EXISTS `conceptopago` (
  `IdConceptoPago` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Tipo` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'I->Ingreso,E->Egreso',
  PRIMARY KEY (`IdConceptoPago`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Solo para el caso de Caja' AUTO_INCREMENT=17 ;

--
-- Volcado de datos para la tabla `conceptopago`
--

INSERT INTO `conceptopago` (`IdConceptoPago`, `Descripcion`, `Tipo`) VALUES
(1, 'APERTURA DE CAJA', 'I'),
(2, 'CIERRE DE CAJA', 'E'),
(3, 'PAGO DE CLIENTE', 'I'),
(4, 'PAGO A PROVEEDOR', 'E'),
(5, 'POR ANULACION DE VENTA', 'E'),
(6, 'POR ANULACION DE COMPRA', 'I'),
(7, 'PAGO A PERSONAL', 'E'),
(8, 'PARA COMPRA DOLARES', 'E'),
(9, 'POR COMPRA DOLARES', 'I'),
(10, 'AJUSTE TIPO CAMBIO', 'I'),
(11, 'AJUSTE TIPO CAMBIO', 'E'),
(12, 'PRESTAMO', 'I'),
(13, 'DEVOLUCION PRESTAMO', 'E'),
(14, 'PRESTAMO', 'E'),
(15, 'DEVOLUCION PRESTAMO', 'I'),
(16, 'DEPOSITO', 'E');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuota`
--

DROP TABLE IF EXISTS `cuota`;
CREATE TABLE IF NOT EXISTS `cuota` (
  `IdCuota` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `IdMovimiento` int(11) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  `FechaCancelacion` datetime NOT NULL,
  `FechaPago` datetime NOT NULL,
  `Moneda` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S' COMMENT 'S->Soles, D->D?lares.',
  `Monto` decimal(10,2) NOT NULL,
  `Interes` decimal(10,2) NOT NULL,
  `MontoPagado` decimal(10,2) NOT NULL,
  `InteresPagado` decimal(10,2) NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N' COMMENT 'N->Normal,C->Cancelada (Pagada), A->Anulada.',
  PRIMARY KEY (`IdCuota`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `cuota`
--

INSERT INTO `cuota` (`IdCuota`, `Nombre`, `IdMovimiento`, `FechaCreacion`, `FechaCancelacion`, `FechaPago`, `Moneda`, `Monto`, `Interes`, `MontoPagado`, `InteresPagado`, `Estado`) VALUES
(1, '1', 6, '2011-01-20 00:00:00', '2011-02-20 00:00:00', '2011-01-25 00:00:00', 'S', '100.00', '0.00', '100.00', '0.00', 'C'),
(2, '2', 6, '2011-01-20 00:00:00', '2011-03-20 00:00:00', '0000-00-00 00:00:00', 'S', '100.00', '0.00', '0.00', '0.00', 'N'),
(3, '1', 11, '2011-01-20 00:00:00', '2011-02-20 00:00:00', '0000-00-00 00:00:00', 'S', '139.70', '0.00', '0.00', '0.00', 'N'),
(4, '1', 12, '2011-01-20 00:00:00', '2011-02-20 00:00:00', '0000-00-00 00:00:00', 'S', '62.70', '0.00', '0.00', '0.00', 'N'),
(5, '1', 41, '2011-01-26 00:00:00', '2011-02-26 00:00:00', '2011-04-27 00:00:00', 'S', '70.00', '0.00', '70.00', '0.00', 'C'),
(6, '1', 53, '2011-01-30 00:00:00', '2011-02-28 00:00:00', '0000-00-00 00:00:00', 'S', '70.00', '0.00', '0.00', '0.00', 'N'),
(7, '1', 129, '2011-04-25 00:00:00', '2011-05-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N'),
(8, '2', 129, '2011-04-25 00:00:00', '2011-06-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N'),
(9, '3', 129, '2011-04-25 00:00:00', '2011-07-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N'),
(10, '4', 129, '2011-04-25 00:00:00', '2011-08-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

DROP TABLE IF EXISTS `departamento`;
CREATE TABLE IF NOT EXISTS `departamento` (
  `IdDepartamento` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `IdPais` int(11) NOT NULL,
  `Codigo` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`IdDepartamento`),
  KEY `XIF1DEPARTAMENTO` (`IdPais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=26 ;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`IdDepartamento`, `Descripcion`, `IdPais`, `Codigo`) VALUES
(1, 'AMAZONAS', 1, '01'),
(2, 'ANCASH', 1, '02'),
(3, 'APURIMAC', 1, '03'),
(4, 'AREQUIPA', 1, '04'),
(5, 'AYACUCHO', 1, '05'),
(6, 'CAJAMARCA', 1, '06'),
(7, 'CALLAO', 1, '07'),
(8, 'CUSCO', 1, '08'),
(9, 'HUANCAVELICA', 1, '09'),
(10, 'HUANUCO', 1, '10'),
(11, 'ICA', 1, '11'),
(12, 'JUNIN', 1, '12'),
(13, 'LA LIBERTAD', 1, '13'),
(14, 'LAMBAYEQUE', 1, '14'),
(15, 'LIMA', 1, '15'),
(16, 'LORETO', 1, '16'),
(17, 'MADRE DE DIOS', 1, '17'),
(18, 'MOQUEGUA', 1, '18'),
(19, 'PASCO', 1, '19'),
(20, 'PIURA', 1, '20'),
(21, 'PUNO', 1, '21'),
(22, 'SAN MARTIN', 1, '22'),
(23, 'TACNA', 1, '23'),
(24, 'TUMBES', 1, '24'),
(25, 'UCAYALI', 1, '25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallemovalmacen`
--

DROP TABLE IF EXISTS `detallemovalmacen`;
CREATE TABLE IF NOT EXISTS `detallemovalmacen` (
  `IdDetalleMovAlmacen` int(11) NOT NULL AUTO_INCREMENT,
  `IdMovimiento` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `Cantidad` decimal(10,2) NOT NULL,
  `PrecioCompra` decimal(10,2) NOT NULL,
  `PrecioVenta` decimal(10,2) NOT NULL,
  PRIMARY KEY (`IdDetalleMovAlmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallemovcaja`
--

DROP TABLE IF EXISTS `detallemovcaja`;
CREATE TABLE IF NOT EXISTS `detallemovcaja` (
  `IdDetalleMovCaja` int(11) NOT NULL AUTO_INCREMENT,
  `IdMovimiento` int(11) NOT NULL,
  `IdCuota` int(11) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Interes` decimal(10,2) NOT NULL,
  `Moneda` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S' COMMENT 'S->Soles, D->D?lares.',
  `TipoCambio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`IdDetalleMovCaja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distrito`
--

DROP TABLE IF EXISTS `distrito`;
CREATE TABLE IF NOT EXISTS `distrito` (
  `IdDistrito` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` char(40) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Codigo` varchar(2) CHARACTER SET utf8 COLLATE utf8_latvian_ci DEFAULT NULL,
  `IdProvincia` int(11) NOT NULL,
  PRIMARY KEY (`IdDistrito`),
  KEY `XIF3DISTRITO` (`IdProvincia`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1829 ;

--
-- Volcado de datos para la tabla `distrito`
--

INSERT INTO `distrito` (`IdDistrito`, `Descripcion`, `Codigo`, `IdProvincia`) VALUES
(1, 'CHACHAPOYAS', '01', 1),
(2, 'ASUNCION', '02', 1),
(3, 'BALSAS', '03', 1),
(4, 'CHETO', '04', 1),
(5, 'CHILIQUIN', '05', 1),
(6, 'CHUQUIBAMBA', '06', 1),
(7, 'GRANADA', '07', 1),
(8, 'HUANCAS', '08', 1),
(9, 'LA JALCA', '09', 1),
(10, 'LEIMEBAMBA', '10', 1),
(11, 'LEVANTO', '11', 1),
(12, 'MAGDALENA', '12', 1),
(13, 'MARISCAL CASTILLA', '13', 1),
(14, 'MOLINOPAMPA', '14', 1),
(15, 'MONTEVIDEO', '15', 1),
(16, 'OLLEROS', '16', 1),
(17, 'QUINJALCA', '17', 1),
(18, 'SAN FRANCISCO DE DAGUAS', '18', 1),
(19, 'SAN ISIDRO DE MAINO', '19', 1),
(20, 'SOLOCO', '20', 1),
(21, 'SONCHE', '21', 1),
(22, 'LA PECA', '01', 2),
(23, 'ARAMANGO', '02', 2),
(24, 'COPALLIN', '03', 2),
(25, 'EL PARCO', '04', 2),
(26, 'IMAZA', '05', 2),
(27, 'JUMBILLA', '01', 3),
(28, 'CHISQUILLA', '02', 3),
(29, 'CHURUJA', '03', 3),
(30, 'COROSHA', '04', 3),
(31, 'CUISPES', '05', 3),
(32, 'FLORIDA', '06', 3),
(33, 'JAZAN', '07', 3),
(34, 'RECTA', '08', 3),
(35, 'SAN CARLOS', '09', 3),
(36, 'SHIPASBAMBA', '10', 3),
(37, 'VALERA', '11', 3),
(38, 'YAMBRASBAMBA', '12', 3),
(39, 'NIEVA', '01', 4),
(40, 'EL CENEPA', '02', 4),
(41, 'RIO SANTIAGO', '03', 4),
(42, 'LAMUD', '01', 5),
(43, 'CAMPORREDONDO', '02', 5),
(44, 'COCABAMBA', '03', 5),
(45, 'COLCAMAR', '04', 5),
(46, 'CONILA', '05', 5),
(47, 'INGUILPATA', '06', 5),
(48, 'LONGUITA', '07', 5),
(49, 'LONYA CHICO', '08', 5),
(50, 'LUYA', '09', 5),
(51, 'LUYA VIEJO', '10', 5),
(52, 'MARIA', '11', 5),
(53, 'OCALLI', '12', 5),
(54, 'OCUMAL', '13', 5),
(55, 'PISUQUIA', '14', 5),
(56, 'PROVIDENCIA', '15', 5),
(57, 'SAN CRISTOBAL', '16', 5),
(58, 'SAN FRANCISCO DEL YESO', '17', 5),
(59, 'SAN JERONIMO', '18', 5),
(60, 'SAN JUAN DE LOPECANCHA', '19', 5),
(61, 'SANTA CATALINA', '20', 5),
(62, 'SANTO TOMAS', '21', 5),
(63, 'TINGO', '22', 5),
(64, 'TRITA', '23', 5),
(65, 'SAN NICOLAS', '01', 6),
(66, 'CHIRIMOTO', '02', 6),
(67, 'COCHAMAL', '03', 6),
(68, 'HUAMBO', '04', 6),
(69, 'LIMABAMBA', '05', 6),
(70, 'LONGAR', '06', 6),
(71, 'MARISCAL BENAVIDES', '07', 6),
(72, 'MILPUC', '08', 6),
(73, 'OMIA', '09', 6),
(74, 'SANTA ROSA', '10', 6),
(75, 'TOTORA', '11', 6),
(76, 'VISTA ALEGRE', '12', 6),
(77, 'BAGUA GRANDE', '01', 7),
(78, 'CAJARURO', '02', 7),
(79, 'CUMBA', '03', 7),
(80, 'EL MILAGRO', '04', 7),
(81, 'JAMALCA', '05', 7),
(82, 'LONYA GRANDE', '06', 7),
(83, 'YAMON', '07', 7),
(84, 'HUANUCO', '01', 88),
(85, 'AMARILIS', '02', 88),
(86, 'CHINCHAO', '03', 88),
(87, 'CHURUBAMBA', '04', 88),
(88, 'MARGOS', '05', 88),
(89, 'QUISQUI', '06', 88),
(90, 'SAN FRANCISCO DE CAYRAN', '07', 88),
(91, 'SAN PEDRO DE CHAULAN', '08', 88),
(92, 'SANTA MARIA DEL VALLE', '09', 88),
(93, 'YARUMAYO', '10', 88),
(94, 'PILLCO MARCA', '11', 88),
(95, 'AMBO', '01', 89),
(96, 'CAYNA', '02', 89),
(97, 'COLPAS', '03', 89),
(98, 'CONCHAMARCA', '04', 89),
(99, 'HUACAR', '05', 89),
(100, 'SAN FRANCISCO', '06', 89),
(101, 'SAN RAFAEL', '07', 89),
(102, 'TOMAY KICHWA', '08', 89),
(103, 'LA UNION', '01', 90),
(104, 'CHUQUIS', '07', 90),
(105, 'MARIAS', '11', 90),
(106, 'PACHAS', '13', 90),
(107, 'QUIVILLA', '16', 90),
(108, 'RIPAN', '17', 90),
(109, 'SHUNQUI', '21', 90),
(110, 'SILLAPATA', '22', 90),
(111, 'YANAS', '23', 90),
(112, 'HUACAYBAMBA', '01', 91),
(113, 'CANCHABAMBA', '02', 91),
(114, 'COCHABAMBA', '03', 91),
(115, 'PINRA', '04', 91),
(116, 'LLATA', '01', 92),
(117, 'ARANCAY', '02', 92),
(118, 'CHAVIN DE PARIARCA', '03', 92),
(119, 'JACAS GRANDE', '04', 92),
(120, 'JIRCAN', '05', 92),
(121, 'MIRAFLORES', '06', 92),
(122, 'MONZON', '07', 92),
(123, 'PUNCHAO', '08', 92),
(124, 'PU?OS', '09', 92),
(125, 'SINGA', '10', 92),
(126, 'TANTAMAYO', '11', 92),
(127, 'RUPA-RUPA', '01', 93),
(128, 'DANIEL ALOMIA ROBLES', '02', 93),
(129, 'HERMILIO VALDIZAN', '03', 93),
(130, 'JOSE CRESPO Y CASTILLO', '04', 93),
(131, 'LUYANDO', '05', 93),
(132, 'MARIANO DAMASO BERAUN', '06', 93),
(133, 'HUACRACHUCO', '01', 94),
(134, 'CHOLON', '02', 94),
(135, 'SAN BUENAVENTURA', '03', 94),
(136, 'PANAO', '01', 95),
(137, 'CHAGLLA', '02', 95),
(138, 'MOLINO', '03', 95),
(139, 'UMARI', '04', 95),
(140, 'PUERTO INCA', '01', 96),
(141, 'CODO DEL POZUZO', '02', 96),
(142, 'HONORIA', '03', 96),
(143, 'TOURNAVISTA', '04', 96),
(144, 'YUYAPICHIS', '05', 96),
(145, 'JESUS', '01', 97),
(146, 'BA?OS', '02', 97),
(147, 'JIVIA', '03', 97),
(148, 'QUEROPALCA', '04', 97),
(149, 'RONDOS', '05', 97),
(150, 'SAN FRANCISCO DE ASIS', '06', 97),
(151, 'SAN MIGUEL DE CAURI', '07', 97),
(152, 'CHAVINILLO', '01', 98),
(153, 'CAHUAC', '02', 98),
(154, 'CHACABAMBA', '03', 98),
(155, 'APARICIO POMARES', '04', 98),
(156, 'JACAS CHICO', '05', 98),
(157, 'OBAS', '06', 98),
(158, 'PAMPAMARCA', '07', 98),
(159, 'CHORAS', '08', 98),
(160, 'ICA', '01', 99),
(161, 'LA TINGUI?A', '02', 99),
(162, 'LOS AQUIJES', '03', 99),
(163, 'OCUCAJE', '04', 99),
(164, 'PACHACUTEC', '05', 99),
(165, 'PARCONA', '06', 99),
(166, 'PUEBLO NUEVO', '07', 99),
(167, 'SALAS', '08', 99),
(168, 'SAN JOSE DE LOS MOLINOS', '09', 99),
(169, 'SAN JUAN BAUTISTA', '10', 99),
(170, 'SANTIAGO', '11', 99),
(171, 'SUBTANJALLA', '12', 99),
(172, 'TATE', '13', 99),
(173, 'YAUCA DEL ROSARIO', '14', 99),
(174, 'CHINCHA ALTA', '01', 100),
(175, 'ALTO LARAN', '02', 100),
(176, 'CHAVIN', '03', 100),
(177, 'CHINCHA BAJA', '04', 100),
(178, 'EL CARMEN', '05', 100),
(179, 'GROCIO PRADO', '06', 100),
(180, 'PUEBLO NUEVO', '07', 100),
(181, 'SAN JUAN DE YANAC', '08', 100),
(182, 'SAN PEDRO DE HUACARPANA', '09', 100),
(183, 'SUNAMPE', '10', 100),
(184, 'TAMBO DE MORA', '11', 100),
(185, 'NAZCA', '01', 101),
(186, 'CHANGUILLO', '02', 101),
(187, 'EL INGENIO', '03', 101),
(188, 'MARCONA', '04', 101),
(189, 'VISTA ALEGRE', '05', 101),
(190, 'PALPA', '01', 102),
(191, 'LLIPATA', '02', 102),
(192, 'RIO GRANDE', '03', 102),
(193, 'SANTA CRUZ', '04', 102),
(194, 'TIBILLO', '05', 102),
(195, 'PISCO', '01', 103),
(196, 'HUANCANO', '02', 103),
(197, 'HUMAY', '03', 103),
(198, 'INDEPENDENCIA', '04', 103),
(199, 'PARACAS', '05', 103),
(200, 'SAN ANDRES', '06', 103),
(201, 'SAN CLEMENTE', '07', 103),
(202, 'TUPAC AMARU INCA', '08', 103),
(203, 'HUANCAYO', '01', 104),
(204, 'CARHUACALLANGA', '04', 104),
(205, 'CHACAPAMPA', '05', 104),
(206, 'CHICCHE', '06', 104),
(207, 'CHILCA', '07', 104),
(208, 'CHONGOS ALTO', '08', 104),
(209, 'CHUPURO', '11', 104),
(210, 'COLCA', '12', 104),
(211, 'CULLHUAS', '13', 104),
(212, 'EL TAMBO', '14', 104),
(213, 'HUACRAPUQUIO', '16', 104),
(214, 'HUALHUAS', '17', 104),
(215, 'HUANCAN', '19', 104),
(216, 'HUASICANCHA', '20', 104),
(217, 'HUAYUCACHI', '21', 104),
(218, 'INGENIO', '22', 104),
(219, 'PARIAHUANCA', '24', 104),
(220, 'PILCOMAYO', '25', 104),
(221, 'PUCARA', '26', 104),
(222, 'QUICHUAY', '27', 104),
(223, 'QUILCAS', '28', 104),
(224, 'SAN AGUSTIN', '29', 104),
(225, 'SAN JERONIMO DE TUNAN', '30', 104),
(226, 'SA?O', '32', 104),
(227, 'SAPALLANGA', '33', 104),
(228, 'SICAYA', '34', 104),
(229, 'SANTO DOMINGO DE ACOBAMBA', '35', 104),
(230, 'VIQUES', '36', 104),
(231, 'CONCEPCION', '01', 105),
(232, 'ACO', '02', 105),
(233, 'ANDAMARCA', '03', 105),
(234, 'CHAMBARA', '04', 105),
(235, 'COCHAS', '05', 105),
(236, 'COMAS', '06', 105),
(237, 'HEROINAS TOLEDO', '07', 105),
(238, 'MANZANARES', '08', 105),
(239, 'MARISCAL CASTILLA', '09', 105),
(240, 'MATAHUASI', '10', 105),
(241, 'MITO', '11', 105),
(242, 'NUEVE DE JULIO', '12', 105),
(243, 'ORCOTUNA', '13', 105),
(244, 'SAN JOSE DE QUERO', '14', 105),
(245, 'SANTA ROSA DE OCOPA', '15', 105),
(246, 'CHANCHAMAYO', '01', 106),
(247, 'PERENE', '02', 106),
(248, 'PICHANAQUI', '03', 106),
(249, 'SAN LUIS DE SHUARO', '04', 106),
(250, 'SAN RAMON', '05', 106),
(251, 'VITOC', '06', 106),
(252, 'JAUJA', '01', 107),
(253, 'ACOLLA', '02', 107),
(254, 'APATA', '03', 107),
(255, 'ATAURA', '04', 107),
(256, 'CANCHAYLLO', '05', 107),
(257, 'CURICACA', '06', 107),
(258, 'EL MANTARO', '07', 107),
(259, 'HUAMALI', '08', 107),
(260, 'HUARIPAMPA', '09', 107),
(261, 'HUERTAS', '10', 107),
(262, 'JANJAILLO', '11', 107),
(263, 'JULCAN', '12', 107),
(264, 'LEONOR ORDO?EZ', '13', 107),
(265, 'LLOCLLAPAMPA', '14', 107),
(266, 'MARCO', '15', 107),
(267, 'MASMA', '16', 107),
(268, 'MASMA CHICCHE', '17', 107),
(269, 'MOLINOS', '18', 107),
(270, 'MONOBAMBA', '19', 107),
(271, 'MUQUI', '20', 107),
(272, 'MUQUIYAUYO', '21', 107),
(273, 'PACA', '22', 107),
(274, 'PACCHA', '23', 107),
(275, 'PANCAN', '24', 107),
(276, 'PARCO', '25', 107),
(277, 'POMACANCHA', '26', 107),
(278, 'RICRAN', '27', 107),
(279, 'SAN LORENZO', '28', 107),
(280, 'SAN PEDRO DE CHUNAN', '29', 107),
(281, 'SAUSA', '30', 107),
(282, 'SINCOS', '31', 107),
(283, 'TUNAN MARCA', '32', 107),
(284, 'YAULI', '33', 107),
(285, 'YAUYOS', '34', 107),
(286, 'JUNIN', '01', 108),
(287, 'CARHUAMAYO', '02', 108),
(288, 'ONDORES', '03', 108),
(289, 'ULCUMAYO', '04', 108),
(290, 'SATIPO', '01', 109),
(291, 'COVIRIALI', '02', 109),
(292, 'LLAYLLA', '03', 109),
(293, 'MAZAMARI', '04', 109),
(294, 'PAMPA HERMOSA', '05', 109),
(295, 'PANGOA', '06', 109),
(296, 'RIO NEGRO', '07', 109),
(297, 'RIO TAMBO', '08', 109),
(298, 'TARMA', '01', 110),
(299, 'ACOBAMBA', '02', 110),
(300, 'HUARICOLCA', '03', 110),
(301, 'HUASAHUASI', '04', 110),
(302, 'LA UNION', '05', 110),
(303, 'PALCA', '06', 110),
(304, 'PALCAMAYO', '07', 110),
(305, 'SAN PEDRO DE CAJAS', '08', 110),
(306, 'TAPO', '09', 110),
(307, 'LA OROYA', '01', 111),
(308, 'CHACAPALPA', '02', 111),
(309, 'HUAY-HUAY', '03', 111),
(310, 'MARCAPOMACOCHA', '04', 111),
(311, 'MOROCOCHA', '05', 111),
(312, 'PACCHA', '06', 111),
(313, 'SANTA BARBARA DE CARHUACAYAN', '07', 111),
(314, 'SANTA ROSA DE SACCO', '08', 111),
(315, 'SUITUCANCHA', '09', 111),
(316, 'YAULI', '10', 111),
(317, 'CHUPACA', '01', 112),
(318, 'AHUAC', '02', 112),
(319, 'CHONGOS BAJO', '03', 112),
(320, 'HUACHAC', '04', 112),
(321, 'HUAMANCACA CHICO', '05', 112),
(322, 'SAN JUAN DE ISCOS', '06', 112),
(323, 'SAN JUAN DE JARPA', '07', 112),
(324, 'TRES DE DICIEMBRE', '08', 112),
(325, 'YANACANCHA', '09', 112),
(326, 'TRUJILLO', '01', 113),
(327, 'EL PORVENIR', '02', 113),
(328, 'FLORENCIA DE MORA', '03', 113),
(329, 'HUANCHACO', '04', 113),
(330, 'LA ESPERANZA', '05', 113),
(331, 'LAREDO', '06', 113),
(332, 'MOCHE', '07', 113),
(333, 'POROTO', '08', 113),
(334, 'SALAVERRY', '09', 113),
(335, 'SIMBAL', '10', 113),
(336, 'VICTOR LARCO HERRERA', '11', 113),
(337, 'ASCOPE', '01', 114),
(338, 'CHICAMA', '02', 114),
(339, 'CHOCOPE', '03', 114),
(340, 'MAGDALENA DE CAO', '04', 114),
(341, 'PAIJAN', '05', 114),
(342, 'RAZURI', '06', 114),
(343, 'SANTIAGO DE CAO', '07', 114),
(344, 'CASA GRANDE', '08', 114),
(345, 'BOLIVAR', '01', 115),
(346, 'BAMBAMARCA', '02', 115),
(347, 'CONDORMARCA', '03', 115),
(348, 'LONGOTEA', '04', 115),
(349, 'UCHUMARCA', '05', 115),
(350, 'UCUNCHA', '06', 115),
(351, 'CHEPEN', '01', 116),
(352, 'PACANGA', '02', 116),
(353, 'PUEBLO NUEVO', '03', 116),
(354, 'JULCAN', '01', 117),
(355, 'CALAMARCA', '02', 117),
(356, 'CARABAMBA', '03', 117),
(357, 'HUASO', '04', 117),
(358, 'OTUZCO', '01', 118),
(359, 'AGALLPAMPA', '02', 118),
(360, 'CHARAT', '04', 118),
(361, 'HUARANCHAL', '05', 118),
(362, 'LA CUESTA', '06', 118),
(363, 'MACHE', '08', 118),
(364, 'PARANDAY', '10', 118),
(365, 'SALPO', '11', 118),
(366, 'SINSICAP', '13', 118),
(367, 'USQUIL', '14', 118),
(368, 'SAN PEDRO DE LLOC', '01', 119),
(369, 'GUADALUPE', '02', 119),
(370, 'JEQUETEPEQUE', '03', 119),
(371, 'PACASMAYO', '04', 119),
(372, 'SAN JOSE', '05', 119),
(373, 'TAYABAMBA', '01', 120),
(374, 'BULDIBUYO', '02', 120),
(375, 'CHILLIA', '03', 120),
(376, 'HUANCASPATA', '04', 120),
(377, 'HUAYLILLAS', '05', 120),
(378, 'HUAYO', '06', 120),
(379, 'ONGON', '07', 120),
(380, 'PARCOY', '08', 120),
(381, 'PATAZ', '09', 120),
(382, 'PIAS', '10', 120),
(383, 'SANTIAGO DE CHALLAS', '11', 120),
(384, 'TAURIJA', '12', 120),
(385, 'URPAY', '13', 120),
(386, 'HUAMACHUCO', '01', 121),
(387, 'CHUGAY', '02', 121),
(388, 'COCHORCO', '03', 121),
(389, 'CURGOS', '04', 121),
(390, 'MARCABAL', '05', 121),
(391, 'SANAGORAN', '06', 121),
(392, 'SARIN', '07', 121),
(393, 'SARTIMBAMBA', '08', 121),
(394, 'SANTIAGO DE CHUCO', '01', 122),
(395, 'ANGASMARCA', '02', 122),
(396, 'CACHICADAN', '03', 122),
(397, 'MOLLEBAMBA', '04', 122),
(398, 'MOLLEPATA', '05', 122),
(399, 'QUIRUVILCA', '06', 122),
(400, 'SANTA CRUZ DE CHUCA', '07', 122),
(401, 'SITABAMBA', '08', 122),
(402, 'CASCAS', '01', 123),
(403, 'LUCMA', '02', 123),
(404, 'MARMOT', '03', 123),
(405, 'SAYAPULLO', '04', 123),
(406, 'VIRU', '01', 124),
(407, 'CHAO', '02', 124),
(408, 'GUADALUPITO', '03', 124),
(409, 'CHICLAYO', '01', 125),
(410, 'CHONGOYAPE', '02', 125),
(411, 'ETEN', '03', 125),
(412, 'ETEN PUERTO', '04', 125),
(413, 'JOSE LEONARDO ORTIZ', '05', 125),
(414, 'LA VICTORIA', '06', 125),
(415, 'LAGUNAS', '07', 125),
(416, 'MONSEFU', '08', 125),
(417, 'NUEVA ARICA', '09', 125),
(418, 'OYOTUN', '10', 125),
(419, 'PICSI', '11', 125),
(420, 'PIMENTEL', '12', 125),
(421, 'REQUE', '13', 125),
(422, 'SANTA ROSA', '14', 125),
(423, 'SA?A', '15', 125),
(424, 'CAYALTI', '16', 125),
(425, 'PATAPO', '17', 125),
(426, 'POMALCA', '18', 125),
(427, 'PUCALA', '19', 125),
(428, 'TUMAN', '20', 125),
(429, 'FERRE?AFE', '01', 126),
(430, 'CA?ARIS', '02', 126),
(431, 'INCAHUASI', '03', 126),
(432, 'MANUEL ANTONIO MESONES MURO', '04', 126),
(433, 'PITIPO', '05', 126),
(434, 'PUEBLO NUEVO', '06', 126),
(435, 'LAMBAYEQUE', '01', 127),
(436, 'CHOCHOPE', '02', 127),
(437, 'ILLIMO', '03', 127),
(438, 'JAYANCA', '04', 127),
(439, 'MOCHUMI', '05', 127),
(440, 'MORROPE', '06', 127),
(441, 'MOTUPE', '07', 127),
(442, 'OLMOS', '08', 127),
(443, 'PACORA', '09', 127),
(444, 'SALAS', '10', 127),
(445, 'SAN JOSE', '11', 127),
(446, 'TUCUME', '12', 127),
(447, 'LIMA', '01', 128),
(448, 'ANCON', '02', 128),
(449, 'ATE', '03', 128),
(450, 'BARRANCO', '04', 128),
(451, 'BRE?A', '05', 128),
(452, 'CARABAYLLO', '06', 128),
(453, 'CHACLACAYO', '07', 128),
(454, 'CHORRILLOS', '08', 128),
(455, 'CIENEGUILLA', '09', 128),
(456, 'COMAS', '10', 128),
(457, 'EL AGUSTINO', '11', 128),
(458, 'INDEPENDENCIA', '12', 128),
(459, 'JESUS MARIA', '13', 128),
(460, 'LA MOLINA', '14', 128),
(461, 'LA VICTORIA', '15', 128),
(462, 'LINCE', '16', 128),
(463, 'LOS OLIVOS', '17', 128),
(464, 'LURIGANCHO', '18', 128),
(465, 'LURIN', '19', 128),
(466, 'MAGDALENA DEL MAR', '20', 128),
(467, 'MAGDALENA VIEJA', '21', 128),
(468, 'MIRAFLORES', '22', 128),
(469, 'PACHACAMAC', '23', 128),
(470, 'PUCUSANA', '24', 128),
(471, 'PUENTE PIEDRA', '25', 128),
(472, 'PUNTA HERMOSA', '26', 128),
(473, 'PUNTA NEGRA', '27', 128),
(474, 'RIMAC', '28', 128),
(475, 'SAN BARTOLO', '29', 128),
(476, 'SAN BORJA', '30', 128),
(477, 'SAN ISIDRO', '31', 128),
(478, 'SAN JUAN DE LURIGANCHO', '32', 128),
(479, 'SAN JUAN DE MIRAFLORES', '33', 128),
(480, 'SAN LUIS', '34', 128),
(481, 'SAN MARTIN DE PORRES', '35', 128),
(482, 'SAN MIGUEL', '36', 128),
(483, 'SANTA ANITA', '37', 128),
(484, 'SANTA MARIA DEL MAR', '38', 128),
(485, 'SANTA ROSA', '39', 128),
(486, 'SANTIAGO DE SURCO', '40', 128),
(487, 'SURQUILLO', '41', 128),
(488, 'VILLA EL SALVADOR', '42', 128),
(489, 'VILLA MARIA DEL TRIUNFO', '43', 128),
(490, 'BARRANCA', '01', 129),
(491, 'PARAMONGA', '02', 129),
(492, 'PATIVILCA', '03', 129),
(493, 'SUPE', '04', 129),
(494, 'SUPE PUERTO', '05', 129),
(495, 'CAJATAMBO', '01', 130),
(496, 'COPA', '02', 130),
(497, 'GORGOR', '03', 130),
(498, 'HUANCAPON', '04', 130),
(499, 'MANAS', '05', 130),
(500, 'CANTA', '01', 131),
(501, 'ARAHUAY', '02', 131),
(502, 'HUAMANTANGA', '03', 131),
(503, 'HUAROS', '04', 131),
(504, 'LACHAQUI', '05', 131),
(505, 'SAN BUENAVENTURA', '06', 131),
(506, 'SANTA ROSA DE QUIVES', '07', 131),
(507, 'SAN VICENTE DE CA?ETE', '01', 132),
(508, 'ASIA', '02', 132),
(509, 'CALANGO', '03', 132),
(510, 'CERRO AZUL', '04', 132),
(511, 'CHILCA', '05', 132),
(512, 'COAYLLO', '06', 132),
(513, 'IMPERIAL', '07', 132),
(514, 'LUNAHUANA', '08', 132),
(515, 'MALA', '09', 132),
(516, 'NUEVO IMPERIAL', '10', 132),
(517, 'PACARAN', '11', 132),
(518, 'QUILMANA', '12', 132),
(519, 'SAN ANTONIO', '13', 132),
(520, 'SAN LUIS', '14', 132),
(521, 'SANTA CRUZ DE FLORES', '15', 132),
(522, 'ZU?IGA', '16', 132),
(523, 'HUARAL', '01', 133),
(524, 'ATAVILLOS ALTO', '02', 133),
(525, 'ATAVILLOS BAJO', '03', 133),
(526, 'AUCALLAMA', '04', 133),
(527, 'CHANCAY', '05', 133),
(528, 'IHUARI', '06', 133),
(529, 'LAMPIAN', '07', 133),
(530, 'PACARAOS', '08', 133),
(531, 'SAN MIGUEL DE ACOS', '09', 133),
(532, 'SANTA CRUZ DE ANDAMARCA', '10', 133),
(533, 'SUMBILCA', '11', 133),
(534, 'VEINTISIETE DE NOVIEMBRE', '12', 133),
(535, 'MATUCANA', '01', 134),
(536, 'ANTIOQUIA', '02', 134),
(537, 'CALLAHUANCA', '03', 134),
(538, 'CARAMPOMA', '04', 134),
(539, 'CHICLA', '05', 134),
(540, 'CUENCA', '06', 134),
(541, 'HUACHUPAMPA', '07', 134),
(542, 'HUANZA', '08', 134),
(543, 'HUAROCHIRI', '09', 134),
(544, 'LAHUAYTAMBO', '10', 134),
(545, 'LANGA', '11', 134),
(546, 'LARAOS', '12', 134),
(547, 'MARIATANA', '13', 134),
(548, 'RICARDO PALMA', '14', 134),
(549, 'SAN ANDRES DE TUPICOCHA', '15', 134),
(550, 'SAN ANTONIO', '16', 134),
(551, 'SAN BARTOLOME', '17', 134),
(552, 'SAN DAMIAN', '18', 134),
(553, 'SAN JUAN DE IRIS', '19', 134),
(554, 'SAN JUAN DE TANTARANCHE', '20', 134),
(555, 'SAN LORENZO DE QUINTI', '21', 134),
(556, 'SAN MATEO', '22', 134),
(557, 'SAN MATEO DE OTAO', '23', 134),
(558, 'SAN PEDRO DE CASTA', '24', 134),
(559, 'SAN PEDRO DE HUANCAYRE', '25', 134),
(560, 'SANGALLAYA', '26', 134),
(561, 'SANTA CRUZ DE COCACHACRA', '27', 134),
(562, 'SANTA EULALIA', '28', 134),
(563, 'SANTIAGO DE ANCHUCAYA', '29', 134),
(564, 'SANTIAGO DE TUNA', '30', 134),
(565, 'SANTO DOMINGO DE LOS OLLEROS', '31', 134),
(566, 'SURCO', '32', 134),
(567, 'HUACHO', '01', 135),
(568, 'AMBAR', '02', 135),
(569, 'CALETA DE CARQUIN', '03', 135),
(570, 'CHECRAS', '04', 135),
(571, 'HUALMAY', '05', 135),
(572, 'HUAURA', '06', 135),
(573, 'LEONCIO PRADO', '07', 135),
(574, 'PACCHO', '08', 135),
(575, 'SANTA LEONOR', '09', 135),
(576, 'SANTA MARIA', '10', 135),
(577, 'SAYAN', '11', 135),
(578, 'VEGUETA', '12', 135),
(579, 'OYON', '01', 136),
(580, 'ANDAJES', '02', 136),
(581, 'CAUJUL', '03', 136),
(582, 'COCHAMARCA', '04', 136),
(583, 'NAVAN', '05', 136),
(584, 'PACHANGARA', '06', 136),
(585, 'YAUYOS', '01', 137),
(586, 'ALIS', '02', 137),
(587, 'AYAUCA', '03', 137),
(588, 'AYAVIRI', '04', 137),
(589, 'AZANGARO', '05', 137),
(590, 'CACRA', '06', 137),
(591, 'CARANIA', '07', 137),
(592, 'CATAHUASI', '08', 137),
(593, 'CHOCOS', '09', 137),
(594, 'COCHAS', '10', 137),
(595, 'COLONIA', '11', 137),
(596, 'HONGOS', '12', 137),
(597, 'HUAMPARA', '13', 137),
(598, 'HUANCAYA', '14', 137),
(599, 'HUANGASCAR', '15', 137),
(600, 'HUANTAN', '16', 137),
(601, 'HUA?EC', '17', 137),
(602, 'LARAOS', '18', 137),
(603, 'LINCHA', '19', 137),
(604, 'MADEAN', '20', 137),
(605, 'MIRAFLORES', '21', 137),
(606, 'OMAS', '22', 137),
(607, 'PUTINZA', '23', 137),
(608, 'QUINCHES', '24', 137),
(609, 'QUINOCAY', '25', 137),
(610, 'SAN JOAQUIN', '26', 137),
(611, 'SAN PEDRO DE PILAS', '27', 137),
(612, 'TANTA', '28', 137),
(613, 'TAURIPAMPA', '29', 137),
(614, 'TOMAS', '30', 137),
(615, 'TUPE', '31', 137),
(616, 'VI?AC', '32', 137),
(617, 'VITIS', '33', 137),
(618, 'IQUITOS', '01', 138),
(619, 'ALTO NANAY', '02', 138),
(620, 'FERNANDO LORES', '03', 138),
(621, 'INDIANA', '04', 138),
(622, 'LAS AMAZONAS', '05', 138),
(623, 'MAZAN', '06', 138),
(624, 'NAPO', '07', 138),
(625, 'PUNCHANA', '08', 138),
(626, 'PUTUMAYO', '09', 138),
(627, 'TORRES CAUSANA', '10', 138),
(628, 'BELEN', '12', 138),
(629, 'SAN JUAN BAUTISTA', '13', 138),
(630, 'YURIMAGUAS', '01', 139),
(631, 'BALSAPUERTO', '02', 139),
(632, 'BARRANCA', '03', 139),
(633, 'CAHUAPANAS', '04', 139),
(634, 'JEBEROS', '05', 139),
(635, 'LAGUNAS', '06', 139),
(636, 'MANSERICHE', '07', 139),
(637, 'MORONA', '08', 139),
(638, 'PASTAZA', '09', 139),
(639, 'SANTA CRUZ', '10', 139),
(640, 'TENIENTE CESAR LOPEZ ROJAS', '11', 139),
(641, 'NAUTA', '01', 140),
(642, 'PARINARI', '02', 140),
(643, 'TIGRE', '03', 140),
(644, 'TROMPETEROS', '04', 140),
(645, 'URARINAS', '05', 140),
(646, 'RAMON CASTILLA', '01', 141),
(647, 'PEBAS', '02', 141),
(648, 'YAVARI', '03', 141),
(649, 'SAN PABLO', '04', 141),
(650, 'REQUENA', '01', 142),
(651, 'ALTO TAPICHE', '02', 142),
(652, 'CAPELO', '03', 142),
(653, 'EMILIO SAN MARTIN', '04', 142),
(654, 'MAQUIA', '05', 142),
(655, 'PUINAHUA', '06', 142),
(656, 'SAQUENA', '07', 142),
(657, 'SOPLIN', '08', 142),
(658, 'TAPICHE', '09', 142),
(659, 'JENARO HERRERA', '10', 142),
(660, 'YAQUERANA', '11', 142),
(661, 'CONTAMANA', '01', 143),
(662, 'INAHUAYA', '02', 143),
(663, 'PADRE MARQUEZ', '03', 143),
(664, 'PAMPA HERMOSA', '04', 143),
(665, 'SARAYACU', '05', 143),
(666, 'VARGAS GUERRA', '06', 143),
(667, 'TAMBOPATA', '01', 144),
(668, 'INAMBARI', '02', 144),
(669, 'LAS PIEDRAS', '03', 144),
(670, 'LABERINTO', '04', 144),
(671, 'MANU', '01', 145),
(672, 'FITZCARRALD', '02', 145),
(673, 'MADRE DE DIOS', '03', 145),
(674, 'HUEPETUHE', '04', 145),
(675, 'I?APARI', '01', 146),
(676, 'IBERIA', '02', 146),
(677, 'TAHUAMANU', '03', 146),
(678, 'MOQUEGUA', '01', 147),
(679, 'CARUMAS', '02', 147),
(680, 'CUCHUMBAYA', '03', 147),
(681, 'SAMEGUA', '04', 147),
(682, 'SAN CRISTOBAL', '05', 147),
(683, 'TORATA', '06', 147),
(684, 'OMATE', '01', 148),
(685, 'CHOJATA', '02', 148),
(686, 'COALAQUE', '03', 148),
(687, 'ICHU?A', '04', 148),
(688, 'LA CAPILLA', '05', 148),
(689, 'LLOQUE', '06', 148),
(690, 'MATALAQUE', '07', 148),
(691, 'PUQUINA', '08', 148),
(692, 'QUINISTAQUILLAS', '09', 148),
(693, 'UBINAS', '10', 148),
(694, 'YUNGA', '11', 148),
(695, 'ILO', '01', 149),
(696, 'EL ALGARROBAL', '02', 149),
(697, 'PACOCHA', '03', 149),
(698, 'CHAUPIMARCA', '01', 150),
(699, 'HUACHON', '02', 150),
(700, 'HUARIACA', '03', 150),
(701, 'HUAYLLAY', '04', 150),
(702, 'NINACACA', '05', 150),
(703, 'PALLANCHACRA', '06', 150),
(704, 'PAUCARTAMBO', '07', 150),
(705, 'SAN FCO.DE ASIS DE YARUSYACAN', '08', 150),
(706, 'SIMON BOLIVAR', '09', 150),
(707, 'TICLACAYAN', '10', 150),
(708, 'TINYAHUARCO', '11', 150),
(709, 'VICCO', '12', 150),
(710, 'YANACANCHA', '13', 150),
(711, 'YANAHUANCA', '01', 151),
(712, 'CHACAYAN', '02', 151),
(713, 'GOYLLARISQUIZGA', '03', 151),
(714, 'PAUCAR', '04', 151),
(715, 'SAN PEDRO DE PILLAO', '05', 151),
(716, 'SANTA ANA DE TUSI', '06', 151),
(717, 'TAPUC', '07', 151),
(718, 'VILCABAMBA', '08', 151),
(719, 'OXAPAMPA', '01', 152),
(720, 'CHONTABAMBA', '02', 152),
(721, 'HUANCABAMBA', '03', 152),
(722, 'PALCAZU', '04', 152),
(723, 'POZUZO', '05', 152),
(724, 'PUERTO BERMUDEZ', '06', 152),
(725, 'VILLA RICA', '07', 152),
(726, 'HUARAZ', '01', 8),
(727, 'COCHABAMBA', '02', 8),
(728, 'COLCABAMBA', '03', 8),
(729, 'HUANCHAY', '04', 8),
(730, 'INDEPENDENCIA', '05', 8),
(731, 'JANGAS', '06', 8),
(732, 'LA LIBERTAD', '07', 8),
(733, 'OLLEROS', '08', 8),
(734, 'PAMPAS', '09', 8),
(735, 'PARIACOTO', '10', 8),
(736, 'PIRA', '11', 8),
(737, 'TARICA', '12', 8),
(738, 'AIJA', '01', 9),
(739, 'CORIS', '02', 9),
(740, 'HUACLLAN', '03', 9),
(741, 'LA MERCED', '04', 9),
(742, 'SUCCHA', '05', 9),
(743, 'LLAMELLIN', '01', 10),
(744, 'ACZO', '02', 10),
(745, 'CHACCHO', '03', 10),
(746, 'CHINGAS', '04', 10),
(747, 'MIRGAS', '05', 10),
(748, 'SAN JUAN DE RONTOY', '06', 10),
(749, 'CHACAS', '01', 11),
(750, 'ACOCHACA', '02', 11),
(751, 'CHIQUIAN', '01', 12),
(752, 'ABELARDO PARDO LEZAMETA', '02', 12),
(753, 'ANTONIO RAYMONDI', '03', 12),
(754, 'AQUIA', '04', 12),
(755, 'CAJACAY', '05', 12),
(756, 'CANIS', '06', 12),
(757, 'COLQUIOC', '07', 12),
(758, 'HUALLANCA', '08', 12),
(759, 'HUASTA', '09', 12),
(760, 'HUAYLLACAYAN', '10', 12),
(761, 'LA PRIMAVERA', '11', 12),
(762, 'MANGAS', '12', 12),
(763, 'PACLLON', '13', 12),
(764, 'SAN MIGUEL DE CORPANQUI', '14', 12),
(765, 'TICLLOS', '15', 12),
(766, 'CARHUAZ', '01', 13),
(767, 'ACOPAMPA', '02', 13),
(768, 'AMASHCA', '03', 13),
(769, 'ANTA', '04', 13),
(770, 'ATAQUERO', '05', 13),
(771, 'MARCARA', '06', 13),
(772, 'PARIAHUANCA', '07', 13),
(773, 'SAN MIGUEL DE ACO', '08', 13),
(774, 'SHILLA', '09', 13),
(775, 'TINCO', '10', 13),
(776, 'YUNGAR', '11', 13),
(777, 'SAN LUIS', '01', 14),
(778, 'SAN NICOLAS', '02', 14),
(779, 'YAUYA', '03', 14),
(780, 'CASMA', '01', 15),
(781, 'BUENA VISTA ALTA', '02', 15),
(782, 'COMANDANTE NOEL', '03', 15),
(783, 'YAUTAN', '04', 15),
(784, 'CORONGO', '01', 16),
(785, 'ACO', '02', 16),
(786, 'BAMBAS', '03', 16),
(787, 'CUSCA', '04', 16),
(788, 'LA PAMPA', '05', 16),
(789, 'YANAC', '06', 16),
(790, 'YUPAN', '07', 16),
(791, 'HUARI', '01', 17),
(792, 'ANRA', '02', 17),
(793, 'CAJAY', '03', 17),
(794, 'CHAVIN DE HUANTAR', '04', 17),
(795, 'HUACACHI', '05', 17),
(796, 'HUACCHIS', '06', 17),
(797, 'HUACHIS', '07', 17),
(798, 'HUANTAR', '08', 17),
(799, 'MASIN', '09', 17),
(800, 'PAUCAS', '10', 17),
(801, 'PONTO', '11', 17),
(802, 'RAHUAPAMPA', '12', 17),
(803, 'RAPAYAN', '13', 17),
(804, 'SAN MARCOS', '14', 17),
(805, 'SAN PEDRO DE CHANA', '15', 17),
(806, 'UCO', '16', 17),
(807, 'HUARMEY', '01', 18),
(808, 'COCHAPETI', '02', 18),
(809, 'CULEBRAS', '03', 18),
(810, 'HUAYAN', '04', 18),
(811, 'MALVAS', '05', 18),
(812, 'CARAZ', '01', 19),
(813, 'HUALLANCA', '02', 19),
(814, 'HUATA', '03', 19),
(815, 'HUAYLAS', '04', 19),
(816, 'MATO', '05', 19),
(817, 'PAMPAROMAS', '06', 19),
(818, 'PUEBLO LIBRE', '07', 19),
(819, 'SANTA CRUZ', '08', 19),
(820, 'SANTO TORIBIO', '09', 19),
(821, 'YURACMARCA', '10', 19),
(822, 'PISCOBAMBA', '01', 20),
(823, 'CASCA', '02', 20),
(824, 'ELEAZAR GUZMAN BARRON', '03', 20),
(825, 'FIDEL OLIVAS ESCUDERO', '04', 20),
(826, 'LLAMA', '05', 20),
(827, 'LLUMPA', '06', 20),
(828, 'LUCMA', '07', 20),
(829, 'MUSGA', '08', 20),
(830, 'OCROS', '01', 21),
(831, 'ACAS', '02', 21),
(832, 'CAJAMARQUILLA', '03', 21),
(833, 'CARHUAPAMPA', '04', 21),
(834, 'COCHAS', '05', 21),
(835, 'CONGAS', '06', 21),
(836, 'LLIPA', '07', 21),
(837, 'SAN CRISTOBAL DE RAJAN', '08', 21),
(838, 'SAN PEDRO', '09', 21),
(839, 'SANTIAGO DE CHILCAS', '10', 21),
(840, 'CABANA', '01', 22),
(841, 'BOLOGNESI', '02', 22),
(842, 'CONCHUCOS', '03', 22),
(843, 'HUACASCHUQUE', '04', 22),
(844, 'HUANDOVAL', '05', 22),
(845, 'LACABAMBA', '06', 22),
(846, 'LLAPO', '07', 22),
(847, 'PALLASCA', '08', 22),
(848, 'PAMPAS', '09', 22),
(849, 'SANTA ROSA', '10', 22),
(850, 'TAUCA', '11', 22),
(851, 'POMABAMBA', '01', 23),
(852, 'HUAYLLAN', '02', 23),
(853, 'PAROBAMBA', '03', 23),
(854, 'QUINUABAMBA', '04', 23),
(855, 'RECUAY', '01', 24),
(856, 'CATAC', '02', 24),
(857, 'COTAPARACO', '03', 24),
(858, 'HUAYLLAPAMPA', '04', 24),
(859, 'LLACLLIN', '05', 24),
(860, 'MARCA', '06', 24),
(861, 'PAMPAS CHICO', '07', 24),
(862, 'PARARIN', '08', 24),
(863, 'TAPACOCHA', '09', 24),
(864, 'TICAPAMPA', '10', 24),
(865, 'CHIMBOTE', '01', 25),
(866, 'CACERES DEL PERU', '02', 25),
(867, 'COISHCO', '03', 25),
(868, 'MACATE', '04', 25),
(869, 'MORO', '05', 25),
(870, 'NEPE?A', '06', 25),
(871, 'SAMANCO', '07', 25),
(872, 'SANTA', '08', 25),
(873, 'NUEVO CHIMBOTE', '09', 25),
(874, 'SIHUAS', '01', 26),
(875, 'ACOBAMBA', '02', 26),
(876, 'ALFONSO UGARTE', '03', 26),
(877, 'CASHAPAMPA', '04', 26),
(878, 'CHINGALPO', '05', 26),
(879, 'HUAYLLABAMBA', '06', 26),
(880, 'QUICHES', '07', 26),
(881, 'RAGASH', '08', 26),
(882, 'SAN JUAN', '09', 26),
(883, 'SICSIBAMBA', '10', 26),
(884, 'YUNGAY', '01', 27),
(885, 'CASCAPARA', '02', 27),
(886, 'MANCOS', '03', 27),
(887, 'MATACOTO', '04', 27),
(888, 'QUILLO', '05', 27),
(889, 'RANRAHIRCA', '06', 27),
(890, 'SHUPLUY', '07', 27),
(891, 'YANAMA', '08', 27),
(892, 'PIURA', '01', 153),
(893, 'CASTILLA', '04', 153),
(894, 'CATACAOS', '05', 153),
(895, 'CURA MORI', '07', 153),
(896, 'EL TALLAN', '08', 153),
(897, 'LA ARENA', '09', 153),
(898, 'LA UNION', '10', 153),
(899, 'LAS LOMAS', '11', 153),
(900, 'TAMBO GRANDE', '14', 153),
(901, 'AYABACA', '01', 154),
(902, 'FRIAS', '02', 154),
(903, 'JILILI', '03', 154),
(904, 'LAGUNAS', '04', 154),
(905, 'MONTERO', '05', 154),
(906, 'PACAIPAMPA', '06', 154),
(907, 'PAIMAS', '07', 154),
(908, 'SAPILLICA', '08', 154),
(909, 'SICCHEZ', '09', 154),
(910, 'SUYO', '10', 154),
(911, 'HUANCABAMBA', '01', 155),
(912, 'CANCHAQUE', '02', 155),
(913, 'EL CARMEN DE LA FRONTERA', '03', 155),
(914, 'HUARMACA', '04', 155),
(915, 'LALAQUIZ', '05', 155),
(916, 'SAN MIGUEL DE EL FAIQUE', '06', 155),
(917, 'SONDOR', '07', 155),
(918, 'SONDORILLO', '08', 155),
(919, 'CHULUCANAS', '01', 156),
(920, 'BUENOS AIRES', '02', 156),
(921, 'CHALACO', '03', 156),
(922, 'LA MATANZA', '04', 156),
(923, 'MORROPON', '05', 156),
(924, 'SALITRAL', '06', 156),
(925, 'SAN JUAN DE BIGOTE', '07', 156),
(926, 'SANTA CATALINA DE MOSSA', '08', 156),
(927, 'SANTO DOMINGO', '09', 156),
(928, 'YAMANGO', '10', 156),
(929, 'PAITA', '01', 157),
(930, 'AMOTAPE', '02', 157),
(931, 'ARENAL', '03', 157),
(932, 'COLAN', '04', 157),
(933, 'LA HUACA', '05', 157),
(934, 'TAMARINDO', '06', 157),
(935, 'VICHAYAL', '07', 157),
(936, 'SULLANA', '01', 158),
(937, 'BELLAVISTA', '02', 158),
(938, 'IGNACIO ESCUDERO', '03', 158),
(939, 'LANCONES', '04', 158),
(940, 'MARCAVELICA', '05', 158),
(941, 'MIGUEL CHECA', '06', 158),
(942, 'QUERECOTILLO', '07', 158),
(943, 'SALITRAL', '08', 158),
(944, 'PARI?AS', '01', 159),
(945, 'EL ALTO', '02', 159),
(946, 'LA BREA', '03', 159),
(947, 'LOBITOS', '04', 159),
(948, 'LOS ORGANOS', '05', 159),
(949, 'MANCORA', '06', 159),
(950, 'SECHURA', '01', 160),
(951, 'BELLAVISTA DE LA UNION', '02', 160),
(952, 'BERNAL', '03', 160),
(953, 'CRISTO NOS VALGA', '04', 160),
(954, 'VICE', '05', 160),
(955, 'RINCONADA LLICUAR', '06', 160),
(956, 'PUNO', '01', 161),
(957, 'ACORA', '02', 161),
(958, 'AMANTANI', '03', 161),
(959, 'ATUNCOLLA', '04', 161),
(960, 'CAPACHICA', '05', 161),
(961, 'CHUCUITO', '06', 161),
(962, 'COATA', '07', 161),
(963, 'HUATA', '08', 161),
(964, 'MA?AZO', '09', 161),
(965, 'PAUCARCOLLA', '10', 161),
(966, 'PICHACANI', '11', 161),
(967, 'PLATERIA', '12', 161),
(968, 'SAN ANTONIO', '13', 161),
(969, 'TIQUILLACA', '14', 161),
(970, 'VILQUE', '15', 161),
(971, 'AZANGARO', '01', 162),
(972, 'ACHAYA', '02', 162),
(973, 'ARAPA', '03', 162),
(974, 'ASILLO', '04', 162),
(975, 'CAMINACA', '05', 162),
(976, 'CHUPA', '06', 162),
(977, 'JOSE DOMINGO CHOQUEHUANCA', '07', 162),
(978, 'MU?ANI', '08', 162),
(979, 'POTONI', '09', 162),
(980, 'SAMAN', '10', 162),
(981, 'SAN ANTON', '11', 162),
(982, 'SAN JOSE', '12', 162),
(983, 'SAN JUAN DE SALINAS', '13', 162),
(984, 'SANTIAGO DE PUPUJA', '14', 162),
(985, 'TIRAPATA', '15', 162),
(986, 'MACUSANI', '01', 163),
(987, 'AJOYANI', '02', 163),
(988, 'AYAPATA', '03', 163),
(989, 'COASA', '04', 163),
(990, 'CORANI', '05', 163),
(991, 'CRUCERO', '06', 163),
(992, 'ITUATA', '07', 163),
(993, 'OLLACHEA', '08', 163),
(994, 'SAN GABAN', '09', 163),
(995, 'USICAYOS', '10', 163),
(996, 'JULI', '01', 164),
(997, 'DESAGUADERO', '02', 164),
(998, 'HUACULLANI', '03', 164),
(999, 'KELLUYO', '04', 164),
(1000, 'PISACOMA', '05', 164),
(1001, 'POMATA', '06', 164),
(1002, 'ZEPITA', '07', 164),
(1003, 'ILAVE', '01', 165),
(1004, 'CAPAZO', '02', 165),
(1005, 'PILCUYO', '03', 165),
(1006, 'SANTA ROSA', '04', 165),
(1007, 'CONDURIRI', '05', 165),
(1008, 'HUANCANE', '01', 166),
(1009, 'COJATA', '02', 166),
(1010, 'HUATASANI', '03', 166),
(1011, 'INCHUPALLA', '04', 166),
(1012, 'PUSI', '05', 166),
(1013, 'ROSASPATA', '06', 166),
(1014, 'TARACO', '07', 166),
(1015, 'VILQUE CHICO', '08', 166),
(1016, 'LAMPA', '01', 167),
(1017, 'CABANILLA', '02', 167),
(1018, 'CALAPUJA', '03', 167),
(1019, 'NICASIO', '04', 167),
(1020, 'OCUVIRI', '05', 167),
(1021, 'PALCA', '06', 167),
(1022, 'PARATIA', '07', 167),
(1023, 'PUCARA', '08', 167),
(1024, 'SANTA LUCIA', '09', 167),
(1025, 'VILAVILA', '10', 167),
(1026, 'AYAVIRI', '01', 168),
(1027, 'ANTAUTA', '02', 168),
(1028, 'CUPI', '03', 168),
(1029, 'LLALLI', '04', 168),
(1030, 'MACARI', '05', 168),
(1031, 'NU?OA', '06', 168),
(1032, 'ORURILLO', '07', 168),
(1033, 'SANTA ROSA', '08', 168),
(1034, 'UMACHIRI', '09', 168),
(1035, 'MOHO', '01', 169),
(1036, 'CONIMA', '02', 169),
(1037, 'HUAYRAPATA', '03', 169),
(1038, 'TILALI', '04', 169),
(1039, 'PUTINA', '01', 170),
(1040, 'ANANEA', '02', 170),
(1041, 'PEDRO VILCA APAZA', '03', 170),
(1042, 'QUILCAPUNCU', '04', 170),
(1043, 'SINA', '05', 170),
(1044, 'JULIACA', '01', 171),
(1045, 'CABANA', '02', 171),
(1046, 'CABANILLAS', '03', 171),
(1047, 'CARACOTO', '04', 171),
(1048, 'SANDIA', '01', 172),
(1049, 'CUYOCUYO', '02', 172),
(1050, 'LIMBANI', '03', 172),
(1051, 'PATAMBUCO', '04', 172),
(1052, 'PHARA', '05', 172),
(1053, 'QUIACA', '06', 172),
(1054, 'SAN JUAN DEL ORO', '07', 172),
(1055, 'YANAHUAYA', '08', 172),
(1056, 'ALTO INAMBARI', '09', 172),
(1057, 'YUNGUYO', '01', 173),
(1058, 'ANAPIA', '02', 173),
(1059, 'COPANI', '03', 173),
(1060, 'CUTURAPI', '04', 173),
(1061, 'OLLARAYA', '05', 173),
(1062, 'TINICACHI', '06', 173),
(1063, 'UNICACHI', '07', 173),
(1064, 'MOYOBAMBA', '01', 174),
(1065, 'CALZADA', '02', 174),
(1066, 'HABANA', '03', 174),
(1067, 'JEPELACIO', '04', 174),
(1068, 'SORITOR', '05', 174),
(1069, 'YANTALO', '06', 174),
(1070, 'BELLAVISTA', '01', 175),
(1071, 'ALTO BIAVO', '02', 175),
(1072, 'BAJO BIAVO', '03', 175),
(1073, 'HUALLAGA', '04', 175),
(1074, 'SAN PABLO', '05', 175),
(1075, 'SAN RAFAEL', '06', 175),
(1076, 'SAN JOSE DE SISA', '01', 176),
(1077, 'AGUA BLANCA', '02', 176),
(1078, 'SAN MARTIN', '03', 176),
(1079, 'SANTA ROSA', '04', 176),
(1080, 'SHATOJA', '05', 176),
(1081, 'SAPOSOA', '01', 177),
(1082, 'ALTO SAPOSOA', '02', 177),
(1083, 'EL ESLABON', '03', 177),
(1084, 'PISCOYACU', '04', 177),
(1085, 'SACANCHE', '05', 177),
(1086, 'TINGO DE SAPOSOA', '06', 177),
(1087, 'LAMAS', '01', 178),
(1088, 'ALONSO DE ALVARADO', '02', 178),
(1089, 'BARRANQUITA', '03', 178),
(1090, 'CAYNARACHI', '04', 178),
(1091, 'CU?UMBUQUI', '05', 178),
(1092, 'PINTO RECODO', '06', 178),
(1093, 'RUMISAPA', '07', 178),
(1094, 'SAN ROQUE DE CUMBAZA', '08', 178),
(1095, 'SHANAO', '09', 178),
(1096, 'TABALOSOS', '10', 178),
(1097, 'ZAPATERO', '11', 178),
(1098, 'JUANJUI', '01', 179),
(1099, 'CAMPANILLA', '02', 179),
(1100, 'HUICUNGO', '03', 179),
(1101, 'PACHIZA', '04', 179),
(1102, 'PAJARILLO', '05', 179),
(1103, 'PICOTA', '01', 180),
(1104, 'BUENOS AIRES', '02', 180),
(1105, 'CASPISAPA', '03', 180),
(1106, 'PILLUANA', '04', 180),
(1107, 'PUCACACA', '05', 180),
(1108, 'SAN CRISTOBAL', '06', 180),
(1109, 'SAN HILARION', '07', 180),
(1110, 'SHAMBOYACU', '08', 180),
(1111, 'TINGO DE PONASA', '09', 180),
(1112, 'TRES UNIDOS', '10', 180),
(1113, 'RIOJA', '01', 181),
(1114, 'AWAJUN', '02', 181),
(1115, 'ELIAS SOPLIN VARGAS', '03', 181),
(1116, 'NUEVA CAJAMARCA', '04', 181),
(1117, 'PARDO MIGUEL', '05', 181),
(1118, 'POSIC', '06', 181),
(1119, 'SAN FERNANDO', '07', 181),
(1120, 'YORONGOS', '08', 181),
(1121, 'YURACYACU', '09', 181),
(1122, 'TARAPOTO', '01', 182),
(1123, 'ALBERTO LEVEAU', '02', 182),
(1124, 'CACATACHI', '03', 182),
(1125, 'CHAZUTA', '04', 182),
(1126, 'CHIPURANA', '05', 182),
(1127, 'EL PORVENIR', '06', 182),
(1128, 'HUIMBAYOC', '07', 182),
(1129, 'JUAN GUERRA', '08', 182),
(1130, 'LA BANDA DE SHILCAYO', '09', 182),
(1131, 'MORALES', '10', 182),
(1132, 'PAPAPLAYA', '11', 182),
(1133, 'SAN ANTONIO', '12', 182),
(1134, 'SAUCE', '13', 182),
(1135, 'SHAPAJA', '14', 182),
(1136, 'TOCACHE', '01', 183),
(1137, 'NUEVO PROGRESO', '02', 183),
(1138, 'POLVORA', '03', 183),
(1139, 'SHUNTE', '04', 183),
(1140, 'UCHIZA', '05', 183),
(1141, 'TACNA', '01', 184),
(1142, 'ALTO DE LA ALIANZA', '02', 184),
(1143, 'CALANA', '03', 184),
(1144, 'CIUDAD NUEVA', '04', 184),
(1145, 'INCLAN', '05', 184),
(1146, 'PACHIA', '06', 184),
(1147, 'PALCA', '07', 184),
(1148, 'POCOLLAY', '08', 184),
(1149, 'SAMA', '09', 184),
(1150, 'CORONEL GREGORIO ALBARRACIN LANCHIPA', '10', 184),
(1151, 'CANDARAVE', '01', 185),
(1152, 'CAIRANI', '02', 185),
(1153, 'CAMILACA', '03', 185),
(1154, 'CURIBAYA', '04', 185),
(1155, 'HUANUARA', '05', 185),
(1156, 'QUILAHUANI', '06', 185),
(1157, 'LOCUMBA', '01', 186),
(1158, 'ILABAYA', '02', 186),
(1159, 'ITE', '03', 186),
(1160, 'TARATA', '01', 187),
(1161, 'CHUCATAMANI', '02', 187),
(1162, 'ESTIQUE', '03', 187),
(1163, 'ESTIQUE-PAMPA', '04', 187),
(1164, 'SITAJARA', '05', 187),
(1165, 'SUSAPAYA', '06', 187),
(1166, 'TARUCACHI', '07', 187),
(1167, 'TICACO', '08', 187),
(1168, 'TUMBES', '01', 188),
(1169, 'CORRALES', '02', 188),
(1170, 'LA CRUZ', '03', 188),
(1171, 'PAMPAS DE HOSPITAL', '04', 188),
(1172, 'SAN JACINTO', '05', 188),
(1173, 'SAN JUAN DE LA VIRGEN', '06', 188),
(1174, 'ZORRITOS', '01', 189),
(1175, 'CASITAS', '02', 189),
(1176, 'ZARUMILLA', '01', 190),
(1177, 'AGUAS VERDES', '02', 190),
(1178, 'MATAPALO', '03', 190),
(1179, 'PAPAYAL', '04', 190),
(1180, 'CALLERIA', '01', 191),
(1181, 'CAMPOVERDE', '02', 191),
(1182, 'IPARIA', '03', 191),
(1183, 'MASISEA', '04', 191),
(1184, 'YARINACOCHA', '05', 191),
(1185, 'NUEVA REQUENA', '06', 191),
(1186, 'RAYMONDI', '01', 192),
(1187, 'SEPAHUA', '02', 192),
(1188, 'TAHUANIA', '03', 192),
(1189, 'YURUA', '04', 192),
(1190, 'PADRE ABAD', '01', 193),
(1191, 'IRAZOLA', '02', 193),
(1192, 'CURIMANA', '03', 193),
(1193, 'PURUS', '01', 194),
(1194, 'ABANCAY', '01', 28),
(1195, 'CHACOCHE', '02', 28),
(1196, 'CIRCA', '03', 28),
(1197, 'CURAHUASI', '04', 28),
(1198, 'HUANIPACA', '05', 28),
(1199, 'LAMBRAMA', '06', 28),
(1200, 'PICHIRHUA', '07', 28),
(1201, 'SAN PEDRO DE CACHORA', '08', 28),
(1202, 'TAMBURCO', '09', 28),
(1203, 'ANDAHUAYLAS', '01', 29),
(1204, 'ANDARAPA', '02', 29),
(1205, 'CHIARA', '03', 29),
(1206, 'HUANCARAMA', '04', 29),
(1207, 'HUANCARAY', '05', 29),
(1208, 'HUAYANA', '06', 29),
(1209, 'KISHUARA', '07', 29),
(1210, 'PACOBAMBA', '08', 29),
(1211, 'PACUCHA', '09', 29),
(1212, 'PAMPACHIRI', '10', 29),
(1213, 'POMACOCHA', '11', 29),
(1214, 'SAN ANTONIO DE CACHI', '12', 29),
(1215, 'SAN JERONIMO', '13', 29),
(1216, 'SAN MIGUEL DE CHACCRAMPA', '14', 29),
(1217, 'SANTA MARIA DE CHICMO', '15', 29),
(1218, 'TALAVERA', '16', 29),
(1219, 'TUMAY HUARACA', '17', 29),
(1220, 'TURPO', '18', 29),
(1221, 'KAQUIABAMBA', '19', 29),
(1222, 'ANTABAMBA', '01', 30),
(1223, 'EL ORO', '02', 30),
(1224, 'HUAQUIRCA', '03', 30),
(1225, 'JUAN ESPINOZA MEDRANO', '04', 30),
(1226, 'OROPESA', '05', 30),
(1227, 'PACHACONAS', '06', 30),
(1228, 'SABAINO', '07', 30),
(1229, 'CHALHUANCA', '01', 31),
(1230, 'CAPAYA', '02', 31),
(1231, 'CARAYBAMBA', '03', 31),
(1232, 'CHAPIMARCA', '04', 31),
(1233, 'COLCABAMBA', '05', 31),
(1234, 'COTARUSE', '06', 31),
(1235, 'HUAYLLO', '07', 31),
(1236, 'JUSTO APU SAHUARAURA', '08', 31),
(1237, 'LUCRE', '09', 31),
(1238, 'POCOHUANCA', '10', 31),
(1239, 'SAN JUAN DE CHAC?A', '11', 31),
(1240, 'SA?AYCA', '12', 31),
(1241, 'SORAYA', '13', 31),
(1242, 'TAPAIRIHUA', '14', 31),
(1243, 'TINTAY', '15', 31),
(1244, 'TORAYA', '16', 31),
(1245, 'YANACA', '17', 31),
(1246, 'TAMBOBAMBA', '01', 32),
(1247, 'COTABAMBAS', '02', 32),
(1248, 'COYLLURQUI', '03', 32),
(1249, 'HAQUIRA', '04', 32),
(1250, 'MARA', '05', 32),
(1251, 'CHALLHUAHUACHO', '06', 32),
(1252, 'CHINCHEROS', '01', 33),
(1253, 'ANCO-HUALLO', '02', 33),
(1254, 'COCHARCAS', '03', 33),
(1255, 'HUACCANA', '04', 33),
(1256, 'OCOBAMBA', '05', 33),
(1257, 'ONGOY', '06', 33),
(1258, 'URANMARCA', '07', 33),
(1259, 'RANRACANCHA', '08', 33),
(1260, 'CHUQUIBAMBILLA', '01', 34),
(1261, 'CURPAHUASI', '02', 34),
(1262, 'GAMARRA', '03', 34),
(1263, 'HUAYLLATI', '04', 34),
(1264, 'MAMARA', '05', 34),
(1265, 'MICAELA BASTIDAS', '06', 34),
(1266, 'PATAYPAMPA', '07', 34),
(1267, 'PROGRESO', '08', 34),
(1268, 'SAN ANTONIO', '09', 34),
(1269, 'SANTA ROSA', '10', 34),
(1270, 'TURPAY', '11', 34),
(1271, 'VILCABAMBA', '12', 34),
(1272, 'VIRUNDO', '13', 34),
(1273, 'CURASCO', '14', 34),
(1274, 'AREQUIPA', '01', 35),
(1275, 'ALTO SELVA ALEGRE', '02', 35),
(1276, 'CAYMA', '03', 35),
(1277, 'CERRO COLORADO', '04', 35),
(1278, 'CHARACATO', '05', 35),
(1279, 'CHIGUATA', '06', 35),
(1280, 'JACOBO HUNTER', '07', 35),
(1281, 'LA JOYA', '08', 35),
(1282, 'MARIANO MELGAR', '09', 35),
(1283, 'MIRAFLORES', '10', 35),
(1284, 'MOLLEBAYA', '11', 35),
(1285, 'PAUCARPATA', '12', 35),
(1286, 'POCSI', '13', 35),
(1287, 'POLOBAYA', '14', 35),
(1288, 'QUEQUE?A', '15', 35),
(1289, 'SABANDIA', '16', 35),
(1290, 'SACHACA', '17', 35),
(1291, 'SAN JUAN DE SIGUAS', '18', 35),
(1292, 'SAN JUAN DE TARUCANI', '19', 35),
(1293, 'SANTA ISABEL DE SIGUAS', '20', 35),
(1294, 'SANTA RITA DE SIGUAS', '21', 35),
(1295, 'SOCABAYA', '22', 35),
(1296, 'TIABAYA', '23', 35),
(1297, 'UCHUMAYO', '24', 35),
(1298, 'VITOR', '25', 35),
(1299, 'YANAHUARA', '26', 35),
(1300, 'YARABAMBA', '27', 35),
(1301, 'YURA', '28', 35),
(1302, 'JOSE LUIS BUSTAMANTE Y RIVERO', '29', 35),
(1303, 'CAMANA', '01', 36),
(1304, 'JOSE MARIA QUIMPER', '02', 36),
(1305, 'MARIANO NICOLAS VALCARCEL', '03', 36),
(1306, 'MARISCAL CACERES', '04', 36),
(1307, 'NICOLAS DE PIEROLA', '05', 36),
(1308, 'OCO?A', '06', 36),
(1309, 'QUILCA', '07', 36),
(1310, 'SAMUEL PASTOR', '08', 36),
(1311, 'CARAVELI', '01', 37),
(1312, 'ACARI', '02', 37),
(1313, 'ATICO', '03', 37),
(1314, 'ATIQUIPA', '04', 37),
(1315, 'BELLA UNION', '05', 37),
(1316, 'CAHUACHO', '06', 37),
(1317, 'CHALA', '07', 37),
(1318, 'CHAPARRA', '08', 37),
(1319, 'HUANUHUANU', '09', 37),
(1320, 'JAQUI', '10', 37),
(1321, 'LOMAS', '11', 37),
(1322, 'QUICACHA', '12', 37),
(1323, 'YAUCA', '13', 37),
(1324, 'APLAO', '01', 38),
(1325, 'ANDAGUA', '02', 38),
(1326, 'AYO', '03', 38),
(1327, 'CHACHAS', '04', 38),
(1328, 'CHILCAYMARCA', '05', 38),
(1329, 'CHOCO', '06', 38),
(1330, 'HUANCARQUI', '07', 38),
(1331, 'MACHAGUAY', '08', 38),
(1332, 'ORCOPAMPA', '09', 38),
(1333, 'PAMPACOLCA', '10', 38),
(1334, 'TIPAN', '11', 38),
(1335, 'U?ON', '12', 38),
(1336, 'URACA', '13', 38),
(1337, 'VIRACO', '14', 38),
(1338, 'CHIVAY', '01', 39),
(1339, 'ACHOMA', '02', 39),
(1340, 'CABANACONDE', '03', 39),
(1341, 'CALLALLI', '04', 39),
(1342, 'CAYLLOMA', '05', 39),
(1343, 'COPORAQUE', '06', 39),
(1344, 'HUAMBO', '07', 39),
(1345, 'HUANCA', '08', 39),
(1346, 'ICHUPAMPA', '09', 39),
(1347, 'LARI', '10', 39),
(1348, 'LLUTA', '11', 39),
(1349, 'MACA', '12', 39),
(1350, 'MADRIGAL', '13', 39),
(1351, 'SAN ANTONIO DE CHUCA', '14', 39),
(1352, 'SIBAYO', '15', 39),
(1353, 'TAPAY', '16', 39),
(1354, 'TISCO', '17', 39),
(1355, 'TUTI', '18', 39),
(1356, 'YANQUE', '19', 39),
(1357, 'MAJES', '20', 39),
(1358, 'CHUQUIBAMBA', '01', 40),
(1359, 'ANDARAY', '02', 40),
(1360, 'CAYARANI', '03', 40),
(1361, 'CHICHAS', '04', 40),
(1362, 'IRAY', '05', 40),
(1363, 'RIO GRANDE', '06', 40),
(1364, 'SALAMANCA', '07', 40),
(1365, 'YANAQUIHUA', '08', 40),
(1366, 'MOLLENDO', '01', 41),
(1367, 'COCACHACRA', '02', 41),
(1368, 'DEAN VALDIVIA', '03', 41),
(1369, 'ISLAY', '04', 41),
(1370, 'MEJIA', '05', 41),
(1371, 'PUNTA DE BOMBON', '06', 41),
(1372, 'COTAHUASI', '01', 42),
(1373, 'ALCA', '02', 42),
(1374, 'CHARCANA', '03', 42),
(1375, 'HUAYNACOTAS', '04', 42),
(1376, 'PAMPAMARCA', '05', 42),
(1377, 'PUYCA', '06', 42),
(1378, 'QUECHUALLA', '07', 42),
(1379, 'SAYLA', '08', 42),
(1380, 'TAURIA', '09', 42),
(1381, 'TOMEPAMPA', '10', 42),
(1382, 'TORO', '11', 42),
(1383, 'AYACUCHO', '01', 43),
(1384, 'ACOCRO', '02', 43),
(1385, 'ACOS VINCHOS', '03', 43),
(1386, 'CARMEN ALTO', '04', 43),
(1387, 'CHIARA', '05', 43),
(1388, 'OCROS', '06', 43),
(1389, 'PACAYCASA', '07', 43),
(1390, 'QUINUA', '08', 43),
(1391, 'SAN JOSE DE TICLLAS', '09', 43),
(1392, 'SAN JUAN BAUTISTA', '10', 43),
(1393, 'SANTIAGO DE PISCHA', '11', 43),
(1394, 'SOCOS', '12', 43),
(1395, 'TAMBILLO', '13', 43),
(1396, 'VINCHOS', '14', 43),
(1397, 'JESUS NAZARENO', '15', 43),
(1398, 'CANGALLO', '01', 44),
(1399, 'CHUSCHI', '02', 44),
(1400, 'LOS MOROCHUCOS', '03', 44),
(1401, 'MARIA PARADO DE BELLIDO', '04', 44),
(1402, 'PARAS', '05', 44),
(1403, 'TOTOS', '06', 44),
(1404, 'SANCOS', '01', 45),
(1405, 'CARAPO', '02', 45),
(1406, 'SACSAMARCA', '03', 45),
(1407, 'SANTIAGO DE LUCANAMARCA', '04', 45),
(1408, 'HUANTA', '01', 46),
(1409, 'AYAHUANCO', '02', 46),
(1410, 'HUAMANGUILLA', '03', 46),
(1411, 'IGUAIN', '04', 46),
(1412, 'LURICOCHA', '05', 46),
(1413, 'SANTILLANA', '06', 46),
(1414, 'SIVIA', '07', 46),
(1415, 'LLOCHEGUA', '08', 46),
(1416, 'SAN MIGUEL', '01', 47),
(1417, 'ANCO', '02', 47),
(1418, 'AYNA', '03', 47),
(1419, 'CHILCAS', '04', 47),
(1420, 'CHUNGUI', '05', 47),
(1421, 'LUIS CARRANZA', '06', 47),
(1422, 'SANTA ROSA', '07', 47),
(1423, 'TAMBO', '08', 47),
(1424, 'PUQUIO', '01', 48),
(1425, 'AUCARA', '02', 48),
(1426, 'CABANA', '03', 48),
(1427, 'CARMEN SALCEDO', '04', 48),
(1428, 'CHAVI?A', '05', 48),
(1429, 'CHIPAO', '06', 48),
(1430, 'HUAC-HUAS', '07', 48),
(1431, 'LARAMATE', '08', 48),
(1432, 'LEONCIO PRADO', '09', 48),
(1433, 'LLAUTA', '10', 48),
(1434, 'LUCANAS', '11', 48),
(1435, 'OCA?A', '12', 48),
(1436, 'OTOCA', '13', 48),
(1437, 'SAISA', '14', 48),
(1438, 'SAN CRISTOBAL', '15', 48),
(1439, 'SAN JUAN', '16', 48),
(1440, 'SAN PEDRO', '17', 48),
(1441, 'SAN PEDRO DE PALCO', '18', 48),
(1442, 'SANCOS', '19', 48),
(1443, 'SANTA ANA DE HUAYCAHUACHO', '20', 48),
(1444, 'SANTA LUCIA', '21', 48),
(1445, 'CORACORA', '01', 49),
(1446, 'CHUMPI', '02', 49),
(1447, 'CORONEL CASTA?EDA', '03', 49),
(1448, 'PACAPAUSA', '04', 49),
(1449, 'PULLO', '05', 49),
(1450, 'PUYUSCA', '06', 49),
(1451, 'SAN FRANCISCO DE RAVACAYCO', '07', 49),
(1452, 'UPAHUACHO', '08', 49),
(1453, 'PAUSA', '01', 50),
(1454, 'COLTA', '02', 50),
(1455, 'CORCULLA', '03', 50),
(1456, 'LAMPA', '04', 50),
(1457, 'MARCABAMBA', '05', 50),
(1458, 'OYOLO', '06', 50),
(1459, 'PARARCA', '07', 50),
(1460, 'SAN JAVIER DE ALPABAMBA', '08', 50),
(1461, 'SAN JOSE DE USHUA', '09', 50),
(1462, 'SARA SARA', '10', 50),
(1463, 'QUEROBAMBA', '01', 51),
(1464, 'BELEN', '02', 51),
(1465, 'CHALCOS', '03', 51),
(1466, 'CHILCAYOC', '04', 51),
(1467, 'HUACA?A', '05', 51),
(1468, 'MORCOLLA', '06', 51),
(1469, 'PAICO', '07', 51),
(1470, 'SAN PEDRO DE LARCAY', '08', 51),
(1471, 'SAN SALVADOR DE QUIJE', '09', 51),
(1472, 'SANTIAGO DE PAUCARAY', '10', 51),
(1473, 'SORAS', '11', 51),
(1474, 'HUANCAPI', '01', 52),
(1475, 'ALCAMENCA', '02', 52),
(1476, 'APONGO', '03', 52),
(1477, 'ASQUIPATA', '04', 52),
(1478, 'CANARIA', '05', 52),
(1479, 'CAYARA', '06', 52),
(1480, 'COLCA', '07', 52),
(1481, 'HUAMANQUIQUIA', '08', 52),
(1482, 'HUANCARAYLLA', '09', 52),
(1483, 'HUAYA', '10', 52),
(1484, 'SARHUA', '11', 52),
(1485, 'VILCANCHOS', '12', 52),
(1486, 'VILCAS HUAMAN', '01', 53),
(1487, 'ACCOMARCA', '02', 53),
(1488, 'CARHUANCA', '03', 53),
(1489, 'CONCEPCION', '04', 53),
(1490, 'HUAMBALPA', '05', 53),
(1491, 'INDEPENDENCIA', '06', 53),
(1492, 'SAURAMA', '07', 53),
(1493, 'VISCHONGO', '08', 53),
(1494, 'CAJAMARCA', '01', 54),
(1495, 'ASUNCION', '02', 54),
(1496, 'CHETILLA', '03', 54),
(1497, 'COSPAN', '04', 54),
(1498, 'ENCA?ADA', '05', 54),
(1499, 'JESUS', '06', 54),
(1500, 'LLACANORA', '07', 54),
(1501, 'LOS BA?OS DEL INCA', '08', 54),
(1502, 'MAGDALENA', '09', 54),
(1503, 'MATARA', '10', 54),
(1504, 'NAMORA', '11', 54),
(1505, 'SAN JUAN', '12', 54),
(1506, 'CAJABAMBA', '01', 55),
(1507, 'CACHACHI', '02', 55),
(1508, 'CONDEBAMBA', '03', 55),
(1509, 'SITACOCHA', '04', 55),
(1510, 'CELENDIN', '01', 56),
(1511, 'CHUMUCH', '02', 56),
(1512, 'CORTEGANA', '03', 56),
(1513, 'HUASMIN', '04', 56),
(1514, 'JORGE CHAVEZ', '05', 56),
(1515, 'JOSE GALVEZ', '06', 56),
(1516, 'MIGUEL IGLESIAS', '07', 56),
(1517, 'OXAMARCA', '08', 56),
(1518, 'SOROCHUCO', '09', 56),
(1519, 'SUCRE', '10', 56),
(1520, 'UTCO', '11', 56),
(1521, 'LA LIBERTAD DE PALLAN', '12', 56),
(1522, 'CHOTA', '01', 57),
(1523, 'ANGUIA', '02', 57),
(1524, 'CHADIN', '03', 57),
(1525, 'CHIGUIRIP', '04', 57),
(1526, 'CHIMBAN', '05', 57),
(1527, 'CHOROPAMPA', '06', 57),
(1528, 'COCHABAMBA', '07', 57),
(1529, 'CONCHAN', '08', 57),
(1530, 'HUAMBOS', '09', 57),
(1531, 'LAJAS', '10', 57),
(1532, 'LLAMA', '11', 57),
(1533, 'MIRACOSTA', '12', 57),
(1534, 'PACCHA', '13', 57),
(1535, 'PION', '14', 57),
(1536, 'QUEROCOTO', '15', 57),
(1537, 'SAN JUAN DE LICUPIS', '16', 57),
(1538, 'TACABAMBA', '17', 57),
(1539, 'TOCMOCHE', '18', 57),
(1540, 'CHALAMARCA', '19', 57),
(1541, 'CONTUMAZA', '01', 58),
(1542, 'CHILETE', '02', 58),
(1543, 'CUPISNIQUE', '03', 58),
(1544, 'GUZMANGO', '04', 58),
(1545, 'SAN BENITO', '05', 58),
(1546, 'SANTA CRUZ DE TOLED', '06', 58),
(1547, 'TANTARICA', '07', 58),
(1548, 'YONAN', '08', 58),
(1549, 'CUTERVO', '01', 59),
(1550, 'CALLAYUC', '02', 59),
(1551, 'CHOROS', '03', 59),
(1552, 'CUJILLO', '04', 59),
(1553, 'LA RAMADA', '05', 59),
(1554, 'PIMPINGOS', '06', 59),
(1555, 'QUEROCOTILLO', '07', 59),
(1556, 'SAN ANDRES DE CUTERVO', '08', 59),
(1557, 'SAN JUAN DE CUTERVO', '09', 59),
(1558, 'SAN LUIS DE LUCMA', '10', 59),
(1559, 'SANTA CRUZ', '11', 59),
(1560, 'SANTO DOMINGO DE LA CAPILLA', '12', 59),
(1561, 'SANTO TOMAS', '13', 59),
(1562, 'SOCOTA', '14', 59),
(1563, 'TORIBIO CASANOVA', '15', 59),
(1564, 'BAMBAMARCA', '01', 60),
(1565, 'CHUGUR', '02', 60),
(1566, 'HUALGAYOC', '03', 60),
(1567, 'JAEN', '01', 61),
(1568, 'BELLAVISTA', '02', 61),
(1569, 'CHONTALI', '03', 61),
(1570, 'COLASAY', '04', 61),
(1571, 'HUABAL', '05', 61),
(1572, 'LAS PIRIAS', '06', 61),
(1573, 'POMAHUACA', '07', 61),
(1574, 'PUCARA', '08', 61),
(1575, 'SALLIQUE', '09', 61),
(1576, 'SAN FELIPE', '10', 61),
(1577, 'SAN JOSE DEL ALTO', '11', 61),
(1578, 'SANTA ROSA', '12', 61),
(1579, 'SAN IGNACIO', '01', 62),
(1580, 'CHIRINOS', '02', 62),
(1581, 'HUARANGO', '03', 62),
(1582, 'LA COIPA', '04', 62),
(1583, 'NAMBALLE', '05', 62),
(1584, 'SAN JOSE DE LOURDES', '06', 62),
(1585, 'TABACONAS', '07', 62),
(1586, 'PEDRO GALVEZ', '01', 63),
(1587, 'CHANCAY', '02', 63),
(1588, 'EDUARDO VILLANUEVA', '03', 63),
(1589, 'GREGORIO PITA', '04', 63),
(1590, 'ICHOCAN', '05', 63),
(1591, 'JOSE MANUEL QUIROZ', '06', 63),
(1592, 'JOSE SABOGAL', '07', 63),
(1593, 'SAN MIGUEL', '01', 64),
(1594, 'BOLIVAR', '02', 64),
(1595, 'CALQUIS', '03', 64),
(1596, 'CATILLUC', '04', 64),
(1597, 'EL PRADO', '05', 64),
(1598, 'LA FLORIDA', '06', 64),
(1599, 'LLAPA', '07', 64),
(1600, 'NANCHOC', '08', 64),
(1601, 'NIEPOS', '09', 64),
(1602, 'SAN GREGORIO', '10', 64),
(1603, 'SAN SILVESTRE DE COCHAN', '11', 64),
(1604, 'TONGOD', '12', 64),
(1605, 'UNION AGUA BLANCA', '13', 64),
(1606, 'SAN PABLO', '01', 65),
(1607, 'SAN BERNARDINO', '02', 65),
(1608, 'SAN LUIS', '03', 65),
(1609, 'TUMBADEN', '04', 65),
(1610, 'SANTA CRUZ', '01', 66),
(1611, 'ANDABAMBA', '02', 66),
(1612, 'CATACHE', '03', 66),
(1613, 'CHANCAYBA?OS', '04', 66),
(1614, 'LA ESPERANZA', '05', 66),
(1615, 'NINABAMBA', '06', 66),
(1616, 'PULAN', '07', 66),
(1617, 'SAUCEPAMPA', '08', 66),
(1618, 'SEXI', '09', 66),
(1619, 'UTICYACU', '10', 66),
(1620, 'YAUYUCAN', '11', 66),
(1621, 'CALLAO', '01', 67),
(1622, 'BELLAVISTA', '02', 67),
(1623, 'CARMEN DE LA LEGUA REYNOSO', '03', 67),
(1624, 'LA PERLA', '04', 67),
(1625, 'LA PUNTA', '05', 67),
(1626, 'VENTANILLA', '06', 67),
(1627, 'CUSCO', '01', 68),
(1628, 'CCORCA', '02', 68),
(1629, 'POROY', '03', 68),
(1630, 'SAN JERONIMO', '04', 68),
(1631, 'SAN SEBASTIAN', '05', 68),
(1632, 'SANTIAGO', '06', 68),
(1633, 'SAYLLA', '07', 68),
(1634, 'WANCHAQ', '08', 68),
(1635, 'ACOMAYO', '01', 69),
(1636, 'ACOPIA', '02', 69),
(1637, 'ACOS', '03', 69),
(1638, 'MOSOC LLACTA', '04', 69),
(1639, 'POMACANCHI', '05', 69),
(1640, 'RONDOCAN', '06', 69),
(1641, 'SANGARARA', '07', 69),
(1642, 'ANTA', '01', 70),
(1643, 'ANCAHUASI', '02', 70),
(1644, 'CACHIMAYO', '03', 70),
(1645, 'CHINCHAYPUJIO', '04', 70),
(1646, 'HUAROCONDO', '05', 70),
(1647, 'LIMATAMBO', '06', 70),
(1648, 'MOLLEPATA', '07', 70),
(1649, 'PUCYURA', '08', 70),
(1650, 'ZURITE', '09', 70),
(1651, 'CALCA', '01', 71),
(1652, 'COYA', '02', 71),
(1653, 'LAMAY', '03', 71),
(1654, 'LARES', '04', 71),
(1655, 'PISAC', '05', 71),
(1656, 'SAN SALVADOR', '06', 71),
(1657, 'TARAY', '07', 71),
(1658, 'YANATILE', '08', 71),
(1659, 'YANAOCA', '01', 72),
(1660, 'CHECCA', '02', 72),
(1661, 'KUNTURKANKI', '03', 72),
(1662, 'LANGUI', '04', 72),
(1663, 'LAYO', '05', 72),
(1664, 'PAMPAMARCA', '06', 72),
(1665, 'QUEHUE', '07', 72),
(1666, 'TUPAC AMARU', '08', 72),
(1667, 'SICUANI', '01', 73),
(1668, 'CHECACUPE', '02', 73),
(1669, 'COMBAPATA', '03', 73),
(1670, 'MARANGANI', '04', 73),
(1671, 'PITUMARCA', '05', 73),
(1672, 'SAN PABLO', '06', 73),
(1673, 'SAN PEDRO', '07', 73),
(1674, 'TINTA', '08', 73),
(1675, 'SANTO TOMAS', '01', 74),
(1676, 'CAPACMARCA', '02', 74),
(1677, 'CHAMACA', '03', 74),
(1678, 'COLQUEMARCA', '04', 74),
(1679, 'LIVITACA', '05', 74),
(1680, 'LLUSCO', '06', 74),
(1681, 'QUI?OTA', '07', 74),
(1682, 'VELILLE', '08', 74),
(1683, 'ESPINAR', '01', 75),
(1684, 'CONDOROMA', '02', 75),
(1685, 'COPORAQUE', '03', 75),
(1686, 'OCORURO', '04', 75),
(1687, 'PALLPATA', '05', 75),
(1688, 'PICHIGUA', '06', 75),
(1689, 'SUYCKUTAMBO', '07', 75),
(1690, 'ALTO PICHIGUA', '08', 75),
(1691, 'SANTA ANA', '01', 76),
(1692, 'ECHARATE', '02', 76),
(1693, 'HUAYOPATA', '03', 76),
(1694, 'MARANURA', '04', 76),
(1695, 'OCOBAMBA', '05', 76),
(1696, 'QUELLOUNO', '06', 76),
(1697, 'KIMBIRI', '07', 76),
(1698, 'SANTA TERESA', '08', 76),
(1699, 'VILCABAMBA', '09', 76),
(1700, 'PICHARI', '10', 76),
(1701, 'PARURO', '01', 77),
(1702, 'ACCHA', '02', 77),
(1703, 'CCAPI', '03', 77),
(1704, 'COLCHA', '04', 77),
(1705, 'HUANOQUITE', '05', 77),
(1706, 'OMACHA', '06', 77),
(1707, 'PACCARITAMBO', '07', 77),
(1708, 'PILLPINTO', '08', 77),
(1709, 'YAURISQUE', '09', 77),
(1710, 'PAUCARTAMBO', '01', 78),
(1711, 'CAICAY', '02', 78),
(1712, 'CHALLABAMBA', '03', 78),
(1713, 'COLQUEPATA', '04', 78),
(1714, 'HUANCARANI', '05', 78),
(1715, 'KOS?IPATA', '06', 78),
(1716, 'URCOS', '01', 79),
(1717, 'ANDAHUAYLILLAS', '02', 79),
(1718, 'CAMANTI', '03', 79),
(1719, 'CCARHUAYO', '04', 79),
(1720, 'CCATCA', '05', 79),
(1721, 'CUSIPATA', '06', 79),
(1722, 'HUARO', '07', 79),
(1723, 'LUCRE', '08', 79),
(1724, 'MARCAPATA', '09', 79),
(1725, 'OCONGATE', '10', 79);
INSERT INTO `distrito` (`IdDistrito`, `Descripcion`, `Codigo`, `IdProvincia`) VALUES
(1726, 'OROPESA', '11', 79),
(1727, 'QUIQUIJANA', '12', 79),
(1728, 'URUBAMBA', '01', 80),
(1729, 'CHINCHERO', '02', 80),
(1730, 'HUAYLLABAMBA', '03', 80),
(1731, 'MACHUPICCHU', '04', 80),
(1732, 'MARAS', '05', 80),
(1733, 'OLLANTAYTAMBO', '06', 80),
(1734, 'YUCAY', '07', 80),
(1735, 'HUANCAVELICA', '01', 81),
(1736, 'ACOBAMBILLA', '02', 81),
(1737, 'ACORIA', '03', 81),
(1738, 'CONAYCA', '04', 81),
(1739, 'CUENCA', '05', 81),
(1740, 'HUACHOCOLPA', '06', 81),
(1741, 'HUAYLLAHUARA', '07', 81),
(1742, 'IZCUCHACA', '08', 81),
(1743, 'LARIA', '09', 81),
(1744, 'MANTA', '10', 81),
(1745, 'MARISCAL CACERES', '11', 81),
(1746, 'MOYA', '12', 81),
(1747, 'NUEVO OCCORO', '13', 81),
(1748, 'PALCA', '14', 81),
(1749, 'PILCHACA', '15', 81),
(1750, 'VILCA', '16', 81),
(1751, 'YAULI', '17', 81),
(1752, 'ASCENSION', '18', 81),
(1753, 'HUANDO', '19', 81),
(1754, 'ACOBAMBA', '01', 82),
(1755, 'ANDABAMBA', '02', 82),
(1756, 'ANTA', '03', 82),
(1757, 'CAJA', '04', 82),
(1758, 'MARCAS', '05', 82),
(1759, 'PAUCARA', '06', 82),
(1760, 'POMACOCHA', '07', 82),
(1761, 'ROSARIO', '08', 82),
(1762, 'LIRCAY', '01', 83),
(1763, 'ANCHONGA', '02', 83),
(1764, 'CALLANMARCA', '03', 83),
(1765, 'CCOCHACCASA', '04', 83),
(1766, 'CHINCHO', '05', 83),
(1767, 'CONGALLA', '06', 83),
(1768, 'HUANCA-HUANCA', '07', 83),
(1769, 'HUAYLLAY GRANDE', '08', 83),
(1770, 'JULCAMARCA', '09', 83),
(1771, 'SAN ANTONIO DE ANTAPARCO', '10', 83),
(1772, 'SANTO TOMAS DE PATA', '11', 83),
(1773, 'SECCLLA', '12', 83),
(1774, 'CASTROVIRREYNA', '01', 84),
(1775, 'ARMA', '02', 84),
(1776, 'AURAHUA', '03', 84),
(1777, 'CAPILLAS', '04', 84),
(1778, 'CHUPAMARCA', '05', 84),
(1779, 'COCAS', '06', 84),
(1780, 'HUACHOS', '07', 84),
(1781, 'HUAMATAMBO', '08', 84),
(1782, 'MOLLEPAMPA', '09', 84),
(1783, 'SAN JUAN', '10', 84),
(1784, 'SANTA ANA', '11', 84),
(1785, 'TANTARA', '12', 84),
(1786, 'TICRAPO', '13', 84),
(1787, 'CHURCAMPA', '01', 85),
(1788, 'ANCO', '02', 85),
(1789, 'CHINCHIHUASI', '03', 85),
(1790, 'EL CARMEN', '04', 85),
(1791, 'LA MERCED', '05', 85),
(1792, 'LOCROJA', '06', 85),
(1793, 'PAUCARBAMBA', '07', 85),
(1794, 'SAN MIGUEL DE MAYOCC', '08', 85),
(1795, 'SAN PEDRO DE CORIS', '09', 85),
(1796, 'PACHAMARCA', '10', 85),
(1797, 'HUAYTARA', '01', 86),
(1798, 'AYAVI', '02', 86),
(1799, 'CORDOVA', '03', 86),
(1800, 'HUAYACUNDO ARMA', '04', 86),
(1801, 'LARAMARCA', '05', 86),
(1802, 'OCOYO', '06', 86),
(1803, 'PILPICHACA', '07', 86),
(1804, 'QUERCO', '08', 86),
(1805, 'QUITO-ARMA', '09', 86),
(1806, 'SAN ANTONIO DE CUSICANCHA', '10', 86),
(1807, 'SAN FRANCISCO DE SANGAYAICO', '11', 86),
(1808, 'SAN ISIDRO', '12', 86),
(1809, 'SANTIAGO DE CHOCORVOS', '13', 86),
(1810, 'SANTIAGO DE QUIRAHUARA', '14', 86),
(1811, 'SANTO DOMINGO DE CAPILLAS', '15', 86),
(1812, 'TAMBO', '16', 86),
(1813, 'PAMPAS', '01', 87),
(1814, 'ACOSTAMBO', '02', 87),
(1815, 'ACRAQUIA', '03', 87),
(1816, 'AHUAYCHA', '04', 87),
(1817, 'COLCABAMBA', '05', 87),
(1818, 'DANIEL HERNANDEZ', '06', 87),
(1819, 'HUACHOCOLPA', '07', 87),
(1820, 'HUARIBAMBA', '09', 87),
(1821, '?AHUIMPUQUIO', '10', 87),
(1822, 'PAZOS', '11', 87),
(1823, 'QUISHUAR', '13', 87),
(1824, 'SALCABAMBA', '14', 87),
(1825, 'SALCAHUASI', '15', 87),
(1826, 'SAN MARCOS DE ROCCHAC', '16', 87),
(1827, 'SURCUBAMBA', '17', 87),
(1828, 'TINTAY PUNCU', '18', 87);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kardex`
--

DROP TABLE IF EXISTS `kardex`;
CREATE TABLE IF NOT EXISTS `kardex` (
  `IdKardex` int(11) NOT NULL AUTO_INCREMENT,
  `IdSucursal` int(11) NOT NULL,
  `IdMovimiento` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `Cantidad` decimal(10,2) NOT NULL,
  `TipoMoneda` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `PrecioUnidad` decimal(10,2) NOT NULL,
  `Importe` decimal(10,2) NOT NULL,
  `Fecha` datetime NOT NULL,
  `SaldoAnteriorBase` decimal(10,2) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `CantidadBase` decimal(10,2) NOT NULL,
  `SaldoActual` decimal(10,2) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `Ultimo` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'S->Si,N->No',
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N' COMMENT 'N->Normal,A->Anulado',
  PRIMARY KEY (`IdKardex`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listaunidad`
--

DROP TABLE IF EXISTS `listaunidad`;
CREATE TABLE IF NOT EXISTS `listaunidad` (
  `IdListaUnidad` int(11) NOT NULL AUTO_INCREMENT,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `Formula` decimal(10,0) NOT NULL,
  `PrecioCompra` decimal(10,3) NOT NULL,
  `PrecioVenta` decimal(10,3) NOT NULL,
  `PrecioVentaEspecial` decimal(10,3) DEFAULT NULL,
  `Moneda` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`IdListaUnidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `listaunidad`
--

INSERT INTO `listaunidad` (`IdListaUnidad`, `IdProducto`, `IdUnidad`, `IdUnidadBase`, `Formula`, `PrecioCompra`, `PrecioVenta`, `PrecioVentaEspecial`, `Moneda`) VALUES
(1, 1, 1, 1, '1', '20.000', '35.000', '30.000', 'S'),
(2, 2, 1, 1, '1', '15.000', '25.000', '20.000', 'S');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar`
--

DROP TABLE IF EXISTS `lugar`;
CREATE TABLE IF NOT EXISTS `lugar` (
  `IdLugar` int(11) NOT NULL AUTO_INCREMENT,
  `Direccion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Ubigeo` int(11) DEFAULT NULL,
  PRIMARY KEY (`IdLugar`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `lugar`
--

INSERT INTO `lugar` (`IdLugar`, `Direccion`, `Ubigeo`) VALUES
(11, 'XX', 14),
(12, 'YY', 14),
(13, 'AV. MARIANO CORNEJO 554 - CHICLAYO', 409),
(14, 'ANGAMOS', 409),
(15, 'AV. LUIS GONZALES - CHICLAYO', 409);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marca`
--

DROP TABLE IF EXISTS `marca`;
CREATE TABLE IF NOT EXISTS `marca` (
  `IdMarca` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(2) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdMarca`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `marca`
--

INSERT INTO `marca` (`IdMarca`, `Descripcion`, `Abreviatura`, `Estado`) VALUES
(1, 'XX', 'XX', 'N'),
(2, 'YY', 'YY', 'N'),
(3, 'ZZ', 'ZZ', 'N'),
(4, 'AA', 'AA', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE IF NOT EXISTS `modulo` (
  `idmodulo` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `abreviatura` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `estado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idmodulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`idmodulo`, `descripcion`, `abreviatura`, `estado`) VALUES
(1, 'VENTA', 'V', 'N'),
(2, 'CAJA', 'C', 'N'),
(3, 'ALMACEN', 'A', 'N'),
(4, 'COMPRAS', 'CO', 'N'),
(5, 'MANTENIMIENTO', 'MA', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivotraslado`
--

DROP TABLE IF EXISTS `motivotraslado`;
CREATE TABLE IF NOT EXISTS `motivotraslado` (
  `IdMotivoTraslado` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`IdMotivoTraslado`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `motivotraslado`
--

INSERT INTO `motivotraslado` (`IdMotivoTraslado`, `Descripcion`) VALUES
(1, 'VENTA'),
(2, 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR'),
(3, 'COMPRA'),
(4, 'CONCIGNACION'),
(5, 'DEVOLUCION'),
(6, 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRES'),
(7, 'TRASLADO DE BIENES PARA TRANSFORMAS'),
(8, 'RECOJO DE BIENES PARA TRASFORMAR'),
(9, 'TRASLADO POR EMISOR INTINERANTE DE COMPROBANTES DE'),
(10, 'TRASLADO ZONA PRIMARIA'),
(11, 'IMPOTACION'),
(12, 'EXPORTACION'),
(13, 'EXHIBICION'),
(14, 'DEMOTRACION'),
(15, 'OTROS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
CREATE TABLE IF NOT EXISTS `movimiento` (
  `IdMovimiento` int(11) NOT NULL AUTO_INCREMENT,
  `IdSucursal` int(11) NOT NULL,
  `IdTipoMovimiento` int(11) NOT NULL,
  `IdConceptoPago` int(11) NOT NULL,
  `Numero` varchar(15) COLLATE utf8_spanish_ci NOT NULL COMMENT '000000-000',
  `IdTipoDocumento` int(11) NOT NULL,
  `FormaPago` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'A' COMMENT 'A->Contado,B->Credito',
  `Fecha` datetime NOT NULL,
  `Moneda` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S' COMMENT 'S->Soles, D->D?lares.',
  `SubTotal` decimal(10,2) NOT NULL,
  `Igv` decimal(10,2) NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `IdPersona` int(11) NOT NULL,
  `IdResponsable` int(11) NOT NULL,
  `IdMovimientoRef` int(11) NOT NULL,
  `IdSucursalRef` int(11) NOT NULL,
  `Comentario` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N' COMMENT 'N->Normal, A->Anulado',
  `FechaInicioTraslado` date NOT NULL,
  `IdMotivoTraslado` int(11) NOT NULL DEFAULT '0',
  `IdTransportista` int(11) NOT NULL DEFAULT '0',
  `IdLugarPartida` int(11) NOT NULL DEFAULT '0',
  `IdLugarDestino` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`IdMovimiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`IdMovimiento`, `IdSucursal`, `IdTipoMovimiento`, `IdConceptoPago`, `Numero`, `IdTipoDocumento`, `FormaPago`, `Fecha`, `Moneda`, `SubTotal`, `Igv`, `Total`, `IdUsuario`, `IdPersona`, `IdResponsable`, `IdMovimientoRef`, `IdSucursalRef`, `Comentario`, `Estado`, `FechaInicioTraslado`, `IdMotivoTraslado`, `IdTransportista`, `IdLugarPartida`, `IdLugarDestino`) VALUES
(1, 1, 3, 1, '001-000001-2011', 10, 'A', '2011-12-17 00:00:00', 'S', '0.00', '0.00', '0.00', 4, 1, 4, 0, 0, 'CAJA APERTURADA EN SOLES EL 2011-12-17', 'N', '0000-00-00', 0, 0, 0, 0),
(2, 1, 3, 1, '001-000002-2011', 10, 'A', '2011-12-17 00:00:00', 'D', '0.00', '0.00', '0.00', 4, 1, 4, 0, 0, 'CAJA APERTURADA EN DOLARES EL 2011-12-17', 'N', '0000-00-00', 0, 0, 0, 0),
(3, 1, 3, 2, '001-000001-2014', 11, 'A', '2011-12-17 00:00:00', 'S', '0.00', '0.00', '0.00', 3, 1, 3, 0, 0, 'CIERRE DE CAJA EN SOLES DEL2011-12-17', 'N', '0000-00-00', 0, 0, 0, 0),
(4, 1, 3, 2, '001-000002-2014', 11, 'A', '2011-12-17 00:00:00', 'D', '0.00', '0.00', '0.00', 3, 1, 3, 0, 0, 'CIERRE DE CAJA EN DOLARES DEL2011-12-17', 'N', '0000-00-00', 0, 0, 0, 0),
(5, 1, 3, 1, '001-000001-2014', 10, 'A', '2014-11-25 00:00:00', 'S', '0.00', '0.00', '0.00', 3, 1, 3, 0, 0, 'APERTURA CAJA EN SOLES DEL 2014-11-25', 'N', '0000-00-00', 0, 0, 0, 0),
(6, 1, 3, 1, '001-000002-2014', 10, 'A', '2014-11-25 00:00:00', 'D', '0.00', '0.00', '0.00', 3, 1, 3, 0, 0, 'APERTURA CAJA EN DOLARES DEL 2014-11-25', 'N', '0000-00-00', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opcionmenu`
--

DROP TABLE IF EXISTS `opcionmenu`;
CREATE TABLE IF NOT EXISTS `opcionmenu` (
  `IdOpcionMenu` int(11) NOT NULL AUTO_INCREMENT,
  `LinkMenu` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `NombreMenu` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `idmodulo` int(11) NOT NULL,
  PRIMARY KEY (`IdOpcionMenu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=36 ;

--
-- Volcado de datos para la tabla `opcionmenu`
--

INSERT INTO `opcionmenu` (`IdOpcionMenu`, `LinkMenu`, `NombreMenu`, `idmodulo`) VALUES
(1, 'list_persona.php?tipo=EMPRESA', 'Mantenimiento Sucursal', 5),
(2, 'list_armario.php', 'Mantenimiento Armario', 5),
(3, 'list_menu.php', 'Opciones de Menu', 5),
(4, 'list_bitacora.php', 'Listado de Bitacoras', 5),
(5, 'list_categoria.php', 'Mantenimiento Categoria', 5),
(6, 'list_conceptopago.php', 'Mantenimiento Concepto Pago', 5),
(7, 'list_marca.php', 'Mant. Marca/Laboratorio', 5),
(8, 'list_persona.php?tipo=PERSONA', 'Mantenimiento Persona', 5),
(9, 'list_producto.php', 'Mantenimiento Producto', 5),
(10, 'list_sector.php', 'Mantenimiento Sector', 5),
(11, 'list_tipocambio.php', 'Listado Tipo Cambio', 2),
(12, 'list_tipousuario.php', 'Mantenimiento Tipo Usuario', 5),
(13, 'list_unidad.php', 'Mantenimiento Unidad', 5),
(14, 'list_usuario.php', 'Mantenimiento Usuario', 5),
(15, 'list_zona.php', 'Mantenimiento Zona', 5),
(16, 'list_almacen.php', 'Documentos de Almacen', 3),
(17, 'list_compra.php', 'Documentos de Compra', 4),
(18, 'list_ventas.php', 'Documentos de Venta', 2),
(19, 'list_movcaja.php', 'Administrar Caja', 2),
(20, 'rpt_productos.php', 'Reporte de Productos mas Vendidos', 1),
(21, 'rpt_ABCproveedores.php', 'Reporte el ABC de Proveedores', 4),
(22, 'rpt_ABCclientes.php', 'Reporte el ABC de Clientes', 1),
(23, 'rpt_MovCaja.php', 'Reporte de Movimientos de Caja', 2),
(24, 'rpt_proyeccionCP.php', 'Reporte de Cobros y Pagos', 2),
(25, 'rpt_compras.php', 'Reporte de Compras', 4),
(26, 'rpt_ventas.php', 'Reporte de Ventas', 1),
(27, 'rpt_kardex.php', 'Reporte de Kardex Valorizado', 3),
(28, 'list_guias.php', 'Listado de Guias Remision', 3),
(29, 'list_pedido.php', 'Pedidos', 1),
(30, 'list_pedidoventa.php', 'Pedidos por atender', 2),
(31, 'list_ventasalmacen.php', 'Ventas a Despachar', 3),
(32, 'list_pedidosucursal.php', 'Pedidos de Sucursales', 3),
(33, 'list_productostockcero.php', 'Reporte de Productos con Stock Mínimo Superado', 3),
(34, 'list_talla.php', 'Mantenimiento Talla', 5),
(35, 'list_color.php', 'Mantenimiento Color', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

DROP TABLE IF EXISTS `pais`;
CREATE TABLE IF NOT EXISTS `pais` (
  `IdPais` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Nacionalidad` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`IdPais`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `pais`
--

INSERT INTO `pais` (`IdPais`, `Descripcion`, `Nacionalidad`) VALUES
(1, 'PERU - PERUANO', NULL),
(2, 'EXTRANJERO - EXTRANJ', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE IF NOT EXISTS `persona` (
  `IdPersona` int(11) NOT NULL AUTO_INCREMENT,
  `TipoPersona` varchar(8) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Natural (RUC), Jur?dica (RUC) y Varios(DNI)',
  `Nombres` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Apellidos` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `NroDoc` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `Sexo` char(1) COLLATE utf8_spanish_ci NOT NULL,
  `Direccion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Celular` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `Email` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `IdSector` int(11) NOT NULL,
  `IdZona` int(11) NOT NULL,
  `IdDistrito` int(11) NOT NULL DEFAULT '409',
  `Representante` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'N->Normal, A->Anulado',
  PRIMARY KEY (`IdPersona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`IdPersona`, `TipoPersona`, `Nombres`, `Apellidos`, `NroDoc`, `Sexo`, `Direccion`, `Celular`, `Email`, `IdSector`, `IdZona`, `IdDistrito`, `Representante`, `Estado`) VALUES
(1, 'JURIDICA', '', 'ANAQUEL', '123456789', 'M', 'AV. LUIS GONZALES - CHICLAYO', '', '', 1, 1, 409, '', 'N'),
(3, 'NATURAL', 'CARLOS', 'NAZARIO', '43825485', 'M', 'CORNEJO', '222222', 'ffff@ffff.com', 1, 1, 409, '', 'N'),
(4, 'NATURAL', 'GEYNEN', 'MONTENEGRO COCHAS', '12345678', 'M', 'ANGAMOS', '979368623', 'geynen_0710@hotmail.com', 1, 1, 409, '', 'N'),
(5, 'NATURAL', 'VARIOS', 'CLIENTE', '-', 'M', '', '', '', 1, 1, 409, '', 'A'),
(8, 'NATURAL', 'VLADIMIR', 'ZE', '66', 'M', '', '', '', 1, 1, 409, '', 'A'),
(9, 'NATURAL', 'CAJERO', 'CAJERO', '3456778585', 'M', 'R', '', '', 1, 1, 409, '', 'A'),
(17, 'NATURAL', 'CESAR', 'CAPU', '1234567890', 'M', '--', '--', '-', 1, 1, 409, '', 'A'),
(19, 'NATURAL', 'CORPORACION LEGOCSA', 'CORPORACION LEGOCSA', '45538382', 'M', 'BOLOGNESI', '212312', 'xxxx@hotmail.com', 1, 1, 409, '', 'N'),
(20, 'NATURAL', 'EL DIAL', 'EL DIAL', '0394948937', 'M', 'BALTA', '978238801', 'yarac_16@hotmail.com', 1, 1, 409, '', 'N'),
(21, 'NATURAL', 'ANAVEL', 'BARRANTES CALDERON', '43206778', 'F', 'HUASCAR', '978383801', 'yarac_16@hotmail.com', 1, 1, 409, '', 'A'),
(22, 'JURIDICA', 'ISELA', 'BARRANTES CALDERON', '47324567', 'F', 'HUASAR 537', '987878789', 'issela@hotmail.com', 1, 1, 409, '', 'A'),
(23, 'JURIDICA', 'CONSTRUCTORA INGENIEROS', 'INGENIEROS', '4325252525', 'M', 'PRADERA 123', '250930', 'gerardo@consting.com', 1, 1, 435, '', 'A'),
(24, 'NATURAL', 'PATRIX', 'BAZAN CALDERON', '234678', 'M', 'SANTA VICTORIA', '250930', 'XXX@HOTMAIL.COM', 1, 1, 409, '', 'A'),
(25, 'NATURAL', 'KAREN', 'MONTOYA', '1111111111', 'M', '', '', '', 1, 1, 409, '', 'A'),
(26, 'JURIDICA', '', 'LOGIN STORE', '1212121212', 'M', '-', '-', '-', 1, 1, 409, '', 'A'),
(27, 'JURIDICA', '', 'ALMACEN', '1234567890', 'M', 'XXX', '', '', 1, 1, 409, '', 'N'),
(28, 'NATURAL', 'CAJERO', 'CAJERO', '43458811', 'M', '', '', '', 1, 1, 409, '', 'A'),
(29, 'NATURAL', 'CAJERO', 'CAJERO', '12345678', 'M', '', '', '', 1, 1, 409, '', 'A'),
(30, 'NATURAL', 'CAJERO2', 'CAJERO', '98778432', 'M', '', '', '', 1, 1, 409, '', 'A'),
(31, 'NATURAL', 'CAJERO', 'CAJERO', '47586912', 'M', '', '', '', 1, 1, 409, '', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `IdProducto` int(11) NOT NULL AUTO_INCREMENT,
  `Codigo` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Descripcion` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `IdCategoria` int(11) NOT NULL,
  `IdMarca` int(11) NOT NULL,
  `IdColor` int(11) NOT NULL,
  `IdTalla` int(11) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `Peso` decimal(18,2) NOT NULL,
  `IdMedidaPeso` int(11) NOT NULL COMMENT 'Se relaciona con la tabla unidad',
  `StockSeguridad` decimal(10,2) NOT NULL,
  `IdArmario` int(11) NOT NULL,
  `Columna` int(11) NOT NULL,
  `Fila` int(11) NOT NULL,
  `Kardex` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'S' COMMENT 'S->Si, N->No',
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N' COMMENT 'N->Normal, A->Anulado',
  `RecetaMedica` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N' COMMENT 'N->No, S->Si',
  PRIMARY KEY (`IdProducto`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`IdProducto`, `Codigo`, `Descripcion`, `IdCategoria`, `IdMarca`, `IdColor`, `IdTalla`, `IdUnidadBase`, `Peso`, `IdMedidaPeso`, `StockSeguridad`, `IdArmario`, `Columna`, `Fila`, `Kardex`, `Estado`, `RecetaMedica`) VALUES
(1, 'CA-AA-001', 'CAMISA MODERNA', 1, 4, 2, 1, 1, '0.00', 4, '5.00', 1, 1, 1, 'S', 'N', 'S'),
(2, 'PO-AA-001', 'POLO RIPCURL', 2, 4, 3, 1, 1, '0.00', 4, '5.00', 1, 1, 1, 'S', 'N', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

DROP TABLE IF EXISTS `provincia`;
CREATE TABLE IF NOT EXISTS `provincia` (
  `IdProvincia` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Codigo` varchar(2) COLLATE utf8_spanish_ci DEFAULT NULL,
  `IdDepartamento` int(11) NOT NULL,
  PRIMARY KEY (`IdProvincia`),
  KEY `XIF1PROVINCIA` (`IdDepartamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=195 ;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`IdProvincia`, `Descripcion`, `Codigo`, `IdDepartamento`) VALUES
(1, 'CHACHAPOYAS', '01', 1),
(2, 'BAGUA', '02', 1),
(3, 'BONGARA', '03', 1),
(4, 'CONDORCANQUI', '04', 1),
(5, 'LUYA', '05', 1),
(6, 'RODRIGUEZ DE MENDOZA', '06', 1),
(7, 'UTCUBAMBA', '07', 1),
(8, 'HUARAZ', '01', 2),
(9, 'AIJA', '02', 2),
(10, 'ANTONIO RAYMONDI', '03', 2),
(11, 'ASUNCION', '04', 2),
(12, 'BOLOGNESI', '05', 2),
(13, 'CARHUAZ', '06', 2),
(14, 'CARLOS FERMIN FITZCARRALD', '07', 2),
(15, 'CASMA', '08', 2),
(16, 'CORONGO', '09', 2),
(17, 'HUARI', '10', 2),
(18, 'HUARMEY', '11', 2),
(19, 'HUAYLAS', '12', 2),
(20, 'MARISCAL LUZURIAGA', '13', 2),
(21, 'OCROS', '14', 2),
(22, 'PALLASCA', '15', 2),
(23, 'POMABAMBA', '16', 2),
(24, 'RECUAY', '17', 2),
(25, 'SANTA', '18', 2),
(26, 'SIHUAS', '19', 2),
(27, 'YUNGAY', '20', 2),
(28, 'ABANCAY', '01', 3),
(29, 'ANDAHUAYLAS', '02', 3),
(30, 'ANTABAMBA', '03', 3),
(31, 'AYMARAES', '04', 3),
(32, 'COTABAMBAS', '05', 3),
(33, 'CHINCHEROS', '06', 3),
(34, 'GRAU', '07', 3),
(35, 'AREQUIPA', '01', 4),
(36, 'CAMANA', '02', 4),
(37, 'CARAVELI', '03', 4),
(38, 'CASTILLA', '04', 4),
(39, 'CAYLLOMA', '05', 4),
(40, 'CONDESUYOS', '06', 4),
(41, 'ISLAY', '07', 4),
(42, 'LA UNION', '08', 4),
(43, 'HUAMANGA', '01', 5),
(44, 'CANGALLO', '02', 5),
(45, 'HUANCA SANCOS', '03', 5),
(46, 'HUANTA', '04', 5),
(47, 'LA MAR', '05', 5),
(48, 'LUCANAS', '06', 5),
(49, 'PARINACOCHAS', '07', 5),
(50, 'PAUCAR DEL SARA SARA', '08', 5),
(51, 'SUCRE', '09', 5),
(52, 'VICTOR FAJARDO', '10', 5),
(53, 'VILCAS HUAMAN', '11', 5),
(54, 'CAJAMARCA', '01', 6),
(55, 'CAJABAMBA', '02', 6),
(56, 'CELENDIN', '03', 6),
(57, 'CHOTA', '04', 6),
(58, 'CONTUMAZA', '05', 6),
(59, 'CUTERVO', '06', 6),
(60, 'HUALGAYOC', '07', 6),
(61, 'JAEN', '08', 6),
(62, 'SAN IGNACIO', '09', 6),
(63, 'SAN MARCOS', '10', 6),
(64, 'SAN MIGUEL', '11', 6),
(65, 'SAN PABLO', '12', 6),
(66, 'SANTA CRUZ', '13', 6),
(67, 'CALLAO', '01', 7),
(68, 'CUSCO', '01', 8),
(69, 'ACOMAYO', '02', 8),
(70, 'ANTA', '03', 8),
(71, 'CALCA', '04', 8),
(72, 'CANAS', '05', 8),
(73, 'CANCHIS', '06', 8),
(74, 'CHUMBIVILCAS', '07', 8),
(75, 'ESPINAR', '08', 8),
(76, 'LA CONVENCION', '09', 8),
(77, 'PARURO', '10', 8),
(78, 'PAUCARTAMBO', '11', 8),
(79, 'QUISPICANCHI', '12', 8),
(80, 'URUBAMBA', '13', 8),
(81, 'HUANCAVELICA', '01', 9),
(82, 'ACOBAMBA', '02', 9),
(83, 'ANGARAES', '03', 9),
(84, 'CASTROVIRREYNA', '04', 9),
(85, 'CHURCAMPA', '05', 9),
(86, 'HUAYTARA', '06', 9),
(87, 'TAYACAJA', '07', 9),
(88, 'HUANUCO', '01', 10),
(89, 'AMBO', '02', 10),
(90, 'DOS DE MAYO', '03', 10),
(91, 'HUACAYBAMBA', '04', 10),
(92, 'HUAMALIES', '05', 10),
(93, 'LEONCIO PRADO', '06', 10),
(94, 'MARA?ON', '07', 10),
(95, 'PACHITEA', '08', 10),
(96, 'PUERTO INCA', '09', 10),
(97, 'LAURICOCHA', '10', 10),
(98, 'YAROWILCA', '11', 10),
(99, 'ICA', '01', 11),
(100, 'CHINCHA', '02', 11),
(101, 'NAZCA', '03', 11),
(102, 'PALPA', '04', 11),
(103, 'PISCO', '05', 11),
(104, 'HUANCAYO', '01', 12),
(105, 'CONCEPCION', '02', 12),
(106, 'CHANCHAMAYO', '03', 12),
(107, 'JAUJA', '04', 12),
(108, 'JUNIN', '05', 12),
(109, 'SATIPO', '06', 12),
(110, 'TARMA', '07', 12),
(111, 'YAULI', '08', 12),
(112, 'CHUPACA', '09', 12),
(113, 'TRUJILLO', '01', 13),
(114, 'ASCOPE', '02', 13),
(115, 'BOLIVAR', '03', 13),
(116, 'CHEPEN', '04', 13),
(117, 'JULCAN', '05', 13),
(118, 'OTUZCO', '06', 13),
(119, 'PACASMAYO', '07', 13),
(120, 'PATAZ', '08', 13),
(121, 'SANCHEZ CARRION', '09', 13),
(122, 'SANTIAGO DE CHUCO', '10', 13),
(123, 'GRAN CHIMU', '11', 13),
(124, 'VIRU', '12', 13),
(125, 'CHICLAYO', '01', 14),
(126, 'FERRE?AFE', '02', 14),
(127, 'LAMBAYEQUE', '03', 14),
(128, 'LIMA', '01', 15),
(129, 'BARRANCA', '02', 15),
(130, 'CAJATAMBO', '03', 15),
(131, 'CANTA', '04', 15),
(132, 'CA?ETE', '05', 15),
(133, 'HUARAL', '06', 15),
(134, 'HUAROCHIRI', '07', 15),
(135, 'HUAURA', '08', 15),
(136, 'OYON', '09', 15),
(137, 'YAUYOS', '10', 15),
(138, 'MAYNAS', '01', 16),
(139, 'ALTO AMAZONAS', '02', 16),
(140, 'LORETO', '03', 16),
(141, 'MARISCAL RAMON CASTILLA', '04', 16),
(142, 'REQUENA', '05', 16),
(143, 'UCAYALI', '06', 16),
(144, 'TAMBOPATA', '01', 17),
(145, 'MANU', '02', 17),
(146, 'TAHUAMANU', '03', 17),
(147, 'MARISCAL NIETO', '01', 18),
(148, 'GENERAL SANCHEZ CERRO', '02', 18),
(149, 'ILO', '03', 18),
(150, 'PASCO', '01', 19),
(151, 'DANIEL ALCIDES CARRION', '02', 19),
(152, 'OXAPAMPA', '03', 19),
(153, 'PIURA', '01', 20),
(154, 'AYABACA', '02', 20),
(155, 'HUANCABAMBA', '03', 20),
(156, 'MORROPON', '04', 20),
(157, 'PAITA', '05', 20),
(158, 'SULLANA', '06', 20),
(159, 'TALARA', '07', 20),
(160, 'SECHURA', '08', 20),
(161, 'PUNO', '01', 21),
(162, 'AZANGARO', '02', 21),
(163, 'CARABAYA', '03', 21),
(164, 'CHUCUITO', '04', 21),
(165, 'EL COLLAO', '05', 21),
(166, 'HUANCANE', '06', 21),
(167, 'LAMPA', '07', 21),
(168, 'MELGAR', '08', 21),
(169, 'MOHO', '09', 21),
(170, 'SAN ANTONIO DE PUTINA', '10', 21),
(171, 'SAN ROMAN', '11', 21),
(172, 'SANDIA', '12', 21),
(173, 'YUNGUYO', '13', 21),
(174, 'MOYOBAMBA', '01', 22),
(175, 'BELLAVISTA', '02', 22),
(176, 'EL DORADO', '03', 22),
(177, 'HUALLAGA', '04', 22),
(178, 'LAMAS', '05', 22),
(179, 'MARISCAL CACERES', '06', 22),
(180, 'PICOTA', '07', 22),
(181, 'RIOJA', '08', 22),
(182, 'SAN MARTIN', '09', 22),
(183, 'TOCACHE', '10', 22),
(184, 'TACNA', '01', 23),
(185, 'CANDARAVE', '02', 23),
(186, 'JORGE BASADRE', '03', 23),
(187, 'TARATA', '04', 23),
(188, 'TUMBES', '01', 24),
(189, 'CONTRALMIRANTE VILLAR', '02', 24),
(190, 'ZARUMILLA', '03', 24),
(191, 'CORONEL PORTILLO', '01', 25),
(192, 'ATALAYA', '02', 25),
(193, 'PADRE ABAD', '03', 25),
(194, 'PURUS', '04', 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `IdRol` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdRol`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Indicara si la persona es: Cliente, Proveedor, Usuario, Empl' AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`IdRol`, `Descripcion`, `Estado`) VALUES
(1, 'CLIENTE', 'N'),
(2, 'PROVEEDOR', 'N'),
(3, 'EMPLEADO', 'N'),
(4, 'USUARIO', 'N'),
(5, 'SUCURSAL', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolpersona`
--

DROP TABLE IF EXISTS `rolpersona`;
CREATE TABLE IF NOT EXISTS `rolpersona` (
  `IdRolPersona` int(11) NOT NULL AUTO_INCREMENT,
  `IdPersona` int(11) NOT NULL,
  `IdRol` int(11) NOT NULL,
  PRIMARY KEY (`IdRolPersona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Permite asignar a una persona muchos roles.' AUTO_INCREMENT=48 ;

--
-- Volcado de datos para la tabla `rolpersona`
--

INSERT INTO `rolpersona` (`IdRolPersona`, `IdPersona`, `IdRol`) VALUES
(7, 1, 5),
(8, 2, 5),
(9, 3, 1),
(10, 3, 2),
(11, 3, 3),
(12, 3, 4),
(13, 4, 1),
(14, 4, 2),
(15, 4, 3),
(16, 4, 4),
(17, 5, 2),
(18, 6, 3),
(19, 7, 1),
(20, 5, 1),
(21, 6, 1),
(22, 7, 1),
(23, 8, 1),
(24, 9, 1),
(25, 10, 1),
(26, 11, 1),
(27, 12, 1),
(28, 13, 1),
(29, 14, 1),
(30, 15, 1),
(31, 16, 1),
(32, 17, 1),
(33, 17, 2),
(34, 18, 3),
(35, 19, 1),
(36, 20, 2),
(37, 21, 1),
(38, 22, 1),
(39, 23, 1),
(40, 24, 4),
(41, 25, 3),
(42, 26, 1),
(43, 27, 5),
(44, 28, 1),
(45, 29, 3),
(46, 30, 1),
(47, 31, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sector`
--

DROP TABLE IF EXISTS `sector`;
CREATE TABLE IF NOT EXISTS `sector` (
  `IdSector` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdSector`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sector`
--

INSERT INTO `sector` (`IdSector`, `Descripcion`) VALUES
(1, 'CIX'),
(2, 'TRUJILLO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stockproducto`
--

DROP TABLE IF EXISTS `stockproducto`;
CREATE TABLE IF NOT EXISTS `stockproducto` (
  `IdStockProducto` int(11) NOT NULL AUTO_INCREMENT,
  `IdSucursal` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `Stock` decimal(10,2) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `StockBase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`IdStockProducto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE IF NOT EXISTS `sucursal` (
  `IdSucursal` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Direccion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `RUC` varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdSucursal`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`IdSucursal`, `Nombre`, `Direccion`, `RUC`) VALUES
(1, 'FERRETERIA EL IMAN EIRL', 'AV. MARIANO CORNEJO 554 - CHICLAYO', '20479570524'),
(2, 'COMERCIAL PISFIL EIRL', 'AV. LA FLORIDA 294 - NUEVO CAJAMARCA', '20531343353');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talla`
--

DROP TABLE IF EXISTS `talla`;
CREATE TABLE IF NOT EXISTS `talla` (
  `IdTalla` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdTalla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `talla`
--

INSERT INTO `talla` (`IdTalla`, `Nombre`, `Abreviatura`, `Estado`) VALUES
(1, 'SMALL', 'S', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocambio`
--

DROP TABLE IF EXISTS `tipocambio`;
CREATE TABLE IF NOT EXISTS `tipocambio` (
  `IdTipocambio` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `Cambio` decimal(10,2) NOT NULL,
  PRIMARY KEY (`IdTipocambio`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=64 ;

--
-- Volcado de datos para la tabla `tipocambio`
--

INSERT INTO `tipocambio` (`IdTipocambio`, `Fecha`, `Cambio`) VALUES
(1, '2010-11-11', '2.80'),
(2, '2010-12-11', '2.85'),
(3, '2010-12-12', '2.85'),
(4, '2010-12-24', '2.85'),
(5, '2011-01-06', '2.85'),
(6, '2011-01-09', '2.85'),
(7, '2011-01-10', '2.85'),
(8, '2011-01-11', '2.85'),
(9, '2011-01-12', '2.85'),
(10, '2011-01-13', '2.85'),
(11, '2011-01-15', '2.85'),
(12, '2011-01-16', '2.85'),
(13, '2011-01-17', '2.85'),
(14, '2011-01-18', '2.85'),
(15, '2011-01-19', '2.85'),
(16, '2011-01-20', '2.85'),
(17, '2011-01-24', '2.85'),
(18, '2011-01-25', '2.85'),
(19, '2011-01-26', '2.85'),
(20, '2011-01-27', '2.85'),
(21, '2011-01-30', '2.85'),
(22, '2011-02-03', '2.85'),
(23, '2011-02-04', '2.85'),
(24, '2011-02-07', '2.85'),
(25, '2011-02-08', '2.85'),
(26, '2011-02-18', '2.85'),
(27, '2011-02-21', '2.85'),
(28, '2011-02-23', '2.85'),
(29, '2011-02-25', '2.85'),
(30, '2011-03-05', '2.85'),
(31, '2011-03-08', '2.85'),
(32, '2011-03-15', '2.85'),
(33, '2011-03-20', '2.85'),
(34, '2011-04-13', '2.85'),
(35, '2011-04-14', '2.85'),
(36, '2011-04-15', '2.85'),
(37, '2011-04-16', '2.85'),
(38, '2011-04-24', '2.85'),
(39, '2011-04-25', '2.85'),
(40, '2011-04-27', '2.85'),
(41, '2011-04-28', '2.85'),
(42, '2011-05-03', '2.85'),
(43, '2011-05-18', '2.85'),
(44, '2011-05-21', '2.85'),
(45, '2011-05-25', '2.85'),
(46, '2011-05-27', '2.85'),
(47, '2011-05-28', '2.85'),
(48, '2011-06-01', '2.85'),
(49, '2011-07-02', '2.85'),
(50, '2011-08-05', '2.85'),
(51, '2011-08-11', '2.85'),
(52, '2011-08-12', '2.85'),
(53, '2011-08-13', '2.85'),
(54, '2011-08-20', '2.85'),
(55, '2011-08-24', '2.85'),
(56, '2011-08-25', '2.85'),
(57, '2011-08-26', '2.85'),
(58, '2011-08-27', '2.85'),
(59, '2011-09-12', '2.85'),
(60, '2011-09-15', '2.85'),
(61, '2011-11-17', '2.85'),
(62, '2011-12-17', '2.85'),
(63, '2014-11-25', '2.85');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

DROP TABLE IF EXISTS `tipodocumento`;
CREATE TABLE IF NOT EXISTS `tipodocumento` (
  `IdTipoDocumento` int(12) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Stock` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'indicara la forma como este mueve stock: R->Resta Stock, S->Suma Stock y N-> Neutro (Ejm: Cotizaciones, ?rdenes de Compra).',
  PRIMARY KEY (`IdTipoDocumento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contendr? el tipo de documento, pudiendo ser Boleta, Factura' AUTO_INCREMENT=16 ;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`IdTipoDocumento`, `Descripcion`, `Abreviatura`, `Stock`) VALUES
(1, 'BOLETA VENTA', 'B/V', 'R'),
(2, 'FACTURA VENTA', 'F/V', 'R'),
(3, 'BOLETA COMPRA', 'B/C', 'S'),
(4, 'FACTURA COMPRA', 'F/C', 'S'),
(5, 'GUIA REMISION EMISOR', 'GRE', 'R'),
(6, 'GUIA REMISION RECEPTOR', 'GRR', 'S'),
(7, 'COTIZACION VENTA', 'COT', 'N'),
(8, 'NOTA DE CREDITO COMPRA', 'NCC', 'R'),
(9, 'NOTA DE CREDITO VENTA', 'NCV', 'S'),
(10, 'INGRESO', 'I', 'N'),
(11, 'EGRESO', 'E', 'N'),
(12, 'RECIBO VENTA', 'R/V', 'R'),
(13, 'PEDIDO', 'P', 'N'),
(14, 'PEDIDO SUCURSALES', 'PS', 'N'),
(15, 'DESPACHO PEDIDO SUCURSAL', 'DPS', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomovimiento`
--

DROP TABLE IF EXISTS `tipomovimiento`;
CREATE TABLE IF NOT EXISTS `tipomovimiento` (
  `IdTipoMovimiento` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdTipoMovimiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Ejm: Compra, Venta, Caja, Almacen' AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `tipomovimiento`
--

INSERT INTO `tipomovimiento` (`IdTipoMovimiento`, `Descripcion`) VALUES
(1, 'COMPRA'),
(2, 'VENTA'),
(3, 'CAJA'),
(4, 'ALMACEN'),
(5, 'PEDIDO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

DROP TABLE IF EXISTS `tipousuario`;
CREATE TABLE IF NOT EXISTS `tipousuario` (
  `IdTipoUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'N',
  PRIMARY KEY (`IdTipoUsuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`IdTipoUsuario`, `Descripcion`, `Estado`) VALUES
(1, 'ADMINISTRACION', 'N'),
(2, 'VENDEDOR', 'N'),
(3, 'ALMACEN', 'N'),
(4, 'CAJERO', 'N'),
(5, 'COMPRAS', 'N'),
(6, 'GERENTE', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transportista`
--

DROP TABLE IF EXISTS `transportista`;
CREATE TABLE IF NOT EXISTS `transportista` (
  `IdTransportista` int(11) NOT NULL AUTO_INCREMENT,
  `IdSucursal` int(11) DEFAULT NULL,
  `NombreRazonSocial` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `DniRuc` varchar(11) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Direccion` varchar(80) COLLATE utf8_spanish_ci DEFAULT NULL,
  `MarcaVehiculo` varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  `NroPlaca` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `NroConstanciaCertificado` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `LicenciaBrevete` varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  `NombreChofer` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`IdTransportista`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `transportista`
--

INSERT INTO `transportista` (`IdTransportista`, `IdSucursal`, `NombreRazonSocial`, `DniRuc`, `Direccion`, `MarcaVehiculo`, `NroPlaca`, `NroConstanciaCertificado`, `LicenciaBrevete`, `NombreChofer`, `Estado`) VALUES
(1, 1, 'CIVA', '12345678', '', 'XX', 'XX', '22', '22', 'XX', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad`
--

DROP TABLE IF EXISTS `unidad`;
CREATE TABLE IF NOT EXISTS `unidad` (
  `IdUnidad` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `Tipo` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'M->Masa, L->Longitud, A->Area y O->Otro',
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdUnidad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `unidad`
--

INSERT INTO `unidad` (`IdUnidad`, `Descripcion`, `Abreviatura`, `Tipo`, `Estado`) VALUES
(1, 'UNIDAD', 'UNI', 'O', 'N'),
(2, 'CAJA', 'CAJA', 'O', 'N'),
(3, 'PACK', 'PACK', 'O', 'N'),
(4, 'GRAMO', 'GM', 'M', 'N'),
(5, 'KILOGRAMO', 'KG', 'M', 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `Idusuario` int(11) NOT NULL,
  `US` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `PW` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `UltimoAcceso` datetime NOT NULL,
  `IdTipoUsuario` int(11) NOT NULL,
  `Estado` char(1) COLLATE utf8_spanish_ci NOT NULL COMMENT 'N->Normal, A->Anulado',
  UNIQUE KEY `Idusuario` (`Idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`Idusuario`, `US`, `PW`, `UltimoAcceso`, `IdTipoUsuario`, `Estado`) VALUES
(3, 'carlos', '123', '2014-11-25 12:50:39', 6, 'N'),
(4, 'admin', '123', '2014-11-25 12:49:57', 1, 'N'),
(24, 'patrix', '123', '2011-05-03 17:45:53', 4, 'A'),
(31, 'cajero', '123', '0000-00-00 00:00:00', 2, 'N');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

DROP TABLE IF EXISTS `zona`;
CREATE TABLE IF NOT EXISTS `zona` (
  `IdZona` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`IdZona`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `zona`
--

INSERT INTO `zona` (`IdZona`, `Descripcion`) VALUES
(1, 'CIX'),
(2, 'TRUJILLO'),
(3, 'PIURA');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
