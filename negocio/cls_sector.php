<?php
class clsSector
{
function insertar($descripcion)
 {
   $sql = "INSERT INTO sector (idsector,descripcion) VALUES(NULL,UPPER('" . $descripcion . "'))";
   global $cnx;
   return $cnx->query($sql);   	
 }

function actualizar($idsector, $descripcion)
 {
   $sql = "UPDATE sector SET descripcion =UPPER('" . $descripcion . "') WHERE idsector = " . $idsector ;
   global $cnx;
   return $cnx->query($sql);   	 	
 }

function eliminar($idsector)
 {
   $sql = "DELETE FROM sector WHERE idsector = " . $idsector;
   global $cnx;
   return $cnx->query($sql);   	 	
 }
 
function vaciar()
 {
   $sql = "DELETE FROM sector";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function consultar()
 {
   $sql = "SELECT IdSector, descripcion FROM sector";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idsector)
 {
   $sql = "SELECT IdSector,Descripcion FROM sector WHERE idsector=".$idsector;
   global $cnx;
   return $cnx->query($sql);   	 	
 }
}
?>