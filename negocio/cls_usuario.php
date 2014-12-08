<?php
class clsUsuario
{
function insertar($id,$us, $pw, $tipousuario)
 {
 
 	$sql1="SELECT estado FROM usuario WHERE idusuario=".$id;
	global $cnx;
	$rst= $cnx->query($sql1);
	$num_row=$rst->rowCount();
	
	if($num_row==0){
	
	$sql = "INSERT INTO usuario(idusuario,us,pw,idtipousuario,estado) VALUES('".$id."','". $us ."',MD5('". $pw ."'),'". $tipousuario ."','N')";
   global $cnx;
   return $cnx->query($sql);  	

	}else{
	
	$dato=$rst->fetch();
		if($dato[0]=='A'){
		
		$sql = "UPDATE usuario SET  us='". $us ."', pw =MD5('". $pw ."'), idtipousuario='". $tipousuario ."', estado='N' WHERE idusuario = " . $id;
		global $cnx;
		return $cnx->query($sql);  
		}
	}
    	 	
 }

function actualizar($id, $us, $pw, $tipousuario)
 {
   
  $sql = "UPDATE usuario SET  us='". $us ."', pw =MD5('". $pw ."'), idtipousuario='". $tipousuario ."', estado='N' WHERE idusuario = " . $id;
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }
 
function actualizarUltimoAcceso($id)
 {
   
  $sql = "UPDATE usuario SET  UltimoAcceso=NOW() WHERE idusuario = " . $id;
   global $cnx;
   return $cnx->query($sql) or die($sql);  	 	 	
 }

function eliminar($idusuario)
 {
   $sql = "UPDATE usuario SET estado='A' where idusuario=" . $idusuario;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar($usuario)
 {
 	if($usuario != 'admin'){
   		$sql = "SELECT usuario.Idusuario,usuario.US,usuario.PW,persona.Nombres,persona.Apellidos,usuario.UltimoAcceso,tipousuario.Descripcion,usuario.Estado FROM usuario INNER JOIN tipousuario ON usuario.IdTipoUsuario=tipousuario.IdTipoUsuario INNER JOIN persona ON persona.idpersona=usuario.idusuario WHERE usuario.estado='N' and tipousuario.estado='N' and idusuario!=4";
 	}else{
 		$sql = "SELECT usuario.Idusuario,usuario.US,usuario.PW,persona.Nombres,persona.Apellidos,usuario.UltimoAcceso,tipousuario.Descripcion,usuario.Estado FROM usuario INNER JOIN tipousuario ON usuario.IdTipoUsuario=tipousuario.IdTipoUsuario INNER JOIN persona ON persona.idpersona=usuario.idusuario WHERE usuario.estado='N' and tipousuario.estado='N'";
 	}
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idusuario,$us)
 {
   $sql = "SELECT usuario.Idusuario,usuario.US,usuario.PW,tipousuario.Descripcion,persona.idpersona,persona.nombres,persona.apellidos FROM usuario INNER JOIN tipousuario ON usuario.IdTipoUsuario=tipousuario.IdTipoUsuario INNER JOIN persona ON usuario.idusuario=persona.idpersona WHERE 1=1";
   if(isset($idusuario))
	$sql = $sql . " AND Idusuario = " . $idusuario;
   if(isset($login))
	$sql = $sql . " AND us LIKE '" . $us . "%'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
function consultarpersonausuario()
 {
 $sql="SELECT persona.idpersona, persona.nombres, persona.apellidos
FROM persona
INNER JOIN rolpersona ON persona.idpersona = rolpersona.idpersona
INNER JOIN rol ON rol.idrol = rolpersona.idrol
WHERE rol.descripcion = 'usuario'
AND rol.estado = 'N'
AND persona.estado = 'N'"; 

 global $cnx;
 return $cnx->query($sql);
 }
 
function logeo($Usuario, $Clave)
 {
   $sql = "SELECT IdUsuario,IdTipoUsuario FROM usuario WHERE estado='N'";
   if(isset($Usuario) and isset($Clave))
	$sql = $sql . " AND US = '$Usuario' AND PW = MD5('$Clave')";

   global $cnx;
   return $cnx->query($sql);   	
 }  
} 
?>