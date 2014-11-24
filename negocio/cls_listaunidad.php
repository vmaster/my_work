<?php
class clsListaUnidad
{
function insertar($idlistaunidad, $idproducto, $idunidad, $idunidadbase, $formula, $preciocompra, $precioventa,$precioespecial, $moneda)
 {
   $sql = "INSERT INTO listaunidad(idlistaunidad, idproducto, idunidad, idunidadbase, formula, preciocompra, precioventa,precioventaespecial, moneda) VALUES(NULL, " . $idproducto . ", " . $idunidad . ", ". $idunidadbase .", ". $formula . ", " . $preciocompra . ", " . $precioventa .",".$precioespecial. ", '" . $moneda . "')";
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function actualizar($idlistaunidad, $idproducto, $idunidad, $idunidadbase, $formula, $preciocompra, $precioventa, $precioespecial,$moneda)
 {
   if(isset($idunidad)){
   $sql = "UPDATE listaunidad SET idproducto = " . $idproducto .", idunidad = " . $idunidad .", idunidadbase = " . $idunidadbase . ", formula= " . $formula . ", preciocompra= " . $preciocompra . ", precioventa=" . $precioventa .", precioventaespecial=".$precioespecial. ", moneda= '" . $moneda . "' WHERE idlistaunidad = " . $idlistaunidad ;
   }else{
     $sql = "UPDATE listaunidad SET preciocompra= " . $preciocompra . ", precioventa=" . $precioventa .", precioventaespecial=".$precioespecial. ", moneda= '" . $moneda . "' WHERE idlistaunidad = " . $idlistaunidad ;
   }
      
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function eliminar($idlistaunidad)
 {
   $sql = "DELETE FROM listaunidad WHERE idlistaunidad = " . $idlistaunidad;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar()
 {
   $sql = "SELECT listaunidad.idlistaunidad, listaunidad.idproducto, producto.descripcion, unidad.descripcion as unidad, unidad.idunidad as idunidad, unidadbase.descripcion as unidadbase, unidadbase.idunidad as idunidadbase, listaunidad.formula, listaunidad.preciocompra, listaunidad.precioventa, listaunidad.moneda, listaunidad.precioventaespecial from listaunidad inner join producto on producto.idproducto=listaunidad.idproducto inner join unidad on unidad.IdUnidad= listaunidad.idunidad inner join (unidad as unidadbase) on listaunidad.idunidadbase=unidadbase.idunidad order by listaunidad.idlistaunidad";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idlistaunidad, $idproducto, $descripcion)
 {
   $sql = "SELECT listaunidad.idlistaunidad, listaunidad.idproducto, producto.descripcion as producto, unidad.descripcion as unidad, unidad.idunidad as idunidad, unidadbase.descripcion as unidadbase, unidadbase.idunidad as idunidadbase, listaunidad.formula, listaunidad.preciocompra, listaunidad.precioventa, listaunidad.moneda, listaunidad.precioventaespecial from listaunidad inner join producto on producto.idproducto=listaunidad.idproducto inner join unidad on unidad.IdUnidad= listaunidad.idunidad inner join (unidad as unidadbase) on listaunidad.idunidadbase=unidadbase.idunidad  WHERE 1=1";
   if(isset($idlistaunidad) && $idlistaunidad!=""){
	$sql = $sql . " AND listaunidad.idlistaunidad = " . $idlistaunidad;}
   if(isset($idproducto) && $idproducto!=""){
	$sql = $sql . " AND listaunidad.idproducto = " . $idproducto;}
   if(isset($descripcion)){
	$sql = $sql . " AND producto.descripcion LIKE '" . $descripcion . "%'";}
   		
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function buscarconxajax($idsucursal, $idproducto, $moneda, $tipocambio)
 {
   $sql = "SELECT listaunidad.idlistaunidad, listaunidad.idproducto, producto.descripcion as producto, unidad.descripcion as unidad, unidadbase.descripcion as unidadbase, listaunidad.formula, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN listaunidad.precioventa ELSE listaunidad.precioventa/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN listaunidad.precioventa ELSE listaunidad.precioventa*".$tipocambio." END END,2) as precioventa,ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN listaunidad.preciocompra ELSE listaunidad.preciocompra/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN listaunidad.preciocompra ELSE listaunidad.preciocompra*".$tipocambio." END END,2) as preciocompra,ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN listaunidad.precioventaespecial ELSE listaunidad.precioventaespecial/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN listaunidad.precioventaespecial ELSE listaunidad.precioventaespecial*".$tipocambio." END END,2) as precioventaespecial,listaunidad.precioventaespecial as pespecial,listaunidad.moneda, unidad.idunidad, producto.idunidadbase, obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") as StockActual FROM listaunidad inner join producto on producto.idproducto=listaunidad.idproducto inner join unidad on unidad.idunidad= listaunidad.idunidad inner join (unidad as unidadbase) on listaunidad.idunidadbase=unidadbase.idunidad WHERE 1=1";
   if(isset($idproducto) && $idproducto!=""){
	$sql = $sql . " AND listaunidad.idproducto = " . $idproducto;}
   		
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
  function buscarparaventaconxajax($idsucursal, $idproducto, $moneda, $tipocambio)
 {
   $sql = "SELECT listaunidad.idlistaunidad, listaunidad.idproducto, producto.descripcion as producto, unidad.descripcion as unidad, unidadbase.descripcion as unidadbase, listaunidad.formula, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN listaunidad.preciocompra ELSE listaunidad.preciocompra/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN listaunidad.preciocompra ELSE listaunidad.preciocompra*".$tipocambio." END END,2) as preciocompra, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN listaunidad.precioventa ELSE listaunidad.precioventa/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN listaunidad.precioventa ELSE listaunidad.precioventa*".$tipocambio." END END,2) as precioventa,ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN listaunidad.precioventaespecial ELSE listaunidad.precioventaespecial/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN listaunidad.precioventaespecial ELSE listaunidad.precioventaespecial*".$tipocambio." END END,2) as precioventaespecial, listaunidad.moneda, unidad.idunidad, producto.idunidadbase, obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") as StockActual FROM listaunidad inner join producto on producto.idproducto=listaunidad.idproducto inner join unidad on unidad.idunidad= listaunidad.idunidad inner join (unidad as unidadbase) on listaunidad.idunidadbase=unidadbase.idunidad WHERE 1=1 ";
   if(isset($idproducto) && $idproducto!=""){
	$sql = $sql . " AND listaunidad.idproducto = " . $idproducto;}
   		
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
  function buscarid($idlistaunidad,$unidad, $idproducto, $descripcion)
 {
   $sql = "SELECT listaunidad.idlistaunidad, listaunidad.idproducto, producto.descripcion as producto, unidad.descripcion as unidad, unidad.idunidad as idunidad, unidadbase.descripcion as unidadbase, unidadbase.idunidad as idunidadbase, listaunidad.formula, listaunidad.preciocompra, listaunidad.precioventa, listaunidad.moneda from listaunidad inner join producto on producto.idproducto=listaunidad.idproducto inner join unidad on unidad.IdUnidad= listaunidad.idunidad inner join (unidad as unidadbase) on listaunidad.idunidadbase=unidadbase.idunidad  WHERE 1=1";
   if(isset($idlistaunidad) && $idlistaunidad!=""){
	$sql = $sql . " AND listaunidad.idlistaunidad = " . $idlistaunidad;}
   if(isset($idproducto) && $idproducto!=""){
	$sql = $sql . " AND listaunidad.idproducto = " . $idproducto;}
   if(isset($descripcion)){
	$sql = $sql . " AND producto.descripcion LIKE '" . $descripcion . "%'";}
  if(isset($unidad)){
	$sql = $sql . " AND unidad.descripcion LIKE '" . $unidad . "%'";}
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
 function buscarprecioventa($idlistaunidad,$idunidad, $idproducto, $descripcion)
 {
   $sql = "SELECT unidad.descripcion as unidad, unidad.idunidad as idunidad, unidadbase.descripcion as unidadbase, unidadbase.idunidad as idunidadbase, listaunidad.formula, listaunidad.preciocompra, listaunidad.precioventa, listaunidad.precioventaespecial, listaunidad.moneda from listaunidad inner join producto on producto.idproducto=listaunidad.idproducto inner join unidad on unidad.IdUnidad= listaunidad.idunidad inner join (unidad as unidadbase) on listaunidad.idunidadbase=unidadbase.idunidad  WHERE 1=1";
   if(isset($idlistaunidad) && $idlistaunidad!=""){
	$sql = $sql . " AND listaunidad.idlistaunidad = " . $idlistaunidad;}
   if(isset($idproducto) && $idproducto!=""){
	$sql = $sql . " AND listaunidad.idproducto = " . $idproducto;}
   if(isset($descripcion)){
	$sql = $sql . " AND producto.descripcion LIKE '" . $descripcion . "%'";}
  if(isset($idunidad)){
	$sql = $sql . " AND unidad.idunidad =" . $idunidad . "";}
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
 function consultarNOAsignadas($idproducto)
 {
   $sql = "SELECT idunidad, descripcion, abreviatura, tipo, estado FROM unidad where estado='N' and idunidad not in (select idunidad from listaunidad where idproducto=".$idproducto.")";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
} 
?>