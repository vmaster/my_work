<?php 
session_start();
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else {$idsucursal=1;}
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores
require("../datos/cado.php");

function listadoproductos($descripcion,$categoria,$marca)
{
require("../negocio/cls_producto.php");
	$registro.="<table class=registros>";
	$registro.="
    <tr>
      	<th><div align='center'>CODIGO</div></th>
	  	<th><div align='center'>DESCRIPCION</div></th>
	  	<th><div align='center'>CATEGORIA</div></th>
		<th><div align='center'>MARCA / LABORATORIO</div></th></div>
		<!--<th><div align='center'>COLOR</div></th></div>-->
		<!--<th><div align='center'>TALLA</div></th></div>-->
		<th><div align='center'>UNIDAD BASE</div></th>
		<th><div align='center'>PESO</div></th>
		<th><div align='center'>MEDIDA</div></th>
		<th><div align='center'>STOCK SEGURIDAD</div></th>
		<th><div align='center'>ARMARIO</div></th>
		<th><div align='center'>COLUMNA</div></th>
		<th><div align='center'>FILA</div></th>
		<!--<th><div align='center'>KARDEX</div></th>-->
		<th colspan='4'><div align='center'>OPERACIONES</div></th>
		</tr>";
		
		date_default_timezone_set('America/Bogota');
	   
	$objProducto = new clsProducto();
$rst = $objProducto->buscar(NULL,$descripcion,$categoria,$marca);
while($dato = $rst->fetchObject()){
	$cont++;
    if($cont%2) $estilo="par"; else $estilo="impar";
	$registro.="<tr class='$estilo'><td align='center'>".$dato->codigo."</td>";
	$registro.="<td align='center'>".$dato->descripcion."</td>";
	$registro.="<td align='center'>".$dato->categoria."</td>";
	$registro.="<td align='center'>".$dato->marca."</td>"; 
	/*if($dato->codigocolor==''){
		$registro.="<td align='center'>".$dato->color."</td>"; 
	}else{
		$registro.="<td align='center' title=".$dato->color." bgcolor=".$dato->codigocolor.">&nbsp;</td>"; 
	}
	$registro.="<td align='center' title=".$dato->talla.">".$dato->tallaabreviatura."</td>"; */
	$registro.="<td align='center'>".$dato->unidadbase."</td>";
	$registro.="<td align='center'>".$dato->peso."</td>";
	$registro.="<td align='center'>".$dato->medidapeso."</td>";
	$registro.="<td align='center'>".$mon.$dato->stockseguridad."</td>";
	$registro.="<td align='center'>".$mon.$dato->armario."</td>";
	$registro.="<td align='center'>".$mon.$dato->columna."</td>";
	$registro.="<td align='center'>".$dato->fila."</td>";
	/*$registro.="<td align='center'>".$dato->kardex."</td>";*/
	$registro.="<td><a href='list_listaunidad.php?IdProducto=$dato->idproducto'>Ver Lista Unidad</a></td>";
	/*$registro.="<td><a href='list_kardex.php?IdProducto=$dato->idproducto'>Ver Kardex</a> </td>";*/
	$registro.="<td><a href='mant_producto.php?accion=ACTUALIZAR&IdProducto=$dato->idproducto'>Actualizar</a></td>";
	$registro.="<td><a href='../negocio/cont_producto.php?accion=ELIMINAR&IdProducto=$dato->idproducto'>Eliminar</a></td>";
	}
	$registro.="</table>";
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_producto.php?desc=$descripcion&categoria=$categoria&marca=$marca','_blank')>";
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR CON PRECIOS' onClick=javascript:window.open('pdf_productoconprecio.php?desc=$descripcion&categoria=$categoria&marca=$marca','_blank')>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	return $obj;
}

$flistadoproductos = & $xajax->registerFunction("listadoproductos");
$flistadoproductos->setParameter(0,XAJAX_INPUT_VALUE,'txtDescripcion');
$flistadoproductos->setParameter(1,XAJAX_INPUT_VALUE,'cboCategoria');
$flistadoproductos->setParameter(2,XAJAX_INPUT_VALUE,'cboMarca');

function reporteproductosstock($descripcion,$categoria,$marca,$situacionstock)
{
	global $idsucursal;
	require("../negocio/cls_producto.php");
	$registro.="<table class=registros>";
	$registro.="
    <tr>
      	<th><div align='center'>CODIGO</div></th>
	  	<th><div align='center'>DESCRIPCION</div></th>
	  	<th><div align='center'>CATEGORIA</div></th>
		<th><div align='center'>MARCA</div></th></div>
		<th><div align='center'>UNIDAD BASE</div></th>
		<th><div align='center'>PESO</div></th>
		<th><div align='center'>MEDIDA</div></th>
		<th><div align='center'>ARMARIO</div></th>
		<th><div align='center'>COLUMNA</div></th>
		<th><div align='center'>FILA</div></th>
		<th><div align='center'>STOCK SEGURIDAD</div></th>
		<th><div align='center'>STOCK ACTUAL</div></th>
		</tr>";
		
		date_default_timezone_set('America/Bogota');
	   
	$objProducto = new clsProducto();
$rst = $objProducto->buscarProductosStockSuperado($idsucursal,NULL,$descripcion,$categoria,$marca,$situacionstock);
while($dato = $rst->fetchObject()){
	$cont++;
    if($cont%2) $estilo="par"; else $estilo="impar";
	$registro.="<tr class='$estilo'><td align='center'>".$dato->codigo."</td>";
	$registro.="<td align='center'>".$dato->descripcion."</td>";
	$registro.="<td align='center'>".$dato->categoria."</td>";
	$registro.="<td align='center'>".$dato->marca."</td>"; 
	$registro.="<td align='center'>".$dato->unidadbase."</td>";
	$registro.="<td align='center'>".$dato->peso."</td>";
	$registro.="<td align='center'>".$dato->medidapeso."</td>";
	$registro.="<td align='center'>".$mon.$dato->armario."</td>";
	$registro.="<td align='center'>".$mon.$dato->columna."</td>";
	$registro.="<td align='center'>".$dato->fila."</td>";
	$registro.="<td align='center'>".$mon.$dato->stockseguridad."</td>";
	$registro.="<td align='center'>".$mon.$dato->stockactual."</td>";	
	}
	$registro.="</table>";
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_productostockcero.php?desc=$descripcion&categoria=$categoria&marca=$marca&situacionstock=$situacionstock','_blank')>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	return $obj;
}

$freporteproductosstock = & $xajax->registerFunction("reporteproductosstock");
$freporteproductosstock->setParameter(0,XAJAX_INPUT_VALUE,'txtDescripcion');
$freporteproductosstock->setParameter(1,XAJAX_INPUT_VALUE,'cboCategoria');
$freporteproductosstock->setParameter(2,XAJAX_INPUT_VALUE,'cboMarca');
$freporteproductosstock->setParameter(3,XAJAX_INPUT_VALUE,'cboSituacion');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";

?>