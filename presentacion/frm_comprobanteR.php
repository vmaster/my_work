<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
$idventa=$_GET['idventa'];
$objMovimiento=new clsMovimiento();
$rst=$objMovimiento->consultarventa(2,$idventa,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
$detalle2 = $rst->fetchObject();
?>
<style type="text/css">
table.botonera {
    margin: auto;
    border-spacing: 0px;
    border-collapse: collapse;
    empty-cells: show;
    width: auto;
    background: url(../imagenes/background-botoneragral.gif) repeat-x;
	background-color: #FFFFFF;
}
</style>
<script>
	function imprimir(que) {
	var ventana = window.open("", '', '');
	var contenido = "<html><body onload='window.print();window.close();'>";
	contenido = contenido + document.getElementById(que).innerHTML + "</body></html>";
	ventana.document.open();
	ventana.document.write(contenido);
	ventana.document.close();
	}
	</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
<!--
.Estilo2 {font-size: 12px}
.Estilo3 {
	font-size: 14px;
	font-weight: bold;
}
.Estilo4 {font-size: 14px}
-->
</style>
</head>
<body onLoad="">
<div id="recibo">
<table width="581" height="108" border="0">
  <tr>
    <td colspan="2" rowspan="3"><div  align="center"><?php echo $_SESSION['Sucursal'];?></div>
      <img src="../imagenes/cabeceraGuia.PNG" width="372" height="69"></td>
    <td width="77" align="right"><span class="Estilo3"><?php echo substr($detalle2->documento,0,7);?> N&deg; </span></td>
    <td width="118" align="center"><span class="Estilo4">
      <?php if($detalle2->numero=="0"){echo "Sin entregar";}else{ echo $detalle2->numero;}?>
    </span></td>
  </tr>
  <tr>
    <td align="right"><span class="Estilo4"><strong>FECHA</strong>:</span></td>
    <td align="center"><span class="Estilo4"><?php echo substr(trim($detalle2->fecha),0,10);?></span></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="124"><span class="Estilo2"><strong>CLIENTE:</strong></span></td>
    <td width="244"><span class="Estilo2"><?php echo $detalle2->cliente.' '.$detalle2->acliente;?></span></td>
    <td><span class="Estilo2"></span></td>
    <td><span class="Estilo2"></span></td>
  </tr>
  <tr>
    <td><span class="Estilo2"><strong>RESPONSABLE:</strong></span></td>
    <td colspan="3"><span class="Estilo2"><?php echo $detalle2->responsable.' '.$detalle2->aresponsable;?></span></td>
    </tr>
</table>

<table width="576" height="27" border="0">
  <tr>
    <td width="69"><span class="Estilo2"><strong>COD.</strong></span></td>
    <td width="191"><span class="Estilo2"><strong>PRODUCTO</strong></span></td>
	<td width="64"><span class="Estilo2"><strong>UNIDAD</strong></span></td>
	<td width="47"><span class="Estilo2"><strong>CANT.</strong></span></td>
    <td width="105"><span class="Estilo2"><strong>PRECIO UNIT. </strong></span></td>
    <td width="141"><span class="Estilo2"><strong>TOTAL</strong></span></td>
  </tr>
  <?php 
  $detalle=$objMovimiento->consultardetalleventa($idventa);
  while($dato = $detalle->fetchObject()){?>
  <tr>
    <td><span class="Estilo2"><?php echo $dato->codigo;?></span></td>
    <td><span class="Estilo2"><?php echo $dato->producto.' /'.$dato->categoria.'_'.$dato->marca.'_'.$dato->peso.$dato->unidadpeso;?></span></td>
	<td><span class="Estilo2"><?php echo $dato->unidad;?></span></td>
	<td><span class="Estilo2"><?php echo $dato->cantidad;?></span></td>
    <td><span class="Estilo2"><?php echo $dato->precioventa;?></span></td>
    <td><span class="Estilo2"><?php echo number_format($dato->subtotal,2);?></span></td>
  </tr>
  <?php } ?>
</table>

<table width="575" border="0">
  <tr>
    <td width="348"><span class="Estilo2"></span></td>
    <td width="92" align="right"><span class="Estilo2">TOTAL :</span></td>
    <td width="121"><span class="Estilo2">
      <?php if($detalle2->moneda=='S'){echo 'S/. '.$detalle2->total;}else{echo '$ '.$detalle2->total;}?>
    </span></td>
  </tr>
</table>
</div>
<br>
<label>
	
	<input type="button" name="Submit2" value="IMPRIMIR" onClick="javascript:imprimir('recibo')">
	
      <input type="button" name="Submit" value="REGRESAR" onClick="javascript:window.open('<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ if($_GET['origen']=='VENTA') echo "list_ventas.php"; else echo "list_pedidoventa.php";}?>','_self')">
	</label>
</body>

