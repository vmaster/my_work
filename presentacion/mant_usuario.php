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

  check_input("txtLogin", 1, "El usuario debe tener un login");

  check_input("txtPassword", 1, "El usuario debe tener un password");
  
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
<body>
<div id="titulo01">MANTENIMIENTO DE USUARIO</div>
<?php  
require("../datos/cado.php");

function genera_cboUsuario($seleccionado)
{
	require("../negocio/cls_Usuario.php");
	$ObjUsuario = new clsUsuario();
	$consulta = $ObjUsuario->consultarpersonausuario();

	echo "<select name='cboUsuario' id='cboUsuario'>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]." ".$registro[2]."</option>";
	}
	echo "</select>";
}
?>

<form name="formMantUsuario" method="post" action="<?php echo '../negocio/cont_usuario.php?accion='.$_GET['accion']?>" onSubmit="return check_form(formMantUsuario);">
<?php
if($_GET['accion']=='ACTUALIZAR'){
require("../datos/cado.php");
require("../negocio/cls_usuario.php");
$objUsuario = new clsUsuario();
$rst = $objUsuario->buscar($_GET['IdUsuario'],'','');
$dato = $rst->fetchObject();
}
?>
<input name="txtCodigo" type="hidden" id="txtCodigo" value="<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Idusuario;?>">
<table width="400" class="tablaint" align="center">
  <tr>
    <td class="alignright">Usuario: </td>
    <td><?php
if($_GET['accion']=='NUEVO')
{
echo genera_cboUsuario(0);
}
elseif($_GET['accion']=='ACTUALIZAR')
{

echo "<select name='cboUsuario' id='cboUsuario' >
<option value='".
$dato->Idusuario."'>".$dato->nombres." ".$dato->apellidos ."</option>
</select>";
}
?></td>
  </tr>
  <tr>
    <td class="alignright">Login:</td>
    <td><input name="txtLogin" type="text" id="txtLogin" value="<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->US;?>" /></td>
  </tr>
  <tr>
    <td class="alignright">Password:</td>
    <td><input name="txtPassword" type="password" id="txtPassword" value="<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->PW;?>" /></td>
  </tr>
  <tr>
    <td class="alignright">Tipo usuario:</td>
    <td><select name="cboTipoUsuario" id="cboTipoUsuario" >
      <?php
	 require("../datos/cado.php");
	require("../negocio/cls_tipousuario.php");
	$objTipo = new clsTipoUsuario();
	$rst2 = $objTipo->consultar();
	while($cbo = $rst2->fetchObject())
	{
	?>
      <option value="<?php echo $cbo->idtipousuario?>" 
	  <?php 
	  if($dato->Descripcion==$cbo->descripcion){echo "selected='selected'";}
	  ?>
	  ><?php echo $cbo->descripcion ?></option>
      <?php
	}
	?>
    </select></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR' />
      <input type='reset' name = 'cancelar' value='CANCELAR' onclick="javascript:window.open('list_usuario.php','_self')" /></th>
    </tr>
</table>

</form>
</body>