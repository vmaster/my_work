<?php
class clsKardex
{
function consultar($idpro){
	$sql="SELECT producto.codigo as codigo, producto.descripcion as pro, tipomovimiento.descripcion as movimiento, kardex.saldoanteriorbase as stockanterior ,kardex.estado as estado,kardex.cantidadbase as cantidad, movimiento.numero as doc,kardex.saldoactual as stockactual ,tipodocumento.abreviatura as tipodoc  FROM kardex inner join movimiento on kardex.idmovimiento= movimiento.idmovimiento inner join producto on kardex.idproducto=producto.idproducto inner join tipomovimiento on tipomovimiento.idtipomovimiento=movimiento.idtipomovimiento inner join tipodocumento on tipodocumento.idtipodocumento=movimiento.idtipodocumento WHERE producto.idproducto=".$idpro;
	global $cnx;
	return $cnx->query($sql);  
}

function consultarkardex($idpro,$fechainicio,$fechafin,$moneda,$categoria){
	$sql="SELECT producto.codigo as codpro,producto.descripcion as pro, DATE_FORMAT(movimiento.fecha,'%d/%m/%Y') as fecha,movimiento.idtipomovimiento,movimiento.numero,tipomovimiento.descripcion as mov, kardex.saldoanteriorbase as stockanterior,kardex.cantidadbase as cantidad, kardex.saldoactual as stockactual,kardex.saldoactual,detallemovalmacen.preciocompra,detallemovalmacen.precioventa,movimiento.estado,tipodocumento.abreviatura as tipodoc,
movimiento.idtipodocumento,kardex.estado,producto.codigo,producto.descripcion as producto FROM kardex inner join movimiento on kardex.idmovimiento= movimiento.idmovimiento inner join producto on kardex.idproducto=producto.idproducto inner join tipomovimiento on tipomovimiento.idtipomovimiento=movimiento.idtipomovimiento inner join tipodocumento on tipodocumento.idtipodocumento=movimiento.idtipodocumento inner join detallemovalmacen on detallemovalmacen.idmovimiento=movimiento.idmovimiento and detallemovalmacen.idproducto=producto.idproducto  WHERE 1=1 ";
if(isset($idpro) && $idpro!=0)	
	$sql = $sql . " AND producto.idproducto=".$idpro;
if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
if(isset($moneda))
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";
if($categoria!="" && isset($categoria)){
	$sql = $sql . " AND producto.idcategoria in (select IdCategoria from categoria tp 
where (tp.IdCategoria = ".$categoria.") OR (tp.IdCategoria = tp.IdCategoria - ".$categoria.")
or (tp.IdCategoriaRef = ".$categoria.") OR (tp.IdCategoriaRef = tp.IdCategoriaRef - ".$categoria."))";}
$sql = $sql . "order by 1,2, movimiento.fecha asc";
	global $cnx;
	return $cnx->query($sql);  
}
}
?>