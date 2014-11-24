<?php
session_start();
/*if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}*/
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else{ $idsucursal=1;}
require("xajax_ventasreporte.php");
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
<body>
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">Reporte de ventas</div>
<form id="form1" name="form1" method="post" action="">
<div id='divParametros' style="height:100px;overflow:auto;">
  <fieldset>
  <legend><strong>Par&aacute;metros de B&uacute;squeda:</strong></legend>
  <table  border="0">
  <tr>
  <td width="92" class="alignright">Fecha Inicial : </td>
  <td><label>
    <input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value="" size="10" />
    <button type="button" id="btnCalendar" class="boton">...</button>
    <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script>
</label></td>
  <td class="alignright">Fecha Final :</td>
  <td><label>
    <input name="txtFechaRegistro2" type="text" id="txtFechaRegistro2" value="" size="10" />
    <button type="button" id="btnCalendar2" class="boton">...</button>
  </label></td>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  <td></td>
  <td width="20">&nbsp;</td>
  <td width="138"><label></label></td>
  <td></td>
  </tr>
    <tr>
   	    <td width="92" class="alignright">Moneda : </td>
      <td width="144"><select name="cboMoneda" id="cboMoneda">
          <option value="S" selected="selected">SOLES</option>
          <option value="D">DOLARES</option>
        </select></td>
      <td width="107" class="alignright">Agrupado por : </td>
      <td width="167"><label>
        <select name="cboAgrupado" id="cboAgrupado">
          <option value="mes">Mes</option>
          <option value="ano">A&ntilde;o</option>
        </select>
      </label></td>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script>
      <td width="141"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'GENERAR REPORTE' onClick="generarreporte();" /></td>
      <td width="43"><label></label></td>
      <td width="15">&nbsp;</td>
      <td colspan="2"><div align="right"></div></td>
      <td width="77">&nbsp;</td>
    </tr>
  </table>
  </fieldset>
</div>
<div id="divReporte"></div>
</form>
</body>
</html>
