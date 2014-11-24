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
		
		$ObjMov->insertar(NULL, 2,$_POST['txtIdSucursal'], 3, $numero, $_POST['cboTipoDocumento'], $_POST['cboTipoVenta'], $_POST['txtFechaRegistro'],$_POST['cboMoneda'], $_POST['txtSubtotal'], $_POST['txtIgv'],$_POST['txtTotal'], $_POST['txtIdUsuario'], $_POST['txtIdPersona'],$_POST['txtIdResponsable'],$_POST['txtIdMovimientoRef'], $_POST['txtIdSucursalRef'], $_POST['txtComentario'], 'N');
		
	$rs = $ObjMov->obtenerLastIdVenta($numero,2,$_POST['cboTipoDocumento'],$_POST['txtIdSucursal']);
		$dato = $rs->fetchObject();
		$idmov = $dato->IdMovimiento;
		
		$ObjBitacora->insertar(NULL,$idmov,$_POST['txtIdUsuario'],date("Y-m-d H:i:s"),'Registro de nueva venta','N');
		
		if($_POST['cboTipoVenta']=='B'){
		if($_SESSION['cuotasventa']){
		$cuotasventa=$_SESSION['cuotasventa'];
		}else{
		$cuotasventa=$ObjMov->generarcuotas($_POST['txtNumCuotas'],$_POST['txtFechaRegistro'],$_POST['txtTotal'],$_POST['txtInicial'],$_POST['txtInteres']);
		
		}
		
		if(number_format($_POST['txtInicial'],2)>0.00){
		if($_POST['cboTipoDocumento']==1){$doc='BOL.';}else{$doc='FACT.';}
		$numero_serie_caja=$ObjMov->consultar_numero_sigue(3,10,$_POST['txtIdSucursal']);
		$ObjMov->insertar(NULL, 3,$_POST['txtIdSucursal'], 3, $numero_serie_caja.'-'.substr($_SESSION['FechaProceso'],0,4), 10, $_POST['cboTipoVenta'], $_SESSION['FechaProceso'],$_POST['cboMoneda'], 0, 0,$_POST['txtInicial'], $_POST['txtIdUsuario'], $_POST['txtIdPersona'],$_POST['txtIdResponsable'],$idmov, $_POST['txtIdSucursal'],"Inicial de la Venta", 'N');
		}
		
   		foreach($cuotasventa as $r=>$v){
		$ObjMov->insertarcuota(NULL, $v['numero'], $idmov,$_POST['txtFechaRegistro'], $v['fecha'],NULL,$_POST['cboMoneda'],$v['subtotal'],$v['interes'],NULL, NULL,"N");}		
		
		}else{
		
		if($_POST['cboTipoDocumento']==1){$doc='BOL.';}
		if($_POST['cboTipoDocumento']==2){$doc='FACT.';}
		if($_POST['cboTipoDocumento']==12){$doc='RECIBO';}
		
		$numero_serie_caja=$ObjMov->consultar_numero_sigue(3,10,$_POST['txtIdSucursal']);
		 $ObjMov->insertar(NULL, 3,$_POST['txtIdSucursal'], 3, $numero_serie_caja.'-'.substr($_SESSION['FechaProceso'],0,4), 10, $_POST['cboTipoVenta'], $_SESSION['FechaProceso'],$_POST['cboMoneda'], $_POST['txtSubtotal'], $_POST['txtIgv'],$_POST['txtTotal'], $_POST['txtIdUsuario'], $_POST['txtIdPersona'],$_POST['txtIdResponsable'],$idmov, $_POST['txtIdSucursal'], $doc.' VENTA Nro.:'.$numero, 'N');
		
		}
    
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
   	$stockprod->insertar($_POST['txtIdSucursal'],$v["idproducto"],$datop->idunidad,-$v['cantidad'],$idmov,$_POST['cboMoneda'],$v["precioventa"],$_POST['txtFechaRegistro'],$_POST['txtIdUsuario']);}
