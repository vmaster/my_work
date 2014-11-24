<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['carrocuota']))
$carro=$_SESSION['carrocuota'];else $carro=false;

if(isset($_SESSION['TipoCambio']))
$tipocambio=$_SESSION['TipoCambio'];else $tipocambio=2.8;

if(isset($_SESSION['FechaProceso']))
{
$fechaproceso = $_SESSION['FechaProceso'];

}
else 
{
$fechaproceso = date("Y-m-d");
}
?>
<?php
require("xajax_movcaja.php");
?>
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
  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";

  check_input("txtPersona", 1, "Seleccione una Persona");

  check_input("txtMontoTotal", 1, "Asigne un monto");
  
  if(parseFloat(frmNuevoMovCaja.txtMontoTotal.value) <= parseFloat(0)){
  error_message=error_message+"* Asigne un Monto Mayor a 0\n";
  error = true;
  }
  
  if(frmNuevoMovCaja.cboDoc.value=='11' && frmNuevoMovCaja.cboMoney.value=='S'){
  var montosoles=frmNuevoMovCaja.hmSoles.value;
  var montototal=frmNuevoMovCaja.txtMontoTotal.value;


	  if(parseFloat(montosoles)<parseFloat(montototal)){
			error_message=error_message+"* No cuenta con la cantidad suficiente en soles para el egreso\n";
			error = true;
		} 
  
  }
  
 if(frmNuevoMovCaja.cboDoc.value=='11' && frmNuevoMovCaja.cboMoney.value=='D'){
  var montodolares=frmNuevoMovCaja.hmDolares.value;
  var montototal=frmNuevoMovCaja.txtMontoTotal.value;
  
	  if(parseFloat(montodolares)<parseFloat(montototal)){
			error_message=error_message+"* No cuenta con la cantidad suficiente en dolares para el egreso\n";
			error = true;
		} 
  
  }
  
  if (error == true ) {
 	alert(error_message);
	return false;  
  } else {
    submitted = true;
    return true;
  }
 	
}

function check_form2(form_name) {
  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";

  check_input("txtPersona", 1, "Seleccione una Persona");
  check_input("txtMontoSoles", 1, "Ingrese Una Cantidad en Soles");
  check_input("txtMontoDolares", 1, "Ingrese una Cantidad en Dolares");
  
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
<?php 
if($_GET['accion']=='NUEVO'){
$flistadocombo->printScript();
}
if($_GET['accion']=='APERTURA'){
$fcomboapertura->printScript();
}
if($_GET['accion']=='CIERRE'){
$fcombocierre->printScript();
}
?>;
<?php
if($_GET['accion']=='NUEVO')
{
?>
divMonto.style.display="";
divMontoAC.style.display="none";
<?php } elseif($_GET['accion']=='APERTURA' || $_GET['accion']=='CIERRE'){?>
divMonto.style.display="none";
divMontoAC.style.display="";
<?php
}
?>;
<?php $fgenerarnumero->printScript() ?>;
 divBuscarPersona.style.display="none";
 divbtnestadocuenta.style.display="none";
 divestadocuenta.style.display="none";
 divcuotas.style.display="none";
 divgeneral.style.display="none";
 divactualizarcuota.style.display="none";
 divcuotas.style.display="none";
 frmNuevoMovCaja.hestadocuenta.value="0";

}

function listadocombo(){
 <?php $flistadocombo->printScript() ?>;
 <?php $fgenerarnumero->printScript() ?>;
}

function comboapertura(){
 <?php $fcomboapertura->printScript() ?>;
 <?php $fgenerarnumero->printScript() ?>;
}
function combocierre(){
 <?php $fcombocierre->printScript() ?>;
 <?php $fgenerarnumero->printScript() ?>;
}

