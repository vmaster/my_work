<?php
class clsRol
{
function insertar($descripcion)
 {
   $sql = "INSERT INTO rol(idrol,descripcion,estado) VALUES(NULL,UPPER('" . $descripcion . "'),'N')";
   global $cnx;
   return $cnx->query($sql);   	
 }

function actualizar($idrol, $descripcion)
 {
   $sql = "UPDATE rol SET descripcion=UPPER('" . $descripcion . "'), estado='N' WHERE idrol = " . $idrol ;
   global $cnx;
   return $cnx->query($sql);   	 	
 }

function eliminar($idrol)
 {
/*   $sql = "DELETE FROM rol WHERE idrol = " . $idrol;*/
     $sql = "UPDATE rol SET estado='A' WHERE idrol = " . $idrol ;
	global $cnx;
   return $cnx->query($sql);   	 	
 }
 
function vaciar()
 {
   $sql = "DELETE FROM rol";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function consultar()
 {
   $sql = "SELECT IdRol, descripcion,estado FROM rol WHERE estado='N'";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idrol)
 {
   $sql = "SELECT IdRol,Descripcion,Estado FROM rol WHERE idrol=".$idrol;
   global $cnx;
   return $cnx->query($sql);   	 	
 }
 
 
}
?>