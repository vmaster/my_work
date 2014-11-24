<?php
class clsConceptoPago
{
function insertar($idconceptopago, $descripcion,$tipo)
 {
   $sql = "INSERT INTO conceptopago(idconceptopago, descripcion,tipo) VALUES('null',UPPER('" . $descripcion . "'),'". $tipo ."')";
   global $cnx;
   return $cnx->query($sql); 	 	
 }

function actualizar($id, $descripcion,$tipo)
 {
   $sql = "UPDATE conceptopago SET Descripcion = UPPER('". $descripcion ."'), Tipo='". $tipo ."'  WHERE idconceptopago=" .$id;
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function eliminar($id)
 {
   $sql = "DELETE FROM conceptopago WHERE idconceptopago='".$id."';";
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
function consultar()
 {
   $sql = "SELECT idconceptopago, descripcion, tipo FROM conceptopago";
   global $cnx;
   return $cnx->query($sql); 	 	
 }

} 
?>