function mostrarBusqueda($origen){
  
   frmNuevoMovCaja.txtOrigenPersona.value=$origen;
   frmNuevoMovCaja.frasePersona.value="";
   divBuscarPersona.style.display="";
   divnumregPersona.innerHTML="";
   divregistrosPersona.innerHTML="";
   divestadocuenta.innerHTML="";
   divestadocuenta.style.display="none";
   divcuotas.innerHTML="";
   divcuotas.style.display="none";
   divbtnestadocuenta.style.display="none";
	
	frmNuevoMovCaja.txtIdMov.value="";
	frmNuevoMovCaja.txtIdCuota.value="";
	frmNuevoMovCaja.txtSaldoMonto.value="";
	frmNuevoMovCaja.txtSaldoInteres.value="";
	frmNuevoMovCaja.txtMontoPagar.value="";
	frmNuevoMovCaja.txtInteres.value="";
	divactualizarcuota.style.display="none";
 	divcuotas.style.display="none";
	divgeneral.style.display="none";
	DivDetalle.style.display="none";
	
	frmNuevoMovCaja.hestadocuenta.value="0";
	frmNuevoMovCaja.txtMontoTotal.value="";
	frmNuevoMovCaja.btnestadocuenta.disabled="";  
	
   xajax_eliminarvariablesession();
   DivDetalle.innerHTML="";
   divMontoTotal.innerHTML="0.00";
	
	<?php unset($_SESSION['carrocuota'])?>;
}

function buscarPersona(){
  <?php $flistadopersona->printScript() ?>;
}

function mostrarPersona(id){
   xajax_mostrarPersona(id);
   divBuscarPersona.style.display="none";
   <?php 
   if($_GET['accion']=='NUEVO'){
   ?>
   
  divbtnestadocuenta.style.display="";
  frmNuevoMovCaja.btnestadocuenta.disabled="";  
  
   <?php 
   }
   ?>
}

function mostrarestadocuenta(){
  <?php $festadocuenta->printScript() ?>;
   xajax_cajadeshabilitada();
   divgeneral.style.display="";
   divestadocuenta.style.display="";
   frmNuevoMovCaja.hestadocuenta.value="1";  
   frmNuevoMovCaja.btnestadocuenta.disabled="none";  
}

function estadocuentaIE(){
  <?php $festadocuenta->printScript() ?>; 
   xajax_eliminarvariablesession();
   DivDetalle.innerHTML="";
   divcuotas.style.display="none";
   divactualizarcuota.style.display="none";
   DivDetalle.style.display="none";
   frmNuevoMovCaja.txtMontoTotal.value="";
   divMontoTotal.innerHTML="0.00";
}
function cambiarmoneda() {
  <?php $festadocuenta->printScript() ?>;
   divcuotas.style.display="none";
   divactualizarcuota.style.display="none";
   DivDetalle.style.display="none";
   xajax_eliminarvariablesession();
   DivDetalle.innerHTML="";
   frmNuevoMovCaja.txtMontoTotal.value="";
   divMontoTotal.innerHTML="0.00";
 
}

function mostrarcuotas(id){
 divcuotas.style.display="";
 xajax_mostrarcuotas(id);
}

function actualizarcuota(id,idcuota,numero,saldo,interes){
 divactualizarcuota.style.display="";
 xajax_actualizarcuota(id,idcuota,numero,saldo,interes); 
}
function agregar(){
  <?php $fagregar->printScript() ?>;
  DivDetalle.style.display="";
}
function quitar(idCuota){
   xajax_quitar(idCuota);
}
function generarnumero(){
  <?php $fgenerarnumero->printScript() ?>;
}

function cerrar(){
 xajax_eliminarvariablesession();
 DivDetalle.style.display="none";
 DivDetalle.innerHTML="";
	frmNuevoMovCaja.txtIdMov.value="";
	frmNuevoMovCaja.txtIdCuota.value="";
	frmNuevoMovCaja.txtSaldoMonto.value="";
	frmNuevoMovCaja.txtSaldoInteres.value="";
	frmNuevoMovCaja.txtMontoPagar.value="";
	frmNuevoMovCaja.txtInteres.value="";
 divactualizarcuota.style.display="none";
 divcuotas.style.display="none";
 divgeneral.style.display="none";
 frmNuevoMovCaja.hestadocuenta.value="0";
 frmNuevoMovCaja.txtMontoTotal.value="";
 divMontoTotal.innerHTML="0.00";
  xajax_cajahabilitada();
frmNuevoMovCaja.btnestadocuenta.disabled="";  
}

