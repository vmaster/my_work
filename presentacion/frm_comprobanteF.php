<?php
session_start();

@require("xajax_comprobante.php");
?>

<html>
<head>
<?php $xajax->printJavascript();?>
<script>

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

table.botonera table {
    border-spacing: 0px;
    border-collapse: collapse;
    empty-cells: show;
    width: 100%;
	background-color: #FFFFFF;
}

table.botonera td.puntos {
    height: 3px;
    background: url(../imagenes/background-plkpuntos-hor.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera td.frameTL {
    width: 6px;
    height: 6px;
    padding: 0;
    background: url(../imagenes/esq-formato-interior-izq-sup.gif) no-repeat left top;
	background-color: #FFFFFF;
}

table.botonera td.frameTC {
    padding: 0;
    background: url(../imagenes/background-formato-interior-sup.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera td.frameTR {
    width: 6px;
    height: 6px;
    padding: 0;
    background: url(../imagenes/esq-formato-interior-der-sup.gif) no-repeat right top;
}

table.botonera td.frameBL {
	width: 3px;
	height: 3px;
	padding: 0;
	background: url(../imagenes/esq-formato-interior-izq-inf.gif) no-repeat left bottom;
	background-color: #FFFFFF;
}

table.botonera td.frameBC {
    padding: 0;
    background: url(../imagenes/background-formato-interior-inf.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera td.frameBR {
    width: 6px;
    height: 6px;
    padding: 0;
    background: url(../imagenes/esq-formato-interior-der-inf.gif) no-repeat right bottom;
	background-color: #FFFFFF;
}

table.botonera td.frameCL {
    padding: 0;
    background: url(../imagenes/background-formato-interior-izq.gif) repeat-y;
	background-color: #FFFFFF;
}


table.botonera td.frameC {
    padding: 0;
    
	background-color: #FFFFFF;
}

table.botonera td.frameCR {
    padding: 0;
    background: url(../imagenes/background-formato-interior-der.gif) repeat-y;
	background-color: #FFFFFF;
}

table.botonera td.linkItem {
    height: 25px;
	background-color: #FFFFFF;
}

table.botonera a:link, table.botonera a:active, table.botonera a:visited {
    color: #3F4C69;
    text-decoration: none;
	background-color: #FFFFFF;
}

table.botonera a:hover { 
    color: #C82E28;
    text-decoration: underline;
	background-color: #FFFFFF;
}

.Estilo2 {
	font-size: 24px;
	font-weight: bold;
}
.Estilo4 {font-size: 12px}
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
<body  style="font:Arial, Helvetica, sans-serif;" onLoad="generarFactura(<?php echo $_GET['idventa'];?>);" >

 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <?php

require("../negocio/numAletras.php");
$aaa='';
$aaa=num2letras($_GET['T'], false,false,$_GET['M']);
?>
<input type="hidden" name="numLetra" value="">
<div id='cabeceraSucursal' style="position:absolute; left: 102px; top: 53px; width: 700px; height: 495px; z-index:50">
<br>
<br>
<table width="100%">
<tr>

<td width="60%" align="center"><span class="Estilo2"><div id="DivNombreSucursal">EMPRESAAA</div></span></td>
<td width="40%" align="center"><br>
  <span class="Estilo2"><div id="DivRucSucursal">PUTO RUC</div></span></td>
</tr>
</table>
<br><br><br>
<table width="60%">
<tr>
<td width="100%" align="center"><span class="Estilo4">
  <div id="DivDetalleSucursal"></div></span></td>
</tr>
</table>
</div>
<div id='cabecera' style="position:absolute; left: 102px; top: 53px; width: 700px; height: 495px; z-index:1"   >
<br>
<br>
 <img src="../imagenes/cabeceraFactura.PNG">
</div>
<div id='cabecera2' style="position:absolute; left: 540px; top: 55px; width: 700px; height: 495px; z-index:4"   >
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

 <!--<img src="../imagenes/cabeceraFacturaAbajo.PNG">-->
</div>

 
 <div id='factura' style="position:absolute; left: 102px; top: 53px; width: 700px; height: 495px; z-index:3"   >
   <p>&nbsp;</p>
   <br><br>
   <br><br>
   <p>&nbsp;</p>
   <table align="right" width="230">
	<tr align="right">
	  <td width="93"><div style="font-size:18px" id="DivSerieDoc" align="rigth"></div></td><td width="125"><div style="font-size:18px" id="DivNumeroDoc" align="left"></div></td>
	</tr>
	<tr>
		<td height="29">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td height="29">&nbsp;</td>
		<td><div style='font-size:13px;' id="DivFechaEmision"></div></td>
	  

	</tr>
	
	<tr>
		<td height="38">&nbsp;</td>
		<td><div id="DivGuiaRemision"></div></td>
	</tr>
</table>
<p style="margin:20;"></p>
<br>
   <table width="440" >
     <tr>
       <td width="85">&nbsp;</td>
       <td width="343" align="left"><div style='font-size:13px;' id="DivNombreCliente"></div></td>
       
     </tr>
   </table>
   <p style="margin:10;"></p>
   <table width="440">
     <tr>
       <td width="79">&nbsp;</td>
       <td width="349" align="right"><div style='font-size:13px;' id="DivDireccionCliente" align="left"></div></td>
     </tr>
   </table>
   <p style="margin:10;"></p>
   <table width="440" >
     <tr>
       <td width="79">&nbsp;</td>
       <td width="349" align="right"><div style='font-size:13px;' id="DivRUC" align="left"></div></td>
     </tr>
   </table>
   <p style="margin:5;"></p>
   <table width="430">
     <tr>
       <td width="59">&nbsp;</td>
       <td width="98" align="right"><div style='font-size:13px;' align="left">
         <div id="DivFP"></div>
       </div></td>
       <td width="73"><strong>
         <div align="right" class="Estilo1" style='font-size:13px;'></div>
       </strong></td>
       <td width="41"><div align="center">
         <div style='font-size:13px;' id="DivNumeroCuotas"></div>
       </div></td>
       <td width="68"><strong>
         <div align="right" class="Estilo1" style='font-size:13px;'></div>
       </strong></td>
       <td width="63"><div align="center">
         <div style='font-size:13px;' id="DivIniciall"></div>
       </div></td>
     </tr>
   </table>
   <div id='DivDetalleVentaa'></div>
    <p style="margin:8;"></p>
   <table width="660" >
   <tr align="left">
	  <td width="462"><div style='font-size:13px;'  align="<?php if(strlen($aaa)>48){ $espacioadelante=""; $espacios="&nbsp;&nbsp;&nbsp;&nbsp;"; echo "right";} else{ $espacioadelante="&nbsp;&nbsp;&nbsp;&nbsp;"; $espacios=""; echo "center";}?>" id="DivTotalEnLetras"><?php echo $espacioadelante."".$aaa."".$espacios;?></div></td>
       <td width="93"><div style='font-size:13px;'  align="right"></div></td>
       <td width="89" align="right"><div style='font-size:13px;' id="DivSubTotal"></div></td>
     </tr>
	 <tr align="left">
       <td colspan="2"><div style='font-size:13px;' align="right"></div></td>
       <td width="89" align="right"><div style='font-size:13px;' id="DivIgv"></div></td>
     </tr>
     <tr align="left">
       <td colspan="2"><strong>
       <div style='font-size:12px;' id="DivEtiquetaTotal" align="right"></div>
       </strong></td>
       <td width="89" align="right"><div style='font-size:13px;' id="DivTotall"></div></td>
     </tr>
   </table>
</div>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center"><a href="javascript:imprimir('factura')" class="Estilo16">IMPRIMIR FACTURA </a> </p>
 <p align="center"><a href="<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ if($_GET['origen']=='VENTA') echo "list_ventas.php"; else echo "list_pedidoventa.php";}?>" class="Estilo16"> REGRESAR </a> </p>
 <p align="center">&nbsp;</p>
 <div id='fondoFactura' style="position:absolute; left: 102px; top: 53px; width: 675px; height: 495px; z-index:1 " >
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <br>
   <table width="200" align="right" class="botonera">
    <tr bgcolor="#FFFFFF" ><td width="4" style="filter:alpha(opacity=value)"></td>
    <td style="filter:alpha(opacity=value)"></td><td style="filter:alpha(opacity=value)"></td><td width="5" style="filter:alpha(opacity=value)"></td>
    </tr>
	<tr align="left" bgcolor="#FFFFFF" bordercolor="#FFFFFF" style="filter:alpha(opacity=value)">
		<td height="33" bordercolor="#FFFFFF" style="filter:alpha(opacity=value)"> </td>
		<td width="116" align="right" bgcolor="#FFFFFF" style="filter:alpha(opacity=value)"><strong>
		  <div style='font-size:12px;' id="DivEtiquetaTotal" align="left"></div>
	  </strong></td>
	  <td width="110" align="right" style="filter:alpha(opacity=value)"></td>
		
    <td style="filter:alpha(opacity=value)"></td></tr>
	<tr ><td width="4" class="frameTL"></td>
    <td class="frameTC"></td><td class="frameTC"></td><td width="5" class="frameTR"></td>
    </tr>
	<tr align="left">
		<td height="24" class="frameCL"></td>
		<td width="116" align="right" class="frameC"><strong>
		  <div style='font-size:12px;' id="DivEtiquetaTotal" align="left">FECHA DE EMISION: </div>
	  </strong></td>
	  <td width="110" align="right" class="frameC"></td>
		
      <td class="frameCR"></td></tr>
	<tr align="left">
		<td height="30" class="frameCL"></td>
		<td width="116" align="right" class="frameC"><strong>
		  <div style='font-size:12px;' id="DivEtiquetaTotal" align="left">G. REMISION: </div>
	  </strong></td>
	  <td width="110" align="right" class="frameC"></td>
		
    <td class="frameCR"></td></tr>
	<tr align="left">
		
		
    <td class="frameLR"></td></tr>
	
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBC"></td><td class="frameBR"></td></tr>
	
  </table>
   <table width="440"  class="botonera">
    <tr ><td width="-2" class="frameTL"></td>
    <td class="frameTC"></td><td width="10" class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td>
       <td class="frameC" width="416" style='font-size:13px;'><strong>Se&ntilde;or(es):</strong></td>
       
     <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
</table>


   <p style="margin:5;"></p>
   <table width="440"  class="botonera">
    <tr ><td width="-2" class="frameTL"></td>
    <td class="frameTC"></td><td width="10" class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td>
       <td width="418" class="frameC" style='font-size:13px;'><strong>Direccion:</strong></td>
       <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
</table>
   <p style="margin:5;"></p>
   <table width="439"  class="botonera">
    <tr ><td width="4" class="frameTL"></td>
    <td class="frameTC"></td><td width="10" class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td>
       <td width="416" class="frameC" style='font-size:13px;'><strong>R.U.C. Nº:</strong></td>
       <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
</table>
   <p style="margin:5;"></p>
   <table width="460"  class="botonera">
    <tr ><td width="-2" class="frameTL"></td>
    <td class="frameTC"></td><td width="10" class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td>
       <td width="418" class="frameC" style='font-size:13px;'><strong>T.PAGO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nº CUOTAS: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INICIAL:</strong></td>
       <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
</table>

   <div id='DivDetalleFondo'></div>
   
   <table width="460"  class="botonera" align="left">
    <tr ><td width="-2" class="frameTL"></td>
    <td class="frameTC"></td><td  class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td>
       <td class="frameC" width="450" style='font-size:13px;'><strong>Son:</strong></td>
       
       <td class="frameCR"></td></tr>
    <tr><td class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
</table>


   <table width="190"  align="right" class="botonera">
     <tr bgcolor="#FFFFFF" >
       <td width="4"  ></td>
       <td width="94"  ></td>
       <td width="78"  ></td>
       <td width="4"  c></td>
     </tr>
     <tr >
       <td class="frameTL"></td>
       <td class="frameTC"></td>
       <td class="frameTC"></td>
       <td  class="frameTR"></td>
     </tr>
     <tr align="left">
       <td height="20"  class="frameCL"></td>
       <td  align="right" class="frameC"><strong>
         <div style='font-size:12px;' id="div" align="left">SUB TOTAL : </div>
       </strong></td>
       <td  align="right" class="frameC"></td>
       <td class="frameCR"></td>
     </tr>
	  <tr align="left">
       <td height="20" class="frameCL"></td>
       <td  align="right" class="frameC"><strong>
         <div style='font-size:12px;' id="div" align="left">I.G.V. 19%  : </div>
       </strong></td>
       <td align="right" class="frameC"></td>
       <td class="frameCR"></td>
     </tr>
	 
<tr align="left">
       <td height="21" class="frameCL"></td>
       <td  align="right" class="frameC"><strong>
         <div style='font-size:12px;' id="div" align="left">TOTAL : </div>
       </strong></td>
       <td  align="right" class="frameC"></td>
       <td class="frameCR"></td>
     </tr>
     <tr align="left">
       <td class="frameLR"></td>
     </tr>
     <tr>
       <td height="2" class="frameBL"></td>
       <td class="frameBC"></td>
       <td class="frameBC"></td>
       <td class="frameBR"></td>
     </tr>
   </table>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
   <p>&nbsp;</p>
 </div>
 <p align="center">&nbsp;</p>
</body>
</html>