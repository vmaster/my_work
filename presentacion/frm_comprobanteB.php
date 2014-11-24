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
.Estilo3 {font-size: 36px}
.Estilo5 {font-size: 12px}
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
<body  style="font:Arial, Helvetica, sans-serif;" onLoad="generarBoleta(<?php echo $_GET['idventa'];?>);">
<div id='boletaSucursal' style="position:absolute; left: 106px; top: 57px; width: 510px; height: 500px; z-index:1;">
 <p> 
<br>

  
  <table>
  	<tr>
		<td  align="center"><br>
	  <span class="Estilo3">
	  <div id="DivNombreSucursal"></div></span></td>
	</tr>
  </table>
  <br>
  <br>
  <br>
  <br>
  <table width="100%">
  <tr>
  	<td width="55%" align="center"><span class="Estilo5">
  	  <div id="DivDetalleSucursal"></div></span></td>
	<td width="45%" align="center"><strong><div id="DivRucSucursal"></div></strong></td>
  </tr>
  </table>
  
 </div>
<div id='boleta' style="position:absolute; left: 106px; top: 120px; width: 510px; height: 1px; z-index:2;"> 
  <p>

  <p  style=" margin:10;"><br><br><br>

  </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="500" style="position:relative" >
      <tr>
        <td   width="60">&nbsp;</td>
        <td width="36"><div style='font-size:13px;' id="diaFecha"></div></td>
        <td width="36"><div style='font-size:13px;' id="mesFecha"></div></td>
        <td width="49" ><div style='font-size:13px;' id="anioFecha"></div></td>
        <td width="130"></td>
        <td width="161" align="center"><div style='font-size:18px;' id="div4"><strong></strong></div></td>
      </tr>
    </table>
	<p  style=" margin:12;">
  <table width="500" >
      <tr>
        <td width="60">&nbsp;</td>
        <td width="260" align="left" ><div style='font-size:13px;' id="divNombreCliente"></div></td>
        <td width="90">&nbsp;</td>
        <td width="70" align="left"><div style='font-size:13px;' id="DivDocIdentidad">
          &nbsp;&nbsp;</td>
      </tr>
    </table>
	<br>
	
  <table width="500" >
      <tr>
        <td width="73">&nbsp;</td>
        <td width="446" align="right"><div style='font-size:13px;' id="DivDireccionCliente" align="left"></div></td>
      </tr>
    </table>
	<br>
  <table width="500">
      <tr>
        <td width="75">&nbsp;</td>
        <td width="107" align="right"><div style='font-size:13px;' align="left">
            <div id="DivTP"></div>
        </div></td>
        <td width="88"><div align="right" class="Estilo1" style='font-size:13px;'></div></td>
        <td width="52"><div align="center">
            <div style='font-size:13px;' id="DivNCuotas"></div>
        </div></td>
        <td width="65"><div align="right" class="Estilo1" style='font-size:13px;'></div></td>
        <td width="85"><div align="center">
            <div style='font-size:13px;' id="DivInicial"></div>
        </div></td>
      </tr>
    </table>
  <div id='DivDetalleVenta'></div>
  <p style="margin:13;"></p>
  <table width="500">
      <tr align="left">
        <td width="393"><div style='font-size:12px;' id="div11" align="right"><strong></strong></div></td>
        <td width="95" align="right"><div style='font-size:13px;' id="DivTotal"></div></td>
      </tr>
    </table>
  <p>&nbsp;</p>
</div>
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
<div id='boletaFondo' style="position:absolute; left: 106px; top: 57px; width: 510px; height: 1px;">
    <br>
    <br>
    <p><img src="../imagenes/cabeceraBoleta.PNG" width="528" height="144"></p>
    <table width="500" style="margin:10;" class="botonera">
    <tr ><td class="frameTL"></td><td class="frameTC"></td><td class="frameTR"></td></tr>
    <tr>
    <td class="frameCL"></td><td class="frameC" width="200" align="left"> &nbsp;FECHA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;      / &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;     / </td>
    <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
</table>

  <table width="500" style="margin:10;" class="botonera">
    <tr ><td class="frameTL"></td>
    <td class="frameTC"></td><td class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td><td class="frameC" width="500" align="left"> &nbsp;SR.(ES):&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Doc. Ident.:</td>
    <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
	
</table>


    <table  width="500" style="margin:10;" class="botonera">
    <tr ><td class="frameTL"></td>
    <td class="frameTC"></td><td class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td><td class="frameC" width="500" align="left"> &nbsp;DIRECCION:</td>
    <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
	
</table>

    <table width="500" style="margin:10;" class="botonera">
    <tr ><td class="frameTL"></td>
    <td class="frameTC"></td><td class="frameTR"></td>
    </tr>
    <tr>
    <td class="frameCL"></td><td class="frameC" width="500" align="left"> &nbsp;T. PAGO: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nº Cuotas: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Interes:</td>
    <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBR"></td></tr>
	
</table>


  <p style="margin:15;"></p>
  
  <div id='DivDetalleVentax'></div>
  <br><br><br>
  <table width="190" align="right" class="botonera">
    <tr ><td width="7" class="frameTL"></td>
    <td class="frameTC"></td><td class="frameTC"></td><td width="11" class="frameTR"></td>
    </tr>
	<tr align="left">
		<td class="frameCL"></td>
		<td width="55" align="right" class="frameC"><strong>
		  <div style='font-size:12px;' id="DivEtiquetaTotal" align="right">TOTAL:</div>
	  </strong></td>
	  <td width="87" align="right" class="frameC"></td>
		
    <td class="frameCR"></td></tr>
    <tr><td height="2" class="frameBL"></td>
    <td class="frameBC"></td><td class="frameBC"></td><td class="frameBR"></td></tr>
	
  </table>
 
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
<p align="center"><a href="javascript:imprimir('boleta')" class="Estilo16">IMPRIMIR BOLETA </a> </p>
<p align="center"><a href="<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ if($_GET['origen']=='VENTA') echo "list_ventas.php"; else echo "list_pedidoventa.php";}?>" class="Estilo16"> REGRESAR </a> </p>
</html>