/*
MySQL Data Transfer
Source Host: localhost
Source Database: dbtienda
Target Host: localhost
Target Database: dbtienda
Date: 26/08/2011 20:21:27
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for acceso
-- ----------------------------
DROP TABLE IF EXISTS `acceso`;
CREATE TABLE `acceso` (
  `IdAcceso` int(11) NOT NULL auto_increment,
  `IdTipoUsuario` int(11) NOT NULL,
  `IdOpcionMenu` int(11) NOT NULL,
  PRIMARY KEY  (`IdAcceso`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for armario
-- ----------------------------
DROP TABLE IF EXISTS `armario`;
CREATE TABLE `armario` (
  `IdArmario` int(11) NOT NULL auto_increment,
  `Codigo` varchar(5) collate utf8_spanish_ci NOT NULL,
  `Nombre` varchar(25) collate utf8_spanish_ci NOT NULL,
  `TotalColumnas` int(11) NOT NULL,
  `TotalFilas` int(11) NOT NULL,
  PRIMARY KEY  (`IdArmario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for bitacora
-- ----------------------------
DROP TABLE IF EXISTS `bitacora`;
CREATE TABLE `bitacora` (
  `IdBitacora` int(11) NOT NULL auto_increment,
  `IdMovimiento` int(11) NOT NULL,
  `FechaHora` datetime NOT NULL,
  `Comentario` varchar(100) collate utf8_spanish_ci NOT NULL,
  `Idusuario` int(11) NOT NULL,
  `Operacion` varchar(1) collate utf8_spanish_ci NOT NULL default 'N' COMMENT 'N->Nuevo,A->Anulacion',
  PRIMARY KEY  (`IdBitacora`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for categoria
-- ----------------------------
DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `IdCategoria` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(100) collate utf8_spanish_ci default NULL,
  `Abreviatura` varchar(2) collate utf8_spanish_ci default NULL,
  `IdCategoriaRef` int(11) default NULL,
  `Nivel` int(11) default '1',
  `Estado` char(1) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`IdCategoria`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for conceptopago
-- ----------------------------
DROP TABLE IF EXISTS `conceptopago`;
CREATE TABLE `conceptopago` (
  `IdConceptoPago` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Tipo` char(1) collate utf8_spanish_ci NOT NULL COMMENT 'I->Ingreso,E->Egreso',
  PRIMARY KEY  (`IdConceptoPago`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Solo para el caso de Caja';

-- ----------------------------
-- Table structure for cuota
-- ----------------------------
DROP TABLE IF EXISTS `cuota`;
CREATE TABLE `cuota` (
  `IdCuota` int(11) NOT NULL auto_increment,
  `Nombre` varchar(20) collate utf8_spanish_ci NOT NULL,
  `IdMovimiento` int(11) NOT NULL,
  `FechaCreacion` datetime NOT NULL,
  `FechaCancelacion` datetime NOT NULL,
  `FechaPago` datetime NOT NULL,
  `Moneda` char(1) collate utf8_spanish_ci NOT NULL default 'S' COMMENT 'S->Soles, D->D?lares.',
  `Monto` decimal(10,2) NOT NULL,
  `Interes` decimal(10,2) NOT NULL,
  `MontoPagado` decimal(10,2) NOT NULL,
  `InteresPagado` decimal(10,2) NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL default 'N' COMMENT 'N->Normal,C->Cancelada (Pagada), A->Anulada.',
  PRIMARY KEY  (`IdCuota`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for departamento
-- ----------------------------
DROP TABLE IF EXISTS `departamento`;
CREATE TABLE `departamento` (
  `IdDepartamento` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(20) collate utf8_spanish_ci default NULL,
  `IdPais` int(11) NOT NULL,
  `Codigo` varchar(2) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`IdDepartamento`),
  KEY `XIF1DEPARTAMENTO` (`IdPais`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for detallemovalmacen
-- ----------------------------
DROP TABLE IF EXISTS `detallemovalmacen`;
CREATE TABLE `detallemovalmacen` (
  `IdDetalleMovAlmacen` int(11) NOT NULL auto_increment,
  `IdMovimiento` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `Cantidad` decimal(10,2) NOT NULL,
  `PrecioCompra` decimal(10,2) NOT NULL,
  `PrecioVenta` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`IdDetalleMovAlmacen`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for detallemovcaja
-- ----------------------------
DROP TABLE IF EXISTS `detallemovcaja`;
CREATE TABLE `detallemovcaja` (
  `IdDetalleMovCaja` int(11) NOT NULL auto_increment,
  `IdMovimiento` int(11) NOT NULL,
  `IdCuota` int(11) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Interes` decimal(10,2) NOT NULL,
  `Moneda` char(1) collate utf8_spanish_ci NOT NULL default 'S' COMMENT 'S->Soles, D->D?lares.',
  `TipoCambio` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`IdDetalleMovCaja`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for distrito
-- ----------------------------
DROP TABLE IF EXISTS `distrito`;
CREATE TABLE `distrito` (
  `IdDistrito` int(11) NOT NULL auto_increment,
  `Descripcion` char(40) collate utf8_spanish_ci default NULL,
  `Codigo` varchar(2) character set utf8 collate utf8_latvian_ci default NULL,
  `IdProvincia` int(11) NOT NULL,
  PRIMARY KEY  (`IdDistrito`),
  KEY `XIF3DISTRITO` (`IdProvincia`)
) ENGINE=InnoDB AUTO_INCREMENT=1829 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for kardex
-- ----------------------------
DROP TABLE IF EXISTS `kardex`;
CREATE TABLE `kardex` (
  `IdKardex` int(11) NOT NULL auto_increment,
  `IdSucursal` int(11) NOT NULL,
  `IdMovimiento` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `Cantidad` decimal(10,2) NOT NULL,
  `TipoMoneda` char(1) collate utf8_spanish_ci NOT NULL,
  `PrecioUnidad` decimal(10,2) NOT NULL,
  `Importe` decimal(10,2) NOT NULL,
  `Fecha` datetime NOT NULL,
  `SaldoAnteriorBase` decimal(10,2) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `CantidadBase` decimal(10,2) NOT NULL,
  `SaldoActual` decimal(10,2) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `Ultimo` char(1) collate utf8_spanish_ci NOT NULL COMMENT 'S->Si,N->No',
  `Estado` char(1) collate utf8_spanish_ci NOT NULL default 'N' COMMENT 'N->Normal,A->Anulado',
  PRIMARY KEY  (`IdKardex`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for listaunidad
-- ----------------------------
DROP TABLE IF EXISTS `listaunidad`;
CREATE TABLE `listaunidad` (
  `IdListaUnidad` int(11) NOT NULL auto_increment,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `Formula` decimal(10,0) NOT NULL,
  `PrecioCompra` decimal(10,3) NOT NULL,
  `PrecioVenta` decimal(10,3) NOT NULL,
  `PrecioVentaEspecial` decimal(10,3) default NULL,
  `Moneda` char(1) collate utf8_spanish_ci NOT NULL default 'S',
  PRIMARY KEY  (`IdListaUnidad`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for lugar
-- ----------------------------
DROP TABLE IF EXISTS `lugar`;
CREATE TABLE `lugar` (
  `IdLugar` int(11) NOT NULL auto_increment,
  `Direccion` varchar(50) collate utf8_spanish_ci default NULL,
  `Ubigeo` int(11) default NULL,
  PRIMARY KEY  (`IdLugar`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for marca
-- ----------------------------
DROP TABLE IF EXISTS `marca`;
CREATE TABLE `marca` (
  `IdMarca` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(25) collate utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(2) collate utf8_spanish_ci NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdMarca`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for modulo
-- ----------------------------
DROP TABLE IF EXISTS `modulo`;
CREATE TABLE `modulo` (
  `idmodulo` int(11) NOT NULL auto_increment,
  `descripcion` varchar(25) collate utf8_spanish_ci NOT NULL,
  `abreviatura` varchar(5) collate utf8_spanish_ci NOT NULL,
  `estado` char(1) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`idmodulo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for motivotraslado
-- ----------------------------
DROP TABLE IF EXISTS `motivotraslado`;
CREATE TABLE `motivotraslado` (
  `IdMotivoTraslado` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`IdMotivoTraslado`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for movimiento
-- ----------------------------
DROP TABLE IF EXISTS `movimiento`;
CREATE TABLE `movimiento` (
  `IdMovimiento` int(11) NOT NULL auto_increment,
  `IdSucursal` int(11) NOT NULL,
  `IdTipoMovimiento` int(11) NOT NULL,
  `IdConceptoPago` int(11) NOT NULL,
  `Numero` varchar(15) collate utf8_spanish_ci NOT NULL COMMENT '000000-000',
  `IdTipoDocumento` int(11) NOT NULL,
  `FormaPago` char(1) collate utf8_spanish_ci NOT NULL default 'A' COMMENT 'A->Contado,B->Credito',
  `Fecha` datetime NOT NULL,
  `Moneda` char(1) collate utf8_spanish_ci NOT NULL default 'S' COMMENT 'S->Soles, D->D?lares.',
  `SubTotal` decimal(10,2) NOT NULL,
  `Igv` decimal(10,2) NOT NULL,
  `Total` decimal(10,2) NOT NULL,
  `IdUsuario` int(11) NOT NULL,
  `IdPersona` int(11) NOT NULL,
  `IdResponsable` int(11) NOT NULL,
  `IdMovimientoRef` int(11) NOT NULL,
  `IdSucursalRef` int(11) NOT NULL,
  `Comentario` varchar(100) collate utf8_spanish_ci NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL default 'N' COMMENT 'N->Normal, A->Anulado',
  `FechaInicioTraslado` date NOT NULL,
  `IdMotivoTraslado` int(11) NOT NULL default '0',
  `IdTransportista` int(11) NOT NULL default '0',
  `IdLugarPartida` int(11) NOT NULL default '0',
  `IdLugarDestino` int(11) NOT NULL default '0',
  PRIMARY KEY  (`IdMovimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for opcionmenu
-- ----------------------------
DROP TABLE IF EXISTS `opcionmenu`;
CREATE TABLE `opcionmenu` (
  `IdOpcionMenu` int(11) NOT NULL auto_increment,
  `LinkMenu` varchar(50) collate utf8_spanish_ci NOT NULL,
  `NombreMenu` varchar(50) collate utf8_spanish_ci NOT NULL,
  `idmodulo` int(11) NOT NULL,
  PRIMARY KEY  (`IdOpcionMenu`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for pais
-- ----------------------------
DROP TABLE IF EXISTS `pais`;
CREATE TABLE `pais` (
  `IdPais` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(20) collate utf8_spanish_ci default NULL,
  `Nacionalidad` varchar(30) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`IdPais`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for persona
-- ----------------------------
DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona` (
  `IdPersona` int(11) NOT NULL auto_increment,
  `TipoPersona` varchar(8) collate utf8_spanish_ci NOT NULL COMMENT 'Natural (RUC), Jur?dica (RUC) y Varios(DNI)',
  `Nombres` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Apellidos` varchar(50) collate utf8_spanish_ci NOT NULL,
  `NroDoc` varchar(10) collate utf8_spanish_ci NOT NULL,
  `Sexo` char(1) collate utf8_spanish_ci NOT NULL,
  `Direccion` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Celular` varchar(9) collate utf8_spanish_ci NOT NULL,
  `Email` varchar(50) collate utf8_spanish_ci NOT NULL,
  `IdSector` int(11) NOT NULL,
  `IdZona` int(11) NOT NULL,
  `IdDistrito` int(11) NOT NULL default '409',
  `Representante` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL COMMENT 'N->Normal, A->Anulado',
  PRIMARY KEY  (`IdPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for producto
-- ----------------------------
DROP TABLE IF EXISTS `producto`;
CREATE TABLE `producto` (
  `IdProducto` int(11) NOT NULL auto_increment,
  `Codigo` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Descripcion` varchar(100) collate utf8_spanish_ci NOT NULL,
  `IdCategoria` int(11) NOT NULL,
  `IdMarca` int(11) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `Peso` decimal(18,2) NOT NULL,
  `IdMedidaPeso` int(11) NOT NULL COMMENT 'Se relaciona con la tabla unidad',
  `StockSeguridad` decimal(10,2) NOT NULL,
  `IdArmario` int(11) NOT NULL,
  `Columna` int(11) NOT NULL,
  `Fila` int(11) NOT NULL,
  `Kardex` char(1) collate utf8_spanish_ci NOT NULL default 'S' COMMENT 'S->Si, N->No',
  `Estado` char(1) collate utf8_spanish_ci NOT NULL default 'N' COMMENT 'N->Normal, A->Anulado',
  PRIMARY KEY  (`IdProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for provincia
-- ----------------------------
DROP TABLE IF EXISTS `provincia`;
CREATE TABLE `provincia` (
  `IdProvincia` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(30) collate utf8_spanish_ci default NULL,
  `Codigo` varchar(2) collate utf8_spanish_ci default NULL,
  `IdDepartamento` int(11) NOT NULL,
  PRIMARY KEY  (`IdProvincia`),
  KEY `XIF1PROVINCIA` (`IdDepartamento`)
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for rol
-- ----------------------------
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `IdRol` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdRol`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Indicara si la persona es: Cliente, Proveedor, Usuario, Empl';

-- ----------------------------
-- Table structure for rolpersona
-- ----------------------------
DROP TABLE IF EXISTS `rolpersona`;
CREATE TABLE `rolpersona` (
  `IdRolPersona` int(11) NOT NULL auto_increment,
  `IdPersona` int(11) NOT NULL,
  `IdRol` int(11) NOT NULL,
  PRIMARY KEY  (`IdRolPersona`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Permite asignar a una persona muchos roles.';

-- ----------------------------
-- Table structure for sector
-- ----------------------------
DROP TABLE IF EXISTS `sector`;
CREATE TABLE `sector` (
  `IdSector` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdSector`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for stockproducto
-- ----------------------------
DROP TABLE IF EXISTS `stockproducto`;
CREATE TABLE `stockproducto` (
  `IdStockProducto` int(11) NOT NULL auto_increment,
  `IdSucursal` int(11) NOT NULL,
  `IdProducto` int(11) NOT NULL,
  `IdUnidad` int(11) NOT NULL,
  `Stock` decimal(10,2) NOT NULL,
  `IdUnidadBase` int(11) NOT NULL,
  `StockBase` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`IdStockProducto`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for sucursal
-- ----------------------------
DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE `sucursal` (
  `IdSucursal` int(11) NOT NULL auto_increment,
  `Nombre` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Direccion` varchar(50) collate utf8_spanish_ci NOT NULL,
  `RUC` varchar(11) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdSucursal`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for tipocambio
-- ----------------------------
DROP TABLE IF EXISTS `tipocambio`;
CREATE TABLE `tipocambio` (
  `IdTipocambio` int(11) NOT NULL auto_increment,
  `Fecha` date NOT NULL,
  `Cambio` decimal(10,2) NOT NULL,
  PRIMARY KEY  (`IdTipocambio`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for tipodocumento
-- ----------------------------
DROP TABLE IF EXISTS `tipodocumento`;
CREATE TABLE `tipodocumento` (
  `IdTipoDocumento` int(12) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(5) collate utf8_spanish_ci NOT NULL,
  `Stock` char(1) collate utf8_spanish_ci NOT NULL COMMENT 'indicara la forma como este mueve stock: R->Resta Stock, S->Suma Stock y N-> Neutro (Ejm: Cotizaciones, ?rdenes de Compra).',
  PRIMARY KEY  (`IdTipoDocumento`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contendr? el tipo de documento, pudiendo ser Boleta, Factura';

-- ----------------------------
-- Table structure for tipomovimiento
-- ----------------------------
DROP TABLE IF EXISTS `tipomovimiento`;
CREATE TABLE `tipomovimiento` (
  `IdTipoMovimiento` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(25) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdTipoMovimiento`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Ejm: Compra, Venta, Caja, Almacen';

-- ----------------------------
-- Table structure for tipousuario
-- ----------------------------
DROP TABLE IF EXISTS `tipousuario`;
CREATE TABLE `tipousuario` (
  `IdTipoUsuario` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL default 'N',
  PRIMARY KEY  (`IdTipoUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for transportista
-- ----------------------------
DROP TABLE IF EXISTS `transportista`;
CREATE TABLE `transportista` (
  `IdTransportista` int(11) NOT NULL auto_increment,
  `IdSucursal` int(11) default NULL,
  `NombreRazonSocial` varchar(100) collate utf8_spanish_ci default NULL,
  `DniRuc` varchar(11) collate utf8_spanish_ci default NULL,
  `Direccion` varchar(80) collate utf8_spanish_ci default NULL,
  `MarcaVehiculo` varchar(30) collate utf8_spanish_ci default NULL,
  `NroPlaca` varchar(20) collate utf8_spanish_ci default NULL,
  `NroConstanciaCertificado` varchar(20) collate utf8_spanish_ci default NULL,
  `LicenciaBrevete` varchar(20) collate utf8_spanish_ci default NULL,
  `NombreChofer` varchar(100) collate utf8_spanish_ci default NULL,
  `Estado` char(1) collate utf8_spanish_ci default NULL,
  PRIMARY KEY  (`IdTransportista`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for unidad
-- ----------------------------
DROP TABLE IF EXISTS `unidad`;
CREATE TABLE `unidad` (
  `IdUnidad` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(25) collate utf8_spanish_ci NOT NULL,
  `Abreviatura` varchar(5) collate utf8_spanish_ci NOT NULL,
  `Tipo` char(1) collate utf8_spanish_ci NOT NULL COMMENT 'M->Masa, L->Longitud, A->Area y O->Otro',
  `Estado` char(1) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdUnidad`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `Idusuario` int(11) NOT NULL,
  `US` varchar(25) collate utf8_spanish_ci NOT NULL,
  `PW` varchar(32) collate utf8_spanish_ci NOT NULL,
  `UltimoAcceso` datetime NOT NULL,
  `IdTipoUsuario` int(11) NOT NULL,
  `Estado` char(1) collate utf8_spanish_ci NOT NULL COMMENT 'N->Normal, A->Anulado',
  UNIQUE KEY `Idusuario` (`Idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Table structure for zona
-- ----------------------------
DROP TABLE IF EXISTS `zona`;
CREATE TABLE `zona` (
  `IdZona` int(11) NOT NULL auto_increment,
  `Descripcion` varchar(50) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`IdZona`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Procedure structure for up_AgregarStockProducto
-- ----------------------------
DROP PROCEDURE IF EXISTS `up_AgregarStockProducto`;
DELIMITER ;;
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
END;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for up_BuscarCategoriaProductoArbol
-- ----------------------------
DROP PROCEDURE IF EXISTS `up_BuscarCategoriaProductoArbol`;
DELIMITER ;;
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
End;;
DELIMITER ;

-- ----------------------------
-- Function structure for obtenerStock
-- ----------------------------
DROP FUNCTION IF EXISTS `obtenerStock`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `obtenerStock`(vIdProducto int,vIdUnidad int,vIdSucursal int) RETURNS decimal(16,2)
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
END;;
DELIMITER ;

-- ----------------------------
-- Records 
-- ----------------------------
INSERT INTO `acceso` VALUES ('7', '1', '1');
INSERT INTO `acceso` VALUES ('10', '1', '2');
INSERT INTO `acceso` VALUES ('11', '1', '3');
INSERT INTO `acceso` VALUES ('12', '1', '4');
INSERT INTO `acceso` VALUES ('13', '1', '5');
INSERT INTO `acceso` VALUES ('14', '1', '6');
INSERT INTO `acceso` VALUES ('15', '1', '7');
INSERT INTO `acceso` VALUES ('16', '1', '8');
INSERT INTO `acceso` VALUES ('18', '1', '10');
INSERT INTO `acceso` VALUES ('19', '1', '11');
INSERT INTO `acceso` VALUES ('20', '1', '12');
INSERT INTO `acceso` VALUES ('21', '1', '13');
INSERT INTO `acceso` VALUES ('22', '1', '14');
INSERT INTO `acceso` VALUES ('23', '1', '15');
INSERT INTO `acceso` VALUES ('24', '1', '9');
INSERT INTO `acceso` VALUES ('25', '2', '9');
INSERT INTO `acceso` VALUES ('26', '1', '16');
INSERT INTO `acceso` VALUES ('27', '1', '17');
INSERT INTO `acceso` VALUES ('28', '1', '19');
INSERT INTO `acceso` VALUES ('29', '1', '18');
INSERT INTO `acceso` VALUES ('30', '2', '18');
INSERT INTO `acceso` VALUES ('31', '1', '22');
INSERT INTO `acceso` VALUES ('32', '1', '20');
INSERT INTO `acceso` VALUES ('33', '1', '21');
INSERT INTO `acceso` VALUES ('34', '1', '23');
INSERT INTO `acceso` VALUES ('35', '1', '24');
INSERT INTO `acceso` VALUES ('36', '1', '25');
INSERT INTO `acceso` VALUES ('37', '1', '26');
INSERT INTO `acceso` VALUES ('38', '1', '27');
INSERT INTO `acceso` VALUES ('39', '1', '28');
INSERT INTO `acceso` VALUES ('40', '3', '16');
INSERT INTO `acceso` VALUES ('41', '3', '4');
INSERT INTO `acceso` VALUES ('42', '2', '4');
INSERT INTO `acceso` VALUES ('43', '2', '11');
INSERT INTO `acceso` VALUES ('44', '2', '15');
INSERT INTO `acceso` VALUES ('45', '2', '27');
INSERT INTO `acceso` VALUES ('46', '2', '2');
INSERT INTO `acceso` VALUES ('47', '2', '16');
INSERT INTO `acceso` VALUES ('48', '2', '7');
INSERT INTO `acceso` VALUES ('49', '2', '19');
INSERT INTO `acceso` VALUES ('50', '2', '26');
INSERT INTO `acceso` VALUES ('51', '2', '24');
INSERT INTO `acceso` VALUES ('52', '2', '23');
INSERT INTO `acceso` VALUES ('53', '2', '25');
INSERT INTO `acceso` VALUES ('54', '2', '21');
INSERT INTO `acceso` VALUES ('55', '3', '2');
INSERT INTO `acceso` VALUES ('56', '1', '29');
INSERT INTO `acceso` VALUES ('57', '1', '30');
INSERT INTO `acceso` VALUES ('58', '1', '31');
INSERT INTO `armario` VALUES ('1', '001', 'ARMARIO1', '5', '5');
INSERT INTO `armario` VALUES ('2', 'ARMAR', 'ARMARIO2', '4', '8');
INSERT INTO `armario` VALUES ('3', '002', 'ARMARIO 3', '4', '3');
INSERT INTO `bitacora` VALUES ('1', '1', '2011-01-20 10:18:02', 'Nuevo Doc Almacen : 001-000001-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('2', '2', '2011-01-20 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('3', '4', '2011-01-20 00:00:00', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('4', '6', '2011-01-20 00:00:00', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('5', '7', '2011-01-20 00:00:00', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('6', '9', '2011-01-20 00:00:00', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('7', '11', '2011-01-20 00:00:00', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('8', '11', '2011-01-20 00:00:00', 'Anulacion de venta', '4', 'A');
INSERT INTO `bitacora` VALUES ('9', '12', '2011-01-20 11:09:32', 'Compra: 001-099999-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('10', '13', '2011-01-24 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('11', '15', '2011-01-24 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('12', '17', '2011-01-24 12:58:26', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('13', '19', '2011-01-24 12:59:03', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('14', '21', '2011-01-24 13:00:21', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('15', '27', '2011-01-24 14:37:21', 'Registro de nueva Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('16', '28', '2011-01-25 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('17', '30', '2011-01-25 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('18', '32', '2011-01-25 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('19', '33', '2011-01-26 00:16:24', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('20', '35', '2011-01-26 00:19:15', 'Registro de nueva Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('21', '36', '2011-01-26 00:00:00', 'Cierre de Caja del 2011-01-25', '4', 'N');
INSERT INTO `bitacora` VALUES ('22', '38', '2011-01-26 00:00:00', 'Apertura de Caja del 2011-01-26', '4', 'N');
INSERT INTO `bitacora` VALUES ('23', '40', '2011-01-26 14:40:04', 'Nuevo Doc Almacen : 001-000002-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('24', '41', '2011-01-26 14:40:30', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('25', '41', '2011-01-26 14:49:19', 'Anulacion de venta', '4', 'A');
INSERT INTO `bitacora` VALUES ('26', '41', '2011-01-26 14:51:01', 'Anulacion de venta', '4', 'A');
INSERT INTO `bitacora` VALUES ('27', '43', '2011-01-26 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('28', '44', '2011-01-26 16:17:31', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('29', '46', '2011-01-26 21:22:46', 'Nuevo Doc Almacen : 001-000003-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('30', '47', '2011-01-30 00:00:00', 'Cierre de Caja del 2011-01-26', '4', 'N');
INSERT INTO `bitacora` VALUES ('31', '49', '2011-01-30 00:00:00', 'Apertura de Caja del 2011-01-30', '4', 'N');
INSERT INTO `bitacora` VALUES ('32', '51', '2011-01-30 20:41:49', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('33', '53', '2011-01-30 20:44:01', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('34', '53', '2011-01-30 20:44:42', 'Anulacion de venta', '4', 'A');
INSERT INTO `bitacora` VALUES ('35', '55', '2011-02-04 00:00:00', 'Cierre de Caja del 2011-01-30', '4', 'N');
INSERT INTO `bitacora` VALUES ('36', '57', '2011-02-04 00:00:00', 'Apertura de Caja del 2011-02-04', '4', 'N');
INSERT INTO `bitacora` VALUES ('37', '59', '2011-02-07 00:00:00', 'Cierre de Caja del 2011-02-04', '4', 'N');
INSERT INTO `bitacora` VALUES ('38', '61', '2011-02-07 00:00:00', 'Apertura de Caja del 2011-02-07', '4', 'N');
INSERT INTO `bitacora` VALUES ('39', '63', '2011-02-18 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('40', '65', '2011-02-18 00:00:00', '', '4', 'N');
INSERT INTO `bitacora` VALUES ('41', '67', '2011-02-18 00:00:00', 'ok', '4', 'N');
INSERT INTO `bitacora` VALUES ('42', '67', '2011-02-18 00:00:00', 'ANULACION DE MOV CAJA', '4', 'A');
INSERT INTO `bitacora` VALUES ('43', '68', '2011-02-18 00:00:00', 'Nuevo Mov Caja Nro: 001-000031-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('44', '69', '2011-02-18 20:17:57', 'Nuevo Mov Caja Nro: 001-000032-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('45', '70', '2011-03-05 00:00:00', 'Cierre de Caja del 2011-02-18', '4', 'N');
INSERT INTO `bitacora` VALUES ('46', '72', '2011-03-05 00:00:00', 'Apertura de Caja del 2011-03-05', '4', 'N');
INSERT INTO `bitacora` VALUES ('47', '74', '2011-03-15 19:17:18', 'Nuevo Doc Almacen : 001-000004-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('48', '75', '2011-03-15 00:00:00', 'Cierre de Caja del 2011-03-05', '4', 'N');
INSERT INTO `bitacora` VALUES ('49', '77', '2011-03-15 00:00:00', 'Apertura de Caja del 2011-03-15', '4', 'N');
INSERT INTO `bitacora` VALUES ('50', '80', '2011-03-15 18:21:40', 'Mov Caja: 001-000019-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('51', '79', '2011-03-15 18:21:40', 'Compra: 001-000100-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('52', '81', '2011-03-15 19:27:52', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('53', '83', '2011-03-20 01:08:39', 'Nuevo Doc Almacen : 001-000005-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('54', '84', '2011-04-13 17:37:27', 'Nuevo Doc Almacen : 001-000006-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('55', '85', '2011-04-13 00:00:00', 'Cierre de Caja del 2011-03-15', '4', 'N');
INSERT INTO `bitacora` VALUES ('56', '87', '2011-04-13 00:00:00', 'Apertura de Caja del 2011-04-13', '4', 'N');
INSERT INTO `bitacora` VALUES ('57', '84', '2011-04-14 15:05:11', 'Anulacion Doc Almacen : 001-000006-2011', '4', 'A');
INSERT INTO `bitacora` VALUES ('58', '89', '2011-04-14 15:13:20', 'Cierre de Caja dia: 2011-04-13', '4', 'N');
INSERT INTO `bitacora` VALUES ('59', '91', '2011-04-14 15:13:20', 'Apertura de Caja dia: 2011-04-14', '4', 'N');
INSERT INTO `bitacora` VALUES ('60', '93', '2011-04-14 15:17:22', 'Nuevo Doc Almacen : 001-000007-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('61', '94', '2011-04-14 15:22:40', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('62', '96', '2011-04-15 00:00:00', 'Cierre de Caja del 2011-04-14', '4', 'N');
INSERT INTO `bitacora` VALUES ('63', '98', '2011-04-15 00:00:00', 'Apertura de Caja del 2011-04-15', '4', 'N');
INSERT INTO `bitacora` VALUES ('64', '100', '2011-04-16 00:00:00', 'Cierre de Caja del 2011-04-15', '4', 'N');
INSERT INTO `bitacora` VALUES ('65', '102', '2011-04-16 00:00:00', 'Apertura de Caja del 2011-04-16', '4', 'N');
INSERT INTO `bitacora` VALUES ('66', '104', '2011-04-16 17:37:35', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('67', '106', '2011-04-16 17:43:36', 'Nuevo Mov Caja Nro: 001-000048-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('68', '107', '2011-04-16 17:43:56', 'Cierre de Caja dia: 2011-04-16', '4', 'N');
INSERT INTO `bitacora` VALUES ('69', '109', '2011-04-17 00:00:00', 'Cierre de Caja dia: 2011-04-17', '4', 'N');
INSERT INTO `bitacora` VALUES ('70', '111', '2011-04-16 17:50:48', 'Nuevo Doc Almacen : 001-000008-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('71', '112', '2011-04-24 00:00:00', 'Cierre de Caja del 2011-04-17', '4', 'N');
INSERT INTO `bitacora` VALUES ('72', '114', '2011-04-24 00:00:00', 'Apertura de Caja del 2011-04-24', '4', 'N');
INSERT INTO `bitacora` VALUES ('73', '116', '2011-04-24 18:28:06', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('74', '118', '2011-04-25 19:05:03', 'Nuevo Doc Almacen : 001-000009-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('75', '119', '2011-04-25 19:06:56', 'Nuevo Doc Almacen : 001-000001-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('76', '120', '2011-04-25 00:00:00', 'Cierre de Caja del 2011-04-24', '4', 'N');
INSERT INTO `bitacora` VALUES ('77', '122', '2011-04-25 00:00:00', 'Apertura de Caja del 2011-04-25', '4', 'N');
INSERT INTO `bitacora` VALUES ('78', '124', '2011-04-25 19:08:39', 'Nuevo Doc Almacen : 001-000010-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('79', '125', '2011-04-25 19:11:17', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('80', '127', '2011-04-25 19:12:36', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('81', '129', '2011-04-25 18:17:51', 'Compra: 009-0001', '4', 'N');
INSERT INTO `bitacora` VALUES ('82', '130', '2011-04-25 19:22:19', 'Nuevo Mov Caja Nro: 001-000058-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('83', '131', '2011-04-27 00:00:00', 'Cierre de Caja del 2011-04-25', '4', 'N');
INSERT INTO `bitacora` VALUES ('84', '133', '2011-04-27 00:00:00', 'Apertura de Caja del 2011-04-27', '4', 'N');
INSERT INTO `bitacora` VALUES ('85', '135', '2011-04-27 17:03:51', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('86', '137', '2011-04-27 17:08:25', 'Nuevo Mov Caja Nro: 001-000062-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('87', '138', '2011-04-27 17:09:30', 'Nuevo Mov Caja Nro: 001-000063-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('88', '139', '2011-04-28 00:00:00', 'Cierre de Caja del 2011-04-27', '4', 'N');
INSERT INTO `bitacora` VALUES ('89', '141', '2011-04-28 00:00:00', 'Apertura de Caja del 2011-04-28', '4', 'N');
INSERT INTO `bitacora` VALUES ('90', '143', '2011-04-28 00:30:02', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('91', '145', '2011-04-28 00:32:53', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('92', '147', '2011-04-28 00:34:38', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('93', '149', '2011-04-28 00:37:53', 'Registro de nueva Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('94', '150', '2011-04-28 00:42:36', 'Registro de nueva Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('95', '151', '2011-04-28 00:43:37', 'Registro de nueva Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('96', '152', '2011-04-28 00:57:01', 'Nuevo Mov Caja Nro: 001-000069-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('97', '153', '2011-04-28 01:16:37', 'Nuevo Doc Almacen : 001-000011-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('98', '154', '2011-04-28 01:23:37', 'Nuevo Doc Almacen : 001-000012-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('99', '155', '2011-04-28 01:27:00', 'Nuevo Doc Almacen : 001-000013-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('100', '157', '2011-04-28 00:37:51', 'Mov Caja: 001-000038-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('101', '156', '2011-04-28 00:37:51', 'Compra: 001-009', '4', 'N');
INSERT INTO `bitacora` VALUES ('102', '158', '2011-05-03 00:00:00', 'Cierre de Caja del 2011-04-28', '4', 'N');
INSERT INTO `bitacora` VALUES ('103', '160', '2011-05-03 00:00:00', 'Apertura de Caja del 2011-05-03', '4', 'N');
INSERT INTO `bitacora` VALUES ('104', '162', '2011-05-18 00:00:00', 'Cierre de Caja del 2011-05-03', '4', 'N');
INSERT INTO `bitacora` VALUES ('105', '164', '2011-05-18 00:00:00', 'Apertura de Caja del 2011-05-18', '4', 'N');
INSERT INTO `bitacora` VALUES ('106', '166', '2011-05-18 15:33:53', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('107', '168', '2011-05-18 15:49:55', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('108', '170', '2011-05-21 00:00:00', 'Cierre de Caja del 2011-05-18', '4', 'N');
INSERT INTO `bitacora` VALUES ('109', '172', '2011-05-21 00:00:00', 'Apertura de Caja del 2011-05-21', '4', 'N');
INSERT INTO `bitacora` VALUES ('110', '174', '2011-05-25 00:00:00', 'Cierre de Caja del 2011-05-21', '4', 'N');
INSERT INTO `bitacora` VALUES ('111', '176', '2011-05-25 00:00:00', 'Apertura de Caja del 2011-05-25', '4', 'N');
INSERT INTO `bitacora` VALUES ('112', '178', '2011-05-27 00:00:00', 'Cierre de Caja del 2011-05-25', '4', 'N');
INSERT INTO `bitacora` VALUES ('113', '180', '2011-05-27 00:00:00', 'Apertura de Caja del 2011-05-27', '4', 'N');
INSERT INTO `bitacora` VALUES ('114', '184', '2011-05-27 16:23:51', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('115', '185', '2011-05-28 00:00:00', 'Cierre de Caja del 2011-05-27', '4', 'N');
INSERT INTO `bitacora` VALUES ('116', '187', '2011-05-28 00:00:00', 'Apertura de Caja del 2011-05-28', '4', 'N');
INSERT INTO `bitacora` VALUES ('117', '189', '2011-05-28 11:29:48', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('118', '191', '2011-05-28 11:31:20', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('119', '192', '2011-05-28 12:54:17', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('120', '193', '2011-05-28 13:17:39', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('121', '195', '2011-05-28 13:51:08', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('122', '168', '2011-05-28 17:45:09', 'Registro de despacho sin Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('123', '94', '2011-05-28 17:51:53', 'Registro de despacho sin Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('124', '197', '2011-05-28 18:53:07', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('125', '198', '2011-05-28 18:54:48', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('126', '199', '2011-05-28 18:56:25', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('127', '201', '2011-05-28 19:00:38', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('128', '202', '2011-05-28 19:02:09', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('129', '202', '2011-05-28 19:24:49', 'Registro de despacho sin Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('130', '204', '2011-05-28 19:55:55', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('131', '205', '2011-05-28 19:57:44', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('132', '205', '2011-05-28 19:58:43', 'Registro de despacho sin Guia de Remision', '4', 'N');
INSERT INTO `bitacora` VALUES ('133', '207', '2011-05-28 22:13:00', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('134', '208', '2011-05-28 22:13:26', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('135', '210', '2011-05-28 22:14:24', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('136', '212', '2011-06-01 00:00:00', 'Cierre de Caja del 2011-05-28', '4', 'N');
INSERT INTO `bitacora` VALUES ('137', '214', '2011-06-01 00:00:00', 'Apertura de Caja del 2011-06-01', '4', 'N');
INSERT INTO `bitacora` VALUES ('138', '216', '2011-08-05 21:10:50', 'Nuevo Doc Almacen : 001-000014-2011', '4', 'N');
INSERT INTO `bitacora` VALUES ('139', '217', '2011-08-13 00:00:00', 'Cierre de Caja del 2011-06-01', '4', 'N');
INSERT INTO `bitacora` VALUES ('140', '219', '2011-08-13 00:00:00', 'Apertura de Caja del 2011-08-13', '4', 'N');
INSERT INTO `bitacora` VALUES ('141', '221', '2011-08-20 00:00:00', 'Cierre de Caja del 2011-08-13', '1', 'N');
INSERT INTO `bitacora` VALUES ('142', '223', '2011-08-20 00:00:00', 'Apertura de Caja del 2011-08-20', '1', 'N');
INSERT INTO `bitacora` VALUES ('143', '225', '2011-08-20 14:08:20', 'Registro de nuevo pedido', '1', 'N');
INSERT INTO `bitacora` VALUES ('144', '226', '2011-08-24 00:00:00', 'Cierre de Caja del 2011-08-20', '4', 'N');
INSERT INTO `bitacora` VALUES ('145', '228', '2011-08-24 00:00:00', 'Apertura de Caja del 2011-08-24', '4', 'N');
INSERT INTO `bitacora` VALUES ('146', '230', '2011-08-24 21:48:12', 'Registro de nuevo pedido', '4', 'N');
INSERT INTO `bitacora` VALUES ('147', '231', '2011-08-24 22:29:23', 'Registro de nueva venta', '4', 'N');
INSERT INTO `bitacora` VALUES ('148', '233', '2011-08-25 00:00:00', 'Cierre de Caja del 2011-08-24', '4', 'N');
INSERT INTO `bitacora` VALUES ('149', '235', '2011-08-25 00:00:00', 'Apertura de Caja del 2011-08-25', '4', 'N');
INSERT INTO `categoria` VALUES ('1', 'XX', 'XX', '0', '1', 'A');
INSERT INTO `categoria` VALUES ('2', 'yy', 'yy', '1', '2', 'A');
INSERT INTO `categoria` VALUES ('3', 'RR', 'RR', '0', '1', 'A');
INSERT INTO `categoria` VALUES ('4', 'ttt', 'tt', '1', '2', 'A');
INSERT INTO `categoria` VALUES ('5', 'qqq', 'qq', '2', '3', 'A');
INSERT INTO `categoria` VALUES ('6', 'uuu', 'uu', '5', '4', 'A');
INSERT INTO `categoria` VALUES ('7', 'www', 'ww', '6', '6', 'A');
INSERT INTO `categoria` VALUES ('8', 'fff', 'ff', '4', '3', 'A');
INSERT INTO `categoria` VALUES ('9', 'yyyuyuyuy', 'yu', '4', '3', 'A');
INSERT INTO `categoria` VALUES ('10', 'pinturas', 'pi', '5', '4', 'A');
INSERT INTO `categoria` VALUES ('11', 'gggg', 'gg', null, '1', 'A');
INSERT INTO `categoria` VALUES ('12', 'PINTURAS', 'PI', null, '1', 'N');
INSERT INTO `categoria` VALUES ('13', 'ESMALTE SINTETICO', 'ES', '12', '2', 'N');
INSERT INTO `categoria` VALUES ('14', 'ESMALTE ACRILICO', 'EA', '12', '2', 'N');
INSERT INTO `categoria` VALUES ('15', 'LATEX', 'LA', '12', '2', 'N');
INSERT INTO `categoria` VALUES ('16', 'TINER', 'TI', null, '1', 'N');
INSERT INTO `categoria` VALUES ('17', 'TUBO ESCALONADO DE SOLDADURA FRIA', 'TE', null, '1', 'N');
INSERT INTO `categoria` VALUES ('18', 'XX', 'XX', '15', '3', 'A');
INSERT INTO `categoria` VALUES ('19', 'ALMBRE', 'AL', '18', '4', 'N');
INSERT INTO `conceptopago` VALUES ('1', 'APERTURA DE CAJA', 'I');
INSERT INTO `conceptopago` VALUES ('2', 'CIERRE DE CAJA', 'E');
INSERT INTO `conceptopago` VALUES ('3', 'PAGO DE CLIENTE', 'I');
INSERT INTO `conceptopago` VALUES ('4', 'PAGO A PROVEEDOR', 'E');
INSERT INTO `conceptopago` VALUES ('5', 'POR ANULACION DE VENTA', 'E');
INSERT INTO `conceptopago` VALUES ('6', 'POR ANULACION DE COMPRA', 'I');
INSERT INTO `conceptopago` VALUES ('7', 'PAGO A PERSONAL', 'E');
INSERT INTO `conceptopago` VALUES ('8', 'PARA COMPRA DOLARES', 'E');
INSERT INTO `conceptopago` VALUES ('9', 'POR COMPRA DOLARES', 'I');
INSERT INTO `conceptopago` VALUES ('10', 'AJUSTE TIPO CAMBIO', 'I');
INSERT INTO `conceptopago` VALUES ('11', 'AJUSTE TIPO CAMBIO', 'E');
INSERT INTO `conceptopago` VALUES ('12', 'PRESTAMO', 'I');
INSERT INTO `conceptopago` VALUES ('13', 'DEVOLUCION PRESTAMO', 'E');
INSERT INTO `conceptopago` VALUES ('14', 'PRESTAMO', 'E');
INSERT INTO `conceptopago` VALUES ('15', 'DEVOLUCION PRESTAMO', 'I');
INSERT INTO `conceptopago` VALUES ('16', 'DEPOSITO', 'E');
INSERT INTO `cuota` VALUES ('1', '1', '6', '2011-01-20 00:00:00', '2011-02-20 00:00:00', '2011-01-25 00:00:00', 'S', '100.00', '0.00', '100.00', '0.00', 'C');
INSERT INTO `cuota` VALUES ('2', '2', '6', '2011-01-20 00:00:00', '2011-03-20 00:00:00', '0000-00-00 00:00:00', 'S', '100.00', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('3', '1', '11', '2011-01-20 00:00:00', '2011-02-20 00:00:00', '0000-00-00 00:00:00', 'S', '139.70', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('4', '1', '12', '2011-01-20 00:00:00', '2011-02-20 00:00:00', '0000-00-00 00:00:00', 'S', '62.70', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('5', '1', '41', '2011-01-26 00:00:00', '2011-02-26 00:00:00', '2011-04-27 00:00:00', 'S', '70.00', '0.00', '70.00', '0.00', 'C');
INSERT INTO `cuota` VALUES ('6', '1', '53', '2011-01-30 00:00:00', '2011-02-28 00:00:00', '0000-00-00 00:00:00', 'S', '70.00', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('7', '1', '129', '2011-04-25 00:00:00', '2011-05-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('8', '2', '129', '2011-04-25 00:00:00', '2011-06-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('9', '3', '129', '2011-04-25 00:00:00', '2011-07-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N');
INSERT INTO `cuota` VALUES ('10', '4', '129', '2011-04-25 00:00:00', '2011-08-25 00:00:00', '0000-00-00 00:00:00', 'D', '4.50', '0.00', '0.00', '0.00', 'N');
INSERT INTO `departamento` VALUES ('1', 'AMAZONAS', '1', '01');
INSERT INTO `departamento` VALUES ('2', 'ANCASH', '1', '02');
INSERT INTO `departamento` VALUES ('3', 'APURIMAC', '1', '03');
INSERT INTO `departamento` VALUES ('4', 'AREQUIPA', '1', '04');
INSERT INTO `departamento` VALUES ('5', 'AYACUCHO', '1', '05');
INSERT INTO `departamento` VALUES ('6', 'CAJAMARCA', '1', '06');
INSERT INTO `departamento` VALUES ('7', 'CALLAO', '1', '07');
INSERT INTO `departamento` VALUES ('8', 'CUSCO', '1', '08');
INSERT INTO `departamento` VALUES ('9', 'HUANCAVELICA', '1', '09');
INSERT INTO `departamento` VALUES ('10', 'HUANUCO', '1', '10');
INSERT INTO `departamento` VALUES ('11', 'ICA', '1', '11');
INSERT INTO `departamento` VALUES ('12', 'JUNIN', '1', '12');
INSERT INTO `departamento` VALUES ('13', 'LA LIBERTAD', '1', '13');
INSERT INTO `departamento` VALUES ('14', 'LAMBAYEQUE', '1', '14');
INSERT INTO `departamento` VALUES ('15', 'LIMA', '1', '15');
INSERT INTO `departamento` VALUES ('16', 'LORETO', '1', '16');
INSERT INTO `departamento` VALUES ('17', 'MADRE DE DIOS', '1', '17');
INSERT INTO `departamento` VALUES ('18', 'MOQUEGUA', '1', '18');
INSERT INTO `departamento` VALUES ('19', 'PASCO', '1', '19');
INSERT INTO `departamento` VALUES ('20', 'PIURA', '1', '20');
INSERT INTO `departamento` VALUES ('21', 'PUNO', '1', '21');
INSERT INTO `departamento` VALUES ('22', 'SAN MARTIN', '1', '22');
INSERT INTO `departamento` VALUES ('23', 'TACNA', '1', '23');
INSERT INTO `departamento` VALUES ('24', 'TUMBES', '1', '24');
INSERT INTO `departamento` VALUES ('25', 'UCAYALI', '1', '25');
INSERT INTO `detallemovalmacen` VALUES ('1', '1', '1', '1', '10.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('2', '4', '1', '1', '3.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('3', '6', '1', '1', '3.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('4', '7', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('5', '9', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('6', '11', '1', '1', '2.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('7', '12', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('8', '17', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('9', '19', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('10', '21', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('15', '27', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('16', '33', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('17', '35', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('18', '40', '1', '1', '10.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('19', '41', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('20', '44', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('21', '46', '5', '1', '1.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('22', '51', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('23', '53', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('24', '74', '1', '1', '10.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('25', '74', '5', '1', '5.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('26', '79', '1', '1', '10.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('27', '81', '5', '1', '1.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('28', '83', '5', '1', '1.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('29', '84', '5', '1', '10.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('30', '93', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('31', '93', '5', '1', '2.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('32', '94', '1', '1', '2.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('33', '104', '1', '1', '2.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('34', '111', '1', '1', '4.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('35', '116', '5', '1', '2.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('36', '118', '8', '1', '1.00', '25.00', '27.00');
INSERT INTO `detallemovalmacen` VALUES ('37', '119', '6', '1', '10.00', '5.00', '6.00');
INSERT INTO `detallemovalmacen` VALUES ('38', '124', '6', '1', '20.00', '5.00', '6.00');
INSERT INTO `detallemovalmacen` VALUES ('39', '125', '5', '1', '2.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('40', '127', '1', '1', '5.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('41', '129', '3', '1', '6.00', '1.05', '1.40');
INSERT INTO `detallemovalmacen` VALUES ('42', '135', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('43', '143', '6', '1', '5.00', '5.00', '6.00');
INSERT INTO `detallemovalmacen` VALUES ('44', '145', '1', '1', '3.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('45', '145', '5', '1', '2.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('46', '147', '6', '1', '2.00', '5.00', '6.00');
INSERT INTO `detallemovalmacen` VALUES ('47', '149', '1', '1', '3.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('48', '149', '5', '1', '2.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('49', '150', '1', '1', '2.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('50', '151', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('51', '153', '5', '1', '10.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('52', '154', '9', '1', '4.00', '55.00', '55.00');
INSERT INTO `detallemovalmacen` VALUES ('53', '155', '9', '1', '1.00', '45.00', '55.00');
INSERT INTO `detallemovalmacen` VALUES ('54', '155', '7', '1', '10.00', '10.00', '10.00');
INSERT INTO `detallemovalmacen` VALUES ('55', '156', '2', '1', '3.00', '1.00', '2.00');
INSERT INTO `detallemovalmacen` VALUES ('56', '166', '9', '1', '1.00', '45.00', '55.00');
INSERT INTO `detallemovalmacen` VALUES ('57', '168', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('58', '184', '9', '1', '1.00', '45.00', '55.00');
INSERT INTO `detallemovalmacen` VALUES ('59', '184', '7', '1', '1.00', '10.00', '10.00');
INSERT INTO `detallemovalmacen` VALUES ('60', '189', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('61', '191', '5', '1', '1.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('62', '192', '3', '1', '1.00', '0.37', '4.00');
INSERT INTO `detallemovalmacen` VALUES ('63', '192', '8', '1', '1.00', '25.00', '27.00');
INSERT INTO `detallemovalmacen` VALUES ('64', '193', '9', '1', '1.00', '45.00', '55.00');
INSERT INTO `detallemovalmacen` VALUES ('65', '193', '7', '1', '1.00', '10.00', '10.00');
INSERT INTO `detallemovalmacen` VALUES ('66', '195', '5', '1', '1.00', '0.50', '0.70');
INSERT INTO `detallemovalmacen` VALUES ('67', '197', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('68', '198', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('69', '199', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('70', '201', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('71', '202', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('72', '204', '3', '1', '1.00', '0.37', '4.00');
INSERT INTO `detallemovalmacen` VALUES ('73', '205', '3', '1', '1.00', '0.37', '4.00');
INSERT INTO `detallemovalmacen` VALUES ('74', '207', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('75', '208', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('76', '210', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('77', '216', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('78', '225', '1', '1', '1.00', '62.70', '71.22');
INSERT INTO `detallemovalmacen` VALUES ('79', '230', '9', '1', '1.00', '45.00', '55.00');
INSERT INTO `detallemovalmacen` VALUES ('80', '231', '9', '1', '1.00', '45.00', '55.00');
INSERT INTO `detallemovcaja` VALUES ('1', '32', '1', '100.00', '0.00', 'S', '2.85');
INSERT INTO `detallemovcaja` VALUES ('2', '138', '5', '70.00', '0.00', 'S', '2.85');
INSERT INTO `distrito` VALUES ('1', 'CHACHAPOYAS', '01', '1');
INSERT INTO `distrito` VALUES ('2', 'ASUNCION', '02', '1');
INSERT INTO `distrito` VALUES ('3', 'BALSAS', '03', '1');
INSERT INTO `distrito` VALUES ('4', 'CHETO', '04', '1');
INSERT INTO `distrito` VALUES ('5', 'CHILIQUIN', '05', '1');
INSERT INTO `distrito` VALUES ('6', 'CHUQUIBAMBA', '06', '1');
INSERT INTO `distrito` VALUES ('7', 'GRANADA', '07', '1');
INSERT INTO `distrito` VALUES ('8', 'HUANCAS', '08', '1');
INSERT INTO `distrito` VALUES ('9', 'LA JALCA', '09', '1');
INSERT INTO `distrito` VALUES ('10', 'LEIMEBAMBA', '10', '1');
INSERT INTO `distrito` VALUES ('11', 'LEVANTO', '11', '1');
INSERT INTO `distrito` VALUES ('12', 'MAGDALENA', '12', '1');
INSERT INTO `distrito` VALUES ('13', 'MARISCAL CASTILLA', '13', '1');
INSERT INTO `distrito` VALUES ('14', 'MOLINOPAMPA', '14', '1');
INSERT INTO `distrito` VALUES ('15', 'MONTEVIDEO', '15', '1');
INSERT INTO `distrito` VALUES ('16', 'OLLEROS', '16', '1');
INSERT INTO `distrito` VALUES ('17', 'QUINJALCA', '17', '1');
INSERT INTO `distrito` VALUES ('18', 'SAN FRANCISCO DE DAGUAS', '18', '1');
INSERT INTO `distrito` VALUES ('19', 'SAN ISIDRO DE MAINO', '19', '1');
INSERT INTO `distrito` VALUES ('20', 'SOLOCO', '20', '1');
INSERT INTO `distrito` VALUES ('21', 'SONCHE', '21', '1');
INSERT INTO `distrito` VALUES ('22', 'LA PECA', '01', '2');
INSERT INTO `distrito` VALUES ('23', 'ARAMANGO', '02', '2');
INSERT INTO `distrito` VALUES ('24', 'COPALLIN', '03', '2');
INSERT INTO `distrito` VALUES ('25', 'EL PARCO', '04', '2');
INSERT INTO `distrito` VALUES ('26', 'IMAZA', '05', '2');
INSERT INTO `distrito` VALUES ('27', 'JUMBILLA', '01', '3');
INSERT INTO `distrito` VALUES ('28', 'CHISQUILLA', '02', '3');
INSERT INTO `distrito` VALUES ('29', 'CHURUJA', '03', '3');
INSERT INTO `distrito` VALUES ('30', 'COROSHA', '04', '3');
INSERT INTO `distrito` VALUES ('31', 'CUISPES', '05', '3');
INSERT INTO `distrito` VALUES ('32', 'FLORIDA', '06', '3');
INSERT INTO `distrito` VALUES ('33', 'JAZAN', '07', '3');
INSERT INTO `distrito` VALUES ('34', 'RECTA', '08', '3');
INSERT INTO `distrito` VALUES ('35', 'SAN CARLOS', '09', '3');
INSERT INTO `distrito` VALUES ('36', 'SHIPASBAMBA', '10', '3');
INSERT INTO `distrito` VALUES ('37', 'VALERA', '11', '3');
INSERT INTO `distrito` VALUES ('38', 'YAMBRASBAMBA', '12', '3');
INSERT INTO `distrito` VALUES ('39', 'NIEVA', '01', '4');
INSERT INTO `distrito` VALUES ('40', 'EL CENEPA', '02', '4');
INSERT INTO `distrito` VALUES ('41', 'RIO SANTIAGO', '03', '4');
INSERT INTO `distrito` VALUES ('42', 'LAMUD', '01', '5');
INSERT INTO `distrito` VALUES ('43', 'CAMPORREDONDO', '02', '5');
INSERT INTO `distrito` VALUES ('44', 'COCABAMBA', '03', '5');
INSERT INTO `distrito` VALUES ('45', 'COLCAMAR', '04', '5');
INSERT INTO `distrito` VALUES ('46', 'CONILA', '05', '5');
INSERT INTO `distrito` VALUES ('47', 'INGUILPATA', '06', '5');
INSERT INTO `distrito` VALUES ('48', 'LONGUITA', '07', '5');
INSERT INTO `distrito` VALUES ('49', 'LONYA CHICO', '08', '5');
INSERT INTO `distrito` VALUES ('50', 'LUYA', '09', '5');
INSERT INTO `distrito` VALUES ('51', 'LUYA VIEJO', '10', '5');
INSERT INTO `distrito` VALUES ('52', 'MARIA', '11', '5');
INSERT INTO `distrito` VALUES ('53', 'OCALLI', '12', '5');
INSERT INTO `distrito` VALUES ('54', 'OCUMAL', '13', '5');
INSERT INTO `distrito` VALUES ('55', 'PISUQUIA', '14', '5');
INSERT INTO `distrito` VALUES ('56', 'PROVIDENCIA', '15', '5');
INSERT INTO `distrito` VALUES ('57', 'SAN CRISTOBAL', '16', '5');
INSERT INTO `distrito` VALUES ('58', 'SAN FRANCISCO DEL YESO', '17', '5');
INSERT INTO `distrito` VALUES ('59', 'SAN JERONIMO', '18', '5');
INSERT INTO `distrito` VALUES ('60', 'SAN JUAN DE LOPECANCHA', '19', '5');
INSERT INTO `distrito` VALUES ('61', 'SANTA CATALINA', '20', '5');
INSERT INTO `distrito` VALUES ('62', 'SANTO TOMAS', '21', '5');
INSERT INTO `distrito` VALUES ('63', 'TINGO', '22', '5');
INSERT INTO `distrito` VALUES ('64', 'TRITA', '23', '5');
INSERT INTO `distrito` VALUES ('65', 'SAN NICOLAS', '01', '6');
INSERT INTO `distrito` VALUES ('66', 'CHIRIMOTO', '02', '6');
INSERT INTO `distrito` VALUES ('67', 'COCHAMAL', '03', '6');
INSERT INTO `distrito` VALUES ('68', 'HUAMBO', '04', '6');
INSERT INTO `distrito` VALUES ('69', 'LIMABAMBA', '05', '6');
INSERT INTO `distrito` VALUES ('70', 'LONGAR', '06', '6');
INSERT INTO `distrito` VALUES ('71', 'MARISCAL BENAVIDES', '07', '6');
INSERT INTO `distrito` VALUES ('72', 'MILPUC', '08', '6');
INSERT INTO `distrito` VALUES ('73', 'OMIA', '09', '6');
INSERT INTO `distrito` VALUES ('74', 'SANTA ROSA', '10', '6');
INSERT INTO `distrito` VALUES ('75', 'TOTORA', '11', '6');
INSERT INTO `distrito` VALUES ('76', 'VISTA ALEGRE', '12', '6');
INSERT INTO `distrito` VALUES ('77', 'BAGUA GRANDE', '01', '7');
INSERT INTO `distrito` VALUES ('78', 'CAJARURO', '02', '7');
INSERT INTO `distrito` VALUES ('79', 'CUMBA', '03', '7');
INSERT INTO `distrito` VALUES ('80', 'EL MILAGRO', '04', '7');
INSERT INTO `distrito` VALUES ('81', 'JAMALCA', '05', '7');
INSERT INTO `distrito` VALUES ('82', 'LONYA GRANDE', '06', '7');
INSERT INTO `distrito` VALUES ('83', 'YAMON', '07', '7');
INSERT INTO `distrito` VALUES ('84', 'HUANUCO', '01', '88');
INSERT INTO `distrito` VALUES ('85', 'AMARILIS', '02', '88');
INSERT INTO `distrito` VALUES ('86', 'CHINCHAO', '03', '88');
INSERT INTO `distrito` VALUES ('87', 'CHURUBAMBA', '04', '88');
INSERT INTO `distrito` VALUES ('88', 'MARGOS', '05', '88');
INSERT INTO `distrito` VALUES ('89', 'QUISQUI', '06', '88');
INSERT INTO `distrito` VALUES ('90', 'SAN FRANCISCO DE CAYRAN', '07', '88');
INSERT INTO `distrito` VALUES ('91', 'SAN PEDRO DE CHAULAN', '08', '88');
INSERT INTO `distrito` VALUES ('92', 'SANTA MARIA DEL VALLE', '09', '88');
INSERT INTO `distrito` VALUES ('93', 'YARUMAYO', '10', '88');
INSERT INTO `distrito` VALUES ('94', 'PILLCO MARCA', '11', '88');
INSERT INTO `distrito` VALUES ('95', 'AMBO', '01', '89');
INSERT INTO `distrito` VALUES ('96', 'CAYNA', '02', '89');
INSERT INTO `distrito` VALUES ('97', 'COLPAS', '03', '89');
INSERT INTO `distrito` VALUES ('98', 'CONCHAMARCA', '04', '89');
INSERT INTO `distrito` VALUES ('99', 'HUACAR', '05', '89');
INSERT INTO `distrito` VALUES ('100', 'SAN FRANCISCO', '06', '89');
INSERT INTO `distrito` VALUES ('101', 'SAN RAFAEL', '07', '89');
INSERT INTO `distrito` VALUES ('102', 'TOMAY KICHWA', '08', '89');
INSERT INTO `distrito` VALUES ('103', 'LA UNION', '01', '90');
INSERT INTO `distrito` VALUES ('104', 'CHUQUIS', '07', '90');
INSERT INTO `distrito` VALUES ('105', 'MARIAS', '11', '90');
INSERT INTO `distrito` VALUES ('106', 'PACHAS', '13', '90');
INSERT INTO `distrito` VALUES ('107', 'QUIVILLA', '16', '90');
INSERT INTO `distrito` VALUES ('108', 'RIPAN', '17', '90');
INSERT INTO `distrito` VALUES ('109', 'SHUNQUI', '21', '90');
INSERT INTO `distrito` VALUES ('110', 'SILLAPATA', '22', '90');
INSERT INTO `distrito` VALUES ('111', 'YANAS', '23', '90');
INSERT INTO `distrito` VALUES ('112', 'HUACAYBAMBA', '01', '91');
INSERT INTO `distrito` VALUES ('113', 'CANCHABAMBA', '02', '91');
INSERT INTO `distrito` VALUES ('114', 'COCHABAMBA', '03', '91');
INSERT INTO `distrito` VALUES ('115', 'PINRA', '04', '91');
INSERT INTO `distrito` VALUES ('116', 'LLATA', '01', '92');
INSERT INTO `distrito` VALUES ('117', 'ARANCAY', '02', '92');
INSERT INTO `distrito` VALUES ('118', 'CHAVIN DE PARIARCA', '03', '92');
INSERT INTO `distrito` VALUES ('119', 'JACAS GRANDE', '04', '92');
INSERT INTO `distrito` VALUES ('120', 'JIRCAN', '05', '92');
INSERT INTO `distrito` VALUES ('121', 'MIRAFLORES', '06', '92');
INSERT INTO `distrito` VALUES ('122', 'MONZON', '07', '92');
INSERT INTO `distrito` VALUES ('123', 'PUNCHAO', '08', '92');
INSERT INTO `distrito` VALUES ('124', 'PU?OS', '09', '92');
INSERT INTO `distrito` VALUES ('125', 'SINGA', '10', '92');
INSERT INTO `distrito` VALUES ('126', 'TANTAMAYO', '11', '92');
INSERT INTO `distrito` VALUES ('127', 'RUPA-RUPA', '01', '93');
INSERT INTO `distrito` VALUES ('128', 'DANIEL ALOMIA ROBLES', '02', '93');
INSERT INTO `distrito` VALUES ('129', 'HERMILIO VALDIZAN', '03', '93');
INSERT INTO `distrito` VALUES ('130', 'JOSE CRESPO Y CASTILLO', '04', '93');
INSERT INTO `distrito` VALUES ('131', 'LUYANDO', '05', '93');
INSERT INTO `distrito` VALUES ('132', 'MARIANO DAMASO BERAUN', '06', '93');
INSERT INTO `distrito` VALUES ('133', 'HUACRACHUCO', '01', '94');
INSERT INTO `distrito` VALUES ('134', 'CHOLON', '02', '94');
INSERT INTO `distrito` VALUES ('135', 'SAN BUENAVENTURA', '03', '94');
INSERT INTO `distrito` VALUES ('136', 'PANAO', '01', '95');
INSERT INTO `distrito` VALUES ('137', 'CHAGLLA', '02', '95');
INSERT INTO `distrito` VALUES ('138', 'MOLINO', '03', '95');
INSERT INTO `distrito` VALUES ('139', 'UMARI', '04', '95');
INSERT INTO `distrito` VALUES ('140', 'PUERTO INCA', '01', '96');
INSERT INTO `distrito` VALUES ('141', 'CODO DEL POZUZO', '02', '96');
INSERT INTO `distrito` VALUES ('142', 'HONORIA', '03', '96');
INSERT INTO `distrito` VALUES ('143', 'TOURNAVISTA', '04', '96');
INSERT INTO `distrito` VALUES ('144', 'YUYAPICHIS', '05', '96');
INSERT INTO `distrito` VALUES ('145', 'JESUS', '01', '97');
INSERT INTO `distrito` VALUES ('146', 'BA?OS', '02', '97');
INSERT INTO `distrito` VALUES ('147', 'JIVIA', '03', '97');
INSERT INTO `distrito` VALUES ('148', 'QUEROPALCA', '04', '97');
INSERT INTO `distrito` VALUES ('149', 'RONDOS', '05', '97');
INSERT INTO `distrito` VALUES ('150', 'SAN FRANCISCO DE ASIS', '06', '97');
INSERT INTO `distrito` VALUES ('151', 'SAN MIGUEL DE CAURI', '07', '97');
INSERT INTO `distrito` VALUES ('152', 'CHAVINILLO', '01', '98');
INSERT INTO `distrito` VALUES ('153', 'CAHUAC', '02', '98');
INSERT INTO `distrito` VALUES ('154', 'CHACABAMBA', '03', '98');
INSERT INTO `distrito` VALUES ('155', 'APARICIO POMARES', '04', '98');
INSERT INTO `distrito` VALUES ('156', 'JACAS CHICO', '05', '98');
INSERT INTO `distrito` VALUES ('157', 'OBAS', '06', '98');
INSERT INTO `distrito` VALUES ('158', 'PAMPAMARCA', '07', '98');
INSERT INTO `distrito` VALUES ('159', 'CHORAS', '08', '98');
INSERT INTO `distrito` VALUES ('160', 'ICA', '01', '99');
INSERT INTO `distrito` VALUES ('161', 'LA TINGUI?A', '02', '99');
INSERT INTO `distrito` VALUES ('162', 'LOS AQUIJES', '03', '99');
INSERT INTO `distrito` VALUES ('163', 'OCUCAJE', '04', '99');
INSERT INTO `distrito` VALUES ('164', 'PACHACUTEC', '05', '99');
INSERT INTO `distrito` VALUES ('165', 'PARCONA', '06', '99');
INSERT INTO `distrito` VALUES ('166', 'PUEBLO NUEVO', '07', '99');
INSERT INTO `distrito` VALUES ('167', 'SALAS', '08', '99');
INSERT INTO `distrito` VALUES ('168', 'SAN JOSE DE LOS MOLINOS', '09', '99');
INSERT INTO `distrito` VALUES ('169', 'SAN JUAN BAUTISTA', '10', '99');
INSERT INTO `distrito` VALUES ('170', 'SANTIAGO', '11', '99');
INSERT INTO `distrito` VALUES ('171', 'SUBTANJALLA', '12', '99');
INSERT INTO `distrito` VALUES ('172', 'TATE', '13', '99');
INSERT INTO `distrito` VALUES ('173', 'YAUCA DEL ROSARIO', '14', '99');
INSERT INTO `distrito` VALUES ('174', 'CHINCHA ALTA', '01', '100');
INSERT INTO `distrito` VALUES ('175', 'ALTO LARAN', '02', '100');
INSERT INTO `distrito` VALUES ('176', 'CHAVIN', '03', '100');
INSERT INTO `distrito` VALUES ('177', 'CHINCHA BAJA', '04', '100');
INSERT INTO `distrito` VALUES ('178', 'EL CARMEN', '05', '100');
INSERT INTO `distrito` VALUES ('179', 'GROCIO PRADO', '06', '100');
INSERT INTO `distrito` VALUES ('180', 'PUEBLO NUEVO', '07', '100');
INSERT INTO `distrito` VALUES ('181', 'SAN JUAN DE YANAC', '08', '100');
INSERT INTO `distrito` VALUES ('182', 'SAN PEDRO DE HUACARPANA', '09', '100');
INSERT INTO `distrito` VALUES ('183', 'SUNAMPE', '10', '100');
INSERT INTO `distrito` VALUES ('184', 'TAMBO DE MORA', '11', '100');
INSERT INTO `distrito` VALUES ('185', 'NAZCA', '01', '101');
INSERT INTO `distrito` VALUES ('186', 'CHANGUILLO', '02', '101');
INSERT INTO `distrito` VALUES ('187', 'EL INGENIO', '03', '101');
INSERT INTO `distrito` VALUES ('188', 'MARCONA', '04', '101');
INSERT INTO `distrito` VALUES ('189', 'VISTA ALEGRE', '05', '101');
INSERT INTO `distrito` VALUES ('190', 'PALPA', '01', '102');
INSERT INTO `distrito` VALUES ('191', 'LLIPATA', '02', '102');
INSERT INTO `distrito` VALUES ('192', 'RIO GRANDE', '03', '102');
INSERT INTO `distrito` VALUES ('193', 'SANTA CRUZ', '04', '102');
INSERT INTO `distrito` VALUES ('194', 'TIBILLO', '05', '102');
INSERT INTO `distrito` VALUES ('195', 'PISCO', '01', '103');
INSERT INTO `distrito` VALUES ('196', 'HUANCANO', '02', '103');
INSERT INTO `distrito` VALUES ('197', 'HUMAY', '03', '103');
INSERT INTO `distrito` VALUES ('198', 'INDEPENDENCIA', '04', '103');
INSERT INTO `distrito` VALUES ('199', 'PARACAS', '05', '103');
INSERT INTO `distrito` VALUES ('200', 'SAN ANDRES', '06', '103');
INSERT INTO `distrito` VALUES ('201', 'SAN CLEMENTE', '07', '103');
INSERT INTO `distrito` VALUES ('202', 'TUPAC AMARU INCA', '08', '103');
INSERT INTO `distrito` VALUES ('203', 'HUANCAYO', '01', '104');
INSERT INTO `distrito` VALUES ('204', 'CARHUACALLANGA', '04', '104');
INSERT INTO `distrito` VALUES ('205', 'CHACAPAMPA', '05', '104');
INSERT INTO `distrito` VALUES ('206', 'CHICCHE', '06', '104');
INSERT INTO `distrito` VALUES ('207', 'CHILCA', '07', '104');
INSERT INTO `distrito` VALUES ('208', 'CHONGOS ALTO', '08', '104');
INSERT INTO `distrito` VALUES ('209', 'CHUPURO', '11', '104');
INSERT INTO `distrito` VALUES ('210', 'COLCA', '12', '104');
INSERT INTO `distrito` VALUES ('211', 'CULLHUAS', '13', '104');
INSERT INTO `distrito` VALUES ('212', 'EL TAMBO', '14', '104');
INSERT INTO `distrito` VALUES ('213', 'HUACRAPUQUIO', '16', '104');
INSERT INTO `distrito` VALUES ('214', 'HUALHUAS', '17', '104');
INSERT INTO `distrito` VALUES ('215', 'HUANCAN', '19', '104');
INSERT INTO `distrito` VALUES ('216', 'HUASICANCHA', '20', '104');
INSERT INTO `distrito` VALUES ('217', 'HUAYUCACHI', '21', '104');
INSERT INTO `distrito` VALUES ('218', 'INGENIO', '22', '104');
INSERT INTO `distrito` VALUES ('219', 'PARIAHUANCA', '24', '104');
INSERT INTO `distrito` VALUES ('220', 'PILCOMAYO', '25', '104');
INSERT INTO `distrito` VALUES ('221', 'PUCARA', '26', '104');
INSERT INTO `distrito` VALUES ('222', 'QUICHUAY', '27', '104');
INSERT INTO `distrito` VALUES ('223', 'QUILCAS', '28', '104');
INSERT INTO `distrito` VALUES ('224', 'SAN AGUSTIN', '29', '104');
INSERT INTO `distrito` VALUES ('225', 'SAN JERONIMO DE TUNAN', '30', '104');
INSERT INTO `distrito` VALUES ('226', 'SA?O', '32', '104');
INSERT INTO `distrito` VALUES ('227', 'SAPALLANGA', '33', '104');
INSERT INTO `distrito` VALUES ('228', 'SICAYA', '34', '104');
INSERT INTO `distrito` VALUES ('229', 'SANTO DOMINGO DE ACOBAMBA', '35', '104');
INSERT INTO `distrito` VALUES ('230', 'VIQUES', '36', '104');
INSERT INTO `distrito` VALUES ('231', 'CONCEPCION', '01', '105');
INSERT INTO `distrito` VALUES ('232', 'ACO', '02', '105');
INSERT INTO `distrito` VALUES ('233', 'ANDAMARCA', '03', '105');
INSERT INTO `distrito` VALUES ('234', 'CHAMBARA', '04', '105');
INSERT INTO `distrito` VALUES ('235', 'COCHAS', '05', '105');
INSERT INTO `distrito` VALUES ('236', 'COMAS', '06', '105');
INSERT INTO `distrito` VALUES ('237', 'HEROINAS TOLEDO', '07', '105');
INSERT INTO `distrito` VALUES ('238', 'MANZANARES', '08', '105');
INSERT INTO `distrito` VALUES ('239', 'MARISCAL CASTILLA', '09', '105');
INSERT INTO `distrito` VALUES ('240', 'MATAHUASI', '10', '105');
INSERT INTO `distrito` VALUES ('241', 'MITO', '11', '105');
INSERT INTO `distrito` VALUES ('242', 'NUEVE DE JULIO', '12', '105');
INSERT INTO `distrito` VALUES ('243', 'ORCOTUNA', '13', '105');
INSERT INTO `distrito` VALUES ('244', 'SAN JOSE DE QUERO', '14', '105');
INSERT INTO `distrito` VALUES ('245', 'SANTA ROSA DE OCOPA', '15', '105');
INSERT INTO `distrito` VALUES ('246', 'CHANCHAMAYO', '01', '106');
INSERT INTO `distrito` VALUES ('247', 'PERENE', '02', '106');
INSERT INTO `distrito` VALUES ('248', 'PICHANAQUI', '03', '106');
INSERT INTO `distrito` VALUES ('249', 'SAN LUIS DE SHUARO', '04', '106');
INSERT INTO `distrito` VALUES ('250', 'SAN RAMON', '05', '106');
INSERT INTO `distrito` VALUES ('251', 'VITOC', '06', '106');
INSERT INTO `distrito` VALUES ('252', 'JAUJA', '01', '107');
INSERT INTO `distrito` VALUES ('253', 'ACOLLA', '02', '107');
INSERT INTO `distrito` VALUES ('254', 'APATA', '03', '107');
INSERT INTO `distrito` VALUES ('255', 'ATAURA', '04', '107');
INSERT INTO `distrito` VALUES ('256', 'CANCHAYLLO', '05', '107');
INSERT INTO `distrito` VALUES ('257', 'CURICACA', '06', '107');
INSERT INTO `distrito` VALUES ('258', 'EL MANTARO', '07', '107');
INSERT INTO `distrito` VALUES ('259', 'HUAMALI', '08', '107');
INSERT INTO `distrito` VALUES ('260', 'HUARIPAMPA', '09', '107');
INSERT INTO `distrito` VALUES ('261', 'HUERTAS', '10', '107');
INSERT INTO `distrito` VALUES ('262', 'JANJAILLO', '11', '107');
INSERT INTO `distrito` VALUES ('263', 'JULCAN', '12', '107');
INSERT INTO `distrito` VALUES ('264', 'LEONOR ORDO?EZ', '13', '107');
INSERT INTO `distrito` VALUES ('265', 'LLOCLLAPAMPA', '14', '107');
INSERT INTO `distrito` VALUES ('266', 'MARCO', '15', '107');
INSERT INTO `distrito` VALUES ('267', 'MASMA', '16', '107');
INSERT INTO `distrito` VALUES ('268', 'MASMA CHICCHE', '17', '107');
INSERT INTO `distrito` VALUES ('269', 'MOLINOS', '18', '107');
INSERT INTO `distrito` VALUES ('270', 'MONOBAMBA', '19', '107');
INSERT INTO `distrito` VALUES ('271', 'MUQUI', '20', '107');
INSERT INTO `distrito` VALUES ('272', 'MUQUIYAUYO', '21', '107');
INSERT INTO `distrito` VALUES ('273', 'PACA', '22', '107');
INSERT INTO `distrito` VALUES ('274', 'PACCHA', '23', '107');
INSERT INTO `distrito` VALUES ('275', 'PANCAN', '24', '107');
INSERT INTO `distrito` VALUES ('276', 'PARCO', '25', '107');
INSERT INTO `distrito` VALUES ('277', 'POMACANCHA', '26', '107');
INSERT INTO `distrito` VALUES ('278', 'RICRAN', '27', '107');
INSERT INTO `distrito` VALUES ('279', 'SAN LORENZO', '28', '107');
INSERT INTO `distrito` VALUES ('280', 'SAN PEDRO DE CHUNAN', '29', '107');
INSERT INTO `distrito` VALUES ('281', 'SAUSA', '30', '107');
INSERT INTO `distrito` VALUES ('282', 'SINCOS', '31', '107');
INSERT INTO `distrito` VALUES ('283', 'TUNAN MARCA', '32', '107');
INSERT INTO `distrito` VALUES ('284', 'YAULI', '33', '107');
INSERT INTO `distrito` VALUES ('285', 'YAUYOS', '34', '107');
INSERT INTO `distrito` VALUES ('286', 'JUNIN', '01', '108');
INSERT INTO `distrito` VALUES ('287', 'CARHUAMAYO', '02', '108');
INSERT INTO `distrito` VALUES ('288', 'ONDORES', '03', '108');
INSERT INTO `distrito` VALUES ('289', 'ULCUMAYO', '04', '108');
INSERT INTO `distrito` VALUES ('290', 'SATIPO', '01', '109');
INSERT INTO `distrito` VALUES ('291', 'COVIRIALI', '02', '109');
INSERT INTO `distrito` VALUES ('292', 'LLAYLLA', '03', '109');
INSERT INTO `distrito` VALUES ('293', 'MAZAMARI', '04', '109');
INSERT INTO `distrito` VALUES ('294', 'PAMPA HERMOSA', '05', '109');
INSERT INTO `distrito` VALUES ('295', 'PANGOA', '06', '109');
INSERT INTO `distrito` VALUES ('296', 'RIO NEGRO', '07', '109');
INSERT INTO `distrito` VALUES ('297', 'RIO TAMBO', '08', '109');
INSERT INTO `distrito` VALUES ('298', 'TARMA', '01', '110');
INSERT INTO `distrito` VALUES ('299', 'ACOBAMBA', '02', '110');
INSERT INTO `distrito` VALUES ('300', 'HUARICOLCA', '03', '110');
INSERT INTO `distrito` VALUES ('301', 'HUASAHUASI', '04', '110');
INSERT INTO `distrito` VALUES ('302', 'LA UNION', '05', '110');
INSERT INTO `distrito` VALUES ('303', 'PALCA', '06', '110');
INSERT INTO `distrito` VALUES ('304', 'PALCAMAYO', '07', '110');
INSERT INTO `distrito` VALUES ('305', 'SAN PEDRO DE CAJAS', '08', '110');
INSERT INTO `distrito` VALUES ('306', 'TAPO', '09', '110');
INSERT INTO `distrito` VALUES ('307', 'LA OROYA', '01', '111');
INSERT INTO `distrito` VALUES ('308', 'CHACAPALPA', '02', '111');
INSERT INTO `distrito` VALUES ('309', 'HUAY-HUAY', '03', '111');
INSERT INTO `distrito` VALUES ('310', 'MARCAPOMACOCHA', '04', '111');
INSERT INTO `distrito` VALUES ('311', 'MOROCOCHA', '05', '111');
INSERT INTO `distrito` VALUES ('312', 'PACCHA', '06', '111');
INSERT INTO `distrito` VALUES ('313', 'SANTA BARBARA DE CARHUACAYAN', '07', '111');
INSERT INTO `distrito` VALUES ('314', 'SANTA ROSA DE SACCO', '08', '111');
INSERT INTO `distrito` VALUES ('315', 'SUITUCANCHA', '09', '111');
INSERT INTO `distrito` VALUES ('316', 'YAULI', '10', '111');
INSERT INTO `distrito` VALUES ('317', 'CHUPACA', '01', '112');
INSERT INTO `distrito` VALUES ('318', 'AHUAC', '02', '112');
INSERT INTO `distrito` VALUES ('319', 'CHONGOS BAJO', '03', '112');
INSERT INTO `distrito` VALUES ('320', 'HUACHAC', '04', '112');
INSERT INTO `distrito` VALUES ('321', 'HUAMANCACA CHICO', '05', '112');
INSERT INTO `distrito` VALUES ('322', 'SAN JUAN DE ISCOS', '06', '112');
INSERT INTO `distrito` VALUES ('323', 'SAN JUAN DE JARPA', '07', '112');
INSERT INTO `distrito` VALUES ('324', 'TRES DE DICIEMBRE', '08', '112');
INSERT INTO `distrito` VALUES ('325', 'YANACANCHA', '09', '112');
INSERT INTO `distrito` VALUES ('326', 'TRUJILLO', '01', '113');
INSERT INTO `distrito` VALUES ('327', 'EL PORVENIR', '02', '113');
INSERT INTO `distrito` VALUES ('328', 'FLORENCIA DE MORA', '03', '113');
INSERT INTO `distrito` VALUES ('329', 'HUANCHACO', '04', '113');
INSERT INTO `distrito` VALUES ('330', 'LA ESPERANZA', '05', '113');
INSERT INTO `distrito` VALUES ('331', 'LAREDO', '06', '113');
INSERT INTO `distrito` VALUES ('332', 'MOCHE', '07', '113');
INSERT INTO `distrito` VALUES ('333', 'POROTO', '08', '113');
INSERT INTO `distrito` VALUES ('334', 'SALAVERRY', '09', '113');
INSERT INTO `distrito` VALUES ('335', 'SIMBAL', '10', '113');
INSERT INTO `distrito` VALUES ('336', 'VICTOR LARCO HERRERA', '11', '113');
INSERT INTO `distrito` VALUES ('337', 'ASCOPE', '01', '114');
INSERT INTO `distrito` VALUES ('338', 'CHICAMA', '02', '114');
INSERT INTO `distrito` VALUES ('339', 'CHOCOPE', '03', '114');
INSERT INTO `distrito` VALUES ('340', 'MAGDALENA DE CAO', '04', '114');
INSERT INTO `distrito` VALUES ('341', 'PAIJAN', '05', '114');
INSERT INTO `distrito` VALUES ('342', 'RAZURI', '06', '114');
INSERT INTO `distrito` VALUES ('343', 'SANTIAGO DE CAO', '07', '114');
INSERT INTO `distrito` VALUES ('344', 'CASA GRANDE', '08', '114');
INSERT INTO `distrito` VALUES ('345', 'BOLIVAR', '01', '115');
INSERT INTO `distrito` VALUES ('346', 'BAMBAMARCA', '02', '115');
INSERT INTO `distrito` VALUES ('347', 'CONDORMARCA', '03', '115');
INSERT INTO `distrito` VALUES ('348', 'LONGOTEA', '04', '115');
INSERT INTO `distrito` VALUES ('349', 'UCHUMARCA', '05', '115');
INSERT INTO `distrito` VALUES ('350', 'UCUNCHA', '06', '115');
INSERT INTO `distrito` VALUES ('351', 'CHEPEN', '01', '116');
INSERT INTO `distrito` VALUES ('352', 'PACANGA', '02', '116');
INSERT INTO `distrito` VALUES ('353', 'PUEBLO NUEVO', '03', '116');
INSERT INTO `distrito` VALUES ('354', 'JULCAN', '01', '117');
INSERT INTO `distrito` VALUES ('355', 'CALAMARCA', '02', '117');
INSERT INTO `distrito` VALUES ('356', 'CARABAMBA', '03', '117');
INSERT INTO `distrito` VALUES ('357', 'HUASO', '04', '117');
INSERT INTO `distrito` VALUES ('358', 'OTUZCO', '01', '118');
INSERT INTO `distrito` VALUES ('359', 'AGALLPAMPA', '02', '118');
INSERT INTO `distrito` VALUES ('360', 'CHARAT', '04', '118');
INSERT INTO `distrito` VALUES ('361', 'HUARANCHAL', '05', '118');
INSERT INTO `distrito` VALUES ('362', 'LA CUESTA', '06', '118');
INSERT INTO `distrito` VALUES ('363', 'MACHE', '08', '118');
INSERT INTO `distrito` VALUES ('364', 'PARANDAY', '10', '118');
INSERT INTO `distrito` VALUES ('365', 'SALPO', '11', '118');
INSERT INTO `distrito` VALUES ('366', 'SINSICAP', '13', '118');
INSERT INTO `distrito` VALUES ('367', 'USQUIL', '14', '118');
INSERT INTO `distrito` VALUES ('368', 'SAN PEDRO DE LLOC', '01', '119');
INSERT INTO `distrito` VALUES ('369', 'GUADALUPE', '02', '119');
INSERT INTO `distrito` VALUES ('370', 'JEQUETEPEQUE', '03', '119');
INSERT INTO `distrito` VALUES ('371', 'PACASMAYO', '04', '119');
INSERT INTO `distrito` VALUES ('372', 'SAN JOSE', '05', '119');
INSERT INTO `distrito` VALUES ('373', 'TAYABAMBA', '01', '120');
INSERT INTO `distrito` VALUES ('374', 'BULDIBUYO', '02', '120');
INSERT INTO `distrito` VALUES ('375', 'CHILLIA', '03', '120');
INSERT INTO `distrito` VALUES ('376', 'HUANCASPATA', '04', '120');
INSERT INTO `distrito` VALUES ('377', 'HUAYLILLAS', '05', '120');
INSERT INTO `distrito` VALUES ('378', 'HUAYO', '06', '120');
INSERT INTO `distrito` VALUES ('379', 'ONGON', '07', '120');
INSERT INTO `distrito` VALUES ('380', 'PARCOY', '08', '120');
INSERT INTO `distrito` VALUES ('381', 'PATAZ', '09', '120');
INSERT INTO `distrito` VALUES ('382', 'PIAS', '10', '120');
INSERT INTO `distrito` VALUES ('383', 'SANTIAGO DE CHALLAS', '11', '120');
INSERT INTO `distrito` VALUES ('384', 'TAURIJA', '12', '120');
INSERT INTO `distrito` VALUES ('385', 'URPAY', '13', '120');
INSERT INTO `distrito` VALUES ('386', 'HUAMACHUCO', '01', '121');
INSERT INTO `distrito` VALUES ('387', 'CHUGAY', '02', '121');
INSERT INTO `distrito` VALUES ('388', 'COCHORCO', '03', '121');
INSERT INTO `distrito` VALUES ('389', 'CURGOS', '04', '121');
INSERT INTO `distrito` VALUES ('390', 'MARCABAL', '05', '121');
INSERT INTO `distrito` VALUES ('391', 'SANAGORAN', '06', '121');
INSERT INTO `distrito` VALUES ('392', 'SARIN', '07', '121');
INSERT INTO `distrito` VALUES ('393', 'SARTIMBAMBA', '08', '121');
INSERT INTO `distrito` VALUES ('394', 'SANTIAGO DE CHUCO', '01', '122');
INSERT INTO `distrito` VALUES ('395', 'ANGASMARCA', '02', '122');
INSERT INTO `distrito` VALUES ('396', 'CACHICADAN', '03', '122');
INSERT INTO `distrito` VALUES ('397', 'MOLLEBAMBA', '04', '122');
INSERT INTO `distrito` VALUES ('398', 'MOLLEPATA', '05', '122');
INSERT INTO `distrito` VALUES ('399', 'QUIRUVILCA', '06', '122');
INSERT INTO `distrito` VALUES ('400', 'SANTA CRUZ DE CHUCA', '07', '122');
INSERT INTO `distrito` VALUES ('401', 'SITABAMBA', '08', '122');
INSERT INTO `distrito` VALUES ('402', 'CASCAS', '01', '123');
INSERT INTO `distrito` VALUES ('403', 'LUCMA', '02', '123');
INSERT INTO `distrito` VALUES ('404', 'MARMOT', '03', '123');
INSERT INTO `distrito` VALUES ('405', 'SAYAPULLO', '04', '123');
INSERT INTO `distrito` VALUES ('406', 'VIRU', '01', '124');
INSERT INTO `distrito` VALUES ('407', 'CHAO', '02', '124');
INSERT INTO `distrito` VALUES ('408', 'GUADALUPITO', '03', '124');
INSERT INTO `distrito` VALUES ('409', 'CHICLAYO', '01', '125');
INSERT INTO `distrito` VALUES ('410', 'CHONGOYAPE', '02', '125');
INSERT INTO `distrito` VALUES ('411', 'ETEN', '03', '125');
INSERT INTO `distrito` VALUES ('412', 'ETEN PUERTO', '04', '125');
INSERT INTO `distrito` VALUES ('413', 'JOSE LEONARDO ORTIZ', '05', '125');
INSERT INTO `distrito` VALUES ('414', 'LA VICTORIA', '06', '125');
INSERT INTO `distrito` VALUES ('415', 'LAGUNAS', '07', '125');
INSERT INTO `distrito` VALUES ('416', 'MONSEFU', '08', '125');
INSERT INTO `distrito` VALUES ('417', 'NUEVA ARICA', '09', '125');
INSERT INTO `distrito` VALUES ('418', 'OYOTUN', '10', '125');
INSERT INTO `distrito` VALUES ('419', 'PICSI', '11', '125');
INSERT INTO `distrito` VALUES ('420', 'PIMENTEL', '12', '125');
INSERT INTO `distrito` VALUES ('421', 'REQUE', '13', '125');
INSERT INTO `distrito` VALUES ('422', 'SANTA ROSA', '14', '125');
INSERT INTO `distrito` VALUES ('423', 'SA?A', '15', '125');
INSERT INTO `distrito` VALUES ('424', 'CAYALTI', '16', '125');
INSERT INTO `distrito` VALUES ('425', 'PATAPO', '17', '125');
INSERT INTO `distrito` VALUES ('426', 'POMALCA', '18', '125');
INSERT INTO `distrito` VALUES ('427', 'PUCALA', '19', '125');
INSERT INTO `distrito` VALUES ('428', 'TUMAN', '20', '125');
INSERT INTO `distrito` VALUES ('429', 'FERRE?AFE', '01', '126');
INSERT INTO `distrito` VALUES ('430', 'CA?ARIS', '02', '126');
INSERT INTO `distrito` VALUES ('431', 'INCAHUASI', '03', '126');
INSERT INTO `distrito` VALUES ('432', 'MANUEL ANTONIO MESONES MURO', '04', '126');
INSERT INTO `distrito` VALUES ('433', 'PITIPO', '05', '126');
INSERT INTO `distrito` VALUES ('434', 'PUEBLO NUEVO', '06', '126');
INSERT INTO `distrito` VALUES ('435', 'LAMBAYEQUE', '01', '127');
INSERT INTO `distrito` VALUES ('436', 'CHOCHOPE', '02', '127');
INSERT INTO `distrito` VALUES ('437', 'ILLIMO', '03', '127');
INSERT INTO `distrito` VALUES ('438', 'JAYANCA', '04', '127');
INSERT INTO `distrito` VALUES ('439', 'MOCHUMI', '05', '127');
INSERT INTO `distrito` VALUES ('440', 'MORROPE', '06', '127');
INSERT INTO `distrito` VALUES ('441', 'MOTUPE', '07', '127');
INSERT INTO `distrito` VALUES ('442', 'OLMOS', '08', '127');
INSERT INTO `distrito` VALUES ('443', 'PACORA', '09', '127');
INSERT INTO `distrito` VALUES ('444', 'SALAS', '10', '127');
INSERT INTO `distrito` VALUES ('445', 'SAN JOSE', '11', '127');
INSERT INTO `distrito` VALUES ('446', 'TUCUME', '12', '127');
INSERT INTO `distrito` VALUES ('447', 'LIMA', '01', '128');
INSERT INTO `distrito` VALUES ('448', 'ANCON', '02', '128');
INSERT INTO `distrito` VALUES ('449', 'ATE', '03', '128');
INSERT INTO `distrito` VALUES ('450', 'BARRANCO', '04', '128');
INSERT INTO `distrito` VALUES ('451', 'BRE?A', '05', '128');
INSERT INTO `distrito` VALUES ('452', 'CARABAYLLO', '06', '128');
INSERT INTO `distrito` VALUES ('453', 'CHACLACAYO', '07', '128');
INSERT INTO `distrito` VALUES ('454', 'CHORRILLOS', '08', '128');
INSERT INTO `distrito` VALUES ('455', 'CIENEGUILLA', '09', '128');
INSERT INTO `distrito` VALUES ('456', 'COMAS', '10', '128');
INSERT INTO `distrito` VALUES ('457', 'EL AGUSTINO', '11', '128');
INSERT INTO `distrito` VALUES ('458', 'INDEPENDENCIA', '12', '128');
INSERT INTO `distrito` VALUES ('459', 'JESUS MARIA', '13', '128');
INSERT INTO `distrito` VALUES ('460', 'LA MOLINA', '14', '128');
INSERT INTO `distrito` VALUES ('461', 'LA VICTORIA', '15', '128');
INSERT INTO `distrito` VALUES ('462', 'LINCE', '16', '128');
INSERT INTO `distrito` VALUES ('463', 'LOS OLIVOS', '17', '128');
INSERT INTO `distrito` VALUES ('464', 'LURIGANCHO', '18', '128');
INSERT INTO `distrito` VALUES ('465', 'LURIN', '19', '128');
INSERT INTO `distrito` VALUES ('466', 'MAGDALENA DEL MAR', '20', '128');
INSERT INTO `distrito` VALUES ('467', 'MAGDALENA VIEJA', '21', '128');
INSERT INTO `distrito` VALUES ('468', 'MIRAFLORES', '22', '128');
INSERT INTO `distrito` VALUES ('469', 'PACHACAMAC', '23', '128');
INSERT INTO `distrito` VALUES ('470', 'PUCUSANA', '24', '128');
INSERT INTO `distrito` VALUES ('471', 'PUENTE PIEDRA', '25', '128');
INSERT INTO `distrito` VALUES ('472', 'PUNTA HERMOSA', '26', '128');
INSERT INTO `distrito` VALUES ('473', 'PUNTA NEGRA', '27', '128');
INSERT INTO `distrito` VALUES ('474', 'RIMAC', '28', '128');
INSERT INTO `distrito` VALUES ('475', 'SAN BARTOLO', '29', '128');
INSERT INTO `distrito` VALUES ('476', 'SAN BORJA', '30', '128');
INSERT INTO `distrito` VALUES ('477', 'SAN ISIDRO', '31', '128');
INSERT INTO `distrito` VALUES ('478', 'SAN JUAN DE LURIGANCHO', '32', '128');
INSERT INTO `distrito` VALUES ('479', 'SAN JUAN DE MIRAFLORES', '33', '128');
INSERT INTO `distrito` VALUES ('480', 'SAN LUIS', '34', '128');
INSERT INTO `distrito` VALUES ('481', 'SAN MARTIN DE PORRES', '35', '128');
INSERT INTO `distrito` VALUES ('482', 'SAN MIGUEL', '36', '128');
INSERT INTO `distrito` VALUES ('483', 'SANTA ANITA', '37', '128');
INSERT INTO `distrito` VALUES ('484', 'SANTA MARIA DEL MAR', '38', '128');
INSERT INTO `distrito` VALUES ('485', 'SANTA ROSA', '39', '128');
INSERT INTO `distrito` VALUES ('486', 'SANTIAGO DE SURCO', '40', '128');
INSERT INTO `distrito` VALUES ('487', 'SURQUILLO', '41', '128');
INSERT INTO `distrito` VALUES ('488', 'VILLA EL SALVADOR', '42', '128');
INSERT INTO `distrito` VALUES ('489', 'VILLA MARIA DEL TRIUNFO', '43', '128');
INSERT INTO `distrito` VALUES ('490', 'BARRANCA', '01', '129');
INSERT INTO `distrito` VALUES ('491', 'PARAMONGA', '02', '129');
INSERT INTO `distrito` VALUES ('492', 'PATIVILCA', '03', '129');
INSERT INTO `distrito` VALUES ('493', 'SUPE', '04', '129');
INSERT INTO `distrito` VALUES ('494', 'SUPE PUERTO', '05', '129');
INSERT INTO `distrito` VALUES ('495', 'CAJATAMBO', '01', '130');
INSERT INTO `distrito` VALUES ('496', 'COPA', '02', '130');
INSERT INTO `distrito` VALUES ('497', 'GORGOR', '03', '130');
INSERT INTO `distrito` VALUES ('498', 'HUANCAPON', '04', '130');
INSERT INTO `distrito` VALUES ('499', 'MANAS', '05', '130');
INSERT INTO `distrito` VALUES ('500', 'CANTA', '01', '131');
INSERT INTO `distrito` VALUES ('501', 'ARAHUAY', '02', '131');
INSERT INTO `distrito` VALUES ('502', 'HUAMANTANGA', '03', '131');
INSERT INTO `distrito` VALUES ('503', 'HUAROS', '04', '131');
INSERT INTO `distrito` VALUES ('504', 'LACHAQUI', '05', '131');
INSERT INTO `distrito` VALUES ('505', 'SAN BUENAVENTURA', '06', '131');
INSERT INTO `distrito` VALUES ('506', 'SANTA ROSA DE QUIVES', '07', '131');
INSERT INTO `distrito` VALUES ('507', 'SAN VICENTE DE CA?ETE', '01', '132');
INSERT INTO `distrito` VALUES ('508', 'ASIA', '02', '132');
INSERT INTO `distrito` VALUES ('509', 'CALANGO', '03', '132');
INSERT INTO `distrito` VALUES ('510', 'CERRO AZUL', '04', '132');
INSERT INTO `distrito` VALUES ('511', 'CHILCA', '05', '132');
INSERT INTO `distrito` VALUES ('512', 'COAYLLO', '06', '132');
INSERT INTO `distrito` VALUES ('513', 'IMPERIAL', '07', '132');
INSERT INTO `distrito` VALUES ('514', 'LUNAHUANA', '08', '132');
INSERT INTO `distrito` VALUES ('515', 'MALA', '09', '132');
INSERT INTO `distrito` VALUES ('516', 'NUEVO IMPERIAL', '10', '132');
INSERT INTO `distrito` VALUES ('517', 'PACARAN', '11', '132');
INSERT INTO `distrito` VALUES ('518', 'QUILMANA', '12', '132');
INSERT INTO `distrito` VALUES ('519', 'SAN ANTONIO', '13', '132');
INSERT INTO `distrito` VALUES ('520', 'SAN LUIS', '14', '132');
INSERT INTO `distrito` VALUES ('521', 'SANTA CRUZ DE FLORES', '15', '132');
INSERT INTO `distrito` VALUES ('522', 'ZU?IGA', '16', '132');
INSERT INTO `distrito` VALUES ('523', 'HUARAL', '01', '133');
INSERT INTO `distrito` VALUES ('524', 'ATAVILLOS ALTO', '02', '133');
INSERT INTO `distrito` VALUES ('525', 'ATAVILLOS BAJO', '03', '133');
INSERT INTO `distrito` VALUES ('526', 'AUCALLAMA', '04', '133');
INSERT INTO `distrito` VALUES ('527', 'CHANCAY', '05', '133');
INSERT INTO `distrito` VALUES ('528', 'IHUARI', '06', '133');
INSERT INTO `distrito` VALUES ('529', 'LAMPIAN', '07', '133');
INSERT INTO `distrito` VALUES ('530', 'PACARAOS', '08', '133');
INSERT INTO `distrito` VALUES ('531', 'SAN MIGUEL DE ACOS', '09', '133');
INSERT INTO `distrito` VALUES ('532', 'SANTA CRUZ DE ANDAMARCA', '10', '133');
INSERT INTO `distrito` VALUES ('533', 'SUMBILCA', '11', '133');
INSERT INTO `distrito` VALUES ('534', 'VEINTISIETE DE NOVIEMBRE', '12', '133');
INSERT INTO `distrito` VALUES ('535', 'MATUCANA', '01', '134');
INSERT INTO `distrito` VALUES ('536', 'ANTIOQUIA', '02', '134');
INSERT INTO `distrito` VALUES ('537', 'CALLAHUANCA', '03', '134');
INSERT INTO `distrito` VALUES ('538', 'CARAMPOMA', '04', '134');
INSERT INTO `distrito` VALUES ('539', 'CHICLA', '05', '134');
INSERT INTO `distrito` VALUES ('540', 'CUENCA', '06', '134');
INSERT INTO `distrito` VALUES ('541', 'HUACHUPAMPA', '07', '134');
INSERT INTO `distrito` VALUES ('542', 'HUANZA', '08', '134');
INSERT INTO `distrito` VALUES ('543', 'HUAROCHIRI', '09', '134');
INSERT INTO `distrito` VALUES ('544', 'LAHUAYTAMBO', '10', '134');
INSERT INTO `distrito` VALUES ('545', 'LANGA', '11', '134');
INSERT INTO `distrito` VALUES ('546', 'LARAOS', '12', '134');
INSERT INTO `distrito` VALUES ('547', 'MARIATANA', '13', '134');
INSERT INTO `distrito` VALUES ('548', 'RICARDO PALMA', '14', '134');
INSERT INTO `distrito` VALUES ('549', 'SAN ANDRES DE TUPICOCHA', '15', '134');
INSERT INTO `distrito` VALUES ('550', 'SAN ANTONIO', '16', '134');
INSERT INTO `distrito` VALUES ('551', 'SAN BARTOLOME', '17', '134');
INSERT INTO `distrito` VALUES ('552', 'SAN DAMIAN', '18', '134');
INSERT INTO `distrito` VALUES ('553', 'SAN JUAN DE IRIS', '19', '134');
INSERT INTO `distrito` VALUES ('554', 'SAN JUAN DE TANTARANCHE', '20', '134');
INSERT INTO `distrito` VALUES ('555', 'SAN LORENZO DE QUINTI', '21', '134');
INSERT INTO `distrito` VALUES ('556', 'SAN MATEO', '22', '134');
INSERT INTO `distrito` VALUES ('557', 'SAN MATEO DE OTAO', '23', '134');
INSERT INTO `distrito` VALUES ('558', 'SAN PEDRO DE CASTA', '24', '134');
INSERT INTO `distrito` VALUES ('559', 'SAN PEDRO DE HUANCAYRE', '25', '134');
INSERT INTO `distrito` VALUES ('560', 'SANGALLAYA', '26', '134');
INSERT INTO `distrito` VALUES ('561', 'SANTA CRUZ DE COCACHACRA', '27', '134');
INSERT INTO `distrito` VALUES ('562', 'SANTA EULALIA', '28', '134');
INSERT INTO `distrito` VALUES ('563', 'SANTIAGO DE ANCHUCAYA', '29', '134');
INSERT INTO `distrito` VALUES ('564', 'SANTIAGO DE TUNA', '30', '134');
INSERT INTO `distrito` VALUES ('565', 'SANTO DOMINGO DE LOS OLLEROS', '31', '134');
INSERT INTO `distrito` VALUES ('566', 'SURCO', '32', '134');
INSERT INTO `distrito` VALUES ('567', 'HUACHO', '01', '135');
INSERT INTO `distrito` VALUES ('568', 'AMBAR', '02', '135');
INSERT INTO `distrito` VALUES ('569', 'CALETA DE CARQUIN', '03', '135');
INSERT INTO `distrito` VALUES ('570', 'CHECRAS', '04', '135');
INSERT INTO `distrito` VALUES ('571', 'HUALMAY', '05', '135');
INSERT INTO `distrito` VALUES ('572', 'HUAURA', '06', '135');
INSERT INTO `distrito` VALUES ('573', 'LEONCIO PRADO', '07', '135');
INSERT INTO `distrito` VALUES ('574', 'PACCHO', '08', '135');
INSERT INTO `distrito` VALUES ('575', 'SANTA LEONOR', '09', '135');
INSERT INTO `distrito` VALUES ('576', 'SANTA MARIA', '10', '135');
INSERT INTO `distrito` VALUES ('577', 'SAYAN', '11', '135');
INSERT INTO `distrito` VALUES ('578', 'VEGUETA', '12', '135');
INSERT INTO `distrito` VALUES ('579', 'OYON', '01', '136');
INSERT INTO `distrito` VALUES ('580', 'ANDAJES', '02', '136');
INSERT INTO `distrito` VALUES ('581', 'CAUJUL', '03', '136');
INSERT INTO `distrito` VALUES ('582', 'COCHAMARCA', '04', '136');
INSERT INTO `distrito` VALUES ('583', 'NAVAN', '05', '136');
INSERT INTO `distrito` VALUES ('584', 'PACHANGARA', '06', '136');
INSERT INTO `distrito` VALUES ('585', 'YAUYOS', '01', '137');
INSERT INTO `distrito` VALUES ('586', 'ALIS', '02', '137');
INSERT INTO `distrito` VALUES ('587', 'AYAUCA', '03', '137');
INSERT INTO `distrito` VALUES ('588', 'AYAVIRI', '04', '137');
INSERT INTO `distrito` VALUES ('589', 'AZANGARO', '05', '137');
INSERT INTO `distrito` VALUES ('590', 'CACRA', '06', '137');
INSERT INTO `distrito` VALUES ('591', 'CARANIA', '07', '137');
INSERT INTO `distrito` VALUES ('592', 'CATAHUASI', '08', '137');
INSERT INTO `distrito` VALUES ('593', 'CHOCOS', '09', '137');
INSERT INTO `distrito` VALUES ('594', 'COCHAS', '10', '137');
INSERT INTO `distrito` VALUES ('595', 'COLONIA', '11', '137');
INSERT INTO `distrito` VALUES ('596', 'HONGOS', '12', '137');
INSERT INTO `distrito` VALUES ('597', 'HUAMPARA', '13', '137');
INSERT INTO `distrito` VALUES ('598', 'HUANCAYA', '14', '137');
INSERT INTO `distrito` VALUES ('599', 'HUANGASCAR', '15', '137');
INSERT INTO `distrito` VALUES ('600', 'HUANTAN', '16', '137');
INSERT INTO `distrito` VALUES ('601', 'HUA?EC', '17', '137');
INSERT INTO `distrito` VALUES ('602', 'LARAOS', '18', '137');
INSERT INTO `distrito` VALUES ('603', 'LINCHA', '19', '137');
INSERT INTO `distrito` VALUES ('604', 'MADEAN', '20', '137');
INSERT INTO `distrito` VALUES ('605', 'MIRAFLORES', '21', '137');
INSERT INTO `distrito` VALUES ('606', 'OMAS', '22', '137');
INSERT INTO `distrito` VALUES ('607', 'PUTINZA', '23', '137');
INSERT INTO `distrito` VALUES ('608', 'QUINCHES', '24', '137');
INSERT INTO `distrito` VALUES ('609', 'QUINOCAY', '25', '137');
INSERT INTO `distrito` VALUES ('610', 'SAN JOAQUIN', '26', '137');
INSERT INTO `distrito` VALUES ('611', 'SAN PEDRO DE PILAS', '27', '137');
INSERT INTO `distrito` VALUES ('612', 'TANTA', '28', '137');
INSERT INTO `distrito` VALUES ('613', 'TAURIPAMPA', '29', '137');
INSERT INTO `distrito` VALUES ('614', 'TOMAS', '30', '137');
INSERT INTO `distrito` VALUES ('615', 'TUPE', '31', '137');
INSERT INTO `distrito` VALUES ('616', 'VI?AC', '32', '137');
INSERT INTO `distrito` VALUES ('617', 'VITIS', '33', '137');
INSERT INTO `distrito` VALUES ('618', 'IQUITOS', '01', '138');
INSERT INTO `distrito` VALUES ('619', 'ALTO NANAY', '02', '138');
INSERT INTO `distrito` VALUES ('620', 'FERNANDO LORES', '03', '138');
INSERT INTO `distrito` VALUES ('621', 'INDIANA', '04', '138');
INSERT INTO `distrito` VALUES ('622', 'LAS AMAZONAS', '05', '138');
INSERT INTO `distrito` VALUES ('623', 'MAZAN', '06', '138');
INSERT INTO `distrito` VALUES ('624', 'NAPO', '07', '138');
INSERT INTO `distrito` VALUES ('625', 'PUNCHANA', '08', '138');
INSERT INTO `distrito` VALUES ('626', 'PUTUMAYO', '09', '138');
INSERT INTO `distrito` VALUES ('627', 'TORRES CAUSANA', '10', '138');
INSERT INTO `distrito` VALUES ('628', 'BELEN', '12', '138');
INSERT INTO `distrito` VALUES ('629', 'SAN JUAN BAUTISTA', '13', '138');
INSERT INTO `distrito` VALUES ('630', 'YURIMAGUAS', '01', '139');
INSERT INTO `distrito` VALUES ('631', 'BALSAPUERTO', '02', '139');
INSERT INTO `distrito` VALUES ('632', 'BARRANCA', '03', '139');
INSERT INTO `distrito` VALUES ('633', 'CAHUAPANAS', '04', '139');
INSERT INTO `distrito` VALUES ('634', 'JEBEROS', '05', '139');
INSERT INTO `distrito` VALUES ('635', 'LAGUNAS', '06', '139');
INSERT INTO `distrito` VALUES ('636', 'MANSERICHE', '07', '139');
INSERT INTO `distrito` VALUES ('637', 'MORONA', '08', '139');
INSERT INTO `distrito` VALUES ('638', 'PASTAZA', '09', '139');
INSERT INTO `distrito` VALUES ('639', 'SANTA CRUZ', '10', '139');
INSERT INTO `distrito` VALUES ('640', 'TENIENTE CESAR LOPEZ ROJAS', '11', '139');
INSERT INTO `distrito` VALUES ('641', 'NAUTA', '01', '140');
INSERT INTO `distrito` VALUES ('642', 'PARINARI', '02', '140');
INSERT INTO `distrito` VALUES ('643', 'TIGRE', '03', '140');
INSERT INTO `distrito` VALUES ('644', 'TROMPETEROS', '04', '140');
INSERT INTO `distrito` VALUES ('645', 'URARINAS', '05', '140');
INSERT INTO `distrito` VALUES ('646', 'RAMON CASTILLA', '01', '141');
INSERT INTO `distrito` VALUES ('647', 'PEBAS', '02', '141');
INSERT INTO `distrito` VALUES ('648', 'YAVARI', '03', '141');
INSERT INTO `distrito` VALUES ('649', 'SAN PABLO', '04', '141');
INSERT INTO `distrito` VALUES ('650', 'REQUENA', '01', '142');
INSERT INTO `distrito` VALUES ('651', 'ALTO TAPICHE', '02', '142');
INSERT INTO `distrito` VALUES ('652', 'CAPELO', '03', '142');
INSERT INTO `distrito` VALUES ('653', 'EMILIO SAN MARTIN', '04', '142');
INSERT INTO `distrito` VALUES ('654', 'MAQUIA', '05', '142');
INSERT INTO `distrito` VALUES ('655', 'PUINAHUA', '06', '142');
INSERT INTO `distrito` VALUES ('656', 'SAQUENA', '07', '142');
INSERT INTO `distrito` VALUES ('657', 'SOPLIN', '08', '142');
INSERT INTO `distrito` VALUES ('658', 'TAPICHE', '09', '142');
INSERT INTO `distrito` VALUES ('659', 'JENARO HERRERA', '10', '142');
INSERT INTO `distrito` VALUES ('660', 'YAQUERANA', '11', '142');
INSERT INTO `distrito` VALUES ('661', 'CONTAMANA', '01', '143');
INSERT INTO `distrito` VALUES ('662', 'INAHUAYA', '02', '143');
INSERT INTO `distrito` VALUES ('663', 'PADRE MARQUEZ', '03', '143');
INSERT INTO `distrito` VALUES ('664', 'PAMPA HERMOSA', '04', '143');
INSERT INTO `distrito` VALUES ('665', 'SARAYACU', '05', '143');
INSERT INTO `distrito` VALUES ('666', 'VARGAS GUERRA', '06', '143');
INSERT INTO `distrito` VALUES ('667', 'TAMBOPATA', '01', '144');
INSERT INTO `distrito` VALUES ('668', 'INAMBARI', '02', '144');
INSERT INTO `distrito` VALUES ('669', 'LAS PIEDRAS', '03', '144');
INSERT INTO `distrito` VALUES ('670', 'LABERINTO', '04', '144');
INSERT INTO `distrito` VALUES ('671', 'MANU', '01', '145');
INSERT INTO `distrito` VALUES ('672', 'FITZCARRALD', '02', '145');
INSERT INTO `distrito` VALUES ('673', 'MADRE DE DIOS', '03', '145');
INSERT INTO `distrito` VALUES ('674', 'HUEPETUHE', '04', '145');
INSERT INTO `distrito` VALUES ('675', 'I?APARI', '01', '146');
INSERT INTO `distrito` VALUES ('676', 'IBERIA', '02', '146');
INSERT INTO `distrito` VALUES ('677', 'TAHUAMANU', '03', '146');
INSERT INTO `distrito` VALUES ('678', 'MOQUEGUA', '01', '147');
INSERT INTO `distrito` VALUES ('679', 'CARUMAS', '02', '147');
INSERT INTO `distrito` VALUES ('680', 'CUCHUMBAYA', '03', '147');
INSERT INTO `distrito` VALUES ('681', 'SAMEGUA', '04', '147');
INSERT INTO `distrito` VALUES ('682', 'SAN CRISTOBAL', '05', '147');
INSERT INTO `distrito` VALUES ('683', 'TORATA', '06', '147');
INSERT INTO `distrito` VALUES ('684', 'OMATE', '01', '148');
INSERT INTO `distrito` VALUES ('685', 'CHOJATA', '02', '148');
INSERT INTO `distrito` VALUES ('686', 'COALAQUE', '03', '148');
INSERT INTO `distrito` VALUES ('687', 'ICHU?A', '04', '148');
INSERT INTO `distrito` VALUES ('688', 'LA CAPILLA', '05', '148');
INSERT INTO `distrito` VALUES ('689', 'LLOQUE', '06', '148');
INSERT INTO `distrito` VALUES ('690', 'MATALAQUE', '07', '148');
INSERT INTO `distrito` VALUES ('691', 'PUQUINA', '08', '148');
INSERT INTO `distrito` VALUES ('692', 'QUINISTAQUILLAS', '09', '148');
INSERT INTO `distrito` VALUES ('693', 'UBINAS', '10', '148');
INSERT INTO `distrito` VALUES ('694', 'YUNGA', '11', '148');
INSERT INTO `distrito` VALUES ('695', 'ILO', '01', '149');
INSERT INTO `distrito` VALUES ('696', 'EL ALGARROBAL', '02', '149');
INSERT INTO `distrito` VALUES ('697', 'PACOCHA', '03', '149');
INSERT INTO `distrito` VALUES ('698', 'CHAUPIMARCA', '01', '150');
INSERT INTO `distrito` VALUES ('699', 'HUACHON', '02', '150');
INSERT INTO `distrito` VALUES ('700', 'HUARIACA', '03', '150');
INSERT INTO `distrito` VALUES ('701', 'HUAYLLAY', '04', '150');
INSERT INTO `distrito` VALUES ('702', 'NINACACA', '05', '150');
INSERT INTO `distrito` VALUES ('703', 'PALLANCHACRA', '06', '150');
INSERT INTO `distrito` VALUES ('704', 'PAUCARTAMBO', '07', '150');
INSERT INTO `distrito` VALUES ('705', 'SAN FCO.DE ASIS DE YARUSYACAN', '08', '150');
INSERT INTO `distrito` VALUES ('706', 'SIMON BOLIVAR', '09', '150');
INSERT INTO `distrito` VALUES ('707', 'TICLACAYAN', '10', '150');
INSERT INTO `distrito` VALUES ('708', 'TINYAHUARCO', '11', '150');
INSERT INTO `distrito` VALUES ('709', 'VICCO', '12', '150');
INSERT INTO `distrito` VALUES ('710', 'YANACANCHA', '13', '150');
INSERT INTO `distrito` VALUES ('711', 'YANAHUANCA', '01', '151');
INSERT INTO `distrito` VALUES ('712', 'CHACAYAN', '02', '151');
INSERT INTO `distrito` VALUES ('713', 'GOYLLARISQUIZGA', '03', '151');
INSERT INTO `distrito` VALUES ('714', 'PAUCAR', '04', '151');
INSERT INTO `distrito` VALUES ('715', 'SAN PEDRO DE PILLAO', '05', '151');
INSERT INTO `distrito` VALUES ('716', 'SANTA ANA DE TUSI', '06', '151');
INSERT INTO `distrito` VALUES ('717', 'TAPUC', '07', '151');
INSERT INTO `distrito` VALUES ('718', 'VILCABAMBA', '08', '151');
INSERT INTO `distrito` VALUES ('719', 'OXAPAMPA', '01', '152');
INSERT INTO `distrito` VALUES ('720', 'CHONTABAMBA', '02', '152');
INSERT INTO `distrito` VALUES ('721', 'HUANCABAMBA', '03', '152');
INSERT INTO `distrito` VALUES ('722', 'PALCAZU', '04', '152');
INSERT INTO `distrito` VALUES ('723', 'POZUZO', '05', '152');
INSERT INTO `distrito` VALUES ('724', 'PUERTO BERMUDEZ', '06', '152');
INSERT INTO `distrito` VALUES ('725', 'VILLA RICA', '07', '152');
INSERT INTO `distrito` VALUES ('726', 'HUARAZ', '01', '8');
INSERT INTO `distrito` VALUES ('727', 'COCHABAMBA', '02', '8');
INSERT INTO `distrito` VALUES ('728', 'COLCABAMBA', '03', '8');
INSERT INTO `distrito` VALUES ('729', 'HUANCHAY', '04', '8');
INSERT INTO `distrito` VALUES ('730', 'INDEPENDENCIA', '05', '8');
INSERT INTO `distrito` VALUES ('731', 'JANGAS', '06', '8');
INSERT INTO `distrito` VALUES ('732', 'LA LIBERTAD', '07', '8');
INSERT INTO `distrito` VALUES ('733', 'OLLEROS', '08', '8');
INSERT INTO `distrito` VALUES ('734', 'PAMPAS', '09', '8');
INSERT INTO `distrito` VALUES ('735', 'PARIACOTO', '10', '8');
INSERT INTO `distrito` VALUES ('736', 'PIRA', '11', '8');
INSERT INTO `distrito` VALUES ('737', 'TARICA', '12', '8');
INSERT INTO `distrito` VALUES ('738', 'AIJA', '01', '9');
INSERT INTO `distrito` VALUES ('739', 'CORIS', '02', '9');
INSERT INTO `distrito` VALUES ('740', 'HUACLLAN', '03', '9');
INSERT INTO `distrito` VALUES ('741', 'LA MERCED', '04', '9');
INSERT INTO `distrito` VALUES ('742', 'SUCCHA', '05', '9');
INSERT INTO `distrito` VALUES ('743', 'LLAMELLIN', '01', '10');
INSERT INTO `distrito` VALUES ('744', 'ACZO', '02', '10');
INSERT INTO `distrito` VALUES ('745', 'CHACCHO', '03', '10');
INSERT INTO `distrito` VALUES ('746', 'CHINGAS', '04', '10');
INSERT INTO `distrito` VALUES ('747', 'MIRGAS', '05', '10');
INSERT INTO `distrito` VALUES ('748', 'SAN JUAN DE RONTOY', '06', '10');
INSERT INTO `distrito` VALUES ('749', 'CHACAS', '01', '11');
INSERT INTO `distrito` VALUES ('750', 'ACOCHACA', '02', '11');
INSERT INTO `distrito` VALUES ('751', 'CHIQUIAN', '01', '12');
INSERT INTO `distrito` VALUES ('752', 'ABELARDO PARDO LEZAMETA', '02', '12');
INSERT INTO `distrito` VALUES ('753', 'ANTONIO RAYMONDI', '03', '12');
INSERT INTO `distrito` VALUES ('754', 'AQUIA', '04', '12');
INSERT INTO `distrito` VALUES ('755', 'CAJACAY', '05', '12');
INSERT INTO `distrito` VALUES ('756', 'CANIS', '06', '12');
INSERT INTO `distrito` VALUES ('757', 'COLQUIOC', '07', '12');
INSERT INTO `distrito` VALUES ('758', 'HUALLANCA', '08', '12');
INSERT INTO `distrito` VALUES ('759', 'HUASTA', '09', '12');
INSERT INTO `distrito` VALUES ('760', 'HUAYLLACAYAN', '10', '12');
INSERT INTO `distrito` VALUES ('761', 'LA PRIMAVERA', '11', '12');
INSERT INTO `distrito` VALUES ('762', 'MANGAS', '12', '12');
INSERT INTO `distrito` VALUES ('763', 'PACLLON', '13', '12');
INSERT INTO `distrito` VALUES ('764', 'SAN MIGUEL DE CORPANQUI', '14', '12');
INSERT INTO `distrito` VALUES ('765', 'TICLLOS', '15', '12');
INSERT INTO `distrito` VALUES ('766', 'CARHUAZ', '01', '13');
INSERT INTO `distrito` VALUES ('767', 'ACOPAMPA', '02', '13');
INSERT INTO `distrito` VALUES ('768', 'AMASHCA', '03', '13');
INSERT INTO `distrito` VALUES ('769', 'ANTA', '04', '13');
INSERT INTO `distrito` VALUES ('770', 'ATAQUERO', '05', '13');
INSERT INTO `distrito` VALUES ('771', 'MARCARA', '06', '13');
INSERT INTO `distrito` VALUES ('772', 'PARIAHUANCA', '07', '13');
INSERT INTO `distrito` VALUES ('773', 'SAN MIGUEL DE ACO', '08', '13');
INSERT INTO `distrito` VALUES ('774', 'SHILLA', '09', '13');
INSERT INTO `distrito` VALUES ('775', 'TINCO', '10', '13');
INSERT INTO `distrito` VALUES ('776', 'YUNGAR', '11', '13');
INSERT INTO `distrito` VALUES ('777', 'SAN LUIS', '01', '14');
INSERT INTO `distrito` VALUES ('778', 'SAN NICOLAS', '02', '14');
INSERT INTO `distrito` VALUES ('779', 'YAUYA', '03', '14');
INSERT INTO `distrito` VALUES ('780', 'CASMA', '01', '15');
INSERT INTO `distrito` VALUES ('781', 'BUENA VISTA ALTA', '02', '15');
INSERT INTO `distrito` VALUES ('782', 'COMANDANTE NOEL', '03', '15');
INSERT INTO `distrito` VALUES ('783', 'YAUTAN', '04', '15');
INSERT INTO `distrito` VALUES ('784', 'CORONGO', '01', '16');
INSERT INTO `distrito` VALUES ('785', 'ACO', '02', '16');
INSERT INTO `distrito` VALUES ('786', 'BAMBAS', '03', '16');
INSERT INTO `distrito` VALUES ('787', 'CUSCA', '04', '16');
INSERT INTO `distrito` VALUES ('788', 'LA PAMPA', '05', '16');
INSERT INTO `distrito` VALUES ('789', 'YANAC', '06', '16');
INSERT INTO `distrito` VALUES ('790', 'YUPAN', '07', '16');
INSERT INTO `distrito` VALUES ('791', 'HUARI', '01', '17');
INSERT INTO `distrito` VALUES ('792', 'ANRA', '02', '17');
INSERT INTO `distrito` VALUES ('793', 'CAJAY', '03', '17');
INSERT INTO `distrito` VALUES ('794', 'CHAVIN DE HUANTAR', '04', '17');
INSERT INTO `distrito` VALUES ('795', 'HUACACHI', '05', '17');
INSERT INTO `distrito` VALUES ('796', 'HUACCHIS', '06', '17');
INSERT INTO `distrito` VALUES ('797', 'HUACHIS', '07', '17');
INSERT INTO `distrito` VALUES ('798', 'HUANTAR', '08', '17');
INSERT INTO `distrito` VALUES ('799', 'MASIN', '09', '17');
INSERT INTO `distrito` VALUES ('800', 'PAUCAS', '10', '17');
INSERT INTO `distrito` VALUES ('801', 'PONTO', '11', '17');
INSERT INTO `distrito` VALUES ('802', 'RAHUAPAMPA', '12', '17');
INSERT INTO `distrito` VALUES ('803', 'RAPAYAN', '13', '17');
INSERT INTO `distrito` VALUES ('804', 'SAN MARCOS', '14', '17');
INSERT INTO `distrito` VALUES ('805', 'SAN PEDRO DE CHANA', '15', '17');
INSERT INTO `distrito` VALUES ('806', 'UCO', '16', '17');
INSERT INTO `distrito` VALUES ('807', 'HUARMEY', '01', '18');
INSERT INTO `distrito` VALUES ('808', 'COCHAPETI', '02', '18');
INSERT INTO `distrito` VALUES ('809', 'CULEBRAS', '03', '18');
INSERT INTO `distrito` VALUES ('810', 'HUAYAN', '04', '18');
INSERT INTO `distrito` VALUES ('811', 'MALVAS', '05', '18');
INSERT INTO `distrito` VALUES ('812', 'CARAZ', '01', '19');
INSERT INTO `distrito` VALUES ('813', 'HUALLANCA', '02', '19');
INSERT INTO `distrito` VALUES ('814', 'HUATA', '03', '19');
INSERT INTO `distrito` VALUES ('815', 'HUAYLAS', '04', '19');
INSERT INTO `distrito` VALUES ('816', 'MATO', '05', '19');
INSERT INTO `distrito` VALUES ('817', 'PAMPAROMAS', '06', '19');
INSERT INTO `distrito` VALUES ('818', 'PUEBLO LIBRE', '07', '19');
INSERT INTO `distrito` VALUES ('819', 'SANTA CRUZ', '08', '19');
INSERT INTO `distrito` VALUES ('820', 'SANTO TORIBIO', '09', '19');
INSERT INTO `distrito` VALUES ('821', 'YURACMARCA', '10', '19');
INSERT INTO `distrito` VALUES ('822', 'PISCOBAMBA', '01', '20');
INSERT INTO `distrito` VALUES ('823', 'CASCA', '02', '20');
INSERT INTO `distrito` VALUES ('824', 'ELEAZAR GUZMAN BARRON', '03', '20');
INSERT INTO `distrito` VALUES ('825', 'FIDEL OLIVAS ESCUDERO', '04', '20');
INSERT INTO `distrito` VALUES ('826', 'LLAMA', '05', '20');
INSERT INTO `distrito` VALUES ('827', 'LLUMPA', '06', '20');
INSERT INTO `distrito` VALUES ('828', 'LUCMA', '07', '20');
INSERT INTO `distrito` VALUES ('829', 'MUSGA', '08', '20');
INSERT INTO `distrito` VALUES ('830', 'OCROS', '01', '21');
INSERT INTO `distrito` VALUES ('831', 'ACAS', '02', '21');
INSERT INTO `distrito` VALUES ('832', 'CAJAMARQUILLA', '03', '21');
INSERT INTO `distrito` VALUES ('833', 'CARHUAPAMPA', '04', '21');
INSERT INTO `distrito` VALUES ('834', 'COCHAS', '05', '21');
INSERT INTO `distrito` VALUES ('835', 'CONGAS', '06', '21');
INSERT INTO `distrito` VALUES ('836', 'LLIPA', '07', '21');
INSERT INTO `distrito` VALUES ('837', 'SAN CRISTOBAL DE RAJAN', '08', '21');
INSERT INTO `distrito` VALUES ('838', 'SAN PEDRO', '09', '21');
INSERT INTO `distrito` VALUES ('839', 'SANTIAGO DE CHILCAS', '10', '21');
INSERT INTO `distrito` VALUES ('840', 'CABANA', '01', '22');
INSERT INTO `distrito` VALUES ('841', 'BOLOGNESI', '02', '22');
INSERT INTO `distrito` VALUES ('842', 'CONCHUCOS', '03', '22');
INSERT INTO `distrito` VALUES ('843', 'HUACASCHUQUE', '04', '22');
INSERT INTO `distrito` VALUES ('844', 'HUANDOVAL', '05', '22');
INSERT INTO `distrito` VALUES ('845', 'LACABAMBA', '06', '22');
INSERT INTO `distrito` VALUES ('846', 'LLAPO', '07', '22');
INSERT INTO `distrito` VALUES ('847', 'PALLASCA', '08', '22');
INSERT INTO `distrito` VALUES ('848', 'PAMPAS', '09', '22');
INSERT INTO `distrito` VALUES ('849', 'SANTA ROSA', '10', '22');
INSERT INTO `distrito` VALUES ('850', 'TAUCA', '11', '22');
INSERT INTO `distrito` VALUES ('851', 'POMABAMBA', '01', '23');
INSERT INTO `distrito` VALUES ('852', 'HUAYLLAN', '02', '23');
INSERT INTO `distrito` VALUES ('853', 'PAROBAMBA', '03', '23');
INSERT INTO `distrito` VALUES ('854', 'QUINUABAMBA', '04', '23');
INSERT INTO `distrito` VALUES ('855', 'RECUAY', '01', '24');
INSERT INTO `distrito` VALUES ('856', 'CATAC', '02', '24');
INSERT INTO `distrito` VALUES ('857', 'COTAPARACO', '03', '24');
INSERT INTO `distrito` VALUES ('858', 'HUAYLLAPAMPA', '04', '24');
INSERT INTO `distrito` VALUES ('859', 'LLACLLIN', '05', '24');
INSERT INTO `distrito` VALUES ('860', 'MARCA', '06', '24');
INSERT INTO `distrito` VALUES ('861', 'PAMPAS CHICO', '07', '24');
INSERT INTO `distrito` VALUES ('862', 'PARARIN', '08', '24');
INSERT INTO `distrito` VALUES ('863', 'TAPACOCHA', '09', '24');
INSERT INTO `distrito` VALUES ('864', 'TICAPAMPA', '10', '24');
INSERT INTO `distrito` VALUES ('865', 'CHIMBOTE', '01', '25');
INSERT INTO `distrito` VALUES ('866', 'CACERES DEL PERU', '02', '25');
INSERT INTO `distrito` VALUES ('867', 'COISHCO', '03', '25');
INSERT INTO `distrito` VALUES ('868', 'MACATE', '04', '25');
INSERT INTO `distrito` VALUES ('869', 'MORO', '05', '25');
INSERT INTO `distrito` VALUES ('870', 'NEPE?A', '06', '25');
INSERT INTO `distrito` VALUES ('871', 'SAMANCO', '07', '25');
INSERT INTO `distrito` VALUES ('872', 'SANTA', '08', '25');
INSERT INTO `distrito` VALUES ('873', 'NUEVO CHIMBOTE', '09', '25');
INSERT INTO `distrito` VALUES ('874', 'SIHUAS', '01', '26');
INSERT INTO `distrito` VALUES ('875', 'ACOBAMBA', '02', '26');
INSERT INTO `distrito` VALUES ('876', 'ALFONSO UGARTE', '03', '26');
INSERT INTO `distrito` VALUES ('877', 'CASHAPAMPA', '04', '26');
INSERT INTO `distrito` VALUES ('878', 'CHINGALPO', '05', '26');
INSERT INTO `distrito` VALUES ('879', 'HUAYLLABAMBA', '06', '26');
INSERT INTO `distrito` VALUES ('880', 'QUICHES', '07', '26');
INSERT INTO `distrito` VALUES ('881', 'RAGASH', '08', '26');
INSERT INTO `distrito` VALUES ('882', 'SAN JUAN', '09', '26');
INSERT INTO `distrito` VALUES ('883', 'SICSIBAMBA', '10', '26');
INSERT INTO `distrito` VALUES ('884', 'YUNGAY', '01', '27');
INSERT INTO `distrito` VALUES ('885', 'CASCAPARA', '02', '27');
INSERT INTO `distrito` VALUES ('886', 'MANCOS', '03', '27');
INSERT INTO `distrito` VALUES ('887', 'MATACOTO', '04', '27');
INSERT INTO `distrito` VALUES ('888', 'QUILLO', '05', '27');
INSERT INTO `distrito` VALUES ('889', 'RANRAHIRCA', '06', '27');
INSERT INTO `distrito` VALUES ('890', 'SHUPLUY', '07', '27');
INSERT INTO `distrito` VALUES ('891', 'YANAMA', '08', '27');
INSERT INTO `distrito` VALUES ('892', 'PIURA', '01', '153');
INSERT INTO `distrito` VALUES ('893', 'CASTILLA', '04', '153');
INSERT INTO `distrito` VALUES ('894', 'CATACAOS', '05', '153');
INSERT INTO `distrito` VALUES ('895', 'CURA MORI', '07', '153');
INSERT INTO `distrito` VALUES ('896', 'EL TALLAN', '08', '153');
INSERT INTO `distrito` VALUES ('897', 'LA ARENA', '09', '153');
INSERT INTO `distrito` VALUES ('898', 'LA UNION', '10', '153');
INSERT INTO `distrito` VALUES ('899', 'LAS LOMAS', '11', '153');
INSERT INTO `distrito` VALUES ('900', 'TAMBO GRANDE', '14', '153');
INSERT INTO `distrito` VALUES ('901', 'AYABACA', '01', '154');
INSERT INTO `distrito` VALUES ('902', 'FRIAS', '02', '154');
INSERT INTO `distrito` VALUES ('903', 'JILILI', '03', '154');
INSERT INTO `distrito` VALUES ('904', 'LAGUNAS', '04', '154');
INSERT INTO `distrito` VALUES ('905', 'MONTERO', '05', '154');
INSERT INTO `distrito` VALUES ('906', 'PACAIPAMPA', '06', '154');
INSERT INTO `distrito` VALUES ('907', 'PAIMAS', '07', '154');
INSERT INTO `distrito` VALUES ('908', 'SAPILLICA', '08', '154');
INSERT INTO `distrito` VALUES ('909', 'SICCHEZ', '09', '154');
INSERT INTO `distrito` VALUES ('910', 'SUYO', '10', '154');
INSERT INTO `distrito` VALUES ('911', 'HUANCABAMBA', '01', '155');
INSERT INTO `distrito` VALUES ('912', 'CANCHAQUE', '02', '155');
INSERT INTO `distrito` VALUES ('913', 'EL CARMEN DE LA FRONTERA', '03', '155');
INSERT INTO `distrito` VALUES ('914', 'HUARMACA', '04', '155');
INSERT INTO `distrito` VALUES ('915', 'LALAQUIZ', '05', '155');
INSERT INTO `distrito` VALUES ('916', 'SAN MIGUEL DE EL FAIQUE', '06', '155');
INSERT INTO `distrito` VALUES ('917', 'SONDOR', '07', '155');
INSERT INTO `distrito` VALUES ('918', 'SONDORILLO', '08', '155');
INSERT INTO `distrito` VALUES ('919', 'CHULUCANAS', '01', '156');
INSERT INTO `distrito` VALUES ('920', 'BUENOS AIRES', '02', '156');
INSERT INTO `distrito` VALUES ('921', 'CHALACO', '03', '156');
INSERT INTO `distrito` VALUES ('922', 'LA MATANZA', '04', '156');
INSERT INTO `distrito` VALUES ('923', 'MORROPON', '05', '156');
INSERT INTO `distrito` VALUES ('924', 'SALITRAL', '06', '156');
INSERT INTO `distrito` VALUES ('925', 'SAN JUAN DE BIGOTE', '07', '156');
INSERT INTO `distrito` VALUES ('926', 'SANTA CATALINA DE MOSSA', '08', '156');
INSERT INTO `distrito` VALUES ('927', 'SANTO DOMINGO', '09', '156');
INSERT INTO `distrito` VALUES ('928', 'YAMANGO', '10', '156');
INSERT INTO `distrito` VALUES ('929', 'PAITA', '01', '157');
INSERT INTO `distrito` VALUES ('930', 'AMOTAPE', '02', '157');
INSERT INTO `distrito` VALUES ('931', 'ARENAL', '03', '157');
INSERT INTO `distrito` VALUES ('932', 'COLAN', '04', '157');
INSERT INTO `distrito` VALUES ('933', 'LA HUACA', '05', '157');
INSERT INTO `distrito` VALUES ('934', 'TAMARINDO', '06', '157');
INSERT INTO `distrito` VALUES ('935', 'VICHAYAL', '07', '157');
INSERT INTO `distrito` VALUES ('936', 'SULLANA', '01', '158');
INSERT INTO `distrito` VALUES ('937', 'BELLAVISTA', '02', '158');
INSERT INTO `distrito` VALUES ('938', 'IGNACIO ESCUDERO', '03', '158');
INSERT INTO `distrito` VALUES ('939', 'LANCONES', '04', '158');
INSERT INTO `distrito` VALUES ('940', 'MARCAVELICA', '05', '158');
INSERT INTO `distrito` VALUES ('941', 'MIGUEL CHECA', '06', '158');
INSERT INTO `distrito` VALUES ('942', 'QUERECOTILLO', '07', '158');
INSERT INTO `distrito` VALUES ('943', 'SALITRAL', '08', '158');
INSERT INTO `distrito` VALUES ('944', 'PARI?AS', '01', '159');
INSERT INTO `distrito` VALUES ('945', 'EL ALTO', '02', '159');
INSERT INTO `distrito` VALUES ('946', 'LA BREA', '03', '159');
INSERT INTO `distrito` VALUES ('947', 'LOBITOS', '04', '159');
INSERT INTO `distrito` VALUES ('948', 'LOS ORGANOS', '05', '159');
INSERT INTO `distrito` VALUES ('949', 'MANCORA', '06', '159');
INSERT INTO `distrito` VALUES ('950', 'SECHURA', '01', '160');
INSERT INTO `distrito` VALUES ('951', 'BELLAVISTA DE LA UNION', '02', '160');
INSERT INTO `distrito` VALUES ('952', 'BERNAL', '03', '160');
INSERT INTO `distrito` VALUES ('953', 'CRISTO NOS VALGA', '04', '160');
INSERT INTO `distrito` VALUES ('954', 'VICE', '05', '160');
INSERT INTO `distrito` VALUES ('955', 'RINCONADA LLICUAR', '06', '160');
INSERT INTO `distrito` VALUES ('956', 'PUNO', '01', '161');
INSERT INTO `distrito` VALUES ('957', 'ACORA', '02', '161');
INSERT INTO `distrito` VALUES ('958', 'AMANTANI', '03', '161');
INSERT INTO `distrito` VALUES ('959', 'ATUNCOLLA', '04', '161');
INSERT INTO `distrito` VALUES ('960', 'CAPACHICA', '05', '161');
INSERT INTO `distrito` VALUES ('961', 'CHUCUITO', '06', '161');
INSERT INTO `distrito` VALUES ('962', 'COATA', '07', '161');
INSERT INTO `distrito` VALUES ('963', 'HUATA', '08', '161');
INSERT INTO `distrito` VALUES ('964', 'MA?AZO', '09', '161');
INSERT INTO `distrito` VALUES ('965', 'PAUCARCOLLA', '10', '161');
INSERT INTO `distrito` VALUES ('966', 'PICHACANI', '11', '161');
INSERT INTO `distrito` VALUES ('967', 'PLATERIA', '12', '161');
INSERT INTO `distrito` VALUES ('968', 'SAN ANTONIO', '13', '161');
INSERT INTO `distrito` VALUES ('969', 'TIQUILLACA', '14', '161');
INSERT INTO `distrito` VALUES ('970', 'VILQUE', '15', '161');
INSERT INTO `distrito` VALUES ('971', 'AZANGARO', '01', '162');
INSERT INTO `distrito` VALUES ('972', 'ACHAYA', '02', '162');
INSERT INTO `distrito` VALUES ('973', 'ARAPA', '03', '162');
INSERT INTO `distrito` VALUES ('974', 'ASILLO', '04', '162');
INSERT INTO `distrito` VALUES ('975', 'CAMINACA', '05', '162');
INSERT INTO `distrito` VALUES ('976', 'CHUPA', '06', '162');
INSERT INTO `distrito` VALUES ('977', 'JOSE DOMINGO CHOQUEHUANCA', '07', '162');
INSERT INTO `distrito` VALUES ('978', 'MU?ANI', '08', '162');
INSERT INTO `distrito` VALUES ('979', 'POTONI', '09', '162');
INSERT INTO `distrito` VALUES ('980', 'SAMAN', '10', '162');
INSERT INTO `distrito` VALUES ('981', 'SAN ANTON', '11', '162');
INSERT INTO `distrito` VALUES ('982', 'SAN JOSE', '12', '162');
INSERT INTO `distrito` VALUES ('983', 'SAN JUAN DE SALINAS', '13', '162');
INSERT INTO `distrito` VALUES ('984', 'SANTIAGO DE PUPUJA', '14', '162');
INSERT INTO `distrito` VALUES ('985', 'TIRAPATA', '15', '162');
INSERT INTO `distrito` VALUES ('986', 'MACUSANI', '01', '163');
INSERT INTO `distrito` VALUES ('987', 'AJOYANI', '02', '163');
INSERT INTO `distrito` VALUES ('988', 'AYAPATA', '03', '163');
INSERT INTO `distrito` VALUES ('989', 'COASA', '04', '163');
INSERT INTO `distrito` VALUES ('990', 'CORANI', '05', '163');
INSERT INTO `distrito` VALUES ('991', 'CRUCERO', '06', '163');
INSERT INTO `distrito` VALUES ('992', 'ITUATA', '07', '163');
INSERT INTO `distrito` VALUES ('993', 'OLLACHEA', '08', '163');
INSERT INTO `distrito` VALUES ('994', 'SAN GABAN', '09', '163');
INSERT INTO `distrito` VALUES ('995', 'USICAYOS', '10', '163');
INSERT INTO `distrito` VALUES ('996', 'JULI', '01', '164');
INSERT INTO `distrito` VALUES ('997', 'DESAGUADERO', '02', '164');
INSERT INTO `distrito` VALUES ('998', 'HUACULLANI', '03', '164');
INSERT INTO `distrito` VALUES ('999', 'KELLUYO', '04', '164');
INSERT INTO `distrito` VALUES ('1000', 'PISACOMA', '05', '164');
INSERT INTO `distrito` VALUES ('1001', 'POMATA', '06', '164');
INSERT INTO `distrito` VALUES ('1002', 'ZEPITA', '07', '164');
INSERT INTO `distrito` VALUES ('1003', 'ILAVE', '01', '165');
INSERT INTO `distrito` VALUES ('1004', 'CAPAZO', '02', '165');
INSERT INTO `distrito` VALUES ('1005', 'PILCUYO', '03', '165');
INSERT INTO `distrito` VALUES ('1006', 'SANTA ROSA', '04', '165');
INSERT INTO `distrito` VALUES ('1007', 'CONDURIRI', '05', '165');
INSERT INTO `distrito` VALUES ('1008', 'HUANCANE', '01', '166');
INSERT INTO `distrito` VALUES ('1009', 'COJATA', '02', '166');
INSERT INTO `distrito` VALUES ('1010', 'HUATASANI', '03', '166');
INSERT INTO `distrito` VALUES ('1011', 'INCHUPALLA', '04', '166');
INSERT INTO `distrito` VALUES ('1012', 'PUSI', '05', '166');
INSERT INTO `distrito` VALUES ('1013', 'ROSASPATA', '06', '166');
INSERT INTO `distrito` VALUES ('1014', 'TARACO', '07', '166');
INSERT INTO `distrito` VALUES ('1015', 'VILQUE CHICO', '08', '166');
INSERT INTO `distrito` VALUES ('1016', 'LAMPA', '01', '167');
INSERT INTO `distrito` VALUES ('1017', 'CABANILLA', '02', '167');
INSERT INTO `distrito` VALUES ('1018', 'CALAPUJA', '03', '167');
INSERT INTO `distrito` VALUES ('1019', 'NICASIO', '04', '167');
INSERT INTO `distrito` VALUES ('1020', 'OCUVIRI', '05', '167');
INSERT INTO `distrito` VALUES ('1021', 'PALCA', '06', '167');
INSERT INTO `distrito` VALUES ('1022', 'PARATIA', '07', '167');
INSERT INTO `distrito` VALUES ('1023', 'PUCARA', '08', '167');
INSERT INTO `distrito` VALUES ('1024', 'SANTA LUCIA', '09', '167');
INSERT INTO `distrito` VALUES ('1025', 'VILAVILA', '10', '167');
INSERT INTO `distrito` VALUES ('1026', 'AYAVIRI', '01', '168');
INSERT INTO `distrito` VALUES ('1027', 'ANTAUTA', '02', '168');
INSERT INTO `distrito` VALUES ('1028', 'CUPI', '03', '168');
INSERT INTO `distrito` VALUES ('1029', 'LLALLI', '04', '168');
INSERT INTO `distrito` VALUES ('1030', 'MACARI', '05', '168');
INSERT INTO `distrito` VALUES ('1031', 'NU?OA', '06', '168');
INSERT INTO `distrito` VALUES ('1032', 'ORURILLO', '07', '168');
INSERT INTO `distrito` VALUES ('1033', 'SANTA ROSA', '08', '168');
INSERT INTO `distrito` VALUES ('1034', 'UMACHIRI', '09', '168');
INSERT INTO `distrito` VALUES ('1035', 'MOHO', '01', '169');
INSERT INTO `distrito` VALUES ('1036', 'CONIMA', '02', '169');
INSERT INTO `distrito` VALUES ('1037', 'HUAYRAPATA', '03', '169');
INSERT INTO `distrito` VALUES ('1038', 'TILALI', '04', '169');
INSERT INTO `distrito` VALUES ('1039', 'PUTINA', '01', '170');
INSERT INTO `distrito` VALUES ('1040', 'ANANEA', '02', '170');
INSERT INTO `distrito` VALUES ('1041', 'PEDRO VILCA APAZA', '03', '170');
INSERT INTO `distrito` VALUES ('1042', 'QUILCAPUNCU', '04', '170');
INSERT INTO `distrito` VALUES ('1043', 'SINA', '05', '170');
INSERT INTO `distrito` VALUES ('1044', 'JULIACA', '01', '171');
INSERT INTO `distrito` VALUES ('1045', 'CABANA', '02', '171');
INSERT INTO `distrito` VALUES ('1046', 'CABANILLAS', '03', '171');
INSERT INTO `distrito` VALUES ('1047', 'CARACOTO', '04', '171');
INSERT INTO `distrito` VALUES ('1048', 'SANDIA', '01', '172');
INSERT INTO `distrito` VALUES ('1049', 'CUYOCUYO', '02', '172');
INSERT INTO `distrito` VALUES ('1050', 'LIMBANI', '03', '172');
INSERT INTO `distrito` VALUES ('1051', 'PATAMBUCO', '04', '172');
INSERT INTO `distrito` VALUES ('1052', 'PHARA', '05', '172');
INSERT INTO `distrito` VALUES ('1053', 'QUIACA', '06', '172');
INSERT INTO `distrito` VALUES ('1054', 'SAN JUAN DEL ORO', '07', '172');
INSERT INTO `distrito` VALUES ('1055', 'YANAHUAYA', '08', '172');
INSERT INTO `distrito` VALUES ('1056', 'ALTO INAMBARI', '09', '172');
INSERT INTO `distrito` VALUES ('1057', 'YUNGUYO', '01', '173');
INSERT INTO `distrito` VALUES ('1058', 'ANAPIA', '02', '173');
INSERT INTO `distrito` VALUES ('1059', 'COPANI', '03', '173');
INSERT INTO `distrito` VALUES ('1060', 'CUTURAPI', '04', '173');
INSERT INTO `distrito` VALUES ('1061', 'OLLARAYA', '05', '173');
INSERT INTO `distrito` VALUES ('1062', 'TINICACHI', '06', '173');
INSERT INTO `distrito` VALUES ('1063', 'UNICACHI', '07', '173');
INSERT INTO `distrito` VALUES ('1064', 'MOYOBAMBA', '01', '174');
INSERT INTO `distrito` VALUES ('1065', 'CALZADA', '02', '174');
INSERT INTO `distrito` VALUES ('1066', 'HABANA', '03', '174');
INSERT INTO `distrito` VALUES ('1067', 'JEPELACIO', '04', '174');
INSERT INTO `distrito` VALUES ('1068', 'SORITOR', '05', '174');
INSERT INTO `distrito` VALUES ('1069', 'YANTALO', '06', '174');
INSERT INTO `distrito` VALUES ('1070', 'BELLAVISTA', '01', '175');
INSERT INTO `distrito` VALUES ('1071', 'ALTO BIAVO', '02', '175');
INSERT INTO `distrito` VALUES ('1072', 'BAJO BIAVO', '03', '175');
INSERT INTO `distrito` VALUES ('1073', 'HUALLAGA', '04', '175');
INSERT INTO `distrito` VALUES ('1074', 'SAN PABLO', '05', '175');
INSERT INTO `distrito` VALUES ('1075', 'SAN RAFAEL', '06', '175');
INSERT INTO `distrito` VALUES ('1076', 'SAN JOSE DE SISA', '01', '176');
INSERT INTO `distrito` VALUES ('1077', 'AGUA BLANCA', '02', '176');
INSERT INTO `distrito` VALUES ('1078', 'SAN MARTIN', '03', '176');
INSERT INTO `distrito` VALUES ('1079', 'SANTA ROSA', '04', '176');
INSERT INTO `distrito` VALUES ('1080', 'SHATOJA', '05', '176');
INSERT INTO `distrito` VALUES ('1081', 'SAPOSOA', '01', '177');
INSERT INTO `distrito` VALUES ('1082', 'ALTO SAPOSOA', '02', '177');
INSERT INTO `distrito` VALUES ('1083', 'EL ESLABON', '03', '177');
INSERT INTO `distrito` VALUES ('1084', 'PISCOYACU', '04', '177');
INSERT INTO `distrito` VALUES ('1085', 'SACANCHE', '05', '177');
INSERT INTO `distrito` VALUES ('1086', 'TINGO DE SAPOSOA', '06', '177');
INSERT INTO `distrito` VALUES ('1087', 'LAMAS', '01', '178');
INSERT INTO `distrito` VALUES ('1088', 'ALONSO DE ALVARADO', '02', '178');
INSERT INTO `distrito` VALUES ('1089', 'BARRANQUITA', '03', '178');
INSERT INTO `distrito` VALUES ('1090', 'CAYNARACHI', '04', '178');
INSERT INTO `distrito` VALUES ('1091', 'CU?UMBUQUI', '05', '178');
INSERT INTO `distrito` VALUES ('1092', 'PINTO RECODO', '06', '178');
INSERT INTO `distrito` VALUES ('1093', 'RUMISAPA', '07', '178');
INSERT INTO `distrito` VALUES ('1094', 'SAN ROQUE DE CUMBAZA', '08', '178');
INSERT INTO `distrito` VALUES ('1095', 'SHANAO', '09', '178');
INSERT INTO `distrito` VALUES ('1096', 'TABALOSOS', '10', '178');
INSERT INTO `distrito` VALUES ('1097', 'ZAPATERO', '11', '178');
INSERT INTO `distrito` VALUES ('1098', 'JUANJUI', '01', '179');
INSERT INTO `distrito` VALUES ('1099', 'CAMPANILLA', '02', '179');
INSERT INTO `distrito` VALUES ('1100', 'HUICUNGO', '03', '179');
INSERT INTO `distrito` VALUES ('1101', 'PACHIZA', '04', '179');
INSERT INTO `distrito` VALUES ('1102', 'PAJARILLO', '05', '179');
INSERT INTO `distrito` VALUES ('1103', 'PICOTA', '01', '180');
INSERT INTO `distrito` VALUES ('1104', 'BUENOS AIRES', '02', '180');
INSERT INTO `distrito` VALUES ('1105', 'CASPISAPA', '03', '180');
INSERT INTO `distrito` VALUES ('1106', 'PILLUANA', '04', '180');
INSERT INTO `distrito` VALUES ('1107', 'PUCACACA', '05', '180');
INSERT INTO `distrito` VALUES ('1108', 'SAN CRISTOBAL', '06', '180');
INSERT INTO `distrito` VALUES ('1109', 'SAN HILARION', '07', '180');
INSERT INTO `distrito` VALUES ('1110', 'SHAMBOYACU', '08', '180');
INSERT INTO `distrito` VALUES ('1111', 'TINGO DE PONASA', '09', '180');
INSERT INTO `distrito` VALUES ('1112', 'TRES UNIDOS', '10', '180');
INSERT INTO `distrito` VALUES ('1113', 'RIOJA', '01', '181');
INSERT INTO `distrito` VALUES ('1114', 'AWAJUN', '02', '181');
INSERT INTO `distrito` VALUES ('1115', 'ELIAS SOPLIN VARGAS', '03', '181');
INSERT INTO `distrito` VALUES ('1116', 'NUEVA CAJAMARCA', '04', '181');
INSERT INTO `distrito` VALUES ('1117', 'PARDO MIGUEL', '05', '181');
INSERT INTO `distrito` VALUES ('1118', 'POSIC', '06', '181');
INSERT INTO `distrito` VALUES ('1119', 'SAN FERNANDO', '07', '181');
INSERT INTO `distrito` VALUES ('1120', 'YORONGOS', '08', '181');
INSERT INTO `distrito` VALUES ('1121', 'YURACYACU', '09', '181');
INSERT INTO `distrito` VALUES ('1122', 'TARAPOTO', '01', '182');
INSERT INTO `distrito` VALUES ('1123', 'ALBERTO LEVEAU', '02', '182');
INSERT INTO `distrito` VALUES ('1124', 'CACATACHI', '03', '182');
INSERT INTO `distrito` VALUES ('1125', 'CHAZUTA', '04', '182');
INSERT INTO `distrito` VALUES ('1126', 'CHIPURANA', '05', '182');
INSERT INTO `distrito` VALUES ('1127', 'EL PORVENIR', '06', '182');
INSERT INTO `distrito` VALUES ('1128', 'HUIMBAYOC', '07', '182');
INSERT INTO `distrito` VALUES ('1129', 'JUAN GUERRA', '08', '182');
INSERT INTO `distrito` VALUES ('1130', 'LA BANDA DE SHILCAYO', '09', '182');
INSERT INTO `distrito` VALUES ('1131', 'MORALES', '10', '182');
INSERT INTO `distrito` VALUES ('1132', 'PAPAPLAYA', '11', '182');
INSERT INTO `distrito` VALUES ('1133', 'SAN ANTONIO', '12', '182');
INSERT INTO `distrito` VALUES ('1134', 'SAUCE', '13', '182');
INSERT INTO `distrito` VALUES ('1135', 'SHAPAJA', '14', '182');
INSERT INTO `distrito` VALUES ('1136', 'TOCACHE', '01', '183');
INSERT INTO `distrito` VALUES ('1137', 'NUEVO PROGRESO', '02', '183');
INSERT INTO `distrito` VALUES ('1138', 'POLVORA', '03', '183');
INSERT INTO `distrito` VALUES ('1139', 'SHUNTE', '04', '183');
INSERT INTO `distrito` VALUES ('1140', 'UCHIZA', '05', '183');
INSERT INTO `distrito` VALUES ('1141', 'TACNA', '01', '184');
INSERT INTO `distrito` VALUES ('1142', 'ALTO DE LA ALIANZA', '02', '184');
INSERT INTO `distrito` VALUES ('1143', 'CALANA', '03', '184');
INSERT INTO `distrito` VALUES ('1144', 'CIUDAD NUEVA', '04', '184');
INSERT INTO `distrito` VALUES ('1145', 'INCLAN', '05', '184');
INSERT INTO `distrito` VALUES ('1146', 'PACHIA', '06', '184');
INSERT INTO `distrito` VALUES ('1147', 'PALCA', '07', '184');
INSERT INTO `distrito` VALUES ('1148', 'POCOLLAY', '08', '184');
INSERT INTO `distrito` VALUES ('1149', 'SAMA', '09', '184');
INSERT INTO `distrito` VALUES ('1150', 'CORONEL GREGORIO ALBARRACIN LANCHIPA', '10', '184');
INSERT INTO `distrito` VALUES ('1151', 'CANDARAVE', '01', '185');
INSERT INTO `distrito` VALUES ('1152', 'CAIRANI', '02', '185');
INSERT INTO `distrito` VALUES ('1153', 'CAMILACA', '03', '185');
INSERT INTO `distrito` VALUES ('1154', 'CURIBAYA', '04', '185');
INSERT INTO `distrito` VALUES ('1155', 'HUANUARA', '05', '185');
INSERT INTO `distrito` VALUES ('1156', 'QUILAHUANI', '06', '185');
INSERT INTO `distrito` VALUES ('1157', 'LOCUMBA', '01', '186');
INSERT INTO `distrito` VALUES ('1158', 'ILABAYA', '02', '186');
INSERT INTO `distrito` VALUES ('1159', 'ITE', '03', '186');
INSERT INTO `distrito` VALUES ('1160', 'TARATA', '01', '187');
INSERT INTO `distrito` VALUES ('1161', 'CHUCATAMANI', '02', '187');
INSERT INTO `distrito` VALUES ('1162', 'ESTIQUE', '03', '187');
INSERT INTO `distrito` VALUES ('1163', 'ESTIQUE-PAMPA', '04', '187');
INSERT INTO `distrito` VALUES ('1164', 'SITAJARA', '05', '187');
INSERT INTO `distrito` VALUES ('1165', 'SUSAPAYA', '06', '187');
INSERT INTO `distrito` VALUES ('1166', 'TARUCACHI', '07', '187');
INSERT INTO `distrito` VALUES ('1167', 'TICACO', '08', '187');
INSERT INTO `distrito` VALUES ('1168', 'TUMBES', '01', '188');
INSERT INTO `distrito` VALUES ('1169', 'CORRALES', '02', '188');
INSERT INTO `distrito` VALUES ('1170', 'LA CRUZ', '03', '188');
INSERT INTO `distrito` VALUES ('1171', 'PAMPAS DE HOSPITAL', '04', '188');
INSERT INTO `distrito` VALUES ('1172', 'SAN JACINTO', '05', '188');
INSERT INTO `distrito` VALUES ('1173', 'SAN JUAN DE LA VIRGEN', '06', '188');
INSERT INTO `distrito` VALUES ('1174', 'ZORRITOS', '01', '189');
INSERT INTO `distrito` VALUES ('1175', 'CASITAS', '02', '189');
INSERT INTO `distrito` VALUES ('1176', 'ZARUMILLA', '01', '190');
INSERT INTO `distrito` VALUES ('1177', 'AGUAS VERDES', '02', '190');
INSERT INTO `distrito` VALUES ('1178', 'MATAPALO', '03', '190');
INSERT INTO `distrito` VALUES ('1179', 'PAPAYAL', '04', '190');
INSERT INTO `distrito` VALUES ('1180', 'CALLERIA', '01', '191');
INSERT INTO `distrito` VALUES ('1181', 'CAMPOVERDE', '02', '191');
INSERT INTO `distrito` VALUES ('1182', 'IPARIA', '03', '191');
INSERT INTO `distrito` VALUES ('1183', 'MASISEA', '04', '191');
INSERT INTO `distrito` VALUES ('1184', 'YARINACOCHA', '05', '191');
INSERT INTO `distrito` VALUES ('1185', 'NUEVA REQUENA', '06', '191');
INSERT INTO `distrito` VALUES ('1186', 'RAYMONDI', '01', '192');
INSERT INTO `distrito` VALUES ('1187', 'SEPAHUA', '02', '192');
INSERT INTO `distrito` VALUES ('1188', 'TAHUANIA', '03', '192');
INSERT INTO `distrito` VALUES ('1189', 'YURUA', '04', '192');
INSERT INTO `distrito` VALUES ('1190', 'PADRE ABAD', '01', '193');
INSERT INTO `distrito` VALUES ('1191', 'IRAZOLA', '02', '193');
INSERT INTO `distrito` VALUES ('1192', 'CURIMANA', '03', '193');
INSERT INTO `distrito` VALUES ('1193', 'PURUS', '01', '194');
INSERT INTO `distrito` VALUES ('1194', 'ABANCAY', '01', '28');
INSERT INTO `distrito` VALUES ('1195', 'CHACOCHE', '02', '28');
INSERT INTO `distrito` VALUES ('1196', 'CIRCA', '03', '28');
INSERT INTO `distrito` VALUES ('1197', 'CURAHUASI', '04', '28');
INSERT INTO `distrito` VALUES ('1198', 'HUANIPACA', '05', '28');
INSERT INTO `distrito` VALUES ('1199', 'LAMBRAMA', '06', '28');
INSERT INTO `distrito` VALUES ('1200', 'PICHIRHUA', '07', '28');
INSERT INTO `distrito` VALUES ('1201', 'SAN PEDRO DE CACHORA', '08', '28');
INSERT INTO `distrito` VALUES ('1202', 'TAMBURCO', '09', '28');
INSERT INTO `distrito` VALUES ('1203', 'ANDAHUAYLAS', '01', '29');
INSERT INTO `distrito` VALUES ('1204', 'ANDARAPA', '02', '29');
INSERT INTO `distrito` VALUES ('1205', 'CHIARA', '03', '29');
INSERT INTO `distrito` VALUES ('1206', 'HUANCARAMA', '04', '29');
INSERT INTO `distrito` VALUES ('1207', 'HUANCARAY', '05', '29');
INSERT INTO `distrito` VALUES ('1208', 'HUAYANA', '06', '29');
INSERT INTO `distrito` VALUES ('1209', 'KISHUARA', '07', '29');
INSERT INTO `distrito` VALUES ('1210', 'PACOBAMBA', '08', '29');
INSERT INTO `distrito` VALUES ('1211', 'PACUCHA', '09', '29');
INSERT INTO `distrito` VALUES ('1212', 'PAMPACHIRI', '10', '29');
INSERT INTO `distrito` VALUES ('1213', 'POMACOCHA', '11', '29');
INSERT INTO `distrito` VALUES ('1214', 'SAN ANTONIO DE CACHI', '12', '29');
INSERT INTO `distrito` VALUES ('1215', 'SAN JERONIMO', '13', '29');
INSERT INTO `distrito` VALUES ('1216', 'SAN MIGUEL DE CHACCRAMPA', '14', '29');
INSERT INTO `distrito` VALUES ('1217', 'SANTA MARIA DE CHICMO', '15', '29');
INSERT INTO `distrito` VALUES ('1218', 'TALAVERA', '16', '29');
INSERT INTO `distrito` VALUES ('1219', 'TUMAY HUARACA', '17', '29');
INSERT INTO `distrito` VALUES ('1220', 'TURPO', '18', '29');
INSERT INTO `distrito` VALUES ('1221', 'KAQUIABAMBA', '19', '29');
INSERT INTO `distrito` VALUES ('1222', 'ANTABAMBA', '01', '30');
INSERT INTO `distrito` VALUES ('1223', 'EL ORO', '02', '30');
INSERT INTO `distrito` VALUES ('1224', 'HUAQUIRCA', '03', '30');
INSERT INTO `distrito` VALUES ('1225', 'JUAN ESPINOZA MEDRANO', '04', '30');
INSERT INTO `distrito` VALUES ('1226', 'OROPESA', '05', '30');
INSERT INTO `distrito` VALUES ('1227', 'PACHACONAS', '06', '30');
INSERT INTO `distrito` VALUES ('1228', 'SABAINO', '07', '30');
INSERT INTO `distrito` VALUES ('1229', 'CHALHUANCA', '01', '31');
INSERT INTO `distrito` VALUES ('1230', 'CAPAYA', '02', '31');
INSERT INTO `distrito` VALUES ('1231', 'CARAYBAMBA', '03', '31');
INSERT INTO `distrito` VALUES ('1232', 'CHAPIMARCA', '04', '31');
INSERT INTO `distrito` VALUES ('1233', 'COLCABAMBA', '05', '31');
INSERT INTO `distrito` VALUES ('1234', 'COTARUSE', '06', '31');
INSERT INTO `distrito` VALUES ('1235', 'HUAYLLO', '07', '31');
INSERT INTO `distrito` VALUES ('1236', 'JUSTO APU SAHUARAURA', '08', '31');
INSERT INTO `distrito` VALUES ('1237', 'LUCRE', '09', '31');
INSERT INTO `distrito` VALUES ('1238', 'POCOHUANCA', '10', '31');
INSERT INTO `distrito` VALUES ('1239', 'SAN JUAN DE CHAC?A', '11', '31');
INSERT INTO `distrito` VALUES ('1240', 'SA?AYCA', '12', '31');
INSERT INTO `distrito` VALUES ('1241', 'SORAYA', '13', '31');
INSERT INTO `distrito` VALUES ('1242', 'TAPAIRIHUA', '14', '31');
INSERT INTO `distrito` VALUES ('1243', 'TINTAY', '15', '31');
INSERT INTO `distrito` VALUES ('1244', 'TORAYA', '16', '31');
INSERT INTO `distrito` VALUES ('1245', 'YANACA', '17', '31');
INSERT INTO `distrito` VALUES ('1246', 'TAMBOBAMBA', '01', '32');
INSERT INTO `distrito` VALUES ('1247', 'COTABAMBAS', '02', '32');
INSERT INTO `distrito` VALUES ('1248', 'COYLLURQUI', '03', '32');
INSERT INTO `distrito` VALUES ('1249', 'HAQUIRA', '04', '32');
INSERT INTO `distrito` VALUES ('1250', 'MARA', '05', '32');
INSERT INTO `distrito` VALUES ('1251', 'CHALLHUAHUACHO', '06', '32');
INSERT INTO `distrito` VALUES ('1252', 'CHINCHEROS', '01', '33');
INSERT INTO `distrito` VALUES ('1253', 'ANCO-HUALLO', '02', '33');
INSERT INTO `distrito` VALUES ('1254', 'COCHARCAS', '03', '33');
INSERT INTO `distrito` VALUES ('1255', 'HUACCANA', '04', '33');
INSERT INTO `distrito` VALUES ('1256', 'OCOBAMBA', '05', '33');
INSERT INTO `distrito` VALUES ('1257', 'ONGOY', '06', '33');
INSERT INTO `distrito` VALUES ('1258', 'URANMARCA', '07', '33');
INSERT INTO `distrito` VALUES ('1259', 'RANRACANCHA', '08', '33');
INSERT INTO `distrito` VALUES ('1260', 'CHUQUIBAMBILLA', '01', '34');
INSERT INTO `distrito` VALUES ('1261', 'CURPAHUASI', '02', '34');
INSERT INTO `distrito` VALUES ('1262', 'GAMARRA', '03', '34');
INSERT INTO `distrito` VALUES ('1263', 'HUAYLLATI', '04', '34');
INSERT INTO `distrito` VALUES ('1264', 'MAMARA', '05', '34');
INSERT INTO `distrito` VALUES ('1265', 'MICAELA BASTIDAS', '06', '34');
INSERT INTO `distrito` VALUES ('1266', 'PATAYPAMPA', '07', '34');
INSERT INTO `distrito` VALUES ('1267', 'PROGRESO', '08', '34');
INSERT INTO `distrito` VALUES ('1268', 'SAN ANTONIO', '09', '34');
INSERT INTO `distrito` VALUES ('1269', 'SANTA ROSA', '10', '34');
INSERT INTO `distrito` VALUES ('1270', 'TURPAY', '11', '34');
INSERT INTO `distrito` VALUES ('1271', 'VILCABAMBA', '12', '34');
INSERT INTO `distrito` VALUES ('1272', 'VIRUNDO', '13', '34');
INSERT INTO `distrito` VALUES ('1273', 'CURASCO', '14', '34');
INSERT INTO `distrito` VALUES ('1274', 'AREQUIPA', '01', '35');
INSERT INTO `distrito` VALUES ('1275', 'ALTO SELVA ALEGRE', '02', '35');
INSERT INTO `distrito` VALUES ('1276', 'CAYMA', '03', '35');
INSERT INTO `distrito` VALUES ('1277', 'CERRO COLORADO', '04', '35');
INSERT INTO `distrito` VALUES ('1278', 'CHARACATO', '05', '35');
INSERT INTO `distrito` VALUES ('1279', 'CHIGUATA', '06', '35');
INSERT INTO `distrito` VALUES ('1280', 'JACOBO HUNTER', '07', '35');
INSERT INTO `distrito` VALUES ('1281', 'LA JOYA', '08', '35');
INSERT INTO `distrito` VALUES ('1282', 'MARIANO MELGAR', '09', '35');
INSERT INTO `distrito` VALUES ('1283', 'MIRAFLORES', '10', '35');
INSERT INTO `distrito` VALUES ('1284', 'MOLLEBAYA', '11', '35');
INSERT INTO `distrito` VALUES ('1285', 'PAUCARPATA', '12', '35');
INSERT INTO `distrito` VALUES ('1286', 'POCSI', '13', '35');
INSERT INTO `distrito` VALUES ('1287', 'POLOBAYA', '14', '35');
INSERT INTO `distrito` VALUES ('1288', 'QUEQUE?A', '15', '35');
INSERT INTO `distrito` VALUES ('1289', 'SABANDIA', '16', '35');
INSERT INTO `distrito` VALUES ('1290', 'SACHACA', '17', '35');
INSERT INTO `distrito` VALUES ('1291', 'SAN JUAN DE SIGUAS', '18', '35');
INSERT INTO `distrito` VALUES ('1292', 'SAN JUAN DE TARUCANI', '19', '35');
INSERT INTO `distrito` VALUES ('1293', 'SANTA ISABEL DE SIGUAS', '20', '35');
INSERT INTO `distrito` VALUES ('1294', 'SANTA RITA DE SIGUAS', '21', '35');
INSERT INTO `distrito` VALUES ('1295', 'SOCABAYA', '22', '35');
INSERT INTO `distrito` VALUES ('1296', 'TIABAYA', '23', '35');
INSERT INTO `distrito` VALUES ('1297', 'UCHUMAYO', '24', '35');
INSERT INTO `distrito` VALUES ('1298', 'VITOR', '25', '35');
INSERT INTO `distrito` VALUES ('1299', 'YANAHUARA', '26', '35');
INSERT INTO `distrito` VALUES ('1300', 'YARABAMBA', '27', '35');
INSERT INTO `distrito` VALUES ('1301', 'YURA', '28', '35');
INSERT INTO `distrito` VALUES ('1302', 'JOSE LUIS BUSTAMANTE Y RIVERO', '29', '35');
INSERT INTO `distrito` VALUES ('1303', 'CAMANA', '01', '36');
INSERT INTO `distrito` VALUES ('1304', 'JOSE MARIA QUIMPER', '02', '36');
INSERT INTO `distrito` VALUES ('1305', 'MARIANO NICOLAS VALCARCEL', '03', '36');
INSERT INTO `distrito` VALUES ('1306', 'MARISCAL CACERES', '04', '36');
INSERT INTO `distrito` VALUES ('1307', 'NICOLAS DE PIEROLA', '05', '36');
INSERT INTO `distrito` VALUES ('1308', 'OCO?A', '06', '36');
INSERT INTO `distrito` VALUES ('1309', 'QUILCA', '07', '36');
INSERT INTO `distrito` VALUES ('1310', 'SAMUEL PASTOR', '08', '36');
INSERT INTO `distrito` VALUES ('1311', 'CARAVELI', '01', '37');
INSERT INTO `distrito` VALUES ('1312', 'ACARI', '02', '37');
INSERT INTO `distrito` VALUES ('1313', 'ATICO', '03', '37');
INSERT INTO `distrito` VALUES ('1314', 'ATIQUIPA', '04', '37');
INSERT INTO `distrito` VALUES ('1315', 'BELLA UNION', '05', '37');
INSERT INTO `distrito` VALUES ('1316', 'CAHUACHO', '06', '37');
INSERT INTO `distrito` VALUES ('1317', 'CHALA', '07', '37');
INSERT INTO `distrito` VALUES ('1318', 'CHAPARRA', '08', '37');
INSERT INTO `distrito` VALUES ('1319', 'HUANUHUANU', '09', '37');
INSERT INTO `distrito` VALUES ('1320', 'JAQUI', '10', '37');
INSERT INTO `distrito` VALUES ('1321', 'LOMAS', '11', '37');
INSERT INTO `distrito` VALUES ('1322', 'QUICACHA', '12', '37');
INSERT INTO `distrito` VALUES ('1323', 'YAUCA', '13', '37');
INSERT INTO `distrito` VALUES ('1324', 'APLAO', '01', '38');
INSERT INTO `distrito` VALUES ('1325', 'ANDAGUA', '02', '38');
INSERT INTO `distrito` VALUES ('1326', 'AYO', '03', '38');
INSERT INTO `distrito` VALUES ('1327', 'CHACHAS', '04', '38');
INSERT INTO `distrito` VALUES ('1328', 'CHILCAYMARCA', '05', '38');
INSERT INTO `distrito` VALUES ('1329', 'CHOCO', '06', '38');
INSERT INTO `distrito` VALUES ('1330', 'HUANCARQUI', '07', '38');
INSERT INTO `distrito` VALUES ('1331', 'MACHAGUAY', '08', '38');
INSERT INTO `distrito` VALUES ('1332', 'ORCOPAMPA', '09', '38');
INSERT INTO `distrito` VALUES ('1333', 'PAMPACOLCA', '10', '38');
INSERT INTO `distrito` VALUES ('1334', 'TIPAN', '11', '38');
INSERT INTO `distrito` VALUES ('1335', 'U?ON', '12', '38');
INSERT INTO `distrito` VALUES ('1336', 'URACA', '13', '38');
INSERT INTO `distrito` VALUES ('1337', 'VIRACO', '14', '38');
INSERT INTO `distrito` VALUES ('1338', 'CHIVAY', '01', '39');
INSERT INTO `distrito` VALUES ('1339', 'ACHOMA', '02', '39');
INSERT INTO `distrito` VALUES ('1340', 'CABANACONDE', '03', '39');
INSERT INTO `distrito` VALUES ('1341', 'CALLALLI', '04', '39');
INSERT INTO `distrito` VALUES ('1342', 'CAYLLOMA', '05', '39');
INSERT INTO `distrito` VALUES ('1343', 'COPORAQUE', '06', '39');
INSERT INTO `distrito` VALUES ('1344', 'HUAMBO', '07', '39');
INSERT INTO `distrito` VALUES ('1345', 'HUANCA', '08', '39');
INSERT INTO `distrito` VALUES ('1346', 'ICHUPAMPA', '09', '39');
INSERT INTO `distrito` VALUES ('1347', 'LARI', '10', '39');
INSERT INTO `distrito` VALUES ('1348', 'LLUTA', '11', '39');
INSERT INTO `distrito` VALUES ('1349', 'MACA', '12', '39');
INSERT INTO `distrito` VALUES ('1350', 'MADRIGAL', '13', '39');
INSERT INTO `distrito` VALUES ('1351', 'SAN ANTONIO DE CHUCA', '14', '39');
INSERT INTO `distrito` VALUES ('1352', 'SIBAYO', '15', '39');
INSERT INTO `distrito` VALUES ('1353', 'TAPAY', '16', '39');
INSERT INTO `distrito` VALUES ('1354', 'TISCO', '17', '39');
INSERT INTO `distrito` VALUES ('1355', 'TUTI', '18', '39');
INSERT INTO `distrito` VALUES ('1356', 'YANQUE', '19', '39');
INSERT INTO `distrito` VALUES ('1357', 'MAJES', '20', '39');
INSERT INTO `distrito` VALUES ('1358', 'CHUQUIBAMBA', '01', '40');
INSERT INTO `distrito` VALUES ('1359', 'ANDARAY', '02', '40');
INSERT INTO `distrito` VALUES ('1360', 'CAYARANI', '03', '40');
INSERT INTO `distrito` VALUES ('1361', 'CHICHAS', '04', '40');
INSERT INTO `distrito` VALUES ('1362', 'IRAY', '05', '40');
INSERT INTO `distrito` VALUES ('1363', 'RIO GRANDE', '06', '40');
INSERT INTO `distrito` VALUES ('1364', 'SALAMANCA', '07', '40');
INSERT INTO `distrito` VALUES ('1365', 'YANAQUIHUA', '08', '40');
INSERT INTO `distrito` VALUES ('1366', 'MOLLENDO', '01', '41');
INSERT INTO `distrito` VALUES ('1367', 'COCACHACRA', '02', '41');
INSERT INTO `distrito` VALUES ('1368', 'DEAN VALDIVIA', '03', '41');
INSERT INTO `distrito` VALUES ('1369', 'ISLAY', '04', '41');
INSERT INTO `distrito` VALUES ('1370', 'MEJIA', '05', '41');
INSERT INTO `distrito` VALUES ('1371', 'PUNTA DE BOMBON', '06', '41');
INSERT INTO `distrito` VALUES ('1372', 'COTAHUASI', '01', '42');
INSERT INTO `distrito` VALUES ('1373', 'ALCA', '02', '42');
INSERT INTO `distrito` VALUES ('1374', 'CHARCANA', '03', '42');
INSERT INTO `distrito` VALUES ('1375', 'HUAYNACOTAS', '04', '42');
INSERT INTO `distrito` VALUES ('1376', 'PAMPAMARCA', '05', '42');
INSERT INTO `distrito` VALUES ('1377', 'PUYCA', '06', '42');
INSERT INTO `distrito` VALUES ('1378', 'QUECHUALLA', '07', '42');
INSERT INTO `distrito` VALUES ('1379', 'SAYLA', '08', '42');
INSERT INTO `distrito` VALUES ('1380', 'TAURIA', '09', '42');
INSERT INTO `distrito` VALUES ('1381', 'TOMEPAMPA', '10', '42');
INSERT INTO `distrito` VALUES ('1382', 'TORO', '11', '42');
INSERT INTO `distrito` VALUES ('1383', 'AYACUCHO', '01', '43');
INSERT INTO `distrito` VALUES ('1384', 'ACOCRO', '02', '43');
INSERT INTO `distrito` VALUES ('1385', 'ACOS VINCHOS', '03', '43');
INSERT INTO `distrito` VALUES ('1386', 'CARMEN ALTO', '04', '43');
INSERT INTO `distrito` VALUES ('1387', 'CHIARA', '05', '43');
INSERT INTO `distrito` VALUES ('1388', 'OCROS', '06', '43');
INSERT INTO `distrito` VALUES ('1389', 'PACAYCASA', '07', '43');
INSERT INTO `distrito` VALUES ('1390', 'QUINUA', '08', '43');
INSERT INTO `distrito` VALUES ('1391', 'SAN JOSE DE TICLLAS', '09', '43');
INSERT INTO `distrito` VALUES ('1392', 'SAN JUAN BAUTISTA', '10', '43');
INSERT INTO `distrito` VALUES ('1393', 'SANTIAGO DE PISCHA', '11', '43');
INSERT INTO `distrito` VALUES ('1394', 'SOCOS', '12', '43');
INSERT INTO `distrito` VALUES ('1395', 'TAMBILLO', '13', '43');
INSERT INTO `distrito` VALUES ('1396', 'VINCHOS', '14', '43');
INSERT INTO `distrito` VALUES ('1397', 'JESUS NAZARENO', '15', '43');
INSERT INTO `distrito` VALUES ('1398', 'CANGALLO', '01', '44');
INSERT INTO `distrito` VALUES ('1399', 'CHUSCHI', '02', '44');
INSERT INTO `distrito` VALUES ('1400', 'LOS MOROCHUCOS', '03', '44');
INSERT INTO `distrito` VALUES ('1401', 'MARIA PARADO DE BELLIDO', '04', '44');
INSERT INTO `distrito` VALUES ('1402', 'PARAS', '05', '44');
INSERT INTO `distrito` VALUES ('1403', 'TOTOS', '06', '44');
INSERT INTO `distrito` VALUES ('1404', 'SANCOS', '01', '45');
INSERT INTO `distrito` VALUES ('1405', 'CARAPO', '02', '45');
INSERT INTO `distrito` VALUES ('1406', 'SACSAMARCA', '03', '45');
INSERT INTO `distrito` VALUES ('1407', 'SANTIAGO DE LUCANAMARCA', '04', '45');
INSERT INTO `distrito` VALUES ('1408', 'HUANTA', '01', '46');
INSERT INTO `distrito` VALUES ('1409', 'AYAHUANCO', '02', '46');
INSERT INTO `distrito` VALUES ('1410', 'HUAMANGUILLA', '03', '46');
INSERT INTO `distrito` VALUES ('1411', 'IGUAIN', '04', '46');
INSERT INTO `distrito` VALUES ('1412', 'LURICOCHA', '05', '46');
INSERT INTO `distrito` VALUES ('1413', 'SANTILLANA', '06', '46');
INSERT INTO `distrito` VALUES ('1414', 'SIVIA', '07', '46');
INSERT INTO `distrito` VALUES ('1415', 'LLOCHEGUA', '08', '46');
INSERT INTO `distrito` VALUES ('1416', 'SAN MIGUEL', '01', '47');
INSERT INTO `distrito` VALUES ('1417', 'ANCO', '02', '47');
INSERT INTO `distrito` VALUES ('1418', 'AYNA', '03', '47');
INSERT INTO `distrito` VALUES ('1419', 'CHILCAS', '04', '47');
INSERT INTO `distrito` VALUES ('1420', 'CHUNGUI', '05', '47');
INSERT INTO `distrito` VALUES ('1421', 'LUIS CARRANZA', '06', '47');
INSERT INTO `distrito` VALUES ('1422', 'SANTA ROSA', '07', '47');
INSERT INTO `distrito` VALUES ('1423', 'TAMBO', '08', '47');
INSERT INTO `distrito` VALUES ('1424', 'PUQUIO', '01', '48');
INSERT INTO `distrito` VALUES ('1425', 'AUCARA', '02', '48');
INSERT INTO `distrito` VALUES ('1426', 'CABANA', '03', '48');
INSERT INTO `distrito` VALUES ('1427', 'CARMEN SALCEDO', '04', '48');
INSERT INTO `distrito` VALUES ('1428', 'CHAVI?A', '05', '48');
INSERT INTO `distrito` VALUES ('1429', 'CHIPAO', '06', '48');
INSERT INTO `distrito` VALUES ('1430', 'HUAC-HUAS', '07', '48');
INSERT INTO `distrito` VALUES ('1431', 'LARAMATE', '08', '48');
INSERT INTO `distrito` VALUES ('1432', 'LEONCIO PRADO', '09', '48');
INSERT INTO `distrito` VALUES ('1433', 'LLAUTA', '10', '48');
INSERT INTO `distrito` VALUES ('1434', 'LUCANAS', '11', '48');
INSERT INTO `distrito` VALUES ('1435', 'OCA?A', '12', '48');
INSERT INTO `distrito` VALUES ('1436', 'OTOCA', '13', '48');
INSERT INTO `distrito` VALUES ('1437', 'SAISA', '14', '48');
INSERT INTO `distrito` VALUES ('1438', 'SAN CRISTOBAL', '15', '48');
INSERT INTO `distrito` VALUES ('1439', 'SAN JUAN', '16', '48');
INSERT INTO `distrito` VALUES ('1440', 'SAN PEDRO', '17', '48');
INSERT INTO `distrito` VALUES ('1441', 'SAN PEDRO DE PALCO', '18', '48');
INSERT INTO `distrito` VALUES ('1442', 'SANCOS', '19', '48');
INSERT INTO `distrito` VALUES ('1443', 'SANTA ANA DE HUAYCAHUACHO', '20', '48');
INSERT INTO `distrito` VALUES ('1444', 'SANTA LUCIA', '21', '48');
INSERT INTO `distrito` VALUES ('1445', 'CORACORA', '01', '49');
INSERT INTO `distrito` VALUES ('1446', 'CHUMPI', '02', '49');
INSERT INTO `distrito` VALUES ('1447', 'CORONEL CASTA?EDA', '03', '49');
INSERT INTO `distrito` VALUES ('1448', 'PACAPAUSA', '04', '49');
INSERT INTO `distrito` VALUES ('1449', 'PULLO', '05', '49');
INSERT INTO `distrito` VALUES ('1450', 'PUYUSCA', '06', '49');
INSERT INTO `distrito` VALUES ('1451', 'SAN FRANCISCO DE RAVACAYCO', '07', '49');
INSERT INTO `distrito` VALUES ('1452', 'UPAHUACHO', '08', '49');
INSERT INTO `distrito` VALUES ('1453', 'PAUSA', '01', '50');
INSERT INTO `distrito` VALUES ('1454', 'COLTA', '02', '50');
INSERT INTO `distrito` VALUES ('1455', 'CORCULLA', '03', '50');
INSERT INTO `distrito` VALUES ('1456', 'LAMPA', '04', '50');
INSERT INTO `distrito` VALUES ('1457', 'MARCABAMBA', '05', '50');
INSERT INTO `distrito` VALUES ('1458', 'OYOLO', '06', '50');
INSERT INTO `distrito` VALUES ('1459', 'PARARCA', '07', '50');
INSERT INTO `distrito` VALUES ('1460', 'SAN JAVIER DE ALPABAMBA', '08', '50');
INSERT INTO `distrito` VALUES ('1461', 'SAN JOSE DE USHUA', '09', '50');
INSERT INTO `distrito` VALUES ('1462', 'SARA SARA', '10', '50');
INSERT INTO `distrito` VALUES ('1463', 'QUEROBAMBA', '01', '51');
INSERT INTO `distrito` VALUES ('1464', 'BELEN', '02', '51');
INSERT INTO `distrito` VALUES ('1465', 'CHALCOS', '03', '51');
INSERT INTO `distrito` VALUES ('1466', 'CHILCAYOC', '04', '51');
INSERT INTO `distrito` VALUES ('1467', 'HUACA?A', '05', '51');
INSERT INTO `distrito` VALUES ('1468', 'MORCOLLA', '06', '51');
INSERT INTO `distrito` VALUES ('1469', 'PAICO', '07', '51');
INSERT INTO `distrito` VALUES ('1470', 'SAN PEDRO DE LARCAY', '08', '51');
INSERT INTO `distrito` VALUES ('1471', 'SAN SALVADOR DE QUIJE', '09', '51');
INSERT INTO `distrito` VALUES ('1472', 'SANTIAGO DE PAUCARAY', '10', '51');
INSERT INTO `distrito` VALUES ('1473', 'SORAS', '11', '51');
INSERT INTO `distrito` VALUES ('1474', 'HUANCAPI', '01', '52');
INSERT INTO `distrito` VALUES ('1475', 'ALCAMENCA', '02', '52');
INSERT INTO `distrito` VALUES ('1476', 'APONGO', '03', '52');
INSERT INTO `distrito` VALUES ('1477', 'ASQUIPATA', '04', '52');
INSERT INTO `distrito` VALUES ('1478', 'CANARIA', '05', '52');
INSERT INTO `distrito` VALUES ('1479', 'CAYARA', '06', '52');
INSERT INTO `distrito` VALUES ('1480', 'COLCA', '07', '52');
INSERT INTO `distrito` VALUES ('1481', 'HUAMANQUIQUIA', '08', '52');
INSERT INTO `distrito` VALUES ('1482', 'HUANCARAYLLA', '09', '52');
INSERT INTO `distrito` VALUES ('1483', 'HUAYA', '10', '52');
INSERT INTO `distrito` VALUES ('1484', 'SARHUA', '11', '52');
INSERT INTO `distrito` VALUES ('1485', 'VILCANCHOS', '12', '52');
INSERT INTO `distrito` VALUES ('1486', 'VILCAS HUAMAN', '01', '53');
INSERT INTO `distrito` VALUES ('1487', 'ACCOMARCA', '02', '53');
INSERT INTO `distrito` VALUES ('1488', 'CARHUANCA', '03', '53');
INSERT INTO `distrito` VALUES ('1489', 'CONCEPCION', '04', '53');
INSERT INTO `distrito` VALUES ('1490', 'HUAMBALPA', '05', '53');
INSERT INTO `distrito` VALUES ('1491', 'INDEPENDENCIA', '06', '53');
INSERT INTO `distrito` VALUES ('1492', 'SAURAMA', '07', '53');
INSERT INTO `distrito` VALUES ('1493', 'VISCHONGO', '08', '53');
INSERT INTO `distrito` VALUES ('1494', 'CAJAMARCA', '01', '54');
INSERT INTO `distrito` VALUES ('1495', 'ASUNCION', '02', '54');
INSERT INTO `distrito` VALUES ('1496', 'CHETILLA', '03', '54');
INSERT INTO `distrito` VALUES ('1497', 'COSPAN', '04', '54');
INSERT INTO `distrito` VALUES ('1498', 'ENCA?ADA', '05', '54');
INSERT INTO `distrito` VALUES ('1499', 'JESUS', '06', '54');
INSERT INTO `distrito` VALUES ('1500', 'LLACANORA', '07', '54');
INSERT INTO `distrito` VALUES ('1501', 'LOS BA?OS DEL INCA', '08', '54');
INSERT INTO `distrito` VALUES ('1502', 'MAGDALENA', '09', '54');
INSERT INTO `distrito` VALUES ('1503', 'MATARA', '10', '54');
INSERT INTO `distrito` VALUES ('1504', 'NAMORA', '11', '54');
INSERT INTO `distrito` VALUES ('1505', 'SAN JUAN', '12', '54');
INSERT INTO `distrito` VALUES ('1506', 'CAJABAMBA', '01', '55');
INSERT INTO `distrito` VALUES ('1507', 'CACHACHI', '02', '55');
INSERT INTO `distrito` VALUES ('1508', 'CONDEBAMBA', '03', '55');
INSERT INTO `distrito` VALUES ('1509', 'SITACOCHA', '04', '55');
INSERT INTO `distrito` VALUES ('1510', 'CELENDIN', '01', '56');
INSERT INTO `distrito` VALUES ('1511', 'CHUMUCH', '02', '56');
INSERT INTO `distrito` VALUES ('1512', 'CORTEGANA', '03', '56');
INSERT INTO `distrito` VALUES ('1513', 'HUASMIN', '04', '56');
INSERT INTO `distrito` VALUES ('1514', 'JORGE CHAVEZ', '05', '56');
INSERT INTO `distrito` VALUES ('1515', 'JOSE GALVEZ', '06', '56');
INSERT INTO `distrito` VALUES ('1516', 'MIGUEL IGLESIAS', '07', '56');
INSERT INTO `distrito` VALUES ('1517', 'OXAMARCA', '08', '56');
INSERT INTO `distrito` VALUES ('1518', 'SOROCHUCO', '09', '56');
INSERT INTO `distrito` VALUES ('1519', 'SUCRE', '10', '56');
INSERT INTO `distrito` VALUES ('1520', 'UTCO', '11', '56');
INSERT INTO `distrito` VALUES ('1521', 'LA LIBERTAD DE PALLAN', '12', '56');
INSERT INTO `distrito` VALUES ('1522', 'CHOTA', '01', '57');
INSERT INTO `distrito` VALUES ('1523', 'ANGUIA', '02', '57');
INSERT INTO `distrito` VALUES ('1524', 'CHADIN', '03', '57');
INSERT INTO `distrito` VALUES ('1525', 'CHIGUIRIP', '04', '57');
INSERT INTO `distrito` VALUES ('1526', 'CHIMBAN', '05', '57');
INSERT INTO `distrito` VALUES ('1527', 'CHOROPAMPA', '06', '57');
INSERT INTO `distrito` VALUES ('1528', 'COCHABAMBA', '07', '57');
INSERT INTO `distrito` VALUES ('1529', 'CONCHAN', '08', '57');
INSERT INTO `distrito` VALUES ('1530', 'HUAMBOS', '09', '57');
INSERT INTO `distrito` VALUES ('1531', 'LAJAS', '10', '57');
INSERT INTO `distrito` VALUES ('1532', 'LLAMA', '11', '57');
INSERT INTO `distrito` VALUES ('1533', 'MIRACOSTA', '12', '57');
INSERT INTO `distrito` VALUES ('1534', 'PACCHA', '13', '57');
INSERT INTO `distrito` VALUES ('1535', 'PION', '14', '57');
INSERT INTO `distrito` VALUES ('1536', 'QUEROCOTO', '15', '57');
INSERT INTO `distrito` VALUES ('1537', 'SAN JUAN DE LICUPIS', '16', '57');
INSERT INTO `distrito` VALUES ('1538', 'TACABAMBA', '17', '57');
INSERT INTO `distrito` VALUES ('1539', 'TOCMOCHE', '18', '57');
INSERT INTO `distrito` VALUES ('1540', 'CHALAMARCA', '19', '57');
INSERT INTO `distrito` VALUES ('1541', 'CONTUMAZA', '01', '58');
INSERT INTO `distrito` VALUES ('1542', 'CHILETE', '02', '58');
INSERT INTO `distrito` VALUES ('1543', 'CUPISNIQUE', '03', '58');
INSERT INTO `distrito` VALUES ('1544', 'GUZMANGO', '04', '58');
INSERT INTO `distrito` VALUES ('1545', 'SAN BENITO', '05', '58');
INSERT INTO `distrito` VALUES ('1546', 'SANTA CRUZ DE TOLED', '06', '58');
INSERT INTO `distrito` VALUES ('1547', 'TANTARICA', '07', '58');
INSERT INTO `distrito` VALUES ('1548', 'YONAN', '08', '58');
INSERT INTO `distrito` VALUES ('1549', 'CUTERVO', '01', '59');
INSERT INTO `distrito` VALUES ('1550', 'CALLAYUC', '02', '59');
INSERT INTO `distrito` VALUES ('1551', 'CHOROS', '03', '59');
INSERT INTO `distrito` VALUES ('1552', 'CUJILLO', '04', '59');
INSERT INTO `distrito` VALUES ('1553', 'LA RAMADA', '05', '59');
INSERT INTO `distrito` VALUES ('1554', 'PIMPINGOS', '06', '59');
INSERT INTO `distrito` VALUES ('1555', 'QUEROCOTILLO', '07', '59');
INSERT INTO `distrito` VALUES ('1556', 'SAN ANDRES DE CUTERVO', '08', '59');
INSERT INTO `distrito` VALUES ('1557', 'SAN JUAN DE CUTERVO', '09', '59');
INSERT INTO `distrito` VALUES ('1558', 'SAN LUIS DE LUCMA', '10', '59');
INSERT INTO `distrito` VALUES ('1559', 'SANTA CRUZ', '11', '59');
INSERT INTO `distrito` VALUES ('1560', 'SANTO DOMINGO DE LA CAPILLA', '12', '59');
INSERT INTO `distrito` VALUES ('1561', 'SANTO TOMAS', '13', '59');
INSERT INTO `distrito` VALUES ('1562', 'SOCOTA', '14', '59');
INSERT INTO `distrito` VALUES ('1563', 'TORIBIO CASANOVA', '15', '59');
INSERT INTO `distrito` VALUES ('1564', 'BAMBAMARCA', '01', '60');
INSERT INTO `distrito` VALUES ('1565', 'CHUGUR', '02', '60');
INSERT INTO `distrito` VALUES ('1566', 'HUALGAYOC', '03', '60');
INSERT INTO `distrito` VALUES ('1567', 'JAEN', '01', '61');
INSERT INTO `distrito` VALUES ('1568', 'BELLAVISTA', '02', '61');
INSERT INTO `distrito` VALUES ('1569', 'CHONTALI', '03', '61');
INSERT INTO `distrito` VALUES ('1570', 'COLASAY', '04', '61');
INSERT INTO `distrito` VALUES ('1571', 'HUABAL', '05', '61');
INSERT INTO `distrito` VALUES ('1572', 'LAS PIRIAS', '06', '61');
INSERT INTO `distrito` VALUES ('1573', 'POMAHUACA', '07', '61');
INSERT INTO `distrito` VALUES ('1574', 'PUCARA', '08', '61');
INSERT INTO `distrito` VALUES ('1575', 'SALLIQUE', '09', '61');
INSERT INTO `distrito` VALUES ('1576', 'SAN FELIPE', '10', '61');
INSERT INTO `distrito` VALUES ('1577', 'SAN JOSE DEL ALTO', '11', '61');
INSERT INTO `distrito` VALUES ('1578', 'SANTA ROSA', '12', '61');
INSERT INTO `distrito` VALUES ('1579', 'SAN IGNACIO', '01', '62');
INSERT INTO `distrito` VALUES ('1580', 'CHIRINOS', '02', '62');
INSERT INTO `distrito` VALUES ('1581', 'HUARANGO', '03', '62');
INSERT INTO `distrito` VALUES ('1582', 'LA COIPA', '04', '62');
INSERT INTO `distrito` VALUES ('1583', 'NAMBALLE', '05', '62');
INSERT INTO `distrito` VALUES ('1584', 'SAN JOSE DE LOURDES', '06', '62');
INSERT INTO `distrito` VALUES ('1585', 'TABACONAS', '07', '62');
INSERT INTO `distrito` VALUES ('1586', 'PEDRO GALVEZ', '01', '63');
INSERT INTO `distrito` VALUES ('1587', 'CHANCAY', '02', '63');
INSERT INTO `distrito` VALUES ('1588', 'EDUARDO VILLANUEVA', '03', '63');
INSERT INTO `distrito` VALUES ('1589', 'GREGORIO PITA', '04', '63');
INSERT INTO `distrito` VALUES ('1590', 'ICHOCAN', '05', '63');
INSERT INTO `distrito` VALUES ('1591', 'JOSE MANUEL QUIROZ', '06', '63');
INSERT INTO `distrito` VALUES ('1592', 'JOSE SABOGAL', '07', '63');
INSERT INTO `distrito` VALUES ('1593', 'SAN MIGUEL', '01', '64');
INSERT INTO `distrito` VALUES ('1594', 'BOLIVAR', '02', '64');
INSERT INTO `distrito` VALUES ('1595', 'CALQUIS', '03', '64');
INSERT INTO `distrito` VALUES ('1596', 'CATILLUC', '04', '64');
INSERT INTO `distrito` VALUES ('1597', 'EL PRADO', '05', '64');
INSERT INTO `distrito` VALUES ('1598', 'LA FLORIDA', '06', '64');
INSERT INTO `distrito` VALUES ('1599', 'LLAPA', '07', '64');
INSERT INTO `distrito` VALUES ('1600', 'NANCHOC', '08', '64');
INSERT INTO `distrito` VALUES ('1601', 'NIEPOS', '09', '64');
INSERT INTO `distrito` VALUES ('1602', 'SAN GREGORIO', '10', '64');
INSERT INTO `distrito` VALUES ('1603', 'SAN SILVESTRE DE COCHAN', '11', '64');
INSERT INTO `distrito` VALUES ('1604', 'TONGOD', '12', '64');
INSERT INTO `distrito` VALUES ('1605', 'UNION AGUA BLANCA', '13', '64');
INSERT INTO `distrito` VALUES ('1606', 'SAN PABLO', '01', '65');
INSERT INTO `distrito` VALUES ('1607', 'SAN BERNARDINO', '02', '65');
INSERT INTO `distrito` VALUES ('1608', 'SAN LUIS', '03', '65');
INSERT INTO `distrito` VALUES ('1609', 'TUMBADEN', '04', '65');
INSERT INTO `distrito` VALUES ('1610', 'SANTA CRUZ', '01', '66');
INSERT INTO `distrito` VALUES ('1611', 'ANDABAMBA', '02', '66');
INSERT INTO `distrito` VALUES ('1612', 'CATACHE', '03', '66');
INSERT INTO `distrito` VALUES ('1613', 'CHANCAYBA?OS', '04', '66');
INSERT INTO `distrito` VALUES ('1614', 'LA ESPERANZA', '05', '66');
INSERT INTO `distrito` VALUES ('1615', 'NINABAMBA', '06', '66');
INSERT INTO `distrito` VALUES ('1616', 'PULAN', '07', '66');
INSERT INTO `distrito` VALUES ('1617', 'SAUCEPAMPA', '08', '66');
INSERT INTO `distrito` VALUES ('1618', 'SEXI', '09', '66');
INSERT INTO `distrito` VALUES ('1619', 'UTICYACU', '10', '66');
INSERT INTO `distrito` VALUES ('1620', 'YAUYUCAN', '11', '66');
INSERT INTO `distrito` VALUES ('1621', 'CALLAO', '01', '67');
INSERT INTO `distrito` VALUES ('1622', 'BELLAVISTA', '02', '67');
INSERT INTO `distrito` VALUES ('1623', 'CARMEN DE LA LEGUA REYNOSO', '03', '67');
INSERT INTO `distrito` VALUES ('1624', 'LA PERLA', '04', '67');
INSERT INTO `distrito` VALUES ('1625', 'LA PUNTA', '05', '67');
INSERT INTO `distrito` VALUES ('1626', 'VENTANILLA', '06', '67');
INSERT INTO `distrito` VALUES ('1627', 'CUSCO', '01', '68');
INSERT INTO `distrito` VALUES ('1628', 'CCORCA', '02', '68');
INSERT INTO `distrito` VALUES ('1629', 'POROY', '03', '68');
INSERT INTO `distrito` VALUES ('1630', 'SAN JERONIMO', '04', '68');
INSERT INTO `distrito` VALUES ('1631', 'SAN SEBASTIAN', '05', '68');
INSERT INTO `distrito` VALUES ('1632', 'SANTIAGO', '06', '68');
INSERT INTO `distrito` VALUES ('1633', 'SAYLLA', '07', '68');
INSERT INTO `distrito` VALUES ('1634', 'WANCHAQ', '08', '68');
INSERT INTO `distrito` VALUES ('1635', 'ACOMAYO', '01', '69');
INSERT INTO `distrito` VALUES ('1636', 'ACOPIA', '02', '69');
INSERT INTO `distrito` VALUES ('1637', 'ACOS', '03', '69');
INSERT INTO `distrito` VALUES ('1638', 'MOSOC LLACTA', '04', '69');
INSERT INTO `distrito` VALUES ('1639', 'POMACANCHI', '05', '69');
INSERT INTO `distrito` VALUES ('1640', 'RONDOCAN', '06', '69');
INSERT INTO `distrito` VALUES ('1641', 'SANGARARA', '07', '69');
INSERT INTO `distrito` VALUES ('1642', 'ANTA', '01', '70');
INSERT INTO `distrito` VALUES ('1643', 'ANCAHUASI', '02', '70');
INSERT INTO `distrito` VALUES ('1644', 'CACHIMAYO', '03', '70');
INSERT INTO `distrito` VALUES ('1645', 'CHINCHAYPUJIO', '04', '70');
INSERT INTO `distrito` VALUES ('1646', 'HUAROCONDO', '05', '70');
INSERT INTO `distrito` VALUES ('1647', 'LIMATAMBO', '06', '70');
INSERT INTO `distrito` VALUES ('1648', 'MOLLEPATA', '07', '70');
INSERT INTO `distrito` VALUES ('1649', 'PUCYURA', '08', '70');
INSERT INTO `distrito` VALUES ('1650', 'ZURITE', '09', '70');
INSERT INTO `distrito` VALUES ('1651', 'CALCA', '01', '71');
INSERT INTO `distrito` VALUES ('1652', 'COYA', '02', '71');
INSERT INTO `distrito` VALUES ('1653', 'LAMAY', '03', '71');
INSERT INTO `distrito` VALUES ('1654', 'LARES', '04', '71');
INSERT INTO `distrito` VALUES ('1655', 'PISAC', '05', '71');
INSERT INTO `distrito` VALUES ('1656', 'SAN SALVADOR', '06', '71');
INSERT INTO `distrito` VALUES ('1657', 'TARAY', '07', '71');
INSERT INTO `distrito` VALUES ('1658', 'YANATILE', '08', '71');
INSERT INTO `distrito` VALUES ('1659', 'YANAOCA', '01', '72');
INSERT INTO `distrito` VALUES ('1660', 'CHECCA', '02', '72');
INSERT INTO `distrito` VALUES ('1661', 'KUNTURKANKI', '03', '72');
INSERT INTO `distrito` VALUES ('1662', 'LANGUI', '04', '72');
INSERT INTO `distrito` VALUES ('1663', 'LAYO', '05', '72');
INSERT INTO `distrito` VALUES ('1664', 'PAMPAMARCA', '06', '72');
INSERT INTO `distrito` VALUES ('1665', 'QUEHUE', '07', '72');
INSERT INTO `distrito` VALUES ('1666', 'TUPAC AMARU', '08', '72');
INSERT INTO `distrito` VALUES ('1667', 'SICUANI', '01', '73');
INSERT INTO `distrito` VALUES ('1668', 'CHECACUPE', '02', '73');
INSERT INTO `distrito` VALUES ('1669', 'COMBAPATA', '03', '73');
INSERT INTO `distrito` VALUES ('1670', 'MARANGANI', '04', '73');
INSERT INTO `distrito` VALUES ('1671', 'PITUMARCA', '05', '73');
INSERT INTO `distrito` VALUES ('1672', 'SAN PABLO', '06', '73');
INSERT INTO `distrito` VALUES ('1673', 'SAN PEDRO', '07', '73');
INSERT INTO `distrito` VALUES ('1674', 'TINTA', '08', '73');
INSERT INTO `distrito` VALUES ('1675', 'SANTO TOMAS', '01', '74');
INSERT INTO `distrito` VALUES ('1676', 'CAPACMARCA', '02', '74');
INSERT INTO `distrito` VALUES ('1677', 'CHAMACA', '03', '74');
INSERT INTO `distrito` VALUES ('1678', 'COLQUEMARCA', '04', '74');
INSERT INTO `distrito` VALUES ('1679', 'LIVITACA', '05', '74');
INSERT INTO `distrito` VALUES ('1680', 'LLUSCO', '06', '74');
INSERT INTO `distrito` VALUES ('1681', 'QUI?OTA', '07', '74');
INSERT INTO `distrito` VALUES ('1682', 'VELILLE', '08', '74');
INSERT INTO `distrito` VALUES ('1683', 'ESPINAR', '01', '75');
INSERT INTO `distrito` VALUES ('1684', 'CONDOROMA', '02', '75');
INSERT INTO `distrito` VALUES ('1685', 'COPORAQUE', '03', '75');
INSERT INTO `distrito` VALUES ('1686', 'OCORURO', '04', '75');
INSERT INTO `distrito` VALUES ('1687', 'PALLPATA', '05', '75');
INSERT INTO `distrito` VALUES ('1688', 'PICHIGUA', '06', '75');
INSERT INTO `distrito` VALUES ('1689', 'SUYCKUTAMBO', '07', '75');
INSERT INTO `distrito` VALUES ('1690', 'ALTO PICHIGUA', '08', '75');
INSERT INTO `distrito` VALUES ('1691', 'SANTA ANA', '01', '76');
INSERT INTO `distrito` VALUES ('1692', 'ECHARATE', '02', '76');
INSERT INTO `distrito` VALUES ('1693', 'HUAYOPATA', '03', '76');
INSERT INTO `distrito` VALUES ('1694', 'MARANURA', '04', '76');
INSERT INTO `distrito` VALUES ('1695', 'OCOBAMBA', '05', '76');
INSERT INTO `distrito` VALUES ('1696', 'QUELLOUNO', '06', '76');
INSERT INTO `distrito` VALUES ('1697', 'KIMBIRI', '07', '76');
INSERT INTO `distrito` VALUES ('1698', 'SANTA TERESA', '08', '76');
INSERT INTO `distrito` VALUES ('1699', 'VILCABAMBA', '09', '76');
INSERT INTO `distrito` VALUES ('1700', 'PICHARI', '10', '76');
INSERT INTO `distrito` VALUES ('1701', 'PARURO', '01', '77');
INSERT INTO `distrito` VALUES ('1702', 'ACCHA', '02', '77');
INSERT INTO `distrito` VALUES ('1703', 'CCAPI', '03', '77');
INSERT INTO `distrito` VALUES ('1704', 'COLCHA', '04', '77');
INSERT INTO `distrito` VALUES ('1705', 'HUANOQUITE', '05', '77');
INSERT INTO `distrito` VALUES ('1706', 'OMACHA', '06', '77');
INSERT INTO `distrito` VALUES ('1707', 'PACCARITAMBO', '07', '77');
INSERT INTO `distrito` VALUES ('1708', 'PILLPINTO', '08', '77');
INSERT INTO `distrito` VALUES ('1709', 'YAURISQUE', '09', '77');
INSERT INTO `distrito` VALUES ('1710', 'PAUCARTAMBO', '01', '78');
INSERT INTO `distrito` VALUES ('1711', 'CAICAY', '02', '78');
INSERT INTO `distrito` VALUES ('1712', 'CHALLABAMBA', '03', '78');
INSERT INTO `distrito` VALUES ('1713', 'COLQUEPATA', '04', '78');
INSERT INTO `distrito` VALUES ('1714', 'HUANCARANI', '05', '78');
INSERT INTO `distrito` VALUES ('1715', 'KOS?IPATA', '06', '78');
INSERT INTO `distrito` VALUES ('1716', 'URCOS', '01', '79');
INSERT INTO `distrito` VALUES ('1717', 'ANDAHUAYLILLAS', '02', '79');
INSERT INTO `distrito` VALUES ('1718', 'CAMANTI', '03', '79');
INSERT INTO `distrito` VALUES ('1719', 'CCARHUAYO', '04', '79');
INSERT INTO `distrito` VALUES ('1720', 'CCATCA', '05', '79');
INSERT INTO `distrito` VALUES ('1721', 'CUSIPATA', '06', '79');
INSERT INTO `distrito` VALUES ('1722', 'HUARO', '07', '79');
INSERT INTO `distrito` VALUES ('1723', 'LUCRE', '08', '79');
INSERT INTO `distrito` VALUES ('1724', 'MARCAPATA', '09', '79');
INSERT INTO `distrito` VALUES ('1725', 'OCONGATE', '10', '79');
INSERT INTO `distrito` VALUES ('1726', 'OROPESA', '11', '79');
INSERT INTO `distrito` VALUES ('1727', 'QUIQUIJANA', '12', '79');
INSERT INTO `distrito` VALUES ('1728', 'URUBAMBA', '01', '80');
INSERT INTO `distrito` VALUES ('1729', 'CHINCHERO', '02', '80');
INSERT INTO `distrito` VALUES ('1730', 'HUAYLLABAMBA', '03', '80');
INSERT INTO `distrito` VALUES ('1731', 'MACHUPICCHU', '04', '80');
INSERT INTO `distrito` VALUES ('1732', 'MARAS', '05', '80');
INSERT INTO `distrito` VALUES ('1733', 'OLLANTAYTAMBO', '06', '80');
INSERT INTO `distrito` VALUES ('1734', 'YUCAY', '07', '80');
INSERT INTO `distrito` VALUES ('1735', 'HUANCAVELICA', '01', '81');
INSERT INTO `distrito` VALUES ('1736', 'ACOBAMBILLA', '02', '81');
INSERT INTO `distrito` VALUES ('1737', 'ACORIA', '03', '81');
INSERT INTO `distrito` VALUES ('1738', 'CONAYCA', '04', '81');
INSERT INTO `distrito` VALUES ('1739', 'CUENCA', '05', '81');
INSERT INTO `distrito` VALUES ('1740', 'HUACHOCOLPA', '06', '81');
INSERT INTO `distrito` VALUES ('1741', 'HUAYLLAHUARA', '07', '81');
INSERT INTO `distrito` VALUES ('1742', 'IZCUCHACA', '08', '81');
INSERT INTO `distrito` VALUES ('1743', 'LARIA', '09', '81');
INSERT INTO `distrito` VALUES ('1744', 'MANTA', '10', '81');
INSERT INTO `distrito` VALUES ('1745', 'MARISCAL CACERES', '11', '81');
INSERT INTO `distrito` VALUES ('1746', 'MOYA', '12', '81');
INSERT INTO `distrito` VALUES ('1747', 'NUEVO OCCORO', '13', '81');
INSERT INTO `distrito` VALUES ('1748', 'PALCA', '14', '81');
INSERT INTO `distrito` VALUES ('1749', 'PILCHACA', '15', '81');
INSERT INTO `distrito` VALUES ('1750', 'VILCA', '16', '81');
INSERT INTO `distrito` VALUES ('1751', 'YAULI', '17', '81');
INSERT INTO `distrito` VALUES ('1752', 'ASCENSION', '18', '81');
INSERT INTO `distrito` VALUES ('1753', 'HUANDO', '19', '81');
INSERT INTO `distrito` VALUES ('1754', 'ACOBAMBA', '01', '82');
INSERT INTO `distrito` VALUES ('1755', 'ANDABAMBA', '02', '82');
INSERT INTO `distrito` VALUES ('1756', 'ANTA', '03', '82');
INSERT INTO `distrito` VALUES ('1757', 'CAJA', '04', '82');
INSERT INTO `distrito` VALUES ('1758', 'MARCAS', '05', '82');
INSERT INTO `distrito` VALUES ('1759', 'PAUCARA', '06', '82');
INSERT INTO `distrito` VALUES ('1760', 'POMACOCHA', '07', '82');
INSERT INTO `distrito` VALUES ('1761', 'ROSARIO', '08', '82');
INSERT INTO `distrito` VALUES ('1762', 'LIRCAY', '01', '83');
INSERT INTO `distrito` VALUES ('1763', 'ANCHONGA', '02', '83');
INSERT INTO `distrito` VALUES ('1764', 'CALLANMARCA', '03', '83');
INSERT INTO `distrito` VALUES ('1765', 'CCOCHACCASA', '04', '83');
INSERT INTO `distrito` VALUES ('1766', 'CHINCHO', '05', '83');
INSERT INTO `distrito` VALUES ('1767', 'CONGALLA', '06', '83');
INSERT INTO `distrito` VALUES ('1768', 'HUANCA-HUANCA', '07', '83');
INSERT INTO `distrito` VALUES ('1769', 'HUAYLLAY GRANDE', '08', '83');
INSERT INTO `distrito` VALUES ('1770', 'JULCAMARCA', '09', '83');
INSERT INTO `distrito` VALUES ('1771', 'SAN ANTONIO DE ANTAPARCO', '10', '83');
INSERT INTO `distrito` VALUES ('1772', 'SANTO TOMAS DE PATA', '11', '83');
INSERT INTO `distrito` VALUES ('1773', 'SECCLLA', '12', '83');
INSERT INTO `distrito` VALUES ('1774', 'CASTROVIRREYNA', '01', '84');
INSERT INTO `distrito` VALUES ('1775', 'ARMA', '02', '84');
INSERT INTO `distrito` VALUES ('1776', 'AURAHUA', '03', '84');
INSERT INTO `distrito` VALUES ('1777', 'CAPILLAS', '04', '84');
INSERT INTO `distrito` VALUES ('1778', 'CHUPAMARCA', '05', '84');
INSERT INTO `distrito` VALUES ('1779', 'COCAS', '06', '84');
INSERT INTO `distrito` VALUES ('1780', 'HUACHOS', '07', '84');
INSERT INTO `distrito` VALUES ('1781', 'HUAMATAMBO', '08', '84');
INSERT INTO `distrito` VALUES ('1782', 'MOLLEPAMPA', '09', '84');
INSERT INTO `distrito` VALUES ('1783', 'SAN JUAN', '10', '84');
INSERT INTO `distrito` VALUES ('1784', 'SANTA ANA', '11', '84');
INSERT INTO `distrito` VALUES ('1785', 'TANTARA', '12', '84');
INSERT INTO `distrito` VALUES ('1786', 'TICRAPO', '13', '84');
INSERT INTO `distrito` VALUES ('1787', 'CHURCAMPA', '01', '85');
INSERT INTO `distrito` VALUES ('1788', 'ANCO', '02', '85');
INSERT INTO `distrito` VALUES ('1789', 'CHINCHIHUASI', '03', '85');
INSERT INTO `distrito` VALUES ('1790', 'EL CARMEN', '04', '85');
INSERT INTO `distrito` VALUES ('1791', 'LA MERCED', '05', '85');
INSERT INTO `distrito` VALUES ('1792', 'LOCROJA', '06', '85');
INSERT INTO `distrito` VALUES ('1793', 'PAUCARBAMBA', '07', '85');
INSERT INTO `distrito` VALUES ('1794', 'SAN MIGUEL DE MAYOCC', '08', '85');
INSERT INTO `distrito` VALUES ('1795', 'SAN PEDRO DE CORIS', '09', '85');
INSERT INTO `distrito` VALUES ('1796', 'PACHAMARCA', '10', '85');
INSERT INTO `distrito` VALUES ('1797', 'HUAYTARA', '01', '86');
INSERT INTO `distrito` VALUES ('1798', 'AYAVI', '02', '86');
INSERT INTO `distrito` VALUES ('1799', 'CORDOVA', '03', '86');
INSERT INTO `distrito` VALUES ('1800', 'HUAYACUNDO ARMA', '04', '86');
INSERT INTO `distrito` VALUES ('1801', 'LARAMARCA', '05', '86');
INSERT INTO `distrito` VALUES ('1802', 'OCOYO', '06', '86');
INSERT INTO `distrito` VALUES ('1803', 'PILPICHACA', '07', '86');
INSERT INTO `distrito` VALUES ('1804', 'QUERCO', '08', '86');
INSERT INTO `distrito` VALUES ('1805', 'QUITO-ARMA', '09', '86');
INSERT INTO `distrito` VALUES ('1806', 'SAN ANTONIO DE CUSICANCHA', '10', '86');
INSERT INTO `distrito` VALUES ('1807', 'SAN FRANCISCO DE SANGAYAICO', '11', '86');
INSERT INTO `distrito` VALUES ('1808', 'SAN ISIDRO', '12', '86');
INSERT INTO `distrito` VALUES ('1809', 'SANTIAGO DE CHOCORVOS', '13', '86');
INSERT INTO `distrito` VALUES ('1810', 'SANTIAGO DE QUIRAHUARA', '14', '86');
INSERT INTO `distrito` VALUES ('1811', 'SANTO DOMINGO DE CAPILLAS', '15', '86');
INSERT INTO `distrito` VALUES ('1812', 'TAMBO', '16', '86');
INSERT INTO `distrito` VALUES ('1813', 'PAMPAS', '01', '87');
INSERT INTO `distrito` VALUES ('1814', 'ACOSTAMBO', '02', '87');
INSERT INTO `distrito` VALUES ('1815', 'ACRAQUIA', '03', '87');
INSERT INTO `distrito` VALUES ('1816', 'AHUAYCHA', '04', '87');
INSERT INTO `distrito` VALUES ('1817', 'COLCABAMBA', '05', '87');
INSERT INTO `distrito` VALUES ('1818', 'DANIEL HERNANDEZ', '06', '87');
INSERT INTO `distrito` VALUES ('1819', 'HUACHOCOLPA', '07', '87');
INSERT INTO `distrito` VALUES ('1820', 'HUARIBAMBA', '09', '87');
INSERT INTO `distrito` VALUES ('1821', '?AHUIMPUQUIO', '10', '87');
INSERT INTO `distrito` VALUES ('1822', 'PAZOS', '11', '87');
INSERT INTO `distrito` VALUES ('1823', 'QUISHUAR', '13', '87');
INSERT INTO `distrito` VALUES ('1824', 'SALCABAMBA', '14', '87');
INSERT INTO `distrito` VALUES ('1825', 'SALCAHUASI', '15', '87');
INSERT INTO `distrito` VALUES ('1826', 'SAN MARCOS DE ROCCHAC', '16', '87');
INSERT INTO `distrito` VALUES ('1827', 'SURCUBAMBA', '17', '87');
INSERT INTO `distrito` VALUES ('1828', 'TINTAY PUNCU', '18', '87');
INSERT INTO `kardex` VALUES ('1', '1', '1', '1', '1', '10.00', 'S', '71.22', '712.20', '2011-01-20 00:00:00', '0.00', '1', '10.00', '10.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('2', '1', '4', '1', '1', '3.00', 'S', '71.22', '213.66', '2011-01-20 00:00:00', '10.00', '1', '3.00', '7.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('3', '1', '6', '1', '1', '3.00', 'S', '71.22', '213.66', '2011-01-20 00:00:00', '7.00', '1', '3.00', '4.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('4', '1', '7', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-20 00:00:00', '4.00', '1', '1.00', '3.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('5', '1', '9', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-20 00:00:00', '3.00', '1', '1.00', '2.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('6', '1', '11', '1', '1', '2.00', 'S', '71.22', '142.44', '2011-01-20 00:00:00', '2.00', '1', '2.00', '0.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('7', '1', '11', '1', '1', '2.00', 'S', '71.22', '142.44', '2011-01-20 11:06:42', '0.00', '1', '2.00', '2.00', '4', 'N', 'A');
INSERT INTO `kardex` VALUES ('8', '1', '12', '1', '1', '1.00', 'S', '62.70', '62.70', '2011-01-20 00:00:00', '2.00', '1', '1.00', '3.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('9', '1', '17', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-24 00:00:00', '3.00', '1', '1.00', '2.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('10', '1', '27', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-24 00:00:00', '2.00', '1', '1.00', '1.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('11', '1', '35', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-25 00:00:00', '1.00', '1', '1.00', '0.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('12', '1', '40', '1', '1', '10.00', 'S', '71.22', '712.20', '2011-01-26 00:00:00', '0.00', '1', '10.00', '10.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('13', '1', '41', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-26 00:00:00', '10.00', '1', '1.00', '11.00', '4', 'N', 'A');
INSERT INTO `kardex` VALUES ('14', '1', '41', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-26 00:00:00', '11.00', '1', '1.00', '12.00', '4', 'N', 'A');
INSERT INTO `kardex` VALUES ('15', '1', '44', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-26 00:00:00', '12.00', '1', '1.00', '11.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('16', '1', '46', '5', '1', '1.00', 'S', '0.70', '0.70', '2011-01-26 00:00:00', '0.00', '1', '1.00', '1.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('17', '1', '51', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-30 00:00:00', '11.00', '1', '1.00', '10.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('18', '1', '53', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-30 00:00:00', '10.00', '1', '1.00', '9.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('19', '1', '53', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-01-30 00:00:00', '9.00', '1', '1.00', '10.00', '4', 'N', 'A');
INSERT INTO `kardex` VALUES ('20', '1', '74', '1', '1', '10.00', 'S', '71.22', '712.20', '2011-03-15 00:00:00', '10.00', '1', '10.00', '20.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('21', '1', '74', '5', '1', '5.00', 'S', '0.70', '3.50', '2011-03-15 00:00:00', '1.00', '1', '5.00', '6.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('22', '1', '79', '1', '1', '10.00', 'S', '62.70', '627.00', '2011-03-15 00:00:00', '20.00', '1', '10.00', '30.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('23', '1', '81', '5', '1', '1.00', 'S', '0.70', '0.70', '2011-03-15 00:00:00', '6.00', '1', '1.00', '5.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('24', '1', '83', '5', '1', '1.00', 'S', '0.70', '0.70', '2011-03-20 00:00:00', '5.00', '1', '1.00', '6.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('25', '1', '84', '5', '1', '10.00', 'S', '0.70', '7.00', '2011-04-13 00:00:00', '6.00', '1', '10.00', '16.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('26', '1', '84', '5', '1', '10.00', 'S', '0.70', '7.00', '2011-04-13 00:00:00', '16.00', '1', '10.00', '6.00', '4', 'N', 'A');
INSERT INTO `kardex` VALUES ('27', '1', '93', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-04-14 00:00:00', '30.00', '1', '1.00', '31.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('28', '1', '93', '5', '1', '2.00', 'S', '0.70', '1.40', '2011-04-14 00:00:00', '6.00', '1', '2.00', '8.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('29', '1', '111', '1', '1', '4.00', 'S', '71.22', '284.88', '2011-04-16 00:00:00', '31.00', '1', '4.00', '35.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('30', '1', '116', '5', '1', '2.00', 'S', '0.70', '1.40', '2011-04-24 00:00:00', '8.00', '1', '2.00', '6.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('31', '1', '118', '8', '1', '1.00', 'S', '27.00', '27.00', '2011-04-25 00:00:00', '0.00', '1', '1.00', '1.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('32', '1', '119', '6', '1', '10.00', 'S', '6.00', '60.00', '2011-04-25 00:00:00', '0.00', '1', '10.00', '-10.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('33', '1', '124', '6', '1', '20.00', 'S', '6.00', '120.00', '2011-04-25 00:00:00', '-10.00', '1', '20.00', '10.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('34', '1', '125', '5', '1', '2.00', 'S', '0.70', '1.40', '2011-04-25 00:00:00', '6.00', '1', '2.00', '4.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('35', '1', '127', '1', '1', '5.00', 'S', '71.22', '356.10', '2011-04-25 00:00:00', '35.00', '1', '5.00', '30.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('36', '1', '129', '3', '1', '6.00', 'D', '1.05', '6.30', '2011-04-25 00:00:00', '0.00', '1', '6.00', '6.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('37', '1', '135', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-04-27 00:00:00', '30.00', '1', '1.00', '29.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('38', '1', '143', '6', '1', '5.00', 'S', '6.00', '30.00', '2011-04-28 00:00:00', '10.00', '1', '5.00', '5.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('39', '1', '147', '6', '1', '2.00', 'S', '6.00', '12.00', '2011-04-28 00:00:00', '5.00', '1', '2.00', '3.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('40', '1', '149', '1', '1', '3.00', 'S', '71.22', '213.66', '2011-04-28 00:00:00', '29.00', '1', '3.00', '26.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('41', '1', '149', '5', '1', '2.00', 'S', '0.70', '1.40', '2011-04-28 00:00:00', '4.00', '1', '2.00', '2.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('42', '1', '150', '1', '1', '2.00', 'S', '71.22', '142.44', '2011-04-28 00:00:00', '26.00', '1', '2.00', '24.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('43', '1', '151', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-04-28 00:00:00', '24.00', '1', '1.00', '23.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('44', '1', '153', '5', '1', '10.00', 'S', '0.70', '7.00', '2011-04-13 00:00:00', '2.00', '1', '10.00', '12.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('45', '1', '154', '9', '1', '4.00', 'S', '55.00', '220.00', '2011-04-27 00:00:00', '0.00', '1', '4.00', '4.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('46', '1', '155', '9', '1', '1.00', 'S', '55.00', '55.00', '2011-04-28 00:00:00', '4.00', '1', '1.00', '5.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('47', '1', '155', '7', '1', '10.00', 'S', '10.00', '100.00', '2011-04-28 00:00:00', '0.00', '1', '10.00', '10.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('48', '1', '156', '2', '1', '3.00', 'S', '1.00', '3.00', '2011-04-28 00:00:00', '0.00', '1', '3.00', '3.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('49', '1', '166', '9', '1', '1.00', 'S', '55.00', '55.00', '2011-05-18 00:00:00', '5.00', '1', '1.00', '4.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('50', '1', '184', '9', '1', '1.00', 'S', '55.00', '55.00', '2011-05-27 00:00:00', '4.00', '1', '1.00', '3.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('51', '1', '184', '7', '1', '1.00', 'S', '10.00', '10.00', '2011-05-27 00:00:00', '10.00', '1', '1.00', '9.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('52', '1', '189', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-05-28 00:00:00', '23.00', '1', '1.00', '22.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('53', '1', '191', '5', '1', '1.00', 'S', '0.70', '0.70', '2011-05-28 00:00:00', '12.00', '1', '1.00', '11.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('54', '1', '192', '3', '1', '1.00', 'S', '4.00', '4.00', '2011-05-28 00:00:00', '6.00', '1', '1.00', '5.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('55', '1', '192', '8', '1', '1.00', 'S', '27.00', '27.00', '2011-05-28 00:00:00', '1.00', '1', '1.00', '0.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('56', '1', '168', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-05-28 16:45:09', '22.00', '1', '1.00', '21.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('57', '1', '94', '1', '1', '2.00', 'S', '71.22', '142.44', '2011-05-28 16:51:53', '21.00', '1', '2.00', '19.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('58', '1', '197', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-05-28 00:00:00', '19.00', '1', '1.00', '18.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('59', '1', '198', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-05-28 00:00:00', '18.00', '1', '1.00', '17.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('60', '1', '202', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-05-28 19:24:49', '17.00', '1', '1.00', '16.00', '4', 'N', 'N');
INSERT INTO `kardex` VALUES ('61', '1', '205', '3', '1', '1.00', 'S', '4.00', '4.00', '2011-05-28 19:58:43', '5.00', '1', '1.00', '4.00', '4', 'S', 'N');
INSERT INTO `kardex` VALUES ('62', '1', '216', '1', '1', '1.00', 'S', '71.22', '71.22', '2011-08-05 00:00:00', '16.00', '1', '1.00', '17.00', '4', 'S', 'N');
INSERT INTO `listaunidad` VALUES ('1', '1', '1', '1', '1', '62.700', '71.220', '68.400', 'S');
INSERT INTO `listaunidad` VALUES ('2', '2', '1', '1', '1', '1.000', '2.000', '1.500', 'S');
INSERT INTO `listaunidad` VALUES ('3', '3', '1', '1', '1', '1.053', '1.404', '1.404', 'D');
INSERT INTO `listaunidad` VALUES ('4', '1', '2', '1', '5', '14.000', '15.000', null, 'S');
INSERT INTO `listaunidad` VALUES ('5', '1', '3', '1', '10', '23.000', '23.000', null, 'S');
INSERT INTO `listaunidad` VALUES ('6', '5', '1', '1', '1', '0.500', '0.700', '0.700', 'S');
INSERT INTO `listaunidad` VALUES ('7', '5', '2', '1', '100', '10.000', '15.000', null, 'S');
INSERT INTO `listaunidad` VALUES ('8', '6', '1', '1', '1', '5.000', '6.000', '6.000', 'S');
INSERT INTO `listaunidad` VALUES ('9', '7', '1', '1', '1', '10.000', '10.000', '10.000', 'S');
INSERT INTO `listaunidad` VALUES ('10', '8', '1', '1', '1', '25.000', '27.000', '26.000', 'S');
INSERT INTO `listaunidad` VALUES ('11', '9', '1', '1', '1', '45.000', '55.000', '52.800', 'S');
INSERT INTO `listaunidad` VALUES ('12', '10', '1', '1', '1', '44.000', '44.000', '44.000', 'S');
INSERT INTO `listaunidad` VALUES ('13', '11', '1', '1', '1', '2.000', '5.000', '4.000', 'S');
INSERT INTO `lugar` VALUES ('11', 'XX', '14');
INSERT INTO `lugar` VALUES ('12', 'YY', '14');
INSERT INTO `lugar` VALUES ('13', 'AV. MARIANO CORNEJO 554 - CHICLAYO', '409');
INSERT INTO `lugar` VALUES ('14', 'ANGAMOS', '409');
INSERT INTO `lugar` VALUES ('15', 'AV. LUIS GONZALES - CHICLAYO', '409');
INSERT INTO `marca` VALUES ('1', 'XX', 'XX', 'N');
INSERT INTO `marca` VALUES ('2', 'YY', 'YY', 'N');
INSERT INTO `marca` VALUES ('3', 'ZZ', 'ZZ', 'N');
INSERT INTO `marca` VALUES ('4', 'AA', 'AA', 'N');
INSERT INTO `modulo` VALUES ('1', 'VENTA', 'V', 'N');
INSERT INTO `modulo` VALUES ('2', 'CAJA', 'C', 'N');
INSERT INTO `modulo` VALUES ('3', 'ALMACEN', 'A', 'N');
INSERT INTO `modulo` VALUES ('4', 'COMPRAS', 'CO', 'N');
INSERT INTO `modulo` VALUES ('5', 'MANTENIMIENTO', 'MA', 'N');
INSERT INTO `motivotraslado` VALUES ('1', 'VENTA');
INSERT INTO `motivotraslado` VALUES ('2', 'VENTA SUJETA A CONFIRMACION DEL COMPRADOR');
INSERT INTO `motivotraslado` VALUES ('3', 'COMPRA');
INSERT INTO `motivotraslado` VALUES ('4', 'CONCIGNACION');
INSERT INTO `motivotraslado` VALUES ('5', 'DEVOLUCION');
INSERT INTO `motivotraslado` VALUES ('6', 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRES');
INSERT INTO `motivotraslado` VALUES ('7', 'TRASLADO DE BIENES PARA TRANSFORMAS');
INSERT INTO `motivotraslado` VALUES ('8', 'RECOJO DE BIENES PARA TRASFORMAR');
INSERT INTO `motivotraslado` VALUES ('9', 'TRASLADO POR EMISOR INTINERANTE DE COMPROBANTES DE');
INSERT INTO `motivotraslado` VALUES ('10', 'TRASLADO ZONA PRIMARIA');
INSERT INTO `motivotraslado` VALUES ('11', 'IMPOTACION');
INSERT INTO `motivotraslado` VALUES ('12', 'EXPORTACION');
INSERT INTO `motivotraslado` VALUES ('13', 'EXHIBICION');
INSERT INTO `motivotraslado` VALUES ('14', 'DEMOTRACION');
INSERT INTO `motivotraslado` VALUES ('15', 'OTROS');
INSERT INTO `movimiento` VALUES ('1', '1', '4', '0', '001-000001-2011', '10', '0', '2011-01-20 00:00:00', 'S', '0.00', '0.00', '712.20', '4', '4', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('2', '1', '3', '1', '001-000001-2011', '10', 'A', '2011-01-20 00:00:00', 'S', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('3', '1', '3', '1', '001-000002-2011', '10', 'A', '2011-01-20 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('4', '1', '2', '3', '001-000001-2011', '1', 'A', '2011-01-20 00:00:00', 'S', '179.55', '34.11', '213.66', '4', '4', '3', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('5', '1', '3', '3', '001-000003-2011', '10', 'A', '2011-01-20 00:00:00', 'S', '179.55', '34.11', '213.66', '4', '4', '3', '0', '0', 'BOL. VENTA Nro.:001-000001-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('6', '1', '2', '3', '0', '1', 'B', '2011-01-20 00:00:00', 'S', '179.55', '34.11', '213.66', '4', '4', '3', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('7', '1', '2', '3', '001-000002-2011', '1', 'A', '2011-01-20 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '4', '3', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('8', '1', '3', '3', '001-000004-2011', '10', 'A', '2011-01-20 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '4', '3', '0', '0', 'BOL. VENTA Nro.:001-000002-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('9', '1', '2', '3', '001-000001-2011', '2', 'A', '2011-01-20 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '4', '3', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('10', '1', '3', '3', '001-000005-2011', '10', 'A', '2011-01-20 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '4', '3', '0', '0', 'FACT. VENTA Nro.:001-000001-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('11', '1', '2', '3', '0', '2', 'B', '2011-01-20 00:00:00', 'S', '119.70', '22.74', '142.44', '4', '4', '3', '0', '0', '', 'A', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('12', '1', '1', '3', '001-099999-2011', '3', 'B', '2011-01-20 00:00:00', 'S', '52.69', '10.01', '62.70', '4', '4', '3', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('13', '1', '3', '2', '001-000001-2011', '11', 'A', '2011-01-20 00:00:00', 'S', '299.24', '56.86', '356.10', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('14', '1', '3', '2', '001-000002-2011', '11', 'A', '2011-01-20 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('15', '1', '3', '1', '001-000006-2011', '10', 'A', '2011-01-24 00:00:00', 'S', '299.24', '56.86', '356.10', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('16', '1', '3', '1', '001-000007-2011', '10', 'A', '2011-01-24 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('17', '1', '2', '3', '001-000003-2011', '1', 'A', '2011-01-24 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('18', '1', '3', '3', '001-000008-2011', '10', 'A', '2011-01-24 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '17', '1', 'BOL. VENTA Nro.:001-000003-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('19', '1', '2', '3', '001-000002-2011', '2', 'A', '2011-01-24 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('20', '1', '3', '3', '001-000009-2011', '10', 'A', '2011-01-24 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '19', '1', 'FACT. VENTA Nro.:001-000002-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('21', '1', '2', '3', '001-000003-2011', '2', 'A', '2011-01-24 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('22', '1', '3', '3', '001-000010-2011', '10', 'A', '2011-01-24 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '21', '1', 'FACT. VENTA Nro.:001-000003-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('27', '1', '2', '3', '001-000001-2011', '5', 'A', '2011-01-24 00:00:00', '0', '0.00', '0.00', '5.00', '4', '3', '4', '21', '1', '', 'N', '2011-01-24', '1', '1', '11', '12');
INSERT INTO `movimiento` VALUES ('28', '1', '3', '2', '001-000003-2011', '11', 'A', '2011-01-24 00:00:00', 'S', '478.79', '90.97', '569.76', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('29', '1', '3', '2', '001-000004-2011', '11', 'A', '2011-01-24 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('30', '1', '3', '1', '001-000011-2011', '10', 'A', '2011-01-25 00:00:00', 'S', '478.79', '90.97', '569.76', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('31', '1', '3', '1', '001-000012-2011', '10', 'A', '2011-01-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('32', '1', '3', '3', '001-000013-2011', '10', 'A', '2011-01-25 00:00:00', 'S', '84.03', '15.97', '100.00', '4', '4', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('33', '1', '2', '3', '001-000004-2011', '2', 'A', '2011-01-25 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('34', '1', '3', '3', '001-000014-2011', '10', 'A', '2011-01-25 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '33', '1', 'FACT. VENTA Nro.:001-000004-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('35', '1', '2', '3', '001-000002-2011', '5', 'A', '2011-01-25 00:00:00', '0', '0.00', '0.00', '5.00', '4', '3', '4', '33', '1', '', 'N', '2011-01-25', '1', '0', '13', '14');
INSERT INTO `movimiento` VALUES ('36', '1', '3', '2', '001-000005-2011', '11', 'A', '2011-01-25 00:00:00', 'S', '622.67', '118.31', '740.98', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-01-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('37', '1', '3', '2', '001-000006-2011', '11', 'A', '2011-01-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-01-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('38', '1', '3', '1', '001-000015-2011', '10', 'A', '2011-01-26 00:00:00', 'S', '622.67', '118.31', '740.98', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-01-26', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('39', '1', '3', '1', '001-000016-2011', '10', 'A', '2011-01-26 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-01-26', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('40', '1', '4', '0', '001-000002-2011', '10', '0', '2011-01-26 00:00:00', 'S', '0.00', '0.00', '712.20', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('41', '1', '2', '3', '0', '2', 'B', '2011-01-26 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '0', '0', '', 'A', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('42', '1', '3', '3', '001-000017-2011', '10', 'B', '2011-01-26 00:00:00', 'S', '0.00', '0.00', '1.22', '4', '3', '4', '41', '1', 'INICIAL DE VENTA AL CREDITO', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('43', '1', '3', '3', '001-000018-2011', '10', 'A', '2011-01-26 00:00:00', 'S', '84.03', '15.97', '100.00', '4', '4', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('44', '1', '2', '3', '001-000004-2011', '1', 'A', '2011-01-26 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('45', '1', '3', '3', '001-000019-2011', '10', 'A', '2011-01-26 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '44', '1', 'BOL. VENTA Nro.:001-000004-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('46', '1', '4', '0', '001-000003-2011', '10', '0', '2011-01-26 00:00:00', 'S', '0.00', '0.00', '0.70', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('47', '1', '3', '2', '001-000007-2011', '11', 'A', '2011-01-26 00:00:00', 'S', '767.58', '145.84', '913.42', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-01-26', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('48', '1', '3', '2', '001-000008-2011', '11', 'A', '2011-01-26 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-01-26', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('49', '1', '3', '1', '001-000020-2011', '10', 'A', '2011-01-30 00:00:00', 'S', '767.58', '145.84', '913.42', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-01-30', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('50', '1', '3', '1', '001-000021-2011', '10', 'A', '2011-01-30 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-01-30', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('51', '1', '2', '3', '001-000005-2011', '1', 'A', '2011-01-30 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('52', '1', '3', '3', '001-000022-2011', '10', 'A', '2011-01-30 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '51', '1', 'BOL. VENTA Nro.:001-000005-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('53', '1', '2', '3', '0', '1', 'B', '2011-01-30 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'A', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('54', '1', '3', '3', '001-000023-2011', '10', 'B', '2011-01-30 00:00:00', 'S', '0.00', '0.00', '1.22', '4', '5', '4', '53', '1', 'Inicial de la Venta', 'A', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('55', '1', '3', '2', '001-000009-2011', '11', 'A', '2011-01-30 00:00:00', 'S', '827.43', '157.21', '984.64', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-01-30', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('56', '1', '3', '2', '001-000010-2011', '11', 'A', '2011-01-30 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-01-30', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('57', '1', '3', '1', '001-000024-2011', '10', 'A', '2011-02-04 00:00:00', 'S', '827.43', '157.21', '984.64', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-02-04', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('58', '1', '3', '1', '001-000025-2011', '10', 'A', '2011-02-04 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-02-04', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('59', '1', '3', '2', '001-000011-2011', '11', 'A', '2011-02-04 00:00:00', 'S', '827.43', '157.21', '984.64', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-02-04', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('60', '1', '3', '2', '001-000012-2011', '11', 'A', '2011-02-04 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-02-04', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('61', '1', '3', '1', '001-000026-2011', '10', 'A', '2011-02-07 00:00:00', 'S', '827.43', '157.21', '984.64', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-02-07', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('62', '1', '3', '1', '001-000027-2011', '10', 'A', '2011-02-07 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-02-07', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('63', '1', '3', '2', '001-000013-2011', '11', 'A', '2011-02-07 00:00:00', 'S', '827.43', '157.21', '984.64', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('64', '1', '3', '2', '001-000014-2011', '11', 'A', '2011-02-07 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('65', '1', '3', '1', '001-000028-2011', '10', 'A', '2011-02-18 00:00:00', 'S', '827.43', '157.21', '984.64', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('66', '1', '3', '1', '001-000029-2011', '10', 'A', '2011-02-18 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('67', '1', '3', '3', '001-000030-2011', '10', 'A', '2011-02-18 00:00:00', 'S', '382.35', '72.65', '455.00', '4', '4', '4', '0', '0', 'OK', 'A', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('68', '1', '3', '3', '001-000031-2011', '10', 'A', '2011-02-18 00:00:00', 'S', '84.03', '15.97', '100.00', '4', '8', '4', '0', '0', 'OK', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('69', '1', '3', '3', '001-000032-2011', '10', 'A', '2011-02-18 00:00:00', 'S', '168.07', '31.93', '200.00', '4', '9', '4', '0', '0', 'OK', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('70', '1', '3', '2', '001-000015-2011', '11', 'A', '2011-02-18 00:00:00', 'S', '1079.53', '205.11', '1284.64', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-02-18', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('71', '1', '3', '2', '001-000016-2011', '11', 'A', '2011-02-18 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-02-18', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('72', '1', '3', '1', '001-000033-2011', '10', 'A', '2011-03-05 00:00:00', 'S', '1079.53', '205.11', '1284.64', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-03-05', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('73', '1', '3', '1', '001-000034-2011', '10', 'A', '2011-03-05 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-03-05', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('74', '1', '4', '0', '001-000004-2011', '10', '0', '2011-03-15 00:00:00', 'S', '0.00', '0.00', '715.70', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('75', '1', '3', '2', '001-000017-2011', '11', 'A', '2011-03-05 00:00:00', 'S', '1079.53', '205.11', '1284.64', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-03-05', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('76', '1', '3', '2', '001-000018-2011', '11', 'A', '2011-03-05 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-03-05', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('77', '1', '3', '1', '001-000035-2011', '10', 'A', '2011-03-15 00:00:00', 'S', '1079.53', '205.11', '1284.64', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-03-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('78', '1', '3', '1', '001-000036-2011', '10', 'A', '2011-03-15 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-03-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('79', '1', '1', '3', '001-000100-2011', '3', 'A', '2011-03-15 00:00:00', 'S', '526.89', '100.11', '627.00', '4', '4', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('80', '1', '3', '4', '001-000019-2011', '11', 'A', '2011-03-15 00:00:00', 'S', '526.89', '100.11', '627.00', '4', '4', '4', '79', '1', 'Compra: 001-000100-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('81', '1', '2', '3', '001-000006-2011', '1', 'A', '2011-03-15 00:00:00', 'S', '0.59', '0.11', '0.70', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('82', '1', '3', '3', '001-000037-2011', '10', 'A', '2011-03-15 00:00:00', 'S', '0.59', '0.11', '0.70', '4', '5', '4', '81', '1', 'BOL. VENTA Nro.:001-000006-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('83', '1', '4', '0', '001-000005-2011', '10', '0', '2011-03-20 00:00:00', 'S', '0.00', '0.00', '0.70', '4', '4', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('84', '1', '4', '0', '001-000006-2011', '10', '0', '2011-04-13 00:00:00', 'S', '0.00', '0.00', '7.00', '4', '3', '4', '0', '0', '', 'A', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('85', '1', '3', '2', '001-000020-2011', '11', 'A', '2011-03-15 00:00:00', 'S', '553.23', '105.11', '658.34', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-03-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('86', '1', '3', '2', '001-000021-2011', '11', 'A', '2011-03-15 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-03-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('87', '1', '3', '1', '001-000038-2011', '10', 'A', '2011-04-13 00:00:00', 'S', '553.23', '105.11', '658.34', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-13', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('88', '1', '3', '1', '001-000039-2011', '10', 'A', '2011-04-13 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-13', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('89', '1', '3', '2', '001-000022-2011', '11', 'A', '2011-04-13 00:00:00', 'S', '553.23', '105.11', '658.34', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('90', '1', '3', '2', '001-000023-2011', '11', 'A', '2011-04-13 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('91', '1', '3', '1', '001-000040-2011', '10', 'A', '2011-04-14 00:00:00', 'S', '553.23', '105.11', '658.34', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('92', '1', '3', '1', '001-000041-2011', '10', 'A', '2011-04-14 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('93', '1', '4', '0', '001-000007-2011', '10', '0', '2011-04-14 00:00:00', 'S', '0.00', '0.00', '72.62', '4', '19', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('94', '1', '2', '3', '001-000005-2011', '2', 'A', '2011-04-14 00:00:00', 'S', '119.70', '22.74', '142.44', '4', '19', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('95', '1', '3', '3', '001-000042-2011', '10', 'A', '2011-04-14 00:00:00', 'S', '119.70', '22.74', '142.44', '4', '19', '4', '94', '1', 'FACT. VENTA Nro.:001-000005-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('96', '1', '3', '2', '001-000024-2011', '11', 'A', '2011-04-14 00:00:00', 'S', '672.92', '127.86', '800.78', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-14', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('97', '1', '3', '2', '001-000025-2011', '11', 'A', '2011-04-14 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-14', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('98', '1', '3', '1', '001-000043-2011', '10', 'A', '2011-04-15 00:00:00', 'S', '672.92', '127.86', '800.78', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('99', '1', '3', '1', '001-000044-2011', '10', 'A', '2011-04-15 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('100', '1', '3', '2', '001-000026-2011', '11', 'A', '2011-04-15 00:00:00', 'S', '672.92', '127.86', '800.78', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('101', '1', '3', '2', '001-000027-2011', '11', 'A', '2011-04-15 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-15', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('102', '1', '3', '1', '001-000045-2011', '10', 'A', '2011-04-16 00:00:00', 'S', '672.92', '127.86', '800.78', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-16', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('103', '1', '3', '1', '001-000046-2011', '10', 'A', '2011-04-16 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-16', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('104', '1', '2', '3', '001-000006-2012', '2', 'A', '2011-04-16 00:00:00', 'S', '119.70', '22.74', '142.44', '4', '21', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('105', '1', '3', '3', '001-000047-2011', '10', 'A', '2011-04-16 00:00:00', 'S', '119.70', '22.74', '142.44', '4', '21', '4', '104', '1', 'FACT. VENTA Nro.:001-000006-2012', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('106', '1', '3', '3', '001-000048-2011', '10', 'A', '2011-04-16 00:00:00', 'S', '168.07', '31.93', '200.00', '4', '21', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('107', '1', '3', '2', '001-000028-2011', '11', 'A', '2011-04-16 00:00:00', 'S', '960.69', '182.53', '1143.22', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('108', '1', '3', '2', '001-000029-2011', '11', 'A', '2011-04-16 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('109', '1', '3', '1', '001-000049-2011', '10', 'A', '2011-04-17 00:00:00', 'S', '960.69', '182.53', '1143.22', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('110', '1', '3', '1', '001-000050-2011', '10', 'A', '2011-04-17 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('111', '1', '4', '0', '001-000008-2011', '10', '0', '2011-04-16 00:00:00', 'S', '0.00', '0.00', '284.88', '4', '21', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('112', '1', '3', '2', '001-000030-2011', '11', 'A', '2011-04-17 00:00:00', 'S', '960.69', '182.53', '1143.22', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-17', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('113', '1', '3', '2', '001-000031-2011', '11', 'A', '2011-04-17 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-17', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('114', '1', '3', '1', '001-000051-2011', '10', 'A', '2011-04-24 00:00:00', 'S', '960.69', '182.53', '1143.22', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('115', '1', '3', '1', '001-000052-2011', '10', 'A', '2011-04-24 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('116', '1', '2', '3', '001-000007-2014', '1', 'A', '2011-04-24 00:00:00', 'S', '1.18', '0.22', '1.40', '4', '22', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('117', '1', '3', '3', '001-000053-2011', '10', 'A', '2011-04-24 00:00:00', 'S', '1.18', '0.22', '1.40', '4', '22', '4', '116', '1', 'BOL. VENTA Nro.:001-000007-2014', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('118', '1', '4', '0', '001-000009-2011', '10', '0', '2011-04-25 00:00:00', 'S', '0.00', '0.00', '27.00', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('119', '1', '4', '0', '001-000001-2011', '11', '0', '2011-04-25 00:00:00', 'S', '0.00', '0.00', '60.00', '4', '22', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('120', '1', '3', '2', '001-000032-2011', '11', 'A', '2011-04-24 00:00:00', 'S', '961.87', '182.75', '1144.62', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('121', '1', '3', '2', '001-000033-2011', '11', 'A', '2011-04-24 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('122', '1', '3', '1', '001-000054-2011', '10', 'A', '2011-04-25 00:00:00', 'S', '961.87', '182.75', '1144.62', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('123', '1', '3', '1', '001-000055-2011', '10', 'A', '2011-04-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('124', '1', '4', '0', '001-000010-2011', '10', '0', '2011-04-25 00:00:00', 'S', '0.00', '0.00', '120.00', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('125', '1', '2', '3', '001-000001-2015', '1', 'A', '2011-04-25 00:00:00', 'S', '1.18', '0.22', '1.40', '4', '21', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('126', '1', '3', '3', '001-000056-2011', '10', 'A', '2011-04-25 00:00:00', 'S', '1.18', '0.22', '1.40', '4', '21', '4', '125', '1', 'BOL. VENTA Nro.:001-000001-2015', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('127', '1', '2', '3', '001-000001-2011', '1', 'A', '2011-04-25 00:00:00', 'S', '299.24', '56.86', '356.10', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('128', '1', '3', '3', '001-000057-2011', '10', 'A', '2011-04-25 00:00:00', 'S', '299.24', '56.86', '356.10', '4', '5', '4', '127', '1', 'BOL. VENTA Nro.:001-000001-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('129', '1', '1', '3', '009-0001', '4', 'B', '2011-04-25 00:00:00', 'D', '5.31', '1.01', '6.32', '4', '20', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('130', '1', '3', '3', '001-000058-2011', '10', 'A', '2011-04-25 00:00:00', 'S', '193.28', '36.72', '230.00', '4', '20', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('131', '1', '3', '2', '001-000034-2011', '11', 'A', '2011-04-25 00:00:00', 'S', '1455.56', '276.56', '1732.12', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('132', '1', '3', '2', '001-000035-2011', '11', 'A', '2011-04-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('133', '1', '3', '1', '001-000059-2011', '10', 'A', '2011-04-27 00:00:00', 'S', '1455.56', '276.56', '1732.12', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('134', '1', '3', '1', '001-000060-2011', '10', 'A', '2011-04-27 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('135', '1', '2', '3', '001-000002-2011', '1', 'A', '2011-04-27 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('136', '1', '3', '3', '001-000061-2011', '10', 'A', '2011-04-27 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '135', '1', 'BOL. VENTA Nro.:001-000002-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('137', '1', '3', '3', '001-000062-2011', '10', 'A', '2011-04-27 00:00:00', 'S', '466.39', '88.61', '555.00', '4', '3', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('138', '1', '3', '3', '001-000063-2011', '10', 'A', '2011-04-27 00:00:00', 'S', '58.82', '11.18', '70.00', '4', '3', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('139', '1', '3', '2', '001-000036-2011', '11', 'A', '2011-04-27 00:00:00', 'S', '2040.62', '387.72', '2428.34', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('140', '1', '3', '2', '001-000037-2011', '11', 'A', '2011-04-27 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('141', '1', '3', '1', '001-000064-2011', '10', 'A', '2011-04-28 00:00:00', 'S', '2040.62', '387.72', '2428.34', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-04-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('142', '1', '3', '1', '001-000065-2011', '10', 'A', '2011-04-28 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-04-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('143', '1', '2', '3', '001-000003-2011', '1', 'A', '2011-04-28 00:00:00', 'S', '25.21', '4.79', '30.00', '4', '23', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('144', '1', '3', '3', '001-000066-2011', '10', 'A', '2011-04-28 00:00:00', 'S', '25.21', '4.79', '30.00', '4', '23', '4', '143', '1', 'BOL. VENTA Nro.:001-000003-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('145', '1', '2', '3', '001-000001-2010', '2', 'A', '2011-04-28 00:00:00', 'S', '180.72', '34.34', '215.06', '4', '19', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('146', '1', '3', '3', '001-000067-2011', '10', 'A', '2011-04-28 00:00:00', 'S', '180.72', '34.34', '215.06', '4', '19', '4', '145', '1', 'FACT. VENTA Nro.:001-000001-2010', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('147', '1', '2', '3', '001-000001-2011', '12', 'A', '2011-04-28 00:00:00', 'S', '10.08', '1.92', '12.00', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('148', '1', '3', '3', '001-000068-2011', '10', 'A', '2011-04-28 00:00:00', 'S', '10.08', '1.92', '12.00', '4', '5', '4', '147', '1', 'RECIBO VENTA Nro.:001-000001-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('149', '1', '2', '3', '001-000003-2011', '5', 'A', '2011-04-28 00:00:00', '0', '400.00', '0.00', '15.00', '4', '19', '4', '145', '1', '', 'N', '2011-04-28', '1', '0', '15', '14');
INSERT INTO `movimiento` VALUES ('150', '1', '2', '3', '001-000004-2011', '5', 'A', '2011-04-28 00:00:00', '0', '0.00', '0.00', '10.00', '4', '21', '4', '104', '1', '', 'N', '2011-04-28', '1', '0', '15', '14');
INSERT INTO `movimiento` VALUES ('151', '1', '2', '3', '001-000005-2011', '5', 'A', '2011-04-28 00:00:00', '0', '0.00', '0.00', '5.00', '4', '5', '4', '19', '1', '', 'N', '2011-04-28', '1', '0', '15', '14');
INSERT INTO `movimiento` VALUES ('152', '1', '3', '3', '001-000069-2011', '10', 'A', '2011-04-28 00:00:00', 'S', '738.66', '140.34', '879.00', '4', '3', '4', '0', '0', '--', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('153', '1', '4', '0', '001-000011-2011', '10', '0', '2011-04-13 00:00:00', 'S', '0.00', '0.00', '7.00', '4', '3', '4', '84', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('154', '1', '4', '0', '001-000012-2011', '10', '0', '2011-04-27 00:00:00', 'S', '0.00', '0.00', '220.00', '4', '22', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('155', '1', '4', '0', '001-000013-2011', '10', '0', '2011-04-28 00:00:00', 'S', '0.00', '0.00', '155.00', '4', '20', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('156', '1', '1', '3', '001-009', '4', 'A', '2011-04-28 00:00:00', 'S', '2.52', '0.48', '3.00', '4', '20', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('157', '1', '3', '4', '001-000038-2011', '11', 'A', '2011-04-28 00:00:00', 'S', '2.52', '0.48', '3.00', '4', '20', '4', '156', '1', 'Compra: 001-009', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('158', '1', '3', '2', '001-000039-2011', '11', 'A', '2011-04-28 00:00:00', 'S', '2992.77', '568.63', '3561.40', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-04-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('159', '1', '3', '2', '001-000040-2011', '11', 'A', '2011-04-28 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-04-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('160', '1', '3', '1', '001-000070-2011', '10', 'A', '2011-05-03 00:00:00', 'S', '2992.77', '568.63', '3561.40', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-05-03', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('161', '1', '3', '1', '001-000071-2011', '10', 'A', '2011-05-03 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-05-03', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('162', '1', '3', '2', '001-000041-2011', '11', 'A', '2011-05-03 00:00:00', 'S', '2992.77', '568.63', '3561.40', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-05-03', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('163', '1', '3', '2', '001-000042-2011', '11', 'A', '2011-05-03 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-05-03', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('164', '1', '3', '1', '001-000072-2011', '10', 'A', '2011-05-18 00:00:00', 'S', '2992.77', '568.63', '3561.40', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-05-18', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('165', '1', '3', '1', '001-000073-2011', '10', 'A', '2011-05-18 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-05-18', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('166', '1', '2', '3', '001-000004-2011', '1', 'A', '2011-05-18 00:00:00', 'S', '46.22', '8.78', '55.00', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('167', '1', '3', '3', '001-000074-2011', '10', 'A', '2011-05-18 00:00:00', 'S', '46.22', '8.78', '55.00', '4', '5', '4', '166', '1', 'BOL. VENTA Nro.:001-000004-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('168', '1', '2', '3', '001-000001-2011', '2', 'A', '2011-05-18 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '4', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('169', '1', '3', '3', '001-000075-2011', '10', 'A', '2011-05-18 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '4', '4', '168', '1', 'FACT. VENTA Nro.:001-000001-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('170', '1', '3', '2', '001-000043-2011', '11', 'A', '2011-05-18 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-05-18', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('171', '1', '3', '2', '001-000044-2011', '11', 'A', '2011-05-18 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-05-18', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('172', '1', '3', '1', '001-000076-2011', '10', 'A', '2011-05-21 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-05-21', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('173', '1', '3', '1', '001-000077-2011', '10', 'A', '2011-05-21 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-05-21', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('174', '1', '3', '2', '001-000045-2011', '11', 'A', '2011-05-21 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-05-21', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('175', '1', '3', '2', '001-000046-2011', '11', 'A', '2011-05-21 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-05-21', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('176', '1', '3', '1', '001-000078-2011', '10', 'A', '2011-05-25 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-05-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('177', '1', '3', '1', '001-000079-2011', '10', 'A', '2011-05-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-05-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('178', '1', '3', '2', '001-000047-2011', '11', 'A', '2011-05-25 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-05-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('179', '1', '3', '2', '001-000048-2011', '11', 'A', '2011-05-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-05-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('180', '1', '3', '1', '001-000080-2011', '10', 'A', '2011-05-27 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-05-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('181', '1', '3', '1', '001-000081-2011', '10', 'A', '2011-05-27 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-05-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('184', '1', '5', '3', '001-000001-2011', '13', 'A', '2011-05-27 00:00:00', 'S', '54.62', '10.38', '65.00', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('185', '1', '3', '2', '001-000049-2011', '11', 'A', '2011-05-27 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-05-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('186', '1', '3', '2', '001-000050-2011', '11', 'A', '2011-05-27 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-05-27', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('187', '1', '3', '1', '001-000082-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '3098.84', '588.78', '3687.62', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-05-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('188', '1', '3', '1', '001-000083-2011', '10', 'A', '2011-05-28 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-05-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('189', '1', '5', '3', '001-000002-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('191', '1', '5', '3', '001-000003-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '0.59', '0.11', '0.70', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('192', '1', '5', '3', '001-000004-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '26.05', '4.95', '31.00', '4', '23', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('193', '1', '2', '3', '001-000005-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '54.62', '10.38', '65.00', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('194', '1', '3', '3', '001-000084-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '54.62', '10.38', '65.00', '4', '5', '4', '193', '1', 'BOL. VENTA Nro.:001-000005-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('195', '1', '2', '3', '001-000006-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '0.59', '0.11', '0.70', '4', '3', '4', '191', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('196', '1', '3', '3', '001-000085-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '0.59', '0.11', '0.70', '4', '3', '4', '195', '1', 'BOL. VENTA Nro.:001-000006-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('197', '1', '5', '3', '001-000005-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('198', '1', '5', '3', '001-000006-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('199', '1', '2', '3', '001-000007-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '198', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('200', '1', '3', '3', '001-000086-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '3', '4', '199', '1', 'BOL. VENTA Nro.:001-000007-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('201', '1', '5', '3', '001-000007-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '17', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('202', '1', '2', '3', '001-000008-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '17', '4', '201', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('203', '1', '3', '3', '001-000087-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '17', '4', '202', '1', 'BOL. VENTA Nro.:001-000008-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('204', '1', '5', '3', '001-000008-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '3.36', '0.64', '4.00', '4', '9', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('205', '1', '2', '3', '001-000009-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '3.36', '0.64', '4.00', '4', '9', '4', '204', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('206', '1', '3', '3', '001-000088-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '3.36', '0.64', '4.00', '4', '9', '4', '205', '1', 'BOL. VENTA Nro.:001-000009-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('207', '1', '5', '3', '001-000009-2011', '13', 'A', '2011-05-28 00:00:00', 'S', '59.85', '11.37', '71.22', '4', '5', '4', '0', '0', 'hg', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('208', '1', '2', '3', '001-000010-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '60.36', '10.86', '71.22', '4', '5', '4', '207', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('209', '1', '3', '3', '001-000089-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '60.36', '10.86', '71.22', '4', '5', '4', '208', '1', 'BOL. VENTA Nro.:001-000010-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('210', '1', '2', '3', '001-000011-2011', '1', 'A', '2011-05-28 00:00:00', 'S', '60.36', '10.86', '71.22', '4', '5', '4', '197', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('211', '1', '3', '3', '001-000090-2011', '10', 'A', '2011-05-28 00:00:00', 'S', '60.36', '10.86', '71.22', '4', '5', '4', '210', '1', 'BOL. VENTA Nro.:001-000011-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('212', '1', '3', '2', '001-000051-2011', '11', 'A', '2011-05-28 00:00:00', 'S', '3425.59', '616.61', '4042.20', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-05-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('213', '1', '3', '2', '001-000052-2011', '11', 'A', '2011-05-28 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-05-28', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('214', '1', '3', '1', '001-000091-2011', '10', 'A', '2011-06-01 00:00:00', 'S', '3425.59', '616.61', '4042.20', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-06-01', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('215', '1', '3', '1', '001-000092-2011', '10', 'A', '2011-06-01 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-06-01', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('216', '1', '4', '0', '001-000014-2011', '10', '0', '2011-08-05 00:00:00', 'S', '0.00', '0.00', '71.22', '4', '3', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('217', '1', '3', '2', '001-000053-2011', '11', 'A', '2011-06-01 00:00:00', 'S', '3396.81', '645.39', '4042.20', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-06-01', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('218', '1', '3', '2', '001-000054-2011', '11', 'A', '2011-06-01 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-06-01', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('219', '1', '3', '1', '001-000093-2011', '10', 'A', '2011-08-13 00:00:00', 'S', '3396.81', '645.39', '4042.20', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-08-13', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('220', '1', '3', '1', '001-000094-2011', '10', 'A', '2011-08-13 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-08-13', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('221', '1', '3', '2', '001-000055-2011', '11', 'A', '2011-08-13 00:00:00', 'S', '3396.81', '645.39', '4042.20', '1', '1', '1', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-08-13', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('222', '1', '3', '2', '001-000056-2011', '11', 'A', '2011-08-13 00:00:00', 'D', '0.00', '0.00', '0.00', '1', '1', '1', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-08-13', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('223', '1', '3', '1', '001-000095-2011', '10', 'A', '2011-08-20 00:00:00', 'S', '3396.81', '645.39', '4042.20', '1', '1', '1', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-08-20', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('224', '1', '3', '1', '001-000096-2011', '10', 'A', '2011-08-20 00:00:00', 'D', '0.00', '0.00', '0.00', '1', '1', '1', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-08-20', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('225', '1', '5', '3', '001-000010-2011', '13', 'A', '2011-08-20 00:00:00', 'S', '59.85', '11.37', '71.22', '1', '5', '1', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('226', '1', '3', '2', '001-000057-2011', '11', 'A', '2011-08-20 00:00:00', 'S', '3396.81', '645.39', '4042.20', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-08-20', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('227', '1', '3', '2', '001-000058-2011', '11', 'A', '2011-08-20 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-08-20', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('228', '1', '3', '1', '001-000097-2011', '10', 'A', '2011-08-24 00:00:00', 'S', '3396.81', '645.39', '4042.20', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-08-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('229', '1', '3', '1', '001-000098-2011', '10', 'A', '2011-08-24 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-08-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('230', '1', '5', '3', '001-000011-2011', '13', 'A', '2011-08-24 00:00:00', 'S', '46.22', '8.78', '55.00', '4', '5', '4', '0', '0', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('231', '1', '2', '3', '001-000012-2011', '1', 'A', '2011-08-24 00:00:00', 'S', '46.61', '8.39', '55.00', '4', '5', '4', '230', '1', '', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('232', '1', '3', '3', '001-000099-2011', '10', 'A', '2011-08-24 00:00:00', 'S', '46.61', '8.39', '55.00', '4', '5', '4', '231', '1', 'BOL. VENTA Nro.:001-000012-2011', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('233', '1', '3', '2', '001-000059-2011', '11', 'A', '2011-08-24 00:00:00', 'S', '3443.03', '654.17', '4097.20', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN SOLES DEL2011-08-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('234', '1', '3', '2', '001-000060-2011', '11', 'A', '2011-08-24 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'CIERRE DE CAJA EN DOLARES DEL2011-08-24', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('235', '1', '3', '1', '001-000100-2011', '10', 'A', '2011-08-25 00:00:00', 'S', '3443.03', '654.17', '4097.20', '4', '1', '4', '0', '0', 'APERTURA CAJA EN SOLES DEL 2011-08-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `movimiento` VALUES ('236', '1', '3', '1', '001-000101-2011', '10', 'A', '2011-08-25 00:00:00', 'D', '0.00', '0.00', '0.00', '4', '1', '4', '0', '0', 'APERTURA CAJA EN DOLARES DEL 2011-08-25', 'N', '0000-00-00', '0', '0', '0', '0');
INSERT INTO `opcionmenu` VALUES ('1', 'list_persona.php?tipo=EMPRESA', 'Mantenimiento Empresa', '5');
INSERT INTO `opcionmenu` VALUES ('2', 'list_armario.php', 'Mantenimiento Armario', '5');
INSERT INTO `opcionmenu` VALUES ('3', 'list_menu.php', 'Opciones de Menu', '5');
INSERT INTO `opcionmenu` VALUES ('4', 'list_bitacora.php', 'Listado de Bitacoras', '5');
INSERT INTO `opcionmenu` VALUES ('5', 'list_categoria.php', 'Mantenimiento Categoria', '5');
INSERT INTO `opcionmenu` VALUES ('6', 'list_conceptopago.php', 'Mantenimiento Concepto Pago', '5');
INSERT INTO `opcionmenu` VALUES ('7', 'list_marca.php', 'Mantenimiento Marca', '5');
INSERT INTO `opcionmenu` VALUES ('8', 'list_persona.php?tipo=PERSONA', 'Mantenimiento Persona', '5');
INSERT INTO `opcionmenu` VALUES ('9', 'list_producto.php', 'Mantenimiento Producto', '5');
INSERT INTO `opcionmenu` VALUES ('10', 'list_sector.php', 'Mantenimiento Sector', '5');
INSERT INTO `opcionmenu` VALUES ('11', 'list_tipocambio.php', 'Listado Tipo Cambio', '2');
INSERT INTO `opcionmenu` VALUES ('12', 'list_tipousuario.php', 'Mantenimiento Tipo Usuario', '5');
INSERT INTO `opcionmenu` VALUES ('13', 'list_unidad.php', 'Mantenimiento Unidad', '5');
INSERT INTO `opcionmenu` VALUES ('14', 'list_usuario.php', 'Mantenimiento Usuario', '5');
INSERT INTO `opcionmenu` VALUES ('15', 'list_zona.php', 'Mantenimiento Zona', '5');
INSERT INTO `opcionmenu` VALUES ('16', 'list_almacen.php', 'Documentos de Almacen', '3');
INSERT INTO `opcionmenu` VALUES ('17', 'list_compra.php', 'Documentos de Compra', '4');
INSERT INTO `opcionmenu` VALUES ('18', 'list_ventas.php', 'Documentos de Venta', '2');
INSERT INTO `opcionmenu` VALUES ('19', 'list_movcaja.php', 'Administrar Caja', '2');
INSERT INTO `opcionmenu` VALUES ('20', 'rpt_productos.php', 'Reporte de Productos mas Vendidos', '1');
INSERT INTO `opcionmenu` VALUES ('21', 'rpt_ABCproveedores.php', 'Reporte el ABC de Proveedores', '4');
INSERT INTO `opcionmenu` VALUES ('22', 'rpt_ABCclientes.php', 'Reporte el ABC de Clientes', '1');
INSERT INTO `opcionmenu` VALUES ('23', 'rpt_MovCaja.php', 'Reporte de Movimientos de Caja', '2');
INSERT INTO `opcionmenu` VALUES ('24', 'rpt_proyeccionCP.php', 'Reporte de Cobros y Pagos', '2');
INSERT INTO `opcionmenu` VALUES ('25', 'rpt_compras.php', 'Reporte de Compras', '4');
INSERT INTO `opcionmenu` VALUES ('26', 'rpt_ventas.php', 'Reporte de Ventas', '1');
INSERT INTO `opcionmenu` VALUES ('27', 'rpt_kardex.php', 'Reporte de Kardex Valorizado', '3');
INSERT INTO `opcionmenu` VALUES ('28', 'list_guias.php', 'Listado de Guias Remision', '3');
INSERT INTO `opcionmenu` VALUES ('29', 'list_pedido.php', 'Pedidos', '1');
INSERT INTO `opcionmenu` VALUES ('30', 'list_pedidoventa.php', 'Pedidos por atender', '2');
INSERT INTO `opcionmenu` VALUES ('31', 'list_ventasalmacen.php', 'Ventas a Despachar', '3');
INSERT INTO `pais` VALUES ('1', 'PERU - PERUANO', null);
INSERT INTO `pais` VALUES ('2', 'EXTRANJERO - EXTRANJ', null);
INSERT INTO `persona` VALUES ('1', 'JURIDICA', '', 'EL XXX', '2047957052', 'M', 'AV. LUIS GONZALES - CHICLAYO', '', '', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('3', 'NATURAL', 'MELISA', 'CASAS MONTALVO', '43825485', 'M', 'CORNEJO', '222222', 'ffff@ffff.com', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('4', 'NATURAL', 'GEYNEN', 'MONTENEGRO COCHAS', '12345678', 'M', 'ANGAMOS', '979368623', 'geynen_0710@hotmail.com', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('5', 'NATURAL', 'VARIOS', 'CLIENTE', '-', 'M', '', '', '', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('6', 'NATURAL', 'EE', 'RR', '33', 'M', '', '', '', '1', '1', '82', '', 'A');
INSERT INTO `persona` VALUES ('7', 'NATURAL', 'WW', 'WWW', '77', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('8', 'NATURAL', 'VLADIMIR', 'ZE', '66', 'M', '', '', '', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('9', 'NATURAL', 'PEDRO', 'PERES PE', '3456778585', 'M', 'R', '', '', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('10', 'NATURAL', 'PE', 'PE', '1111111111', 'M', 'DDD', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('11', 'NATURAL', '', '', '33', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('12', 'NATURAL', '', '', '33', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('13', 'NATURAL', '', '', '33', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('14', 'NATURAL', 'XXX', 'XXX', '1111111111', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('15', 'NATURAL', 'CCCCCCC', 'CCCCCC', '1111111111', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('16', 'NATURAL', 'VVVV', 'VVVVV', '1111111111', 'M', '', '', '', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('17', 'NATURAL', 'CESAR', 'CAPU', '1234567890', 'M', '--', '--', '-', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('18', 'NATURAL', 'TT', 'TT', '1111111111', 'M', 'WWW', '-', '-', '1', '1', '409', '', 'A');
INSERT INTO `persona` VALUES ('19', 'JURIDICA', '20372789396', 'CORPORACION LEGOCSA', '45538382', 'M', 'BOLOGNESI', '212312', 'xxxx@hotmail.com', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('20', 'NATURAL', 'EL DIAL', 'EL DIAL', '0394948937', 'M', 'BALTA', '978238801', 'yarac_16@hotmail.com', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('21', 'NATURAL', 'ANAVEL', 'BARRANTES CALDERON', '43206778', 'F', 'HUASCAR', '978383801', 'yarac_16@hotmail.com', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('22', 'JURIDICA', 'ISELA', 'BARRANTES CALDERON', '47324567', 'F', 'HUASAR 537', '987878789', 'issela@hotmail.com', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('23', 'JURIDICA', 'CONSTRUCTORA INGENIEROS', 'INGENIEROS', '4325252525', 'M', 'PRADERA 123', '250930', 'gerardo@consting.com', '1', '1', '435', '', 'N');
INSERT INTO `persona` VALUES ('24', 'NATURAL', 'PATRIX', 'BAZAN CALDERON', '234678', 'M', 'SANTA VICTORIA', '250930', 'XXX@HOTMAIL.COM', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('25', 'NATURAL', 'KAREN', 'MONTOYA', '1111111111', 'M', '', '', '', '1', '1', '409', '', 'N');
INSERT INTO `persona` VALUES ('26', 'JURIDICA', '', 'LOGIN STORE', '1212121212', 'M', '-', '-', '-', '1', '1', '409', '', 'N');
INSERT INTO `producto` VALUES ('1', '001', 'PINTURA', '12', '1', '1', '5.00', '5', '10.00', '1', '3', '4', 'S', 'N');
INSERT INTO `producto` VALUES ('2', '01', 'SSSS', '15', '1', '1', '4.00', '5', '23.00', '2', '2', '6', 'S', 'A');
INSERT INTO `producto` VALUES ('3', '444', 'PINTURA', '12', '1', '1', '4.00', '5', '45.00', '1', '1', '1', 'S', 'N');
INSERT INTO `producto` VALUES ('4', '003', 'LIJAS', '12', '1', '1', '5.00', '4', '3.00', '1', '2', '4', 'S', 'N');
INSERT INTO `producto` VALUES ('5', '007', 'PERNO', '12', '2', '1', '200.00', '4', '100.00', '1', '2', '3', 'S', 'N');
INSERT INTO `producto` VALUES ('6', 'PEG-PIN-XX-001', 'PEGAMENTO', '12', '1', '1', '1.00', '5', '2.00', '1', '1', '1', 'S', 'N');
INSERT INTO `producto` VALUES ('7', 'PI-YY-002', 'AZUL MARINO', '15', '2', '1', '5.00', '5', '5.00', '1', '1', '1', 'S', 'N');
INSERT INTO `producto` VALUES ('8', 'ES-XX-001', 'ROJO', '13', '1', '1', '5.00', '5', '5.00', '1', '1', '1', 'S', 'N');
INSERT INTO `producto` VALUES ('9', 'EA-XX-001', 'TUBO DE 3 1/4 * 5+2', '14', '1', '1', '5.00', '5', '5.00', '1', '1', '1', 'S', 'N');
INSERT INTO `producto` VALUES ('10', 'TE-XX-001', 'TUBO DE 3 1/2 * 5+2', '17', '1', '1', '4.00', '5', '4.00', '1', '1', '1', 'S', 'N');
INSERT INTO `producto` VALUES ('11', 'PI-AA-001', 'NNN', '12', '4', '1', '2.50', '5', '12.00', '1', '1', '1', 'S', 'N');
INSERT INTO `provincia` VALUES ('1', 'CHACHAPOYAS', '01', '1');
INSERT INTO `provincia` VALUES ('2', 'BAGUA', '02', '1');
INSERT INTO `provincia` VALUES ('3', 'BONGARA', '03', '1');
INSERT INTO `provincia` VALUES ('4', 'CONDORCANQUI', '04', '1');
INSERT INTO `provincia` VALUES ('5', 'LUYA', '05', '1');
INSERT INTO `provincia` VALUES ('6', 'RODRIGUEZ DE MENDOZA', '06', '1');
INSERT INTO `provincia` VALUES ('7', 'UTCUBAMBA', '07', '1');
INSERT INTO `provincia` VALUES ('8', 'HUARAZ', '01', '2');
INSERT INTO `provincia` VALUES ('9', 'AIJA', '02', '2');
INSERT INTO `provincia` VALUES ('10', 'ANTONIO RAYMONDI', '03', '2');
INSERT INTO `provincia` VALUES ('11', 'ASUNCION', '04', '2');
INSERT INTO `provincia` VALUES ('12', 'BOLOGNESI', '05', '2');
INSERT INTO `provincia` VALUES ('13', 'CARHUAZ', '06', '2');
INSERT INTO `provincia` VALUES ('14', 'CARLOS FERMIN FITZCARRALD', '07', '2');
INSERT INTO `provincia` VALUES ('15', 'CASMA', '08', '2');
INSERT INTO `provincia` VALUES ('16', 'CORONGO', '09', '2');
INSERT INTO `provincia` VALUES ('17', 'HUARI', '10', '2');
INSERT INTO `provincia` VALUES ('18', 'HUARMEY', '11', '2');
INSERT INTO `provincia` VALUES ('19', 'HUAYLAS', '12', '2');
INSERT INTO `provincia` VALUES ('20', 'MARISCAL LUZURIAGA', '13', '2');
INSERT INTO `provincia` VALUES ('21', 'OCROS', '14', '2');
INSERT INTO `provincia` VALUES ('22', 'PALLASCA', '15', '2');
INSERT INTO `provincia` VALUES ('23', 'POMABAMBA', '16', '2');
INSERT INTO `provincia` VALUES ('24', 'RECUAY', '17', '2');
INSERT INTO `provincia` VALUES ('25', 'SANTA', '18', '2');
INSERT INTO `provincia` VALUES ('26', 'SIHUAS', '19', '2');
INSERT INTO `provincia` VALUES ('27', 'YUNGAY', '20', '2');
INSERT INTO `provincia` VALUES ('28', 'ABANCAY', '01', '3');
INSERT INTO `provincia` VALUES ('29', 'ANDAHUAYLAS', '02', '3');
INSERT INTO `provincia` VALUES ('30', 'ANTABAMBA', '03', '3');
INSERT INTO `provincia` VALUES ('31', 'AYMARAES', '04', '3');
INSERT INTO `provincia` VALUES ('32', 'COTABAMBAS', '05', '3');
INSERT INTO `provincia` VALUES ('33', 'CHINCHEROS', '06', '3');
INSERT INTO `provincia` VALUES ('34', 'GRAU', '07', '3');
INSERT INTO `provincia` VALUES ('35', 'AREQUIPA', '01', '4');
INSERT INTO `provincia` VALUES ('36', 'CAMANA', '02', '4');
INSERT INTO `provincia` VALUES ('37', 'CARAVELI', '03', '4');
INSERT INTO `provincia` VALUES ('38', 'CASTILLA', '04', '4');
INSERT INTO `provincia` VALUES ('39', 'CAYLLOMA', '05', '4');
INSERT INTO `provincia` VALUES ('40', 'CONDESUYOS', '06', '4');
INSERT INTO `provincia` VALUES ('41', 'ISLAY', '07', '4');
INSERT INTO `provincia` VALUES ('42', 'LA UNION', '08', '4');
INSERT INTO `provincia` VALUES ('43', 'HUAMANGA', '01', '5');
INSERT INTO `provincia` VALUES ('44', 'CANGALLO', '02', '5');
INSERT INTO `provincia` VALUES ('45', 'HUANCA SANCOS', '03', '5');
INSERT INTO `provincia` VALUES ('46', 'HUANTA', '04', '5');
INSERT INTO `provincia` VALUES ('47', 'LA MAR', '05', '5');
INSERT INTO `provincia` VALUES ('48', 'LUCANAS', '06', '5');
INSERT INTO `provincia` VALUES ('49', 'PARINACOCHAS', '07', '5');
INSERT INTO `provincia` VALUES ('50', 'PAUCAR DEL SARA SARA', '08', '5');
INSERT INTO `provincia` VALUES ('51', 'SUCRE', '09', '5');
INSERT INTO `provincia` VALUES ('52', 'VICTOR FAJARDO', '10', '5');
INSERT INTO `provincia` VALUES ('53', 'VILCAS HUAMAN', '11', '5');
INSERT INTO `provincia` VALUES ('54', 'CAJAMARCA', '01', '6');
INSERT INTO `provincia` VALUES ('55', 'CAJABAMBA', '02', '6');
INSERT INTO `provincia` VALUES ('56', 'CELENDIN', '03', '6');
INSERT INTO `provincia` VALUES ('57', 'CHOTA', '04', '6');
INSERT INTO `provincia` VALUES ('58', 'CONTUMAZA', '05', '6');
INSERT INTO `provincia` VALUES ('59', 'CUTERVO', '06', '6');
INSERT INTO `provincia` VALUES ('60', 'HUALGAYOC', '07', '6');
INSERT INTO `provincia` VALUES ('61', 'JAEN', '08', '6');
INSERT INTO `provincia` VALUES ('62', 'SAN IGNACIO', '09', '6');
INSERT INTO `provincia` VALUES ('63', 'SAN MARCOS', '10', '6');
INSERT INTO `provincia` VALUES ('64', 'SAN MIGUEL', '11', '6');
INSERT INTO `provincia` VALUES ('65', 'SAN PABLO', '12', '6');
INSERT INTO `provincia` VALUES ('66', 'SANTA CRUZ', '13', '6');
INSERT INTO `provincia` VALUES ('67', 'CALLAO', '01', '7');
INSERT INTO `provincia` VALUES ('68', 'CUSCO', '01', '8');
INSERT INTO `provincia` VALUES ('69', 'ACOMAYO', '02', '8');
INSERT INTO `provincia` VALUES ('70', 'ANTA', '03', '8');
INSERT INTO `provincia` VALUES ('71', 'CALCA', '04', '8');
INSERT INTO `provincia` VALUES ('72', 'CANAS', '05', '8');
INSERT INTO `provincia` VALUES ('73', 'CANCHIS', '06', '8');
INSERT INTO `provincia` VALUES ('74', 'CHUMBIVILCAS', '07', '8');
INSERT INTO `provincia` VALUES ('75', 'ESPINAR', '08', '8');
INSERT INTO `provincia` VALUES ('76', 'LA CONVENCION', '09', '8');
INSERT INTO `provincia` VALUES ('77', 'PARURO', '10', '8');
INSERT INTO `provincia` VALUES ('78', 'PAUCARTAMBO', '11', '8');
INSERT INTO `provincia` VALUES ('79', 'QUISPICANCHI', '12', '8');
INSERT INTO `provincia` VALUES ('80', 'URUBAMBA', '13', '8');
INSERT INTO `provincia` VALUES ('81', 'HUANCAVELICA', '01', '9');
INSERT INTO `provincia` VALUES ('82', 'ACOBAMBA', '02', '9');
INSERT INTO `provincia` VALUES ('83', 'ANGARAES', '03', '9');
INSERT INTO `provincia` VALUES ('84', 'CASTROVIRREYNA', '04', '9');
INSERT INTO `provincia` VALUES ('85', 'CHURCAMPA', '05', '9');
INSERT INTO `provincia` VALUES ('86', 'HUAYTARA', '06', '9');
INSERT INTO `provincia` VALUES ('87', 'TAYACAJA', '07', '9');
INSERT INTO `provincia` VALUES ('88', 'HUANUCO', '01', '10');
INSERT INTO `provincia` VALUES ('89', 'AMBO', '02', '10');
INSERT INTO `provincia` VALUES ('90', 'DOS DE MAYO', '03', '10');
INSERT INTO `provincia` VALUES ('91', 'HUACAYBAMBA', '04', '10');
INSERT INTO `provincia` VALUES ('92', 'HUAMALIES', '05', '10');
INSERT INTO `provincia` VALUES ('93', 'LEONCIO PRADO', '06', '10');
INSERT INTO `provincia` VALUES ('94', 'MARA?ON', '07', '10');
INSERT INTO `provincia` VALUES ('95', 'PACHITEA', '08', '10');
INSERT INTO `provincia` VALUES ('96', 'PUERTO INCA', '09', '10');
INSERT INTO `provincia` VALUES ('97', 'LAURICOCHA', '10', '10');
INSERT INTO `provincia` VALUES ('98', 'YAROWILCA', '11', '10');
INSERT INTO `provincia` VALUES ('99', 'ICA', '01', '11');
INSERT INTO `provincia` VALUES ('100', 'CHINCHA', '02', '11');
INSERT INTO `provincia` VALUES ('101', 'NAZCA', '03', '11');
INSERT INTO `provincia` VALUES ('102', 'PALPA', '04', '11');
INSERT INTO `provincia` VALUES ('103', 'PISCO', '05', '11');
INSERT INTO `provincia` VALUES ('104', 'HUANCAYO', '01', '12');
INSERT INTO `provincia` VALUES ('105', 'CONCEPCION', '02', '12');
INSERT INTO `provincia` VALUES ('106', 'CHANCHAMAYO', '03', '12');
INSERT INTO `provincia` VALUES ('107', 'JAUJA', '04', '12');
INSERT INTO `provincia` VALUES ('108', 'JUNIN', '05', '12');
INSERT INTO `provincia` VALUES ('109', 'SATIPO', '06', '12');
INSERT INTO `provincia` VALUES ('110', 'TARMA', '07', '12');
INSERT INTO `provincia` VALUES ('111', 'YAULI', '08', '12');
INSERT INTO `provincia` VALUES ('112', 'CHUPACA', '09', '12');
INSERT INTO `provincia` VALUES ('113', 'TRUJILLO', '01', '13');
INSERT INTO `provincia` VALUES ('114', 'ASCOPE', '02', '13');
INSERT INTO `provincia` VALUES ('115', 'BOLIVAR', '03', '13');
INSERT INTO `provincia` VALUES ('116', 'CHEPEN', '04', '13');
INSERT INTO `provincia` VALUES ('117', 'JULCAN', '05', '13');
INSERT INTO `provincia` VALUES ('118', 'OTUZCO', '06', '13');
INSERT INTO `provincia` VALUES ('119', 'PACASMAYO', '07', '13');
INSERT INTO `provincia` VALUES ('120', 'PATAZ', '08', '13');
INSERT INTO `provincia` VALUES ('121', 'SANCHEZ CARRION', '09', '13');
INSERT INTO `provincia` VALUES ('122', 'SANTIAGO DE CHUCO', '10', '13');
INSERT INTO `provincia` VALUES ('123', 'GRAN CHIMU', '11', '13');
INSERT INTO `provincia` VALUES ('124', 'VIRU', '12', '13');
INSERT INTO `provincia` VALUES ('125', 'CHICLAYO', '01', '14');
INSERT INTO `provincia` VALUES ('126', 'FERRE?AFE', '02', '14');
INSERT INTO `provincia` VALUES ('127', 'LAMBAYEQUE', '03', '14');
INSERT INTO `provincia` VALUES ('128', 'LIMA', '01', '15');
INSERT INTO `provincia` VALUES ('129', 'BARRANCA', '02', '15');
INSERT INTO `provincia` VALUES ('130', 'CAJATAMBO', '03', '15');
INSERT INTO `provincia` VALUES ('131', 'CANTA', '04', '15');
INSERT INTO `provincia` VALUES ('132', 'CA?ETE', '05', '15');
INSERT INTO `provincia` VALUES ('133', 'HUARAL', '06', '15');
INSERT INTO `provincia` VALUES ('134', 'HUAROCHIRI', '07', '15');
INSERT INTO `provincia` VALUES ('135', 'HUAURA', '08', '15');
INSERT INTO `provincia` VALUES ('136', 'OYON', '09', '15');
INSERT INTO `provincia` VALUES ('137', 'YAUYOS', '10', '15');
INSERT INTO `provincia` VALUES ('138', 'MAYNAS', '01', '16');
INSERT INTO `provincia` VALUES ('139', 'ALTO AMAZONAS', '02', '16');
INSERT INTO `provincia` VALUES ('140', 'LORETO', '03', '16');
INSERT INTO `provincia` VALUES ('141', 'MARISCAL RAMON CASTILLA', '04', '16');
INSERT INTO `provincia` VALUES ('142', 'REQUENA', '05', '16');
INSERT INTO `provincia` VALUES ('143', 'UCAYALI', '06', '16');
INSERT INTO `provincia` VALUES ('144', 'TAMBOPATA', '01', '17');
INSERT INTO `provincia` VALUES ('145', 'MANU', '02', '17');
INSERT INTO `provincia` VALUES ('146', 'TAHUAMANU', '03', '17');
INSERT INTO `provincia` VALUES ('147', 'MARISCAL NIETO', '01', '18');
INSERT INTO `provincia` VALUES ('148', 'GENERAL SANCHEZ CERRO', '02', '18');
INSERT INTO `provincia` VALUES ('149', 'ILO', '03', '18');
INSERT INTO `provincia` VALUES ('150', 'PASCO', '01', '19');
INSERT INTO `provincia` VALUES ('151', 'DANIEL ALCIDES CARRION', '02', '19');
INSERT INTO `provincia` VALUES ('152', 'OXAPAMPA', '03', '19');
INSERT INTO `provincia` VALUES ('153', 'PIURA', '01', '20');
INSERT INTO `provincia` VALUES ('154', 'AYABACA', '02', '20');
INSERT INTO `provincia` VALUES ('155', 'HUANCABAMBA', '03', '20');
INSERT INTO `provincia` VALUES ('156', 'MORROPON', '04', '20');
INSERT INTO `provincia` VALUES ('157', 'PAITA', '05', '20');
INSERT INTO `provincia` VALUES ('158', 'SULLANA', '06', '20');
INSERT INTO `provincia` VALUES ('159', 'TALARA', '07', '20');
INSERT INTO `provincia` VALUES ('160', 'SECHURA', '08', '20');
INSERT INTO `provincia` VALUES ('161', 'PUNO', '01', '21');
INSERT INTO `provincia` VALUES ('162', 'AZANGARO', '02', '21');
INSERT INTO `provincia` VALUES ('163', 'CARABAYA', '03', '21');
INSERT INTO `provincia` VALUES ('164', 'CHUCUITO', '04', '21');
INSERT INTO `provincia` VALUES ('165', 'EL COLLAO', '05', '21');
INSERT INTO `provincia` VALUES ('166', 'HUANCANE', '06', '21');
INSERT INTO `provincia` VALUES ('167', 'LAMPA', '07', '21');
INSERT INTO `provincia` VALUES ('168', 'MELGAR', '08', '21');
INSERT INTO `provincia` VALUES ('169', 'MOHO', '09', '21');
INSERT INTO `provincia` VALUES ('170', 'SAN ANTONIO DE PUTINA', '10', '21');
INSERT INTO `provincia` VALUES ('171', 'SAN ROMAN', '11', '21');
INSERT INTO `provincia` VALUES ('172', 'SANDIA', '12', '21');
INSERT INTO `provincia` VALUES ('173', 'YUNGUYO', '13', '21');
INSERT INTO `provincia` VALUES ('174', 'MOYOBAMBA', '01', '22');
INSERT INTO `provincia` VALUES ('175', 'BELLAVISTA', '02', '22');
INSERT INTO `provincia` VALUES ('176', 'EL DORADO', '03', '22');
INSERT INTO `provincia` VALUES ('177', 'HUALLAGA', '04', '22');
INSERT INTO `provincia` VALUES ('178', 'LAMAS', '05', '22');
INSERT INTO `provincia` VALUES ('179', 'MARISCAL CACERES', '06', '22');
INSERT INTO `provincia` VALUES ('180', 'PICOTA', '07', '22');
INSERT INTO `provincia` VALUES ('181', 'RIOJA', '08', '22');
INSERT INTO `provincia` VALUES ('182', 'SAN MARTIN', '09', '22');
INSERT INTO `provincia` VALUES ('183', 'TOCACHE', '10', '22');
INSERT INTO `provincia` VALUES ('184', 'TACNA', '01', '23');
INSERT INTO `provincia` VALUES ('185', 'CANDARAVE', '02', '23');
INSERT INTO `provincia` VALUES ('186', 'JORGE BASADRE', '03', '23');
INSERT INTO `provincia` VALUES ('187', 'TARATA', '04', '23');
INSERT INTO `provincia` VALUES ('188', 'TUMBES', '01', '24');
INSERT INTO `provincia` VALUES ('189', 'CONTRALMIRANTE VILLAR', '02', '24');
INSERT INTO `provincia` VALUES ('190', 'ZARUMILLA', '03', '24');
INSERT INTO `provincia` VALUES ('191', 'CORONEL PORTILLO', '01', '25');
INSERT INTO `provincia` VALUES ('192', 'ATALAYA', '02', '25');
INSERT INTO `provincia` VALUES ('193', 'PADRE ABAD', '03', '25');
INSERT INTO `provincia` VALUES ('194', 'PURUS', '04', '25');
INSERT INTO `rol` VALUES ('1', 'CLIENTE', 'N');
INSERT INTO `rol` VALUES ('2', 'PROVEEDOR', 'N');
INSERT INTO `rol` VALUES ('3', 'EMPLEADO', 'N');
INSERT INTO `rol` VALUES ('4', 'USUARIO', 'N');
INSERT INTO `rol` VALUES ('5', 'EMPRESA', 'N');
INSERT INTO `rolpersona` VALUES ('7', '1', '5');
INSERT INTO `rolpersona` VALUES ('8', '2', '5');
INSERT INTO `rolpersona` VALUES ('9', '3', '1');
INSERT INTO `rolpersona` VALUES ('10', '3', '2');
INSERT INTO `rolpersona` VALUES ('11', '3', '3');
INSERT INTO `rolpersona` VALUES ('12', '3', '4');
INSERT INTO `rolpersona` VALUES ('13', '4', '1');
INSERT INTO `rolpersona` VALUES ('14', '4', '2');
INSERT INTO `rolpersona` VALUES ('15', '4', '3');
INSERT INTO `rolpersona` VALUES ('16', '4', '4');
INSERT INTO `rolpersona` VALUES ('17', '5', '2');
INSERT INTO `rolpersona` VALUES ('18', '6', '3');
INSERT INTO `rolpersona` VALUES ('19', '7', '1');
INSERT INTO `rolpersona` VALUES ('20', '5', '1');
INSERT INTO `rolpersona` VALUES ('21', '6', '1');
INSERT INTO `rolpersona` VALUES ('22', '7', '1');
INSERT INTO `rolpersona` VALUES ('23', '8', '1');
INSERT INTO `rolpersona` VALUES ('24', '9', '1');
INSERT INTO `rolpersona` VALUES ('25', '10', '1');
INSERT INTO `rolpersona` VALUES ('26', '11', '1');
INSERT INTO `rolpersona` VALUES ('27', '12', '1');
INSERT INTO `rolpersona` VALUES ('28', '13', '1');
INSERT INTO `rolpersona` VALUES ('29', '14', '1');
INSERT INTO `rolpersona` VALUES ('30', '15', '1');
INSERT INTO `rolpersona` VALUES ('31', '16', '1');
INSERT INTO `rolpersona` VALUES ('32', '17', '1');
INSERT INTO `rolpersona` VALUES ('33', '17', '2');
INSERT INTO `rolpersona` VALUES ('34', '18', '3');
INSERT INTO `rolpersona` VALUES ('35', '19', '1');
INSERT INTO `rolpersona` VALUES ('36', '20', '2');
INSERT INTO `rolpersona` VALUES ('37', '21', '1');
INSERT INTO `rolpersona` VALUES ('38', '22', '1');
INSERT INTO `rolpersona` VALUES ('39', '23', '1');
INSERT INTO `rolpersona` VALUES ('40', '24', '4');
INSERT INTO `rolpersona` VALUES ('41', '25', '3');
INSERT INTO `rolpersona` VALUES ('42', '26', '1');
INSERT INTO `sector` VALUES ('1', 'CIX');
INSERT INTO `sector` VALUES ('2', 'TRUJILLO');
INSERT INTO `stockproducto` VALUES ('1', '1', '1', '1', '17.00', '1', '17.00');
INSERT INTO `stockproducto` VALUES ('2', '1', '5', '1', '11.00', '1', '11.00');
INSERT INTO `stockproducto` VALUES ('3', '1', '8', '1', '0.00', '1', '0.00');
INSERT INTO `stockproducto` VALUES ('4', '1', '6', '1', '3.00', '1', '3.00');
INSERT INTO `stockproducto` VALUES ('5', '1', '3', '1', '4.00', '1', '4.00');
INSERT INTO `stockproducto` VALUES ('6', '1', '9', '1', '3.00', '1', '3.00');
INSERT INTO `stockproducto` VALUES ('7', '1', '7', '1', '9.00', '1', '9.00');
INSERT INTO `stockproducto` VALUES ('8', '1', '2', '1', '3.00', '1', '3.00');
INSERT INTO `sucursal` VALUES ('1', 'FERRETERIA EL IMAN EIRL', 'AV. MARIANO CORNEJO 554 - CHICLAYO', '20479570524');
INSERT INTO `sucursal` VALUES ('2', 'COMERCIAL PISFIL EIRL', 'AV. LA FLORIDA 294 - NUEVO CAJAMARCA', '20531343353');
INSERT INTO `tipocambio` VALUES ('1', '2010-11-11', '2.80');
INSERT INTO `tipocambio` VALUES ('2', '2010-12-11', '2.85');
INSERT INTO `tipocambio` VALUES ('3', '2010-12-12', '2.85');
INSERT INTO `tipocambio` VALUES ('4', '2010-12-24', '2.85');
INSERT INTO `tipocambio` VALUES ('5', '2011-01-06', '2.85');
INSERT INTO `tipocambio` VALUES ('6', '2011-01-09', '2.85');
INSERT INTO `tipocambio` VALUES ('7', '2011-01-10', '2.85');
INSERT INTO `tipocambio` VALUES ('8', '2011-01-11', '2.85');
INSERT INTO `tipocambio` VALUES ('9', '2011-01-12', '2.85');
INSERT INTO `tipocambio` VALUES ('10', '2011-01-13', '2.85');
INSERT INTO `tipocambio` VALUES ('11', '2011-01-15', '2.85');
INSERT INTO `tipocambio` VALUES ('12', '2011-01-16', '2.85');
INSERT INTO `tipocambio` VALUES ('13', '2011-01-17', '2.85');
INSERT INTO `tipocambio` VALUES ('14', '2011-01-18', '2.85');
INSERT INTO `tipocambio` VALUES ('15', '2011-01-19', '2.85');
INSERT INTO `tipocambio` VALUES ('16', '2011-01-20', '2.85');
INSERT INTO `tipocambio` VALUES ('17', '2011-01-24', '2.85');
INSERT INTO `tipocambio` VALUES ('18', '2011-01-25', '2.85');
INSERT INTO `tipocambio` VALUES ('19', '2011-01-26', '2.85');
INSERT INTO `tipocambio` VALUES ('20', '2011-01-27', '2.85');
INSERT INTO `tipocambio` VALUES ('21', '2011-01-30', '2.85');
INSERT INTO `tipocambio` VALUES ('22', '2011-02-03', '2.85');
INSERT INTO `tipocambio` VALUES ('23', '2011-02-04', '2.85');
INSERT INTO `tipocambio` VALUES ('24', '2011-02-07', '2.85');
INSERT INTO `tipocambio` VALUES ('25', '2011-02-08', '2.85');
INSERT INTO `tipocambio` VALUES ('26', '2011-02-18', '2.85');
INSERT INTO `tipocambio` VALUES ('27', '2011-02-21', '2.85');
INSERT INTO `tipocambio` VALUES ('28', '2011-02-23', '2.85');
INSERT INTO `tipocambio` VALUES ('29', '2011-02-25', '2.85');
INSERT INTO `tipocambio` VALUES ('30', '2011-03-05', '2.85');
INSERT INTO `tipocambio` VALUES ('31', '2011-03-08', '2.85');
INSERT INTO `tipocambio` VALUES ('32', '2011-03-15', '2.85');
INSERT INTO `tipocambio` VALUES ('33', '2011-03-20', '2.85');
INSERT INTO `tipocambio` VALUES ('34', '2011-04-13', '2.85');
INSERT INTO `tipocambio` VALUES ('35', '2011-04-14', '2.85');
INSERT INTO `tipocambio` VALUES ('36', '2011-04-15', '2.85');
INSERT INTO `tipocambio` VALUES ('37', '2011-04-16', '2.85');
INSERT INTO `tipocambio` VALUES ('38', '2011-04-24', '2.85');
INSERT INTO `tipocambio` VALUES ('39', '2011-04-25', '2.85');
INSERT INTO `tipocambio` VALUES ('40', '2011-04-27', '2.85');
INSERT INTO `tipocambio` VALUES ('41', '2011-04-28', '2.85');
INSERT INTO `tipocambio` VALUES ('42', '2011-05-03', '2.85');
INSERT INTO `tipocambio` VALUES ('43', '2011-05-18', '2.85');
INSERT INTO `tipocambio` VALUES ('44', '2011-05-21', '2.85');
INSERT INTO `tipocambio` VALUES ('45', '2011-05-25', '2.85');
INSERT INTO `tipocambio` VALUES ('46', '2011-05-27', '2.85');
INSERT INTO `tipocambio` VALUES ('47', '2011-05-28', '2.85');
INSERT INTO `tipocambio` VALUES ('48', '2011-06-01', '2.85');
INSERT INTO `tipocambio` VALUES ('49', '2011-07-02', '2.85');
INSERT INTO `tipocambio` VALUES ('50', '2011-08-05', '2.85');
INSERT INTO `tipocambio` VALUES ('51', '2011-08-11', '2.85');
INSERT INTO `tipocambio` VALUES ('52', '2011-08-12', '2.85');
INSERT INTO `tipocambio` VALUES ('53', '2011-08-13', '2.85');
INSERT INTO `tipocambio` VALUES ('54', '2011-08-20', '2.85');
INSERT INTO `tipocambio` VALUES ('55', '2011-08-24', '2.85');
INSERT INTO `tipocambio` VALUES ('56', '2011-08-25', '2.85');
INSERT INTO `tipodocumento` VALUES ('1', 'BOLETA VENTA', 'B/V', 'R');
INSERT INTO `tipodocumento` VALUES ('2', 'FACTURA VENTA', 'F/V', 'R');
INSERT INTO `tipodocumento` VALUES ('3', 'BOLETA COMPRA', 'B/C', 'S');
INSERT INTO `tipodocumento` VALUES ('4', 'FACTURA COMPRA', 'F/C', 'S');
INSERT INTO `tipodocumento` VALUES ('5', 'GUIA REMISION EMISOR', 'GRE', 'R');
INSERT INTO `tipodocumento` VALUES ('6', 'GUIA REMISION RECEPTOR', 'GRR', 'S');
INSERT INTO `tipodocumento` VALUES ('7', 'COTIZACION VENTA', 'COT', 'N');
INSERT INTO `tipodocumento` VALUES ('8', 'NOTA DE CREDITO COMPRA', 'NCC', 'R');
INSERT INTO `tipodocumento` VALUES ('9', 'NOTA DE CREDITO VENTA', 'NCV', 'S');
INSERT INTO `tipodocumento` VALUES ('10', 'INGRESO', 'I', 'N');
INSERT INTO `tipodocumento` VALUES ('11', 'EGRESO', 'E', 'N');
INSERT INTO `tipodocumento` VALUES ('12', 'RECIBO VENTA', 'R/V', 'R');
INSERT INTO `tipodocumento` VALUES ('13', 'PEDIDO', 'P', 'N');
INSERT INTO `tipomovimiento` VALUES ('1', 'COMPRA');
INSERT INTO `tipomovimiento` VALUES ('2', 'VENTA');
INSERT INTO `tipomovimiento` VALUES ('3', 'CAJA');
INSERT INTO `tipomovimiento` VALUES ('4', 'ALMACEN');
INSERT INTO `tipomovimiento` VALUES ('5', 'PEDIDO');
INSERT INTO `tipousuario` VALUES ('1', 'ADMINISTRACION', 'N');
INSERT INTO `tipousuario` VALUES ('2', 'VENDEDOR', 'N');
INSERT INTO `tipousuario` VALUES ('3', 'ALMACEN', 'N');
INSERT INTO `tipousuario` VALUES ('4', 'CAJERO', 'N');
INSERT INTO `tipousuario` VALUES ('5', 'COMPRAS', 'N');
INSERT INTO `transportista` VALUES ('1', '1', 'CIVA', '12345678', '', 'XX', 'XX', '22', '22', 'XX', 'N');
INSERT INTO `unidad` VALUES ('1', 'UNIDAD', 'UNI', 'O', 'N');
INSERT INTO `unidad` VALUES ('2', 'CAJA', 'CAJA', 'O', 'N');
INSERT INTO `unidad` VALUES ('3', 'PACK', 'PACK', 'O', 'N');
INSERT INTO `unidad` VALUES ('4', 'GRAMO', 'GM', 'M', 'N');
INSERT INTO `unidad` VALUES ('5', 'KILOGRAMO', 'KG', 'M', 'N');
INSERT INTO `usuario` VALUES ('3', 'melissa', '321', '2011-05-03 15:35:27', '2', 'N');
INSERT INTO `usuario` VALUES ('4', 'admin', '123', '2011-08-25 14:25:58', '1', 'N');
INSERT INTO `usuario` VALUES ('24', 'patrix', '123', '2011-05-03 17:45:53', '4', 'N');
INSERT INTO `zona` VALUES ('1', 'CIX');
INSERT INTO `zona` VALUES ('2', 'TRUJILLO');
INSERT INTO `zona` VALUES ('3', 'PIURA');
