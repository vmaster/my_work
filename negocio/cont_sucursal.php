<?php
require("../datos/cado.php");
include_once("cls_sucursal.php");
controlador($_GET['accion']);

header('Location: ../presentacion/list_sucursal.php');

function controlador($accion)
{
  $objSucursal = new clsSucursal();
  if($accion=='NUEVO')	      
	return $objSucursal->insertar($_POST['txtNombre'], $_POST['txtDireccion'], $_POST['txtRuc']);      
  if($accion=='ACTUALIZAR')
	return $objSucursal->actualizar($_POST['txtIdSucursal'], $_POST['txtNombre'], $_POST['txtDireccion'], $_POST['txtRuc']);
  if($accion=='ELIMINAR')
	return $objSucursal->eliminar($_GET['IdSucursal']);
  if($accion=='CONSULTAR')
	return $objSucursal->consultar(); 
}
?>