*/	}
		$cnx->commit(); 
		
		if($_POST['cboTipoDocumento']==1){
			header('Location: ../presentacion/frm_comprobanteB.php?idventa='.$idmov.'&origen='.$_GET['origen']);
		}
		if($_POST['cboTipoDocumento']==2){
			header('Location: ../presentacion/frm_comprobanteF.php?idventa='.$idmov.'&T='.$_POST['txtTotal'].'&M='.$_POST['cboMoneda'].'&origen='.$_GET['origen']);
		}
		if($_POST['cboTipoDocumento']==12){
			header('Location: ../presentacion/frm_comprobanteR.php?idventa='.$idmov.'&origen='.$_GET['origen']);
		}
		
		
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso de registro de venta: " . $e->getMessage();
	}   
	
	}
  
  if($accion=='NUEVO-PEDIDO'){
  	
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
		
		$ObjBitacora->insertar(NULL,$idmov,$_POST['txtIdUsuario'],date("Y-m-d H:i:s"),'Registro de nuevo despacho pedido de sucursal','N');
		
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
	 	//QUITO STOCK A SUCURSAL ORIGEN
		$stockprod->insertar($_SESSION['IdSucursal'],$v["idproducto"],$datop->idunidad,-$v['cantidad'],$idmov,$moneda,$v["precioventa"],date("Y-m-d H:i:s"),$_SESSION['IdUsuario']);
		//AGREGO STOCK A SUCURSAL DESTINO
		$stockprod->insertar($_POST['txtIdPersona'],$v["idproducto"],$datop->idunidad,$v['cantidad'],$idmov,$moneda,$v["precioventa"],date("Y-m-d H:i:s"),$_SESSION['IdUsuario']);

	}
		$cnx->commit(); 
		
		header('Location: ../presentacion/list_pedidosucursal.php');
		
		
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso de registro de despacho de pedido de sucursal: " . $e->getMessage();
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
		$frase1=" <script>alert(' *** CAJA esta cerrada, NO puede anular venta!! ***');</script>";
		$frase2=" <META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_ventas.php?'>";}
		if($numero==3){
		$frase1=" <script>alert(' *** CAJA aun no ha sido aperturada, NO puede anular venta!!!! ***');</script>";
		$frase2=" <META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_ventas.php?'>";
		}
	}
		
		$ventas=$ObjMov->consultarventa(2,$_GET['IdVenta'],NULL,NULL,NULL,NULL,NULL,NULL,NULL);
		$venta=$ventas->fetchObject();
		
	if($venta->formapago=='B'){//venta al credito
		
		$mov2=$ObjMov->consultarcajaventa($_GET['IdVenta']);
		
		if($mov2->rowCount()>0){//sino dio alguna inicial
		$cuotaspag=$ObjMov->cuotaporestado($_GET['IdVenta'],'C');
		$cuotap=$cuotaspag->fetch();
		if($numero==1){
		if($cuotap[0]=='0'){//ver cuotas pagadas
			$movicaja=$mov2->fetchObject();
			$ObjMov->anular($movicaja->idmovimiento);
			
			$ObjMov->anular($_GET['IdVenta']);
			$detalle=	$ObjMov->consultardetalleventa($_GET['IdVenta']);	
			$stockprod = new clsStockProducto();
		
					while($dato = $detalle->fetchObject()){
		$stockprod->insertar($_GET['IdSucursal'],$dato->idproducto,$dato->idunidad,$dato->cantidad,$_GET['IdVenta'],$dato->moneda,$dato->precioventa,$_SESSION['FechaProceso'],$_GET['IdUsuario']);
		}
		
			$ObjBitacora->insertar(NULL,$_GET['IdVenta'],$_GET['IdUsuario'],date('Y-m-d H:i:s'),'Anulacion de venta','A');
			header('Location: ../presentacion/list_ventas.php');
		
		
		}else{
		echo "<script>alert(' *** No se puede eliminar este documento de venta porque ya tiene una amortización ***');</script>";
		
		echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_ventas.php?'>";
		}
		
		}else{echo $frase1;
		echo $frase2;}	
		
		}else {// si no dio alguna inicial
		
		
			$cuotaspag=$ObjMov->cuotaporestado($_GET['IdVenta'],'C');
			$cuotap=$cuotaspag->fetch();
			if($cuotap[0]=='0'){//si cno hay cuotas pagadas
		
				$ObjMov->anular($_GET['IdVenta']);
			$detalle=	$ObjMov->consultardetalleventa($_GET['IdVenta']);	
			$stockprod = new clsStockProducto();
		
					while($dato = $detalle->fetchObject()){
		$stockprod->insertar($_GET['IdSucursal'],$dato->idproducto,$dato->idunidad,$dato->cantidad,$_GET['IdVenta'],$dato->moneda,$dato->precioventa,$_SESSION['FechaProceso'],$_GET['IdUsuario']);
		}
		
			$ObjBitacora->insertar(NULL,$_GET['IdVenta'],$_GET['IdUsuario'],date('Y-m-d H:i:s'),'Anulacion de venta','A');		
		
		
		
			
		
			}else{// si hay cuotas pagadas
		
		echo "<script>alert(' *** No se puede eliminar este documento de venta porque ya tiene una amortización ***');</script>";
		
		echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_ventas.php?'>";
		
				}
		header('Location: ../presentacion/list_ventas.php');
		
		}
	
}else{//venta al contado

	if($numero==1){//verificamos caja
		$mov2=$ObjMov->consultarcajaventa($_GET['IdVenta']);
		if($mov2->rowCount()>0){//si hay amortizacion
			echo "<script>alert(' *** No se puede eliminar este documento de venta porque ya tiene una amortización ***');</script>";
		
			echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_ventas.php?'>";
		}else{
		
			$ObjMov->anular($_GET['IdVenta']);
			$detalle=	$ObjMov->consultardetalleventa($_GET['IdVenta']);	
			$stockprod = new clsStockProducto();
		
			while($dato = $detalle->fetchObject()){
			$stockprod->insertar($_GET['IdSucursal'],$dato->idproducto,$dato->idunidad,$dato->cantidad,$_GET['IdVenta'],$dato->moneda,$dato->precioventa,$_SESSION['FechaProceso'],$_GET['IdUsuario']);
				}
		
			$ObjBitacora->insertar(NULL,$_GET['IdVenta'],$_GET['IdUsuario'],date('Y-m-d H:i:s'),'Anulacion de venta','A');		
		
			$mov2=$ObjMov->consultarcajaventa($_GET['IdVenta']);
		
			if($mov2->rowCount()>0){
				$movicaja=$mov2->fetchObject();
				$ObjMov->anular($movicaja->idmovimiento);
			}
		
		 
		header('Location: ../presentacion/list_ventas.php');
		
		}

	}else{//si hay problemas en caja
	
	echo $frase1;
	echo $frase2;
	
	}
	
	}
	$cnx->commit();
}catch(Exception $e){
		$cnx->rollBack(); 
		echo "Error de Proceso de anulacion de venta: " . $e->getMessage();
	}
}
	
	
	
	
	
	  if($accion=='ACTUALIZARCUOTA'){
  	
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
				
			
		if(isset($_SESSION['cuotasventa'])){
		$cuotasventa=$_SESSION['cuotasventa'];
   			foreach($cuotasventa as $r=>$v){
			$ObjMov->modificarcuota($v['idcuota'], $v['nombre'], $v['fechac'], $v['fechap'],$v['moneda'],$v['monto'],$v['interes'],$v['montop'], $v['interesp'],$v['estado']);
				}
  }
  
  if(isset($_SESSION['cuotaseliminar'])){
		$cuotaseliminar=$_SESSION['cuotaseliminar'];
   			foreach($cuotaseliminar as $r=>$v){
			$ObjMov->anularcuota($v['idcuota']);
				}
  }
  
  if(isset($_SESSION['cuotanueva'])){
		$cuotanueva=$_SESSION['cuotanueva'];
   			foreach($cuotanueva as $r=>$v){
			$ObjMov->insertarcuota(NULL,$v['nombre'], $v['idventa'],$_SESSION['FechaProceso'],$v['fechac'], '0000-00-00',$v['moneda'],$v['monto'],$v['interes'],'0','0',"N");
			}
  }
   		$cnx->commit(); 
		header('Location: ../presentacion/frm_cuotasventas.php?accion=CERRAR');
		return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso de actualizacion de cuotas: " . $e->getMessage();
	}   
	
	}
	
	
	
	if($accion=='NUEVAGUIA'){
		try{
			global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			$cnx->beginTransaction();

			$objUbi=new clsUbigeo();
			
			$partida=$objUbi->consultarlugar(NULL,$_POST['txtDireccionPartida'],$_POST['cboDistrito']);
			
			if(!$partida->rowCount()>0){
			$objUbi->insertarlugar($_POST['txtDireccionPartida'],$_POST['cboDistrito']);
			$partida=$objUbi->consultarlugar(NULL,$_POST['txtDireccionPartida'],$_POST['cboDistrito']);
			}
			
			$destino=$objUbi->consultarlugar(NULL,$_POST['txtDireccionLlegada'],$_POST['cboDistrito2']);
			
			if(!$destino->rowCount()>0){
			$objUbi->insertarlugar($_POST['txtDireccionLlegada'],$_POST['cboDistrito2']);
			$destino=$objUbi->consultarlugar(NULL,$_POST['txtDireccionLlegada'],$_POST['cboDistrito2']);
			}
			
			$lugardestino=$destino->fetchObject();
			$lugarpartida=$partida->fetchObject();
			$ObjMov->insertarguia(NULL, 2,$_POST['txtIdSucursal'], 3, $_POST['txtNroDoc'], 5, $_POST['txtTipoVenta'], $_POST['txtFechaRegistro'],0, $_POST['txtTotalGr'], 0,$_POST['txtTotalKg'], $_POST['txtIdUsuario'], $_POST['txtIdPersona'],$_POST['txtIdResponsable'],$_POST['txtIdMovimientoRef'], $_POST['txtIdSucursalRef'], $_POST['txtComentario'], 'N', $_POST['txtFechaTraslado'], $_POST['cboMotivoTraslado'], $_POST['txtIdTransportista'], $lugarpartida->idlugar, $lugardestino->idlugar);

	$rs = $ObjMov->obtenerLastIdVenta($_POST['txtNroDoc'],2,5,$_POST['txtIdSucursal']);
		$dato = $rs->fetchObject();
		$idmov = $dato->IdMovimiento;		
			
   $olu=new clsListaUnidad();
   $ObjMovAlm= new clsDetalleMovAlmacen();
   $stockprod = new clsStockProducto();
   
	foreach($_SESSION['carroguia'] as $k => $v){
      	$consulta=$olu->buscarid(NULL,$v["unidad"],$v["idproducto"],NULL);
   		$datop=$consulta->fetchObject();
		$pc=$datop->preciocompra;
		$pc=number_format($pc,2);
	
   	$ObjMovAlm->insertar($idmov,$v["idproducto"],$datop->idunidad,$v['cantidad'],$pc,$v["precioventa"]);
   	$stockprod->insertar($_POST['txtIdSucursal'],$v["idproducto"],$datop->idunidad,-$v['cantidad'],$idmov,$v['moneda'],$v["precioventa"],$_POST['txtFechaRegistro'],$_POST['txtIdUsuario']);
		}
			$ObjBitacora->insertar(NULL,$idmov,$_POST['txtIdUsuario'],date("Y-m-d H:i:s"),'Registro de nueva Guia de Remision','N');
			
			
			$cnx->commit(); 
			header('Location: ../presentacion/frm_Impdetalleguia.php?IdVenta='.$idmov);
			
		}catch(Exception $e){
			$cnx->rollBack(); 
			echo "Error de Proceso de generacion de guias de remision: " . $e->getMessage();
		}
	}
	if($accion=='DESPACHAR'){
		try{
			global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			$cnx->beginTransaction();

   $stockprod = new clsStockProducto();

  $detalle=$ObjMov->consultardetalleventa($_GET['IdVenta']);
  while($dato = $detalle->fetchObject()){
		$stockprod->insertar($_SESSION['IdSucursal'],$dato->idproducto,$dato->idunidad,-$dato->cantidad,$_GET['IdVenta'],$dato->moneda,$dato->precioventa,date("Y-m-d H:i:s"),$_SESSION['IdUsuario']);
	}
		$ObjBitacora->insertar(NULL,$_GET['IdVenta'],$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Registro de despacho sin Guia de Remision','N');
			
			
			$cnx->commit(); 
			header('Location: ../presentacion/list_ventasalmacen.php');
			
		}catch(Exception $e){
			$cnx->rollBack(); 
			echo "Error de Proceso de generacion de guias de remision: " . $e->getMessage();
		}
	}
}
?>