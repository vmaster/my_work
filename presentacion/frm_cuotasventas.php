<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}
if(isset($_SESSION['FechaProceso'])){ $fechaproceso = $_SESSION['FechaProceso'];}
else {$fechaproceso = date("Y-m-d"); $_SESSION['FechaProceso']=date("Y-m-d"); }

if($_GET['accion']=='CERRAR'){echo "<script languaje='javascript' type='text/javascript'> alert('se han modificado las cuotas satisfactoriamente');window.close();</script>";}

if($_GET['accion']=='GUARDAR'){
	$_SESSION['cuotasventa']='';
	$cuotasventa=$_SESSION['cuotasventa'];
	$num=$_POST['Contador'];
	for($i=1;$i<=$num;$i++){
		$cuotasventa[($i)]=array('numero'=>($i),'subtotal'=>$_POST['txtSubtotal'.$i],'interes'=>$_POST['txtInteres'.$i],'fecha'=>$_POST['txtFechaRegistro'.$i]);
	}	
	$_SESSION['cuotasventa']=$cuotasventa;
		
	echo "<script languaje='javascript' type='text/javascript'> alert('se han definido las cuotas satisfactoriamente');window.close();</script>";
	
}

if($_GET['accion']=='MODIFICAR'){
	$_SESSION['cuotasventa']='';
	$cuotasventa=$_SESSION['cuotasventa'];
	$num=$_POST['txtContador'];
	for($i=1;$i<=$num;$i++){
	if(isset($_POST['txtModificar'.$i])){
		if($_POST['txtModificar'.$i]=='1'){
		$cuotasventa[($i)]=array('idcuota'=>$_POST['txtIdCuota'.$i],'nombre'=>$_POST['txtNumCuota'.$i],'fechac'=>$_POST['txtFechaRegistro'.$i],'fechap'=>$_POST['txtFechaPago'.$i],'moneda'=>$_POST['txtMoneda'],'monto'=>$_POST['txtSubtotal'.$i],'interes'=>$_POST['txtInteres'.$i],'montop'=>$_POST['txtMontoPagado'.$i],'interesp'=>$_POST['txtInteresPagado'.$i],'estado'=>$_POST['cboEstado'.$i]);}
		}
	}	
	$_SESSION['cuotasventa']=$cuotasventa;
	
	$_SESSION['cuotaseliminar']='';
	$cuotaseliminar=$_SESSION['cuotaseliminar'];
	for($i=1;$i<=$num;$i++){
	if(isset($_POST['txtEliminar'.$i])){
		if($_POST['txtEliminar'.$i]=='1'){
		$cuotaseliminar[($i)]=array('idcuota'=>$_POST['txtIdCuota'.$i]);}
		}
	}	
	$_SESSION['cuotaseliminar']=$cuotaseliminar;
	
		if(isset($_POST['txtNuevaCuota'])){
		if($_POST['txtNuevaCuota']=='1'){
		$cuotanueva[(1)]=array('idventa'=>$_POST['txtIdVenta'],'nombre'=>($num+1),'fechac'=>$_POST['txtFechaNueva'],'moneda'=>$_POST['txtMonedaNueva'],'monto'=>$_POST['txtSubtotalNueva'],'interes'=>$_POST['txtInteresNuevo']);}
		
		$_SESSION['cuotanueva']=$cuotanueva;
		}
	
	
	require_once('../negocio/cont_venta.php');
	controlador('ACTUALIZARCUOTA');	
}


if(isset($_SESSION['cuotasventa'])){$_SESSION['cuotasventa']=false;}
if(isset($_SESSION['cuotaseliminar'])){$_SESSION['cuotaseliminar']=false;}
if(isset($_SESSION['cuotanueva'])){$_SESSION['cuotanueva']=false;}
@require("xajax_venta.php");
?>
<?php $xajax->printJavascript();?>
<script>
function generarcuotas(){
 <?php $fgenerarcuotas->printScript() ?>;
}

function vercuotas(idventa){
 xajax_vercuotas(idventa);
}

function activar(numero){
	if(document.getElementById('check'+numero).checked==true){
		document.getElementById('checkeli'+numero).checked=false;
		document.getElementById('txtEliminar'+numero).value = '0';
		document.getElementById('DivEliminar'+numero).style.display="none";
		document.getElementById('txtInteres'+numero).readOnly = false;
		document.getElementById('txtSubtotal'+numero).readOnly = false;
		document.getElementById('txtModificar'+numero).value = '1';
		document.getElementById('btnCalendar'+numero).disabled = false;		
	}else{
		document.getElementById('txtInteres'+numero).readOnly = true;
		document.getElementById('txtSubtotal'+numero).readOnly = true;
		document.getElementById('txtModificar'+numero).value = '0';
		document.getElementById('btnCalendar'+numero).disabled = true;
	}
}

function eliminar(i,j){
xajax_vercuotaspendientes(i,j);
	if(document.getElementById('checkeli'+i).checked==true){
		document.getElementById('DivEliminar'+i).style.display="";
		document.getElementById('check'+i).checked=false;
		activar(i);
	}else{
		document.getElementById('DivEliminar'+i).style.display="none";
	}
}

