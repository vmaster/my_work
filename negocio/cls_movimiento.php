<?php
class clsMovimiento
{

function insertar($idmovimiento,$idtipomovimiento, $idsucursal, $idconceptopago, $numero, $idtipodocumento, $formapago,$fecha,$moneda,$subtotal,$igv,$total,$idusuario,$idpersona,$idresponsable,$idmovimientoref,$idsucursalref,$comentario,$estado)
 {
   $sql = "INSERT INTO movimiento(idmovimiento, idsucursal, idtipomovimiento, idconceptopago, numero, idtipodocumento, formapago, fecha, moneda, subtotal, igv, total, idusuario, idpersona, idresponsable, idmovimientoref, idsucursalref, comentario, estado) VALUES(NULL,'" . $idsucursal . "',".$idtipomovimiento.",'". $idconceptopago ."','". $numero . "','".$idtipodocumento."','".$formapago."','".$fecha."','".$moneda."','".$subtotal."','".$igv."','".$total."','".$idusuario."','".$idpersona."','".$idresponsable."','".$idmovimientoref."','".$idsucursalref."','".$comentario."','".$estado."')";
      
   global $cnx;
   return $cnx->query($sql) or die($sql);
       	 	 	
 }

function buscar($idpersona){
$sql = "SELECT * FROM movimiento WHERE idPersona=".$idpersona." OR idresponsable=".$idpersona." OR idusuario=".$idpersona ;
global $cnx;
	return $cnx->query($sql) ;
}

function consultar($idtipomovimiento,$idmovimiento,$idsucursal,$numero, $idtipodocumento,$fecha){
 $sql="SELECT movimiento.idmovimiento,movimiento.idsucursal,movimiento.idtipomovimiento,movimiento.idconceptopago,movimiento.numero,movimiento.formapago, movimiento.fecha, sucursal.apellidos as sucursal, movimiento.idpersona,persona.nombres FROM movimiento inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal inner join persona on persona.idpersona=movimiento.idpersona WHERE idtipomovimiento=".$idtipomovimiento;
 if(isset($idmovimiento))
 	$sql = $sql . " AND movimiento.idmovimiento=".$idmovimiento;
 if(isset($idsucursal))
 	$sql = $sql . " AND movimiento.idsucursal=".$idsucursal;
 if(isset($numero))
 	$sql = $sql . " AND movimiento.numero LIKE '".$numero."%'";
 if(isset($fecha))
	$sql = $sql . " AND movimiento.fecha=".$fecha;
 if(isset($idtipodocumento))
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
	
	global $cnx;
	return $cnx->query($sql);
}

function consultarCompra($idtipomovimiento,$idsucursal, $idtipodocumento,$fecha1,$fecha2,$estado,$numero,$FormaPago){
 $sql="SELECT
		movimiento.IdMovimiento AS idmov,
		movimiento.Moneda AS moneda,
		tipomovimiento.Descripcion AS tipomov,
		movimiento.Numero AS numero,
		DATE_FORMAT(movimiento.Fecha,'%d/%m/%Y') AS fecha,
		sucursal.apellidos AS sucursal,
		movimiento.Comentario AS comentario,
		movimiento.IdMovimientoRef AS movref,
		movimiento.IdSucursalRef AS sucref,
		movimiento.Estado AS estado,
		movimiento.Total,
		persona.Nombres AS nomResponsable,
		persona.Apellidos AS apeResponsable,
		proveedor.Nombres AS nomProveedor,
		proveedor.Apellidos AS apeProveedor,
		movimiento.IdTipoDocumento AS tipoDoc,
		docum.Descripcion AS nomDoc,
		movimiento.SubTotal,
		movimiento.Igv,
		movimiento.FormaPago
	FROM
		movimiento
		inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal
		Inner Join conceptopago ON conceptopago.IdConceptoPago = movimiento.IdConceptoPago
		Inner Join tipodocumento ON tipodocumento.IdTipoDocumento = movimiento.IdTipoDocumento
		Inner Join tipomovimiento ON tipomovimiento.IdTipoMovimiento = movimiento.IdTipoMovimiento AND '' = ''
		Inner Join persona ON movimiento.IdResponsable = persona.IdPersona
		Inner Join persona AS proveedor ON movimiento.IdPersona = proveedor.IdPersona
		Inner Join tipodocumento AS docum ON movimiento.IdTipoDocumento = docum.IdTipoDocumento
	WHERE
		tipomovimiento.IdTipoMovimiento =".$idtipomovimiento;

 if(isset($idsucursal))
 	$sql = $sql . " AND movimiento.idsucursal=".$idsucursal;
 if(isset($fecha1) && isset($fecha2))
	$sql = $sql . " AND fecha BETWEEN '".$fecha1."' AND '".$fecha2."'";
 if(isset($idtipodocumento) && $idtipodocumento!='0')
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
 if(isset($estado) && $estado!='0')
	$sql = $sql . " AND movimiento.Estado='".$estado."'";
 if(isset($numero) && $numero!='' )
	$sql = $sql . " AND numero LIKE '%".$numero."%'";	
 if(isset($FormaPago) && $FormaPago!='0')
	$sql = $sql . " AND FormaPago='".$FormaPago."'";
	   
  $sql=$sql." ORDER BY movimiento.Fecha DESC, movimiento.Numero ASC, tipomovimiento.Descripcion ASC";


	global $cnx;
	return $cnx->query($sql);
}

function verCompra($idmovimiento){
 $sql="SELECT
		movimiento.IdMovimiento AS idmov,
		movimiento.Moneda AS moneda,
		tipomovimiento.Descripcion AS tipomov,
		movimiento.Numero AS numero,
		DATE_FORMAT(movimiento.Fecha,'%d/%m/%Y') AS fecha,
		sucursal.apellidos AS sucursal,
		movimiento.IdSucursal AS idsucursal,
		movimiento.Comentario AS comentario,
		movimiento.IdMovimientoRef AS movref,
		movimiento.IdSucursalRef AS sucref,
		movimiento.Estado AS estado,
		movimiento.Total,
		persona.Nombres AS nomResponsable,
		persona.Apellidos AS apeResponsable,
		proveedor.Nombres AS nomProveedor,
		proveedor.Apellidos AS apeProveedor,
		movimiento.IdTipoDocumento AS tipoDoc,
		docum.Descripcion AS nomDoc,
		movimiento.SubTotal,
		movimiento.Igv,
		movimiento.FormaPago,
		movimiento.IdPersona,
		movimiento.IdResponsable
	FROM
		movimiento
		inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal
		Inner Join conceptopago ON conceptopago.IdConceptoPago = movimiento.IdConceptoPago
		Inner Join tipodocumento ON tipodocumento.IdTipoDocumento = movimiento.IdTipoDocumento
		Inner Join tipomovimiento ON tipomovimiento.IdTipoMovimiento = movimiento.IdTipoMovimiento AND '' = ''
		Inner Join persona ON movimiento.IdResponsable = persona.IdPersona
		Inner Join persona AS proveedor ON movimiento.IdPersona = proveedor.IdPersona
		Inner Join tipodocumento AS docum ON movimiento.IdTipoDocumento = docum.IdTipoDocumento
	WHERE
		movimiento.IdMovimiento =".$idmovimiento;

 	global $cnx;
	return $cnx->query($sql);
}

