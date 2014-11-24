<?php
require("../datos/cado.php");
require("cls_conceptopago.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_conceptopago.php');

function controlador($accion)
{
  $ObjConcepto = new clsConceptoPago();
  if($accion=='NUEVO')	      
	return $ObjConcepto  ->insertar('',$_POST['txtDescripcion'],$_POST['cboTipo']);      
  if($accion=='ACTUALIZAR')
	return $ObjConcepto ->actualizar($_POST['txtIdConcepto'], $_POST['txtDescripcion'],$_POST['cboTipo']);
  if($accion=='ELIMINAR')
	return $ObjConcepto  ->eliminar($_GET['IdConcepto']);
  if($accion=='CONSULTAR')
	return $ObjConcepto  ->consultar(); 
}
?>
