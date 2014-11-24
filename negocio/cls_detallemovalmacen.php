<?php
class clsDetalleMovAlmacen
{
	function insertar($idmov,$idpro,$idunid,$cantidad,$pcompra,$pventa){
		global $cnx;
		$sql="INSERT INTO detallemovalmacen(iddetallemovalmacen,idmovimiento,idproducto,idunidad,cantidad,preciocompra,precioventa) VALUES 		(NULL,'".$idmov."','".$idpro."','".$idunid."','".$cantidad."','".$pcompra."','".$pventa."')";
   		return $cnx->query($sql);
	}
	
function consultar($idmov){
		$sql="SELECT unidad.descripcion as unidad,unidad.idunidad as idunidad,producto.descripcion as producto, producto.codigo as codpro,producto.idproducto as idproducto,detallemovalmacen.iddetallemovalmacen as iddetalle,detallemovalmacen.preciocompra ,detallemovalmacen.precioventa,peso.descripcion as peso,detallemovalmacen.cantidad as cantidad,movimiento.fecha as fecha,movimiento.idusuario as idusuario,movimiento.moneda as moneda,listaunidad.precioventaespecial ,(cantidad*detallemovalmacen.precioventa) as subtotal,movimiento.idtipodocumento as tipodoc,movimiento.comentario as comentario,movimiento.idmovimiento as idmov,movimiento.numero, Producto.idcolor, color.nombre as color, color.codigo as codigocolor, Producto.idtalla, talla.nombre as talla, talla.abreviatura as tallaabreviatura, categoria.descripcion as categoria, marca.descripcion as marca FROM detallemovalmacen inner join producto on detallemovalmacen.idproducto=producto.idproducto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join unidad on detallemovalmacen.idunidad=unidad.idunidad inner join unidad peso on producto.idmedidapeso=peso.idunidad inner join movimiento on movimiento.idmovimiento=detallemovalmacen.idmovimiento inner join listaunidad on listaunidad.idproducto=producto.idproducto and unidad.idunidad=listaunidad.idunidad LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN Talla ON Talla.idtalla=producto.idtalla WHERE detallemovalmacen.idmovimiento=".$idmov;
		
	 	global $cnx;
   		return $cnx->query($sql);  
	}
	
function consultarDetalleCompra($idmov){
		$sql="SELECT unidad.descripcion as unidad,unidad.idunidad as idunidad,producto.descripcion as producto, producto.codigo as codpro,producto.idproducto as idproducto,detallemovalmacen.iddetallemovalmacen as iddetalle,detallemovalmacen.preciocompra as pcompra,detallemovalmacen.precioventa as pventa,detallemovalmacen.cantidad as cantidad,movimiento.fecha as fecha,movimiento.idusuario as idusuario,movimiento.moneda as moneda,(cantidad*preciocompra) as subtotal, movimiento.FormaPago,movimiento.IdPersona,movimiento.Numero FROM detallemovalmacen inner join producto on detallemovalmacen.idproducto=producto.idproducto inner join unidad on detallemovalmacen.idunidad=unidad.idunidad inner join movimiento on movimiento.idmovimiento=detallemovalmacen.idmovimiento WHERE detallemovalmacen.idmovimiento=".$idmov;
	 	global $cnx;
   		return $cnx->query($sql);  
	}
		
}
?>