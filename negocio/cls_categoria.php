<?php
class clsCategoria
{
function insertar($idCategoria,$Descripcion, $Abreviatura,$IdCategoriaRef,$Nivel,$Estado)
 {
   $sql = "INSERT INTO categoria (IdCategoria,Descripcion, Abreviatura,IdCategoriaRef,Nivel,Estado) VALUES(NULL,UPPER('" . $Descripcion . "'),UPPER('" . $Abreviatura . "'),".$IdCategoriaRef.",".$Nivel.",'" . $Estado ."')";
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
function eliminar($idCategoria)
 {
   $sql = "UPDATE categoria SET Estado='A' WHERE IdCategoria = " . $idCategoria;
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function actualizar($idCategoria,$Descripcion, $Abreviatura,$IdCategoriaRef,$Nivel,$Estado)
 {
   $sql = "UPDATE categoria SET Descripcion=UPPER('" . $Descripcion . "'), Abreviatura=UPPER('" . $Abreviatura . "'),IdCategoriaRef=".$IdCategoriaRef." , Nivel=".$Nivel.", Estado='" . $Estado . "' WHERE IdCategoria="  .$idCategoria;
   global $cnx;
   return $cnx->query($sql);  	 	
 }
 
 
function consultar()
 {
   $sql = "SELECT * FROM categoria WHERE Estado='N'  ORDER BY Descripcion ASC";
   global $cnx;
   return $cnx->query($sql); 	 	
 }
 
 function maxNivel(){
 	$sql ="SELECT MAX(Nivel)as maximo FROM categoria";
 	global $cnx;
    return $cnx->query($sql); 
 }
 
 function buscar($idCategoria,$Descripcion, $Abreviatura,$IdCategoriaRef,$Nivel,$Estado)
 {
   $sql = "SELECT * FROM categoria WHERE 1=1";
   if(isset($idCategoria))
	$sql = $sql . " AND IdCategoria = " . $idCategoria;
   if(isset($Descripcion))
	$sql = $sql . " AND Descripcion LIKE '" . $Descripcion . "%'";
   if(isset($Abreviatura))
	$sql = $sql . " AND Abreviatura LIKE '" . $Abreviatura . "%'";
   if(isset($IdCategoriaRef))
    $sql = $sql . " AND IdCategoriaRef =".$IdCategoriaRef;
   if(isset($Nivel))
    $sql = $sql . " AND Nivel=".$Nivel;
   if(isset($Estado))
	$sql = $sql . " AND Estado='".$Estado."'";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
function MostrarArbolCategorias()
 {
   $sql = "Call up_BuscarCategoriaProductoArbol();";
   global $cnx;
   return $cnx->query($sql);  		 	
 } 
} 
?>