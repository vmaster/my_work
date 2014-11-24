<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
$idventa=$_GET['IdVenta'];
$objMovimiento=new clsMovimiento();
$rst=$objMovimiento->consultarventa(5,$idventa,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
$detalle2 = $rst->fetchObject();
?>
<script>
function vercuotas(idventa){
window.open('frm_cuotasventas.php?accion=VER&origen=DOC&idventa='+idventa,'_blank','width=380,height=630');
}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="">
<center>
<fieldset><legend>Detalle de Pedido</legend>
<table width="858" height="108" border="0">
  <tr>
    <td width="151">&nbsp;</td>
    <td width="212">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2" class="alignright"><?php echo substr($detalle2->documento,0,7);?> N&deg;</td>
    <td width="153" align="center"><?php if($detalle2->numero=="0"){echo "Sin entregar";}else{ echo $detalle2->numero;}?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2" class="alignright">Fecha:</td>
    <td align="center"><?php echo substr(trim($detalle2->fecha),0,10);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td class="alignright">Forma de pago:</td>
    <td><div id="divFormaPago">
      <?php if($detalle2->formapago=="A"){echo "CONTADO";}else{echo "CREDITO";} ?>
    </div></td>
    <td width="86" class="alignright"><div id="divTextoInicial">
      <?php if($detalle2->formapago=="B"){echo "<strong>INICIAL: </strong>";} ?>
    </div></td>
    <td width="79"><div id="divInicial">
      <?php if($detalle2->formapago=="B"){$rst5=$objMovimiento->consultarcuotaventaNoAnulada($idventa); 
	$numcuota=$rst5->rowCount();
	$suma=0;
	while($dato = $rst5->fetchObject()){$suma=$suma+$dato->monto;}
	if($detalle2->moneda=='S'){
	echo 'S/. '.number_format(($detalle2->total-$suma),2);}else{
	echo '$ '.($detalle2->total-$suma);
	}
	}
	?>
    </div></td>
    <td width="82" class="alignright"><div id="divTextoCuotas">
      <?php if($detalle2->formapago=="B"){echo "<strong>CUOTAS:</strong>";} ?>
    </div></td>
    <td width="65"><div id="divCuotas">
      <?php 
	if($detalle2->formapago=="B"){
	if($numcuota>1){
	$sigue='cuotas';
	}else{
	$sigue='cuota';
	}
	echo $numcuota.' '.$sigue;}
	?>
    </div></td>
    <td><div id="divLink">
      <?php if($detalle2->formapago=="B"){echo "<a onClick='vercuotas(".$detalle2->idmovimiento.")'>VerCuotas</a>";} ?>
    </div></td>
  </tr>
  <tr>
    <td class="alignright">Cliente:</td>
    <td><?php echo $detalle2->cliente.' '.$detalle2->acliente;?></td>
    <td colspan="2" class="alignright">Responsable:</td>
    <td colspan="3"><?php echo $detalle2->responsable.' '.$detalle2->aresponsable;?></td>
  </tr>
</table>
</fieldset>
</center>
<br>
<center>
<fieldset><legend>Lista</legend>
<table width="861" height="27" class="tablaint">
  <tr>
    <th width="94">Cod. Prod. </th>
    <th width="321">Producto</th>
	<th width="94">Unidad</th>
	<th width="59">Cant.</th>
    <th width="149">Precio unit. </th>
    <th width="118">Total</th>
  </tr>
  <?php 
  $detalle=$objMovimiento->consultardetalleventa($idventa);
  while($dato = $detalle->fetchObject()){?>
  <tr>
    <td><?php echo $dato->codigo;?></td>
    <td><?php //echo $dato->producto.' /'.$dato->categoria.'_'.$dato->marca.'_'.$dato->peso.$dato->unidadpeso;?><?php echo $dato->producto.' /'.$dato->categoria.'-'.$dato->marca.'-'.$dato->color.'-'.$dato->talla;?></td>
	<td><?php echo $dato->unidad;?></td>
	<td><?php echo $dato->cantidad;?></td>
    <td><?php echo $dato->precioventa;?></td>
    <td><?php echo number_format($dato->subtotal,2);?></td>
  </tr>
  <?php } ?>
</table>
</fieldset>
<center>
<BR>
<table width="858" border="0">
  <tr>
    <td width="581" ><label>
	<?php if(isset($_GET['accion'])){?>
	<input type="button" name="Submit" value="CERRAR" onClick="javascript:window.close()">
	<?php }else{?>
      <input type="button" name="Submit" value="Regresar" onClick="javascript:window.open('<?php if($_GET['origen']=='PA') echo 'list_pedidoventa.php'; else echo 'list_pedido.php';?>','_self')">
	  <?php }?>
    </label></td>
    <td width="145" class="alignright" >SUBTOTAL :</td>
    <td width="118"><?php if($detalle2->moneda=='S'){ echo  'S/.  '.$detalle2->subtotal;}else{echo  '$ '.$detalle2->subtotal; }?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="alignright">IGV :</td>
    <td><?php if($detalle2->moneda=='S'){echo  'S/. '.$detalle2->igv;}else{ echo  '$ '.$detalle2->igv;}?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="alignright">TOTAL :</td>
    <td><?php if($detalle2->moneda=='S'){echo 'S/. '.$detalle2->total;}else{echo '$ '.$detalle2->total;}?></td>
  </tr>
</table>
<br>
<?php
if($detalle2->idtipodocumento==1){
$h="frm_comprobanteB.php?idventa=".$_GET['IdVenta']."";
}else{
$h="frm_comprobanteF.php?idventa=".$_GET['IdVenta']."&T=".$detalle2->total."&M=".$detalle2->moneda."";
}
?>
<!--  <label><a href="<?php /*?><?php echo $h;?><?php */?>&REF=detalle">IR A COMRPOBANTE DE PAGO</a></label>-->
</body>

