<?php
class clsTipoUsuario
{
function insertar($idtipousuario, $descripcion)
 {
 
 $sql1="SELECT idtipousuario, descripcion, estado FROM tipousuario WHERE descripcion=UPPER('".$descripcion."')";
  global $cnx;
   $rst=$cnx->query($sql1); 
   $num=$rst->rowcount();
   
   if($num==0){
    $sql = "INSERT INTO tipousuario(idtipousuario, descripcion, estado) VALUES('null',UPPER('" . $descripcion . "'),'N')";
   global $cnx;
   return $cnx->query($sql); 
   
   }else{
   
   $dato=$rst->fetch();
   $sql="UPDATE tipousuario SET descripcion = UPPER('" . $descripcion . "'), estado='N' WHERE idtipousuario = " .$dato[0];
   global $cnx;
   return $cnx->query($sql); 
   }
   	 	
 }

function actualizar($idtipousuario, $descripcion)
 {
   $sql = "UPDATE tipousuario SET descripcion = UPPER('" . $descripcion . "') WHERE idtipousuario = " . $idtipousuario;
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function eliminar($idtipousuario)
 {
   $sql = "UPDATE tipousuario SET estado = 'D' WHERE idtipousuario = " . $idtipousuario;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
function consultar()
 {
   $sql = "SELECT idtipousuario, descripcion, estado FROM tipousuario WHERE estado='N'";
   global $cnx;
   return $cnx->query($sql); 	 	
 }

function buscar($idtipousuario, $descripcion)
 {
   $sql = "SELECT idtipousuario, descripcion, estado FROM tipousuario WHERE 1=1";
   if(isset($idtipousuario)&& strlen($idtipousuario)>0)
	$sql = $sql . " AND idtipousuario = " . $idtipousuario;
   if(isset($descripcion)&& strlen($descripcion)>0)
	$sql = $sql . " AND descripcion ='" . $descripcion . "'";	
	
   global $cnx;
   return $cnx->query($sql); 	 	
 }
} 
?>