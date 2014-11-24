<?php 
session_start();
if(!isset($_SESSION['Usuario'])){
    header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['carroAlmacen']))
$carroAlmacen=$_SESSION['carroAlmacen'];else $carroAlmacen=false;

if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

$idUsuario=$_SESSION['IdUsuario'];

if(isset($_SESSION['FechaProceso'])){
$fechaproceso = $_SESSION['FechaProceso'];
}else {
$fechaproceso = date("Y-m-d");
$_SESSION['FechaProceso']=date("Y-m-d");
}



date_default_timezone_set('America/Bogota');

$tipomovimiento=4;//ALMACEN
$año = date("Y"); 
$idmov=$_GET['IdMov'];
require("xajax_almacen.php");?>
<html>
<head>
<?php $xajax->printJavascript();?>
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

function check_form(frmAlmacen) {
  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

  error = false;
  form = frmAlmacen;
  error_message = "Hay errores en su formulario!\nPor favor, corriga lo siguiente:\n\n";

  check_input("txtNombresPersona", 1, "Debe definir la persona en la operacion");

  check_input("txtNombresResponsable", 1, "Debe definir el responsable en la operacion");

  if(frmAlmacen.txtTotal.value==0){
  error=true;
  error_message = error_message + "* " + "Debe definir algun producto" + "\n";}  
	
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
<script>

function inicio(){
   divBuscarPersona.style.display="none";
   generaNroDoc();
   var accion="<?php echo $_GET['accion']?>";
   if(accion=='ACTUALIZAR'){ 
   agregar_detalles(<?php echo $idmov ?>); 
   } else{
   presentarinicio();
    <?php $fcompletarcajausuario->printScript()?>;

   }
}
function buscar(){
  <?php $flistado->printScript() ?>;
}
function seleccionar(idproducto,moneda){
	xajax_genera_cboUnidad(idproducto,moneda);
}
function cambiaStock(){
  <?php $fcambiaStock->printScript() ?>;
}
function agregar(){
  <?php $fagregar->printScript() ?>;
  frmAlmacen.txtCantidad.value="1";
  frmAlmacen.txtPrecioVenta.value="";
  frmAlmacen.txtPrecioEspecial.value="";
   frmAlmacen.txtPrecioCompra.value="";
  frmAlmacen.txtStockActual.value="";
  lblProducto.innerHTML="...";
  DivUnidad.innerHTML="";
}
function quitar(idproducto){
   xajax_quitar(idproducto);
}
function cambiarmonedadetalle(){
  <?php $fcambiarmonedadetalle->printScript(); ?>;
  buscar();
  txtPrecioVenta.value="";
}
function mostrarBusqueda($origen){
   frmAlmacen.txtOrigenPersona.value=$origen;
   frmAlmacen.frasePersona.value="";
   divBuscarPersona.style.display="";
   divnumregPersona.innerHTML="";
   divregistrosPersona.innerHTML="";
}
function buscarPersona(){
  <?php $flistadopersona->printScript() ?>;
}
function mostrarPersona(id){
   xajax_mostrarPersona(id);
   divBuscarPersona.style.display="none";
}
function mostrarEmpleado(id){
   xajax_mostrarEmpleado(id);
   divBuscarPersona.style.display="none";
}
function presentarinicio(){
 xajax_presentarinicio();
}
function agregar_detalles(idmov){
  xajax_agregar_detalles(idmov);

}
function generaNroDoc(){
  <?php $fgeneraNroDoc->printScript() ?>;
}
function vercheck(){
	if(frmAlmacen.cboTipoDocumento.value=='11'){
	frmAlmacen.cboxPreciosP.style.display="none";
	document.getElementById("lblPreciosP").style.display="none";
	}else{
	frmAlmacen.cboxPreciosP.style.display="";
	document.getElementById("lblPreciosP").style.display="";
	}
}
function preciopredeterminados(){
	if(frmAlmacen.cboxPreciosP.checked== true){
		frmAlmacen.txtPreciosP.value="1";
			
	}else{
		frmAlmacen.txtPreciosP.value="0";
	}
}
</script>
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->

<?php
function genera_cboCategoria($seleccionado)
{

	require("../negocio/cls_categoria.php");
	$objCategoria = new clsCategoria();
	$rst = $objCategoria->MostrarArbolCategorias();

	echo "<select name='cboCategoria' id='cboCategoria'><option value='0'>Todos...</option>";
	while($registro=$rst->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".str_replace(' ','&nbsp;',$registro[1])."</option>";
	}
	echo "</select>";
}
?>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<?php

if($_GET['accion']=="ACTUALIZAR"){
require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
$objMovimiento = new clsMovimiento();
$rst = $objMovimiento->consultaralmacen($idmov,NULL,NULL,0,NULL,NULL,0);
$dato = $rst->fetchObject();}?>	
<body onLoad="inicio()">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">REGISTRO DE DOCUMENTOS DE ALMAC&Eacute;N</div>
<form id="frmAlmacen" action="../negocio/cont_almacen.php?accion=NUEVO" method="post" onSubmit="return check_form(frmAlmacen);" >
<input type="hidden" name="txtIdMovimiento" value="" >
 <input type="hidden" name="txtIdUsuario" value="<?php echo $idUsuario;?>" >
 <input type="hidden" name="txtSucursal" value="<?php echo $idsucursal;?>" >
 <input type="hidden" name="txtIdMovRef" value="<?php if($_GET['accion']=="ACTUALIZAR") echo $dato->idmov; else echo 0; ?>" >
 <input type="hidden" name="txtSucursalRef" value="<?php  if($_GET['accion']=="ACTUALIZAR") echo $dato->sucursal; else echo 0; ?>" >

 <label>
 </label><input type="hidden" name="txtPreciosP" value="" >
 <table>
<tr>
<td width="100%" colspan="2">
<fieldset><legend>Datos del documento:</legend> 
 <p>
   <label class="alignleft"><input name="cboxPreciosP" type="checkbox" id="cboxPreciosP" onClick="preciopredeterminados()">
   <label id="lblPreciosP">Establecer los precios ingresados como predeterminados</label></label></p>
 <table border="0">
  <tr>
  <td class="alignright">N&ordm;:</td>
    <td><div id='divNumero'> </div></td>
    <td class="alignright">Tipo:</td>
    <td><select id="cboTipoDocumento" name="cboTipoDocumento" onChange="vercheck();generaNroDoc();">
      <!--De acuerdo a la tabla TipoDocumento-->
	  
	  <?php if($_GET['accion']=="ACTUALIZAR" and $dato->tipodoc==11)
	  { 
	  echo
      "<option  value='10'>Ingreso</option>
      <option selected='selected' value='11'>Salida</option>";
	  }else{ 
	  echo
      "<option selected='selected' value='10'>Ingreso</option>
      <option value='11'>Salida</option>";
	  }
	  
	  ?>
    </select></td>
    <td class="alignright">Moneda:</td>
    <td><select id="cboMoneda" name="cboMoneda" onChange="cambiarmonedadetalle()">
      
	  <?php if($_GET['accion']=="ACTUALIZAR" and $dato->moneda=='D')
	  { echo
	  "<option value='S'>Soles</option>
      <option selected='selected' value='D'>Dolares</option>";}
	  else{
	  echo
	  "<option selected='selected' value='S'>Soles</option>
      <option value='D'>Dolares</option>";
	  }
	  ?>
    </select></td>
  </tr>
  <tr>
    <td class="alignright">Persona:</td>
    <td><input type='hidden' name = 'txtIdPersona' id="txtIdPersona" value ='<?php if($_GET['accion']=="ACTUALIZAR") echo $dato->idper?>'>
<input type='text' name = 'txtNombresPersona' id="txtNombresPersona" style="text-transform:uppercase"  readonly='true' value = '<?php if($_GET['accion']=="ACTUALIZAR") echo $dato->apersona." ".$dato->persona?>'><button type="button" class="boton" onClick="mostrarBusqueda(0);frasePersona.focus();">...</button></td>
    <td class="alignright">Responsable:</td>
    <td><input type='hidden' name = 'txtIdResponsable' id="txtIdResponsable" value ='<?php if($_GET['accion']=="ACTUALIZAR") echo $dato->idresp; else echo $_SESSION['IdUsuario'];?>'>
<input type='text' name = 'txtNombresResponsable' id="txtNombresResponsable" style="text-transform:uppercase"  readonly='true' value = '<?php if($_GET['accion']=="ACTUALIZAR") echo $dato->aresponsable." ".$dato->responsable?>'><button type="button" class="boton" onClick="mostrarBusqueda(3);frasePersona.focus();">...</button></td>
<td class="alignright">Fecha:</td>
<td><label>
  <input name="txtFechaRegistro" type="text" id="txtFechaRegistro" readonly="true" value=" <?php if($_GET['accion']=="ACTUALIZAR") echo $dato->fecha; else {
echo $fechaproceso;}
?> "><button type="button" id="btnCalendar" class="boton">...</button>
<script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//]]></script>
</label></td>
    </tr>
</table>
</fieldset></td>
</tr><tr>
<td colspan="2">
<div id='divBuscarPersona'>
<fieldset><legend><strong>Búsqueda de personas:</strong></legend>
<input type="hidden" name="pagPersona" id="pagPersona" value="1">
<input type="hidden" name="TotalRegPersona" id="TotalRegPersona">
<input type='hidden' name = 'txtOrigenPersona' id="txtOrigenPersona" value = ''>
  <div id="divbusquedaPersona" class="textoazul">   
    <div align="left" class="alignleft">&nbsp;Por:
  <select name="campoPersona" id="campoPersona" onChange="buscarPersona()">
    <option value="CONCAT(apellidos,' ',nombres)">Apellidos y Nombres</option>
	<option value="NroDoc">Nro Doc.</option>
  </select><br>
      &nbsp;Descripción:
      <input type="text" name="frasePersona" id="frasePersona" onKeyUp="buscarPersona()">
  <button type="button" class="boton" onClick="window.open('mant_persona.php?accion=NUEVO&origen=DOC','_blank','width=404,height=480');">NUEVO</button>
    </div>
  </div>
<div id="divnumregPersona" class="registros"></div>
<div id="divregistrosPersona"></div>
</fieldset></div></td>
</tr>
<tr><td colspan="2">
<fieldset><legend><strong>Búsqueda de produsctos:</strong></legend>
<div id="divbusqueda" class="textoazul">
<input type="hidden" name="pag" id="pag" value="1">
<input type="hidden" name="TotalReg" id="TotalReg">
<label class="alignleft">&nbsp;Categoria:</label> <?php echo genera_cboCategoria(0);?>
<label class="alignleft">&nbsp;Por:</label>
<select name="campo" id="campo" onChange="document.getElementById('pag').value=1;buscar();">
<option value="producto.descripcion">Descripci&oacute;n</option>
<option value="producto.codigo">C&oacute;digo</option>
</select><label class="alignleft">&nbsp;:</label>
<input name="frase" id="frase" onKeyUp="document.getElementById('pag').value=1;buscar();">
</div>
<div id="divnumreg" class="registros"></div>
<div id="divregistros" style="height:100px"></div></fieldset>
</td></tr>
<tr><td colspan="2">
<center>
<fieldset><legend><strong>Datos del producto seleccionado:</strong></legend>
<table>
<tr>
<td width="74" class="alignright">Producto :</td>
<td width="77"><label id="lblProducto" name="lblProducto">...</label></td>
<td width="66" class="alignright">Unidad :</td>
<td width="79"><div id="DivUnidad"></div><!--Aca se genera el combo unidades y el link para ver las unidades(ponerle imagen: Archivo: xajax_prueba2.php funcion:genera_cboUnidad())--></td>
<td width="67" class="alignright">Stock Actual :</td>
<td width="62">
  <input name="txtStockActual" type="text" id="txtStockActual" value="0" size="10" maxlength="10" readonly="readonly"></td></tr>

<tr>
  <td class="alignright">Precio Venta Normal:</td>
  <td><input type="text" name="txtPrecioVenta" id="txtPrecioVenta" value="" maxlength="10" size="10"  onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
  <td class="alignright">Precio Venta Especial: </td>
  <td><input type="text" name="txtPrecioEspecial" id="txtPrecioEspecial" maxlength="10" size="10" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;">
  </td>
  <td class="alignright"> Precio Compra:</td>
  <td><input type="text" name="txtPrecioCompra" id="txtPrecioCompra" value="" maxlength="10" size="10"  onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td></tr>
	<tr>
	  <td class="alignright">Cantidad:</td>
	  <td><input type="text" name="txtCantidad" id="txtCantidad" value="1" maxlength="10" size="10" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;">
	    <input name="hidden" type="hidden" id="txtIdProductoSeleccionado" value="0"></td>
	  <td></td><td><a href="#" onClick="agregar()">Agregar</a></p></td></tr>
</table>
</fieldset>
</center>
</td></tr>
<tr><td colspan="2">
<fieldset><legend>Detalle del documento:</legend>
  <input type="hidden" id="txtDetalle" value="FALSE">
<div id="DivDetalle"></div></fieldset>
</td></tr>
<tr><td class="alignleft">
Comentario:<br>
<textarea name="txtComentario" cols="50" rows="2"></textarea>
</td>
<td>
  <div align="center">
    <input type="submit" name="button" id="button" value="Guardar">
    <input type="button" name="button2" id="button2" value="Cancelar" onClick="javascript:window.open('list_almacen.php','_self')">
  </div></td></tr>
</table>
</form>
</body>
</html>