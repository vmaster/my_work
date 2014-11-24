<?php
class clsMarca
{
function insertar($idMarca,$Descripcion, $Abreviatura,$Estado)
 {
   $sql = "INSERT INTO marca (idMarca,Descripcion, Abreviatura,Estado) VALUES(NULL,UPPER('" . $Descripcion . "'),UPPER('" . $Abreviatura . "'),'" . $Estado ."')";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function eliminar($idMarca)
 {
   $sql = "UPDATE marca SET Estado='A' WHERE idMarca = " . $idMarca;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function actualizar($idMarca,$Descripcion, $Abreviatura,$Estado)
 {
   $sql = "UPDATE marca SET Descripcion=UPPER('" . $Descripcion . "'), Abreviatura=UPPER('" . $Abreviatura . "'),Estado='" . $Estado . "' WHERE idMarca="  .$idMarca;
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
 
function consultar()
 {
   $sql = "SELECT * FROM marca WHERE Estado='N' ORDER BY Descripcion ASC";
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function buscar($idMarca, $Descripcion, $Abreviatura,$Estado)
 {
   $sql = "SELECT * FROM marca WHERE 1=1";
   if(isset($idMarca))
	$sql = $sql . " AND idMarca = " . $idMarca;
   if(isset($Descripcion))
	$sql = $sql . " AND Descripcion LIKE '" . $Descripcion . "%'";
   if(isset($Abreviatura))
	$sql = $sql . " AND Abreviatura LIKE '" . $Abreviatura . "%'";
//   if(isset($Estado))
//	$sql = $sql . " AND Estado='" . $Estado."'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }

} 
?>