function pagar(numero,idventa){
	if(document.getElementById('cboEstado'+numero).value=='C'){
		document.getElementById('txtMontoPagado'+numero).value=document.getElementById('txtSubtotal'+numero).value;
		document.getElementById('txtInteresPagado'+numero).value=document.getElementById('txtInteres'+numero).value;
	}
	
	if(document.getElementById('cboEstado'+numero).value=='A'){
	document.getElementById('checkeli'+numero).checked=true;
	eliminar(numero,idventa);
	document.getElementById('cboEstado'+numero).disabled=true;
	document.getElementById('check'+numero).checked=false;
	document.getElementById('txtInteresPagado'+numero).readOnly = true;
		document.getElementById('txtMontoPagado'+numero).readOnly = true;
		document.getElementById('txtModificar'+numero).value = '0';
	}
}

function darmonto(numero){
	var a=document.getElementById('cboAsignado'+numero).value;
	document.getElementById('txtSubtotal'+a).value=(parseFloat(document.getElementById('txtSubtotal'+numero).value)+parseFloat(document.getElementById('txtSubtotal'+a).value)).toFixed(2);
	document.getElementById('check'+a).checked=true;

	document.getElementById('check'+numero).disabled=true;
	document.getElementById('checkeli'+numero).disabled=true;
	document.getElementById('check'+a).disabled=true;
	document.getElementById('checkeli'+a).disabled=true;
	document.getElementById('cboAsignado'+numero).disabled=true;
	document.getElementById('txtModificar'+a).value=1;
	document.getElementById('txtEliminar'+numero).value=1;
	document.getElementById('btnok'+numero).disabled=true;
	
	document.getElementById('txtSubtotal'+numero).value='0.00';
}
</script>
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
	<?php if($_GET['accion']=='VER'){ ?>
var a=comprobarsuma();
  if(a!='0'){
  if(a<0){
  error_message = error_message+ "* La suma de montos no puede ser mayor al total de pago\n";
  divComentario.style.display="none";
  divComentario2.style.display="";
  divComentario2.innerHTML="Debe disminuir el monto de alguna cuota en "+((a*-1).toFixed(2));
  }else{
  error_message = error_message+ "* La suma de montos no puede ser menor al total de pago\n";
  divComentario.style.display="";
  divComentario2.style.display="none";
  divComentario.innerHTML="<input name='checkCuota' id='checkCuota' type='checkbox' onClick='generarnuevacuota()' value='checkCuota'>Generar nueva cuota con monto faltante ("+(a.toFixed(2))+")<input name='txtNuevaCuota' type='hidden' id='txtNuevaCuota' size='10' value='0'>";
  frmCuotas.txtSubtotalNueva2.value=(a).toFixed(2);
  
  }
  error=true;
  }else{
  divComentario.style.display="none";
  }
  <?php } ?>
  check_input("txtInicial", 1, "Debe definir la aportacion inicial");

  check_input("txtnNumCuota", 1, "Debe definir el numero de cuota");

  check_input("txtSubtotal", 1, "Debe definir el subtotal");
  
  check_input("txtInteres", 1, "Debe definir el interes");
  
  check_input("txtFecha", 1, "Debe definir la fecha de pago");
  
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}

function completar(num,numcuota){
for(i=num;i<=numcuota;i++){
document.getElementById('txtInteres'+i).value=document.getElementById('txtInteres'+num).value;}

}

function totalizar(num,numcuota){
var total=0; var faltante=0; var dividido=0; var x=0;

for(i=1;i<=num;i++){total=total+parseFloat(document.getElementById('txtSubtotal'+i).value);}

	faltante=parseFloat(document.getElementById('txtTotal').value)-total-parseFloat(document.getElementById('txtInicial').value);

	if(faltante<=0 || parseFloat(document.getElementById('txtSubtotal'+num))==0){
		total=0;
		for(i=1;i<num;i++){total=total+parseFloat(document.getElementById('txtSubtotal'+i).value);}
	faltante=parseFloat(document.getElementById('txtTotal').value)-parseFloat(document.getElementById('txtInicial').value)-total;
document.getElementById('txtSubtotal'+num).value=faltante.toFixed(2);
		for(i=num+1;i<=numcuota;i++){document.getElementById('txtSubtotal'+i).value='0.00';}
	}else{
	
		dividido=faltante/(numcuota-num);
		for(i=num+1;i<=numcuota;i++){document.getElementById('txtSubtotal'+i).value=dividido.toFixed(2);}
		if(num==numcuota){
		document.getElementById('txtSubtotal'+numcuota).value=(faltante+parseFloat(document.getElementById('txtSubtotal'+num).value)).toFixed(2);}
		
	}
}

