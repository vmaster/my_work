<?php
session_start();
@require("xajax_comprobante.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php $xajax->printJavascript();?>
<script>
function generarBoleta(id){
 xajax_presentarboleta(id);
}
function generarFactura(id){
 xajax_presentarfactura(id);
}
</script>
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
<div id='boleta' style="background: url(../imagenes/boleta.jpg) no-repeat; height: 470px; font-size: 13px;"> 
	<div id="fecha" style="font-size:13px;position: absolute;top: 114px;left: 118px;">
		<div id="diaFecha" style="float: left;"></div><div style="float: left;">/</div>
		<div id="mesFecha" style="float: left;"></div><div style="float: left;">/</div>
		<div id="anioFecha" style="float: left;"></div>
	</div>
	<div id="divNombreCliente" style="font-size:13px;position: absolute;top: 130px;left: 122px;"></div>
	<div id='DivDetalleVenta' style="font-size:13px;position: absolute;top: 170px;left: 87px;"></div>
	<div id="DivTotal" style='font-size:13px;position: absolute;top: 340px;left: 540px;'></div>
</div>

<div align="center"><a href="javascript:imprimir('boleta')">IMPRIMIR BOLETA</a></div>
<div align="center"><a href="<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ if($_GET['origen']=='VENTA') echo "list_ventas.php"; else echo "list_pedidoventa.php";}?>"> REGRESAR </a> </div>
</body>
</html>