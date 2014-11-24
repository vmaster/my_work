<?php 
session_start();

if($_GET['accion']=='CERRAR'){echo "<script languaje='javascript' type='text/javascript'> alert('se han modificado las cuotas satisfactoriamente');window.close();</script>";}



if($_GET['accion']=='GUARDAR'){
	$_SESSION['cuotas']='';
	$cuotas=$_SESSION['cuotas'];
	$num=$_POST['Contador'];
	for($i=1;$i<=$num;$i++){
		$cuotas[($i)]=array('numero'=>($i),'subtotal'=>$_POST['txtSubtotal'.$i],'interes'=>$_POST['txtInteres'.$i],'fecha'=>$_POST['txtFechaRegistro'.$i]);
	}	
	$_SESSION['cuotas']=$cuotas;
		
	echo "<script languaje='javascript' type='text/javascript'> alert('se han definido las cuotas satisfactoriamente');window.close();</script>";
	
}

if($_GET['accion']=='MODIFICAR'){
	$_SESSION['cuotas']='';
	$cuotas=$_SESSION['cuotas'];
	$num=$_POST['Contador'];
	for($i=1;$i<=$num;$i++){
	if(isset($_POST['txtModificar'.$i])){
		if($_POST['txtModificar'.$i]=='1'){
		$cuotas[($i)]=array('idcuota'=>$_POST['txtIdCuota'.$i],'nombre'=>$_POST['txtNumCuota'.$i],'fechac'=>$_POST['txtFechaRegistro'.$i],'fechap'=>$_POST['txtFechaPago'.$i],'moneda'=>$_POST['txtMoneda'.$i],'monto'=>$_POST['txtSubtotal'.$i],'interes'=>$_POST['txtInteres'.$i],'montop'=>$_POST['txtSubtotalP'.$i],'interesp'=>$_POST['txtInteresP'.$i],'estado'=>$_POST['cboEstado'.$i]);}
		}
	}	
	$_SESSION['cuotas']=$cuotas;
	
	
	for($i=1;$i<=$num;$i++){
	if(isset($_POST['txtEliminar'.$i])){
		if($_POST['txtEliminar'.$i]=='1'){
		$cuotaseliminar[($i)]=array('idcuota'=>$_POST['txtIdCuota'.$i]);}
		}
	}	
	$_SESSION['cuotaseliminar']=$cuotaseliminar;
	
	require_once('../negocio/cont_compra.php');
	controlador('ACTUALIZARCUOTA');	
}


if(isset($_SESSION['cuotas'])){$_SESSION['cuotas']==false;}
@require("xajax_compra.php");
?>
<?php $xajax->printJavascript();?>
<script>
function generarcuotas(){
 <?php $fgenerarcuotas->printScript() ?>;
}

function vercuotas(idcompra){
 xajax_vercuotas(idcompra);
}

