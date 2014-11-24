<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?><head>
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

  check_input("txtDescripcion", 1, "El Concepto pago Debe Tener Una Descripcion");

  
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css" />
</head>
<div id="titulo01">MANTENIMIENTO DE CONCEPTO DE PAGO</div>
<form id="form1" name="formMant" method="post" action="<?php echo '../negocio/cont_conceptopago.php?accion='.$_GET['accion']?>" onSubmit="return check_form(formMant);">

    <input name="txtIdConcepto" type="hidden" id="txtIdConcepto" 	 value="<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['IdConcepto']?>"/>

  <table width="400" class="tablaint" align="center">
    <tr>
      <td class="alignright">Descripción:</td>
      <td><input name="txtDescripcion" type="text" id="txtDescripcion"  value="<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['descrip']?>" style="text-transform:uppercase"/></td>
    </tr>
    <tr>
      <td class="alignright">Tipo:</td>
      <td><select name="cboTipo" id="cboTipo">
        <?php
	if($_GET['tip']=='I'){
	echo "<option value='I' selected='selected'>Ingreso</option>
	<option value='E' >Egreso</option>";

  }else if($_GET['accion']=='ACTUALIZAR' && $_GET['tip']=='E'){
	 	echo "<option value='I'>Ingreso</option>
	<option value='E' selected='selected'>Egreso</option>";
	}else if($_GET['accion']=='NUEVO'){
	echo "<option value='I'>Ingreso</option>
	<option value='E'>Egreso</option>";
	}
	 ?>
      </select></td>
    </tr>
    <tr>
      <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR' />
      <input type='reset' name = 'cancelar' value='CANCELAR' onclick="javascript:window.open('list_conceptopago.php','_self')" /></th>
    </tr>
  </table>

</form>
