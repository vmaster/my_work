<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;

if(isset($_GET['origen']))
$origen=$_GET['origen'];else $origen='LIST';
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

  check_input("txtCodigo", 1, "El producto debe tener un codigo");

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
<form action=<?php echo '../negocio/cont_transportista.php?accion='.$_GET['accion'].'&origen='.$origen ;?> method='POST' onSubmit="return check_form(formMantTransportista);" name="formMantTransportista">
<?php
if($_GET['accion']=='ACTUALIZAR'){
require("../datos/cado.php");
require("../negocio/cls_transportista.php");
$objTrans = new clsTransportista();
$rst = $objTrans->consultarajax($_GET['IdTransportista'],NULL, NULL);	
$dato= $rst->fetchObject();
}

?>
<table width="350" class="tablaint">
<tr>
<td width="198" class="alignright">
ID transportista :</td>
<td width="144"><input type='hidden' name = 'txtIdSucursal' value = '<?php echo $idsucursal;?>' readonly="" >
<input type='text' name = 'txtIdTransportista' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->id;?>' readonly="" style="text-transform:uppercase"></td>
</tr>
<tr>
<td class="alignright">
Nombre / Raz. Social:</td>
<td> <input type='text' name = 'txtNombre' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->transportista;?>' style="text-transform:uppercase"></td>
</tr>
<tr>
<td class="alignright">
DNI / RUC:</td>
<td> <input type='text' name = 'txtNroDoc' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->documento;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td></tr>
<tr>
  <td class="alignright">Dirección :</td>
  <td><input type='text' name = 'txtDireccion' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->direccion;?>' style="text-transform:uppercase"></td>
</tr>
<tr>
  <td class="alignright">Marca vehículo :</td>
  <td><input type='text' name = 'txtMarcaVehiculo' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->marcavehiculo;?>' style="text-transform:uppercase"></td>
</tr>
<tr>
  <td class="alignright">Número placa:</td>
  <td><input type='text' name = 'txtNumeroPlaca' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->numeroplaca;?>' style="text-transform:uppercase"></td>
</tr>
<tr>
  <td class="alignright">Nro. Constancia cert.:</td>
  <td><input type='text' name = 'txtConstancia' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->numeroconstancia;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
</tr>
<tr>
  <td class="alignright"> Nro Licencia / Brevete:</td>
  <td><input type='text' name = 'txtLicencia' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->licenciabrevete;?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">  </td>
</tr>
<tr>
  <td class="alignright">Nombres chofer :</td><td> <input type='text' name = 'txtChofer' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->chofer;?>' style="text-transform:uppercase">
</td></tr>
<tr>
  <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
    <input type='button' name = 'cancelar' value='CANCELAR' onClick="<?php if($origen=='LIST'){ echo "javascript:window.open('list_transportista.php','_self')";}else{ echo "javascript:window.close()";}?>"></th>
  </tr>
</table>
</form>
</body>
</html>