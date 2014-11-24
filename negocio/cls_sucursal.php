<?php
class clsSucursal
{
function insertar($nombre, $direccion, $ruc)
 {
   $sql = "INSERT INTO sucursal (idsucursal, nombre, direccion, ruc) VALUES(NULL,UPPER('" . $nombre . "'),UPPER('" . $direccion . "'), '".$ruc."')";
   global $cnx;
   return $cnx->query($sql);   	
 }

function actualizar($idsucursal, $nombre, $direccion, $ruc)
 {
   $sql = "UPDATE sucursal SET nombre =UPPER('" . $nombre ."'), direccion = UPPER('" . $direccion . "'), ruc='".$ruc."' WHERE idsucursal = " . $idsucursal ;
   global $cnx;
   return $cnx->query($sql);   	 	
 }

function eliminar($idsucursal)
 {
   $sql = "DELETE FROM sucursal WHERE idsucursal = " . $idsucursal;
   global $cnx;
   return $cnx->query($sql);   	 	
 }
 
function vaciar()
 {
   $sql = "TRUNCATE TABLE sucursal";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function consultar()
 {
   $sql = "SELECT IdSucursal, Nombre, Direccion, Ruc FROM sucursal";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idsucursal)
 {
   $sql = "SELECT IdSucursal, Nombre, Direccion, Ruc FROM sucursal WHERE 1=1";
   $sql .= " AND idsucursal = " . $idsucursal . " ORDER BY 2 Asc";
   global $cnx;
   return $cnx->query($sql);   	 	
 }
 
function buscarconxajax($campo,$frase,$limite)
 {
   $sql = "SELECT IdSucursal, Nombre, Direccion FROM sucursal WHERE 1=1";
   $sql .= " AND " . $campo . " like '%" .$frase. "%' ORDER BY 2 Asc ".$limite;
   global $cnx;
   return $cnx->query($sql);   	 	
 }

} 
?>