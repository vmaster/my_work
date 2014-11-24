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

  check_input("txtFecha", 1, "El Tipo de Cambio debe Tener Fecha");

  check_input("txtCambio", 1, "El Tipo de Cambio debe tener Monto(Cambio)");

  
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
<form action=<?php echo '../negocio/cont_tipocambio.php?accion='.$_GET['accion']?> method='POST' onSubmit="return check_form(formTipoCambio);" name="formTipoCambio"><input type='hidden' name = 'txtIdTipoCambio' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['idTipoCambio'];?>'>
<?php
if($_GET['accion']=='ACTUALIZAR'){
require("../datos/cado.php");
require("../negocio/cls_tipocambio.php");
$objTipoCambio = new clsTipoCambio();
$rst = $objTipoCambio->buscar($_GET['idTipoCambio'],'');
$dato = $rst->fetchObject();
}?>
<BR>
<table width="400" class="tablaint">
  <tr>
    <td class="alignright">Fecha:</td>
    <td><input type='text' name = 'txtFecha' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Fecha;?>' style="text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Cambio :</td>
    <td><input type='text' name = 'txtCambio' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Cambio;?>'  onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_tipocambio.php','_self')"></th>
    </tr>
</table>

</form>
</body>
</html>