<?php
session_start();

if(isset($_SESSION['FechaProceso'])){
$fechaproceso = $_SESSION['FechaProceso'];
}else {
$fechaproceso = date("Y-m-d");
$_SESSION['FechaProceso']=date("Y-m-d");
}

date_default_timezone_set('America/Bogota');

require("../datos/cado.php");
require("cls_movimiento.php");
require("cls_listaunidad.php");
require("cls_detallemovalmacen.php");
require("cls_stockproducto.php");
require("cls_bitacora.php");


controlador($_GET['accion']);
//if($_GET['accion']!='PAGAR_CUOTA' && $_GET['accion']!='ACTUALIZAR_CUOTA')
//header('Location: ../presentacion/list_compra.php');

function controlador($accion)
{
  $ObjCompra = new clsMovimiento();
 $ObjDetalle = new clsDetalleMovAlmacen();
 $ObjStockProducto = new clsStockProducto();
  $ObjBitacora = new clsBitacora();

   
 if($accion=='NUEVO'){   
   try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$nuevo=1;
		
		$ExistenciaCaja=$ObjCompra->comprobarCierreCaja(2,$_POST['idsucursal'],$_POST['txtFecha']);
		$EstadoCerrado=$ExistenciaCaja->fetchObject();
		
		if(($EstadoCerrado->resultado)>0){
		$nuevo=2;
		
		echo "<script>alert(' *** CAJA HA SIDO CERRADA ***  \\r    La Compra No Fue Registrada');</script>";
		
		echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_compra.php?'>";
			
			//header('Location: ../presentacion/list_compra.php?error=1');
		
		}else{
		
		
	$ObjCompra->insertar($_POST['txtIdMovimiento'],1,$_POST['idsucursal'],3,$_POST['txtNroDoc'], $_POST['cboTipoDocumento'],$_POST['FormaPago'],$_POST['txtFecha'],$_POST['cboMoneda'],str_replace(",","",$_POST['txtSubtotal']),str_replace(",","",$_POST['txtIgv']),str_replace(",","",$_POST['txtTotal']),$_POST['txtIdUsuario'],$_POST['txtIdPersona'],$_POST['txtIdResponsable'],$_POST['txtIdMovimientoRef'],$_POST['txtSucursalRef'], $_POST['txtComentario'], 'N');
	
	$ultimoId=$ObjCompra->obtenerLastIdCompra(1,$_POST['txtNroDoc'],$_POST['cboTipoDocumento'],$_POST['txtFecha'],$_POST['txtIdPersona']);
	$idU=$ultimoId->fetchObject();
	$id=$idU->IdMovimiento;

// if(!isset($_SESSION['carroCompra'])){
//		
//		echo "no existe carro<br>";
//		$cnx->rollBack(); 
//		
//	}

	//insertando cuotas
if($_POST['FormaPago']=='B'){
	if(isset($_SESSION['cuotas'])){
		$cuotas=$_SESSION['cuotas'];
	}else{
	$cuotas=$ObjCompra->generarcuotas($_POST['txtNumCuotas'],$_POST['txtFecha'],str_replace(",","",$_POST['txtTotal']),str_replace(",","",$_POST['txtInicial']),str_replace(",","",$_POST['txtInteres']));
	}
	
   	
	foreach($cuotas as $r=>$v){
		$ObjCompra->insertarcuota(NULL, $v['numero'], $id,$_POST['txtFecha'], $v['fecha'],NULL,$_POST['cboMoneda'],str_replace(",","",$v['subtotal']),str_replace(",","",$v['interes']),NULL, NULL,"N");
	}
	
	if($_POST['txtInicial']!='0'){
	
	$seriee=$ObjCompra->num_serie(3,11,$_POST['idsucursal']);
	
	$numero_serie_cajaa=$ObjCompra->consultar_numero_sigue(3,11,$seriee,$_POST['idsucursal']);

	$nuevoSub=$_POST['txtInicial']*100/119;
	$nuevoIgv=$_POST['txtInicial']-$nuevoSub;
	$nuevoSub=number_format($nuevoSub,2);
	$nuevoIgv=number_format($nuevoIgv,2);
	$nuevoInicial=number_format($_POST['txtInicial'],2);
	
	$ObjCompra->insertar('',3,$_POST['idsucursal'],4,$numero_serie_cajaa."-".date('Y'),11,'B',$_POST['txtFecha'],$_POST['cboMoneda'],str_replace(",","",$nuevoSub),str_replace(",","",$nuevoIgv),str_replace(",","",$nuevoInicial),$_POST['txtIdUsuario'],$_POST['txtIdPersona'],$_POST['txtIdUsuario'],$id,$_POST['idsucursal'],"Inicial de la Compra: ".$_POST['txtNroDoc'],'N');
	
	
	}
				
				unset($_SESSION['cuotas']);
  
  
  }
  


	$olu=new clsListaUnidad();
	foreach($_SESSION['carroCompra'] as $v){
		
		$consulta=$olu->buscarid(NULL,$v['unidad'],$v['idproducto'],NULL);
   		$datop=$consulta->fetchObject();
		$pc=str_replace(",","",$v['preciocompra']);
		$pv=str_replace(",","",$v['precioventa']);
		$pves=str_replace(",","",$v["precioventaE"]);
		$idlu=$datop->idlistaunidad;
		
		if($_POST['cboMoneda']!=$v["moneda"]){
			if(isset($_SESSION['TipoCambio'])){$tc=$_SESSION['TipoCambio'];}else{$tc=2.8;}
			if($_POST['cboMoneda']=='S'){$pc= $pc*$tc;}else{$pc= $pc/$tc;}
			if($_POST['cboMoneda']=='S'){$pv= $pv*$tc;}else{$pv= $pv/$tc;}
			if($_POST['cboMoneda']=='S'){$pves= $pves*$tc;}else{$pves= $pves/$tc;}
		}
		
		
		//$datop->preciocompra;
		//$dato->precioventa;
		
		if(isset($idlu) && isset($_POST['cambioprecios'])){
		$olu->actualizar($idlu,$v['idproducto'],NULL, NULL, NULL,$pc,$pv,$pves,$_POST['cboMoneda']);
		}
		
		$pc=number_format($pc,2);
		$pv=number_format($pv,2);
		$pves=number_format($pves,2);
	
			$ObjDetalle->insertar($id,$v['idproducto'],$v['idunidad'],$v['cantidad'],$pc,$pv);
			$ObjStockProducto->insertar($_POST['idsucursal'],$v['idproducto'],$v['idunidad'],str_replace(",","",$v['cantidad']),$id,$_POST['cboMoneda'],$pc,$_POST['txtFecha'],$_POST['txtIdUsuario']);
	}
	
	unset($_SESSION['carroCompra']);
	
	
//agregando movimiento caja
	
	if($_POST['FormaPago']=='A'){
	
	$serie=$ObjCompra->num_serie(3,11,$_POST['idsucursal']);
	
	$numero_serie_caja=$ObjCompra->consultar_numero_sigue(3,11,$serie,$_POST['idsucursal']);

	
	$ObjCompra->insertar('',3,$_POST['idsucursal'],4,$numero_serie_caja."-".date('Y'),11,'A',$_POST['txtFecha'],$_POST['cboMoneda'],str_replace(",","",$_POST['txtSubtotal']),str_replace(",","",$_POST['txtIgv']),str_replace(",","",$_POST['txtTotal']),$_POST['txtIdUsuario'],$_POST['txtIdPersona'],$_POST['txtIdUsuario'],$id,$_POST['idsucursal'],"Compra: ".$_POST['txtNroDoc'],'N');
	
	$ObjBitacora->insertar('', $id+1,$_SESSION['IdUsuario'], date('Y-m-d H:i:s'),"Mov Caja: ".$numero_serie_caja."-".date('Y'), "N");
	
	}
	$ObjBitacora->insertar('', $id,$_SESSION['IdUsuario'], date('Y-m-d H:i:s'),"Compra: ".$_POST['txtNroDoc'], "N");
	
	
	header('Location: ../presentacion/list_compra.php');
	
	}
	
	$cnx->commit(); 
	return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	}    
}









  if($accion=='ANULAR'){
  try{
	global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		if(isset($_SESSION['IdSucursal']))
	$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;
		
		$permisoAnular=$ObjCompra->consultarPermisoAnular($_GET['IdMov'],$_SESSION['IdSucursal']);
		$npermisoAnular=$permisoAnular->rowCount();
		
		if($npermisoAnular>0){
		
		echo "<script>alert(' No Se Puede Eliminar Este Documento De Compra Porque Ya Tiene Una Amortización ');</script>";
		
		echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_compra.php'>";
		
		
		}else{
		
		$infoCompra=$ObjCompra->verCompra($_GET['IdMov']);
			$info = $infoCompra->fetchObject();
		
		
			if($info->FormaPago=='B'){
			
				$existenciaMovIniciall=$ObjCompra->consultarMovimientoxRef($_GET['IdMov'],'Inicial de la Compra:');
				$num_row_movv=$existenciaMovIniciall->rowCount();
				
				
				if($num_row_movv>0){
		
					$datoimportantee=$existenciaMovIniciall->fetchObject();
					$existenciacierrecajaa=$ObjCompra->comprobarCierreCaja(2,$_SESSION['IdSucursal'],$datoimportantee->Fecha);
					$cajacerrada=$existenciacierrecajaa->fetchObject();
			
			if( $cajacerrada->resultado > 0){
				echo "<script>alert(' No Se Puede Anular Este Documento De Compra Porque Tiene Una Inicial Y la Caja De Tal Dia Ya cerró ');</script>";
		
				echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_compra.php'>";
			
			}else{
			
			
			$ObjCompra->anular($_GET['IdMov']);
			$ObjCompra->anular_cuotas($_GET['IdMov']);
			$ObjCompra->anular($datoimportantee->IdMovimiento);
			
			$rst=$ObjDetalle->consultarDetalleCompra($_GET['IdMov']);
			
			 while($dato = $rst->fetchObject()){
				$ObjStockProducto->insertar($idsucursal,$dato->idproducto,$dato->idunidad,(-1)*(str_replace(",","",$dato->cantidad)),$_GET['IdMov'],$dato->moneda,$dato->pcompra,$dato->fecha,$dato->idusuario);
	
					$formaPagoo=$dato->FormaPago;
					$fechaBien=$dato->fecha;
					$idProv=$dato->IdPersona;
					$nnn=$dato->Numero;
			}
			
			$ObjBitacora->insertar('',$_GET['IdMov'],$_SESSION['IdUsuario'], date('Y-m-d H:i:s'),"Anulacion de la Compra: ".$nnn, "A");
  			header('Location: ../presentacion/list_compra.php');
			
			
			}
		
		}else{
		//cuando no ay inicial
		
		
			$ObjCompra->anular($_GET['IdMov']);
			
			$ObjCompra->anular_cuotas($_GET['IdMov']);
			
			$rst=$ObjDetalle->consultarDetalleCompra($_GET['IdMov']);
			
			 while($dato = $rst->fetchObject()){
				$ObjStockProducto->insertar($idsucursal,$dato->idproducto,$dato->idunidad,(-1)*(str_replace(",","",$dato->cantidad)),$_GET['IdMov'],$dato->moneda,$dato->pcompra,$dato->fecha,$dato->idusuario);
	
					$formaPagoo=$dato->FormaPago;
					$fechaBien=$dato->fecha;
					$idProv=$dato->IdPersona;
					$nnn=$dato->Numero;
			}
			
			$ObjBitacora->insertar('',$_GET['IdMov'],$_SESSION['IdUsuario'], date('Y-m-d H:i:s'),"Anulacion de la Compra: ".$nnn, "A");
  			header('Location: ../presentacion/list_compra.php');
			
			
			
		}
			//fin if no inicvial
			
			
		}else{
			// cuando forma pago=A
			$formaPagoo="A";
			
			$rst=$ObjDetalle->consultarDetalleCompra($_GET['IdMov']);
			 while($dato = $rst->fetchObject()){
	$ObjStockProducto->insertar($idsucursal,$dato->idproducto,$dato->idunidad,(-1)*(str_replace(",","",$dato->cantidad)),$_GET['IdMov'],$dato->moneda,$dato->pcompra,$dato->fecha,$dato->idusuario);
	
	$formaPagoo=$dato->FormaPago;
	$fechaBien=$dato->fecha;
	$idProv=$dato->IdPersona;
	$nnn=$dato->Numero;

	}
			
			
			$infoCompra=$ObjCompra->verCompra($_GET['IdMov']);
	$info = $infoCompra->fetchObject();
	
		$serie=$ObjCompra->num_serie(3,10,$idsucursal);
	
	$numero_serie_caja=$ObjCompra->consultar_numero_sigue(3,10,$serie,$idsucursal);
	
		$ObjCompra->insertar('',3,$idsucursal,6,$numero_serie_caja."-".date('Y'),10,'A',$fechaBien,$info->moneda,str_replace(",","",$info->SubTotal),str_replace(",","",$info->Igv),str_replace(",","",$info->Total),$_SESSION['IdUsuario'],$idProv,$_SESSION['IdUsuario'],$_GET['IdMov'],$idsucursal,'Fue Por la Anulacion De La Compra '.$info->numero,'N');
		$numdoc=$info->numero;
		
		$idCaj=$ObjCompra->obtenerLastIdCaja($numero_serie_caja."-".date('Y'),'Fue Por la Anulacion De La Compra '.$numdoc,$fechaBien,$idsucursal);
		$idCaja=$idCaj->fetchObject();
		
		$ObjBitacora->insertar('',$idCaja->IdMovimiento,$_SESSION['IdUsuario'], date('Y-m-d H:i:s'),"Mov Caja: ".$numero_serie_caja."-".date('Y'), "A");
		
		$ObjCompra->anular($_GET['IdMov']);
		$ObjCompra->anular_cuotas($_GET['IdMov']);
		
		$ObjBitacora->insertar('',$_GET['IdMov'],$_SESSION['IdUsuario'], date('Y-m-d H:i:s'),"Anulacion de la Compra: ".$nnn, "A");
		
		header('Location: ../presentacion/list_compra.php');
		
		}
		
  }
  
  $cnx->commit(); 
		return 1;
	}catch(Exception $e){
		$cnx->rollBack(); 
		echo "Error al anular compra: " . $e->getMessage();
	}
	
	
  
  }
  
  
  
  
  
  
  if($accion=='PAGAR_CUOTA'){
  	$ObjCompra->pagar_cuota($_GET['IdMov'],$_GET['IdCuota'],$_GET['Nombre']);
	echo "<META HTTP-EQUIV=Refresh CONTENT='1;URL= ../presentacion/mant_cuotascompras.php?IdMov=".$_GET['IdMov']."'>";
	//header('Location: ../presentacion/mant_cuotascompras.php?IdMov='.$_GET['IdMov']);
  }
  
	
 if($accion=='CANCELAR_COMPRA'){
  
  	unset($_SESSION['carroCompra']);
	unset($_SESSION['cuotas']);
	header('Location: ../presentacion/list_compra.php');
  }
  
  if($accion=='ACTUALIZARCUOTA'){
  	
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$ultimaFecha='';
			$ix=1;
		if(isset($_SESSION['cuotas'])){
		$cuotas=$_SESSION['cuotas'];
		
   			foreach($cuotas as $r=>$v){
			$ultimoNombre=$v['nombre'];
			$monedaU=$v['moneda'];
			
			
			
			$ultimaFecha=$v['fechac'];
			
			if($v['estado']!='A'){
				$ix=$ix+1;
			}
			
			$ObjCompra->modificarcuota($v['idcuota'], $v['nombre'], $v['fechac'],$v['fechap'],$v['moneda'],str_replace(",","",$v['monto']),str_replace(",","",$v['interes']),str_replace(",","",$v['montop']),str_replace(",","",$v['interesp']),$v['estado']);
				}
  	}
  
  
  
  if($_POST['mfaltante']!='0'){
  
  	function sumaUnMEs($fechas){	
	list($year,$mon,$day) = explode('-',$fechas);	
	return date('Y-m-d',mktime(0,0,0,$mon+$ix,$day,$year));		
	} 
	$fexa=sumaUnMEs($ultimaFecha);
  
  	$ObjCompra->insertarcuota(0,$ultimoNombre+1,$_POST['txtIdCompra'],date('Y-m-d'),$fexa,'0000-00-00',$monedaU,$_POST['mfaltante'],0,0,0,'N');
  }
  
  if(isset($_SESSION['cuotaseliminar'])){
		$cuotaseliminar=$_SESSION['cuotaseliminar'];
   			foreach($cuotaseliminar as $r=>$v){
			$ObjCompra->anularcuota($v['idcuota']);
				}
			  unset($_SESSION['cuotaseliminar']);	
  }
  
  
  unset($_SESSION['cuotas']);

    
   		$cnx->commit(); 
		header('Location: ../presentacion/frm_cuotascompras.php?accion=CERRAR');
		return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso de actualizacion de cuotas: " . $e->getMessage();
	}   
	
	}
	
  if($accion=='BUSCAR'){
  }
	 
}
?>