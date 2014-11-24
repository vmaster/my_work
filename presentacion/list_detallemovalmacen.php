<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
  header("location: ../presentacion/login.php?error=1");
}
require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
require("../negocio/cls_detallemovalmacen.php");
$idmov=$_GET['IdMov'];
$objMovimiento=new clsMovimiento();
$rst2=$objMovimiento->consultaralmacen($idmov,NULL,NULL,0,NULL,NULL,0);
$dato2 = $rst2->fetchObject();
?>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css" />

<center>
  <fieldset>
  <legend>Datos del documento</legend>
  <table class="tablaint">
    <tr>
      <td width="74">&nbsp;</td>
      <td width="321">&nbsp;</td>
      <td width="92">&nbsp;</td>
      <td width="122" class="alignright"><?php echo $dato2->tipodoc;?> N&deg;</td>
      <td width="230" align="left"><?php echo $dato2->numero;?></td>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="alignright">FECHA :</td>
      <td align="left"><?php echo substr(trim($dato2->fecha),0,10);?></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td class="alignright">CLIENTE :</td>
      <td><?php echo $dato2->persona.' '.$dato2->apersona;?></td>
    <td class="alignright">RESPONSABLE :</td>
      <td colspan="2"><?php echo $dato2->responsable.' '.$dato2->aresponsable;?></td>
    </tr>
  </table>
  </fieldset>
</center>
</br>
<center>
<fieldset>
<legend>Detalle del documento</legend>
<table class="tablaint">
  <tr>
    <th width="69">Código</th>
    <th width="103">Producto</th>
    <th width="82">Unidad</th>
    <th width="99">Cantidad</th>
    <th width="105">Medida</th>
    <th width="90">Precio Compra</th>
	<th width="126">Precio venta normal</th>
	<th width="147">Precio venta especial</th>
    <th width="107">Subtotal</th>
  </tr>
  <tr>
    <?php
$objDetalleMovAlmacen = new clsDetalleMovAlmacen();
$rst = $objDetalleMovAlmacen->consultar($idmov);
while($dato = $rst->fetchObject())
{
?>
    <td><div align="center"><?php echo $dato->codpro?>&nbsp;</div></td>
    <td><div align="center"><?php echo $dato->producto.' /'.$dato->categoria.'-'.$dato->marca.'-'.$dato->color.'-'.$dato->talla;?>&nbsp;</div></td>
    <td><div align="center"><?php echo $dato->unidad?>&nbsp;</div></td>
    <td><div align="center"><?php echo $dato->cantidad?>&nbsp;</div></td>
    <td><div align="center"><?php echo $dato->peso?>&nbsp;</div></td>
    <td><div align="center"><?php echo number_format($dato->preciocompra,2)?>&nbsp;</div></td>
	<td><div align="center"><?php echo number_format($dato->precioventa,2)?>&nbsp;</div></td>
	<td><div align="center"><?php echo number_format($dato->precioventaespecial,2)?>&nbsp;</div></td>
    <td><div align="right"><?php echo number_format($dato->subtotal,2)?></div>
    <div align="right"></div>  
    <div align="right"></div>
      <div align="right"></div><div align="right"></div></td>
  </tr>
  <?php } ?>
</table>
<table class="tablaint">
  <tr>
    <td width="698">&nbsp;</td>
    <td width="147" class="alignright">TOTAL :</td>
    <td width="107"><div align="right">
      <?php  $rst1 = $objDetalleMovAlmacen->consultar($idmov);														                                            while($dato1 = $rst1->fetchObject()){
											$suma1=$dato1->subtotal;
											$suma=$suma1+$suma;}
											echo number_format($suma,2);?> 
    </div></td>
  </tr>
  <tr>
    <th colspan="3"><input type='button' name = 'cancelar' value='CANCELAR' onclick="javascript:window.open('list_almacen.php','_self')" /></th>
  </tr>
</table>
</fieldset>
</center>