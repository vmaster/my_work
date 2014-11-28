<?php
session_start();

@require('../xajax/xajax_core/xajax.inc.php');

$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores


//if(isset($_SESSION['FechaProceso'])){
//$fechaproceso = $_SESSION['FechaProceso'];
//}else {
//$fechaproceso = date("Y-m-d");
//$_SESSION['FechaProceso']=date("Y-m-d");
//}


require("../datos/cado.php");

//$objLetra=new letra();

function presentarboleta($idventa){

	//require("../negocio/cls_persona.php");

require("../negocio/cls_movimiento.php");
$objMovimiento=new clsMovimiento();

require("../negocio/cls_persona.php");
$objPersona=new clsPersona();

$rst=$objMovimiento->consultarventa(2,$idventa,NULL,NULL,NULL,NULL,NULL,NULL);
$detalle2 = $rst->fetchObject();

$pers=$objPersona->buscar($detalle2->idcliente,NULL, NULL);
$detallePersona = $pers->fetchObject();

$reg.="<br><table width='660' style='margin:21;' style='font-size:13px;'>";
	$registros.="<table width='512' style='margin:0;' style='font-size:13px;'>";
	
	/*$registros.="<tr>
		<th width='49' align='center' bgcolor='#CCCCCC'><strong>Cant.</strong></th>
        <th width='312' align='center' bgcolor='#CCCCCC'><strong>DESCRIPCION</strong></th>
		<th width='55' align='center' bgcolor='#CCCCCC'><strong>P. Unit.</strong></th>
		<th width='6' align='center' bgcolor='#CCCCCC'><strong>IMPORTE</strong></th>
    </tr>";
	
		$reg.="<tr>
		<th width='100' align='center'><strong>&nbsp;</strong></th>
		<th width='120' align='center'><strong></strong></th>
        <th width='340' align='center'><strong></strong></th>
		<th width='100' align='center'><strong></strong></th>
		<th width='100' align='center'><strong></strong></th>
    </tr>";*/
	
	$i=0;
$detalle=$objMovimiento->consultardetalleventa($idventa);
  while($dato = $detalle->fetchObject()){
  
  
	if(($i+1)%2==0)
		$color=" bgcolor='#CCCCCC'";
	else
		$color=" ";
	$registros.="<tr>
    <td align='center' $color>".$dato->cantidad."</td>";
	$descripcion=$dato->producto.' /'.$dato->marca.'-'.$dato->peso.$dato->unidadpeso."";
	if(strlen($descripcion)>=36){
		$descripcion=substr($descripcion,0,35);
	}
    $registros.="<td align='left' $color>".$descripcion." </td>
    <td align='right' >".$dato->precioventa."&nbsp;&nbsp; </td>
    <td align='right' >".number_format($dato->subtotal,2)."&nbsp;&nbsp;&nbsp; </td>
  </tr>";
  if($i<11)
    $reg.="<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>";
  $i=$i+1;
  }
  
  while($i<8){
  if(($i+1)%2==0)
		$color=" bgcolor='#CCCCCC'";
	else
		$color=" ";
  $registros.="<tr>
    <td align='center' ></td>
    <td align='center' ></td>
    <td align='right' >&nbsp;&nbsp; </td>
    <td align='center' > - </td>
  </tr>";
  if($i<10)
    $reg.="<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>";
	
  $i=$i+1;
  }
	
 	$registros.=" </table>";
	
	if($detalle2->formapago=="A"){
	$FP="CONTADO";
	$NC='0';
	$Inicial='S/. 0.00';
	}else{
	$FP= "CREDITO";
	$rst5=$objMovimiento->consultarcuotaventaNoAnulada($idventa);
		$NC=$rst5->rowCount();
		$suma=0;
		while($dato = $rst5->fetchObject()){
		$suma=$suma+$dato->monto;
		}
		if($detalle2->moneda=='S'){
		$Inicial= 'S/. '.number_format(($detalle2->total-$suma),2);
		}else{
		$Inicial= '$ '.($detalle2->total-$suma);
		}
	}
	if($detalle2->moneda=="S"){
	$etiquetaMonedal="S/.";
	}else{
	$etiquetaMoneda="$ ";
	}
	

	
	$us=$objPersona->buscar($detalle2->idsucursal,NULL,NULL);
	$sucur=$us->fetchObject();
	$descripcionS=$sucur->Direccion;
	$NroDocS=$sucur->NroDoc;
	$celularS=$sucur->Celular;
	
	if(strlen($detalle2->sucursal)>25){
	$nombresucursal=substr($detalle2->sucursal,0,25);
	}else{
	$nombresucursal=$detalle2->sucursal;
	}
	
	
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalleVenta","innerHTML",$registros);
	$obj->assign("DivDetalleVentax","innerHTML",$reg);
	
	$obj->assign("DivNombreSucursal","innerHTML",$nombresucursal);
	$obj->assign("DivDetalleSucursal","innerHTML",$descripcionS." - TELF. ".$celularS);
	$obj->assign("DivRucSucursal","innerHTML",$NroDocS);
	
	$obj->assign("DivNumDoc","innerHTML",utf8_encode("N� ".substr($detalle2->numero,4,6)));
	$obj->assign("diaFecha","innerHTML","&nbsp;&nbsp;&nbsp;&nbsp;".substr($detalle2->fecha,0,2));
	$obj->assign("mesFecha","innerHTML","&nbsp;&nbsp;&nbsp;".substr($detalle2->fecha,3,2));
	$obj->assign("anioFecha","innerHTML","&nbsp;&nbsp;&nbsp;".substr($detalle2->fecha,6,4));
	
	$obj->assign("divNombreCliente","innerHTML","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$detalle2->cliente.' '.$detalle2->acliente);
	$obj->assign("divTP","innerHTML",$FP);
	$obj->assign("DivTotal","innerHTML",$etiquetaMonedal."&nbsp;".$detalle2->total."");
	$obj->assign("DivNCuotas","innerHTML",$NC);
	$obj->assign("DivInicial","innerHTML","&nbsp;".$Inicial);
	$obj->assign("DivDocIdentidad","innerHTML",$detallePersona->NroDoc."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	$obj->assign("DivDireccionCliente","innerHTML","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$detallePersona->Direccion);


	return $obj;
}


function presentarfactura($idventa){


require("../negocio/cls_movimiento.php");
$objMovimiento=new clsMovimiento();

require("../negocio/cls_persona.php");
$objPersona=new clsPersona();

$rst=$objMovimiento->consultarventa(2,$idventa,NULL,NULL,NULL,NULL,NULL,NULL);
$detalle2 = $rst->fetchObject();

$pers=$objPersona->buscar($detalle2->idcliente,NULL, NULL);
$detallePersona = $pers->fetchObject();

$reg.="<br><table width='670' style='margin:2;' style='font-size:13px;'>";
$reg.="<tr>
		<th width='100' align='center' bgcolor='#CCCCCC'>CANT.<strong></strong></th>
		<th width='120' align='center' bgcolor='#CCCCCC'><strong>UNID.</strong></th>
        <th width='350' align='center' bgcolor='#CCCCCC'><strong>DESCRIPCION</strong></th>
		<th width='100' align='center' bgcolor='#CCCCCC'><strong>P.UNIT.</strong></th>
		<th width='100' align='center' bgcolor='#CCCCCC'><strong>TOTAL</strong></th>
    </tr>";

	$registros.="<br><table width='660' style='margin:2;' style='font-size:13px;'>";

	$registros.="<tr>
		<th width='100' align='center'><strong>&nbsp;</strong></th>
		<th width='120' align='center'><strong></strong></th>
        <th width='340' align='center'><strong></strong></th>
		<th width='100' align='center'><strong></strong></th>
		<th width='100' align='center'><strong></strong></th>
    </tr>";
	
	$i=0;
$detalle=$objMovimiento->consultardetalleventa($idventa);
  while($dato = $detalle->fetchObject()){
  
  if(($i+1)%2==0)
		$color=" bgcolor='#CCCCCC'";
	else
		$color=" ";
  
  $registros.="<tr>
    <td align='center' >".$dato->cantidad."</td>
	<td align='center' >".$dato->unidad."</td>";
	$descripcion=$dato->producto.' /'.$dato->categoria.'_'.$dato->marca.'_'.$dato->peso.$dato->unidadpeso."";
	if(strlen($descripcion)>=36){
		$descripcion=substr($descripcion,0,35);
	}
	$registros.="<td align='left' >&nbsp;".$descripcion." </td>
    <td align='right'   >".$dato->precioventa."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
    <td align='right'  >".number_format($dato->subtotal,2)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
  </tr>";
  
 $reg.="<tr><td $color>&nbsp;</td><td $color>&nbsp;</td><td $color>&nbsp;</td><td  $color>&nbsp;</td><td  $color>&nbsp;</td>";

  $i=$i+1;
  }
  
  while($i<12){
  
  if(($i+1)%2==0)
		$color=" bgcolor='#CCCCCC'";
	else
		$color=" ";
		
  $registros.="<tr>
    <td align='center'>&nbsp;</td>
	<td align='center' >&nbsp;</td>
    <td align='center' >&nbsp;</td>
    <td align='right' >&nbsp;&nbsp; </td>
    <td align='center'> - </td>
  </tr>";
  $reg.="<tr><td $color>&nbsp;</td><td $color>&nbsp;</td><td $color>&nbsp;</td><td  $color>&nbsp;</td><td  $color>&nbsp;</td>";

  $i=$i+1;
  }
	
 	$registros.=" </table>";
	
	if($detalle2->formapago=="A"){
	$FP="CONTADO";
	$NC='0';
	$Inicial='0.00';
	}else{
	$FP= "CREDITO";
	$rst5=$objMovimiento->consultarcuotaventaNoAnulada($idventa);
		$NC=$rst5->rowCount();
		$suma=0;
		while($dato = $rst5->fetchObject()){
		$suma=$suma+$dato->monto;
		}
		if($detalle2->moneda=='S'){
		$Inicial= 'S/. '.number_format(($detalle2->total-$suma),2);
		}else{
		$Inicial= '$ '.($detalle2->total-$suma);
		}
	}
	if($detalle2->moneda=="S"){
	$etiquetaTotal="S/. ";
	}else{
	$etiquetaTotal="$ ";
	}
	
	//require_once("../negocio/numAletras.php");
	
	//$enletras=return num2letras('505', true,true,'S');
	$_SESSION['enletra']=$detalle2->total;
	$_SESSION['enletraMoneda']=$detalle2->moneda;
	
	if(substr($detalle2->numero,4,6)==false){
		$nd="Sin Entregar";
	}else{
		$nd=substr($detalle2->numero,4,6);
	}
	
	$us=$objPersona->buscar($detalle2->idsucursal,NULL,NULL);
	$sucur=$us->fetchObject();
	$descripcionS=$sucur->Direccion;
	$celularS=$sucur->Celular;
	$NroDocS=$sucur->NroDoc;
	
	
	if(strlen($detalle2->sucursal)>35){
	$nombresucursal=substr($detalle2->sucursal,0,35);
	}else{
	$nombresucursal=$detalle2->sucursal;
	}
	
	$nombresucursal=utf8_encode($nombresucursal);
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	//$obj->assign("DivTotalEnLetras","innerHTML", $enletras);
	$obj->assign("DivNumeroDoc","innerHTML", $nd);
	$obj->assign("DivSerieDoc","innerHTML",utf8_encode(substr($detalle2->numero,0,3)."&nbsp;&nbsp;&nbsp;&nbsp;N� "));
	$obj->assign("DivDetalleVentaa","innerHTML",$registros);
	$obj->assign("DivDetalleFondo","innerHTML",$reg);
	
	$obj->assign("DivNombreSucursal","innerHTML",$nombresucursal);
	$obj->assign("DivDetalleSucursal","innerHTML",$descripcionS . " - TELF. ".$celularS);
	$obj->assign("DivRucSucursal","innerHTML","R.U.C. ".$NroDocS);

	$obj->assign("DivFechaEmision","innerHTML",substr($detalle2->fecha,0,2)."&nbsp;&nbsp;&nbsp;".substr($detalle2->fecha,3,2)."&nbsp;&nbsp;&nbsp;".substr($detalle2->fecha,6,4));
	
	$obj->assign("DivRUC","innerHTML",$detallePersona->NroDoc);
	$obj->assign("divNombreCliente","innerHTML",$detalle2->cliente.' '.$detalle2->acliente);
	$obj->assign("DivFP","innerHTML","&nbsp;&nbsp;&nbsp;&nbsp;".$FP);
	$obj->assign("DivSubTotal","innerHTML",$etiquetaTotal."".$detalle2->subtotal."&nbsp;&nbsp;&nbsp;&nbsp;");
	$obj->assign("DivIgv","innerHTML",$etiquetaTotal."".$detalle2->igv."&nbsp;&nbsp;&nbsp;&nbsp;");
	$obj->assign("DivTotall","innerHTML",$etiquetaTotal.$detalle2->total."&nbsp;&nbsp;&nbsp;&nbsp;");
	$obj->assign("DivNumeroCuotas","innerHTML",$NC."&nbsp;&nbsp;&nbsp;&nbsp;");
	$obj->assign("DivIniciall","innerHTML",$Inicial);
	$obj->assign("DivGuiaRemision","innerHTML","-----------");
	$obj->assign("DivDireccionCliente","innerHTML","&nbsp;&nbsp;".$detallePersona->Direccion);

	//$obj->assign("DivEtiquetaTotal","innerHTML",$etiquetaTotal);

	return $obj;
}


$xajax->registerFunction('presentarboleta');

$xajax->registerFunction('presentarfactura');


$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>