<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

if(isset($_SESSION['FechaProceso'])){ $fechaproceso = $_SESSION['FechaProceso'];}
else {$fechaproceso = date("Y-m-d"); $_SESSION['FechaProceso']=date("Y-m-d"); }

if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

if(isset($_GET['IdVenta']))
$idventa=$_GET['IdVenta'];else $idventa=NULL;

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
  if (form.elements[field_name]) {
    var field_value = form.elements[field_name].value;

    if (field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_select(field_name, field_default, message) {
  if (form.elements[field_name]) {
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
	error=false;

error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";
  check_input("txtIdMovimientoRef", 1, "Debe definir una venta para despachar");

  check_input("txtNombresResponsable", 1, "Debe definir el responsable en el registro de guia");
  
   if(frmGuia.cboDepartamento.value=='0'){
  check_input("txtPrueba", 1, "Debe definir el departamento de partida");
  }
  if(frmGuia.cboProvincia.value=='0'){
  check_input("txtPrueba", 1, "Debe definir la provincia de partida");
  }
  
  if(frmGuia.cboDistrito.value=='0'){
  check_input("txtPrueba", 1, "Debe definir el distrito de partida");
  }
  
  check_input("txtDireccionPartida", 1, "Debe definir la direccion de partida");
  
  if(frmGuia.cboDepartamento2.value=='0'){
  check_input("txtPrueba", 1, "Debe definir el departamento de destino");
  }
  if(frmGuia.cboProvincia2.value=='0'){
  check_input("txtPrueba", 1, "Debe definir la provincia de destino");
  }
  
  if(frmGuia.cboDistrito2.value=='0'){
  check_input("txtPrueba", 1, "Debe definir el distrito de destino");
  }
  
  check_input("txtDireccionLlegada", 1, "Debe definir la direccion de llegada");
  //check_input("txtTransportista", 1, "Debe definir el transportista");

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
   xajax_presentarmotivos();
   xajax_presentardepartamentos();
   generarnumero();
}

function verparaguias(){
  <?php $fbucarventasguia->printScript() ?>;
}

function verdetalle(idventa){
	window.open('frm_detalleventa.php?IdVenta='+idventa+'&accion=REFERENCIAR','_blank','width=1000,height=500');	
}

function generarnumero(){
  <?php $fgenerarnumeroguia->printScript() ?>;
}

function mostrarventasguias(){
   xajax_verbucarventasguia();
   divBuscarPersona.style.display="";
}

function transporte(){
   xajax_verbucarventasguia();
   divBuscarPersona.style.display="";
}

function referenciar(idventa){
   xajax_referenciarguiaremision(idventa);
   divBuscarPersona.style.display="none";
}

function generarprovincias(id){
if(id=='1'){
<?php $fpresentarprovincias->printScript() ?>;
<?php $fpresentardistritos->printScript() ?>;
}else{
<?php $fpresentarprovincias2->printScript() ?>;
<?php $fpresentardistritos2->printScript() ?>;
}
}

function generardistritos(id){
if(id=='1'){
<?php $fpresentardistritos->printScript() ?>;
}else{
<?php $fpresentardistritos2->printScript() ?>;
}
}

function mostrarBusqueda($origen){
   xajax_verbuscarpersona($origen);
   divBuscarPersona.style.display="";
}

function buscarPersona(){
   <?php $flistadopersona->printScript() ?>;
  }
  
function buscarchofer(){
   <?php $flistadotransportista->printScript() ?>;
}
  
function mostrarPersona(id){
   xajax_mostrarPersona(id);
   divBuscarPersona.style.display="none";
}
function mostrarEmpleado(id){
   xajax_mostrarEmpleado(id);
   divBuscarPersona.style.display="none";
}

function mostrarTransportista(id){
   xajax_mostrartransportista(id);
   divBuscarPersona.style.display="none";
}
</script>
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="inicio();<?php if($idventa){ echo 'referenciar('.$idventa.')';}?>">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">REGISTRO DE GUIAS DE REMISI&Oacute;N</div>
<form name="frmGuia" id="frmGuia" action="../negocio/cont_venta.php?accion=NUEVAGUIA" method="POST" onSubmit='return check_form(frmGuia);'>
   <table width="1121" height="147" border="0">
    <tr>
      <td colspan="2">
	  <fieldset>
        <legend>Datos del documento:</legend>
        <table width="805" border="0">
          <tr>
            <td width="41">&nbsp;</td>
            <td width="149"><input type="hidden" name="txtIdMovimientoRef" id="txtIdMovimientoRef" value="">
                <input type="hidden" name="txtIdSucursalRef" id="txtIdSucursalRef" value="0">
                <input type="hidden" id="txtTipoVenta" name="txtTipoVenta"  readonly="">
                <input type="hidden" name="txtIdUsuario" id="txtIdUsuario" value="<?php
	 if(isset($_SESSION['IdUsuario'])) echo $_SESSION['IdUsuario']; else echo '1';?>">
                <input type="hidden" name="txtIdSucursal" id="txtIdSucursal" value="<?php echo $idsucursal;?>">
                <input type="hidden" name="txtPrueba" id="txtPrueba" value=''>
                <button type="button" class="boton" onClick="mostrarventasguias()">Ver F/V para Desp.</button></td>
            <td width="144">&nbsp;</td>
            <td width="220">&nbsp;</td>
            <td width="113">&nbsp;</td>
            <td width="112" class="titulo"><input type="hidden" id="cboTipoDocumento" name="cboTipoDocumento" value='5'>
            GUIA REMISION </td>
          </tr>
          <tr>
            <td class="alignright">Doc.:</td>
            <td><div id="divtxtDocumento"></div>              </td>
            <td class="alignright">Responsable:</td>
            <td><input type='hidden' name = 'txtIdResponsable' id="txtIdResponsable" value = ''>
              <input name = 'txtNombresResponsable' type='text' id="txtNombresResponsable" style="text-transform:uppercase" value = '' size="28"  readonly='true'>
              <button type="button" class="boton" onClick="mostrarBusqueda(3)">...</button></td>
            <td class="alignright">N&deg;: </td>
            <td><div id="supernumero">
                <div id='divNumero'> </div>
            </div></td>
          </tr>
          <tr>
            <td class="alignright">Nro :</td>
            <td><div id="divtxtNumeroDocumento" > </div></td>
            <td class="alignright">Cliente:</td>
            <td><input type='hidden' name = 'txtIdPersona' id="txtIdPersona" value = ''>
              <input name = 'txtNombresPersona' type='text' id="txtNombresPersona" style="text-transform:uppercase" value = '' size="28"  readonly='true'></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="alignright">Fecha Emisi&oacute;n:</td>
            <td><input name="txtFechaRegistro" type="text" id="txtFechaRegistro" value=" <?php 
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
            <td class="alignright">Fecha Traslado:</td>
            <td><input name="txtFechaTraslado" type="text" id="txtFechaTraslado" value=" <?php 
$mysql_date = $fechaproceso; 
echo $mysql_date;
?> " size="10" readonly="true">
                <button type="button" id="btnTraslado" class="boton"><img src="../imagenes/b_views.png"></button>
              <script type="text/javascript">//<![CDATA[
//function vercalendar(a){
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide();},
  showTime: false
});
cal.manageFields("btnTraslado", "txtFechaTraslado", "%Y-%m-%d");
//}
              </script></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="alignright">Motivo Traslado: </t>
            <td colspan="3"><div id="divMotivoTraslado"></div>         </td>
          </tr>
        </table>
      </fieldset>	  </td>
      <td width="304">
	  <div id='divBuscarPersona' style="height:200px;overflow:auto;"></div>
      <script type="text/javascript">//<![CDATA[
