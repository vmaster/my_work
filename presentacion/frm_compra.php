<?php 
session_start();

if(!isset($_SESSION['Usuario']))
{
            header("location: ../presentacion/login.php?error=1");
}


//$_SESSION['carroCompra']=null;
if(isset($_SESSION['FechaProceso'])){
$fechaproceso = $_SESSION['FechaProceso'];
}else {
$fechaproceso = date("Y-m-d");
$_SESSION['FechaProceso']=date("Y-m-d");
}

if(isset($_SESSION['carroCompra']))
$carro=$_SESSION['carroCompra'];else $carro=false;

if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

if(isset($_SESSION['IdUsuario']))
$idUsuario=$_SESSION['IdUsuario'];else $idUsuario=1;

date_default_timezone_set('America/Bogota');

$tipomovimiento=1;//COMPRA


//require("../negocio/cls_movimiento.php");
//$objMovimiento = new clsMovimiento();
		
//	  $apert=$objMovimiento->comprobarAperturaCaja(1,$idsucursal,date('Y-m-d'));
//	  $aperturaCaja = $apert->fetchObject();


@require("xajax_compra.php");?>
<html>
<head>
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->

<?php $xajax->printJavascript();?>
<script>

function inicio(){
   divBuscarPersona.style.display="none";
   //divCuota.style.display="none";
   divImpuesto.style.display="none";
   divCheckIGV.style.display="none";
   opcCuota.style.display="none";
   divBuscarCompra.style.display="none";
   //frmAlmacen.txtNroDoc.disabled=true;
   //generarnumero();
   presentarinicio();
  
}

function mostrarCompraRef(){
divBuscarCompra.style.display="";
}

function cerrarCompraRef(){
divBuscarCompra.style.display="none";
}

function comprobarNumeroDocumento(){
     <?php $fcomprobarnumero->printScript() ?>; 
}

function comprobarSaldoCaja(){
     <?php $fsaldocaja->printScript() ?>; 
}


//function activarnumero(){
//frmAlmacen.txtNroDoc.disabled=false;
//}

