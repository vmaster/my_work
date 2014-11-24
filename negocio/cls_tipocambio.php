<?php
class clsTipoCambio
{
function insertar($idTipoCambio, $fecha, $cambio)
 {
   $sql = "INSERT INTO tipocambio(IdTipocambio, Fecha, Cambio) VALUES(NULL," . $fecha . "," . $cambio . ")";
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function actualizar($idTipoCambio, $fecha, $cambio)
 {
   $sql = "UPDATE tipocambio SET Fecha=" . $fecha .", Cambio=" . $cambio ." WHERE IdTipocambio = " . $idTipoCambio ;
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function eliminar($idTipoCambio)
 {
   $sql = "DELETE FROM tipocambio WHERE IdTipocambio = " . $idTipoCambio;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar()
 {
   $sql = "SELECT IdTipocambio, Fecha, Cambio FROM tipocambio";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idTipoCambio, $fecha)
 {
   $sql = "SELECT * FROM tipocambio WHERE 1=1";
   if(isset($idTipoCambio))
	$sql = $sql . " AND (IdTipocambio=".$idTipoCambio." OR IdTipocambio=IdTipocambio-".$idTipoCambio.")";
   if(isset($fecha))
	$sql = $sql . " AND Fecha LIKE '" . $fecha . "%'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function buscarxfecha($fecha)
 {
   $sql = "SELECT * FROM tipocambio WHERE 1=1";
   if(isset($fecha))
	$sql = $sql . " AND Fecha = " . $fecha . "";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
} 
?>