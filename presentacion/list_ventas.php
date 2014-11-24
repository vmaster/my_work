<?php
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}
if(isset($_SESSION['FechaProceso'])){ $fechaproceso = $_SESSION['FechaProceso'];}
else {$fechaproceso = date("Y-m-d"); $_SESSION['FechaProceso']=date("Y-m-d"); }
if(!isset($_GET['accion'])){
if(isset($_SESSION['IdMovimientoRef']))
unset($_SESSION['IdMovimientoRef']);
if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;
}else{
$idsucursal=NULL;
}

if(isset($_SESSION['IdUsuario'])){$idusuario=$_SESSION['IdUsuario'];}else{$idusuario=1;}
if(isset($_SESSION['carroventa']))
unset($_SESSION['carroventa']);
?>
<?php @require("xajax_venta.php");?>
<?php $xajax->printJavascript();?>
<script>
function enlistar(){
<?php 
	$fgenerarventas->printScript();
?>
}
function generarinicio(){
	xajax_generarventasinicio();
	
}
function vercuotas(idventa){
	window.open('frm_cuotasventas.php?accion=VER&origen=DOC&idventa='+idventa,'_blank','width=380,height=630');
	
}

function activacion(){
frmListaVenta.cboFormaPago.value="F";
if(frmListaVenta.cboVer.value=="1"){
frmListaVenta.cboTipoDoc.value="2";
frmListaVenta.cboTipoDoc.disabled=true;
}else{
frmListaVenta.cboTipoDoc.value="0";
frmListaVenta.cboTipoDoc.disabled=false;
}

}
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
<title>VENTAS</title>
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
<div id="titulo01">LISTADO DE DOCUMENTOS DE VENTA </div>
<form  name="frmListaVenta" id="frmListaVenta" action="" method="POST" onSubmit="return VerificarSerie();">
  <div id='divParametros'>
    <fieldset>
      <legend>Ventas</legend>
      <table width="100%" border="0">
      <tr>
        <td class="alignright">Ver: </td>
        <td><select name="cboVer" id="cboVer" onChange="activacion()">
          <option value="0">Todos..</option>
          <option value="1">Ventas para despacho</option>
          <option value="2">Ventas despachadas</option>
        </select></td>
        <td></td><td></td><td></td><td></td><td></td><td rowspan="3" align="center"><img src="../imagenes/Spreadsheet.png" height="100" width="100"></td>
        </tr>
      <tr>
        <td class="alignright">Forma Pago:</td>
        <td><select name="cboFormaPago" id="cboFormaPago">
            <option value="F">Todos..</option>
            <option value="A">Contado</option>
            <option value="B">Credito</option>
          </select>        </td>
        <td class="alignright">Fecha Inicial:</td>
    <td><input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value="<?php 
//$mysql_date = date("Y-m-d"); 
//echo $mysql_date;
?>" size="10">
            <button type="button" id="btnCalendar" class="boton"><img src="../imagenes/b_views.png"> </button>
          <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td class="alignright">Nro. Doc.:</td>
        <td><input name="txtNumero" type="text" id="txtNumero" size="10" maxlength="10"></td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td width="83" class="alignright">Tipo Doc.:</td>
        <td width="122"><select name="cboTipoDoc" id="cboTipoDoc">
            <option value="0">Todos..</option>
            <option value="1">Boleta Venta</option>
            <option value="2">Factura Venta</option>
			<option value="12">Recibo Venta</option>
        </select></td>
        <td width="83" class="alignright">Fecha Final:</td>
    <td width="111"><input name="txtFechaRegistro2" type="text" id="txtFechaRegistro2" value="<?php 
$mysql_date = $fechaproceso; 
echo $mysql_date;
?>" size="10">
            <button type="button" id="btnCalendar2" class="boton"><img src="../imagenes/b_views.png"> </button>
        <script type="text/javascript">//<![CDATA[
var cal2 = Calendar.setup({
  onSelect: function(cal2) { cal2.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td width="71">&nbsp;</td>
        <td width="63"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'BUSCAR' onClick="enlistar()"></td>
        <td width="98"><input name = 'NUEVO' type='button' id="NUEVO" value = 'NUEVA VENTA' onClick="javascript:window.open('frm_venta.php','_self')"></td>
        </tr>
    </table>
    </fieldset>
  </div>
  <BR>
  <a href="list_guias.php">IR A GUIAS </a>
  <BR>
  <BR>
  <fieldset><legend>Lista</legend>
  <div id="divListaVenta" style="height:350px;" width="">  </div>
  </fieldset>
  <BR>

</form>
</body>
</html>