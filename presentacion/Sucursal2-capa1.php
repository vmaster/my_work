<?php
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores
require("../datos/cado.php");
require("../negocio/cls_sucursal.php");
$sqlSentence="SELECT IdSucursal,Nombre, Direccion FROM Sucursal";
$EncabezadoTabla=array("Id","Nombre","Direccion");
$objSucursal = new clsSucursal();
function listado($campo,$frase,$pag,$TotalReg){
	Global $objSucursal;
	Global $sqlSentence;
	Global $EncabezadoTabla;
	$regxpag=10;
	$nr1=$TotalReg;
	$inicio=$regxpag*($pag - 1);
	$limite="";
	$frase=utf8_decode($frase);	
	if($inicio==0){
	$rs = $objSucursal->buscarconxajax($campo,$frase,$limite);	
    $nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $objSucursal->buscarconxajax($campo,$frase,$limite);	
    $nr=$rs->rowCount()*($pag);	
	$CantCampos=$rs->columnCount();
    $cadena="Registros encontrados: $nr de $nr1";
    $registros="
	<form name='frmlista' id='frmlista' method='post'>
	<table class=registros>
    <tr>
	<th> </th>";
	for($i=0;$i<count($EncabezadoTabla);$i++){
	$registros.="<th>".$EncabezadoTabla[$i]."</th>";
	}
	$registros.="<th>Editar</th></tr>";
	$cont=0;
    while($reg=$rs->fetch()){
	   $cont++;
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr class='$estilo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				$registros.= "<td><input type='checkbox' name='chk[]' id='chk[]' value='".$reg[$i]."'></td>";
				$registros.= "<td>".$reg[$i]."</td>";
				$RegistroEdicion="<td><a href='#' onClick='editar(".$reg[$i].")'>Editar</a></td>";
			}
			else{
				$registros.= "<td>".$reg[$i]."</td>";
			}
	   }
	   $registros.=$RegistroEdicion;
	   $registros.= "</tr>";
    }
    $registros.="</table></form><center>Pag: ";
	for($i=1;$i<=$nunPag;$i++){
		$registros.='<a href="#" onClick="javascript:document.getElementById(\'pag\').value='.$i.';buscar()">'.$i.' </a>';
	}
	$registros.='</center>';
	$registros=utf8_encode($registros);
	$objResp=new xajaxResponse();
	$objResp->assign('divnumreg','innerHTML',$cadena);
	$objResp->assign('divregistros','innerHTML',$registros);
	$objResp->assign('TotalReg','value',$nr1);
	return $objResp;
}

function guardar($ids,$nom,$dir){
   Global $objSucursal;
   if($ids==0){
	 $objSucursal->insertar($nom,$dir);   
     $mensaje="Registro insertado!";
   } else {
	 $objSucursal->actualizar($ids,$nom,$dir);
     $mensaje="Registro actualizado!";
   }
   $objResp=new xajaxResponse();
   $objResp->alert($mensaje);
   return $objResp;
}

function editar($id){
  Global $objSucursal;
  $rs=$objSucursal->consultar();
  $reg=$rs->fetchObject();
  $objResp=new xajaxResponse();
  $objResp->assign('txtIdSucursal','value',$reg->IdSucursal);
  $objResp->assign('txtNombre','value',$reg->Nombre);
  $objResp->assign('txtDireccion','value',$reg->Direccion);
  return $objResp;
}
function botones(){
   $contenido='<button type="button" class="boton" onClick="confirmar(1)"><img src="../imagenes/b_drop.png" width="16" height="16"> Eliminar</button>
<button type="button" class="boton" onClick="confirmar(2)"><img src="../imagenes/b_empty.png" width="16" height="16"> Vaciar</button>';
   $contenido=utf8_encode($contenido);
   $objResp=new xajaxResponse();
   $objResp->assign('DivBotones','innerHTML',$contenido);
   $objResp->assign('DivConfirmar','innerHTML','');
   return $objResp;
}
function confirmar($Origen){
	if($Origen==1){
   $mensaje="¿Está seguro que desea eliminar los registros seleccionados?<br>
<button type='button' class='boton' onClick='eliminar()'>Si</button>&nbsp;&nbsp;&nbsp;
<button type='button' class='boton' onClick='botones()'>No</button>";
	}else{
	$mensaje="¿Está seguro que desea vaciar todos los registros de la tabla?<br>
<button type='button' class='boton' onClick='vaciar()'>Si</button>&nbsp;&nbsp;&nbsp;
<button type='button' class='boton' onClick='botones()'>No</button>";
	}
   $mensaje=utf8_encode($mensaje);
   $objResp=new xajaxResponse();
   $objResp->assign('DivBotones','innerHTML','');
   $objResp->assign('DivConfirmar','innerHTML',$mensaje);
   return $objResp;
}
function eliminar($formulario){
   Global $objSucursal;
   $lista=$formulario['chk'];
   $n=count($lista);
   if($n>0)
   foreach($lista as $k => $valor){
   		$objSucursal->eliminar($valor);
   }
   $mensaje="$n registros eliminados!";
   $objResp=new xajaxResponse();
   $objResp->alert($mensaje);
   return $objResp;
}

function vaciar(){
   Global $objSucursal;
   $objSucursal->vaciar();
   $mensaje="registros eliminados!";
   $objResp=new xajaxResponse();
   $objResp->alert($mensaje);
   return $objResp;
}
$fbotones = & $xajax->registerFunction('botones');
$fconfirmar = & $xajax->registerFunction('confirmar');
$feliminar = & $xajax->registerFunction('eliminar');
$fvaciar = & $xajax->registerFunction('vaciar');
$feditar = & $xajax->registerFunction('editar');
$fguardar = & $xajax-> registerFunction('guardar');
$fguardar->setParameter(0,XAJAX_INPUT_VALUE,'txtIdSucursal');
$fguardar->setParameter(1,XAJAX_INPUT_VALUE,'txtNombre');
$fguardar->setParameter(2,XAJAX_INPUT_VALUE,'txtDireccion');

$flistado = & $xajax-> registerFunction('listado');
$flistado->setParameter(0,XAJAX_INPUT_VALUE,'campo');
$flistado->setParameter(1,XAJAX_INPUT_VALUE,'frase');
$flistado->setParameter(2,XAJAX_INPUT_VALUE,'Pag');
$flistado->setParameter(3,XAJAX_INPUT_VALUE,'TotalReg');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>