</script>
<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<style type="text/css">
<!--
.Estilo15 {font-size: 14px}
.Estilo16 {font-size: 14px; font-weight: bold; }
.Estilo17 {font-weight: bold}
.Estilo18 {
	font-size: 24px;
	font-weight: bold;
	font-family: "Arial Black";
}
-->
</style>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
    <body onLoad="inicio()">
    <div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<form name="frmNuevoMovCaja" method="post" action="<?php echo '../negocio/cont_movcaja.php?accion='.$_GET['accion']?>"  onSubmit="<?php if($_GET['accion']=='NUEVO'){echo 'return check_form(frmNuevoMovCaja)';}elseif($_GET['accion']=='APERTURA' || $_GET['accion']=='CIERRE'){echo 'return check_form2(frmNuevoMovCaja)';}?>;">
<div id="titulo01">NUEVO MOVIMIENTO DE CAJA</div>
  <table width="100%" height="208" border="0" cellpadding="0" cellspacing="0" class="tablaint">
    <tr>
      <td width="653" height="89"><div align="center">
        <table width="657" height="92" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td height="37" class="alignright">Tipo:</td>
            <td width="130" height="37"><?php
			  if($_GET['accion']=='NUEVO'){
			  ?>
              <select name="cboDoc" id="cboDoc" onChange="listadocombo();generarnumero();estadocuentaIE()">
                <option value="10">Ingreso</option>
                <option value="11">Egreso</option>
              </select>
              <?php
			  }elseif($_GET['accion']=='APERTURA'){
			  ?>
              <select name="cboDoc" id="cboDoc" onChange="comboapertura();generarnumero()">
                <option value="10">Ingreso</option>
              </select>
              <?php
			  }elseif($_GET['accion']=='CIERRE'){
			  ?>
              <select name="cboDoc" id="cboDoc" onChange="combocierre();generarnumero()">
                <option value="11">Egreso</option>
              </select>
              <?php
			  }
			  ?></td>
            <td width="4">&nbsp;</td>
            <td width="23" class="alignright">N&ordm;:</td>
            <td width="146"><div id="divNumero"></div></td>
            <td width="59" class="alignright">Fecha:</td>
            <td width="171" align="left">
              <input name="txtFecha" type="text" id="txtFecha" value="<?php echo $fechaproceso?>" size="10" readonly=""></td>
          </tr>
          <tr>
            <td width="124" height="23" class="alignright">Concepto de Pago:</td>
            <td height="23" colspan="6"><div class="Estilo16" id="divconceptopago"> </div></td>
            </tr>
        </table>
      </div></td>
      <td width="350" rowspan="3"><div id='divBuscarPersona' style="height:200px;overflow:auto;">
        <fieldset>
        <div align="right">
          <legend>B&uacute;squeda personas:</legend>
          <input type="hidden" name="pagPersona" id="pagPersona" value="1">
          <input type="hidden" name="TotalRegPersona" id="TotalRegPersona">
          <input type='hidden' name = 'txtOrigenPersona' id="txtOrigenPersona" value = ''>
        </div>
          <div id="divbusquedaPersona" class="textoazul">
          <div align="left">Por:
            <select name="campoPersona" id="campoPersona" onChange="buscarPersona()">
                <option value="CONCAT(apellidos,' ',nombres)">Apellidos y Nombres</option>
                <option value="NroDoc">Nro Doc.</option>
              </select>
              <br>
            Descripci&oacute;n:
            <input name="frasePersona" id="frasePersona" onKeyUp="buscarPersona()">
            <button type="button" class="boton" onClick="window.open('mant_persona.php?accion=NUEVO&origen=DOC','_blank','width=404,height=480');">NUEVO</button>
          </div>
          </div>
          <div id="divnumregPersona" class="registros"></div>
          <div id="divregistrosPersona"></div>
          </fieldset>
      </div></td>
    </tr>
    <tr>
      <td height="37"><table width="653" height="27" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td width="124" class="alignright">A Nombre de:</td>
          <td width="334"><input name="txtIdPersona" type="hidden" id="txtIdPersona" value="<?php  if($_GET['accion']!='NUEVO'){echo $_SESSION['IdSucursal'];}?>">
              <input name="txtPersona" type="text" id="txtPersona" size="40" readonly=""  value="<?php 
			  if($_GET['accion']!='NUEVO'){
			  $obj = new clsMovCaja();
			  $sucursal = $obj->consultarsucursal();
			  echo $sucursal;
			  }
			  ?>">
            <?php if($_GET['accion']=='NUEVO'){ ?><button name="btnBuscarPersona" type="button" class="boton" onClick="mostrarBusqueda(0);frasePersona.focus();">...</button><?php }?></td>
          <td width="195"><div align="center" id="divbtnestadocuenta">
              <button name="btnestadocuenta" id="btnestadocuenta" type="button" class="boton" onClick="mostrarestadocuenta()">ESTADO DE CUENTA</button>
              <input name="hestadocuenta" type="hidden" id="hestadocuenta" value="0">
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="59"><table width="496" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td height="28">&nbsp;</td>
          <td width="89" rowspan="2" class="alignright">Monto:</td>
          <td width="372" colspan="2" rowspan="2"><div id="divMonto" >
            <table width="309" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="97">
                  
                      <div align="center">
                        <select name="cboMoney" id="cboMoney" onChange="cambiarmoneda()">
                          <option value="S">Soles</option>
                          <option value="D">Dolares</option>
                        </select>
                    </div></td>
                <td width="112"><div id="divcajamonto">
                  <input name="txtMontoTotal" type="text" id="txtMontoTotal" size="15" maxlength="11">
                </div></td>
                <td width="100"><input name="hmSoles" type="hidden" id="hmSoles" value="<?php echo $_SESSION['saldosoles'];?>" >
                  <input name="hmDolares" type="hidden" id="hmDolares" value="<?php echo $_SESSION['saldodolares'];?>">                  </td>
              </tr>
            </table>
          </div><div id="divMontoAC">
            <table width="223" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="113" class="alignright">Soles (S/.):</td>
                <td width="110"><input name="txtMontoSoles" type="text" id="txtMontoSoles" 
				value="<?php
				if($_GET['accion']=='CIERRE'){
				echo $_SESSION['saldosoles'];
				}elseif($_GET['accion']=='APERTURA'){
				$objMov = new clsMovCaja();
				$rst = $objMov->montodeaperturasoles();
				echo $rst;	
				}			
				?>" size="15" maxlength="11"<?php 
				$objMov = new clsMovCaja();
				$num = $objMov->existenciamov();
				if($_GET['accion']=='APERTURA' && $num==0){
				}else{
				//echo "readonly=''";
				}
				?>></td>
                </tr>
              <tr>
                <td class="alignright" >Dolares ($.):</td>
                <td><input name="txtMontoDolares" type="text" id="txtMontoDolares" value="<?php 
				if($_GET['accion']=='CIERRE'){
				echo $_SESSION['saldodolares'];
				}elseif($_GET['accion']=='APERTURA'){
				$objMov = new clsMovCaja();
				$rst = $objMov->montodeaperturadolares();
				echo $rst;
				}			
				?>" size="15" maxlength="11"<?php 
				$objMov = new clsMovCaja();
				$num = $objMov->existenciamov();
				if($_GET['accion']=='APERTURA' && $num==0){
				}else{
				//echo "readonly=''";
				}
				?>></td>
                </tr>
            </table>
          </div></td>
          </tr>
        <tr>
          <td width="35" height="28" class="Estilo16">&nbsp;</td>
          </tr>

      </table></td>
    </tr>
  </table>
