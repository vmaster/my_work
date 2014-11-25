<?php
require("../datos/cado.php");
require("cls_producto.php");
require("cls_listaunidad.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_producto.php');

function controlador($accion)
{
  $ObjProducto = new clsProducto();
  $ObjLU = new clsListaUnidad();
  if($accion=='NUEVO'){	 
  
  try{    
  		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();

	$codigo=$ObjProducto->buscarSiguienteCodigo($_POST['cboCategoria'],$_POST['cboMarca']);
		 
	$ObjProducto->insertar($_POST['txtIdProducto'], $codigo, $_POST['txtDescripcion'], $_POST['cboCategoria'], $_POST['cboMarca'], $_POST['cboUnidadBase'], $_POST['txtPeso'], $_POST['cboMedidaPeso'],$_POST['txtStockSeguridad'], $_POST['cboArmario'], $_POST['cboColumna'],$_POST['cboFila'], $_POST['cboKardex'], $_POST['cboColor'], $_POST['cboTalla'], $_POST['cboRecetaMedica']);
	
	/*$registro=$ObjProducto->buscar(NULL,$_POST['txtDescripcion'],$_POST['cboCategoria'],$_POST['cboMarca']);
	$dato=$registro->fetchObject();*/
	
	$registro=$ObjProducto->obtenerLastId();
	$dato=$registro->fetchObject();
	
	$ObjLU->insertar(NULL,$dato->idproducto,$_POST['cboUnidadBase'],$_POST['cboUnidadBase'],1, $_POST['txtPrecioCompra'], $_POST['txtPrecioVenta'], $_POST['txtPrecioEspecial'],$_POST['cboMoneda']);
		
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
		
	$ObjProducto->actualizar($_POST['txtIdProducto'], $_POST['txtCodigo'], $_POST['txtDescripcion'], $_POST['cboCategoria'], $_POST['cboMarca'], $_POST['cboUnidadBase'], $_POST['txtPeso'], $_POST['cboMedidaPeso'],$_POST['txtStockSeguridad'], $_POST['cboArmario'], $_POST['cboColumna'],$_POST['cboFila'], $_POST['cboKardex'], $_POST['cboColor'], $_POST['cboTalla'], $_POST['cboRecetaMedica']);
	
	if($_POST['txtPrecioCompra']!=""){
	$registro=$ObjLU->buscar(NULL,$_POST['txtIdProducto'],$_POST['txtDescripcion']);
	$dato=$registro->fetchObject();
		
	$ObjLU->actualizar($dato->idlistaunidad,$_POST['txtIdProducto'],$_POST['cboUnidadBase'],$_POST['cboUnidadBase'],1,$_POST['txtPrecioCompra'],$_POST['txtPrecioVenta'],$_POST['txtPrecioEspecial'],$_POST['cboMoneda']);  }
	
	$cnx->commit(); 
		return 1;
	}catch(Exception $e){
	$cnx->rollBack(); 
		echo "Error de Proceso de registro de venta: " . $e->getMessage();
	
		}
	
	}
  
  
  
  if($accion=='ELIMINAR')
	return $ObjProducto->eliminar($_GET['IdProducto']);
	
  if($accion=='CONSULTAR')
  
	return $ObjProducto->consultar(); 
	
  if($accion=='BUSCAR')
	return $ObjArmario->buscar($_POST['txtIdProducto'], $_POST['txtDescripcion'], $_POST['txtCategoria'], $_POST['txtMarca']); 
}
?>