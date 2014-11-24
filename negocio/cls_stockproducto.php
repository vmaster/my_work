<?php
class clsStockProducto
{
function insertar($idsucursal,$idproducto,$idunidad,$cantidad,$idmovimiento,$Moneda,$PrecioUnidad,$Fecha,$IdUsuario)
 {
   $sql = "CALL up_AgregarStockProducto (".$idsucursal.",".$idproducto.",".$idunidad.",".$cantidad.",".$idmovimiento.",'".$Moneda."',".$PrecioUnidad.",'".$Fecha."',".$IdUsuario.")";
   global $cnx;
   return $cnx->query($sql) or die($sql);  	 	 	
 }

function obtenerStock($idsucursal,$idproducto,$idunidad)
 {
   $sql = "SELECT obtenerStock(".$idproducto.",".$idunidad.",".$idsucursal.") as StockActual";
   global $cnx;
   return $cnx->query($sql);  		 	
 }
} 
?>