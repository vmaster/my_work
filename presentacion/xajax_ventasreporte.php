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
 require("../negocio/cls_movimiento.php");

function generareporte($fechainicio,$fechafin,$moneda,$agrupado){

		$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
	

	$registro.="<table class='registros' width=100%>";
	$registro.="
    <tr>";
	if($agrupado=='mes'){
	 $registro.="<th rowspan='2'><div align='center'>MES</div></th>";
	 }else{
	 $registro.="<th rowspan='2'><div align='center'>AÑO</div></th>";
	 }
	 $registro.=" <th colspan='2'><div align='center'>BOLETAS</div></th> <th colspan='2'><div align='center'>FACTURAS</div></th> <th rowspan='2'><div align='center' >TOTAL CREDITO</div></th>
	  <th rowspan='2'><div align='center' >TOTAL CONTADO</div></th><th rowspan='2'><div align='center' colspan='2'>TOTAL</div></th>		            </tr>
    <tr> 
	  <th><div align='center'>CREDITO</div></th>
	  <th><div align='center'>CONTADO</div></th>
	 
	  <th><div align='center'>CREDITO</div></th>
	  <th><div align='center'>CONTADO</div></th>
	  
	 </tr>
	";

	date_default_timezone_set('America/Bogota');
	  
   $objMovimiento = new clsMovimiento();
	$rst = $objMovimiento->consultarreporte($fechainicio,$fechafin,$moneda,$agrupado);
	while($dato = $rst->fetchObject()){

		if($dato1->moneda=='D'){
		$mon='$ ';
		}else{
		$mon='S/. ';
		}
	if($cont%2) $estilo="impar";else $estilo="par";
	 $cont++;
	$registro.="<tr class='$estilo'>";
	if($agrupado=='mes'){
	$mes=$meses[date(($dato->mes)-1)];
	$registro.="<td align='center'>".$mes."</td>";
	}elseif($agrupado=='ano'){
	$registro.="<td align='center'>".$dato->ano."</td>";
	}
	
	$registro.="<td align='right'>".$mon.$dato->totalBB."</td>";
	$registro.="<td align='right'>".$mon.$dato->totalAB."</td>";
	$registro.="<td align='right'>".$mon.$dato->totalBF."</td>";
	$registro.="<td align='right'>".$mon.$dato->totalAF."</td>";
	$registro.="<td align='right'>".$mon.number_format($dato->totalBF+$dato->totalBB,2)."</td>";
	$registro.="<td align='right'>".$mon.number_format($dato->totalAB+$dato->totalAF,2)."</td>";
	$registro.="<td align='right'>".$mon.number_format($dato->totalAB+$dato->totalAF+$dato->totalBF+$dato->totalBB,2)."</td>";
	$tot= $dato->totalAB+$dato->totalAF+$dato->totalBF+$dato->totalBB;
	$total = number_format($total + $tot,2); 	
	if($agrupado=='mes'){
	$datos[$mes]=$tot;
	}elseif($agrupado=='ano'){
	$datos[$dato->ano]=$tot;
	}

	}
	
	$registro.="<tr class='par'><td colspan='7'><div align='right'>TOTAL: </div></td><td><div align='right'>".$mon.$total."</div></td></tr></table>";
	$registro.="<center><a href=\"#\" onClick=javascript:window.open('pdf_ventas.php?inicio=$fechainicio&final=$fechafin&formap=$formapago&moneda=$moneda&agrupado=$agrupado','_blank')><img src=\"../imagenes/print_f2.png\" with=20 height=20>&nbsp;Imprimir</a>&nbsp;";
	$_SESSION['data']=$datos;
	$registro.="<a href=\"#\" onclick=\"javascript: if(document.getElementById('divGrafico').style.display=='') document.getElementById('divGrafico').style.display='none'; else document.getElementById('divGrafico').style.display=''\"><img src=\"../imagenes/estadistica.png\" with=20 height=20>&nbsp;ver Gr&aacute;fico</a></center><br>";
	$registro.='<div id="divGrafico" style="display:none"><center><div id="titulo01">Gr&aacute;fico de Ventas</div><img src="grafico2.php"/></center></div>';
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	//$obj->alert($nromeses);
	return $obj;
}

$fgenerareporte = & $xajax->registerFunction("generareporte");
$fgenerareporte->setParameter(0,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerareporte->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgenerareporte->setParameter(2,XAJAX_INPUT_VALUE,'cboMoneda');
$fgenerareporte->setParameter(3,XAJAX_INPUT_VALUE,'cboAgrupado');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";

?>