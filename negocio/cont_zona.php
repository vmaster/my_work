<?php
require("../datos/cado.php");
require("cls_zona.php");
controlador($_GET['accion']);


function controlador($accion)
{
  $ObjZona = new clsZona();
  if($accion=='NUEVO'){	      
  	header('Location: ../presentacion/list_zona.php');
	return $ObjZona ->insertar('',$_POST['txtDescripcion']); }     
  if($accion=='ACTUALIZAR'){
	header('Location: ../presentacion/list_zona.php');
	return $ObjZona ->actualizar($_POST['txtIdZona'], $_POST['txtDescripcion']);}
  if($accion=='ELIMINAR'){
   include_once("cls_persona.php");
	$objPersona= new clsPersona();
  	$rst=$objPersona->buscar1(NULL,NULL,NULL,NULL,NULL,NULL,$_GET['IdZona']);
	$dato = $rst->rowCount();
	if($dato>0){
		echo "<script>alert('Zona No Se Puede Eliminar Porque Esta Siendo Usada')</script>";
	}else{
		$ObjZona->eliminar($_GET['IdZona']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_zona.php'>";
  }
  
  if($accion=='CONSULTAR'){
	header('Location: ../presentacion/list_zona.php');
	return $ObjZona ->consultar();} 
}
?>
