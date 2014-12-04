<?php
session_start();
class clsMovCaja
{

function InsertarDetalleMovCaja($idMovCaja,$idCuota,$monto,$interes,$moneda,$tipocambio){

$sql="INSERT INTO detallemovcaja(idmovimiento,idcuota,monto,interes,moneda,tipocambio) VALUES('".$idMovCaja."','".$idCuota."','".$monto."','".$interes."','".$moneda."','".$tipocambio."')";

global $cnx;
return $cnx->query($sql);

}




function rptproyeccion($tipomov,$tipodoc,$fechaI,$fechaF,$moneda){
	
	$sql="SELECT movimiento.IDmovimiento, tipodocumento.abreviatura, movimiento.NUMERO, DATE(movimiento.FECHA), movimiento.TOTAL, movimiento.MONEDA,CONCAT(persona.NOMBRES,' ',persona.APELLIDOS),tipomovimiento.DESCRIPCION, cuota.ESTADO, movimiento.FORMAPAGO, tipomovimiento.IDTIPOMOVIMIENTO
FROM movimiento
INNER JOIN cuota ON cuota.IDmovimiento = movimiento.IDmovimiento
INNER JOIN tipodocumento ON movimiento.IDTIPODOCUMENTO = tipodocumento.IDTIPODOCUMENTO
INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento = tipomovimiento.IDtipomovimiento
INNER JOIN persona ON persona.IDpersona=movimiento.IDpersona
WHERE cuota.ESTADO = 'N'
AND movimiento.FORMAPAGO = 'B'";

if($moneda!='0'){
	$sql=$sql." AND movimiento.MONEDA = '".$moneda."'";
}
if($tipomov!='0'){
	$sql=$sql."AND tipomovimiento.IDtipomovimiento='".$tipomov."'";
}
if($tipodoc!='0'){
	$sql=$sql."AND tipodocumento.IDTIPODOCUMENTO='".$tipodoc."'";
}
if($fechaI!=''){
	$sql=$sql."AND DATE(movimiento.FECHA)>='".$fechaI."'";
}
if($fechaF!=''){
	$sql=$sql."AND DATE(movimiento.FECHA)<='".$fechaF."'";
}

$sql=$sql. " GROUP BY movimiento.IDmovimiento, cuota.estado";
	
		global $cnx;
		return $cnx->query($sql);
}

function reportesyear($idtipodocumento,$idrolpersona,$idconceptopago,$moneda,$caja,$estado,$yearI,$idsucursal){

if($idrolpersona!='0'){

$sql="SELECT SUM( movimiento.TOTAL )  FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN rolpersona ON rolpersona.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE conceptopago.IDconceptopago!=2 ";

}else{

$sql="SELECT SUM( movimiento.TOTAL ) FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE conceptopago.IDconceptopago!=2 ";
}

if(isset($caja)){
 	$sql = $sql . " AND tipomovimiento.descripcion='".$caja."'";
}
if(isset($estado)){
 	$sql = $sql . " AND movimiento.estado='".$estado."'";
}
if(isset($yearI)){
 	$sql = $sql . " AND YEAR( movimiento.fecha )='".$yearI."'";
}
if(isset($idsucursal)){
 	$sql = $sql . " AND movimiento.IDSUCURSAL='".$idsucursal."'";
}
if($idtipodocumento!='0'){
 	$sql = $sql . " AND movimiento.idtipodocumento='".$idtipodocumento."'";
}
if($idrolpersona!='0'){
 	$sql = $sql . " AND rolpersona.idrol='".$idrolpersona."'";
}
if($idconceptopago!='0'){
 	$sql = $sql . " AND movimiento.idconceptopago='".$idconceptopago."'";
}
if($moneda!='0'){
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";
}

$sql=$sql." GROUP BY YEAR( movimiento.FECHA ), tipodocumento.DESCRIPCION, movimiento.MONEDA";
	
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
if($valor[0]==''){
return '0.00';
}else{
return $valor[0];
}

}



function reportesmes($idtipodocumento,$idrolpersona,$idconceptopago,$moneda,$caja,$estado,$fechaI,$idsucursal,$year){

if($idrolpersona!='0'){

$sql="SELECT SUM( movimiento.TOTAL )  FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN rolpersona ON rolpersona.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE conceptopago.IDconceptopago!=2 ";

}else{

$sql="SELECT SUM( movimiento.TOTAL ) FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE conceptopago.IDconceptopago!=2 ";
}

if(isset($caja)){
 	$sql = $sql . " AND tipomovimiento.descripcion='".$caja."'";
}
if(isset($estado)){
 	$sql = $sql . " AND movimiento.estado='".$estado."'";
}
if(isset($fechaI)){
 	$sql = $sql . " AND MONTH( movimiento.fecha )='".$fechaI."'";
}
if(isset($idsucursal)){
 	$sql = $sql . " AND movimiento.IDSUCURSAL='".$idsucursal."'";
}
if($idtipodocumento!='0'){
 	$sql = $sql . " AND movimiento.idtipodocumento='".$idtipodocumento."'";
}
if($idrolpersona!='0'){
 	$sql = $sql . " AND rolpersona.idrol='".$idrolpersona."'";
}
if($idconceptopago!='0'){
 	$sql = $sql . " AND movimiento.idconceptopago='".$idconceptopago."'";
}
if($moneda!='0'){
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";
}
if(isset($year)){
	$sql = $sql . " AND YEAR( movimiento.fecha )='".$year."'";
}

$sql=$sql." GROUP BY MONTH( movimiento.FECHA ), tipodocumento.DESCRIPCION, movimiento.MONEDA";
	
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
if($valor[0]==''){
return '0.00';
}else{
return $valor[0];
}

}

function nombremes($mes){

if($mes==1){$mes='ENERO';}elseif($mes==2){$mes='FEBRERO';}elseif($mes==3){$mes='MARZO';}elseif($mes==4){$mes='ABRIL';}elseif($mes==5){$mes='MAYO';}elseif($mes==6){$mes='JUNIO';}elseif($mes==7){$mes='JULIO';}elseif($mes==8){$mes='AGOSTO';}elseif($mes==9){$mes='SEPTIEMBRE';}elseif($mes==10){$mes='OCTUBRE';}elseif($mes==11){$mes='NOVIEMBRE';}elseif($mes==12){$mes='DICIEMBRE';}

 return $mes;
}



function year_min_max(){

$sql="SELECT MIN( YEAR( FECHA ) ), MAX(YEAR(FECHA))
FROM movimiento
WHERE IDtipomovimiento =3
AND IDSUCURSAL =".$_SESSION['IdSucursal'];
global $cnx;
return $cnx->query($sql);
}


function fechasiguiente($f){

$day=substr(trim($f),8,2);
$mes=substr(trim($f),5,2);
$year=substr(trim($f),0,4);
	
	if(($year%4) > 0){
		if($mes==2){
			if($day>=28){$mes=$mes+1; $day=1;
			}else{$day=$day+1;}
		}else if($mes==1 || $mes==3 || $mes==5 || $mes==7 || $mes==8 || $mes==10 || $mes==12){
			if($day>=31){$mes=$mes+1; $day=1;
			}else{$day=$day+1;}
		}else if($mes==4 || $mes==6 || $mes==9 || $mes==11){
			if($day>=30){$mes=$mes+1; $day=1;
				}else{$day=$day+1;}
		}			
	}else{
		if($mes==2){
			if($day>=29){$mes=$mes+1; $day=1;
			}else{$day=$day+1;}
		}else if($mes==1 || $mes==3 || $mes==5 || $mes==7 || $mes==8 || $mes==10 || $mes==12){
			if($day>=31){$mes=$mes+1; $day=1;
			}else{$day=$day+1;}
		
		}else if($mes==4 || $mes==6 || $mes==9 || $mes==11){
			if($day>=30){$mes=$mes+1; $day=1;
			}else{$day=$day+1;}
		}		

	}
	if($mes==13){$mes=1;$year=$year+1;}
	$cero='00';
	$day=substr($cero,0,2-strlen($day)).$day;
	$mes=substr($cero,0,2-strlen($mes)).$mes;
	$fecha=$year.'-'.$mes.'-'.$day;

return $fecha;
}


function consultarmaxfecha(){
$sql="SELECT MAX( DATE( FECHA ) ) 
FROM movimiento
WHERE IDtipomovimiento =3
AND IDSUCURSAL =".$_SESSION['IdSucursal'];
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
return $valor[0];
}


function consultarconceptopago(){
$sql = "SELECT IdConceptoPago, CONCAT(TIPO,' - ',Descripcion) FROM conceptopago WHERE IdConceptoPago!=5 AND IdConceptoPago!=6";
	global $cnx;
	return $cnx->query($sql);

}

function existenciamov(){
$sql="SELECT * FROM movimiento WHERE IdSucursal=".$_SESSION['IdSucursal']." AND IdTipoMovimiento=3";
global $cnx;
$rst=$cnx->query($sql);
$num=$rst->rowCount();
return $num;
}

function consultarsucursal(){
$sql="SELECT APELLIDOS FROM persona WHERE IDpersona=".$_SESSION['IdSucursal'];
global $cnx;
$rst=$cnx->query($sql);
$sucursal=$rst->fetch();
return $sucursal[0];
}


function montodeaperturasoles(){

$sql1="SELECT MAX(DATE(movimiento.FECHA))
FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND movimiento.IDSUCURSAL='".$_SESSION['IdSucursal']."'";
global $cnx;
$rst1=$cnx->query($sql1);
$fecha=$rst1->fetch();

$sql="SELECT movimiento.moneda, movimiento.total, conceptopago.tipo
FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
INNER JOIN conceptopago ON movimiento.idconceptopago = conceptopago.idconceptopago
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND date( movimiento.fecha ) ='".$fecha[0]."'
AND movimiento.IDSUCURSAL='".$_SESSION['IdSucursal']."'
AND movimiento.MONEDA='S' 
AND conceptopago.IDconceptopago <> '2'";

global $cnx;
$rst=$cnx->query($sql);

$totalIS=0;
$totalES=0;

if(isset($rst)){
while($dato6 = $rst->fetchObject())
{
if($dato6->moneda=='S' && $dato6->tipo=='I')
$totalIS=$totalIS+($dato6->total);

if($dato6->moneda=='S' && $dato6->tipo=='E')
$totalES=$totalES+($dato6->total);

}
}
$saldos=$totalIS-$totalES;

return $saldos;
}


function montodeaperturadolares(){

$sql1="SELECT MAX(DATE(movimiento.FECHA))
FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND movimiento.IDSUCURSAL='".$_SESSION['IdSucursal']."'";
global $cnx;
$rst1=$cnx->query($sql1);
$fecha=$rst1->fetch();

$sql="SELECT movimiento.moneda, movimiento.total, conceptopago.tipo
FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
INNER JOIN conceptopago ON movimiento.idconceptopago = conceptopago.idconceptopago
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND date( movimiento.fecha ) ='".$fecha[0]."'
AND movimiento.IDSUCURSAL='".$_SESSION['IdSucursal']."'
AND movimiento.MONEDA='D' 
AND conceptopago.IDconceptopago <> '2'";

global $cnx;
$rst=$cnx->query($sql);

$totalID=0;
$totalED=0;

if(isset($rst)){
while($dato6 = $rst->fetchObject())
{

if($dato6->moneda=='D' && $dato6->tipo=='I')
$totalID=$totalID+($dato6->total);

if($dato6->moneda=='D' && $dato6->tipo=='E')
$totalED=$totalED+($dato6->total);
}
}
$saldod=$totalID-$totalED;
return $saldod;
}



function consultarapertura(){

$sql="SELECT movimiento.idmovimiento, movimiento.numero, movimiento.total, conceptopago.descripcion
FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
INNER JOIN conceptopago ON movimiento.idconceptopago = conceptopago.idconceptopago
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND date( movimiento.fecha ) = '".$_SESSION['FechaProceso']."'
AND conceptopago.idConceptoPago = '1'
AND movimiento.IDSUCURSAL = '".$_SESSION['IdSucursal']."'";

global $cnx;
$rst=$cnx->query($sql);
$num_row=$rst->rowCount();

return $num_row;
}

function consultarcierre($fecha)
{
$sql="SELECT movimiento.idmovimiento, movimiento.numero, movimiento.total, conceptopago.descripcion
FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
INNER JOIN conceptopago ON movimiento.idconceptopago = conceptopago.idconceptopago
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND date( movimiento.fecha ) = '".$fecha."'
AND conceptopago.idConceptoPago = '2'
AND movimiento.IDSUCURSAL = '".$_SESSION['IdSucursal']."'";

global $cnx;
$rst=$cnx->query($sql);
$num_row=$rst->rowCount();

return $num_row;

}

function consultar()
{

$sql="SELECT movimiento.idmovimiento, movimiento.numero, movimiento.moneda, movimiento.total, conceptopago.tipo, conceptopago.descripcion, DATE( movimiento.fecha ) AS fecha, movimiento.comentario,
persona.nombres, persona.apellidos, persona.idpersona, conceptopago.idconceptopago,movimiento.idmovimientoref,movimiento.formapago FROM movimiento
INNER JOIN tipomovimiento ON movimiento.idtipomovimiento = tipomovimiento.idtipomovimiento
INNER JOIN conceptopago ON movimiento.idconceptopago = conceptopago.idconceptopago
INNER JOIN persona ON persona.IDpersona = movimiento.IDpersona
WHERE tipomovimiento.descripcion = 'CAJA'
AND movimiento.estado = 'N'
AND date( movimiento.fecha ) = '".$_SESSION['FechaProceso']."'
AND movimiento.IDSUCURSAL = '".$_SESSION['IdSucursal']."' ORDER BY movimiento.IDmovimiento ASC 
";
   global $cnx;
   return $cnx->query($sql);

}  

function cargarcombo($tipo)
{

if($tipo=='E'){
	$sql = "SELECT IdConceptoPago, Descripcion FROM conceptopago WHERE tipo='".$tipo."' AND IdConceptoPago!=2 AND IdConceptoPago!=5";
	global $cnx;
	return $cnx->query($sql);
}elseif($tipo=='I'){

$sql = "SELECT IdConceptoPago, Descripcion FROM conceptopago WHERE tipo='".$tipo."' AND IdConceptoPago!=1 AND IdConceptoPago!=6";
	global $cnx;
	return $cnx->query($sql);
}
}

function comboapertura()
{
$sql = "SELECT IdConceptoPago, Descripcion FROM conceptopago WHERE IdConceptoPago=1";
	global $cnx;
	return $cnx->query($sql);
}
	
function combocierre()
{
$sql = "SELECT IdConceptoPago, Descripcion FROM conceptopago WHERE IdConceptoPago=2";
	global $cnx;
	return $cnx->query($sql);
}		


function listarestadodecuenta($id,$money,$doc){
	
	$sql="SELECT movimiento.IDMOVIMIENTO, tipodocumento.DESCRIPCION, movimiento.NUMERO, DATE(movimiento.FECHA), movimiento.TOTAL, movimiento.MONEDA, cuota.ESTADO, movimiento.IDPERSONA, movimiento.FORMAPAGO, tipomovimiento.IDtipomovimiento
FROM movimiento
INNER JOIN cuota ON cuota.IDmovimiento = movimiento.IDmovimiento
INNER JOIN tipodocumento ON movimiento.IDTIPODOCUMENTO = tipodocumento.IDTIPODOCUMENTO
INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento = tipomovimiento.IDtipomovimiento
GROUP BY movimiento.IDmovimiento, cuota.estado
HAVING cuota.ESTADO = 'N'
AND movimiento.FORMAPAGO = 'B'
AND movimiento.MONEDA = '".$money."'
AND movimiento.IDpersona =".$id." ";
	if($doc=='10'){
	$sql.=" AND tipomovimiento.IDtipomovimiento=2";
	}elseif($doc=='11'){
	$sql.=" AND tipomovimiento.IDtipomovimiento=1";
	}
	
		global $cnx;
		return $cnx->query($sql);
}

function listarcuotas($id){
$sql="SELECT idcuota,nombre,DATE(FechaCancelacion),moneda,monto,interes,montopagado,interespagado,(monto-montopagado) as saldo, (interes-interespagado) as saldointeres FROM cuota WHERE ESTADO='N' AND IDmovimiento=".$id;
global $cnx;
return $cnx->query($sql);
}


function insertar($idsucursal,$idtipomovimiento,$idconceptopago,$numero,$idtipodocumento,$formapago,$fecha,$moneda,$subtotal,$igv,$total,$idusuario,$idpersona,$idresponsable,$idmovimientoref,$idsucursalref,$comentario,$estado){

$sql="INSERT INTO movimiento(idsucursal,idtipomovimiento,idconceptopago,numero,idtipodocumento,formapago,fecha,moneda,subtotal,igv,total,idusuario,idpersona,idresponsable,idmovimientoref,idsucursalref,comentario,estado) VALUES('".$idsucursal."','".$idtipomovimiento."','".$idconceptopago."','".$numero."','".$idtipodocumento."','".$formapago."','".$fecha."','".$moneda."','".$subtotal."','".$igv."','".$total."','".$idusuario."','".$idpersona."','".$idresponsable."','".$idmovimientoref."','".$idsucursalref."',";
if($comentario==""){
$sql.="'--',";
}else{
$sql.="UPPER('".$comentario."'),";
}
$sql.="'".$estado."')";

global $cnx;
return $cnx->query($sql);

}

function buscarmovimientos($idtipodocumento,$idrolpersona,$idconceptopago,$moneda,$caja,$estado,$fecha,$idsucursal){

if($idrolpersona!='0'){

$sql="SELECT movimiento.NUMERO,tipodocumento.DESCRIPCION,movimiento.MONEDA,movimiento.TOTAL,persona.NOMBRES,persona.APELLIDOS,conceptopago.DESCRIPCION,movimiento.COMENTARIO FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN rolpersona ON rolpersona.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE 1=1 ";

}else{

$sql="SELECT movimiento.NUMERO,tipodocumento.DESCRIPCION,movimiento.MONEDA,movimiento.TOTAL,persona.NOMBRES,persona.APELLIDOS,conceptopago.DESCRIPCION,movimiento.COMENTARIO FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE 1=1 ";
}

if(isset($caja)){
 	$sql = $sql . " AND tipomovimiento.descripcion='".$caja."'";
}
if(isset($estado)){
 	$sql = $sql . " AND movimiento.estado='".$estado."'";
}
if(isset($fecha)){
 	$sql = $sql . " AND date( movimiento.fecha )='".$fecha."'";
}
if(isset($idsucursal)){
 	$sql = $sql . " AND movimiento.IDSUCURSAL='".$idsucursal."'";
}
if($idtipodocumento!='0'){
 	$sql = $sql . " AND movimiento.idtipodocumento='".$idtipodocumento."'";
}
if($idrolpersona!='0'){
 	$sql = $sql . " AND rolpersona.idrol='".$idrolpersona."'";
}
if($idconceptopago!='0'){
 	$sql = $sql . " AND movimiento.idconceptopago='".$idconceptopago."'";
}
if($moneda!='0'){
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";
}
	
global $cnx;
return $cnx->query($sql);

}

function buscarmovimientosrpt($idtipodocumento,$idrolpersona,$idconceptopago,$moneda,$caja,$estado,$fecha,$idsucursal){

if($idrolpersona!='0'){

$sql="SELECT movimiento.NUMERO,tipodocumento.DESCRIPCION,movimiento.MONEDA,movimiento.TOTAL,persona.NOMBRES,persona.APELLIDOS,conceptopago.DESCRIPCION,movimiento.COMENTARIO,movimiento.IdMovimiento FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN rolpersona ON rolpersona.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE conceptopago.IDconceptopago!=2 ";

}else{

$sql="SELECT movimiento.NUMERO,tipodocumento.DESCRIPCION,movimiento.MONEDA,movimiento.TOTAL,persona.NOMBRES,persona.APELLIDOS,conceptopago.DESCRIPCION,movimiento.COMENTARIO, movimiento.IdMovimiento FROM movimiento INNER JOIN tipodocumento ON tipodocumento.IDTIPODOCUMENTO = movimiento.IDTIPODOCUMENTO INNER JOIN persona ON movimiento.IDpersona = persona.IDpersona INNER JOIN conceptopago ON conceptopago.IDconceptopago = movimiento.IDconceptopago INNER JOIN tipomovimiento ON movimiento.IDtipomovimiento=tipomovimiento.IDtipomovimiento WHERE conceptopago.IDconceptopago!=2 ";
}

if(isset($caja)){
 	$sql = $sql . " AND tipomovimiento.descripcion='".$caja."'";
}
if(isset($estado)){
 	$sql = $sql . " AND movimiento.estado='".$estado."'";
}
if(isset($fecha)){
 	$sql = $sql . " AND date( movimiento.fecha )='".$fecha."'";
}
if(isset($idsucursal)){
 	$sql = $sql . " AND movimiento.IDSUCURSAL='".$idsucursal."'";
}
if($idtipodocumento!='0'){
 	$sql = $sql . " AND movimiento.idtipodocumento='".$idtipodocumento."'";
}
if($idrolpersona!='0'){
 	$sql = $sql . " AND rolpersona.idrol='".$idrolpersona."'";
}
if($idconceptopago!='0'){
 	$sql = $sql . " AND movimiento.idconceptopago='".$idconceptopago."'";
}
if($moneda!='0'){
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";
}

global $cnx;
return $cnx->query($sql);

}

function consultarDetPrecCompPorMovimiento($idmovimiento){
	$sql = "SELECT SUM(detallemovalmacen.preciocompra) as totaldepreciocompra FROM detallemovalmacen INNER JOIN movimiento ON detallemovalmacen.idmovimiento= movimiento.idmovimiento WHERE movimiento.idmovimiento ='".$idmovimiento."'";
	global $cnx;
	return $cnx->query($sql);
}

function consultar_cuota($idcuota){
	$sql="SELECT
			cuota.IdCuota,
			cuota.Nombre,
			cuota.IdMovimiento,
			DATE_FORMAT(cuota.FechaCancelacion,'%d/%m/%Y') as FechaCancelacion,
			cuota.FechaCreacion,
			cuota.FechaPago,
			cuota.Moneda,
			cuota.Interes,
			cuota.Monto,
			cuota.MontoPagado,
			cuota.InteresPagado,
			cuota.Estado
		FROM
			cuota
		WHERE
			cuota.IdCuota=".$idcuota;
			
	global $cnx;
	return $cnx->query($sql);


}

function actutalizarcuota($idcuota,$nombre,$fechacancelacion,$fechapago,$moneda,$monto,$interes,$montopagado,$interespagado,$estado){

$sql="UPDATE cuota SET  montopagado='".$montopagado."', interespagado='".$interespagado."', estado='".$estado."'";

if($fechapago!=''){
$sql.=", fechapago='".$fechapago."'";
}
if($fechacancelacion!='NO'){
$sql.=", fechacancelacion='".$fechacancelacion."'";
}

$sql.="  WHERE idcuota=".$idcuota."";

	global $cnx;
	return $cnx->query($sql);
}

function cargarcomborolpersona()
 {
   $sql = "SELECT IdRol, descripcion,estado FROM rol WHERE estado='N' and IdRol<>5";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function consultarpersonarol($rol,$campo,$frase,$limite)
 {
 $sql="SELECT Distinct persona.idpersona, CONCAT(apellidos,' ',nombres) as Nombres, NroDoc
FROM persona
INNER JOIN rolpersona ON persona.idpersona = rolpersona.idpersona
INNER JOIN rol ON rol.idrol = rolpersona.idrol
WHERE rol.estado = 'N'
AND persona.estado = 'N' AND rol.idrol<>5"; 
/*	enviar 0 para todos
	1 CLIENTE
    2 PROVEEDOR
    3 EMPLEADO
    4 USUARIO */
 $sql = $sql . " AND ".$campo." LIKE '%" . $frase . "%'";
 if(isset($rol))
	$sql = $sql . " AND (rol.idrol = ".$rol." OR rol.idrol=rol.idrol-".$rol.")";

 $sql = $sql . " $limite";
 
 global $cnx;
 return $cnx->query($sql);
 }

function fecha_prox_pago($id){
$sql="SELECT MIN( DATE( FECHACANCELACION ) ) 
FROM cuota
WHERE IDmovimiento =".$id."
AND ESTADO = 'N'";
 global $cnx;
 $rst= $cnx->query($sql);
 $valor=$rst->fetch();
 return $valor[0];
}


function monto_pagado($id){
$sql="SELECT SUM( MONTOPAGADO ) AS MONTOPAGADO
FROM cuota
WHERE IDmovimiento =".$id;

global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$montopagado=$valor[0];
return $montopagado;


}

function interes_pagado($id){
$sql="SELECT SUM( INTERESPAGADO ) AS INTERESPAGADO
FROM cuota
WHERE IDmovimiento =".$id;

global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$interespagado=$valor[0];
return $interespagado;
}

function saldo_monto($id){
$sql="SELECT SUM( MONTO - MONTOPAGADO ) AS SALDOMONTO
FROM cuota
WHERE IDmovimiento =".$id;
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$saldomonto=$valor[0];
return $saldomonto;
}

function saldo_interes($id){
$sql="SELECT  SUM( INTERES - INTERESPAGADO ) AS SALDOINTERES
FROM cuota
WHERE IDmovimiento =".$id;
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$saldointeres=$valor[0];
return $saldointeres;
}

function numero_documento($idcuota)
{
$sql="SELECT movimiento.NUMERO, tipodocumento.DESCRIPCION
FROM movimiento
INNER JOIN tipodocumento ON movimiento.IDTIPODOCUMENTO = tipodocumento.IDTIPODOCUMENTO
INNER JOIN cuota ON cuota.IDmovimiento = movimiento.IDmovimiento
WHERE IDcuota =".$idcuota;
global $cnx;
return $cnx->query($sql);
}

function consultartipodoc($id){
$sql="SELECT DESCRIPCION FROM tipodocumento WHERE IDTIPODOCUMENTO=".$id;
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$nombre=$valor[0];
return $nombre;
}

function consultarconceptopag($id){
$sql="SELECT DESCRIPCION FROM conceptopago WHERE IDconceptopago=".$id;
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$nombre=$valor[0];
return $nombre;
}

function consultarrolper($id){
$sql="SELECT DESCRIPCION FROM rol WHERE IDrol=".$id;
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$nombre=$valor[0];
return $nombre;
}

function consultarcomentario($idmovcaja)
{

$sql="SELECT COMENTARIO FROM movimiento WHERE IDmovimiento=".$idmovcaja;
global $cnx;
$rst=$cnx->query($sql);
$valor=$rst->fetch();
$nombre=$valor[0];
return $nombre;

}

function consultardetallesmovcaja($idmov){
$sql="SELECT detallemovcaja.IDcuota, cuota.NOMBRE, detallemovcaja.MONTO, detallemovcaja.INTERES, detallemovcaja.MONEDA, detallemovcaja.TIPOCAMBIO
FROM detallemovcaja
INNER JOIN cuota ON cuota.IDcuota = detallemovcaja.IDCUOTA
WHERE detallemovcaja.IDmovimiento =".$idmov;
global $cnx;
return $cnx->query($sql);
}




}
?>