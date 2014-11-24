<?php
class clsTalla
{
function insertar($idTalla,$Nombre, $Abreviatura,$Estado)
 {
   $sql = "INSERT INTO talla (idTalla,Nombre, Abreviatura,Estado) VALUES(NULL,UPPER('" . $Nombre . "'),UPPER('" . $Abreviatura . "'),'" . $Estado ."')";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function eliminar($idTalla)
 {
   $sql = "UPDATE talla SET Estado='A' WHERE idTalla = " . $idTalla;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function actualizar($idTalla,$Nombre, $Abreviatura,$Estado)
 {
   $sql = "UPDATE talla SET Nombre=UPPER('" . $Nombre . "'), Abreviatura=UPPER('" . $Abreviatura . "'),Estado='" . $Estado . "' WHERE idTalla="  .$idTalla;
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
 
function consultar()
 {
   $sql = "SELECT * FROM talla WHERE Estado='N' ORDER BY Nombre ASC";
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function buscar($idTalla, $Nombre, $Abreviatura,$Estado)
 {
   $sql = "SELECT * FROM talla WHERE 1=1";
   if(isset($idTalla))
	$sql = $sql . " AND idTalla = " . $idTalla;
   if(isset($Nombre))
	$sql = $sql . " AND Nombre LIKE '" . $Nombre . "%'";
   if(isset($Abreviatura))
	$sql = $sql . " AND Abreviatura LIKE '" . $Abreviatura . "%'";
//   if(isset($Estado))
//	$sql = $sql . " AND Estado='" . $Estado."'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }

} 
?>