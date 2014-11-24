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

  check_input("txtDescripcion", 1, "La Categoria debe tener una Descripcion");

  check_input("txtAbreviatura", 1, "La Categoria debe tener una Abreviatura");
  
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
<div id="titulo01">MANTENIMIENTO DE CATEGOR&Iacute;A</div>
<?php 
if(isset($_GET['origen'])) {
	if($_GET['origen']=='PROD') {
	$origen=$_GET['origen'].'&accionprod='.$_GET['accionprod'].'&idprod='.$_GET['idprod'];
	}else {$origen='LIST';}	
}else {$origen='LIST';}
?>
<form action=<?php echo '../negocio/cont_categoria.php?accion='.$_GET['accion'].'&origen='.$origen?> method='POST' onSubmit="return check_form(formMantCategoria);" name="formMantCategoria"><input type='hidden' name = 'txtIdCategoria' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['idCategoria'];?>'>
<?php
require("../datos/cado.php");
require("../negocio/cls_categoria.php");
$objCategoria = new clsCategoria();
if($_GET['accion']=='ACTUALIZAR'){
$rst = $objCategoria->buscar($_GET['idCategoria'],'','',null,null,null);
$dato = $rst->fetchObject();
}?>
<BR>
<table width="450" class="tablaint" align="center">
  <tr>
    <td class="alignright">Descripción :</td>
    <td><input type='text' name = 'txtDescripcion' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Descripcion;?>' style="text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Abreviatura :</td>
    <td><input type='text' name = 'txtAbreviatura' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Abreviatura;?>' maxlength="3" size="3" style="text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Categoría Ref:</td>
    <td><select name="cboIdCategoriaRef">
      <option value="null"> Sin Ref </option>
      <?php
$rs = $objCategoria->MostrarArbolCategorias();
while($dato2 = $rs->fetchObject())
{

$seleccionar="";
if($_GET['accion']=='ACTUALIZAR'){if($dato->IdCategoriaRef==$dato2->IdCategoria){$seleccionar="selected";}}
	
	
	echo "<option value='".$dato2->IdCategoria."' ".$seleccionar.">".str_replace(' ','&nbsp;',$dato2->Descripcion)."</option>";
	
}
?>
    </select></td>
  </tr>
  <tr>
    <td class="alignright">Nivel :</td>
    <td><input disabled="disabled" type='text' name = 'txtNivel' value = '<?php if($_GET['accion']=='ACTUALIZAR'){
echo $dato->Nivel;}else{echo "AutoGenerable";}?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
      * depende de CategoriaRef</td>
  </tr>
  <tr>
    <td colspan="2"><?php
/*
echo "ESTADO : ";
echo "<select name='cboEstado'>";
$seleccionar="";
if($_GET['accion']=='ACTUALIZAR'){if($dato->Estado=='A') $seleccionar="selected";}

echo "<option value='N'>NORMAL</option>";
echo "<option value='A' ".$seleccionar.">ANULADO</option>";
echo "</select>";
*/
?></td>
    </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript: <?php if($_GET['origen']=='PROD') echo "window.close();"; else echo "window.open('list_categoria.php','_self');";?>"></th>
  </tr>
</table>
</form>
</body>
</html>