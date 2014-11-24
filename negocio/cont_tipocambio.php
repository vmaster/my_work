<?php
require("../datos/cado.php");
require("cls_tipocambio.php");
controlador($_GET['accion']);
//header('Location: ../presentacion/list_tipocambio.php');

function controlador($accion)
{
  $ObjTipoCambio = new clsTipoCambio();
  if($accion=='NUEVO'){   
	$ObjTipoCambio->insertar($_POST['txtIdTipoCambio'], "'".$_POST['txtFecha']."'", $_POST['txtCambio']);      
	}
  if($accion=='ACTUALIZAR'){
	$ObjTipoCambio->actualizar($_POST['txtIdTipoCambio'], "'".$_POST['txtFecha']."'", $_POST['txtCambio']);
	}
  if($accion=='ELIMINAR'){
 	$exist=$ObjTipoCambio-> buscar($_GET['idTipoCambio'], date('Y-m-d'));
	$dato = $exist->rowCount();
	if($dato>0){
		echo "<script>alert('Tipo De Cambio Actual No Se Puede Eliminar')</script>";
	}else{
		$ObjTipoCambio->eliminar($_GET['idTipoCambio']);
	}
		echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_tipocambio.php'>";
	}
  if($accion=='CONSULTAR'){
	$ObjTipoCambio->consultar(); 
	}
  if($accion=='BUSCAR'){
	$ObjTipoCambio->buscar($_POST['txtIdTipoCambio'], $_POST['txtFecha']); 
	}
}
?>