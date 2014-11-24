<?php
require("../datos/cado.php");
include_once("cls_rolpersona.php");
controlador($_GET['accion']);

function controlador($accion)
{
  $objRolPersona = new clsRolPersona();
  if($accion=='NUEVO')	{
  $objRolPersona = new clsRolPersona();
	$rst=$objRolPersona->consultarrolpersona($_GET['IdPersona'],$_POST['cboRol']);      
  	$dato = $rst->rowCount();
		if($dato==1){
			echo "<script>alert('Persona Ya Tiene Asignado Este Permiso')</script>";
		}elseif($dato==0){
$objRolPersona->insertar($_GET['IdPersona'],$_POST['cboRol']);      
		}
		echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_persona.php'>";
   }
  if($accion=='ACTUALIZAR'){
header('Location: ../presentacion/list_persona.php');
	return $objRolPersona->actualizar($_POST['txtIdRolPersona'],$_POST['txtIdPersona'],$_POST['cboRol']);
}
  if($accion=='ELIMINAR'){
  
   $objRolPersona = new clsRolPersona();
	$rst=$objRolPersona->consultarrolpersona($_GET['IdPersona'],NULL);      
  	$dato = $rst->rowCount();
		if($dato==1){
			echo "<script>alert('Persona Tiene Que Tener Asignado Por Lo Menos Un Permiso')</script>";
		}else{
   $objRolPersona->eliminar($_GET['IdRolPersona']);
		}
 echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_persona.php'>";
	}
  if($accion=='CONSULTAR'){
	header('Location: ../presentacion/list_persona.php');
	return $objRolPersona->consultarrolpersona($_GET['IdPersona']); }
  if($accion=='BUSCAR'){
  header('Location: ../presentacion/list_persona.php');
	return $objRolPersona->buscar($_POST['txtIdRolPersona']); }
}
?>