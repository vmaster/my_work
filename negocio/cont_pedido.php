<?php
require("../datos/cado.php");
require("cls_movimiento.php");
require("cls_stockproducto.php");
require("cls_listaunidad.php");
require("cls_detallemovalmacen.php");
require("cls_bitacora.php");
require("cls_ubigeo.php");
controlador($_GET['accion']);
//if($_GET['accion']=='NUEVAGUIA'){
//header('Location: ../presentacion/list_guias.php');
//}else{
//header('Location: ../presentacion/list_ventas.php');
//}

function controlador($accion)
{
session_start();
  $ObjMov = new clsMovimiento();
  $ObjBitacora = new clsBitacora();
  
  
  
  if($accion=='NUEVO'){
  	
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		if($_POST['cboTipoVenta']=="A" || $_POST['cboTipoVenta']==NULL){
		$_POST['cboTipoVenta']="A";
		$numero=$_POST['txtNroDoc'];
		}else{
		$numero="0";
		}
		
		$ObjMov->insertar(NULL, 5,$_POST['txtIdSucursal'], 3, $numero, $_POST['cboTipoDocumento'], $_POST['cboTipoVenta'], $_POST['txtFechaRegistro'],$_POST['cboMoneda'], $_POST['txtSubtotal'], $_POST['txtIgv'],$_POST['txtTotal'], $_POST['txtIdUsuario'], $_POST['txtIdPersona'],$_POST['txtIdResponsable'],$_POST['txtIdMovimientoRef'], $_POST['txtIdSucursalRef'], $_POST['txtComentario'], 'N');
		
		$rs = $ObjMov->obtenerLastIdVenta($numero,5,$_POST['cboTipoDocumento'],$_POST['txtIdSucursal']);
		$dato = $rs->fetchObject();
		$idmov = $dato->IdMovimiento;
		
		$ObjBitacora->insertar(NULL,$idmov,$_POST['txtIdUsuario'],date("Y-m-d H:i:s"),'Registro de nuevo pedido','N');
		
    
   $olu=new clsListaUnidad();
   $ObjMovAlm= new clsDetalleMovAlmacen();
   $stockprod = new clsStockProducto();
   
   foreach($_SESSION['carroventa'] as $k => $v){
      	$consulta=$olu->buscarid(NULL,$v["unidad"],$v["idproducto"],NULL);
   		$datop=$consulta->fetchObject();
		$pc=$datop->preciocompra;
		
		if($_POST['cboMoneda']!=$datop->moneda){
			if(isset($_SESSION['TipoCambio'])){$tc=$_SESSION['TipoCambio'];}else{$tc=2.8;}
			if($moneda=='S'){$pc= $pc*$tc;}else{$pc= $pc/$tc;}
		}
		
		$pc=number_format($pc,2);
	
   	$ObjMovAlm->insertar($idmov,$v["idproducto"],$datop->idunidad,$v['cantidad'],$pc,$v["precioventa"]);
/*     if($_POST['txtGuia']==0){
   	$stockprod->insertar($_POST['txtIdSucursal'],$v["idproducto"],$datop->idunidad,-$v['cantidad'],$idmov,$_POST['cboMoneda'],$v["precioventa"],$_POST['txtFechaRegistro'],$_POST['txtIdUsuario']);}*/
	}
		$cnx->commit(); 

		header('Location: ../presentacion/list_pedido.php');		
		
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso de registro de venta: " . $e->getMessage();
	}   
	
	}
  
if($accion=='NUEVO-SUCURSAL'){
  	
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		if($_POST['cboTipoVenta']=="A" || $_POST['cboTipoVenta']==NULL){
		$_POST['cboTipoVenta']="A";
		$numero=$_POST['txtNroDoc'];
		}else{
		$numero="0";
		}
		
		$ObjMov->insertar(NULL, 5,$_POST['txtIdSucursal'], 3, $numero, $_POST['cboTipoDocumento'], $_POST['cboTipoVenta'], $_POST['txtFechaRegistro'],$_POST['cboMoneda'], $_POST['txtSubtotal'], $_POST['txtIgv'],$_POST['txtTotal'], $_POST['txtIdUsuario'], $_POST['txtIdPersona'],$_POST['txtIdResponsable'],$_POST['txtIdMovimientoRef'], $_POST['txtIdSucursalRef'], $_POST['txtComentario'], 'N');
		
		$rs = $ObjMov->obtenerLastIdVenta($numero,5,$_POST['cboTipoDocumento'],$_POST['txtIdSucursal']);
		$dato = $rs->fetchObject();
		$idmov = $dato->IdMovimiento;
		
		$ObjBitacora->insertar(NULL,$idmov,$_POST['txtIdUsuario'],date("Y-m-d H:i:s"),'Registro de nuevo pedido de sucursal','N');
		
    
   $olu=new clsListaUnidad();
   $ObjMovAlm= new clsDetalleMovAlmacen();
   $stockprod = new clsStockProducto();
   
   foreach($_SESSION['carroventa'] as $k => $v){
      	$consulta=$olu->buscarid(NULL,$v["unidad"],$v["idproducto"],NULL);
   		$datop=$consulta->fetchObject();
		$pc=$datop->preciocompra;
		
		if($_POST['cboMoneda']!=$datop->moneda){
			if(isset($_SESSION['TipoCambio'])){$tc=$_SESSION['TipoCambio'];}else{$tc=2.8;}
			if($moneda=='S'){$pc= $pc*$tc;}else{$pc= $pc/$tc;}
		}
		
		$pc=number_format($pc,2);
	
   	$ObjMovAlm->insertar($idmov,$v["idproducto"],$datop->idunidad,$v['cantidad'],$pc,$v["precioventa"]);
/*     if($_POST['txtGuia']==0){
   	$stockprod->insertar($_POST['txtIdSucursal'],$v["idproducto"],$datop->idunidad,-$v['cantidad'],$idmov,$_POST['cboMoneda'],$v["precioventa"],$_POST['txtFechaRegistro'],$_POST['txtIdUsuario']);}*/
	}
		$cnx->commit(); 

		header('Location: ../presentacion/list_pedidosucursal.php');		
		
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso de registro de pedido de sucursal: " . $e->getMessage();
	}   
	
	}  
  
  
    if($accion=='ANULAR'){
	try{
	global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$numero=$ObjMov->verificarcierrecaja($_SESSION['FechaProceso'],$_SESSION['IdSucursal']);
	if($numero!=1){
		if($numero==2){
		$frase1=" <script>alert(' *** CAJA esta cerrada, NO puede anular pedido!! ***');</script>";
		$frase2=" <META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_pedido.php?'>";}
		if($numero==3){
		$frase1=" <script>alert(' *** CAJA aun no ha sido aperturada, NO puede anular pedido!!!! ***');</script>";
		$frase2=" <META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_pedido.php?'>";
		}
	}
		
		$ventas=$ObjMov->consultarventa(5,$_GET['IdVenta'],NULL,NULL,NULL,NULL,NULL,NULL,NULL);
		$venta=$ventas->fetchObject();
		
	$cnx->commit();
	//falta verificar si esta siendo referenciado a un doc venta
	header("location: ../presentacion/list_pedido.php");
}catch(Exception $e){
		$cnx->rollBack(); 
		echo "Error de Proceso de anulacion de venta: " . $e->getMessage();
	}
}
}
?>