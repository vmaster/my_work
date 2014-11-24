<?php
require("../datos/cado.php");
require("cls_tipousuario.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_tipousuario.php');

function controlador($accion)
{
  $ObjTipoUsuario = new clsTipoUsuario();
  if($accion=='NUEVO')	      
	return $ObjTipoUsuario->insertar('',$_POST['txtDescripcion'],$_POST['cboEstado']);      
  if($accion=='ACTUALIZAR')
	return $ObjTipoUsuario->actualizar($_POST['txtIdTipoUsuario'], $_POST['txtDescripcion']);
  if($accion=='ELIMINAR')
	return $ObjTipoUsuario->eliminar($_GET['IdTipoUsuario']);
  if($accion=='CONSULTAR')
	return $ObjTipoUsuario->consultar(); 
}
?>