<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else{ $idsucursal=1;}
require("xajax_ABCproveedores.php");
$xajax->printJavascript();?>

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

<body>
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">ABC DE PROVEEDORES </div>
  <?php
require("../datos/cado.php");
function genera_cboZona($seleccionado)
{

	require("../negocio/cls_zona.php");
		$objZona = new clsZona();
		$rst2 = $objZona->consultar();

	echo "<select name='cboZona' id='cboZona'>";
	echo "<option selected='selected' value='0'>TODOS</option>";
	while($registro2=$rst2->fetch())
	{
		echo "<option value='".$registro2[0]."' ".$seleccionar.">".$registro2[1]."</option>";
	}
	echo "</select>";
}

function genera_cboSector($seleccionado)
{

	require("../negocio/cls_sector.php");
		$objSector = new clsSector();
		$rst1 = $objSector->consultar();

	echo "<select name='cboSector' id='cboSector'>";
	echo "<option selected='selected' value='0'>TODOS</option>";
	while($registro1=$rst1->fetch())
	{
		echo "<option value='".$registro1[0]."'>".$registro1[1]."</option>";
	}
	echo "</select>";

}
?>

<div>
<table width="100%">
<tr><td>
  <p class="alignleft">Categoria A : Mayor o Igual al 70% del Total</p>
  <p class="alignleft">Categoria B: Comprendida entre el 30% y 70% del Total</p>
  <p class="alignleft">Categoria C: Menor o Igual 30% del Total</p>
  </td><td><img src="../imagenes/abc.png" /></td>
  </tr></table>
  </div>
<form id="form1" name="form1" method="post" action="">
<div id='divParametros' style="height:100px;overflow:auto;">
  <fieldset>
  <legend><strong>Par&aacute;metros de B&uacute;squeda:</strong></legend>
  <table  border="0">
  <tr>
  <td width="112" class="alignright">Tipo Documento : </td>
  <td><label>
    <select name="cboTipoDoc" id="cboTipoDoc">
      <option value="0" selected="selected">TODOS</option>
	  <option value="3">BOLETA</option>
      <option value="4">FACTURA</option>
    
    </select>
  </label></td>
  <td class="alignright">Forma Pago : </td>
  <td><label>
    <select name="cboFormaPago" id="cboFormaPago">
	<option value="0" selected="selected">TODAS</option>
      <option value="A">CONTADO</option>
      <option value="B">CREDITO</option>
    </select>
  </label></td>
  <td class="alignright">Sector :  </td>
  <td><?php echo genera_cboSector(0)?></td>
  <td></td>
  <td width="64" class="alignright">Moneda : </td>
  <td width="139"><label>
    <select name="cboMoneda" id="cboMoneda">
      <option value="S" selected="selected">SOLES</option>
      <option value="D">DOLARES</option>
    </select>
  </label></td>
  <td></td>
  </tr>
    <tr>
      <td width="112" class="alignright">Fecha Inicial : </td>
      <td width="126"><input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value="" size="10" />
        <button type="button" id="btnCalendar" class="boton">...</button>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
      <td width="93" class="alignright">Fecha Final :</td>
      <td width="131"><input name="txtFechaRegistro2" type="text" id="txtFechaRegistro2" value="" size="10" />
          <button type="button" id="btnCalendar2" class="boton">...</button></td>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script>
      <td width="67" class="alignright">Zona : </td>
      <td width="95"><?php echo genera_cboZona(0)?></td>
      <td width="18">&nbsp;</td>
      <td colspan="2"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'GENERAR REPORTE' onClick="generarreporte()" /></td>
      <td width="70">&nbsp;</td>
    </tr>
  </table>
  </fieldset>
</div>
<div id="divReporte"></div>
</form>
</body>
</html>
