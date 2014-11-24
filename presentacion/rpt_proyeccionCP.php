<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require("xajax_rptMovCaja.php");
?>
<?php $xajax->printJavascript();?>
<script>
function cambiardoc(){
 <?php $flistadocombo->printScript() ?>;
}
function generarreporte(){
<?php $frptproyeccion->printScript()?>;
}
</script>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 24px;
	font-weight: bold;
	font-family: "Arial Black";
}
.Estilo3 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>

<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<body onLoad="cambiardoc()">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">Reportes de  cobros y pagos</div>
<form name="form1" method="post" action="">
  <fieldset>
  <legend>Parámetros de búsqueda</legend>
    <table width="797">
      <tr>
        <td width="137">&nbsp;</td>
        <td width="188">&nbsp;</td>
        <td width="92">&nbsp;</td>
        <td width="145">&nbsp;</td>
        <td width="84">&nbsp;</td>
        <td width="123">&nbsp;</td>
      </tr>
      <tr>
        <td class="alignright">Tipo De Movimiento:</td>
        <td><select name="cboTipoMov" id="cboTipoMov" onChange="cambiardoc()">
          <option value="0">TODOS</option>
          <option value="1">COMPRA</option>
          <option value="2">VENTA</option>
        </select>        </td>
        <td class="alignright">Fecha Inicial: </td>
        <td><input name="txtFechaInicial" type="text" id="txtFechaInicial" value="" size="10"/>
        <button type="button" id="btnCalendar" class="boton">...</button>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaInicial", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td class="alignright" >Moneda:</td>
    <td><label>
          <select name="cboMoneda" id="cboMoneda">
            <option value="0">TODOS</option>
            <option value="S">SOLES</option>
            <option value="D">DOLARES</option>
        </select>
        </label></td>
      </tr>
      <tr>
        <td class="alignright">Documento:</td>
        <td><div id="divtipodoc"></div></td>
        <td class="alignright">Fecha Final:</td>
        <td><input name="txtFechaFinal" type="text" id="txtFechaFinal" size="10"/>
        <button type="button" id="btnCalendar2" class="boton">...</button>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaFinal", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="6" align="center"><input name="btnGenerarReporte" type="button" id="btnGenerarReporte" value="Generar Reporte" onClick="generarreporte()" /></td>
      </tr>
      <tr>
        <td colspan="6" align="center"><p>&nbsp;</p></td>
      </tr>
  </table>
  </fieldset>
    <div id="divreporte"></div>
</form>
</body>