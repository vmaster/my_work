<?php
/*session_start();
//if(!isset($_SESSION['usuario'])) die("Acceso denegado");
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}*/
?>
<html>
<head>
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

  check_input("txtNombre", 1, "La empresa debe tener un nombre");
  check_input("txtDireccion", 1, "La empresa debe tener una dirección");
  check_input("txtRuc", 1, "La empresa debe tener un ruc");
  
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
<form action=<?php echo '../negocio/cont_sucursal.php?accion='.$_GET['accion']?> method='POST' onSubmit="return check_form(formMantSucursal);" name="formMantSucursal">
<input type='hidden' name = 'txtIdSucursal' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['IdSucursal'];?>'>
<?php
if($_GET['accion']=='ACTUALIZAR'){
require("../datos/cado.php");
require("../negocio/cls_sucursal.php");
$objSucursal = new clsSucursal();
$rst = $objSucursal->buscar($_GET['IdSucursal']);
$dato = $rst->fetchObject();
}?>
<BR>
<table width="400" class="tablaint">
  <tr>
    <td class="alignright">Nombre :</td>
    <td><input type='text' name = 'txtNombre' id='txtNombre' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Nombre;?>' style="text-transform:uppercase" maxlength="50"></td>
  </tr>
  <tr>
    <td class="alignright">Direcci&oacute;n :</td>
    <td><input type='text' name = 'txtDireccion' id='txtDireccion' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Direccion;?>' style="text-transform:uppercase" maxlength="50" size="50"></td>
  </tr>
  <tr>
    <td class="alignright">RUC :</td>
    <td><input type='text' name = 'txtRuc' id='txtRuc' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Ruc;?>' maxlength="11" size="11" onKeyPress="if (event.keyCode < 47 || event.keyCode > 57) event.returnValue = false;"></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_sucursal.php','_self')"></th>
    </tr>
</table>
</form>
</body>
</html>