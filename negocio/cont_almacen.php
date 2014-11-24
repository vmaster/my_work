<?php
session_start();

require("../datos/cado.php");
require("cls_movimiento.php");
require("cls_detallemovalmacen.php");
require("cls_stockproducto.php");
require("cls_listaunidad.php");
require("cls_bitacora.php");
controlador($_GET['accion']);
header('Location: ../presentacion/list_almacen.php');

function controlador($accion)
{
  $ObjAlmacen= new clsMovimiento();
   $ObjDetalle = new clsDetalleMovAlmacen();
   $ObjBitacora = new clsBitacora();
   
 if($accion=='NUEVO'){	      
 try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
 	$ObjAlmacen->insertar($_POST['txtIdMovimiento'],4,$_POST['txtSucursal'],0,$_POST['txtNroDoc'], $_POST['cboTipoDocumento'],0,$_POST['txtFechaRegistro'],$_POST['cboMoneda'],0,0,$_POST['txtTotal'],$_POST['txtIdUsuario'],$_POST['txtIdPersona'],$_POST['txtIdResponsable'],$_POST['txtIdMovRef'],$_POST['txtSucursalRef'], $_POST['txtComentario'],'N');
	$ultimoId=$ObjAlmacen->obtenerLastId($_POST['txtNroDoc'],4,$_POST['cboTipoDocumento']);
	$idU=$ultimoId->fetchObject();
	$id=$idU->IdMovimiento;

	$ObjBitacora->insertar(NULL,$id,$_SESSION['IdUsuario'],date('Y-m-d H:i:s'),"Nuevo Doc Almacen : ".$_POST['txtNroDoc'],'N');

	$olu=new clsListaUnidad();
	foreach($_SESSION['carroAlmacen'] as $v){
	$consulta=$olu->buscarid(NULL,$v['unidad'],$v['idproducto'],NULL);
   		$datop=$consulta->fetchObject();
		$idlu=$datop->idlistaunidad;
		
		if(isset($idlu) && $_POST['txtPreciosP']==1){
		$olu->actualizar($idlu,$v['idproducto'],NULL, NULL, NULL,$v['preciocompra'],$v['precioventa'],$v['precioespecial'],$_POST['cboMoneda']);
		}
	
	$ObjDetalle->insertar($id,$v['idproducto'],$v['idunidad'],$v['cantidad'],$v['preciocompra'],$v['precioventa']);
	
	if($_POST['cboTipoDocumento']==10){
	$ObjStockProducto = new clsStockProducto();
	$ObjStockProducto->insertar($_POST['txtSucursal'],$v['idproducto'],$v['idunidad'],$v['cantidad'],$id,$_POST['cboMoneda'],$v['precioventa'],$_POST['txtFechaRegistro'],$_POST['txtIdUsuario']);
	
	}else{
	$ObjStockProducto = new clsStockProducto();
$ObjStockProducto->insertar($_POST['txtSucursal'],$v['idproducto'],$v['idunidad'],"-".$v['cantidad'],$id,$_POST['cboMoneda'],$v['precioventa'],$_POST['txtFechaRegistro'],$_POST['txtIdUsuario']);

	}
	}
	$cnx->commit(); 
	return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	}    
} 
/*  if($accion=='ACTUALIZAR')
	return $ObjArmario->actualizar();*/
  if($accion=='ANULAR'){
   try{
		
		if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];
}else{
$idsucursal=1;
}
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		$ObjAlmacen->anular($_GET['IdMov']);
		
		$rst1 = $ObjDetalle->consultar($_GET['IdMov']);
		$dato1 = $rst1->fetchObject();
		
		$ObjBitacora->insertar(NULL,$_GET['IdMov'],$_SESSION['IdUsuario'],date('Y-m-d H:i:s'),"Anulacion Doc Almacen : ".$dato1->numero,'A');
		
		
		
		$ObjStockProducto = new clsStockProducto();
	
   $rst = $ObjDetalle->consultar($_GET['IdMov']);
	while($dato = $rst->fetchObject()){
	if($dato->tipodoc=='10'){
	$ObjStockProducto->insertar($idsucursal,$dato->idproducto,$dato->idunidad,-1*($dato->cantidad),$_GET['IdMov'],$dato->moneda,$dato->precioventa,$dato->fecha,$dato->idusuario);
	}else{
	$ObjStockProducto->insertar($idsucursal,$dato->idproducto,$dato->idunidad,$dato->cantidad,$_GET['IdMov'],$dato->moneda,$dato->precioventa,$dato->fecha,$dato->idusuario);
	}
	
	}
	
	$cnx->commit(); 
	return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	}    

}

  if($accion=='CONSULTAR')
	return $ObjAlmacen->consultar(); 
/*  if($accion=='BUSCAR')
	return $ObjMovimiento->buscar(); */
}
?>