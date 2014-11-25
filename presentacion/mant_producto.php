<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<?php
@require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores
require("../datos/cado.php");
require("../negocio/cls_armario.php");
$ObjArmario = new clsArmario();
function genera_cboColumna($armario,$seleccionado)
{
	Global $ObjArmario;
	$consulta = $ObjArmario->buscar($armario,"","");
	$registro=$consulta->fetchObject();
	
	$columnas = "<select name='cboColumna' id='cboColumna'>";
	$i=1;
	while($i<=$registro->totalcolumnas)
	{
		$seleccionar="";
		if($i==$seleccionado) $seleccionar="selected";
		$columnas.= "<option value='".$i."' ".$seleccionar.">".$i."</option>";
		$i++;
	}
	$columnas .= "</select>";
	$columnas=utf8_encode($columnas);
	$objResp=new xajaxResponse();
	$objResp->assign('DivcboColumna','innerHTML',$columnas);
	return $objResp;
}

function genera_cboFila($armario,$seleccionado)
{
	Global $ObjArmario;
	$consulta = $ObjArmario->buscar($armario,"","");
	$registro=$consulta->fetchObject();
	
	$filas = "<select name='cboFila' id='cboFila'>";
	$i=1;
	while($i<=$registro->totalfilas)
	{
		$seleccionar="";
		if($i==$seleccionado) $seleccionar="selected";
		$filas .= "<option value='".$i."' ".$seleccionar.">".$i."</option>";
		$i++;
	}
	$filas .= "</select>";
	$filas=utf8_encode($filas);
	$objResp=new xajaxResponse();
	$objResp->assign('DivcboFila','innerHTML',$filas);
	return $objResp;
}

$fcolumna = & $xajax-> registerFunction('genera_cboColumna');
$fcolumna->setParameter(0,XAJAX_INPUT_VALUE,'cboArmario');
$fcolumna->setParameter(1,XAJAX_INPUT_VALUE,'txtColumna');

$ffila = & $xajax-> registerFunction('genera_cboFila');
$ffila->setParameter(0,XAJAX_INPUT_VALUE,'cboArmario');
$ffila->setParameter(1,XAJAX_INPUT_VALUE,'txtFila');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>
<html>
<head>
<?php $xajax->printJavascript();?>
<script>
function llenarColumnayFila(){
  <?php $fcolumna->printScript() ?>;
  <?php $ffila->printScript() ?>;
}
</script>
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

//  check_input("txtCodigo", 1, "El producto debe tener un codigo");

  check_input("txtDescripcion", 1, "El producto debe tener una descripcion");

  check_input("txtStockSeguridad", 1, "El producto debe tener un Stock Minimo");
  
  check_input("txtPrecioCompra", 1, "Debe definir el precio de compra de la unidad base");
  
  check_input("txtPrecioVenta", 1, "Debe definir el precio de venta de la unidad base");
  
  check_input("txtPeso", 1, "Debe definir el peso");
  
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
<div id="titulo01">MANTENIMIENTO DE PRODUCTO</div>
<form action=<?php echo '../negocio/cont_producto.php?accion='.$_GET['accion']?> method='POST' onSubmit="return check_form(formMantProducto);" name="formMantProducto">
<?php
//require("../datos/cado.php");

if($_GET['accion']=='ACTUALIZAR'){
	
require("../negocio/cls_producto.php");
$objProducto = new clsProducto();
$rst = $objProducto->buscar($_GET['IdProducto'],'','','');
$dato = $rst->fetchObject();
}

function genera_cboCategoria($seleccionado)
{
	require("../negocio/cls_categoria.php");
	$objCategoria = new clsCategoria();
	$rst2 = $objCategoria->MostrarArbolCategorias();
	echo "<select name='cboCategoria' id='cboCategoria'>";
	while($dato2 = $rst2->fetchObject())
	{
	$seleccionar="";
		if(trim($dato2->Descripcion)==$seleccionado) $seleccionar="selected";
		echo "<option value='".$dato2->IdCategoria."' ".$seleccionar.">";
		echo str_replace(' ','&nbsp;',$dato2->Descripcion);
		echo "</option>";
	}
	echo "</select>";
	global $cnx;
	$cnx=null;
	require("../datos/cado.php");
}

