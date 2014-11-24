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

  check_input("txtFormula", 1, "Debe asignar una formula a la nueva lista unidad");

  check_input("txtPrecioCompra", 1, "Debe ingresar un Precio de Compra");

  check_input("txtPrecioVenta", 1, "Debe ingresar un Precio de Venta");

  
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
<div id="titulo01">MANTENIMIENTO DE ASIGNACI&Oacute;N DE UNIDADES</div>
<form action=<?php echo '../negocio/cont_listaunidad.php?accion='.$_GET['accion'].'&IdProducto='.$_GET['IdProducto']?> method='POST' onSubmit="return check_form(formMantListaUnidad);" name="formMantListaUnidad">
  <p>
    <?php
require("../datos/cado.php");
require("../negocio/cls_listaunidad.php");
$objListaUnidad = new clsListaUnidad();
if($_GET['accion']=='ACTUALIZAR'){
$rst = $objListaUnidad->buscar($_GET['IdListaUnidad'],'','');
$dato = $rst->fetchObject();
}else{
require("../negocio/cls_producto.php");
$objProducto = new clsProducto();
$rst = $objProducto->buscar($_GET['IdProducto'],'','','');
$producto = $rst->fetchObject();
}
//require("../negocio/cls_unidad.php");
function genera_cboUnidad($seleccionado,$nombre)
{
	//$ObjUnidad = new clsUnidad();
	global $objListaUnidad;
	$consulta = $objListaUnidad->consultarNOAsignadas($_GET['IdProducto']);
	echo "<select name='cboUnidad' id='cboUnidad' ".$nombre.">";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[1]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

?>
  
  <table width="700" class="tablaint" align="center">
    <tr>
      <td class="alignright">ID Lista Unidad:</td>
      <td><input type="text" name="txtIdListaUnidad"  readonly="" value="<?php if($_GET['accion']=='ACTUALIZAR') echo $dato->idlistaunidad;?>" style="text-transform:uppercase"></td>
    </tr>
    <tr>
      <td class="alignright">Código Producto:</td>
      <td><input type='text' name = 'txtIdProducto' readonly="" value = '<?php if($_GET['accion']=='ACTUALIZAR'){
echo $dato->idproducto;}else{
echo $producto->idproducto;
}?>' style="text-transform:uppercase"></td>
    </tr>
    <tr>
      <td class="alignright">Producto:</td>
      <td><input type='text' name = 'txtProducto' readonly="" value = '<?php if($_GET['accion']=='ACTUALIZAR'){
echo $dato->producto;
}else{
echo $producto->descripcion;
}?>' style="text-transform:uppercase"></td>
    </tr>
    <tr>
      <td class="alignright">Unidad:</td>
      <td><?php 
if($_GET['accion']=='ACTUALIZAR'){
	if($dato->unidad == $dato->unidadbase){
	echo genera_cboUnidad($dato->unidad,"disabled='disabled'");}
	else{
	echo genera_cboUnidad($dato->unidad,"");}
}else{
echo genera_cboUnidad("","");}
?>
que corresponde a
  <input name = 'txtFormula' type='text' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value = '<?php if($_GET['accion']=='ACTUALIZAR'){
echo $dato->formula;echo "'"; if($dato->unidad == $dato->unidadbase){ echo "readonly='";}}?>' size="5">
  <input type="text" name="txtUnidadBase" readonly="" value="<?php if($_GET['accion']=='ACTUALIZAR')echo $dato->unidadbase; else echo $producto->unidadbase;?>" style="text-transform:uppercase">
    (Formula-UnidadBase) 
<input type='hidden' name = 'txtIdUnidadBase' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->idunidadbase; else echo $producto->idunidadbase;?>'></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Ejm: CAJA correponde a 12 UNIDAD </td>
    </tr>
    <tr>
      <td class="alignright">Precio Compra :</td>
      <td><input type='text' name = 'txtPrecioCompra' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->preciocompra;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
    </tr>
    <tr>
      <td class="alignright">Precio venta:</td>
      <td><input type='text' name = 'txtPrecioVenta' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->precioventa;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
    </tr>
    <tr>
      <td class="alignright">Precio Especial: </td>
      <td><input type='text' name = 'txtPrecioEspecial' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->precioventaespecial;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
    </tr>
    <tr>
      <td class="alignright">Moneda :</td>
      <td><select name="cboMoneda" id="cboMoneda">
        <?php if($_GET['accion']=='ACTUALIZAR'){?>
        <option value="S" <?php if($dato->moneda=='S'){?>selected="selected" <?php } ?>>Soles</option>
        <option value="D" <?php if($dato->moneda=='D'){?>selected="selected" <?php } ?>>Dolar</option>
        <?php }else{?>
        <option value="S">Soles</option>
        <option value="D">Dolar</option>
        <?php }
?>
      </select></td>
    </tr>
    <tr>
      <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_listaunidad.php?IdProducto=<?php if($_GET['accion']=='ACTUALIZAR'){echo $dato->idproducto;}else{echo $producto->idproducto;}?>','_self')"></th>
    </tr>
  </table>
</form>
</body>
</html>