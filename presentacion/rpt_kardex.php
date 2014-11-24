<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else{ $idsucursal=1;}

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

require("xajax_kardex.php");
$xajax->printJavascript();
?>

<script>
function generarreporte(){
<?php 	$fgenerareporte->printScript(); ?>
}
function buscar(){
  <?php $flistado->printScript() ?>;
}
function seleccionar(idproducto,moneda){
	xajax_genera_cboUnidad(idproducto,moneda);
}
</script>
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
-->
</style>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css" />
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

</head>

<body>
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">KARDEX VALORIZADO</div>
<?php

function genera_cboCategoria($seleccionado)
{

	require("../negocio/cls_categoria.php");
	$objCategoria = new clsCategoria();
	$rst = $objCategoria->MostrarArbolCategorias();

	echo "<select name='cboCategoria' id='cboCategoria'><option value='0'>TODOS</option>";
	while($registro=$rst->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".str_replace(' ','&nbsp;',$registro[1])."</option>";
	}
	echo "</select>";
}

?>

</div>

 <form id="form1" name="form1" method="post" action="">

<div id='divParametros' style="overflow:auto;">
  <fieldset>
  <legend><strong>Par&aacute;metros de B&uacute;squeda:</strong></legend>
  <table  border="0">
  <tr>
  <td width="68"><span class="alignright">Categoria:</span></td>
  <td width="50"><div id="divbusqueda" class="textoazul">
<input type="hidden" name="pag" id="pag" value="1">
<input type="hidden" name="TotalReg" id="TotalReg">
<?php echo genera_cboCategoria(0);?>
<select name="campo" id="campo" onChange="buscar()" style="display:none">
<option value="producto.descripcion">Descripci&oacute;n</option>
</select>&nbsp;</div></td>
  <td width="41">  <input name="hidden" type="hidden" id="txtIdProductoSeleccionado" value="0"></td>
  <td width="63"><span class="alignright">Producto: </span></td>
  <td width="150"><span class="textoazul">
    <input name="frase" id="frase" onKeyUp="buscar()" />
  </span></td>
  <td width="61" class="alignright">Moneda :</td>
  <td width="105"><select name="cboMoneda" id="cboMoneda" onChange="buscar()">
    <option value="S" selected="selected">SOLES</option>
    <option value="D">DOLARES</option>
  </select></td>
  <td class="alignright">Fecha Inicial :</td>
      <td width="111"><input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value="" size="10" />
        <button type="button" id="btnCalendar" class="boton">...</button>
        <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
<td width="165" rowspan="2"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'GENERAR REPORTE' onClick="generarreporte()" /></td>
            
  </tr><tr><td>&nbsp;</td>
  <td>&nbsp;</td>
  <td></td><td></td><td></td><td></td><td></td><td class="alignright">Fecha Final :</td>
  <td><input name="txtFechaRegistro2" type="text" id="txtFechaRegistro2" value="" size="10" />
      <button type="button" id="btnCalendar2" class="boton">...</button></td><td></td></tr>
    <tr>
      <td colspan="11"><div id="divnumreg" class="registros"></div>
<div id="divregistros" style="height:100px"></div> </td>
      </tr>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script>
  </table>
  
  </fieldset>
</div>

<div id="divReporte"></div>

</form>
</body>
</html>
