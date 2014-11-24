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

 require_once("../negocio/cls_persona.php");
// require("../negocio/cls_tipocambio.php");

function listadopersona($nombres,$apellidos,$tipopersona,$rol,$sector,$zona){
require("../datos/cado.php");
	$registro.="<table class=registros>";
	$registro.="<tr>
	  <th><div align='center'>TIPO PERSONA</div></th>
      <th><div align='center'>NOMBRES</div></th>
	  <th><div align='center'>APELLIDOS</div></th>		         
	  <th><div align='center'>NUMERO DOCUMENTO</div></th>
      <th><div align='center'>SEXO</div></th>
      <th><div align='center'>DISTRITO</div></th>
      <th><div align='center'>PROVINCIA</div></th>
      <th><div align='center'>DEPARTAMENTO</div></th>
      <th><div align='center'>DIRECCION</div></th>
	  <th><div align='center'>CELULAR</div></th>
	  <th><div align='center'>EMAIL</div></th>
	  <th><div align='center'>SECTOR</div></th>
	  <th><div align='center'>ZONA</div></th>
	  <th colspan='3'><div align='center'>OPERACIONES</div></th>
 
    </tr>";
	
	date_default_timezone_set('America/Bogota');
	
   $objPersona= new clsPersona();
	$rst = $objPersona->buscar1(NULL,$nombres,$apellidos,$tipopersona,$rol,$sector,$zona);
	while($dato = $rst->fetchObject()){
		$cont++;
	   if($cont%2) $estilo="par"; else $estilo="impar";
	$registro.="<tr class='$estilo'>";
	$registro.="<td align='center'>".$dato->tipopersona."</td>";
	$registro.="<td align='center'>".$dato->nombres."</td>";
	$registro.="<td align='center'>".$dato->apellidos."</td>";
	$registro.="<td align='center'>".$dato->nrodoc."</td>"; 
	$registro.="<td align='center'>".$dato->sexo."</td>";
	$registro.="<td align='center'>".$dato->distrito."</td>";
	$registro.="<td align='center'>".$dato->provincia."</td>";
    $registro.="<td align='center'>".$dato->departamento."</td>";
	$registro.="<td align='center'>".$dato->direccion."&nbsp;</td>";
	$registro.="<td align='center'>".$dato->celular."&nbsp;</td>";
	$registro.="<td align='center'>".$dato->email."&nbsp;</td>";
	$registro.="<td align='center'>".$dato->sector."</td>";
	$registro.="<td align='center'>".$dato->zona."</td>";
	$registro.="<td><a href='list_rolpersona.php?accion=CONSULTAR&IdPersona=$dato->idpersona'>Ver Roles</a></td>";
    $registro.="<td><a href='mant_persona.php?accion=ACTUALIZAR&IdPersona=$dato->idpersona'>Actualizar</a></td>";
    $registro.="<td><a href='../negocio/cont_persona.php?accion=ELIMINAR&origen=LIST&IdPersona=$dato->idpersona'>Eliminar</a></td>";
  $registro.="</tr>";
	}
	$registro.="</table>";
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_persona.php?nombres=$nombres&apellidos=$apellidos&tipoper=$tipopersona&rol=$rol&sector=$sector&zona=$zona','_blank')>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	return $obj;
}

$flistadopersona= & $xajax->registerFunction("listadopersona");
$flistadopersona->setParameter(0,XAJAX_INPUT_VALUE,'txtNombres');
$flistadopersona->setParameter(1,XAJAX_INPUT_VALUE,'txtApellidos');
$flistadopersona->setParameter(2,XAJAX_INPUT_VALUE,'cboTipo');
$flistadopersona->setParameter(3,XAJAX_INPUT_VALUE,'cboRol');
$flistadopersona->setParameter(4,XAJAX_INPUT_VALUE,'cboSector');
$flistadopersona->setParameter(5,XAJAX_INPUT_VALUE,'cboZona');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";

?>