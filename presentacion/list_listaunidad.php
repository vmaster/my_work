<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<html>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<body>
<div id="titulo01">LISTADO DE UNIDADES ASIGNADAS</div>
<form action="mant_listaunidad.php?accion=NUEVO&IdProducto=<?php echo $_GET['IdProducto'];?>" method="POST">
<table class="tablaint" align="center">
<tr>
<th>Producto</th>
<th>Unidad</th>
<th>Fórmula</th>
<th>Unidad base</th>
<th>Precio compra</th>
<th>Precio venta</th>
<th>Precio especial</th>
<th>Moneda</th>
<th></th>
<th></th>
</tr>
<tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_listaunidad.php");
$objListaUnidad = new clsListaUnidad();
$rst = $objListaUnidad->buscar('',$_GET['IdProducto'],'');
while($dato = $rst->fetchObject())
{
?>
<td><?php echo $dato->producto?></td> 
<td><?php echo $dato->unidad?></td> 
<td><?php echo $dato->formula?></td>
<td><?php echo $dato->unidadbase?></td>
<td><?php echo $dato->preciocompra?></td>
<td><?php echo $dato->precioventa?></td>
<td><?php echo $dato->precioventaespecial?></td>
<td><?php echo $dato->moneda?></td>
<td><a href="mant_listaunidad.php?accion=ACTUALIZAR&IdProducto=<?php echo $dato->idproducto?>&IdListaUnidad=<?php echo $dato->idlistaunidad;?>"> Actualizar </a></td>
<td>
<?php if($dato->unidad!=$dato->unidadbase){?>
<a href="../negocio/cont_listaunidad.php?accion=ELIMINAR&IdProducto=<?php echo $dato->idproducto?>&IdListaUnidad=<?php echo $dato->idlistaunidad;?>"> Eliminar </a>
<?php }?></td>
</tr>
<?php }?>
<tr>
    <th colspan="10"><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>
<p align="center"><a href="list_producto.php">IR A PRODUCTOS</a></p>
</form>
</body>
</html>