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
	$registro.="<table class=registros>";
	$registro.="
    <tr>
      	  <th><div align='center'>CODIGO</div></th>
	  <th><div align='center'>DESCRIPCI&Oacute;N</div></th>
      <th><div align='center'>CATEGOR&Iacute;A</div></th>
	  <th><div align='center'>MARCA</div></th>		         
	  <th><div align='center'>UNIDAD BASE</div></th>
      <th><div align='center'>PESO</div></th>
      <th><div align='center'>MEDIDA PESO</div></th>
	  <th><div align='center'>PRECIO COMPRA</div></th>
	  <th><div align='center'>PRECIO VENTA</div></th>
     <th><div align='center'>PRECIO VENTA ESPECIAL</div></th>
      <th><div align='center'>CANTIDAD VECES</div></th>
	 <th><div align='center'>TOTAL VENTAS</div></th>

     
    </tr>";
	
	date_default_timezone_set('America/Bogota');
	
   
	$objProducto = new clsProducto();
$rst = $objProducto->consultarproductosmasvendidos($categoria,$fechainicio,$fechafin,$moneda,$marca);
while($dato = $rst->fetchObject()){
	
		$registro.="<tr>";
 	if($moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}
	 if($cont%2) $estilo="impar";else $estilo="par";
	 $cont++;
	$registro.="<tr class='$estilo'><td align='center'>".$dato->codigo."</td>";
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
	$datos[$dato->codigo]=$dato->veces;
	}
	$registro.="</table>";
	$registro.="<center><a href=\"#\" onClick=javascript:window.open('pdf_productosmasvendidos.php?idcategoria=$categoria&inicio=$fechainicio&final=$fechafin&moneda=$moneda&marca=$marca','_blank')><img src=\"../imagenes/print_f2.png\" with=20 height=20>&nbsp;Imprimir</a>&nbsp;";
	$_SESSION['data']=$datos;
	$registro.="<a href=\"#\" onclick=\"javascript: if(document.getElementById('divGrafico').style.display=='') document.getElementById('divGrafico').style.display='none'; else document.getElementById('divGrafico').style.display=''\"><img src=\"../imagenes/estadistica.png\" with=20 height=20>&nbsp;ver Gr&aacute;fico</a></center><br>";
	$registro.='<div id="divGrafico" style="display:none"><center><div id="titulo01">Gr&aacute;fico de Productos m&aacute;s Vendidos</div><img src="grafico2.php"/></center></div>';
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