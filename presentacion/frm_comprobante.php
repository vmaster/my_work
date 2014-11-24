<?php
session_start();
@require("xajax_comprobante.php");
?>

<html>
<head>
<?php $xajax->printJavascript();?>
<script>

function generarBoleta(id){
 xajax_presentarboleta(id);
}
function generarFactura(id){
 xajax_presentarfactura(id);
}
</script>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
	
}

-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><head>
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
</head>
<body  style="font:Arial, Helvetica, sans-serif;" onLoad="<?php if($_GET['TD']=="1"){echo "generarBoleta(".$_GET['idventa'].");";}else{echo "generarFactura(".$_GET['idventa'].");";}?>">
<?php

if($_GET['TD']=="1"){
?>

 <p align="center">&nbsp;</p>
 
<div id='boleta' style="position:; left: 302px; top: 63px; width: 800px; height: 495px;">
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table width="179" style="margin:20;">
	<tr>
		<td width="60"><span class="Estilo1" style='font-size:13px;'>FECHA: </span></td>
	  <td width="25"><div style='font-size:13px;' id="diaFecha"></div></td>
	  <td width="25"><div style='font-size:13px;' id="mesFecha"></div></td>
	  <td width="49"><div style='font-size:13px;' id="anioFecha"></div></td>
  	</tr>
  </table>
  <table width="500" style="margin:20;">
    <tr>
		<td width="60"><span style='font-size:13px;' class="Estilo1">SR.(ES): </span></td>
      <td width="260" align="left"><div style='font-size:13px;' id="divNombreCliente"></div></td>
		<td width="90"><span style='font-size:13px;' class="Estilo1">DOC. IDENT. </span></td>
		<td width="70" align="right"><div style='font-size:13px;' id="DivDocIdentidad">&nbsp;&nbsp;</td>
    </tr>
  </table>
  <table width="531" style="margin:20;">
    <tr>
		<td width="73"><span class="Estilo1" style='font-size:13px;'>DIRECCION: </span></td>
      <td width="446" align="right"><div style='font-size:13px;' id="DivDireccionCliente" align="left"></div></td>
		
    </tr>
  </table>
    <table width="530" style="margin:20;">
    <tr>
		<td width="79"><span class="Estilo1" style='font-size:13px;'>F. PAGO: </span></td>
      <td width="101" align="right"><div style='font-size:13px;' align="left"><div id="DivTP"></div></div></td>
	  <td width="90"><div align="right" class="Estilo1" style='font-size:13px;'>Nº CUOTAS :</div></td>
		<td width="70"> <div align="center"><div style='font-size:13px;' id="DivNCuotas"></div></div></td>
		<td width="69"><div align="right" class="Estilo1" style='font-size:13px;'>INICIAL:</div></td>
		<td width="103"> <div align="center"><div style='font-size:13px;' id="DivInicial"></div></div></td>
    </tr>
  </table>
  <div id='DivDetalleVenta'></div>
  <table width="500" style="margin:20;">
    <tr align="left">
		
		<td width="455"><strong><div style='font-size:12px;' id="DivEtiquetaTotal" align="right">TOTAL :</div>
		</strong></td>
		<td width="73" align="right"><div style='font-size:13px;' id="DivTotal"></div></td>
    </tr>
  </table>
  
 
</div>
<p align="center"><a href="javascript:imprimir('boleta')" class="Estilo16">IMPRIMIR BOLETA </a> </p>
<p align="center"><a href="<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ echo "list_ventas.php";}?>" class="Estilo16"> REGRESAR </a> </p>
<?php
}else{
?>
 
 <div id='factura' style="position:; left: 302px; top: 63px; width: 700px; height: 495px;">
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <table align="right" width="230">
	<tr align="right">
	  <td width="109"><div style="font-size:18px" id="DivSerieDoc" align="rigth"></div></td><td width="109"><div style="font-size:18px" id="DivNumeroDoc" align="left"></div></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><strong><span style='font-size:13px;' class="Estilo1">FECHA EMISION: </span></strong></td>
		<td><div style='font-size:13px;' id="DivFechaEmision"></div></td>
	  

	</tr>
	<tr>
		<td>&nbsp;</td>
		<td></td>
	</tr>
	<tr>
		<td><strong><span style='font-size:13px;' class="Estilo1">G. DE REMISION: </span></strong></td>
		<td><div id="DivGuiaRemision"></div></td>
	</tr>
</table>
<p>
   <table width="440" style="margin:20;">
     <tr>
       <td width="85"><strong><span style='font-size:13px;' class="Estilo1">Se&ntilde;or (es): </span></strong></td>
       <td width="343" align="left"><div style='font-size:13px;' id="DivNombreCliente"></div></td>
       
     </tr>
   </table>
   <table width="440" style="margin:20;">
     <tr>
       <td width="79"><strong><span style='font-size:13px;' class="Estilo1">Direccion: </span></strong></td>
       <td width="349" align="right"><div style='font-size:13px;' id="DivDireccionCliente" align="left"></div></td>
     </tr>
   </table>
   <table width="440" style="margin:20;">
     <tr>
       <td width="79"><strong><span style='font-size:13px;' class="Estilo1">R.U.C. Nº: </span></strong></td>
       <td width="349" align="right"><div style='font-size:13px;' id="DivRUC" align="left"></div></td>
     </tr>
   </table>
   <table width="437" style="margin:20;">
     <tr>
       <td width="59"><strong><span class="Estilo1" style='font-size:13px;'>F. PAGO: </span></strong></td>
       <td width="98" align="right"><div style='font-size:13px;' align="left">
         <div id="DivFP"></div>
       </div></td>
       <td width="92"><strong><div align="right" class="Estilo1" style='font-size:13px;'>N&ordm; CUOTAS :</div></strong></td>
       <td width="30"><div align="center">
         <div style='font-size:13px;' id="DivNumeroCuotas"></div>
       </div></td>
       <td width="60"><strong><div align="right" class="Estilo1" style='font-size:13px;'>INICIAL:</div></strong></td>
       <td width="70"><div align="center">
         <div style='font-size:13px;' id="DivIniciall"></div>
       </div></td>
     </tr>
   </table>
   <div id='DivDetalleVentaa'></div>
   <table width="660" style="margin:20;">
   
   <tr align="left">
	  <td width="462"><div style='font-size:13px;'  align="center" id="DivTotalEnLetras"></div></td>
       <td width="93"><div style='font-size:13px;'  align="right"><strong>SUB TOTAL :</strong></div></td>
       <td width="89" align="right"><div style='font-size:13px;' id="DivSubTotal"></div></td>
     </tr>
	 <tr align="left">
       <td colspan="2"><div style='font-size:13px;' align="right"><strong>I.G.V 19% :</strong></div></td>
       <td width="89" align="right"><div style='font-size:13px;' id="DivIgv"></div></td>
     </tr>
     <tr align="left">
       <td colspan="2"><strong>
       <div style='font-size:12px;' id="DivEtiquetaTotal" align="right">TOTAL :</div></strong></td>
       <td width="89" align="right"><div style='font-size:13px;' id="DivTotall"></div></td>
     </tr>
   </table>
 </div>
 <p align="center"><a href="javascript:imprimir('factura')" class="Estilo16">IMPRIMIR FACTURA </a> </p>
 <p align="center"><a href="<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ echo "list_ventas.php";}?>" class="Estilo16"> REGRESAR </a> </p>
</body>

<?php 
}
?>
</html>