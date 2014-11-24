<?php
require("../datos/cado.php");
include_once("cls_rol.php");
controlador($_GET['accion']);

function controlador($accion)
{
  $objRol = new clsRol();
  if($accion=='NUEVO'){
	header('Location: ../presentacion/list_rol.php'); 
	return $objRol->insertar($_POST['txtDescripcion'],$_POST['cboEstado']);    
	 }
  if($accion=='ACTUALIZAR'){
	header('Location: ../presentacion/list_rol.php');
	return $objRol->actualizar($_POST['txtIdRol'], $_POST['txtDescripcion'],$_POST['cboEstado']);
}
  if($accion=='ELIMINAR'){
  include_once("cls_rolpersona.php");
	$objRolPersona= new clsRolPersona();
  	$rst=$objRolPersona->buscarrol($_GET['IdRol']);
	$dato = $rst->rowCount();
	
	if($dato>0){
		echo "<script>alert('Rol No Se Puede Eliminar Porque Esta Siendo Usado')</script>";
	}else{
		$objRol->eliminar($_GET['IdRol']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_rol.php'>";

	}
	
  if($accion=='CONSULTAR'){
	header('Location: ../presentacion/list_rol.php');
	return $objRol->consultar();} 
  if($accion=='BUSCAR'){
	header('Location: ../presentacion/list_rol.php');
	return $objRol->buscar($_POST['txtIdRol']); 
	}
}
?>