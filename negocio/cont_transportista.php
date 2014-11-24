<?php
require("../datos/cado.php");
require("cls_transportista.php");
controlador($_GET['accion']);
if($_GET['origen']=='LIST'){
header('Location: ../presentacion/list_transportista.php');
}else{?>
<script>window.close()</script>
<?php 
}

function controlador($accion)
{
  $ObjTrans = new clsTransportista();

  if($accion=='NUEVO'){	 
  
  try{    
  		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		 		 
	$ObjTrans->insertar($_POST['txtIdTransportista'], $_POST['txtIdSucursal'], $_POST['txtNombre'], $_POST['txtNroDoc'], $_POST['txtDireccion'], $_POST['txtMarcaVehiculo'], $_POST['txtNumeroPlaca'], $_POST['txtConstancia'],$_POST['txtLicencia'], $_POST['txtChofer'], 'N');
	
	$cnx->commit(); 
		return 1;
	}catch(Exception $e){
		$cnx->rollBack(); 
		echo "Error de Proceso de registro de venta: " . $e->getMessage();
	}
	
	}
	  
  if($accion=='ACTUALIZAR'){
  try{    
  		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
	$ObjTrans->actualizar($_POST['txtIdTransportista'], $_POST['txtIdSucursal'], $_POST['txtNombre'], $_POST['txtNroDoc'], $_POST['txtDireccion'], $_POST['txtMarcaVehiculo'], $_POST['txtNumeroPlaca'], $_POST['txtConstancia'],$_POST['txtLicencia'], $_POST['txtChofer'], 'N');
	
	$cnx->commit(); 
		return 1;
	}catch(Exception $e){
	$cnx->rollBack(); 
		echo "Error de Proceso de registro de venta: " . $e->getMessage();
	
		}
	
	}
  
  if($accion=='ELIMINAR'){
	return $ObjProducto->eliminar($_GET['IdTransportista']);
	
	}
	
}
?>