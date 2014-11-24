<?php
session_start();
require("../datos/cado.php");
require("cls_movcaja.php");
require("cls_bitacora.php");
require("cls_movimiento.php");

controlador($_GET['accion']);
header('Location: ../presentacion/list_movcaja.php');

function controlador($accion)
{
  $Objmovimientos= new clsMovimiento();
  $ObjMov = new clsMovCaja();
  $ObjBitacora = new clsBitacora();
  $ObjUltimoMov= new clsMovimiento();
  
  
    if($accion=='ANULAR'){
	try{
	global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$rst= $ObjMov->consultardetallesmovcaja($_GET['idmovcaja']);
		$numreg=$rst->rowCount();
		
	if($numreg==0){
		
		$Objmovimientos->anular($_GET['idmovcaja']);
		
	}elseif($numreg>0){
	
	while($resultado=$rst->fetch()){
		
		$consulta=$ObjMov->consultar_cuota($resultado[0]);
		$datop=$consulta->fetchObject();
		
		$montopagado=$datop->MontoPagado;
		$interespagado=$datop->InteresPagado;
		
		$ObjMov->actutalizarcuota($resultado[0],'','NO','','','','',($montopagado-$resultado[2]),($interespagado-$resultado[3]),'N');	
		
	}

		$Objmovimientos->anular($_GET['idmovcaja']);
	}
		
		
		$ObjBitacora->insertar(NULL,$_GET['idmovcaja'],$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Anulacion de Mov Caja','A');	
		
		
	$cnx->commit(); 
		return 1;
	}catch(Exception $e){
		$cnx->rollBack(); 
		echo "Error de Proceso de anulacion de venta: " . $e->getMessage();
	}
	}
  
  
  if($accion=='NUEVO'){
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,$_POST['cboConceptoPago'],$_POST['txtNroDoc'],$_POST['cboDoc'],'A',$_POST['txtFecha'],$_POST['cboMoney'],$_POST['txtMontoTotal']/1.19,($_POST['txtMontoTotal']/1.19)*(19/100),$_POST['txtMontoTotal'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');

	$ultimoId=$ObjUltimoMov->obtenerLastId($_POST['txtNroDoc'],3,$_POST['cboDoc']);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;
		
		
		
	if(isset($_SESSION['carrocuota']) && $_POST['hestadocuenta']=='1'){
		
		foreach($_SESSION['carrocuota'] as $k => $v){
		$consulta=$ObjMov->consultar_cuota($v["idCuota"]);
		$datop=$consulta->fetchObject();
		
		$montopagado=$datop->MontoPagado;
		$monto=$datop->Monto;
		$interespagado=$datop->InteresPagado;
		$interes=$datop->Interes;
		
		if(($montopagado+$v["monto"])==$monto && ($interespagado+$v["interes"])==$interes)
		{
			$ObjMov->actutalizarcuota($v["idCuota"],'',$v["fechaprox"],$_SESSION['FechaProceso'],'','','',($montopagado+$v["monto"]),($interespagado+$v["interes"]),'C');
			
			$ObjMov->InsertarDetalleMovCaja($idultimomov,$v["idCuota"],$v["monto"],$v["interes"],$_POST['cboMoney'],$_SESSION['TipoCambio']);
			
		
		}
		if(($montopagado+$v["monto"])!=$monto && ($interespagado+$v["interes"])==$interes)
		{
		
		$ObjMov->actutalizarcuota($v["idCuota"],'',$v["fechaprox"],$_SESSION['FechaProceso'],'','','',($montopagado+$v["monto"]),($interespagado+$v["interes"]),'N');
		
		$ObjMov->InsertarDetalleMovCaja($idultimomov,$v["idCuota"],$v["monto"],$v["interes"],$_POST['cboMoney'],$_SESSION['TipoCambio']);
		}	
		
		if(($montopagado+$v["monto"])==$monto && ($interespagado+$v["interes"])!=$interes)
		{
		$ObjMov->actutalizarcuota($v["idCuota"],'',$v["fechaprox"],$_SESSION['FechaProceso'],'','','',($montopagado+$v["monto"]),($interespagado+$v["interes"]),'N');
		
		$ObjMov->InsertarDetalleMovCaja($idultimomov,$v["idCuota"],$v["monto"],$v["interes"],$_POST['cboMoney'],$_SESSION['TipoCambio']);
		}
		
		if(($montopagado+$v["monto"])!=$monto && ($interespagado+$v["interes"])!=$interes)
		{
		$ObjMov->actutalizarcuota($v["idCuota"],'',$v["fechaprox"],$_SESSION['FechaProceso'],'','','',($montopagado+$v["monto"]),($interespagado+$v["interes"]),'N');
		
		$ObjMov->InsertarDetalleMovCaja($idultimomov,$v["idCuota"],$v["monto"],$v["interes"],$_POST['cboMoney'],$_SESSION['TipoCambio']);
		}
		
		}
	}
		

	$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Nuevo Mov Caja Nro: '.$_POST['txtNroDoc'],'N');	
		
	$cnx->commit(); 
	return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	}   
  }
  
  
  
  
  if($accion=='APERTURA'){
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$num_mov=$ObjMov->existenciamov();
		
		if($num_mov==0){
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$_POST['txtNroDoc'],$_POST['cboDoc'],'A',$_POST['txtFecha'],'S',$_POST['txtMontoSoles']/1.19,($_POST['txtMontoSoles']/1.19)*(19/100),$_POST['txtMontoSoles'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
		
		$num= $_POST['txtNroDoc'];
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente,$_POST['cboDoc'],'A',$_POST['txtFecha'],'D',$_POST['txtMontoDolares']/1.19,($_POST['txtMontoDolares']/1.19)*(19/100),$_POST['txtMontoDolares'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
	$ultimoId=$ObjUltimoMov->obtenerLastId($_POST['txtNroDoc'],3,$_POST['cboDoc']);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Apertura de Caja dia: '.$_POST['txtFecha'],'N');
		
		$cnx->commit(); 
		return 1;
		
	}else{
		$fechacierre1=$ObjMov->consultarmaxfecha();
		$cierre=$ObjMov->consultarcierre($fechacierre1);
		if($cierre==0){
		
		$fechacierre=$ObjMov->consultarmaxfecha();
		
		
		$yearfecha=substr(trim($fechacierre),0,4);
	 
		$objMov=new clsMovimiento(); 
	 
		$numerodoc=$objMov->consultar_numero_sigue(3,11,$_SESSION['IdSucursal']); 
		$numerodoc=$numerodoc.'-'.$yearfecha;
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,2,$numerodoc,11,'A',$fechacierre,'S',$_POST['txtMontoSoles']/1.19,($_POST['txtMontoSoles']/1.19)*(19/100),$_POST['txtMontoSoles'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');	
		$num= $numerodoc;
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,2,$numsiguiente,11,'A',$fechacierre,'D',$_POST['txtMontoDolares']/1.19,($_POST['txtMontoDolares']/1.19)*(19/100),$_POST['txtMontoDolares'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
	$ultimoId=$ObjUltimoMov->obtenerLastId($numerodoc,3,11);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Cierre de Caja dia: '.$fechacierre,'N');
		
		//ahora aperturamos
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$_POST['txtNroDoc'],$_POST['cboDoc'],'A',$_POST['txtFecha'],'S',$_POST['txtMontoSoles']/1.19,($_POST['txtMontoSoles']/1.19)*(19/100),$_POST['txtMontoSoles'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
		
		$num2= $_POST['txtNroDoc'];
		$year=substr($num2,11,4);
		$year2=date('Y');
		if($year!=$year2){$num2='001-000000';}
		$serie=substr($num2,0,3)+0;
		if(substr($num2,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num2=substr($num2,4,6)+1;
		$num2= substr($cero,0,6-strlen($num2)).$num2;
		$num2=$serie.'-'.$num2.'-'.date('Y');
		$numsiguiente2=$num2;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente2,$_POST['cboDoc'],'A',$_POST['txtFecha'],'D',$_POST['txtMontoDolares']/1.19,($_POST['txtMontoDolares']/1.19)*(19/100),$_POST['txtMontoDolares'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
	$ultimoId=$ObjUltimoMov->obtenerLastId($_POST['txtNroDoc'],3,$_POST['cboDoc']);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Apertura de Caja dia: '.$_POST['txtFecha'],'N');
		
		$cnx->commit(); 
		return 1;
		
		}else{
		
		
	$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$_POST['txtNroDoc'],$_POST['cboDoc'],'A',$_POST['txtFecha'],'S',$_POST['txtMontoSoles']/1.19,($_POST['txtMontoSoles']/1.19)*(19/100),$_POST['txtMontoSoles'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
		
		$num= $_POST['txtNroDoc'];
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente,$_POST['cboDoc'],'A',$_POST['txtFecha'],'D',$_POST['txtMontoDolares']/1.19,($_POST['txtMontoDolares']/1.19)*(19/100),$_POST['txtMontoDolares'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
	$ultimoId=$ObjUltimoMov->obtenerLastId($_POST['txtNroDoc'],3,$_POST['cboDoc']);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Apertura de Caja dia: '.$_POST['txtFecha'],'N');
		
		$cnx->commit(); 
		return 1;
			
		
		}
	
	}
		
		
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	} 
	  
  }
  
  
  if($accion=='CIERRE'){
	try{
		global $cnx;
		$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		$cnx->beginTransaction();
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,2,$_POST['txtNroDoc'],$_POST['cboDoc'],'A',$_POST['txtFecha'],'S',$_POST['txtMontoSoles']/1.19,($_POST['txtMontoSoles']/1.19)*(19/100),$_POST['txtMontoSoles'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
				
		$num= $_POST['txtNroDoc'];
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,2,$numsiguiente,$_POST['cboDoc'],'A',$_POST['txtFecha'],'D',$_POST['txtMontoDolares']/1.19,($_POST['txtMontoDolares']/1.19)*(19/100),$_POST['txtMontoDolares'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
	$ultimoId=$ObjUltimoMov->obtenerLastId($_POST['txtNroDoc'],3,$_POST['cboDoc']);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],date("Y-m-d H:i:s"),'Cierre de Caja dia: '.$_POST['txtFecha'],'N');
	
	//aperturar dia siguiente	
	
	/*$fechadiasiguiente=	$ObjMov->fechasiguiente($_POST['txtFecha']);
	
		$yearfecha=substr(trim($fechadiasiguiente),0,4);
	 
		$objMov=new clsMovimiento(); 
	 
		$numerodoc=$objMov->consultar_numero_sigue(3,10,$_SESSION['IdSucursal']); 
		$numerodoc=$numerodoc.'-'.$yearfecha;
	
	$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numerodoc,10,'A',$fechadiasiguiente,'S',$_POST['txtMontoSoles']/1.19,($_POST['txtMontoSoles']/1.19)*(19/100),$_POST['txtMontoSoles'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
				
		$num2= $numerodoc;
		$year=substr($num2,11,4);
		$year2=date('Y');
		if($year!=$year2){$num2='001-000000';}
		$serie=substr($num2,0,3)+0;
		if(substr($num2,4,6)==999999){$serie=$serie+1;$num2=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num2=substr($num2,4,6)+1;
		$num2= substr($cero,0,6-strlen($num2)).$num2;
		$num2=$serie.'-'.$num2.'-'.date('Y');
		$numsiguiente2=$num2;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente2,10,'A',$fechadiasiguiente,'D',$_POST['txtMontoDolares']/1.19,($_POST['txtMontoDolares']/1.19)*(19/100),$_POST['txtMontoDolares'],$_SESSION['IdUsuario'],$_POST['txtIdPersona'],$_SESSION['IdUsuario'],'','',$_POST['txtComentario'],'N');
		
	$ultimoId=$ObjUltimoMov->obtenerLastId($numerodoc,3,10);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],$fechadiasiguiente,'Cierre de Caja dia: '.$fechadiasiguiente,'N');
	*/
	
		$cnx->commit(); 
		return 1;
	} catch (Exception $e) { 
		$cnx->rollBack(); 
		echo "Error de Proceso en Lotes: " . $e->getMessage();
	} 
	  
  }
}
?>