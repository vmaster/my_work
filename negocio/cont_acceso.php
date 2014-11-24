<?php
require("../datos/cado.php");
require("cls_acceso.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_acceso.php');

function controlador($accion)
{
  $ObjAcceso = new clsAcceso();
  if($accion=='NUEVO')
  {
	try{
	  global $cnx;
	  $cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
	  $cnx->beginTransaction(); 
	  //vetrificar si ya se registro el menu para el tipo de registrador
	  $rs = $ObjAcceso->verificar($_GET['idtipousuario'], $_GET['idopcionmenu']);
	  $dato = $rs->fetchObject();
	  $cantidad = $dato->cantidad;      
	  if($cantidad>0)
	  header('Location: ../presentacion/list_acceso.php');
	  else{
	  $resultado=$ObjAcceso->insertar('',$_GET['idtipousuario'], $_GET['idopcionmenu']);
//	  $resultado=$ObjAcceso->insertar2('',$_GET['idtipousuario'], $_GET['idopcionmenu']);
	  }
	  $cnx->commit(); 
	  return 1;
	} catch (Exception $e) { 
	  $cnx->rollBack(); 
	  echo "Error de Proceso en Lotes: " . $e->getMessage(); 
	}   
  }
  if($accion=='ACTUALIZAR')
	return $ObjAcceso->actualizar($_POST['idopcionmenu'], $_POST['linkmenu'], $_POST['nombremenu']);
  if($accion=='ELIMINAR')
	return $ObjAcceso->eliminar($_GET['idacceso']);
  if($accion=='CONSULTAR')
	return $ObjAcceso->consultar(); 
  if($accion=='BUSCAR')
	return $ObjAcceso->consultar($_POST['idopcionmenu'], $_POST['linkmenu'], $_POST['nombremenu']); 
}
?>