function vercalendar(){
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide();},
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFechaRegistro2", "%Y-%m-%d");
}
    </script>	  </td>
    </tr>
    <tr>
      <td colspan="2">
	  
	  <fieldset>
        <legend>Punto de partida:</legend>
        <table width="716" border="0">
          <tr>
            <td width="108" class="alignright">Departamento:</td>
            <td width="129"><div id="divDepartamento">              	
              </div></td>
            <td width="86" class="alignright">Provincia:</td>
            <td width="189"><div id="divProvincia">
              <select name="cboProvincia" id="cboProvincia">
                <option value="0">Seleccionar uno</option>
              </select>
              </div></td>
            <td width="52" class="alignright">Distrito:</td>
            <td width="126"><div id="divDistrito">
              <select name="cboDistrito" id="cboDistrito">
                <option value="0">Selecciona uno</option>
              </select>
              </div></td>
          </tr>
          <tr>
            <td class="alignright">Direccion:</td>
            <td colspan="5"><input name="txtDireccionPartida" type="text" id="txtDireccionPartida" size="50"  style="text-transform:uppercase"></td>
          </tr>
        </table>
	  </fieldset>	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
	  <fieldset>
        <legend>Punto de llegada:</legend>
        <table width="719" border="0">
          <tr>
            <td width="108" class="alignright">Departamento:</td>
            <td width="128"><div id="divDepartamento2"> </div></td>
            <td width="86" class="alignright">Provincia:</td>
            <td width="192"><div id="divProvincia2">
                <select name="cboProvincia2" id="cboProvincia2">
                  <option value="0">Seleccionar uno</option>
                </select>
            </div></td>
            <td width="52" class="alignright">Distrito:</td>
            <td width="127"><div id="divDistrito2">
                <select name="cboDistrito2" id="cboDistrito2">
                  <option value="0">Selecciona uno</option>
                </select>
            </div></td>
          </tr>
          <tr>
            <td class="alignright">Direccion:</td>
            <td colspan="5"><input name="txtDireccionLlegada" type="text" id="txtDireccionLlegada" size="50"  style="text-transform:uppercase"></td>
          </tr>
        </table>
	  </fieldset>	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
	  <fieldset>
        <legend>Datos transportista / Unidad de transporte:</strong></legend>
        <table width="799" border="0">
          <tr>
            <td class="alignright">Nombre:</td>
          <td><input type='hidden' name = 'txtIdTransportista' id="txtIdTransportista" value = '0'>
                <input name = 'txtTransportista' type='text' id="txtTransportista" style="text-transform:uppercase" value = '' size="25"  readonly='true'>
                <button type="button" class="boton" onClick="mostrarBusqueda(100)">...</button></td>
            <td class="alignright">Dirección:</td>
            <td width="141"><div id="divdireccionT"></div></td>
            <td width="117" class="alignright">DNI/RUC.:</td>
            <td width="120"><div id="divdocumentoT"></div></td>
          </tr>
          <tr>
            <td width="127" class="alignright">Marca Vehiculo:</td>
            <td width="176"><div id="divmarcavehiculoT"></div></td>
            <td width="92"><div align="left" class="alignright">Chofer:</div></td>
            <td colspan="3"><div id="divchoferT"></div>              </div></td>
          </tr>
          <tr>
            <td class="alignright">Brevete:</td>
            <td><div id="divbreveteT"></div></td>
            <td class="alignright">Nro Placa:</td>
            <td><div id="divplacaT"></div></td>
            <td class="alignright">Nro. Constancia</td>
            <td><div id="divconstanciaT"></div></td>
          </tr>
        </table>
	  </fieldset>	    </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
	  <fieldset>
        <legend>Detalle del documento:</legend>
        <input name="hidden" type="hidden" id="txtDetalle" value="FALSE">
        <div id="DivDetalle"></div>
        <br>
      </fieldset>	  </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="321" class="alignleft">Comentario:<br>
          <textarea name="txtComentario" cols="50" rows="2"></textarea>		  </td>
      <td width="482">
	   <fieldset>
        <div align="center">
          <input type="submit" name="button" id="button" value="Guardar">
          <label>
          <input type="button" name="Submit" value="Cancelar" onClick="javascript:window.open('list_guias.php','_self')">
          </label>
          <input type="hidden" name="txtCaja">
          <input type="hidden" name="txtValidarSerie" id="txtValidarSerie" value="si">
        </div>
      </fieldset>	  </td>
      <td>	 	  </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
</html>