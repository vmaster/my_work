<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISTEMA DE COMERCIALIZACI&oacute;N</title>
<!--CODIGO PARA EL BANNER-->
<style type="text/css">
#banner{float:none;position:relative}
#banner img{position:absolute;top:0;left:0;}
</style>
<script src="../js/all-exe.js" type="text/javascript"></script>
<script type="../text/javascript" src="js/class.Window.js"></script>
<script src="../js/class.Slide.js" type="text/javascript"></script>
<!--FIN CODIGO BANNER-->
<script type="text/javascript">
	window.addEvent("domready",function(){								
			imagenes=$$('#banner img');
			new Asset.images(
				imagenes.map(function(el) { return el.get('src'); }),
				{
				onComplete: function(){
					$('banner').fade("in");
					new ASlide('banner', '#banner img').start();
				}
			});
			
	});
</script>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<script language="javascript"><!--
var form = "";
var submitted = false;
var error = false;
var error_message = "";

function check_input(field_name, field_size, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_select(field_name, field_default, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == field_default) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_form(form_name) {
  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";

  check_input("check_select", 0, "Debe seleccionar una sucursal");
  check_input("txtUsuario", 1, "Debe ingresar el usuario");
  check_input("txtClave", 1, "Debe ingresar la clave");
  check_input("txtTipoCambio", 1, "Debe ingresar el tipo de cambio");

  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
<link href="../css/estiloadmin.css" rel="stylesheet" type="text/css">
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #333333}
-->
</style>
<style type="text/css">
<!--
.Estilo3 {font-family: Arial, Helvetica, sans-serif}
.Estilo4 {
	font-weight: bold;
	font-size: 36px;
}
body,td,th {
	color: #003399;
}
body {
	background-color: #FFFFFF;
}
.Estilo5 {font-size: 24px}
-->
</style>
</head>

<body>
<?php  
require("../datos/cado.php");
function genera_cboSucursal($seleccionado)
{

	require("../negocio/cls_persona.php");
	$ObjPersona = new clsPersona();
	$consulta = $ObjPersona->consultarpersonarol(5,'apellidos','','');

	echo "<select name='cboSucursal' id='cboSucursal'>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}
require("../negocio/cls_tipocambio.php");
$ObjTipoCambio = new clsTipoCambio();
$rs=$ObjTipoCambio->buscarxfecha('CURDATE()');
$dato = $rs->fetchObject();
if(isset($dato->Cambio))
	$cambio=$dato->Cambio;
else
	$cambio='2.85';
?>
<center>
<table width="869" height="369" border="0">
  <tr bgcolor="#DEF5FE">
    <td height="61" colspan="3">
      <center>
      <p>
        <marquee behavior="alternate" width="100%">
        <h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BIENVENIDO AL</h2>
        <h2>SISTEMA DE COMERCIALIZACI&Oacute;N EL XXX</h2>
          </marquee>
         </p>
        </center>
     </td></tr>
      <tr bgcolor="#DEF5FE"><td>
      <p><span class="Estilo5">Productos de la mejor calidad ...</span></p></td><td colspan="2">
      <table cellpadding="0" cellspacing="0"><tr><td><span style=" font-size:14px">Informes al:</span><br>
        <div class="Estilo4" align="center"><b>979368623</b></div>
      <span style=" font-size:11px">LUN - VIE   08:00 - 21:00   &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp; &nbsp;  SAB 09:00 - 21:00&nbsp;&nbsp; </span>
      </td><td width="57" cellspacing="0" cellpadding="1" ><img src="../imagenes/icono_fono.jpg" alt="" title="" width="57" height="60" /></td>
      </tr></table>
  </tr>
  <tr>
    <td width="561" height="21" bgcolor="#DEF5FE"><a href="">INICIO</a>  | <a href="../home.html">QUIENES SOMOS</a>  | <a href="">LINEA DE PRODUCTOS</a> | <a href="">OFERTAS</a> | <a href="">SUGERENCIAS</a></td>
	<td colspan="2" bgcolor="#DEF5FE">&nbsp;&nbsp;&nbsp;</td>
    </tr>
  <tr>
    <td bgcolor="#DEF5FE" valign="top" align="center" height="395"><div id="banner" style="visibility:hidden">
    
    	<a href="#"><img src="../imagenes/banner/foto1.jpg" alt="" title="" width="540" height="395" border="0" /></a>
        <a href="#"><img src="../imagenes/banner/opcion1.jpg" alt="" title="" width="540" height="395" border="0" /></a>
        <a href="#"><img src="../imagenes/banner/opcion2.jpg" alt="" title="" width="540" height="395" border="0" /></a>
        <a href="#"><img src="../imagenes/banner/opcion3.jpg" alt="" title="" width="540" height="395" border="0" /></a>
        <a href="#"><img src="../imagenes/banner/opcion4.jpg" alt="" width="540" height="395" border="0" title="" /></a>
        <a href="#"><img src="../imagenes/banner/opcion5.jpg" alt="" width="540" height="395" border="0" title="" /></a>

    </div>&nbsp;</td>
	<td colspan="2" rowspan="2" bgcolor="#DEF5FE">
<form id="Login" action=<?php echo '../negocio/cont_usuario.php?accion=LOGEO'?> method='POST' onSubmit="return check_form(Login);" name="Login">
<center>
<table width="247" height="169" class="tablaint">
<tr>
	<th colspan="3">Acceso al Sistema</th>
</tr>
<tr>
                <td class="alignright">Sucursal:</td>
        <td><?php echo genera_cboSucursal(0)?></td>
                <td rowspan="5"><img src="../imagenes/acceso.jpg" width="71" height="60"></td>
</tr>
              <tr>
                <td class="alignright">Usuario:</td>
                <td><input name="txtUsuario" type="text" id="txtUsuario" maxlength="25" /></td>
              </tr>
              <tr>
                <td class="alignright">Clave:</td>
                <td><input name="txtClave" type="password" id="txtClave" maxlength="32" /></td>
              </tr>
              <tr>
              <td class="alignright">Fecha:</td>
      <td><input name="txtFechaProceso" type="text" id="txtFechaProceso" value="<?php 
$mysql_date = date("Y-m-d"); 
echo $mysql_date;
?>" size="10">
        <button type="button" id="btnCalendar" class="boton"><img src="../imagenes/b_views.png"> </button>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaProceso", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
              </tr>
              <tr>
                <td class="alignright">Tipo Cambio:</td>
                <td><input name="txtTipoCambio" type="text" id="txtTipoCambio" maxlength="10" size="10" value="<?php echo $cambio;?>" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;" /></td>
              </tr>
              <tr>
                <th colspan="3">
                    <input src="../imagenes/btn_ingresar.png" type="image" name="Submit" value="Logearse" />
                  <br />
                    <?php if($_GET['error']==1) echo "Datos Necesarios";?>                </th>
              </tr>
    </table>
</center>
</form>
    </td>
  </tr>
</table>
</center>
<p class="MsoNormal" align="center" style="text-align:center">  
<span class="Estilo1" style="font-size: 8pt; font-family: Calibri" lang="EN-US">
  </span>
<div align="center" class="Estilo1" id='DivEquipo'>
    <span style="font-size: 8pt; font-family: Calibri" lang="EN-US">Chiclayo - Per&uacute; - 2011 &copy; Ing. Geynen Rossler Montenegro Cochas<br>Totos los derechos reservados</span>
  </div>
</p>
</body>
</html>