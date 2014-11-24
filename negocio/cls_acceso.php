<?php
class clsAcceso
{
function insertar($idacceso, $idtipousuario, $idopcionmenu)
 {
   $sql = "INSERT INTO acceso (idacceso, idtipousuario, idopcionmenu) VALUES('null','" . $idtipousuario . "','" . $idopcionmenu . "')";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function eliminar($idacceso)
 {
   $sql = "DELETE FROM acceso WHERE idacceso = " . $idacceso;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
function consultar($idtipousuario, $idmodulo="")
 {
   if(isset($idtipousuario)){
   		$sql = "SELECT idacceso, idtipousuario, linkmenu, nombremenu FROM acceso A LEFT JOIN opcionmenu OM ON A.idopcionmenu=OM.idopcionmenu WHERE  idtipousuario=" .$idtipousuario. " ";
		if($idmodulo!=''){
			$sql.=" and idmodulo =".$idmodulo." ";
		}
		$sql.="ORDER BY nombremenu ASC";
   }
   else{
   $sql = "SELECT idacceso, idtipousuario, linkmenu, nombremenu FROM acceso A LEFT JOIN opcionmenu OM ON A.idopcionmenu=OM.idopcionmenu WHERE idmodulo =".$idmodulo." ORDER BY nombremenu ASC";
   }
   global $cnx;
   return $cnx->query($sql); 	 	
 }
function verificar($idtipousuario,$idopcionmenu)
 {
   $sql = "SELECT COUNT(*) as cantidad FROM acceso WHERE  idtipousuario='" .$idtipousuario. "' AND idopcionmenu='" .$idopcionmenu. "'";
   global $cnx;
   return $cnx->query($sql);   	 	
 } 
} 
?>