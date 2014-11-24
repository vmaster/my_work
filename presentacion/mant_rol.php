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
  form = formMantRol;
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
<form action=<?php echo '../negocio/cont_rol.php?accion='.$_GET['accion']?> method='POST' onSubmit="return check_form(formMantRol);" name="formMantRol">
  <input type='hidden' name = 'txtIdRol' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['IdRol'];?>'>
  <?php
if($_GET['accion']=='ACTUALIZAR'){
require("../datos/cado.php");
require("../negocio/cls_rol.php");
$objRol = new clsRol();
$rst = $objRol->buscar($_GET['IdRol']);
$dato = $rst->fetchObject();
}?>
  <table width="300" class="tablaint">
  <tr>
    <td class="alignright">Descripción:</td>
    <td><input type='text' id='txtDescripcion' name = 'txtDescripcion' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Descripcion;?>' style="text-transform:uppercase"></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_rol.php','_self')"></th>
    </tr>
</table>
</form>
</body>
</html>