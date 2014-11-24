<?php
require("../datos/cado.php");
require("cls_menu.php");
//echo $_POST['idopcionmenu'].$_POST['linkmenu'].$_POST['nombremenu'].$_POST['cboModulo'];
controlador($_GET['accion']);
header('Location: ../presentacion/list_menu.php');

function controlador($accion)
{
  $ObjMenu = new clsMenu();
  if($accion=='NUEVO')	      
	return $ObjMenu->insertar($_POST['idopcionmenu'], $_POST['linkmenu'], $_POST['nombremenu'], $_POST['cboModulo']);      
  if($accion=='ACTUALIZAR')
	return $ObjMenu->actualizar($_POST['idopcionmenu'], $_POST['linkmenu'], $_POST['nombremenu'], $_POST['cboModulo']);
  if($accion=='ELIMINAR')
	return $ObjMenu->eliminar($_GET['idopcionmenu']);
  if($accion=='CONSULTAR')
	return $ObjMenu->consultar(); 
  if($accion=='BUSCAR')
	return $ObjMenu->consultar($_POST['idopcionmenu'], $_POST['linkmenu'], $_POST['nombremenu']); 
}
?>