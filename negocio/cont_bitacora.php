<?php
require("../datos/cado.php");
require("cls_bitacora.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_bitacora.php');

function controlador($accion)
{
  $ObjBitacora = new clsBitacora();
  if($accion=='NUEVO'){    
	return $ObjBitacora->insertar($_POST['txtIdBitacora'], $_POST['txtIdMovimiento'], $_POST['cboIdUsuario'], $_POST['txtFechaHora'], $_POST['txtComentario']);
	}
   
  //if($accion=='ACTUALIZAR')
	//return $ObjBitacora->actualizar($_POST['txtIdBitacora'], $_POST['txtIdMovimiento'], $_POST['cboIdUsuario'], $_POST['txtFechaHora'], $_POST['txtComentario']);
  if($accion=='ELIMINAR')
	return $ObjBitacora->eliminar();
  if($accion=='CONSULTAR')
	return $ObjBitacora->consultar(); 
  if($accion=='BUSCAR')
	return $ObjBitacora->buscar($_POST['txtFechaHora'], $_POST['txtApellidoCliente'], $_POST['txtApellidoResponsable'], $_POST['txtTipoMovimiento']); 
}
?>