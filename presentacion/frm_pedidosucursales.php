<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

if(isset($_SESSION['FechaProceso'])){ $fechaproceso = $_SESSION['FechaProceso'];}
else {$fechaproceso = date("Y-m-d"); $_SESSION['FechaProceso']=date("Y-m-d"); }

$_SESSION['carropedido']=false;
if(isset($_SESSION['carropedido']))
$carroventa=$_SESSION['carroventa'];else $carroventa=false;

if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

$tipomovimiento=5;

@require("xajax_pedido.php");?>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
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
  
  if(frmPedido.txtTotal.value==0){
  error_message = error_message + "* " + "Debe definir algun producto para vender" + "\n";
  error = true;
  }
  
  if(frmPedido.cboTipoVenta.value=='B'){
  check_input("txtNumCuotas", 1, "Debe definir el numero de cuotas de la venta al credito");
  
  check_input("txtInicial", 1, "Debe definir el aporte inicial de la venta al credito");
  
  check_input("txtInteres", 1, "Debe definir el interes de las cuotas");
  
  	var inicial=frmPedido.txtInicial.value;
	var total=frmPedido.txtTotal.value;
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
}

function buscar(){
	if(document.getElementById('txtIdPersona').value==''){
		alert('Debe indicar una sucursal de origen');
		document.getElementById('btnPersona').focus();
	}else{
  <?php $flistadoPedidoSucursal->printScript() ?>;
	}
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
  if(frmPedido.cboTipoDocumento.value==2){
  	divGuia.style.display="";
	frmPedido.cGuia.checked=false;
	frmPedido.txtGuia.value='0';
	frmPedido.cboTipoVenta.disabled=false;
  }else{
  	divGuia.style.display="none";
	frmPedido.cGuia.checked=false;
	frmPedido.txtGuia.value='0';
		if(frmPedido.cboTipoDocumento.value==12){
	frmPedido.cboTipoVenta.disabled=true;
	frmPedido.cboTipoVenta.value="A";
	divCuota.style.display="none";
	divNumero.style.display="";
		}else{
	frmPedido.cboTipoVenta.disabled=false;
		}
  }
}


function seleccionar(idproducto,moneda,idsucursalorigen){
	xajax_genera_cboUnidadPedidoSucursal(idproducto,moneda,idsucursalorigen);
	frmPedido.cboTipoPrecio.value="N";
	frmPedido.txtCantidad.value="1";
}
function cambiaStockPedidoSucursal(){
  <?php $fcambiaStockPedidoSucursal->printScript() ?>;
  frmPedido.cboTipoPrecio.value="N";
}
function agregar(){
  <?php $fagregar->printScript() ?>;
  frmPedido.txtCantidad.value="1"
  frmPedido.txtPrecioVenta.value="";
}
function quitar(idproducto){
   xajax_quitar(idproducto,frmPedido.txtImpuesto.value,frmPedido.cboTipoDocumento.value);
}

function cambiarmonedadetalle(){
  <?php $fcambiarmonedadetalle->printScript() ?>;
  buscar();
  frmPedido.txtPrecioVenta.value="";
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
   if(frmPedido.cboTipoVenta.value=='B'){
   divCuota.style.display="";
   divNumero.style.display="none";
   frmPedido.txtNumCuotas.value="1";
   frmPedido.txtInteres.value="0";
   }
   if(frmPedido.cboTipoVenta.value=='A'){
   divCuota.style.display="none";
   divNumero.style.display="";
   }
 }
 
function asignar(){
	if(frmPedido.cboTipoDocumento.value=="2"){
	divImpuesto.style.display="";
	}else{
	divImpuesto.style.display="none";
	frmPedido.checkIgv.checked = true;
	}
	if(frmPedido.checkIgv.checked == false){
		frmPedido.txtTotal.value=frmPedido.rTotal.value;
		frmPedido.txtSubtotal.value=frmPedido.rSub.value;
		frmPedido.txtIgv.value=frmPedido.rIgv.value;
		frmPedido.txtImpuesto.value="1";
	}else{
		frmPedido.txtTotal.value=frmPedido.rTotal2.value;
		frmPedido.txtSubtotal.value=frmPedido.rSub2.value;
		frmPedido.txtIgv.value=frmPedido.rIgv2.value;
		frmPedido.txtImpuesto.value="2";
	}
}

function asignarguia(){
	if(frmPedido.cGuia.checked== true){
		frmPedido.txtGuia.value="1";
			
	}else{
		frmPedido.txtGuia.value="0";
	}

}

function cambiarprecio(){
	if(frmPedido.cboTipoPrecio.value=='N'){
		frmPedido.txtPrecioVenta.value=frmPedido.txtPrecioNormal.value;
	}else{
		frmPedido.txtPrecioVenta.value=frmPedido.txtPrecioEspecial.value;
	}

}

