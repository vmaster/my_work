<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

if(isset($_SESSION['FechaProceso'])){ $fechaproceso = $_SESSION['FechaProceso'];}
else {$fechaproceso = date("Y-m-d"); $_SESSION['FechaProceso']=date("Y-m-d"); }

$_SESSION['cuotasventa']=false;
if(isset($_SESSION['carroventa']))
$carroventa=$_SESSION['carroventa'];else $carroventa=false;

if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

$tipomovimiento=2;

@require("xajax_venta.php");?>
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

function check_form(form_name) {
 form = form_name;
 
  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

 error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";

error=false;
  
  check_input("txtNombresPersona", 1, "Debe definir el cliente en la venta");

  check_input("txtNombresResponsable", 1, "Debe definir el responsable en la venta");
  
  if(frmVenta.txtTotal.value==0){
  error_message = error_message + "* " + "Debe definir algun producto para vender" + "\n";
  error = true;
  }
  
  if(frmVenta.cboTipoVenta.value=='B'){
  check_input("txtNumCuotas", 1, "Debe definir el numero de cuotas de la venta al credito");
  
  check_input("txtInicial", 1, "Debe definir el aporte inicial de la venta al credito");
  
  check_input("txtInteres", 1, "Debe definir el interes de las cuotas");
  
  	var inicial=frmVenta.txtInicial.value;
	var total=frmVenta.txtTotal.value;
	if(parseFloat(total)<=parseFloat(inicial)){
		error_message=error_message+"* Importe inicial es mayor o igual al total de venta\n";
	}
 }
 
			
  if (error == true) {
  	if(error_message!="Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n"){
	alert(error_message);}
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
   divCuota.style.display="none";
   divImpuesto.style.display="none";
   generarnumero();
   presentarinicio();
   <?php if($_GET['accion']=='ACTUALIZAR'){?>
	 xajax_agregardetallepedido(<?php echo $_GET['IdPedido'];?>);  
   <?php }?>
}

function buscar(){
  <?php $flistado->printScript() ?>;
}

function presentarinicio(){
 xajax_presentarinicio();
}

function verlistaventas(){
<?php 
$freferenciarventas->printScript();
?>
}

function referenciar(idventa){
	xajax_referenciar(idventa);
	divBuscarPersona.style.display="none";
}

function verdetalle(idventa){
	window.open('frm_detalleventa.php?IdVenta='+idventa+'&accion=REFERENCIAR','_blank','width=1000,height=500');	
}

function generarnumero(){
  <?php $fgenerarnumero->printScript() ?>;
  if(frmVenta.cboTipoDocumento.value==2){
  	divGuia.style.display="";
	frmVenta.cGuia.checked=false;
	frmVenta.txtGuia.value='0';
	frmVenta.cboTipoVenta.disabled=false;
  }else{
  	divGuia.style.display="none";
	frmVenta.cGuia.checked=false;
	frmVenta.txtGuia.value='0';
		if(frmVenta.cboTipoDocumento.value==12){
	frmVenta.cboTipoVenta.disabled=true;
	frmVenta.cboTipoVenta.value="A";
	divCuota.style.display="none";
	divNumero.style.display="";
		}else{
	frmVenta.cboTipoVenta.disabled=false;
		}
  }
}


function seleccionar(idproducto,moneda){
	xajax_genera_cboUnidad(idproducto,moneda);
	frmVenta.cboTipoPrecio.value="N";
	frmVenta.txtCantidad.value="1";
}
function cambiaStock(){
  <?php $fcambiaStock->printScript() ?>;
  frmVenta.cboTipoPrecio.value="N";
}
function agregar(){
  <?php $fagregar->printScript() ?>;
  frmVenta.txtCantidad.value="1"
  frmVenta.txtPrecioVenta.value="";
}
function quitar(idproducto){
   xajax_quitar(idproducto,frmVenta.txtImpuesto.value,frmVenta.cboTipoDocumento.value);
}

function cambiarmonedadetalle(){
  <?php $fcambiarmonedadetalle->printScript() ?>;
  buscar();
  frmVenta.txtPrecioVenta.value="";
  asignar();
}

function mostrarBusqueda($origen){
   xajax_verbuscarpersona($origen);
   divBuscarPersona.style.display="";
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

function mostrarcuota(){
   if(frmVenta.cboTipoVenta.value=='B'){
   divCuota.style.display="";
   divNumero.style.display="none";
   frmVenta.txtNumCuotas.value="1";
   frmVenta.txtInteres.value="0";
   }
   if(frmVenta.cboTipoVenta.value=='A'){
   divCuota.style.display="none";
   divNumero.style.display="";
   }
 }
 
function asignar(){
	if(frmVenta.cboTipoDocumento.value=="2"){
	divImpuesto.style.display="";
	}else{
	divImpuesto.style.display="none";
	frmVenta.checkIgv.checked = true;
	}
	if(frmVenta.checkIgv.checked == false){
		frmVenta.txtTotal.value=frmVenta.rTotal.value;
		frmVenta.txtSubtotal.value=frmVenta.rSub.value;
		frmVenta.txtIgv.value=frmVenta.rIgv.value;
		frmVenta.txtImpuesto.value="1";
	}else{
		frmVenta.txtTotal.value=frmVenta.rTotal2.value;
		frmVenta.txtSubtotal.value=frmVenta.rSub2.value;
		frmVenta.txtIgv.value=frmVenta.rIgv2.value;
		frmVenta.txtImpuesto.value="2";
	}
}

function asignarguia(){
	if(frmVenta.cGuia.checked== true){
		frmVenta.txtGuia.value="1";
			
	}else{
		frmVenta.txtGuia.value="0";
	}

}

function cambiarprecio(){
	if(frmVenta.cboTipoPrecio.value=='N'){
		frmVenta.txtPrecioVenta.value=frmVenta.txtPrecioNormal.value;
	}else{
		frmVenta.txtPrecioVenta.value=frmVenta.txtPrecioEspecial.value;
	}

}

function abrircuotas(){
	var numero=frmVenta.txtNumCuotas.value;
	var inicial=frmVenta.txtInicial.value;
	var total=frmVenta.txtTotal.value;
	var interes=frmVenta.txtInteres.value;
	var fecha=frmVenta.txtFechaRegistro.value;
	var error="";
	var mensaje="Antes de entrar a cuotas debe corregir lo siguiente: \n";
	if(numero==""){
	error=error+"* Definir numero de cuotas \n";
	}
	if(inicial==""){
		error=error+"* Definir pago inicial \n";
	}
	if(interes==""){
		error=error+"* Definir Interes \n";
	}
	if(parseFloat(total)<=parseFloat(inicial)){
		error=error+"* Importe inicial es mayor o igual al total de venta\n";
	}
	if(error==""){
		window.open('frm_cuotasventas.php?accion=NUEVO&origen=DOC&numCuota='+numero+'&inicial='+inicial+'&total='+total+'&fecha='+fecha+'&Interes='+interes,'_blank','width=380,height=630');
	}else{
		alert(mensaje+error);
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
<div id="titulo01">REGISTRO DE DOCUMENTOS DE VENTA </div>
<form id="frmVenta" action="../negocio/cont_venta.php?accion=NUEVO&origen=<?php if($_GET['accion']=='NUEVO') echo 'VENTA'; else echo 'PEDIDO';?>" method="POST" onSubmit='return check_form(frmVenta);'>
<table>
<tr>
<td colspan="3">
<fieldset><legend>Datos del documento</legend>
<table width="792" border="0">
  <tr>
    <td width="66" class="alignright">Tipo:. </td>
    <td width="189"><select id="cboTipoVenta" name="cboTipoVenta" onChange="mostrarcuota();">
      <option value="A">Contado</option>
      <option value="B">Credito</option>
     </select>
	 <input type="hidden" name="txtIdUsuario" id="txtIdUsuario" value="<?php
	 if(isset($_SESSION['IdUsuario'])) echo $_SESSION['IdUsuario']; else echo '1';?>">
	 <input type="hidden" name="txtIdSucursal" id="txtIdSucursal" value="<?php echo $idsucursal;?>"></td>
  <td width="33" class="alignright">Doc.:</td>
    <td width="130"><select id="cboTipoDocumento" name="cboTipoDocumento" size="1" onChange="cambiarmonedadetalle();generarnumero()">
      <option value="1">Boleta Venta</option>
      <!-- <option value="2">Factura Venta</option>
      <option value="12">Recibo Venta</option>-->
    </select>      </td>
    <td width="25" class="alignright">N&deg;:      </td>
	
    <td width="159">
	<div id="supernumero">
	<div id='divNumero'>	</div>	</div></td>
	
    <td width="57" class="alignright">Moneda:</td>
    <td width="99"><select id="cboMoneda" name="cboMoneda" onChange="cambiarmonedadetalle();">
      <option value="S">Soles</option>
      <option value="D">Dolares</option>
    </select></td>
    </tr>
  <tr>
    <td class="alignright">Cliente:</td>
    <td><input type='hidden' name = 'txtIdPersona' id="txtIdPersona" value = ''>
        <input type='text' name = 'txtNombresPersona' id="txtNombresPersona" style="text-transform:uppercase"  readonly='true' value = ''>
        <button type="button" class="boton" onClick="mostrarBusqueda(0)">...</button></td>
    <td>&nbsp;</td>
    <td><div id="divImpuestoAyax"><div id="divImpuesto"><input name="checkIgv" type="checkbox" onClick="asignar()" value="checkIgv" checked>
      Incluido IGV</div> </div>
        <input type="hidden" name="txtImpuesto" id="txtImpuesto" value="2" readonly=""></td>
    <td colspan="2"><div id="divGuia" style="display:none"><input type="checkbox" name="cGuia" id="cGuia" value="" onClick="asignarguia();" style="display:none">
<!--Con Guia de Remisi&oacute;n--></div>
<input type="hidden" name="txtGuia" id="txtGuia" value="0"></td>
    <td class="alignright">Fecha:</td>
    <td>
      <input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value=" <?php 
$mysql_date = $fechaproceso; 
echo $mysql_date;
?> " size="10" readonly="true">
      <button type="button" id="btnCalendar" class="boton"><img src="../imagenes/b_views.png"></button>
      <script type="text/javascript">//<![CDATA[
//function vercalendar(a){
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide();},
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//}
      </script></td>
  </tr>
  <tr>
    <td class="alignright">Responsable:</td>
    <td><input type='hidden' name = 'txtIdResponsable' id="txtIdResponsable" value = ''>
      <input type='text' name = 'txtNombresResponsable' id="txtNombresResponsable" style="text-transform:uppercase"  readonly='true' value = ''>
      <button type="button" class="boton" onClick="mostrarBusqueda(3)">...</button></td>
    <td></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="alignright">Venta Ref.</td>
    <td><input type="hidden" name="txtIdMovimientoRef" id="txtIdMovimientoRef" value="0">
      <input type="hidden" name="txtIdSucursalRef" id="txtIdSucursalRef" value="0">
      <input type='text' name = 'txtReferencia' id="txtReferencia" readonly='true' value = ''>
      <button type="button" class="boton" onClick="mostrarBusqueda(10)">...</button>
      <BR><div id="divactualizar"> </div></td>
    <td></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</fieldset></td></tr>
<tr>
<td align="left" colspan="3">
<div id='divBuscarPersona'>
</div>
<script type="text/javascript">//<![CDATA[
function vercalendar(){
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide();},
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
}
</script>

</td>

</tr>
<tr><td colspan="3">

<fieldset><legend>Búsqueda de productos</legend>
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
<tr>
<td colspan="3">
<center>
<fieldset><legend><strong>DATOS DEL PRODUCTO SELECCIONADO:</strong></legend>
<table>
<tr>
<td class="alignright">Producto :</td>
<td><label id="lblProducto" name="lblProducto">...</label></td>
<td class="alignright">Unidad :</td>
<td><div id="DivUnidad"></div><!--Aca se genera el combo unidades y el link para ver las unidades(ponerle imagen: Archivo: xajax_prueba2.php funcion:genera_cboUnidad())--></td>
<td class="alignright">Stock Actual :</td>
<td>
  <input type="text" id="txtStockActual" value="0" size="10" maxlength="10" readonly="readonly"></td></tr>
<tr>
  <td class="alignright">Precio Ofertado:</td>
  <td><select name="cboTipoPrecio" id="cboTipoPrecio" onChange="cambiarprecio()">
    <option value="N">Normal</option>
    <option value="E">Especial</option>
  </select>
  </td><td><input type="text" name="txtPrecioVenta" id="txtPrecioVenta" value="" readonly="" maxlength="10" size="10"  onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;">
    <input type="hidden" name="txtPrecioNormal" id="txtPrecioNormal">
    <input type="hidden" name="txtPrecioEspecial" id="txtPrecioEspecial"></td>
  <td class="alignright">Cantidad:</td>
  <td><input type="text" name="txtCantidad" id="txtCantidad" value="1" maxlength="10" size="10" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;">
    <input name="hidden" type="hidden" id="txtIdProductoSeleccionado" value="0"></td>
  <td><a href="#" onClick="agregar()">Agregar</a></td>
</tr></table>
</fieldset>
</center>
</td></tr>
<tr><td colspan="3">
<fieldset><legend>Detalle del documento</legend>
  <input type="hidden" id="txtDetalle" value="FALSE">
  
  <div id="DivDetalle"></div>
  <br>
   
  </fieldset>
</td></tr>
<tr><td width="27%" class="alignleft">
Comentario:<br>
<textarea name="txtComentario" cols="50" rows="2"></textarea>
</td>
  <td width="21%">
  <div id="supercuota">
  <div id='divCuota' style="height:100px;overflow:auto;">
  <fieldset><legend>Cuotas:</legend>
  <table width="363" border="0">
    <tr>
      <td width="66" class="alignright">Inicial:</td>
      <td width="61"><input name="txtInicial" id="txtInicial" type="text" size="10" value='0' onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
      <td width="54" class="alignright">Interes:</td>
      <td width="164"><label>
        <input name="txtInteres" id="txtInteres" type="txtInteres" size="10" value="0">
      </label></td>
    </tr>
    <tr>
      <td class="alignright">N&deg; Cuotas</td>
      <td><input name="txtNumCuotas" id="txtNumCuotas" type="text" size="5" onKeyPress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"></td>
      <td><input type="hidden" name="txtTipoVenta" id="txtTipoVenta"></td>
      <td><button type="button" class="boton" onClick="abrircuotas();">Ver Cuotas</button></td>
    </tr>
  </table> 
  </fieldset>  
  </div></div>  </td>
  <td>
<fieldset>
  <div align="center">
   
  <input type="submit" name="button" id="button" value="Guardar">
    
    <label>
    <input type="button" name="Submit" value="Cancelar" onClick="javascript:window.open('<?php if($_GET['accion']=='ACTUALIZAR') echo 'list_pedidoventa.php'; else echo 'list_ventas.php';?>','_self')">
    </label>
    <input type="hidden" name="txtCaja">
		<input type="hidden" name="txtValidarSerie" id="txtValidarSerie" value="si">
  </div>
</fieldset></td></tr>
</table>
</form>
</body>
</html>