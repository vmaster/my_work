<?php
class clsArmario
{
function insertar($idarmario, $codigo, $nombre, $totalcolumnas, $totalfilas)
 {
   $sql = "INSERT INTO armario(idarmario, codigo, nombre, totalcolumnas, totalfilas) VALUES(NULL,UPPER('" . $codigo . "'),UPPER('" . $nombre . "'),". $totalcolumnas .",". $totalfilas . ")";
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function actualizar($idarmario, $codigo, $nombre, $totalcolumnas, $totalfilas)
 {
   $sql = "UPDATE armario SET codigo =UPPER('" . $codigo ."'), nombre =UPPER('" . $nombre ."'), totalcolumnas = " . $totalcolumnas . ", totalfilas = " . $totalfilas . " WHERE idarmario = " . $idarmario ;
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function eliminar($idarmario)
 {
   $sql = "DELETE FROM armario WHERE idarmario = " . $idarmario;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar()
 {
   $sql = "SELECT idarmario, codigo, nombre, totalcolumnas, totalfilas FROM armario";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idarmario, $codigo, $nombre)
 {
   $sql = "SELECT idarmario, codigo, nombre, totalcolumnas, totalfilas FROM armario WHERE 1=1";
   if(isset($idarmario))
	$sql = $sql . " AND idarmario = " . $idarmario;
   if(isset($nombre))
	$sql = $sql . " AND codigo LIKE '" . $codigo . "%'";
   if(isset($descripcion))
	$sql = $sql . " AND nombre LIKE '" . $nombre . "%'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
} 
?>