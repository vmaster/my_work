<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISTEMA DE COMERCIALIZACI&oacute;N</title>
<!--CODIGO PARA EL BANNER-->
<style type="text/css">
@import "../css/login.css";
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
<script language="javascript">//<!--
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
<header style="background-color: orange;">
 <img src="../imagenes/logo.png" width="200px" />
</header>
<body>
<?php  
require("../datos/cado.php");
function genera_cboSucursal($seleccionado)
{

	require("../negocio/cls_persona.php");
	$ObjPersona = new clsPersona();
	$consulta = $ObjPersona->consultarpersonarol(5,'apellidos','','');

	echo "<select name='cboSucursal' id='cboSucursal' class='cboSucursal' style='width:150px !important;'>";
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
	<div id="envoltura">
		<div id="mensaje">Mensaje...</div>
		<div id="contenedor" class="curva">
			<div id="cabecera" class="tac">
				Acceso al Sistema
			</div>
			<div id="cuerpo">
				<form id="Login"
					action=<?php echo '../negocio/cont_usuario.php?accion=LOGEO'?>
					method='POST' onSubmit="return check_form(Login);" name="Login">
							<p><label for="sucursal">Sucursal:</label></p>
							<p class="mb10"><?php echo genera_cboSucursal(0)?></p>
							<p><label for="usuario">Usuario:</label></p>
							<p class="mb10"><input name="txtUsuario" type="text" id="txtUsuario"
									maxlength="25" /></p>
							<p><label for="contrasenia">Clave:</label></p>
							<p class="mb10"><input name="txtClave" type="password" id="txtClave"
									maxlength="32" /></p>
							<div style="display: none">
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
	              </div>
	              <div style="display: none">
	                <td class="alignright">Tipo Cambio:</td>
	                <td><input name="txtTipoCambio" type="text" id="txtTipoCambio" maxlength="10" size="10" value="<?php echo $cambio;?>" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;" /></td>
	              </div>
							<p>
								<input
									type="submit" name="submit" id ="submit" value="Ingresar" class="boton"/> <br /> <?php if($_GET['error']==1) echo "Datos Necesarios";?>
								</p>
				</form>
			</div>
			<div id="pie" class="tac">Sistema para Botica</div>
		</div>
		<!-- fin contenedor -->
		<div id="nota">
			<a href="http://gruposistemas.com" title="¿Necesitas un Sitio Web?">GrupoSistemas</a>
		</div>
	</div>
</body>
<footer>
	<p class="MsoNormal" align="center" style="text-align:center">  
<span class="Estilo1" style="font-size: 8pt; font-family: Calibri" lang="EN-US">
  </span>
<div align="center" class="Estilo1" id='DivEquipo'>
    <span style="font-size: 8pt; font-family: Calibri" lang="EN-US">Chiclayo - Per&uacute; - 2014 &copy; Ing. Geynen Montenegro C. & Hobra Systems SA<br>Totos los derechos reservados</span>
  </div>
</p>
</footer>
</html>