<?php
class clsTransportista
{
function insertar($idtransportista, $idsucursal, $nombre, $dniruc, $direccion, $marcavehiculo, $numeroplaca, $numeroconstancia, $licenciabrevete, $nombrechofer, $estado){
$sql="INSERT INTO transportista(idtransportista, idsucursal, nombrerazonsocial, dniruc, direccion, marcavehiculo, nroplaca, nroconstanciacertificado, licenciabrevete, nombrechofer, estado) VALUES(NULL,'".$idsucursal."',UPPER('".$nombre."'),'".$dniruc."',UPPER('".$direccion."'),UPPER('".$marcavehiculo."'),UPPER('".$numeroplaca."'),'".$numeroconstancia."','".$licenciabrevete."',UPPER('".$nombrechofer."'),'N')";

   global $cnx;
   return $cnx->query($sql);
}

function actualizar($idtransportista, $idsucursal, $nombre, $dniruc, $direccion, $marcavehiculo, $numeroplaca, $numeroconstancia, $licenciabrevete, $nombrechofer, $estado){
 $sql = "UPDATE transportista SET nombrerazonsocial =UPPER('" . $nombre ."'), dniruc ='" . $dniruc ."', direccion = UPPER('" . $direccion . "'), marcavehiculo = UPPER('" . $marcavehiculo . "'), nroplaca = UPPER('" . $numeroplaca . "'), nroconstanciacertificado = '" . $numeroconstancia . "', licenciabrevete = '" . $licenciabrevete. "', nombrechofer = UPPER('" . $nombrechofer . "'), estado = 'N'  WHERE idtransportista = " . $idtransportista ;

   global $cnx;
   return $cnx->query($sql);
}

function eliminar($idtransportista)
 {
   $sql = "UPDATE transportista SET estado='A' WHERE idtransportista = " . $idtransportista;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultarajax($idtransportista, $campo, $frase)
 {
   $sql = "SELECT idtransportista as id, nombrerazonsocial as transportista, dniruc as documento, nombrechofer as chofer, direccion, marcavehiculo, nroplaca as numeroplaca, nroconstanciacertificado as numeroconstancia, licenciabrevete FROM transportista WHERE estado='N'";
   
   if(isset($idtransportista))
	$sql.= " AND idtransportista = " . $idtransportista;
   if(isset($campo)){
   if($campo=='1')
   $sql.= " AND nombrerazonsocial LIKE '%" . $frase."%'";
   else if($campo=='2')
   $sql.= " AND dniruc LIKE '%" . $frase."%'";
   else if ($campo=='3')
   $sql.= " AND nombrechofer LIKE '%" . $frase."%'";
   
   }
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
} 
?>