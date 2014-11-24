<?php
require("../datos/cado.php");
require("cls_usuario.php");
require("cls_tipocambio.php");
require("../negocio/cls_persona.php");
controlador($_GET['accion']);
if ($_GET['accion']!='LOGEO' and $_GET['accion']!='LOGOUT') {
header('Location: ../presentacion/list_usuario.php');
}

function controlador($accion)
{
  $ObjUsuario = new clsUsuario();
  if($accion=='NUEVO')	      
	return $ObjUsuario->insertar($_POST['cboUsuario'],$_POST['txtLogin'], $_POST['txtPassword'], $_POST['cboTipoUsuario']);   
  if($accion=='ACTUALIZAR')
	return $ObjUsuario->actualizar($_POST['cboUsuario'],$_POST['txtLogin'], $_POST['txtPassword'], $_POST['cboTipoUsuario']);
  if($accion=='ELIMINAR')
	return $ObjUsuario->eliminar($_GET['Idusuario']);
  if($accion=='CONSULTAR')
	return $ObjUsuario->consultar();
  if($accion=='LOGEO') {
	$rst = $ObjUsuario->logeo($_POST['txtUsuario'], $_POST['txtClave']); 
	if ($rst->rowCount()==1) {
		$dato = $rst->fetchObject();
		session_start();
		$_SESSION['Usuario']=$_POST['txtUsuario'];
		$_SESSION['IdUsuario']=$dato->IdUsuario;
		$_SESSION['IdTipoUsuario']=$dato->IdTipoUsuario;
		$_SESSION['IdSucursal']=$_POST['cboSucursal'];

		$ObjPersona = new clsPersona();
		$rst = $ObjPersona->buscar($_SESSION['IdSucursal'],'','');
		$dato = $rst->fetchObject();
		//Sucursal denominada ahora empresa
		$_SESSION['Sucursal']=$dato->Apellidos.' '.$dato->Nombres;

		$_SESSION['TipoCambio']=$_POST['txtTipoCambio'];
		$_SESSION['FechaProceso']=$_POST['txtFechaProceso'];
		$_SESSION['IGV']=18;
				
		$ObjUsuario->actualizarUltimoAcceso($_SESSION['IdUsuario']);
		$ObjTipoCambio = new clsTipoCambio();
		$rs=$ObjTipoCambio->buscarxfecha('CURDATE()');
		if ($rs->rowCount()==1) {
			$dato = $rs->fetchObject();
			$ObjTipoCambio->actualizar($dato->IdTipocambio, 'CURDATE()', $_POST['txtTipoCambio']);
		}else{
			$ObjTipoCambio->insertar(0, 'CURDATE()', $_POST['txtTipoCambio']);
		}
		
		header("location: ../presentacion/main.php");
	}
	else{ header("location: ../presentacion/login.php?error=1");}
  }
  if($accion=='LOGOUT') {
	session_start();
	session_destroy();
		echo "<script>window.open('../presentacion/login.php','_parent');</script>";
  }
}
?>