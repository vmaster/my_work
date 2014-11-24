<?php
class clsUnidad
{
function insertar($idunidad, $descripcion, $abreviatura, $tipo)
 {
   $sql = "INSERT INTO unidad(idunidad, descripcion, abreviatura, tipo, estado) VALUES(NULL,UPPER('" . $descripcion . "'),UPPER('" . $abreviatura . "'), '".$tipo."', 'N')";
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function actualizar($idunidad, $descripcion, $abreviatura, $tipo)
 {
   $sql = "UPDATE unidad SET descripcion=UPPER('" . $descripcion ."'), abreviatura =UPPER('" . $abreviatura ."'), tipo='".$tipo."', estado = 'N' WHERE idunidad = " . $idunidad ;
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function eliminar($idunidad)
 {
   $sql = "UPDATE unidad SET estado='A' WHERE idunidad = " . $idunidad;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar()
 {
   $sql = "SELECT idunidad, descripcion, abreviatura, tipo, estado FROM unidad where estado='N'";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idunidad, $descripcion, $tipo)
 {
   $sql = "SELECT idunidad, descripcion, abreviatura, tipo, estado FROM unidad WHERE 1=1";
   if(isset($idunidad) && $idunidad!=""){
	$sql = $sql . " AND idunidad = " . $idunidad;}
   if(isset($descripcion)){
	$sql = $sql . " AND descripcion LIKE '" . $descripcion . "%'";}
   if(isset($tipo)){
	$sql = $sql . " AND tipo LIKE '" . $tipo . "%'";}
   	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
} 
?>