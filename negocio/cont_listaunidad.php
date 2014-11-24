<?php
require("../datos/cado.php");
require("cls_listaunidad.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_listaunidad.php?IdProducto='.$_GET['IdProducto']);

function controlador($accion)
{
  $ObjListUnidad = new clsListaUnidad();
  if($accion=='NUEVO')	      
	return $ObjListUnidad->insertar($_POST['txtIdListaUnidad'], $_POST['txtIdProducto'],$_POST['cboUnidad'], $_POST['txtIdUnidadBase'], $_POST['txtFormula'], $_POST['txtPrecioCompra'],$_POST['txtPrecioVenta'],$_POST['txtPrecioEspecial'], $_POST['cboMoneda']);   
  if($accion=='ACTUALIZAR')
	return $ObjListUnidad->actualizar($_POST['txtIdListaUnidad'], $_POST['txtIdProducto'],$_POST['cboUnidad'], $_POST['txtIdUnidadBase'], $_POST['txtFormula'], $_POST['txtPrecioCompra'],$_POST['txtPrecioVenta'], $_POST['txtPrecioEspecial'], $_POST['cboMoneda']);
  if($accion=='ELIMINAR')
	return $ObjListUnidad->eliminar($_GET['IdListaUnidad']);
  if($accion=='CONSULTAR')
	return $ObjListUnidad->consultar(); 
  if($accion=='BUSCAR')
	return $ObjListUnidad->buscar($_GET['IdProducto'], $_POST['txtDescripcion']); 
}
?>