function activar(numero){
	if(document.getElementById('check'+numero).checked==true){
		document.getElementById('checkeli'+numero).checked=false;
		document.getElementById('txtEliminar'+numero).value = '0';
		document.getElementById('DivEliminar'+numero).style.display="none";
		document.getElementById('txtInteres'+numero).readOnly = false;
		document.getElementById('txtSubtotal'+numero).readOnly = false;
		document.getElementById('cboEstado'+numero).disabled = false;
		document.getElementById('txtModificar'+numero).value = '1';
		document.getElementById('btnCalendar'+numero).disabled=false;
		
	}else{
		document.getElementById('txtInteres'+numero).readOnly = true;
		document.getElementById('txtSubtotal'+numero).readOnly = true;
		document.getElementById('cboEstado'+numero).disabled = true;
		document.getElementById('txtModificar'+numero).value = '0';
		document.getElementById('btnCalendar'+numero).disabled=true;
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


function pagar(numero){
	if(document.getElementById('cboEstado'+numero).value=='C'){
		document.getElementById('btnCalendarPago'+numero).style.display="";
	//	document.getElementById('txtInteresPagado'+numero).value=document.getElementById('txtInteres'+numero).value;
	}else{
	document.getElementById('btnCalendarPago'+numero).style.display="none";
	}
	
}

function darmonto(numero){
var a=document.getElementById('cboAsignado'+numero).value;
document.getElementById('txtSubtotal'+a).value=(parseFloat(document.getElementById('txtSubtotal'+numero).value)+parseFloat(document.getElementById('txtSubtotal'+a).value)).toFixed(2);
document.getElementById('txtSubtotal'+numero).value=0;
document.getElementById('txtInteres'+numero).value=0;
document.getElementById('cboEstado'+numero).value='A';
document.getElementById('check'+a).checked=true;

document.getElementById('check'+numero).disabled=true;
document.getElementById('checkeli'+numero).disabled=true;
document.getElementById('check'+a).disabled=true;
document.getElementById('checkeli'+a).disabled=true;
document.getElementById('cboAsignado'+numero).disabled=true;
document.getElementById('txtModificar'+a).value=1;
document.getElementById('txtEliminar'+numero).value=1;
document.getElementById('btnok'+numero).disabled=true;
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
var errordos= false;

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

function redondear(num, dec){ 
    num = parseFloat(num); 
    dec = parseFloat(dec); 
    dec = (!dec ? 2 : dec); 
    return Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec); 
   }


function verificarSumaMontos(){


	var total=0;
	
	for(var i=1; i<=parseInt(document.getElementById('Contador').value);i++ ){
		if(document.getElementById('cboEstado'+i).value!='A')
		total = total + parseFloat(document.getElementById('txtSubtotal'+i).value);
	}
	
	
	//if(redondear(parseFloat(document.getElementById('txtAcuenta').value),2) == total){
	//	alert('ESTA BIENN!!');
	//}
	if(redondear(parseFloat(document.getElementById('txtAcuenta').value),2) < total){
		
		 var xx= redondear(total-redondear(parseFloat(document.getElementById('txtAcuenta').value),2),2);
		
		//alert('El Monto Total SobrePasa La Deuda En: '+xx);
		error=true;
		error_message=error_message + "* "+ "El Monto Total SobrePasa La Deuda En: "+xx+" \r";
	}
	if(redondear(parseFloat(document.getElementById('txtAcuenta').value),2) > total){
		var yy=redondear(redondear(parseFloat(document.getElementById('txtAcuenta').value),2) - total,2);
		//alert('es menor');
		//if(document.getElementById('txtIdCompra').value!=''){
		if(confirm('           El Monto Total Es Menor Que La Cuenta En :'+yy+'  \r  ¿ DESEA CREAR OTRA CUOTA CON ESTE MONTO FALTANTE ?')==true){
			frmCuotas.mfaltante.value=yy;
			
		
		}else{
		error=true;
		errordos=true;
			
		}
		//}else{
		//	error=true;
		//	error_message=error_message + "* "+ "El Monto Total Es Menor Que La Cuenta En: "+yy+" \r";
		//}
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

  check_input("txtInicial", 1, "Debe definir la aportacion inicial");

  check_input("txtnNumCuota", 1, "Debe definir el numero de cuota");

  check_input("txtSubtotal", 1, "Debe definir el subtotal");
  
  check_input("txtInteres", 1, "Debe definir el interes");
  
  check_input("txtFecha", 1, "Debe definir la fecha de pago");
  
  verificarSumaMontos();
  
  if (error == true) {
  	if(errordos==true){
	
	}else{
    alert(error_message);}
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
<body onLoad="<?php if($_GET['accion']=='NUEVO'){echo 'generarcuotas()';}else if($_GET['accion']=='VER'){echo "vercuotas(".$_GET['idcompra'].")";}?>">

<form name='frmCuotas' id="frmCuotas" action='frm_cuotascompras.php?accion=<?php if ($_GET['accion']=='NUEVO'){ echo 'GUARDAR';}else{echo 'MODIFICAR';}?>' method='POST' onSubmit="return check_form(frmCuotas);">
<?php if($_GET['accion']=="VER"){?>
<input type="hidden" name="mfaltante" id="mfaltante" value="0">
<input type="hidden" name="txtIdCompra" id="txtIdCompra" value="<?php if(isset($_GET['idcompra'])){echo $_GET['idcompra'];}else{echo "0";}?>">
<table width="381" border="0" class="tablaint">
  <tr>
    <td class="alignright">Cliente:</td>
    <td><div id="divCliente">
      
</div></td>
  </tr>
  <tr>
    <td width="88" class="alignright">Fecha Compra: </td>
    <td width="283"><div id="divFecha"> </div></td>
  </tr>
</table>

<?php }?>

<table width="381" class="tablaint">
    <tr>
      <td width="62" class="alignright">Total: </td>
      <td width="103"><input name = 'txtTotal' type='text' id = 'txtTotal' value = '<?php if($_GET['accion']=='NUEVO') echo $_GET['total']?>' size="10" readonly="">
        <input type="hidden" name="txtFechaVenta" id="txtFechaVenta" value="<?php echo $_GET['fecha']?>"></td>
      <td width="70" class="alignright">A cuenta  :</td>
      <td width="126"><input name = 'txtAcuenta' type='text' id = 'txtAcuenta'  readonly="" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value = '<?php if($_GET['accion']=='NUEVO') echo number_format($_GET['total']-$_GET['inicial'],2)?>' size="10"></td>
    </tr>
    <tr>
      <td class="alignright">Inicial :</td>
      <td><input name = 'txtInicial' type='text' id = 'txtInicial' onClick="generarcuotas()" readonly="" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value = '<?php if($_GET['accion']=='NUEVO') echo number_format($_GET['inicial'],2)?>' size="10" ></td>
      <td class="alignright">Cuotas:</td>
      <td><input name="Contador" type="text" id="Contador" <?php if($_GET['accion']=='NUEVO'){echo "onKeyUp='generarcuotas()'";} else { echo "readonly='true'";}?>   onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" value="<?php if($_GET['accion']=='NUEVO') echo $_GET['numCuota']?>" size="10">
      <input type="hidden" name="txtInteres" value="<?php if($_GET['accion']=='NUEVO') echo $_GET['Interes']; ?>"></td>
    </tr>
  </table>

  <fieldset><legend>Cuotas:</legend>
  <div id='divCuotas' style="height:430px;overflow:auto;">
   
    
  </div>
  </fieldset>
  <table width="400">
  <tr>
    <th><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="window.close()"></th>
  </tr>
</table>

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
