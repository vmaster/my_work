<?php
class clsColor
{
function insertar($idColor,$Nombre, $Codigo,$Estado)
 {
   $sql = "INSERT INTO color (idColor,Nombre, Codigo,Estado) VALUES(NULL,UPPER('" . $Nombre . "'),UPPER('" . $Codigo . "'),'" . $Estado ."')";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function eliminar($idColor)
 {
   $sql = "UPDATE color SET Estado='A' WHERE idColor = " . $idColor;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function actualizar($idColor,$Nombre, $Codigo,$Estado)
 {
   $sql = "UPDATE color SET Nombre=UPPER('" . $Nombre . "'), Codigo=UPPER('" . $Codigo . "'),Estado='" . $Estado . "' WHERE idColor="  .$idColor;
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
 
function consultar()
 {
   $sql = "SELECT * FROM color WHERE Estado='N' ORDER BY Nombre ASC";
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function buscar($idColor, $Nombre, $Codigo,$Estado)
 {
   $sql = "SELECT * FROM color WHERE 1=1";
   if(isset($idColor))
	$sql = $sql . " AND idColor = " . $idColor;
   if(isset($Nombre))
	$sql = $sql . " AND Nombre LIKE '" . $Nombre . "%'";
   if(isset($Codigo))
	$sql = $sql . " AND Codigo LIKE '" . $Codigo . "%'";
//   if(isset($Estado))
//	$sql = $sql . " AND Estado='" . $Estado."'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }

} 
?>