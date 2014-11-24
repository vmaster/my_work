<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<html>
<head>
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

  check_input("txtDescripcion", 1, "El rol debe tener un nombre");
  
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="titulo01">ASIGNAR ROL</div>
<form action='../negocio/cont_rolpersona.php?accion=<?php echo $_GET['accion']?>&IdPersona=<?php echo $_GET['IdPersona']?>' method='POST' onSubmit="return check_form(formMantRolPersona);" name="formMantRolPersona">
    <p><?php
require("../datos/cado.php");

if($_GET['accion']=='ACTUALIZAR'){
require("../negocio/cls_rolpersona.php");
$objRolPersona = new clsRolPersona();

$rst1=$objRolPersona->consultarrolpersona($_GET['IdPersona'],NULL);
$dato1=$rst1->fetchObject();
}

function genera_cboRol($seleccionado)
{

require("../negocio/cls_rol.php");
	$objRol = new clsRol();
$rst2=$objRol->consultar();

	echo "<select name='cboRol' id='cboRol'>";
	while($registro=$rst2->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
			if($registro[0]<>5){
				echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
			}
	}
	echo "</select>";
}
?>   
      <input type='hidden' name = 'txtIdRolPersona' value = '<?php if($_GET['accion']=='ACTUALIZAR')
	
echo $dato1->IdRolPersona;?>'>
    <input type='hidden' name = 'txtIdPersona' value = '<?php echo $_GET['IdPersona']?>'>
    <BR>
    <table width="300" class="tablaint" align="center">
  <tr>
    <td class="alignright">Rol :</td>
    <td ><?php  if($_GET['accion']=='NUEVO')
{
echo genera_cboRol(0);  }
elseif($_GET['accion']=='ACTUALIZAR')
{
echo genera_cboRol($dato1->IdRolPersona);
}
?></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_rolpersona.php?IdPersona=<?php echo $_GET['IdPersona']?>','_self')"></th>
    </tr>
</table>

  </p>
<p>&nbsp;</p>
</form>
</body>
</html>