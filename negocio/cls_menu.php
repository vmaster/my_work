<?php
class clsMenu
{
function insertar($idopcionmenu, $linkmenu, $nombremenu , $idmodulo)
 {
   $sql = "INSERT INTO opcionmenu(idopcionmenu, linkmenu, nombremenu, idmodulo) VALUES('" . $idopcionmenu . "','" . $linkmenu . "','" . $nombremenu . "', ". $idmodulo.")";
   global $cnx;
   return $cnx->query($sql); 	 	
 }

function actualizar($idopcionmenu, $linkmenu, $nombremenu, $idmodulo)
 {
   $sql = "UPDATE opcionmenu SET linkmenu = '" . $linkmenu . "', nombremenu = '" . $nombremenu . "', idmodulo=". $idmodulo." WHERE idopcionmenu = " . $idopcionmenu;
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function eliminar($idopcionmenu)
 {
   $sql = "DELETE FROM opcionmenu WHERE idopcionmenu = " . $idopcionmenu;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
function consultar($idtipousuario)
 {
   if($idtipousuario>0)
   $sql = "SELECT idopcionmenu, linkmenu, nombremenu, m.idmodulo, descripcion as modulo FROM opcionmenu opc left join modulo m on opc.idmodulo=m.idmodulo WHERE idopcionmenu NOT IN (SELECT idopcionmenu FROM acceso WHERE idtipousuario=".$idtipousuario.") ORDER BY linkmenu";
   else
   $sql = "SELECT idopcionmenu, linkmenu, nombremenu, opc.idmodulo, descripcion as modulo FROM opcionmenu opc left join modulo m on opc.idmodulo=m.idmodulo ORDER BY linkmenu";
   
   global $cnx;
   return $cnx->query($sql); 	 	
 }

function buscar($idopcionmenu, $linkmenu, $nombremenu)
 {
   $sql = "SELECT idopcionmenu, linkmenu, nombremenu, m.idmodulo, descripcion as modulo FROM opcionmenu opc left join modulo m on opc.idmodulo=m.idmodulo WHERE 1=1";
   if(isset($idopcionmenu) && strlen($idopcionmenu)>0)
	$sql = $sql . " AND idopcionmenu = " . $idopcionmenu;
   if(isset($linkmenu) && strlen($linkmenu)>0)
	$sql = $sql . " AND linkmenu LIKE '" . $linkmenu . "%'";
   if(isset($nombremenu) && strlen($nombremenu)>0)
	$sql = $sql . " AND nombremenu LIKE '" . $nombremenu . "%'";
	
   global $cnx;
   return $cnx->query($sql); 	 	
 }
} 
?>