function comprobarsuma(){
var suma=0;
for(i=1;i<=frmCuotas.txtContador.value;i++){
	suma=suma+parseFloat(document.getElementById('txtSubtotal'+i).value);
}
suma=suma+parseFloat(document.getElementById('txtSubtotalNueva').value);
var total=parseFloat(document.getElementById('txtAcuenta').value);
	if(suma<total){
		return total-suma;
	}else if(suma>total){
		return total-suma;
	}else{
		return '0';
	}

}

function generarnuevacuota(){
	if(frmCuotas.checkCuota.checked==true){
		frmCuotas.txtNuevaCuota.value="1";
		frmCuotas.txtSubtotalNueva.value=frmCuotas.txtSubtotalNueva2.value;
	}else{
		frmCuotas.txtNuevaCuota.value="0";
		frmCuotas.txtSubtotalNueva.value="0.00";
	}
}

//--></script>

<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="<?php if($_GET['accion']=='NUEVO'){echo 'generarcuotas()';}else if($_GET['accion']=='VER'){echo "vercuotas(".$_GET['idventa'].")";}?>">

<form name='frmCuotas' id="frmCuotas" action='frm_cuotasventas.php?accion=<?php if ($_GET['accion']=='NUEVO'){ echo 'GUARDAR';}else{echo 'MODIFICAR';}?>' method='POST' onSubmit="return check_form(frmCuotas);">
<?php if($_GET['accion']=="VER"){?>
<table width="381" border="0" class="tablaint">
  <tr>
    <td class="alignright">Cliente:</td>
    <td><div id="divCliente">
      
</div></td>
  </tr>
  <tr>
    <td width="88" class="alignright">Fecha Venta: </td>
    <td width="283"><div id="divFecha"> </div><input name = 'txtIdVenta' type='hidden' id = 'txtIdVenta' value = '<?php echo $_GET['idventa']?>' size="10" readonly=""></td>
  </tr>
</table>

<?php }?>

<table width="381" class="tablaint">
    <tr>
      <td width="62" class="alignright">Total: </td>
      <td width="94"><input name = 'txtTotal' type='text' id = 'txtTotal' value = '<?php if($_GET['accion']=='NUEVO') echo $_GET['total']?>' size="10" readonly="">
      <input type="hidden" name="txtFechaVenta" id="txtFechaVenta" value="<?php echo $_GET['fecha']?>"></td>
      <td width="66" class="alignright">A cuenta  :</td>
      <td colspan="2"><input name = 'txtAcuenta' type='text' id = 'txtAcuenta'  readonly="" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value = '<?php if($_GET['accion']=='NUEVO') echo (number_format($_GET['total'],2)-number_format($_GET['inicial'],2))?>' size="10"></td>
    </tr>
    <tr>
      <td class="alignright">Inicial :</td>
      <td><input name = 'txtInicial' type='text' id = 'txtInicial' onClick="" readonly="" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value = '<?php if($_GET['accion']=='NUEVO') echo number_format($_GET['inicial'],2)?>' size="10" ></td>
      <td class="alignright">Cuotas:</td>
      <td width="34"><input name="Contador" type="text" id="Contador" <?php if($_GET['accion']=='NUEVO'){echo "onKeyUp='generarcuotas()'";} else { echo "readonly='true'";}?>   onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php if($_GET['accion']=='NUEVO') echo $_GET['numCuota']?>" size="3">
	  <input name="txtContador" type="hidden" id="txtContador" size="10">
      <input type="hidden" name="txtInteres" value="<?php if($_GET['accion']=='NUEVO') echo $_GET['Interes']; ?>"></td>
      <td width="101"> <div id="divMoneda"></div></td>
    </tr>
  </table>

  <fieldset><legend><strong>Cuotas:</strong></legend>
  <div id='divCuotas' style="height:430px;overflow:auto;">
   
    
  </div>
  </fieldset>
 <div id="divComentario" style="display:none" style="color:#FF0000"></div>
  <div id="divComentario2" style="display:none" style="color:#FF0000"> </div>
 	<input type="hidden" name="txtInteresNuevo" id="txtInteresNuevo" value="">
	<input type="hidden" name="txtFechaNueva" id="txtFechaNueva" value="">
	<input type="hidden" name="txtMonedaNueva"  id="txtMonedaNueva" value="">
	<input type="hidden" name="txtSubtotalNueva" id="txtSubtotalNueva" value="0.00">
	<input type="hidden" name="txtSubtotalNueva2" id="txtSubtotalNueva2" value="0.00">
	
  <input type='submit' name = 'grabar' value='GRABAR'>
  <input type='button' name = 'cancelar' value='CANCELAR' onClick="window.close()">
<script type='text/javascript'>
	function elegirfecha(a){
	var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide(); },
  showTime: false});
  cal.manageFields('btnCalendar'+a, 'txtFechaRegistro'+a, '%Y-%m-%d');
  }
  </script>
<script type='text/javascript'>
	function seleccionarfecha(a){
	var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide(); },
  showTime: false});
  cal.manageFields('btnCalendarPago'+a, 'txtFechaPago'+a, '%Y-%m-%d');
}
</script>

</form>
</body>
</html>
