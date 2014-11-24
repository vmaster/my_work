<?php
class clsUbigeo
{
function consultardepartamentos($idpais){
	$sql="SELECT departamento.iddepartamento as iddepar, departamento.descripcion as departamento FROM departamento WHERE idpais=".$idpais;
	global $cnx;
	return $cnx->query($sql) ;
}

function consultarprovincias($iddepartamento){
	$sql="SELECT provincia.idprovincia as idprov, provincia.descripcion as provincia FROM provincia WHERE iddepartamento=".$iddepartamento;
	global $cnx;
	return $cnx->query($sql) ;
}

function consultardistritos($idprovincia){
	$sql="SELECT distrito.iddistrito as iddist, distrito.descripcion as distrito FROM distrito WHERE idprovincia=".$idprovincia;
	global $cnx;
	return $cnx->query($sql) ;
}

function insertarlugar($direccion, $ubigeo){
$sql="INSERT INTO LUGAR(idlugar,direccion, ubigeo) VALUES(NULL,UPPER('".$direccion."'),UPPER('".$ubigeo."'))";
	global $cnx;
	return $cnx->query($sql) ;
}

function consultarlugar($idlugar, $direccion, $ubigeo){
	$sql="SELECT lugar.idlugar, lugar.direccion, lugar.ubigeo, distrito.iddistrito, distrito.descripcion as distrito, provincia.idprovincia, provincia.descripcion as provincia, departamento.iddepartamento,departamento.descripcion as departamento FROM lugar inner join distrito on distrito.iddistrito=lugar.ubigeo inner join provincia on provincia.idprovincia=distrito.idprovincia inner join departamento on departamento.iddepartamento=provincia.iddepartamento WHERE 1=1";
	if(isset($idlugar))
	$sql.=" AND lugar.idlugar = '".$idlugar."'";
	if(isset($direccion))
	$sql.=" AND lugar.direccion LIKE '%".$direccion."%'";
	if(isset($ubigeo))
	$sql.=" AND lugar.ubigeo = '".$ubigeo."'";
	
	global $cnx;
	return $cnx->query($sql) ;
}

function consultarpordistrito($iddistrito){
	$sql="SELECT distrito.iddistrito, distrito.descripcion as distrito, provincia.idprovincia, provincia.descripcion as provincia, departamento.iddepartamento,departamento.descripcion as departamento FROM  distrito inner join provincia on provincia.idprovincia=distrito.idprovincia inner join departamento on departamento.iddepartamento=provincia.iddepartamento WHERE distrito.iddistrito=".$iddistrito;
		
	global $cnx;
	return $cnx->query($sql) ;
}

function consultarporpersona($idpersona){
	$sql="SELECT persona.direccion, distrito.iddistrito, distrito.descripcion as distrito, provincia.idprovincia, provincia.descripcion as provincia, departamento.iddepartamento,departamento.descripcion as departamento FROM persona inner join distrito on persona.iddistrito=distrito.iddistrito inner join provincia on provincia.idprovincia=distrito.idprovincia inner join departamento on departamento.iddepartamento=provincia.iddepartamento WHERE persona.idpersona=".$idpersona;
		
	global $cnx;
	return $cnx->query($sql) ;
}

} 
?>