	function consultarDetalleCompra($idmov){
		$sql="SELECT
				movimiento.Moneda,
				detallemovalmacen.Cantidad,
				unidad.Descripcion AS unidad,
				unidad.IdUnidad,
				producto.Descripcion AS producto,
				producto.IdProducto,
				producto.Codigo,
				categoria.Abreviatura AS categoria,
				marca.Descripcion AS marca,
				producto.Codigo,
				producto.Peso,
				peso.Abreviatura AS unidadpeso,
				detallemovalmacen.PrecioCompra,
				detallemovalmacen.precioCompra*detallemovalmacen.cantidad AS subtotal,
				movimiento.Moneda,
				NOW() AS fechaactual,
				detallemovalmacen.PrecioVenta,
				listaunidad.PrecioVentaEspecial, producto.idcolor, color.nombre as color, color.codigo as codigocolor, producto.idtalla, talla.nombre as talla, talla.abreviatura as tallaabreviatura
			FROM
				detallemovalmacen
				Inner Join producto ON producto.IdProducto = detallemovalmacen.IdProducto
				Inner Join unidad ON detallemovalmacen.IdUnidad = unidad.IdUnidad
				Inner Join categoria ON producto.IdCategoria = categoria.IdCategoria
				Inner Join marca ON marca.IdMarca = producto.IdMarca
				Inner Join unidad AS peso ON producto.IdMedidaPeso = peso.IdUnidad
				Inner Join movimiento ON movimiento.IdMovimiento = detallemovalmacen.IdMovimiento
				Inner Join listaunidad ON detallemovalmacen.IdProducto = listaunidad.IdProducto AND detallemovalmacen.IdUnidad = 				listaunidad.IdUnidad LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla on talla.idtalla=producto.idtalla
			WHERE 
				detallemovalmacen.idmovimiento=".$idmov;
	 	global $cnx;
   		return $cnx->query($sql);  
	}

function consultar_cuotas($idmovimiento){
	$sql="SELECT
			cuota.IdCuota,
			cuota.Nombre,
			cuota.IdMovimiento,
			DATE_FORMAT(cuota.FechaCancelacion,'%Y/%m/%d') as FechaCancelacion,
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
			cuota.IdMovimiento=".$idmovimiento;
			
	global $cnx;
	return $cnx->query($sql);


}

function TotalAPagar_Cuota($idMov){
$sql="SELECT
			sum(Monto) as totalapagar,count(*) as cant
		FROM
			cuota
		WHERE
			cuota.IdMovimiento=".$idMov;
			
	global $cnx;
	return $cnx->query($sql);
}

function pagar_cuota($idmovimiento,$idcuota,$nombrecuota){
$sql="UPDATE cuota SET estado='C' WHERE idmovimiento=".$idmovimiento." AND idcuota=".$idcuota." AND Nombre='".$nombrecuota."'";
	global $cnx;
	return $cnx->query($sql);
}

function anular_cuotas($idmovimiento){
$sql="UPDATE cuota SET estado='A' WHERE idmovimiento=".$idmovimiento;
	global $cnx;
	return $cnx->query($sql);
}

function consultaralmacen($idmovimiento,$idsucursal,$numero, $idtipodocumento,$fechainicio,$fechafin,$estado){
 $sql="SELECT movimiento.idmovimiento as idmov,movimiento.moneda as moneda,movimiento.numero as numero, date_format(movimiento.fecha,'%Y/%m/%d') as fecha,persona.idpersona as idper, persona.nombres as persona,persona.apellidos as apersona,P.idpersona as idresp,P.nombres as responsable,P.apellidos as aresponsable,movimiento.total as total,movimiento.comentario as comentario,movimiento.estado as estado,movimiento.idsucursal as sucursal,movimiento.idtipodocumento as idtipodoc,tipodocumento.descripcion as tipodoc FROM movimiento inner join persona on persona.idpersona=movimiento.idpersona inner join tipodocumento on tipodocumento.idtipodocumento=movimiento.idtipodocumento inner join persona P on P.idpersona=movimiento.idresponsable WHERE movimiento.idtipomovimiento=4";
 if(isset($idmovimiento))
 	$sql = $sql . " AND movimiento.idmovimiento=".$idmovimiento;
 if(isset($idsucursal))
 	$sql = $sql . " AND movimiento.idsucursal=".$idsucursal;
 if(isset($numero))
 	$sql = $sql . " AND movimiento.numero LIKE '%".$numero."%'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
 if($idtipodocumento!='0')
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
if($estado!='0')
	$sql = $sql . " AND movimiento.estado='".$estado."'";
$sql.=" ORDER BY movimiento.fecha DESC,movimiento.idtipodocumento DESC,movimiento.numero ASC ";
	global $cnx;
	return $cnx->query($sql);
}




function insertarcuota($idcuota,$nombre,$idmovimiento,$fechacreacion,$fechacancelacion,$fechapago,$moneda,$monto,$interes,$montopagado,$interespagado,$estado){
$sql="INSERT INTO cuota(idcuota, nombre, idmovimiento, fechacreacion, fechacancelacion, fechapago,moneda, monto, interes, montopagado, interespagado, estado) VALUES(NULL,'".$nombre."','".$idmovimiento."','".$fechacreacion."','".$fechacancelacion."','".$fechapago."','".$moneda."','".$monto."','".$interes."','".$montopagado."','".$interespagado."','".$estado."')";
	global $cnx;
	return $cnx->query($sql);
}

function anular($idmovimiento){
$sql="UPDATE movimiento SET estado='A' WHERE idmovimiento=".$idmovimiento;
	global $cnx;
	return $cnx->query($sql);
}

function obtenerLastId($Numero,$idTipoMovimiento,$idTipoDocumento)
 {
   $sql = "SELECT IdMovimiento FROM movimiento WHERE Numero='".$Numero."' AND IdTipoMovimiento=".$idTipoMovimiento." AND IdTipoDocumento=".$idTipoDocumento ;	
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function consultaralmacen1($idmov){
 $sql="SELECT movimiento.idmovimiento as idmov,movimiento.moneda as moneda,movimiento.numero as numero, movimiento.fecha as fecha,persona.idpersona as idper,persona.nombres as persona,persona.apellidos as apellidosper,P.idpersona as idresp,P.apellidos as apellidosresp,P.nombres as responsable,movimiento.comentario as comentario,movimiento.idsucursal as sucursal,tipodocumento.idtipodocumento as tipodoc FROM movimiento inner join persona on persona.idpersona=movimiento.idpersona inner join tipodocumento on tipodocumento.idtipodocumento=movimiento.idtipodocumento inner join persona P on P.idpersona=movimiento.idresponsable inner join usuario on movimiento.idusuario=usuario.idusuario WHERE movimiento.idtipomovimiento=4 and movimiento.idmovimiento=".$idmov;
	global $cnx;
	return $cnx->query($sql);
}

function obtenerNumeroDoc($serie,$idtipomovimiento)
 {
   $sql = "SELECT max(numero) as numero FROM movimiento WHERE numero like '".$serie."%' and idtipomovimiento=".$idtipomovimiento;	
   global $cnx;
   $rst=$cnx->query($sql);  
   
   while($dato=$rst->fetchObject()){
		$numero=$dato->numero;
	}	
	
	if($numero==NULL){
	$num2='000001';
	}else{
	$num = substr($numero,4,6);
	$num1=$num+1;
	$cero='000000';
	$num2= substr($cero,0,6-strlen($num1)).$num1;
		
	}
	return $num2;
 }

function consultar_numero_sigue($idtipomovimiento,$idtipodocumento,$idsucursal){
$sql="select numero from movimiento where movimiento.idtipomovimiento='".$idtipomovimiento."' and movimiento.idtipodocumento='".$idtipodocumento."' and idsucursal='".$idsucursal."' and numero<>0 ORDER BY movimiento.idmovimiento DESC LIMIT 0,1";
	global $cnx;
		$registro=$cnx->query($sql);
	if($registro->rowCount()>0){
		$dato=$registro->fetchObject();
		$num= $dato->numero;
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
		$num=$serie.'-'.$num;
		}else{
		$num="001-000001";
		}
	return $num;
}

function consultarventa($idtipomovimiento,$idmovimiento,$idsucursal,$numero, $idtipodocumento,$fechainicio,$fechafin, $formapago, $conguia){
 $sql="SELECT  movimiento.idmovimientoref, mov.idmovimiento as idguia,mov.idtipodocumento as numguia, DATE_FORMAT(movimiento.fechainiciotraslado,'%d/%m/%Y') as fechatraslado, movimiento.idmotivotraslado, movimiento.idtransportista, movimiento.idlugardestino,movimiento.idlugarpartida, movimiento.idmovimiento,movimiento.idtipomovimiento,movimiento.idconceptopago,movimiento.numero ,movimiento.formapago, DATE_FORMAT(movimiento.fecha,'%d/%m/%Y') as fecha,movimiento.idsucursal, sucursal.apellidos as sucursal, movimiento.idpersona as idcliente,persona.nombres as cliente,persona.apellidos as acliente, movimiento.total, movimiento.igv,movimiento.subtotal, movimiento.idusuario,usuario.us as usuario, movimiento.idtipodocumento, movimiento.estado,tipodocumento.descripcion as documento, movimiento.idresponsable, resp.nombres as responsable, resp.apellidos as aresponsable, movimiento.moneda, movimiento.comentario, kardex.idmovimiento as kardex FROM movimiento inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal inner join persona on persona.idpersona=movimiento.idpersona inner join tipodocumento on movimiento.idtipodocumento=tipodocumento.idtipodocumento inner join usuario on usuario.idusuario=movimiento.idusuario inner join persona resp on resp.idpersona = movimiento.idresponsable left join kardex on movimiento.idmovimiento=kardex.idmovimiento left join movimiento mov on (movimiento.idmovimiento=mov.IdMovimientoref and mov.IdTipoMovimiento=2 and mov.Estado='N') WHERE movimiento.idtipomovimiento=".$idtipomovimiento;
 if(isset($idmovimiento))
 	$sql = $sql . " AND movimiento.idmovimiento=".$idmovimiento;
 if(isset($idsucursal))
 	$sql = $sql . " AND movimiento.idsucursal=".$idsucursal;
 if(isset($numero))
 	$sql = $sql . " AND movimiento.numero LIKE '%".$numero."%'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
 if(isset($idtipodocumento)){
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
 } 	else{
	$sql = $sql . " AND movimiento.idtipodocumento <> 5 ";}
	
if(isset($formapago))
	$sql = $sql . " AND movimiento.formapago='".$formapago."'";	
if(isset($conguia)){
	if($conguia=='2'){
	$sql = $sql . " AND (kardex.idmovimiento IS NOT NULL)";
	$sql = $sql. " AND movimiento.estado='N' ";
	}
	else{
	$sql = $sql . " AND kardex.idmovimiento IS NULL ";
	//$sql = $sql. " AND mov.idmovimiento IS NULL ";
	$sql = $sql. " AND movimiento.estado='N' ";
	}
}
$sql.=" GROUP BY movimiento.idmovimiento ORDER BY movimiento.idmovimiento DESC ";
	global $cnx;
	return $cnx->query($sql);
}

function consultarPedido($idtipomovimiento,$idmovimiento,$idsucursal,$numero, $idtipodocumento,$fechainicio,$fechafin, $formapago, $conguia){
 $sql="SELECT  movimiento.idmovimientoref, mov.idmovimiento as idguia,mov.idtipomovimiento as numguia, DATE_FORMAT(movimiento.fechainiciotraslado,'%d/%m/%Y') as fechatraslado, movimiento.idmotivotraslado, movimiento.idtransportista, movimiento.idlugardestino,movimiento.idlugarpartida, movimiento.idmovimiento,movimiento.idtipomovimiento,movimiento.idconceptopago,movimiento.numero ,movimiento.formapago, DATE_FORMAT(movimiento.fecha,'%d/%m/%Y') as fecha,movimiento.idsucursal, sucursal.apellidos as sucursal, movimiento.idpersona as idcliente,persona.nombres as cliente,persona.apellidos as acliente, movimiento.total, movimiento.igv,movimiento.subtotal, movimiento.idusuario,usuario.us as usuario, movimiento.idtipodocumento, movimiento.estado,tipodocumento.descripcion as documento, movimiento.idresponsable, resp.nombres as responsable, resp.apellidos as aresponsable, movimiento.moneda, movimiento.comentario FROM movimiento inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal inner join persona on persona.idpersona=movimiento.idpersona inner join tipodocumento on movimiento.idtipodocumento=tipodocumento.idtipodocumento inner join usuario on usuario.idusuario=movimiento.idusuario inner join persona resp on resp.idpersona = movimiento.idresponsable left join movimiento mov on (movimiento.idmovimiento=mov.IdMovimientoref and mov.IdTipoMovimiento=2 and mov.Estado='N') WHERE movimiento.idtipomovimiento=".$idtipomovimiento;
 if(isset($idmovimiento))
 	$sql = $sql . " AND movimiento.idmovimiento=".$idmovimiento;
 if(isset($idsucursal))
 	$sql = $sql . " AND movimiento.idsucursal=".$idsucursal;
 if(isset($numero))
 	$sql = $sql . " AND movimiento.numero LIKE '%".$numero."%'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
 if(isset($idtipodocumento)){
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
 } 	else{
	$sql = $sql . " AND movimiento.idtipodocumento <> 5 ";}
	
if(isset($formapago))
	$sql = $sql . " AND movimiento.formapago='".$formapago."'";	
if(isset($conguia)){
	if($conguia=='2'){
	$sql = $sql . " AND (mov.idmovimiento IS NOT NULL)";
	$sql = $sql. " AND movimiento.estado='N' ";
	}
	else{
	$sql = $sql. " AND mov.idmovimiento IS NULL ";
	$sql = $sql. " AND movimiento.estado='N' ";
	}
}
$sql.=" GROUP BY movimiento.idmovimiento ORDER BY movimiento.idmovimiento DESC ";
	global $cnx;
	return $cnx->query($sql);
}

function consultardetalleventa($idmovimiento){
 $sql="select movimiento.moneda, detallemovalmacen.cantidad,unidad.descripcion as unidad ,unidad.idunidad,producto.descripcion as producto, producto.idproducto, producto.codigo,categoria.abreviatura as categoria, marca.descripcion as marca,producto.codigo, producto.peso, peso.abreviatura as unidadpeso,detallemovalmacen.precioventa, detallemovalmacen.precioventa*detallemovalmacen.cantidad as subtotal,movimiento.moneda,NOW() as fechaactual, peso.idunidad as idunidadpeso, producto.idcolor, color.nombre as color, color.codigo as codigocolor, producto.idtalla, talla.nombre as talla, talla.abreviatura as tallaabreviatura from detallemovalmacen inner join producto on producto.idproducto=detallemovalmacen.idproducto inner join unidad on detallemovalmacen.idunidad = unidad.idunidad inner join categoria on producto.idcategoria=categoria.idcategoria inner join marca on marca.idmarca=producto.idmarca inner join unidad peso on producto.idmedidapeso=peso.idunidad inner join movimiento on movimiento.idmovimiento=detallemovalmacen.idmovimiento LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla on talla.idtalla=producto.idtalla where detallemovalmacen.idmovimiento='".$idmovimiento."';";
	global $cnx;
	return $cnx->query($sql);
}

function consultardetallepedido($idmovimiento){
 $sql="select movimiento.moneda, movimiento.numero, movimiento.idtipomovimiento, detallemovalmacen.cantidad,unidad.descripcion as unidad ,unidad.idunidad,producto.descripcion as producto, producto.idproducto, producto.codigo,categoria.abreviatura as categoria, marca.descripcion as marca,producto.codigo, producto.peso, peso.abreviatura as unidadpeso,detallemovalmacen.precioventa, detallemovalmacen.precioventa*detallemovalmacen.cantidad as subtotal,movimiento.moneda,NOW() as fechaactual, peso.idunidad as idunidadpeso, movimiento.idpersona, CONCAT(persona.apellidos,' ',persona.nombres) as cliente,movimiento.idsucursal, sucursal.apellidos as sucursal, producto.idcolor, color.nombre as color, color.codigo as codigocolor, producto.idtalla, talla.nombre as talla, talla.abreviatura as tallaabreviatura from detallemovalmacen inner join producto on producto.idproducto=detallemovalmacen.idproducto inner join unidad on detallemovalmacen.idunidad = unidad.idunidad inner join categoria on producto.idcategoria=categoria.idcategoria inner join marca on marca.idmarca=producto.idmarca inner join unidad peso on producto.idmedidapeso=peso.idunidad inner join movimiento on movimiento.idmovimiento=detallemovalmacen.idmovimiento Inner Join persona ON movimiento.IdPersona = persona.IdPersona inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla on talla.idtalla=producto.idtalla where detallemovalmacen.idmovimiento='".$idmovimiento."';";
	global $cnx;
	return $cnx->query($sql);
}

function consultarcuotaventa($idmovimiento){
 $sql="select cuota.idcuota, cuota.nombre, cuota.fechacreacion,cuota.fechacancelacion,cuota.fechapago,cuota.moneda,cuota.monto,cuota.interes,cuota.interespagado,cuota.montopagado, cuota.estado from cuota where cuota.IdMovimiento='".$idmovimiento."';";
	global $cnx;
	return $cnx->query($sql);
}

function obtenerLastIdVenta($Numero,$idTipoMovimiento,$tipodocumento,$idsucursal)
 {
   $sql = "SELECT IdMovimiento FROM movimiento WHERE Numero='".$Numero."' AND IdTipoMovimiento=".$idTipoMovimiento." AND idtipodocumento='".$tipodocumento."' AND idsucursal='".$idsucursal."' ORDER BY idmovimiento DESC";;	
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function generarcuotas($numcuota,$fecha,$total,$inicial,$interes){

	$cuota=($total-$inicial)/$numcuota;
	$cuota=number_format($cuota,2);

	$day=substr(trim($fecha),8,2);
	$mes=substr(trim($fecha),5,2);
	$year=substr(trim($fecha),0,4);
	
	for($i=1;$i<=$numcuota;$i++){

	$mes=$mes+1;
	if($mes==13){$mes=1;$year=$year+1;}
	if($day>=29){
		if($mes==2){
			if(($year%4) > 0){$day=28;}
		}elseif(($mes%2)==1 && $mes!=7){
			if($day>30){$day=30;}
		}	
	}
	$cero='00';
	$day=substr($cero,0,2-strlen($day)).$day;
	$mes=substr($cero,0,2-strlen($mes)).$mes;
	$fecha=$year.'-'.$mes.'-'.$day;
	
	$cuotasventa[($i)]=array('numero'=>($i),'subtotal'=>$cuota,'interes'=>$interes,'fecha'=>$fecha);
	}
	return $cuotasventa;
	}

function modificarcuota($idcuota,$nombre,$fechacancelacion,$fechapago,$moneda,$monto,$interes,$montopagado,$interespagado,$estado){
$sql.="UPDATE cuota SET nombre='".$nombre."', fechacancelacion='".$fechacancelacion."' , fechapago='".$fechapago."', moneda='".$moneda."', monto='".$monto."', interes='".$interes."', montopagado='".$montopagado."', interespagado='".$interespagado."' ";
if(isset($estado) && $estado!=""){
$sql.=", estado='".$estado."'";
}
$sql.=" WHERE idcuota=".$idcuota;
	global $cnx;
	return $cnx->query($sql);
}

function modificarCuotaCompra($idcuota,$nombre,$fechacancelacion,$monto,$interes){
$sql="UPDATE cuota SET Nombre='".$nombre."', FechaCancelacion='".$fechacancelacion."' , Monto=".$monto.", interes=".$interes." WHERE IdCuota=".$idcuota;
	global $cnx;
	return $cnx->query($sql);
}

 function num_serie($idtipomovimiento,$idtipodocumento,$idsucursal){
 $serie="";
 $sql="SELECT max(numero) as numero FROM movimiento WHERE  idtipomovimiento=".$idtipomovimiento." AND idtipodocumento=".$idtipodocumento." AND idSucursal=".$idsucursal." AND idMovimiento=(select MAX(idMovimiento) from movimiento WHERE idtipomovimiento=".$idtipomovimiento." AND idtipodocumento=".$idtipodocumento." AND idsucursal=".$idsucursal.")";
 	global $cnx;
 	$registro=$cnx->query($sql);
 	if($registro->rowCount()>0){
	$dato=$registro->fetchObject();
		$num= $dato->numero;
		$s=substr($num,0,3);
		if(substr($num,4,6)=='999999'){
		$s=$s+1;
		}
		$serie=$s;
		
	}else{
	$serie="001";
	}
	
	return $serie;
 }
 
function obtenerLastIdCompra($idTipoMovimiento,$Numero,$idTipoDocumento,$fecha,$idproveedor)
 {
   $sql = "SELECT IdMovimiento FROM movimiento WHERE Numero='".$Numero."' AND IdTipoDocumento= ".$idTipoDocumento ." AND IdTipoMovimiento=".$idTipoMovimiento." AND fecha='".$fecha."' AND IdPersona=".$idproveedor ;	
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function consultarExistenciaNumeroCompra($idSucursal,$idProveedor,$Fecha,$Numero,$idTipoMovimiento,$idTipoDocumento)
 {
   $sql = "SELECT COUNT(*) as cant FROM movimiento WHERE Estado<>'A' AND Numero='".$Numero."' AND IdSucursal=".$idSucursal." AND IdPersona=".$idProveedor." AND Fecha='".$Fecha."' AND IdTipoDocumento= ".$idTipoDocumento ." AND IdTipoMovimiento=".$idTipoMovimiento ;	
   global $cnx;
   return $cnx->query($sql);  	 	
 }
  
function comprobarCierreCaja($IdConceptoPago,$IdSucursal,$Fecha){
 	$sql="SELECT COUNT(*) as resultado FROM movimiento WHERE IdSucursal=".$IdSucursal." AND IdConceptoPago=".$IdConceptoPago." AND Fecha='".$Fecha."'  AND IdTipoMovimiento=3";
	
	global $cnx;
   return $cnx->query($sql); 
 
 }

function verificarcierrecaja($fecha,$idsucursal){
$sql="SELECT idconceptopago FROM movimiento where IdTipoMovimiento=3 and (IdConceptoPago=2 or IdConceptoPago=1)  and DATE_FORMAT(movimiento.fecha,'%Y-%m-%d')= '".$fecha."' AND idsucursal=".$idsucursal." ORDER BY IdMovimiento DESC LIMIT 0,1";
global $cnx;
$datos=$cnx->query($sql);
$x=$datos->rowCount();
if($x>0){
$dato=$datos->fetchObject();
$valor=$dato->idconceptopago;
}else{
$valor=3;
}
return $valor;
}

 function obtenerLastIdCaja($Numero,$comentario,$fecha,$idsucursal)
 {
   $sql = "SELECT IdMovimiento FROM movimiento WHERE Numero='".$Numero."' AND Comentario= '".$comentario ."' AND IdTipoMovimiento=3 AND fecha='".$fecha."' AND IdSucursal=".$idsucursal ;	
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
 function comprobarAperturaCaja($IdConceptoPago,$IdSucursal,$Fecha){
 	$sql="SELECT COUNT(*) as resultado FROM movimiento WHERE IdSucursal=".$IdSucursal." AND IdConceptoPago=".$IdConceptoPago." AND Fecha='".$Fecha."'  AND IdTipoMovimiento=3";
	
	global $cnx;
   return $cnx->query($sql); 
 
 }
 
function anularcuota($idcuota){
$sql="UPDATE cuota set cuota.Estado='A' WHERE cuota.idcuota=".$idcuota;
global $cnx;
return $cnx->query($sql); 
}

function consultarventaanulada($idtipomovimiento,$numero, $idtipodocumento,$fechainicio, $formapago){
 $sql="SELECT movimiento.idmovimiento,movimiento.idtipomovimiento, movimiento.idconceptopago,movimiento.numero,movimiento.formapago, DATE_FORMAT(movimiento.fecha,'%d/%m/%Y') as fecha,movimiento.idsucursal, sucursal.apellidos as sucursal, movimiento.idpersona as idcliente,persona.nombres as cliente,persona.apellidos as acliente, movimiento.total, movimiento.igv,movimiento.subtotal, movimiento.idusuario,usuario.us as usuario, movimiento.idtipodocumento, movimiento.estado,tipodocumento.descripcion as documento, movimiento.idresponsable, resp.nombres as responsable, resp.apellidos as aresponsable, movimiento.moneda, movimiento.comentario FROM movimiento inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal inner join persona on persona.idpersona=movimiento.idpersona inner join tipodocumento on movimiento.idtipodocumento=tipodocumento.idtipodocumento inner join usuario on usuario.idusuario=movimiento.idusuario inner join persona resp on resp.idpersona = movimiento.idresponsable WHERE movimiento.idtipomovimiento=".$idtipomovimiento;
 if(isset($numero))
 	$sql = $sql . " AND movimiento.numero LIKE '%".$numero."%'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
if(isset($idtipodocumento))
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
if(isset($formapago))
	$sql = $sql . " AND movimiento.formapago='".$formapago."'";	

$sql.=" AND movimiento.estado='A' ORDER BY movimiento.idmovimiento DESC";
	global $cnx;
	return $cnx->query($sql);
}

function consultarrpt($idtipomovimiento,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda){
$sql="SELECT sum(movimiento.total) as totalcli,movimiento.formapago, DATE_FORMAT(MAX(movimiento.fecha),'%d/%m/%Y') as fecha,persona.nombres as cliente,persona.apellidos as acliente,persona.tipopersona,persona.nrodoc,  movimiento.estado,movimiento.moneda,tipodocumento.descripcion as documento,sector.descripcion as sector, zona.descripcion as zona FROM movimiento inner join persona on persona.idpersona=movimiento.idpersona inner join sector on persona.idsector = sector.idsector inner join tipodocumento on movimiento.idtipodocumento=tipodocumento.idtipodocumento inner join zona on zona.idzona=persona.idzona WHERE movimiento.estado='N'";
 if(isset($idtipomovimiento)&& $idtipomovimiento!=0)
 	$sql = $sql . " AND movimiento.idtipomovimiento=".$idtipomovimiento;
 if(isset($idzona)&& $idzona!=0)
 	$sql = $sql . " AND persona.idzona=".$idzona;
 if(isset($idsector) && $idsector!=0)
 	$sql = $sql . " AND persona.idsector=".$idsector;
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
 if(isset($idtipodocumento)&& $idtipodocumento!=0)
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
if(isset($formapago) && $formapago!="" && $formapago!="0")
	$sql = $sql . " AND movimiento.formapago='".$formapago."'";	
if(isset($moneda))
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";		

$sql.=" GROUP BY persona.nrodoc ORDER BY totalcli DESC";
	global $cnx;
	return $cnx->query($sql) ;
}

function consultarcuotaventaNoAnulada($idmovimiento){
 $sql="select cuota.idcuota, cuota.nombre, cuota.fechacreacion,cuota.fechacancelacion,cuota.fechapago,cuota.moneda,cuota.monto,cuota.interes,cuota.interespagado,cuota.montopagado, cuota.estado from cuota where cuota.IdMovimiento='".$idmovimiento."' AND estado<>'A'";
	global $cnx;
	return $cnx->query($sql);
}

function consultarreporte($fechainicio,$fechafin,$moneda,$agrupado){
$sql="SELECT TT.ano, TT.mes,TT.moneda,SUM(TT.totalAB) AS totalAB, SUM(TT.totalBB) AS totalBB,SUM(TT.totalAF) AS totalAF, SUM(TT.totalBF) AS totalBF FROM (
SELECT T.ano, T.mes,T.moneda,SUM(T.totalAB) AS totalAB, SUM(T.totalBB) AS totalBB, 0 as totalAF, 0 as totalBF FROM (
SELECT year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda,sum(movimiento.total) as totalAB, 0 AS totalBB FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=1 AND movimiento.formapago = 'A' AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'"; 
$sql = $sql . " GROUP BY formapago,idtipodocumento,".$agrupado." asc
UNION
SELECT year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda, 0 as totalAB,sum(movimiento.total) as totalBB FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=1 AND movimiento.formapago = 'B'  AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";

$sql = $sql . " GROUP BY formapago,idtipodocumento,".$agrupado." asc
) T GROUP BY T.".$agrupado." asc
UNION
SELECT T.ano, T.mes,T.moneda, 0 as totalAB, 0 as totalBB,SUM(T.totalAF) AS totalAF, SUM(T.totalBF) AS totalBF FROM (
SELECT year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda, 0 as totalAB, 0 as totalBB,sum(movimiento.total) as totalAF, 0 AS totalBF FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=2 AND movimiento.formapago = 'A'  AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
$sql = $sql . " GROUP BY formapago,idtipodocumento,".$agrupado." asc
UNION
SELECT  year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda, 0 as totalAB, 0 as totalBB, 0 as totalAF,sum(movimiento.total) as totalBF FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=2 AND movimiento.formapago = 'B'  AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
$sql = $sql . "GROUP BY formapago,idtipodocumento,".$agrupado." asc
) T GROUP BY T.".$agrupado." asc
) TT GROUP BY TT.".$agrupado." asc";
	global $cnx;
	return $cnx->query($sql);
}

function consultarreportecompra($fechainicio,$fechafin,$moneda,$agrupado){
$sql="SELECT TT.ano, TT.mes,TT.moneda,SUM(TT.totalAB) AS totalAB, SUM(TT.totalBB) AS totalBB,SUM(TT.totalAF) AS totalAF, SUM(TT.totalBF) AS totalBF FROM (
SELECT T.ano, T.mes,T.moneda,SUM(T.totalAB) AS totalAB, SUM(T.totalBB) AS totalBB, 0 as totalAF, 0 as totalBF FROM (
SELECT year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda,sum(movimiento.total) as totalAB, 0 AS totalBB FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=3 AND movimiento.formapago = 'A' AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'"; 
$sql = $sql . " GROUP BY formapago,idtipodocumento,".$agrupado." asc
UNION
SELECT year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda, 0 as totalAB,sum(movimiento.total) as totalBB FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=3 AND movimiento.formapago = 'B'  AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";

$sql = $sql . " GROUP BY formapago,idtipodocumento,".$agrupado." asc
) T GROUP BY T.".$agrupado." asc
UNION
SELECT T.ano, T.mes,T.moneda, 0 as totalAB, 0 as totalBB,SUM(T.totalAF) AS totalAF, SUM(T.totalBF) AS totalBF FROM (
SELECT year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda, 0 as totalAB, 0 as totalBB,sum(movimiento.total) as totalAF, 0 AS totalBF FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=4 AND movimiento.formapago = 'A'  AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
$sql = $sql . " GROUP BY formapago,idtipodocumento,".$agrupado." asc
UNION
SELECT  year(movimiento.fecha) as ano, month(movimiento.fecha) as mes,movimiento.moneda, 0 as totalAB, 0 as totalBB, 0 as totalAF,sum(movimiento.total) as totalBF FROM movimiento WHERE movimiento.estado='N' AND movimiento.idtipodocumento=4 AND movimiento.formapago = 'B'  AND movimiento.moneda='".$moneda."'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
$sql = $sql . "GROUP BY formapago,idtipodocumento,".$agrupado." asc
) T GROUP BY T.".$agrupado." asc
) TT GROUP BY TT.".$agrupado." asc";
	global $cnx;
	return $cnx->query($sql);
}

function consultarmotivotraslado($id){
$sql="SELECT idmotivotraslado as idmotivo, descripcion as motivo FROM motivotraslado WHERE 1=1 ";
if(isset($id)){
$sql.=" AND idmotivotraslado=".$id;
}

global $cnx;
return $cnx->query($sql);
}

function insertarguia($idmovimiento,$idtipomovimiento, $idsucursal, $idconceptopago, $numero, $idtipodocumento, $formapago,$fecha,$moneda,$subtotal,$igv,$total,$idusuario,$idpersona,$idresponsable,$idmovimientoref,$idsucursalref,$comentario,$estado, $fechainiciotraslado, $idmotivotraslado, $idtransportista, $idlugarpartida, $idlugardestino)
 {
   $sql = "INSERT INTO movimiento(idmovimiento, idsucursal, idtipomovimiento, idconceptopago, numero, idtipodocumento, formapago, fecha, moneda, subtotal, igv, total, idusuario, idpersona, idresponsable, idmovimientoref, idsucursalref, comentario, estado, fechainiciotraslado, idmotivotraslado, idtransportista, idlugarpartida, idlugardestino) VALUES(NULL,'" . $idsucursal . "',".$idtipomovimiento.",'". $idconceptopago ."','". $numero . "','".$idtipodocumento."','".$formapago."','".$fecha."','".$moneda."','".$subtotal."','".$igv."','".$total."','".$idusuario."','".$idpersona."','".$idresponsable."','".$idmovimientoref."','".$idsucursalref."','".$comentario."','".$estado."','".$fechainiciotraslado."','".$idmotivotraslado."','".$idtransportista."','".$idlugarpartida."','".$idlugardestino."')";
      
   global $cnx;
   return $cnx->query($sql) or die($sql);
       	 	 	
 }
 
function cuotaporestado($idmovimiento, $estado){
 $sql="select count(*) from cuota where 1=1 ";
 
 if(isset($idmovimiento))
 	$sql.=" AND cuota.IdMovimiento='".$idmovimiento."'";
 if(isset($estado))	
	 $sql.="AND cuota.estado='".$estado."'";
	
	global $cnx;
	return $cnx->query($sql);
   }
   
function consultarcajaventa($idmovimientoref){
 	$sql="Select idmovimiento, total, subtotal, igv from movimiento where idtipomovimiento=3 and idtipodocumento=10 and estado='N' and idmovimientoref=".$idmovimientoref;
 	global $cnx;
	return $cnx->query($sql);
 }

function consultarPermisoAnular($id,$sucursal){
$sql="(SELECT
			movimiento.IdMovimiento
		FROM
			movimiento AS movimientoUno
		Inner Join movimiento ON movimientoUno.IdMovimiento = movimiento.IdMovimientoRef AND movimientoUno.IdSucursal = 			movimiento.IdSucursal
		WHERE
			movimientoUno.FormaPago =  'A' AND
			movimiento.IdMovimientoRef = ".$id." AND
			movimientoUno.IdSucursal =  ".$sucursal. " AND movimiento.Estado<>'A'


	) UNION(

	SELECT cuota.IdCuota
		FROM cuota Inner Join movimiento ON cuota.IdMovimiento=movimiento.IdMovimiento WHERE cuota.Estado='C' AND movimiento.IdMovimiento= ".$id." 

)";

global $cnx;
return $cnx->query($sql);
}


function consultarMovimientoxRef($IdMovRef,$comentario){

$sql="SELECT * FROM movimiento WHERE IdMovimientoRef=".$IdMovRef." AND comentario LIKE '%".$comentario."%'";

global $cnx;
return $cnx->query($sql);
}

function consultarPedidoSucursal($idtipomovimiento,$idmovimiento,$idsucursal,$numero, $idtipodocumento,$fechainicio,$fechafin, $formapago, $conguia){
 $sql="SELECT  movimiento.idmovimientoref, mov.idmovimiento as idguia,mov.idtipomovimiento as numguia, DATE_FORMAT(movimiento.fechainiciotraslado,'%d/%m/%Y') as fechatraslado, movimiento.idmotivotraslado, movimiento.idtransportista, movimiento.idlugardestino,movimiento.idlugarpartida, movimiento.idmovimiento,movimiento.idtipomovimiento,movimiento.idconceptopago,movimiento.numero ,movimiento.formapago, DATE_FORMAT(movimiento.fecha,'%d/%m/%Y') as fecha,movimiento.idsucursal, sucursal.apellidos as sucursal, movimiento.idpersona as idcliente,persona.nombres as cliente,persona.apellidos as acliente, movimiento.total, movimiento.igv,movimiento.subtotal, movimiento.idusuario,usuario.us as usuario, movimiento.idtipodocumento, movimiento.estado,tipodocumento.descripcion as documento, movimiento.idresponsable, resp.nombres as responsable, resp.apellidos as aresponsable, movimiento.moneda, movimiento.comentario FROM movimiento inner join persona sucursal on sucursal.idpersona=movimiento.idsucursal inner join persona on persona.idpersona=movimiento.idpersona inner join rolpersona rp on persona.idpersona=rp.idpersona and idrol=5 inner join tipodocumento on movimiento.idtipodocumento=tipodocumento.idtipodocumento inner join usuario on usuario.idusuario=movimiento.idusuario inner join persona resp on resp.idpersona = movimiento.idresponsable left join movimiento mov on (movimiento.idmovimiento=mov.IdMovimientoref and mov.IdTipoMovimiento=5 and mov.Estado='N') WHERE movimiento.idtipomovimiento=".$idtipomovimiento;
 if(isset($idmovimiento))
 	$sql = $sql . " AND movimiento.idmovimiento=".$idmovimiento;
 if(isset($idsucursal))
 	$sql = $sql . " AND (movimiento.idsucursal=".$idsucursal." or movimiento.idpersona=".$idsucursal.")";
 if(isset($numero))
 	$sql = $sql . " AND movimiento.numero LIKE '%".$numero."%'";
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
 if(isset($idtipodocumento)){
	$sql = $sql . " AND movimiento.idtipodocumento=".$idtipodocumento;
 } 	else{
	$sql = $sql . " AND movimiento.idtipodocumento <> 5 ";}
	
if(isset($formapago))
	$sql = $sql . " AND movimiento.formapago='".$formapago."'";	
if(isset($conguia)){
	if($conguia=='2'){
	$sql = $sql . " AND (mov.idmovimiento IS NOT NULL or movimiento.idtipodocumento=15)";
	$sql = $sql. " AND movimiento.estado='N' ";
	}
	else{
	$sql = $sql. " AND (mov.idmovimiento IS NULL and movimiento.idtipodocumento<>15)";
	$sql = $sql. " AND movimiento.estado='N' ";
	}
}
$sql.=" GROUP BY movimiento.idmovimiento ORDER BY movimiento.idmovimiento DESC ";
	global $cnx;
	return $cnx->query($sql);
}

} 
?>