function presentarinicio(){
 xajax_presentarinicio();
  <?php $fcompletarcajausuario->printScript()?>;
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
  frmAlmacen.txtCantidad.value="1"
  frmAlmacen.txtPrecioCompra.value="";
  frmAlmacen.txtPrecioVenta.value="";
  frmAlmacen.txtPrecioVentaE.value="";
  divImpuesto.style.display="";

}
function quitar(idproducto){
   xajax_quitar(idproducto);
   asignarCheck();
}
function cambiarmonedadetalle(){
  
  <?php $fcambiarmonedadetalle->printScript() ?>;
  buscar();
  frmAlmacen.txtPrecioCompra.value="";
  frmAlmacen.txtPrecioVenta.value="";
  frmAlmacen.txtPrecioVentaE.value="";
  divImpuesto.style.display="";
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



function asignarCheck(){
if(frmAlmacen.tdigv.value=='1'){
if(frmAlmacen.Cigv1.checked == false){
igvParaRef.style.display="none";
}
}


	if(frmAlmacen.cboTipoDocumento.value=='4'){
	divCheckIGV.style.display="";
	}else{
	divCheckIGV.style.display="none";
	frmAlmacen.Cigv.checked = true;
	}
	if(frmAlmacen.Cigv.checked == false){
		frmAlmacen.txtTotal.value=frmAlmacen.rTotal.value;
		frmAlmacen.txtSubtotal.value=frmAlmacen.rSub.value;
		frmAlmacen.txtIgv.value=frmAlmacen.rIgv.value;
		frmAlmacen.txtImpuesto.value="1";
		}
	if(frmAlmacen.Cigv.checked == true){
		frmAlmacen.txtTotal.value=frmAlmacen.rTotal2.value;
		frmAlmacen.txtSubtotal.value=frmAlmacen.rSub2.value;
		frmAlmacen.txtIgv.value=frmAlmacen.rIgv2.value;
		frmAlmacen.txtImpuesto.value="2";
		}
	
}

function activarNum(){
	//if(frmAlmacen.txtNombresPersona.value!=''){
		frmAlmacen.txtNroDoc.disabled=false;
	//}
}

function verlistacompras(){
<?php 
$freferenciarcompras->printScript();
?>
}

function verdetalle(idMov){
	window.open('frm_detallecompra.php?IdMov='+idMov+'&accion=REFERENCIAR','_blank','width=1000,height=500');	
}

function referenciar(id){
	xajax_referenciar(id);
	frmAlmacen.txtNroDoc.disabled=false;
	
	asignarCheck();
	//cambiarmonedadetalle();
	divBuscarCompra.style.display="none";

}



function activarOpcCuotas(){
	if(frmAlmacen.FormaPago[1].checked == true){
		opcCuota.style.display="";
	}else{
		opcCuota.style.display="none";
	}
}


function abrircuotas(){
	var numero=frmAlmacen.txtNumCuotas.value;
	var inicial=frmAlmacen.txtInicial.value;
	inicial=inicial.replace(/,/,"");
	var total=frmAlmacen.txtTotal.value;
	total=total.replace(/,/,"");
	//var total=parseFloat(frmAlmacen.txtTotal.value);
	var fecha=frmAlmacen.txtFecha.value;
	var interes=frmAlmacen.txtInteres.value;
	var error="";
	var mensaje="Antes de entrar a cuotas debe corregir lo siguiente: \n";
	if(numero==""){
	error=error+"* Definir numero de cuotas \n";
	}
	if(interes==""){
	error=error+"* Definir Interes \n";
	}
	
	if(inicial==""){
		error=error+"* Definir pago inicial \n";
	}
	if(parseFloat(total)<=parseFloat(inicial)){
		error=error+"* Importe inicial es mayor o igual al total de compra\n";
	}
	if(error==""){
		window.open('frm_cuotascompras.php?accion=NUEVO&origen=DOC&numCuota='+numero+'&inicial='+inicial+'&total='+total+'&fecha='+fecha+'&Interes='+interes,'_blank','width=380,height=630');
	}else{
		alert(mensaje+error);
	}
}

</script>

<script language="javascript"><!--
var form = "frmAlmacen";
var submitted = false;
var error = false;
var error_message = "";



function check_ndocumento(){
if(form.elements['txtExistenciaNumero'].value>0){
error_message = error_message + "* " + "Numero de Documento Esta Siendo Usado" +  "\n";
error=true;
}
}

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

function check_cuotas(){
	var numero=frmAlmacen.txtNumCuotas.value;
	var inicial=frmAlmacen.txtInicial.value;
	inicial=inicial.replace(/,/,"");
	var total=frmAlmacen.txtTotal.value;
	total=total.replace(/,/,"");
	var fecha=frmAlmacen.txtFecha.value;

	if(numero==""){
	message=" Definir numero de cuotas ";
	error_message = error_message + "* " + message + "\n";
	error=true;
	}
	if(inicial==""){
	message2=" Definir pago inicial ";
	error_message = error_message + "* " + message2 + "\n";
	error=true;
	}
	if(parseFloat(total)<=parseFloat(inicial)){
		message3=" Importe inicial es mayor o igual al total de compra";
		error_message = error_message + "* " + message3 + "\n";
		error=true;
	}
}

function aperturaa(){
<?php $faperturacaja->printScript(); ?>
}


function check_form(form_name) {

  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";

  check_input("txtNombresPersona", 1, "Ingrese Proveedor");
  check_input("txtNombresResponsable", 1, "Ingrese Responsable");
  check_input("txtNroDoc", 1, "Ingrese Numero de Documento");
  
  if(frmAlmacen.FormaPago[0].checked == true){
  //aperturaa();
  }
  
  if(frmAlmacen.FormaPago[1].checked == true){
    check_cuotas();
  }
  
  
  comprobarNumeroDocumento();
  check_ndocumento();
  
  if(frmAlmacen.txtTotal.value==0){
  error_message = error_message + "* " + "Debe definir algun producto para Registrar La Compra" + "\n";
  error=true;
  }
  
  
  
  if(frmAlmacen.FormaPago[0].checked == true){
  //aperturaa();
 	 if(frmAlmacen.txtAperturaCaja.value=='0'){
	error_message = error_message +"* "+ " NO HAY APERTURA DE CAJA       \r  (No Se Permite Guardar Documentos Al Contado)"+ "\n";
	error=true;
	}
	
	if(frmAlmacen.txtSaldoCaja.value< Number(frmAlmacen.txtTotal.value.replace( /,/,'') )){
	error_message = error_message +"* "+ " NO HAY SALDO EN CAJA       \r  (No Se Puede Realizar La Compra Al Contado)"+ "\n";
	error=true;
	}
	
	}else{
	if(frmAlmacen.txtSaldoCaja.value< Number(frmAlmacen.txtInicial.value.replace( /,/,'') )){
	error_message = error_message +"* "+ " NO HAY SALDO EN CAJA       \r  (No Se Puede Realizar La Compra con tal Inicial)"+ "\n";
	error=true;
	}
	
	}
  
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>

<?php
function genera_cboCategoria($seleccionado)
{

	require("../negocio/cls_categoria.php");
	$objCategoria = new clsCategoria();
	$rst = $objCategoria->MostrarArbolCategorias();

	echo "<select name='cboCategoria' id='cboCategoria' onChange='buscar();'><option value='0'>Todos...</option>";
	while($registro=$rst->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option  value='".$registro[0]."' ".$seleccionar.">".str_replace(' ','&nbsp;',$registro[1])."</option>";
	}
	echo "</select>";
}



?>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="inicio()">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
  <div id="titulo01">REGISTRO DE COMPRA</div>
<form action="../negocio/cont_compra.php?accion=NUEVO" method="post" name="frmAlmacen" id="frmAlmacen" onSubmit="return check_form(frmAlmacen);" >
<input type="hidden" name="txtAperturaCaja" id="txtAperturaCaja" value="" >

<input type="hidden" name="txtSaldoCaja" id="txtSaldoCaja" value="0" >
<table>

<tr>
<td width="50%">

<input type="hidden" name="idsucursal" value="<?php echo $idsucursal;?>">
<div id="nnn"><input type="hidden" name="txtExistenciaNumero" value="0"></div>
<fieldset><legend>Datos del documento:</legend>
<table border="0">
<tr>
    <td class="alignright">Proveedor:</td>
    <td><input type='hidden' name = 'txtIdPersona' id="txtIdPersona" value = ''>
      <input type='text' name = 'txtNombresPersona' id="txtNombresPersona" style="text-transform:uppercase"  readonly='true' value = '' onBlur="comprobarNumeroDocumento();activarNum();" onChange="activarNum();" >
      <button type="button" class="boton" onClick="mostrarBusqueda(2);">...</button></td>
    <td class="alignright">Responsable:</td>
    <td><input type='hidden' name = 'txtIdResponsable' id="txtIdResponsable" value = '<?php echo $_SESSION['IdUsuario'];?>'>
<input type='text' name = 'txtNombresResponsable' id="txtNombresResponsable" style="text-transform:uppercase"  readonly='true' value = ''><button type="button" class="boton" onClick="mostrarBusqueda(3);">...</button></td>
<td class="alignright">Fecha:</td>
<td><input type="text" name="txtFecha" value="<?php echo $fechaproceso;?>" size="10" onBlur="comprobarNumeroDocumento();"><button type="button" id="btnCalendar" class="boton">...</button>
<script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFecha", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
    </tr>
  <tr>
  <td class="alignright">N&ordm;:</td>
  <input type="hidden" name="txtIdMovimiento" value="0" ><input type="hidden" name="txtIdUsuario" value="<?php echo $idUsuario;?>" >
    <td><input name="txtNroDoc" type="text" size="15" maxlength="15" value="001-000000-<?php echo date('Y')?>" onChange="comprobarNumeroDocumento();"  onBlur="comprobarNumeroDocumento();" disabled="disabled"></td>
    <td class="alignright">Tipo:</td>
    <td><select name="cboTipoDocumento" onChange="asignarCheck();cambiarmonedadetalle();comprobarNumeroDocumento();">
      <option value="3">BOLETA</option>
      <option value="4">FACTURA</option>
    </select></td>
    <td class="alignright">Moneda:</td>
    <td><select id="cboMoneda" name="cboMoneda" onChange="cambiarmonedadetalle()">
      <option value="S">Soles</option>
      <option value="D">Dolares</option>
    </select></td>
  </tr>
  <tr>
  	<td class="alignright" >Doc. Ref.: </td>
  	<td><input type='hidden' name = 'txtSucursalRef' id="txtSucursalRef" value = '0'>
	<input type='hidden' name = 'txtIdMovimientoRef' id="txtIdMovimientoRef" value = '0'>
	<input type='text' name = 'txtDocRef' id="txtDocRef"  readonly='true' value = '' >
  	  <button type="button" class="boton" onClick="mostrarCompraRef();">...</button></td>
	<td></td><td><input type="hidden" name="tdigv" value="0"><div id="igvParaRef"></div><div id="divCheckIGV"><input type="checkbox" name="Cigv" value="1" onClick="asignarCheck();" checked="checked">
	Incluir IGV</div></td>
	<td></td>
  </tr>
</table>
</fieldset>
</td></tr>
<tr>
<td colspan="3">
<div id='divBuscarPersona'>
<fieldset><legend>Búsqueda personas:</legend>
<input type="hidden" name="pagPersona" id="pagPersona" value="1">
<input type="hidden" name="TotalRegPersona" id="TotalRegPersona">
<input type='hidden' name = 'txtOrigenPersona' id="txtOrigenPersona" value = ''>
  <div id="divbusquedaPersona" class="textoazul">   
    <div align="left" class="alignleft">Por:
  <select name="campoPersona" id="campoPersona" onChange="buscarPersona()">
    <option value="CONCAT(apellidos,' ',nombres)">Apellidos y Nombres</option>
	<option value="NroDoc">Nro Doc.</option>
  </select><br>
      Descripción:
  <input name="frasePersona" id="frasePersona" onKeyUp="buscarPersona()">
  <button type="button" class="boton" onClick="window.open('mant_persona.php?accion=NUEVO&origen=DOC','_blank','width=380,height=480');">NUEVO</button>
    </div>
  </div>  
<div id="divnumregPersona" class="registros"></div>
<div id="divregistrosPersona"></div>
</fieldset></div>

<div id='divBuscarCompra' style="height:200px;overflow:auto;">
<fieldset><legend>Búsqueda compras:<a href="#" onClick="cerrarCompraRef();">X</a></legend>
<input type="hidden" name="pagCompra" id="pagCompra" value="1">
<input type="hidden" name="TotalRegCompra" id="TotalRegCompra">
<input type='hidden' name = 'txtOrigenCompra' id="txtOrigenCompra" value = ''>
  <div id="divbusquedaCompra" class="textoazul">   
  <div align="left">
  <table width='400' border='0' class="alignleft">
    <tr width='150'>
      <td>Tipo: <select name='cboFormaPagoRef' id='cboFormaPagoRef'>
	  
        <option value='0'>Todos</option>
        <option value='A'>Contado</option>
        <option value='B'>Credito</option>
      </select>	  </td>
      <td>A partir de: <input name='txtFechaRef' type='text' id='txtFechaRef' size='10' value=''>
	  <button type="button" id='btnCalendarRef' class='boton' onClick='vercalendar()'> <img src='../imagenes/b_views.png'> </button><script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendarRef", "txtFechaRef", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      </tr>
    <tr width='150'>
      <td>Tipo Doc.:<select name='cboTipoDocRef' id='cboTipoDocRef'>
		<option value='0'>Todos</option>
        <option value='3'>Boleta</option>
        <option value='4'>Factura</option>
      </select></td>
             
      <td >Nro.: <input name='txtNumeroRef' type='text' id='txtNumeroRef' size='10' maxlength='10'> 
	  <input name = 'BUSCAR' type='button' id='BUSCAR' value = 'BUSCAR' onClick='verlistacompras()'>
	  </td>
	  </tr>
  </table><BR><div id='divListaCompras'></div>
  <br>
  </div>
  </div>  
<div id="divnumregCompra" class="registros"></div>
<div id="divregistrosCompra"></div>
</fieldset></div>
</td>
</tr>
<tr><td colspan="3">
<fieldset><legend>Búsqueda de productos:</legend>
<div id="divbusqueda" class="textoazul">
<input type="hidden" name="pag" id="pag" value="1">
<input type="hidden" name="TotalReg" id="TotalReg">
<label class="alignleft">&nbsp;Categoria:</label> <?php echo genera_cboCategoria(0);?>
<label class="alignleft">&nbsp;Por:</label>
<select name="campo" id="campo" onChange="document.getElementById('pag').value=1;buscar();">
<option value="producto.descripcion">Descripci&oacute;n</option>
<option value="producto.codigo">C&oacute;digo</option>
</select><label class="alignleft">&nbsp;:</label>
<input name="frase" id="frase" onKeyUp="document.getElementById('pag').value=1;buscar();" onFocus="comprobarNumeroDocumento();">
</div>
<div id="divnumreg" class="registros"></div>
<div id="divregistros" style="height:100px"></div></fieldset>
</td></tr>
<tr><td colspan="3">
<center>
<fieldset><legend>Datos del producto seleccionado:</legend>
<table>
<tr>
<td class="alignright">Producto :</td>
<td><label id="lblProducto" name="lblProducto">...</label></td>
<td class="alignright">Unidad :</td>
<td><div id="DivUnidad"></div><!--Aca se genera el combo unidades y el link para ver las unidades(ponerle imagen: Archivo: xajax_prueba2.php funcion:genera_cboUnidad())--></td>
<td class="alignright">Stock Actual :</td>
<td>
  <input type="text" id="txtStockActual" value="0" size="8" maxlength="10" readonly="readonly"></td></tr>
<tr>
  <td class="alignright">Precio Compra :</td>
  <td><input type="text" name="txtPrecioCompra" id="txtPrecioCompra" value="" maxlength="10" size="8"  onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
  <td class="alignright">Precio Venta Normal :</td>
  <td><input type="text" name="txtPrecioVenta" id="txtPrecioVenta" value="" maxlength="10" size="8"  onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
  <td class="alignright">Precio Venta Especial :</td>
  <td><input type="text" name="txtPrecioVentaE" id="txtPrecioVentaE" value="" maxlength="10" size="8"  onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;">    </td>
  </tr><tr>
    <td class="alignright">Cantidad :</td>
    <td><input type="text" name="txtCantidad" id="txtCantidad" value="1" maxlength="10" size="8" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;">
      <input name="hidden" type="hidden" id="txtIdProductoSeleccionado" value="0"></td>
    <td colspan="2"></td><td colspan="2"><div align="center"><a href="#" onClick="agregar();cambiarmonedadetalle();">Agregar</a></div></td></tr></table>
</fieldset>
</center>
</td></tr>
<tr><td colspan="3">
<fieldset><legend>Detalle del documento:</legend>
  <input type="hidden" id="txtDetalle" value="FALSE">
  
  <div id="DivDetalle"></div>
  <br>
  <div id="divRadioButton"  align="center" >
  	<label>
  		<div id="divImpuesto" align="center">
  		<!--<input name="rbIgv" type="radio" value="S" checked onClick="asignar()">
  		Con IGV
  		<input name="rbIgv" type="radio" value="C" onClick="asignar()">
   	
  		Sin IGV-->
  		</div>
 	</label>
  	<input type="hidden" name="txtImpuesto" id="txtImpuesto" value="2" readonly="">
  </div>
  </fieldset>
</td></tr>
</table>
<table>
<tr><td width="30%" class="alignleft">
Comentario:<br>
<textarea name="txtComentario" cols="50" rows="2"></textarea>
</td>
<td width="30%">
<fieldset>
<legend>Tipo de pago</legend>
<p class="alignleft">
  <input type="radio" name="FormaPago" value="A" checked="checked" onClick="activarOpcCuotas();">
  Contado <br>
  <input type="radio" name="FormaPago" value="B" onClick="activarOpcCuotas();">
  Credito </p>
<blockquote>
<div id="opcCuota" style="position:relative">
<table width="250" border="0">
  <tr>
    <td width="66" class="alignright">Inicial:</td>
    <td width="61"><input name="txtInicial" id="txtInicial" type="text" size="5" value='0' onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
    <td width="109"><label>
      <input type="hidden" name="txtTipoVenta" id="txtTipoVenta">
    </label></td>
  </tr>
  <tr>
    <td class="alignright">N&deg; Cuotas</td>
    <td><input name="txtNumCuotas" type="text" value="1" size="5" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
    <td></td>
  </tr>
  <td class="alignright">Interes: </td>
      <td><input name="txtInteres" id="txtInteres" type="text" size="5" value="0">
      <td><button type="button" class="boton" onClick="abrircuotas();">Ver Cuotas</button></td>
  </tr>
</table>
</div>
</blockquote>  
</fieldset></td>
<td>
<fieldset><legend>Opciones</legend>
  <div align="center">
  <p class="alignleft">
    <input type="checkbox" name="cambioprecios" id="cambioprecios" value="1" >
    Establecer los precios ingresados como Predeterminados
</p>
      <input type="submit" name="button2" id="button2" value="Guardar" onClick="comprobarNumeroDocumento();aperturaa();comprobarSaldoCaja();" onFocus="comprobarNumeroDocumento();aperturaa();comprobarSaldoCaja();" onSelect="comprobarNumeroDocumento();aperturaa();comprobarSaldoCaja();">

    <p><input type="button" name="button" id="button" value="Cancelar" onClick="javascript: window.open('../negocio/cont_compra.php?accion=CANCELAR_COMPRA','_self');"></p>
  </div>
</fieldset>
</td></tr>
</table>
</form>
</body>
</html>