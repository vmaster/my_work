<?php
require("../datos/cado.php");
include_once("cls_sector.php");
controlador($_GET['accion']);

function controlador($accion)
{
  $objSector = new clsSector();
  if($accion=='NUEVO'){	      
header('Location: ../presentacion/list_sector.php');
	return $objSector->insertar($_POST['txtDescripcion']);}      
  if($accion=='ACTUALIZAR'){
  header('Location: ../presentacion/list_sector.php');
	return $objSector->actualizar($_POST['txtIdSector'], $_POST['txtDescripcion']);}
  if($accion=='ELIMINAR'){
     include_once("cls_persona.php");
	$objPersona= new clsPersona();
  	$rst=$objPersona->buscar1(NULL,NULL,NULL,NULL,NULL,$_GET['IdSector'],NULL);
	$dato = $rst->rowCount();
	if($dato>0){
		echo "<script>alert('Sector No Se Puede Eliminar Porque Esta Siendo Usado')</script>";
	}else{
		$objSector->eliminar($_GET['IdZona']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_zona.php'>";
  }
  if($accion=='CONSULTAR'){
  header('Location: ../presentacion/list_sector.php');
	return $objSector->consultar(); }
  if($accion=='BUSCAR'){
  header('Location: ../presentacion/list_sector.php');
	return $objSector->buscar($_POST['txtIdSector']);} 
}
?>