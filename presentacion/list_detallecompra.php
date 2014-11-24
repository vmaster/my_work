<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css" />

<form id="form1" name="form1" method="post" action="frm_almacen.php">
  <table width="600" class="tablaint">
    <tr>
     <th width="124">Cod producto </th>
      <th width="85">Producto</th>
      <th width="61">Unidad</th>
      <th width="81">Cantidad</th>
      <th width="60">Precio compra </th>
	  <th width="124">Sub total </th>
    </tr>
	
    <tr>
	
<?php
require("../datos/cado.php");
require("../negocio/cls_detallemovalmacen.php");
$objDetalleMovAlmacen = new clsDetalleMovAlmacen();
$rst = $objDetalleMovAlmacen->consultarDetalleCompra($_GET['IdMov']);
$total=0;
while($dato = $rst->fetchObject())
{
?>
      <td><div align="center"><?php echo $dato->codpro?>&nbsp;</div></td>
      <td><div align="center"><?php echo $dato->producto?>&nbsp;</div></td>
      <td><div align="center"><?php echo $dato->unidad?>&nbsp;</div></td>
      <td><div align="center"><?php echo $dato->cantidad?>&nbsp;</div></td>
	  <td><div align="center"><?php echo $dato->pcompra?>&nbsp;</div></td>
	  <td><div align="right"><?php echo number_format(($dato->pcompra*$dato->cantidad),2)?>&nbsp;</div></td>
    </tr>
	
<?php 
$total=number_format(($dato->pcompra*$dato->cantidad),2)+$total;
} 
?>	
<tr>
      <td colspan="5" class="alignright">TOTAL:</td>
      <td><div align="right"><?php echo number_format($total,2)?>&nbsp;</div></td>
</tr>
  </table>
  <table width="600">
  <tr>
    <th><input type='button' name = 'cancelar' value='CANCELAR' onclick="javascript:window.open('list_compra.php','_self')" /></th>
  </tr>
</table>

</form>
