<?php
class clsPersona
{
function insertar($tipopersona,$nombres,$apellidos,$nrodoc,$sexo,$direccion,$celular,$email,$idsector,$idzona,$iddistrito,$representante)
 {
    $sql = "INSERT INTO persona(idpersona,tipopersona,nombres,apellidos,nrodoc,sexo,direccion,celular,email,idsector,idzona,iddistrito,estado, representante) VALUES(NULL,UPPER(TRIM('".$tipopersona."')),UPPER(TRIM('".$nombres."')),UPPER(TRIM('".$apellidos."')),'".$nrodoc."',UPPER('".$sexo."'),UPPER('".$direccion."'),'".$celular."','".$email."',".$idsector.",".$idzona.",".$iddistrito.",'N','".$representante."')";
	
      global $cnx;
      $cnx->query($sql);	 	
 }

function actualizar($idpersona,$tipopersona,$nombres,$apellidos,$numdoc,$sexo,$direccion,$celular,$email,$idsector,$idzona,$iddistrito,$representante)
 {
   $sql = "UPDATE persona SET tipopersona=UPPER(TRIM('".$tipopersona."')) ,nombres =UPPER(TRIM('".$nombres."')), apellidos = UPPER(TRIM('".$apellidos."')), nrodoc = '".$numdoc."', sexo=UPPER('".$sexo."'), direccion=UPPER('".$direccion."'), celular='".$celular."',email='".$email."',idsector=".$idsector.",idzona=".$idzona.",iddistrito=".$iddistrito.", estado = 'N', representante='".$representante."' WHERE idpersona = ".$idpersona ;
      global $cnx;
      $cnx->query($sql);
 }

function eliminar($idpersona)
 {
/*   $sql = "DELETE FROM persona WHERE idpersona = " . $idpersona;*/
 $sql = "UPDATE persona SET estado = 'A' WHERE idpersona = " . $idpersona ;
   global $cnx;
   return $cnx->query($sql);  	
 }
 
function verificaNroDoc($NroDoc)
 {
   $sql = "SELECT * FROM persona WHERE NroDoc='".$NroDoc."'";	  
   global $cnx;
   return $cnx->query($sql);
 }  

function obtenerLastId($NroDoc)
 {
   //$sql = "SELECT IdPersona FROM persona WHERE NroDoc='".$NroDoc."'";	
   $sql = "SELECT LAST_INSERT_ID() as IdPersona";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function consultar()
 {
   $sql = "SELECT persona.IdPersona AS IdPersona, persona.TipoPersona AS TipoPersona, persona.Nombres AS Nombres, persona.Apellidos AS Apellidos, persona.NroDoc AS NroDoc , persona.Sexo AS Sexo, persona.Direccion AS Direccion,persona.Celular AS Celular, persona.Email AS Email, sector.Descripcion AS Sector, zona.Descripcion AS Zona,persona.Estado AS Estado, zona.IdZona AS IdZona, sector.IdSector AS IdSector FROM persona INNER JOIN sector ON persona.Idsector=sector.idsector INNER JOIN zona on persona.idZona=zona.idZona WHERE persona.Estado='N'";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idpersona, $nombres, $apellidos)
 {
   $sql = "SELECT * FROM persona WHERE 1=1";
   if(isset($idpersona))
	$sql = $sql . " AND idpersona = " . $idpersona;
   if(isset($nombres))
	$sql = $sql . " AND nombres LIKE '" . $nombres . "%'";
   if(isset($apellidos))
	$sql = $sql . " AND apellidos LIKE '" . $apellidos . "%'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
 function buscarpersona($idpersona, $nombres, $apellidos)
 {
   $sql = "SELECT IdPersona, TipoPersona, Nombres, Apellidos, NroDoc, Sexo, Direccion, Celular, Email, IdSector, IdZona, Estado, P.iddistrito as iddistrito, D.descripcion as distrito, D.idprovincia as idprovincia, PRO.descripcion as provincia, PRO.iddepartamento as iddepartamento FROM persona P INNER JOIN distrito D ON P.IDdistrito=D.IDdistrito INNER JOIN provincia PRO ON D.IDprovincia=PRO.IDprovincia WHERE 1=1";
   if(isset($idpersona))
	$sql = $sql . " AND idpersona = " . $idpersona;
   if(isset($nombres))
	$sql = $sql . " AND nombres LIKE '" . $nombres . "%'";
   if(isset($apellidos))
	$sql = $sql . " AND apellidos LIKE '" . $apellidos . "%'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
 function idpersona(){
 $sql = "SELECT MAX(idpersona) AS IdPersona FROM persona";
 global $cnx;
 return $cnx->query($sql); 
 }

function consultarpersonarol($rol,$campo,$frase,$limite)
 {
 $sql="SELECT Distinct persona.idpersona, CONCAT(apellidos,' ',nombres) as Nombres, NroDoc
FROM persona
INNER JOIN rolpersona ON persona.idpersona = rolpersona.idpersona
INNER JOIN rol ON rol.idrol = rolpersona.idrol
WHERE rol.estado = 'N'
AND persona.estado = 'N'"; 
/*	enviar 0 para todos
	1 CLIENTE
    2 PROVEEDOR
    3 EMPLEADO
    4 USUARIO
	5 EMPRESA */
 $sql = $sql . " AND ".$campo." LIKE '%" . $frase . "%'";
 if(isset($rol)){
 	if($rol=='5')
		$sql = $sql . " AND rol.idrol = ".$rol."";
	else
		$sql = $sql . " AND (rol.idrol = ".$rol." OR rol.idrol=rol.idrol-".$rol.") AND rol.idrol<>5";
 }	
 $sql = $sql . " $limite";
 
 global $cnx;
 return $cnx->query($sql);
 }

function consultarpersonarollistado($rol,$condicion)
 {
 $sql="SELECT Distinct persona.IdPersona AS IdPersona, persona.TipoPersona AS TipoPersona, persona.Nombres AS Nombres, persona.Apellidos AS Apellidos, persona.NroDoc AS NroDoc , persona.Sexo AS Sexo, persona.Direccion AS Direccion,persona.Celular AS Celular, persona.Email AS Email, sector.Descripcion AS Sector, zona.Descripcion AS Zona, D.descripcion as distrito, PRO.descripcion as provincia, DPTO.descripcion as departamento,persona.Estado AS Estado, zona.IdZona AS IdZona, sector.IdSector AS IdSector FROM persona INNER JOIN sector ON persona.Idsector=sector.idsector INNER JOIN zona on persona.idZona=zona.idZona 
INNER JOIN rolpersona ON persona.idpersona = rolpersona.idpersona
INNER JOIN rol ON rol.idrol = rolpersona.idrol INNER JOIN distrito D ON persona.IDdistrito=D.IDdistrito INNER JOIN provincia PRO ON D.IDprovincia=PRO.IDprovincia INNER JOIN departamento DPTO ON DPTO.IDdepartamento=PRO.IDdepartamento WHERE rol.estado = 'N'
AND persona.estado = 'N'"; 
/*	enviar 0 para todos
	1 CLIENTE
    2 PROVEEDOR
    3 EMPLEADO
    4 USUARIO
	5 EMPRESA */
 if(isset($rol))
	$sql = $sql . " AND (rol.idrol = ".$rol." OR rol.idrol=rol.idrol-".$rol.")";

 $sql = $sql . " $condicion";
 
 global $cnx;
 return $cnx->query($sql);
 }

function buscarXId($id)
 {
 	$sql = "SELECT IdPersona,CONCAT(apellidos,' ',nombres) as Nombres, NroDoc FROM persona WHERE 1=1";
	$sql .= " AND IdPersona=".$id;
	
 global $cnx;
 return $cnx->query($sql); 	 	
 } 
 
function consultarpersonarolventa($rol,$campo,$frase,$limite)
 {
 $sql="SELECT Distinct persona.idpersona, CONCAT(apellidos,' ',nombres) as Nombres, NroDoc
FROM persona
INNER JOIN rolpersona ON persona.idpersona = rolpersona.idpersona
INNER JOIN rol ON rol.idrol = rolpersona.idrol
WHERE rol.estado = 'N'
AND persona.estado = 'N'"; 
/*	enviar 0 para todos
	1 CLIENTE
    2 PROVEEDOR
    3 EMPLEADO
    4 USUARIO
	5 EMPRESA */
 $sql = $sql . " AND ".$campo." LIKE '%" . $frase . "%'";
 if(isset($rol)){
 	if($rol=='5')
		$sql = $sql . " AND rol.idrol = ".$rol."";
	else
		$sql = $sql . " AND (rol.idrol = ".$rol." OR rol.idrol=rol.idrol-".$rol.") AND rol.idrol<>5";
 }

 $sql = $sql . " $limite";
 
 global $cnx;
 return $cnx->query($sql);
 }

function buscar1($idpersona, $nombres, $apellidos,$tipopersona,$rol,$sector,$zona)
 {
   $sql = "SELECT Distinct persona.idpersona,persona.tipopersona,persona.nombres,persona.apellidos,persona.nrodoc,persona.sexo,persona.direccion,persona.celular,persona.email,sector.descripcion as sector,zona.descripcion as zona, D.descripcion as distrito, PRO.descripcion as provincia, DPTO.descripcion as departamento  FROM persona INNER JOIN sector ON persona.Idsector=sector.idsector INNER JOIN zona on persona.idZona=zona.idZona INNER JOIN rolpersona ON persona.idpersona = rolpersona.idpersona
INNER JOIN rol ON rol.idrol = rolpersona.idrol INNER JOIN distrito D ON persona.IDdistrito=D.IDdistrito INNER JOIN provincia PRO ON D.IDprovincia=PRO.IDprovincia INNER JOIN departamento DPTO ON DPTO.IDdepartamento=PRO.IDdepartamento  WHERE rol.estado = 'N'  and persona.estado='N' and rolpersona.idrol<>5";
   if(isset($idpersona))
	$sql = $sql . " AND idpersona = " . $idpersona;
   if(isset($nombres))
	$sql = $sql . " AND nombres LIKE '" . $nombres . "%'";
   if(isset($apellidos))
	$sql = $sql . " AND apellidos LIKE '" . $apellidos . "%'";
	  if(isset($tipopersona) && $tipopersona!='NO')
	$sql = $sql . " AND persona.tipopersona='". $tipopersona."'";
   if(isset($rol) && $rol!=0)
	$sql = $sql . " AND rol.idrol='".$rol."'";
	  if(isset($sector) && $sector!=0)
	$sql = $sql . " AND persona.idsector='".$sector."'";
   if(isset($zona) && $zona!=0)
	$sql = $sql . " AND persona.idzona ='".$zona."'";
   global $cnx;
   return $cnx->query($sql);  		 	
 }

} 
?>