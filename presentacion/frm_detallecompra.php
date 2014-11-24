<?php 
session_start();

if(!isset($_SESSION['Usuario']))
{
            header("location: ../presentacion/login.php?error=1");
}

require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
$idcompra=$_GET['IdMov'];

if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

$objMovimiento=new clsMovimiento();
$rst=$objMovimiento->verCompra($idcompra);
$detalle2 = $rst->fetchObject();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body>
<center>
<fieldset>
<legend>Datos de la compra:</legend>
<table width="858" height="108" border="0">
  <tr>
    <td width="86">&nbsp;</td>
    <td width="241">&nbsp;</td>
    <td width="152">&nbsp;</td>
    <td width="136" class="alignright"><?php echo substr($detalle2->nomDoc,0,7);?> N&deg;</td>
    <td width="136" align="center"><?php if($detalle2->numero=="0"){echo "Sin entregar";}else{ echo $detalle2->numero;}?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="alignright">Fecha:</td>
    <td align="center"><?php echo trim($detalle2->fecha);?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="alignright">Forma de pago:</td>
	<td><?php if($detalle2->FormaPago=='A'){echo "CONTADO<br>";}else{echo "CREDITO<br>";}?></td>
    
    <td><?php 
	if($detalle2->FormaPago=='B'){
	
	$respCuota=$objMovimiento->TotalAPagar_Cuota($idcompra);
	$TotalCuota = $respCuota->fetchObject();
	
	echo "<label class='alignright'>INICIAL: </label>";
	if($detalle2->moneda=="S" ){echo  "S/. ";}else{echo  "$ ";} 
	echo number_format(($detalle2->Total - $TotalCuota->totalapagar),1)."";?>
	</td><td>
    <?php 
	if($TotalCuota->cant>1){
	$sigue=' cuotas';
	}else{
	$sigue=' cuota';
	}
	echo "<label class='alignright'>CUOTAS: </label>". $TotalCuota->cant.$sigue;?>
	</td><td>
	<?php $abrir="window.open('frm_cuotascompras.php?accion=VER&origen=DOC&idcompra=".$idcompra."','_blank','width=380,height=680');";
	echo "<a href='#' onclick=".$abrir.">Ver Cuotas </a>";
	} 
	?></td>

  </tr>
  <tr>
    <td class="alignright">Proveedor:</td>
    <td><?php echo $detalle2->nomProveedor.' '.$detalle2->apeProveedor;?></td>
    <td class="alignright">responsable:</td>
    <td colspan="2"><?php echo $detalle2->nomResponsable.' '.$detalle2->apeResponsable;?></td>
  </tr>
  
</table>
</fieldset>
</center>
<br>
<center>
<fieldset><legend><strong>Detalle de la compra:</strong></legend>
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
  $detalle=$objMovimiento->consultarDetalleCompra($idcompra);
  while($dato = $detalle->fetchObject()){?>
  <tr>
    <td><?php echo $dato->Codigo;?></td>
    <td><?php //echo $dato->producto.' /'.$dato->categoria.'_'.$dato->marca.'_'.$dato->Peso."".$dato->unidadpeso;?><?php echo $dato->producto.' /'.$dato->categoria.'-'.$dato->marca.'-'.$dato->color.'-'.$dato->talla;?></td>
	<td><?php echo $dato->unidad;?></td>
	<td><?php echo $dato->Cantidad;?></td>
    <td><?php echo $dato->PrecioCompra;?></td>
    <td><?php echo number_format($dato->subtotal,2);?></td>
  </tr>
  <?php } ?>
</table>
<table width="858" class="tablaint">
  <tr>
    <td width="581" ><label>
	
    </label></td>
    <td width="145" class="alignright">SUBTOTAL:</td>
    <td width="118"><?php if($detalle2->moneda=="S" ){echo  "S/. ".$detalle2->SubTotal;}else{echo  "$ ".$detalle2->SubTotal. "";}?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="alignright">IGV:</td>
    <td><?php if($detalle2->moneda=="S" ){echo  "S/. ".$detalle2->Igv;}else{echo  "$ ".$detalle2->Igv. "";}?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td class="alignright">TOTAL:</td>
    <td><strong><?php if($detalle2->moneda=="S" ){echo  "S/. ".$detalle2->Total;}else{echo  "$ ".$detalle2->Total. "";}?></strong></td>
  </tr>
  </table>
</fieldset>
<table width="858" border="0">
  <tr>
  <td align="center"><br><?php if(isset($_GET['accion'])){?>
	<input type="button" name="Submit" value="CERRAR" onClick="javascript:window.close()">
	<?php }else{?>
      <input type="button" name="Submit" value="Regresar" onClick="javascript:window.open('list_compra.php','_self')">
	  <?php }?></td><td></td><td></td>
  </tr>
</table>
</body>

