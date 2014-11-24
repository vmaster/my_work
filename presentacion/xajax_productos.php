<?php 
session_start();
/*if(!isset($_SESSION['Usuario']))
{
  header("location: ../presentacion/login.php?error=1");
}*/
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else {$idsucursal=1;}
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores
require("../datos/cado.php");

// require("../negocio/cls_tipocambio.php");

function generareporte($categoria,$fechainicio,$fechafin,$moneda,$marca){
 require("../negocio/cls_producto.php");
	$registro.="<table border='1'>";
	$registro.="
    <tr>
      	  <td ><div align='center'>CODIGO</div></td>
	  <td ><div align='center'>DESCRIPCION</div></td>
      <td ><div align='center'>CATEGORIA</div></td>
	  <td ><div align='center'>MARCA</div></td>		         
	  <td ><div align='center'>UNIDAD BASE</div></td>
      <td ><div align='center'>PESO</div></td>
      <td ><div align='center'>MEDIDA PESO</div></td>
	  <td ><div align='center'>PRECIO COMPRA</div></td>
	  <td ><div align='center'>PRECIO VENTA</div></td>
     <td ><div align='center'>PRECIO VENTA ESPECIAL</div></td>
	      <td ><div align='center'>CANTIDAD VECES</div></td>
	 <td ><div align='center'>TOTAL VENTAS</div></td>

     
    </tr>";
	
	date_default_timezone_set('America/Bogota');
	
   
	$objProducto = new clsProducto();
$rst = $objProducto->consultarproductosmasvendidos($categoria,$fechainicio,$fechafin,$moneda,$marca);
while($dato = $rst->fetchObject()){
	
		$registro.="<tr>";
		
	/*	
	if($dato->total>=($total)*0.7){
	$cat='A';
	}else{
	
	if($dato->total<=($total)*0.3){
	$cat='C';
	}else $cat='B';
	}*/

 	if($moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}
	
	$registro.="<tr><td align='center'>".$dato->codigo."</td>";
	$registro.="<td align='center'>".$dato->descripcion."</td>";
	$registro.="<td align='center'>".$dato->categoria."</td>";
	$registro.="<td align='center'>".$dato->marca."</td>"; 
	$registro.="<td align='center'>".$dato->unidadbase."</td>";
	$registro.="<td align='center'>".$dato->peso."</td>";
	$registro.="<td align='center'>".$dato->medidapeso."</td>";
	$registro.="<td align='center'>".$mon.$dato->pcompra."</td>";
	$registro.="<td align='center'>".$mon.$dato->pventaespecial."</td>";
	$registro.="<td align='center'>".$mon.$dato->pventa."</td>";
		$registro.="<td align='center'>".$dato->veces."</td>";
	$registro.="<td align='center'>".$mon.$dato->total."</td></tr>";
	}
	$registro.="</table>";
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_productosmasvendidos.php?idcategoria=$categoria&inicio=$fechainicio&final=$fechafin&moneda=$moneda&marca=$marca','_blank')>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	return $obj;
}

$fgenerareporte = & $xajax->registerFunction("generareporte");
$fgenerareporte->setParameter(0,XAJAX_INPUT_VALUE,'cboCategoria');
$fgenerareporte->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerareporte->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgenerareporte->setParameter(3,XAJAX_INPUT_VALUE,'cboMoneda');
$fgenerareporte->setParameter(4,XAJAX_INPUT_VALUE,'cboMarca');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";

?>