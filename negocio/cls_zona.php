<?php
class clsZona
{
function insertar($idzona, $descripcion)
 {
   $sql = "INSERT INTO zona(idzona, descripcion) VALUES('null',UPPER('" . $descripcion . "'))";
   global $cnx;
   return $cnx->query($sql); 	 	
 }

function actualizar($id, $descripcion)
 {
   $sql = "UPDATE zona SET descripcion = UPPER('" . $descripcion . "') WHERE idzona = " . $id;
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function eliminar($id)
 {
   $sql = "DELETE FROM zona WHERE idzona='".$id."';";
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
function consultar()
 {
   $sql = "SELECT idzona, descripcion FROM zona";
   global $cnx;
   return $cnx->query($sql); 	 	
 }

} 
?>