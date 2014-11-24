<?php
require("../datos/cado.php");
require("cls_persona.php");
require("cls_rolpersona.php");
controlador($_GET['accion']);

function controlador($accion)
{
  $ObjPersona = new clsPersona();
  if($accion=='NUEVO'){
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$ObjPersona->insertar($_POST['cboTipoPersona'], $_POST['txtNombres'], $_POST['txtApellidos'], $_POST['txtNroDoc'],$_POST['cboSexo'],$_POST['txtDireccion'],$_POST['txtCelular'],$_POST['txtEmail'],$_POST['cboSector'],$_POST['cboZona'],$_POST['cboDist'],$_POST['txtResponsable']);
		
		$rs = $ObjPersona->obtenerLastId($_POST['txtNroDoc']);
		$dato = $rs->fetchObject();
		$idpersona = $dato->IdPersona;
		
		$ObjRolPersona = new clsRolPersona();
		$ObjRolPersona->insertar($idpersona,$_POST['cboRol']);
		
		$cnx->commit(); 
		if($_GET['origen']=='LIST'){
	header('Location: ../presentacion/list_persona.php?tipo='.$_GET['tipo']);
	}else{?><script>window.close()</script><?php } 
		return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	}   
  }
  if($accion=='ACTUALIZAR'){
	$ObjPersona->actualizar($_POST['txtIdPersona'], $_POST['cboTipoPersona'], $_POST['txtNombres'], $_POST['txtApellidos'], $_POST['txtNroDoc'],$_POST['cboSexo'],$_POST['txtDireccion'],$_POST['txtCelular'],$_POST['txtEmail'],$_POST['cboSector'],$_POST['cboZona'],$_POST['cboDist'],$_POST['txtResponsable']);
	if($_GET['origen']=='LIST'){
	header('Location: ../presentacion/list_persona.php?tipo='.$_GET['tipo']);
	}else{?><script>window.close()</script><?php } 
	return 1;
  }
  if($accion=='ELIMINAR'){
    if($_GET['tipo']=='EMPRESA'){
		header('Location: ../presentacion/list_persona.php?tipo=EMPRESA');
	return	$ObjPersona->eliminar($_GET['IdPersona']);
	}else{
	
	include_once("cls_movimiento.php");
	$objMovimiento= new clsMovimiento();
	$rst=$objMovimiento->buscar($_GET['IdPersona']);
	$dato = $rst->rowCount();
		if($dato>0){
			echo "<script>alert('Persona No Se Puede Eliminar Porque Esta Siendo Usada')</script>";
		}else{
			$ObjPersona->eliminar($_GET['IdPersona']);
		}
	 if($_GET['origen']=='LIST'){	
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_persona.php?tipo=PERSONA'>";
	}else{?><script>window.close()</script><?php } 
	}
	}
  if($accion=='CONSULTAR'){
  if($_GET['origen']=='LIST'){
	header('Location: ../presentacion/list_persona.php?tipo='.$_GET['tipo']);
	}else{?><script>window.close()</script><?php } 
	return $ObjPersona->consultar(); }
  if($accion=='BUSCAR'){
  if($_GET['origen']=='LIST'){
	header('Location: ../presentacion/list_persona.php?tipo='.$_GET['tipo']);
	}else{?><script>window.close()</script><?php } 
	return $ObjPersona->buscar($_POST['txtIdPersona']); }
}
?>