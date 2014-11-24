<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else{ $idsucursal=1;}
require("xajax_productosmasvendidos.php");
$xajax->printJavascript();
?>

<script>
function generarreporte(){
<?php 	$fgenerareporte->printScript(); ?>
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
<div id="titulo01">Productos más vendidos</div>
<?php

function genera_cboCategoria($seleccionado)
{
	require("../negocio/cls_categoria.php");
	$objCategoria = new clsCategoria();
	$rst2 = $objCategoria->MostrarArbolCategorias();
	echo "<select name='cboCategoria' id='cboCategoria'>";
	echo "<option value='0' selected='selected'>TODAS</option>";
	while($dato2 = $rst2->fetchObject())
	{
	
		if(trim($dato2->Descripcion)==$seleccionado);
		echo "<option value='".$dato2->IdCategoria."' ".$seleccionar.">";
		echo str_replace(' ','&nbsp;',$dato2->Descripcion);
		echo "</option>";
	}
	echo "</select>";
	global $cnx;
	$cnx=null;
	require("../datos/cado.php");
}

function genera_cboMarca($seleccionado)
{
	require("../negocio/cls_marca.php");
	$objMarca = new clsMarca();
	$rst = $objMarca->consultar();

	echo "<select onChange='buscar()' name='cboMarca' id='cboMarca'>";
	echo "<option value='0' selected='selected'>TODAS</option>";
	while($registro = $rst->fetch())
	{
		
		
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}
?>

</div>

 <form id="form1" name="form1" method="post" action="">

<div id='divParametros' style="height:100px;overflow:auto;">
  <fieldset>
  <legend><strong>Par&aacute;metros de B&uacute;squeda:</strong></legend>
  <table  border="0">
  <tr>
  <td width="100" class="alignright">Fecha Inicial : </td>
  <td><input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value="" size="10" />
    <button type="button" id="btnCalendar" class="boton">...</button>
    <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
  <td class="alignright">Fecha Final :</td>
  <td><input name="txtFechaRegistro2" type="text" id="txtFechaRegistro2" value="" size="10" />
    <button type="button" id="btnCalendar2" class="boton">...</button></td>
  <td>&nbsp;</td>
  <td width="52" class="alignright">Marca : </td>
  <td width="133"><?php echo genera_cboMarca(""); ?>&nbsp;</td>
  <td width="15">&nbsp;</td>
  <td width="127"><label></label></td>
  <td></td>
  </tr>
    <tr>
      <td width="100" class="alignright">Categoria : </td>
      <td width="211"><?php echo genera_cboCategoria("");?>&nbsp;</td>
      <td width="91" class="alignright">Moneda :</td>
      <td width="133"><select name="cboMoneda" id="cboMoneda">
        <option value="S" selected="selected">SOLES</option>
        <option value="D">DOLARES</option>
      </select></td>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script>
      <td width="10">&nbsp;</td>
      <td colspan="2"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'GENERAR REPORTE' onClick="generarreporte()" /></td>
      <td colspan="2">&nbsp;</td>
      <td width="72">&nbsp;</td>
    </tr>
  </table>
  </fieldset>
</div>
<div id="divReporte"></div>
</form>
</body>
</html>