function abrircuotas(){
	var numero=frmPedido.txtNumCuotas.value;
	var inicial=frmPedido.txtInicial.value;
	var total=frmPedido.txtTotal.value;
	var interes=frmPedido.txtInteres.value;
	var fecha=frmPedido.txtFechaRegistro.value;
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
		window.open('frm_carropedidos.php?accion=NUEVO&origen=DOC&numCuota='+numero+'&inicial='+inicial+'&total='+total+'&fecha='+fecha+'&Interes='+interes,'_blank','width=380,height=630');
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
<div id="titulo01">REGISTRO DE SOLICITUD DE PEDIDO ENTRE SUCURSALES</div>
<form id="frmPedido" action="../negocio/cont_pedido.php?accion=NUEVO-SUCURSAL" method="POST" onSubmit='return check_form(frmPedido);'>
<table width="100%">
<tr>
<td colspan="2">
<fieldset><legend>Datos del documento</legend>
<table width="100%" border="0">
  <tr>
    <!--<td width="66" class="alignright">Tipo:. </td> -->
    <select id="cboTipoVenta" name="cboTipoVenta" onChange="mostrarcuota();" style="display:none">
      <option value="A">Contado</option>
      <option value="B">Credito</option>
     </select>	 <input type="hidden" name="txtIdUsuario" id="txtIdUsuario" value="<?php
	 if(isset($_SESSION['IdUsuario'])) echo $_SESSION['IdUsuario']; else echo '1';?>">
	 <input type="hidden" name="txtIdSucursal" id="txtIdSucursal" value="<?php echo $idsucursal;?>">
    <td width="77" class="alignright">N&deg;: </td>
    <td width="163"><div id="supernumero">
      <div id='divNumero'> </div>
    </div></td>
    <td width="47"><select id="cboTipoDocumento" name="cboTipoDocumento" size="1" onChange="cambiarmonedadetalle();generarnumero()" style="display:none">
      <option value="14">Pedido Sucursal</option>
    </select>      <span class="alignright">Fecha:</span></td>
    <td width="207"><!--N&deg;:      -->
      <input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value=" <?php 
$mysql_date = $fechaproceso; 
echo $mysql_date;
?> " size="10" readonly="true" style="background-color:#CCC">
      <button type="button" id="btnCalendar" class="boton" disabled><img src="../imagenes/b_views.png"></button>
      <script type="text/javascript">//<![CDATA[
//function vercalendar(a){
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide();},
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaRegistro", "%Y-%m-%d");
//}
      </script></td>
	
    <td width="95">
	<!--<div id="supernumero">
	<div id='divNumero'>	</div>	</div>--></td>
	
    <td width="57"><!--Moneda:--></td>
    <td width="99"><select id="cboMoneda" name="cboMoneda" onChange="cambiarmonedadetalle();" style="display:none">
      <option value="S">Soles</option>
      <option value="D">Dolares</option>
    </select></td>
    </tr>
  <tr>
    <td class="alignright">Sucursal Origen:</td>
    <td><input type='hidden' name = 'txtIdPersona' id="txtIdPersona" value = ''>
        <input type='text' name = 'txtNombresPersona' id="txtNombresPersona" style="text-transform:uppercase"  readonly='true' value = ''>
        <button id="btnPersona" type="button" class="boton" onClick="javascript: mostrarBusqueda(5);">...</button></td>
    <td><span class="alignright">Responsable:</span></td>
    <td><input type='hidden' name = 'txtIdResponsable' id="txtIdResponsable" value = ''>
      <input type='text' name = 'txtNombresResponsable' id="txtNombresResponsable" style="text-transform:uppercase"  readonly='true' value = ''>
      <button type="button" class="boton" onClick="mostrarBusqueda(3)">...</button></td>
    <td colspan="2"><div id="divImpuestoAyax">
      <div id="divImpuesto">
        <input name="checkIgv" type="checkbox" onClick="asignar()" value="checkIgv" checked>
        Incluido IGV</div>
    </div>
      <input type="hidden" name="txtImpuesto" id="txtImpuesto" value="2" readonly="">
      <div id="divGuia" style="display:none"><input type="checkbox" name="cGuia" id="cGuia" value="" onClick="asignarguia();">
Con Guia de Remisi&oacute;n</div></td>
    <td><input type="hidden" name="txtGuia" id="txtGuia" value="0"></td>
    <td width="13">&nbsp;</td>
  </tr>
  <tr>
    <td class="alignright">&nbsp;</td>
    <td>&nbsp;</td>
    <td></td>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr style="display:none">
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
<br>
<div id='divBuscarPersona' name='divBuscarPersona' style="overflow:auto;">
</div><br>
</fieldset>
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
<tr><td colspan="2">

<fieldset><legend>Búsqueda de productos</legend>
<div id="divbusqueda" class="alignleft">
<input type="hidden" name="pag" id="pag" value="1">
<input type="hidden" name="TotalReg" id="TotalReg">
&nbsp;Categoria: <?php echo genera_cboCategoria(0);?>
Por: 
<select name="campo" id="campo" onChange="document.getElementById('pag').value=1;buscar();">
<option value="producto.descripcion">Descripci&oacute;n</option>
<option value="producto.codigo">C&oacute;digo</option>
</select>&nbsp;:
<input name="frase" id="frase" onKeyUp="document.getElementById('pag').value=1;buscar();">
<label class="registros">Ojo: Los precios y stock mostrado en el listado est&aacute;n en base a la sucursal de origen selecciona</label>
</div>
<div id="divnumreg" class="registros"></div>
<div id="divregistros" style="height:100px; overflow:auto"></div></fieldset>
</td></tr><tr><td colspan="2">
<center>
<div id="divProdSeleccionado">
<fieldset><legend><strong>DATOS DEL PRODUCTO SELECCIONADO:</strong></legend>
<table width="100%">
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
  <td><a name="linkAgregar" href="#" onClick="agregar()">Agregar</a></td>
</tr></table>
</fieldset>
</div>
</center>
</td></tr>
<tr><td colspan="2">
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
  <td width="21%" style=" display:none">
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
    <input type="button" name="Submit" value="Cancelar" onClick="javascript:window.open('list_pedidosucursal.php','_self')">
    </label>
    <input type="hidden" name="txtCaja">
		<input type="hidden" name="txtValidarSerie" id="txtValidarSerie" value="si">
  </div>
</fieldset></td></tr>
</table>
</form>
</body>
</html>