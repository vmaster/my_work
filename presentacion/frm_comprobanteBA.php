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
<div style="background: url(../imagenes/boleta.jpg) no-repeat; height: 430px; font-size: 13px; margin-top: 40px; margin-left: 20px; width: 600px;">
	<div id='boleta' style="padding-top: 72px; padding-left: 90px;"> 
		<div id="fecha" style="font-size:12px; position: relative; top: 28px; left: 20px; width: 300px;">
			<span id="diaFecha"></span><span>/</span>
			<span id="mesFecha"></span><span>/</span>
			<span id="anioFecha"></span>
		</div>
		<div id="divNombreCliente" style="font-size:12px; position: relative; top: 28px; left: 24px; width: 300px;"></div>
		<div id='DivDetalleVenta' style="font-size:12px; position: relative; top: 58px; left: -20px;"></div>
		<div id="DivTotal" style='font-size:12px; position: relative; top: 86px; left: 440px; float:left'></div>
	</div>
</div>
<div align="center"><a href="javascript:imprimir('boleta')">IMPRIMIR BOLETA</a></div>
<div align="center"><a href="<?php if(isset($_GET['REF'])){echo "frm_detalleventa.php?IdVenta=".$_GET['idventa'];}else{ if($_GET['origen']=='VENTA') echo "list_ventas.php"; else echo "list_pedidoventa.php";}?>"> REGRESAR </a> </div>
</body>
</html>