<div id="divgeneral">
          <table width="100%" height="201" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="62"><fieldset>
                <legend>Estado de Cuenta:</legend>
                <div id="divestadocuenta"></div>
              </fieldset></td>
            </tr>
            <tr>
              <td>
                <div id="divcuotas"></div>              </td>
            </tr>
            
            <tr>
              <td height="77">
                <table width="100%" height="99" border="0" cellpadding="0" cellspacing="0" class="tablaint">
                  <tr>
                    <td height="55"><div id="divactualizarcuota">
                      <fieldset>
                      <legend>Datos de la cuota seleccionada:</legend>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><div align="center">
                              <table width="100%" height="98" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td width="145">&nbsp;</td>
                                  <td width="148" height="19">&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="145" class="Estilo16">&nbsp;</td>
                                  <td width="148" height="23" class="alignright">
                                  <input name="txtnumerocuota" type="hidden" id="txtnumerocuota">
                                      <input name="txtIdCuota" type="hidden" id="txtIdCuota">
                                      <input name="txtIdMov" type="hidden" id="txtIdMov" value="">
                                    Nro Cuota:</td>
                                  <td width="33"><div id="divnumerocuota" style="color:#FF0000"></div></td>
                                  <td width="131" class="alignright">
                                    <input name="txtSaldoMonto" type="hidden" id="txtSaldoMonto">
                                  Saldo monto:</td>
                                  <td width="79"><div id="divsaldomonto" style="color:#FF0000"></div></td>
                                  <td width="150" align="right" class="alignright">
                                  <input name="txtSaldoInteres" type="hidden" id="txtSaldoInteres">Saldo inter&eacute;s:</td>
                                  <td width="76"><div style="color:#FF0000" id="divsaldointeres"></div></td>
                                  <td width="152">&nbsp;</td>
                                  <td width="101">&nbsp;</td>
                                  <td width="145">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td width="145" class="Estilo16">&nbsp;</td>
                                  <td width="148" height="28" class="Estilo16">&nbsp;</td>
                                  <td>&nbsp;</td>
                                  <td class="alignright">Monto a pagar:</td>
                                  <td><input name="txtMontoPagar" type="text" id="txtMontoPagar" size="10"></td>
                                  <td class="alignright">Inter&eacute;s a pagar:</td>
                                  <td><input name="txtInteres" type="text" id="txtInteres" size="10"></td>
                                  <td class="alignright">Fecha prox. canc.:</td>
                                  <td><input name="txtFechaProx" type="text" id="txtFechaProx" value=" <?php 
