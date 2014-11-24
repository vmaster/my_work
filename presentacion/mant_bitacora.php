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

  check_input("txtFechaHora", 1, "La Bitacora debe tener una Fecha y Hora");
  
  check_input("txtComentario", 1, "La Bitacora debe tener un Comentario");
  
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
<form action=<?php echo "../negocio/cont_bitacora.php?accion=".$_GET['accion'];?> method='POST' onSubmit="return check_form(formMantBitacora);" name="formMantBitacora"><input type='hidden' value='0' name = 'txtIdBitacora'>
<?php
require("../datos/cado.php");
require("../negocio/cls_bitacora.php");
$objBitacora = new clsBitacora();
?>
<BR>
<table width="300" class="tablaint">
  <tr>
    <td class="alignright">Id movimiento :</td>
    <td><select name="txtIdMovimiento">
      <option value="1">COMPRA</option>
      <option value="2">VENTA</option>
      <option value="3">CAJA</option>
      <option value="4">ALMACEN</option>
    </select></td>
  </tr>
  <tr>
    <td class="alignright">Fecha y hora:</td>
    <td><input type="text" name="txtFechaHora" style="text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Comentario :</td>
    <td><input type="text" name="txtComentario" style="text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Usuario:</td>
    <td><?php
//while(){

require("../negocio/cls_usuario.php");
$objUsuario = new clsUsuario();
$rsta = $objUsuario->consultar();
echo "<select name='cboIdUsuario'>";
while($datoo = $rsta->fetchObject())
{
echo "<option value='".$datoo->Idusuario."'>".$datoo->US."</option>";
}
echo "</select>";

?></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
    <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_bitacora.php','_self')"></td>    </tr>
</table>
</form>
</body>
</html>