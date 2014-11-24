<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
 header("location: ../presentacion/login.php?error=1");
}
$idusuario=$_SESSION['IdUsuario'];
if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;
if(isset($_SESSION['carro']))
unset($_SESSION['carro']);
require("xajax_almacen.php");
$xajax->printJavascript();?>

<script>
function enlistar(){
<?php 	$fgeneraralmacen->printScript(); ?>
}
function generarinicio(){
	xajax_generaralmaceninicio();
}
/*function ir_a(pagina){
	<?php //$fir_a->printScript();?>
}
function paginar(n){
	<?php //$fpaginar->printScript();?>
}*/
</script>
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<html>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
    <head>
<title>ALMAC&Eacute;N</title>
</head>
<body onLoad="generarinicio()">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">LISTADO ALMAC&Eacute;N</div>
<form id="form1" name="form1" method="post" action="frm_almacen.php">
  <div id='divParametros' style="height:100px;overflow:auto;">
    <fieldset>
      <legend>Almac&eacute;n</legend>
      <table width="923">
      <tr>
        <td class="alignright">Nro. Doc. :</td>
        <td><input name="txtNumero" type="text" id="txtNumero" size="15" maxlength="15"></td>
        <td class="alignright">Fecha Inicial :</td>
        <td><input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value="" size="10">
 <button type="button" id="btnCalendar" class="boton"><img src="../imagenes/b_views.png"> </button><script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td class="alignright">Estado :</td>
        <td><label>
          <select name="cboEstado" id="cboEstado">
            <option selected="selected" value="0">Todos</option>
            <option value="N">Normal</option>
            <option value="A">Anulado</option>
          </select>
        </label></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="77" class="alignright">Tipo Doc. :</td>
        <td width="164"><select name="cboTipoDoc" id="cboTipoDoc">
          <option value="0" selected="selected">Todos</option>
		  <option value="10">Ingreso</option>
          <option value="11">Salida</option>
        </select></td>
        <td width="88" class="alignright">Fecha Final :</td>
        <td width="133"><input name="txtFechaRegistro2" type="text" id="txtFechaRegistro2" value="" size="10"> 
        <button type="button" id="btnCalendar2" class="boton"><img src="../imagenes/b_views.png"> </button></td><script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script>
        <td width="86"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'BUSCAR' onClick="enlistar()"></td>
        <td width="84"><div align="right">
          <input name = 'NUEVO' type='button' id="NUEVO" value = 'NUEVO' onClick="javascript:window.open('frm_almacen.php','_self')">
        </div></td>
        <td width="32">&nbsp;</td>
        <td width="98">&nbsp;</td>
        <td width="37">&nbsp;</td>
        <td width="82">&nbsp;</td>
      </tr>
    </table>
    </fieldset>
  </div>
  
  <fieldset><legend>Lista</legend>
  <div id="divListaAlmacen" style=";<?php if(isset($_GET['accion'])) echo 'overflow:auto';?>">
  
  
  </div>
  
  </fieldset>

</form>
</body>
</html>