function genera_cboMarca($seleccionado)
{
	require("../negocio/cls_marca.php");
	$objMarca = new clsMarca();
	$rst = $objMarca->consultar();

	echo "<select name='cboMarca' id='cboMarca'>";
	while($registro = $rst->fetch())
	{
		$seleccionar="";
		if($registro[1]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

function genera_cboTalla($seleccionado)
{
	require("../negocio/cls_talla.php");
	$objTalla = new clsTalla();
	$rst = $objTalla->consultar();

	echo "<select name='cboTalla' id='cboTalla'>";
	echo "<option value='0'>NINGUNA</option>";
	while($registro = $rst->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

function genera_cboColor($seleccionado)
{
	require("../negocio/cls_color.php");
	$objColor = new clsColor();
	$rst = $objColor->consultar();

	echo "<select name='cboColor' id='cboColor'>";
	echo "<option value='0'>NINGUNA</option>";
	while($registro = $rst->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

require("../negocio/cls_unidad.php");
function genera_cboUnidadBase($seleccionado,$estado)
{
	$ObjUnidad = new clsUnidad();
	$consulta = $ObjUnidad->consultar();

	echo "<select name='cboUnidadBase' id='cboUnidadBase' ".$estado.">";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[1]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

function genera_cboMedidaPeso($seleccionado,$estado)
{
	$ObjUnidad = new clsUnidad();
	$consulta = $ObjUnidad->buscar('','','M');

	echo "<select name='cboMedidaPeso' id='cboMedidaPeso' ".$estado.">";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

function genera_cboArmario($seleccionado)
{
	//require("../negocio/cls_armario.php");
	$ObjArmario = new clsArmario();
	$consulta = $ObjArmario->consultar();

	echo "<select name='cboArmario' id='cboArmario' onChange='llenarColumnayFila()'>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[2]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[2]."</option>";
	}
	echo "</select>";
}
?>
<table class="tablaint" align="center">
<!--<tr>
<td>
ID Producto :</td><td>-->
<input type="hidden" name = 'txtIdProducto' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->idproducto;?>' readonly="" style="text-transform:uppercase"><!--</td>
</tr>-->
<tr>
<td class="alignright">
Código:</td><td> <?php if($_GET['accion']=='NUEVO') {echo 'Se generara autom&aacute;ticamente';} else {?><input type='text' name = 'txtCodigo' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->codigo;?>' style="text-transform:uppercase"><?php }?></td>
</tr>
<tr>
<td class="alignright">
Descripción :</td><td> <input type='text' name = 'txtDescripcion' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->descripcion;?>' style="text-transform:uppercase"></td></tr>
<tr><td class="alignright">
Categoría :</td><td> <?php 
if($_GET['accion']=='ACTUALIZAR')
echo genera_cboCategoria($dato->categoria);
else
echo genera_cboCategoria("");

?>&nbsp;<a href="#" onClick="javascript: window.open('mant_categoria.php?accion=NUEVO&origen=PROD&accionprod=<?php echo $_GET['accion'];?>&idprod=<?php echo $_GET['IdProducto'];?>','_blank','width=420,height=480');" title="Nueva Categor&iacute;a">...</a></td></tr>
<tr><td class="alignright">
Marca : </td><td><?php 
if($_GET['accion']=='ACTUALIZAR')
echo genera_cboMarca($dato->marca);
else
echo genera_cboMarca("");

?>
&nbsp;<a href="#" onClick="javascript: window.open('mant_marca.php?accion=NUEVO&origen=PROD&accionprod=<?php echo $_GET['accion'];?>&idprod=<?php echo $_GET['IdProducto'];?>','_blank','width=420,height=160');" title="Nueva Marca">...</a>
</td></tr>
<!--
<tr><td class="alignright">
Color : </td><td><?php 
if($_GET['accion']=='ACTUALIZAR')
echo genera_cboColor($dato->idcolor);
else
echo genera_cboColor("");

?>
&nbsp;<a href="#" onClick="javascript: window.open('mant_color.php?accion=NUEVO&origen=PROD&accionprod=<?php echo $_GET['accion'];?>&idprod=<?php echo $_GET['IdProducto'];?>','_blank','width=420,height=160');" title="Nueva Color">...</a>
</td></tr>
<tr><td class="alignright">
Talla : </td><td><?php 
if($_GET['accion']=='ACTUALIZAR')
echo genera_cboTalla($dato->idtalla);
else
echo genera_cboTalla("");

?>
&nbsp;<a href="#" onClick="javascript: window.open('mant_talla.php?accion=NUEVO&origen=PROD&accionprod=<?php echo $_GET['accion'];?>&idprod=<?php echo $_GET['IdProducto'];?>','_blank','width=420,height=160');" title="Nueva Talla">...</a>
</td></tr>
-->
<tr><td class="alignright">
Unidad base : </td><td>
<?php 
if($_GET['accion']=='ACTUALIZAR'){
require("../negocio/cls_listaunidad.php");
$objListaUnidad = new clsListaUnidad();
$rst = $objListaUnidad->buscar('',$dato->idproducto,'');
$a=$rst->rowCount();
$dato2 = $rst->fetchObject();
}
if($_GET['accion']=='NUEVO' || ($_GET['accion']=='ACTUALIZAR' && $a<2)){?>
<?php 
if($_GET['accion']=='ACTUALIZAR')
echo genera_cboUnidadBase($dato->unidadbase,"");
else
echo genera_cboUnidadBase("","");
?>
 &nbsp;<a href="#" onClick="javascript: window.open('mant_unidad.php?accion=NUEVO&origen=PROD&accionprod=<?php echo $_GET['accion'];?>&idprod=<?php echo $_GET['IdProducto'];?>','_blank','width=420,height=230');" title="Nueva Unidad de Medida">...</a>
 </td></tr>
<tr>
  <td class="alignright">Precio compra:</td>
  <td><input type='text' name = 'txtPrecioCompra' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato2->preciocompra;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
</tr>
<tr>
  <td class="alignright">Precio venta normal:</td>
  <td><input type='text' name = 'txtPrecioVenta' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato2->precioventa;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
</tr>
<tr>
  <td class="alignright">Precio venta especial:</td>
  <td><input type='text' name = 'txtPrecioEspecial' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato2->precioventaespecial;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
</tr>
<tr>
  <td class="alignright">Moneda :</td>
  <td><select name="cboMoneda" id="cboMoneda">
  <?php if($_GET['accion']=='ACTUALIZAR'){?>
  <option value="S" <?php if($dato2->moneda=='S'){?>selected="selected" <?php } ?>>Soles</option>
  <option value="D" <?php if($dato2->moneda=='D'){?>selected="selected" <?php } ?>>Dolar</option>
  <?php }else{?>
  <option value="S">Soles</option>
  <option value="D">Dolar</option>
  <?php }
?>
</select> </td>
 <?php }else{
 echo genera_cboUnidadBase("","disabled='disabled'");
 }?>
</tr>
<tr>
  <td class="alignright">Peso :</td>
  <td><input type='text' name = 'txtPeso' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->peso;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
</tr>
<tr>
  <td class="alignright">Medida :</td>
  <td><?php 
if($_GET['accion']=='ACTUALIZAR')
echo genera_cboMedidaPeso($dato->idmedidapeso,"");
else
echo genera_cboMedidaPeso("","");
?></tr>
<tr><td class="alignright">
Stock seguridad:</td><td> <input type='text' name = 'txtStockSeguridad' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->stockseguridad;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
</td></tr>
<tr><td class="alignright">
Armario :</td><td>
 <?php 
if($_GET['accion']=='ACTUALIZAR')
 echo genera_cboArmario($dato->armario);
 else
 echo genera_cboArmario("");
 
 ?>
 &nbsp;<a href="#" onClick="javascript: window.open('mant_armario.php?accion=NUEVO&origen=PROD&accionprod=<?php echo $_GET['accion'];?>&idprod=<?php echo $_GET['IdProducto'];?>','_blank','width=420,height=230');" title="Nuevo Armario">...</a>
 </td></tr>
<tr>
<td class="alignright">Columna: <input type="hidden" name="txtColumna" id="txtColumna" value="<?php echo $dato->columna?>"></td><td>
<div id="DivcboColumna"></div></td></tr>
<tr>
<td class="alignright">Fila : <input type="hidden" name="txtFila" id="txtFila" value="<?php echo $dato->fila?>"></td><td>
<div id="DivcboFila"></div></td></tr>
<script>llenarColumnayFila();</script>
<!--
<tr><td class="alignright">
Kardex :</td><td> 
<select name="cboKardex" id="cboKardex">
<?php if($_GET['accion']=='ACTUALIZAR'){?>
  <option value="S" <?php if($dato->kardex=='S'){?>selected="selected" <?php } ?>>SI</option>
  <option value="N" <?php if($dato->kardex=='N'){?>selected="selected" <?php } ?>>NO</option>
  <?php }else{?>
  <option value="S">SI</option>
  <option value="N">NO</option>
  <?php }?>
</select>
</td></tr>
-->
<tr><td class="alignright">
Receta Medica <?php echo $dato->recetamedica;?>:</td><td> 
<select name="cboRecetaMedica" id="cboRecetaMedica">
<?php if($_GET['accion']=='ACTUALIZAR'){?>
  <option value="S" <?php if($dato->recetamedica=='N'){?>selected="selected" <?php } ?>>NO</option>
  <option value="N" <?php if($dato->recetamedica=='S'){?>selected="selected" <?php } ?>>SI</option>
  <?php }else{?>
  <option value="N">NO</option>
  <option value="S">SI</option>
  <?php }?>
</select>
</td></tr>
<tr>
  <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
    <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_producto.php','_self')"></th>
  </tr>
</table>
</form>
</body>
</html>