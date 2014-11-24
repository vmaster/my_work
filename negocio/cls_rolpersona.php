<?php
class clsRolPersona
{
function insertar($idpersona,$idrol)
 {
   $sql = "INSERT INTO rolpersona(idrolpersona,idpersona,idrol) VALUES(NULL,'" . $idpersona . "','" . $idrol . "')";
   global $cnx;
   return $cnx->query($sql);   
 }

function actualizar($idrolpersona,$idpersona,$idrol)
 {
   $sql = "UPDATE rolpersona SET idrolpersona='" . $idrolpersona . "', idpersona='" . $idpersona . "', idrol='".$idrol."' WHERE idrolpersona = " . $idrolpersona ;
   global $cnx;
   return $cnx->query($sql);   	 
   	
 }

function eliminar($idrolpersona)
 {
   $sql = "DELETE FROM rolpersona WHERE idrolpersona = " . $idrolpersona;
   global $cnx;
   return $cnx->query($sql);   	 	
 }
 
function vaciar()
 {
   $sql = "DELETE FROM rolpersona";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function consultar()
 {
   $sql = "SELECT IdRolPersona,IdPersona,IdRol FROM rolpersona";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idrolpersona)
 {
   $sql = "SELECT IdRolPersona,IdPersona,IdRol FROM rolpersona WHERE idrolpersona=".$idrolpersona;
   global $cnx;
   return $cnx->query($sql);   	 	
 }

function buscarrol($idrol)
 {
   $sql = "SELECT * FROM rolpersona WHERE idrol=".$idrol;
   global $cnx;
   return $cnx->query($sql);   	 	
 }

/*function consultarrol()
 {
   $sql = "SELECT rolpersona.IdRolPersona, persona.Nombres, persona.Apellidos, rol.Descripcion FROM persona INNER JOIN rolpersona ON persona.IdPersona=rolpersona.IdPersona INNER JOIN rol ON rolpersona.idRol=rol.idRol";
   global $cnx;
   return $cnx->query($sql);  	 	
 }*/
 
 function consultarrolpersona($idpersona)
 {
  $sql = "SELECT rolpersona.IdRolPersona AS IdRolPersona, persona.Nombres AS Nombres, persona.Apellidos AS Apellidos, rol.IdRol AS IdRol,rol.Descripcion AS Descripcion FROM persona INNER JOIN rolpersona ON persona.IdPersona=rolpersona.IdPersona INNER JOIN rol ON rolpersona.idRol=rol.idRol";
 if(isset($idpersona))
 $sql = $sql." WHERE persona.idpersona=".$idpersona;
   global $cnx;
   return $cnx->query($sql);  	 
 }	
 }
?>