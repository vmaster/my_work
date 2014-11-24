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
// require("../negocio/cls_tipocambio.php");

function generareporte($idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda){
	/*$ObjTipoCambio = new clsTipoCambio();
	$rst2 = $ObjTipoCambio->buscarxfecha('CURDATE()');
	$dato2 = $rst2->fetchObject();
	if(isset($dato2->Cambio)) {
	$cambio=$dato2->Cambio;}
	else{
	$cambio='2.85';}
	*/
	$objMovimiento = new clsMovimiento();
	$rst1 = $objMovimiento->consultarrpt(2,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda);
	while($dato1 = $rst1->fetchObject()){
		/*if($dato1->moneda=='D'){
		$totalcliente=number_format($dato1->totalcli*$cambio,2);
		}else{*/
		$totalcliente=$dato1->totalcli;
	//	}
	$total=$total+$totalcliente;
		if($dato1->moneda=='D'){
		$mon='$ ';
		}else{
		$mon='S/. ';
		}
	}
	$registro.="<table class=registros>";
	$registro.="<tr><th>TOTAL: </th><td>".$mon.$total."</td></tr>
    <tr>
      
	  <th ><div align='center'>TIPO CLIENTE</div></th>
	  <th ><div align='center'>CLIENTE</div></th>
      <th ><div align='center'>DNI/RUC</div></th>
	  <th ><div align='center'>SECTOR</div></th>		         
	  <th ><div align='center'>ZONA</div></th>
      <th ><div align='center'>TOTAL VENTAS</div></th>
      <th ><div align='center'>CATEGORIA</div></th>
	  <th ><div align='center'>ULTIMA VENTA</div></th>

     
    </tr>";
	
	date_default_timezone_set('America/Bogota');
	
   
	$rst = $objMovimiento->consultarrpt(2,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda);
	while($dato = $rst->fetchObject()){
	if($cont%2) $estilo="par";else $estilo="impar";
	 $cont++;
	$registro.="<tr class='$estilo'>";
		
	if($dato->totalcli>=($total)*0.7){
	$cat='A';
	}else{
	
	if($dato->totalcli<=($total)*0.3){
	$cat='C';
	}else $cat='B';
	}

 	if($dato->moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}
	
	
	
	$registro.="<td align='center'>".$dato->tipopersona."</td>";
	$registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->nrodoc."</td>";
	$registro.="<td align='center'>".$dato->sector."</td>"; 
	$registro.="<td align='center'>".$dato->zona."</td>";
	$registro.="<td align='center'>".$mon.$dato->totalcli."</td>";
	$registro.="<td align='center'>".$cat."</td>";
	$registro.="<td align='center'>".$dato->fecha."</td>";
	}
	
	$registro.="</table>";
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_ABCcliente.php?idzona=$idzona&idsector=$idsector&inicio=$fechainicio&final=$fechafin&tipodoc=$idtipodocumento&formap=$formapago&moneda=$moneda','_blank')>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	return $obj;
}

$fgenerareporte = & $xajax->registerFunction("generareporte");
$fgenerareporte->setParameter(0,XAJAX_INPUT_VALUE,'cboSector');
$fgenerareporte->setParameter(1,XAJAX_INPUT_VALUE,'cboZona');
$fgenerareporte->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerareporte->setParameter(3,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgenerareporte->setParameter(4,XAJAX_INPUT_VALUE,'cboTipoDoc');
$fgenerareporte->setParameter(5,XAJAX_INPUT_VALUE,'cboFormaPago');
$fgenerareporte->setParameter(6,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";

?>