$mysql_date = date("Y-m-d"); 
echo $mysql_date;
?> " size="10" readonly="true">
                                    <button type="button" id="btnCalendar" class="boton"><img src="../imagenes/b_views.png"></button>
                                  <script type="text/javascript">//<![CDATA[
//function vercalendar(a){
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide();},
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaProx", "%Y-%m-%d");
//}
                                    </script></td>
                                  <td>&nbsp;</td>
                                </tr>
                                <tr>
                                  <td height="28" colspan="10" class="Estilo16" align="center">
                                    <button type="button" class="boton" onClick="agregar()">AGREGAR</button></td>
                                </tr>
                              </table>
                          </div></td>
                          </tr>
                      </table>
                      </fieldset>
                    </div></td>
                  </tr>
                  <tr>
                    <td height="55"><div id="DivDetalle"></div></td>
                  </tr>
                  <tr>
                    <td><div align="center" id="divtotal">
                      <table width="518" height="57" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="180" class="Estilo16">&nbsp;</td>
                          <td width="70">&nbsp;</td>
                          <td width="268">&nbsp;</td>
                        </tr>
                        <tr>
                          <td class="alignright">MONTO TOTAL:</td>
                          <td><div id="divMontoTotal">0.00</div></td>
                          <td><a href="#" onClick="cerrar()">Salir de Estados de Cuenta </a></td>
                        </tr>
                      </table>
                    </div></td>
                  </tr>
              </table></td>
            </tr>
          </table>
  </div>

   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablaint">
          <tr>
            <td><table width="653" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="86" height="25" class="alignleft">Comentario:</td>
                <td width="264" rowspan="2"><textarea name="txtComentario" cols="40" rows="3" id="txtComentario"></textarea></td>
                <td width="303" rowspan="2" align="center">
                    <input name="btnGuardar" type="submit" id="btnGuardar" value="GUARDAR">
                    <input type="reset" name="Submit" value="CANCELAR" onClick="javascript:window.open('list_movcaja.php','_self')"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
  </table>
</form>
</body>