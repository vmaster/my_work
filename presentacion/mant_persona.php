<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores

require("../datos/cado.php");
function genera_cboDpto($seleccionado){
	Global $cnx;
	require("../negocio/cls_ubigeo.php");
	$oubigeo = new clsUbigeo();
	$consulta = $oubigeo->consultardepartamentos(1);

	$Dptos="<select name='cboDpto' id='cboDpto' onChange='VerProv()'>";
	while($registro=$consulta->fetch()){
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected='selected'";
		$Dptos=$Dptos."<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	$Dptos=$Dptos."</select>";
	$Dptos=utf8_encode($Dptos);
	$obj=new xajaxResponse();
	$obj->assign("DivDpto","innerHTML",$Dptos);
	return $obj;
}

function genera_cboProv($iddpto,$seleccionado){
	Global $cnx;
	include_once("../negocio/cls_ubigeo.php");
	$oubigeo = new clsUbigeo();
	$consulta = $oubigeo->consultarprovincias($iddpto);

	$Provs="<select name='cboProv' id='cboProv' onChange='VerDist()'>";
	while($registro=$consulta->fetch()){
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected='selected'";
		$Provs=$Provs."<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	$Provs=$Provs."</select>";
	$Provs=utf8_encode($Provs);
	$obj=new xajaxResponse();
	$obj->assign("DivProv","innerHTML",$Provs);
	return $obj;
}

function genera_cboDist($idprov,$seleccionado){
	Global $cnx;
	include_once("../negocio/cls_ubigeo.php");
	$oubigeo = new clsUbigeo();
	$consulta = $oubigeo->consultardistritos($idprov);

	$Dist="<select name='cboDist' id='cboDist'>";
	while($registro=$consulta->fetch()){
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected='selected'";
		$Dist=$Dist."<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	$Dist=$Dist."</select>";
	$Dist=utf8_encode($Dist);
	$obj=new xajaxResponse();
	$obj->assign("DivDist","innerHTML",$Dist);
	return $obj;
}

function verificaNroDoc($nro){
	require("../negocio/cls_persona.php");
	$opersona = new clsPersona();
	$consulta = $opersona->verificaNroDoc($nro);

	$Cadena="";
	if($consulta->rowCount()!=0){$Cadena=$Cadena."El N&uacute;mero de Documento ya existe";}
	$Cadena=utf8_encode($Cadena);
	$obj=new xajaxResponse();
    $obj->assign("LabelVerificaNroDoc","innerHTML",$Cadena);
	return $obj;
}

$xajax->registerFunction("verificaNroDoc");

$xajax->registerFunction("genera_cboDpto");
$fprovincia=& $xajax->registerFunction("genera_cboProv");
$fprovincia->setParameter(0,XAJAX_INPUT_VALUE,"cboDpto");
$fprovincia->setParameter(1,XAJAX_INPUT_VALUE,"txtSeleccionado");
$fdistrito=& $xajax->registerFunction("genera_cboDist");
$fdistrito->setParameter(0,XAJAX_INPUT_VALUE,"cboProv");
$fdistrito->setParameter(1,XAJAX_INPUT_VALUE,"txtSeleccionado");
$xajax->processRequest();
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<html>
<head>
<?php $xajax->printJavascript();?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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



function check_form(formMantPersona) {
  if (submitted == true) {
    alert("Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.");
    return false;
  }

  error = false;
  form = formMantPersona;
  error_message = "Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n";

  //check_input("txtNombres", 1, "La Persona debe tener un nombres");
  check_input("txtApellidos", 1, "La Persona debe tener un apellidos");
  check_input("txtNroDoc", 1, "La Persona debe tener un documento");
/*  check_input("txtDireccion", 1, "La Persona debe tener direccion");
  check_input("txtCelular", 1, "La Persona debe tener celular");
  check_input("txtEmail", 1, "La Persona debe tener email");*/
  check_select ("cboSector", 0, "Debe seleccionar un Sector");
  check_select ("cboSexo", 0, "Debe seleccionar un Sexo");
  check_select ("cboZona", 0, "Debe seleccionar una Zona");
  check_select ("cboRol", 0, "Debe seleccionar un Rol");
  
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
function verDpto(id)
{
	xajax_genera_cboDpto(id);
}
function VerProv()
{
	<?php $fprovincia->printScript()?>
}
function VerDist()
{
	<?php $fdistrito->printScript()?>
}
function VerProv2(iddpto,id)
{
	xajax_genera_cboProv(iddpto,id);
}
function VerDist2(idprov,id)
{
	xajax_genera_cboDist(idprov,id);
}
function verificaNroDoc(nro,Accion)
{
	if(nro=="1111111111"){
		return true;
	}else
	{
		xajax_verificaNroDoc(nro);
		if(LabelVerificaNroDoc.innerHTML==""){ 
			return true;
		}else{
			if(Accion=='NUEVO'){
				alert("El Número de Documento ya existe");
				return false;
			} else{
				return true;
			}
		}
	}
}
function cambiaDatos(tipo){
	if(tipo=="NATURAL"){
		document.getElementById("lblnrodoc").innerHTML="DNI:";
		document.getElementById("txtNroDoc").size=8;
		document.getElementById("txtNroDoc").maxLength=8;
		document.getElementById("lblnombres").innerHTML="Nombres:";
		document.getElementById("lblapellidos").innerHTML="Apellidos:";
		document.getElementById("txtNombres").style.display="";
		document.getElementById("lblrepresentante").innerHTML="";
		document.getElementById("txtRepresentante").style.display="none";
	}else{
		document.getElementById("lblnrodoc").innerHTML="RUC :";
		document.getElementById("txtNroDoc").size=11;
		document.getElementById("txtNroDoc").maxLength=11;
		document.getElementById("lblnombres").innerHTML="Raz&oacute;n Social:";		
		document.getElementById("lblapellidos").innerHTML="";
		document.getElementById("txtNombres").style.display="none";
		document.getElementById("lblrepresentante").innerHTML="Representante:";
		document.getElementById("txtRepresentante").style.display="";
		}
}
</script>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<!--<body onLoad="verDpto(14);VerProv(125);VerDist(409);">-->
<body>
<?php
if(isset($_GET['origen'])) $origen=$_GET['origen']; else $origen='LIST';

if(!isset($_GET['tipo'])){
$_GET['tipo']='PERSONA';}

if($_GET['tipo']=='PERSONA'){
?>
<div id="titulo01">MANTENIMIENTO DE PERSONA</div>
<?php }else{?>
<div id="titulo01">MANTENIMIENTO DE SUCURSAL</div>
<?php }?>
<form action=<?php echo '../negocio/cont_persona.php?accion='.$_GET['accion'].'&origen='.$origen.'&tipo='.$_GET['tipo']?> method='POST' onSubmit="if(verificaNroDoc(txtNroDoc.value,'<?php echo $_GET['accion'];?>')==false){return false;}else{ return check_form(formMantPersona);}" name="formMantPersona"><input type='hidden' name = 'txtIdPersona' value = '<?php echo $_GET['IdPersona'];?>'>
<?php
require("../datos/cado.php");
if($_GET['accion']=='ACTUALIZAR'){
require("../negocio/cls_persona.php");
$objPersona = new clsPersona();
$rst = $objPersona->buscarpersona($_GET['IdPersona'],'','');
$dato = $rst->fetchObject();
}

function genera_cboSector($seleccionado)
{

	require("../negocio/cls_sector.php");
		$objSector = new clsSector();
		$rst1 = $objSector->consultar();

	echo "<select name='cboSector' id='cboSector'>";
	while($registro1=$rst1->fetch())
	{
		$seleccionar="";
		if($registro1[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro1[0]."' ".$seleccionar.">".$registro1[1]."</option>";
	}
	echo "</select>";
}

function genera_cboZona($seleccionado)
{

	require("../negocio/cls_zona.php");
		$objZona = new clsZona();
		$rst2 = $objZona->consultar();


	echo "<select name='cboZona' id='cboZona'>";
	while($registro2=$rst2->fetch())
	{
		$seleccionar="";
		if($registro2[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro2[0]."' ".$seleccionar.">".$registro2[1]."</option>";
	}
	echo "</select>";
}

function genera_cboRol($seleccionado)
{

	require("../negocio/cls_rol.php");
		$objRol = new clsRol();
		$rst3 = $objRol->consultar();

	echo "<select name='cboRol' id='cboRol'>";
	while($registro3=$rst3->fetch())
	{
		$seleccionar="";
		if($registro3[0]==$seleccionado) $seleccionar="selected";
			if($registro3[0]<>5){
				echo "<option value='".$registro3[0]."' ".$seleccionar.">".$registro3[1]."</option>";
			}
	}
	echo "</select>";
}

?>
<table width="400" class="tablaint" align="center">
  <tr>
<?php
if ($_GET['tipo']=='PERSONA'){?> 
    <td width="150" class="alignright">Tipo persona:</td><td>
<select name="cboTipoPersona" onChange="cambiaDatos(this.value)">
      <?php
					if($_GET['accion']=="ACTUALIZAR" & $dato->TipoPersona=="NATURAL"){
					
					  echo "<option selected='selected' value='NATURAL'>Natural</option>
							<option value='JURIDICA'>Juridica</option>";
					 }
					else{
					  echo "<option selected='selected' value='NATURAL'>Natural</option>
					  <option value='JURIDICA'>Juridica</option>";
					  }
					?>
    </select></td>
      <?php
}else{?>
      <td width="150" ></td><td><input type="hidden" name="cboTipoPersona" value="JURIDICA" ></td>
      <?php }?>
      <?php
if ($_GET['tipo']=='PERSONA'){?>
  </tr>
  <tr>
    <td class="alignright"><label id="lblapellidos">Nombres:</label></td>
    <td><input type='text' name = 'txtNombres' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Nombres;?>' maxlength="50" size="25" style= "text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright"><label id="lblnombres">Apellidos:</label></td>
    <td><input type='text' name = 'txtApellidos' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Apellidos;?>' maxlength="50" size="25" style= "text-transform:uppercase">
      <?php
}else{?>
      <input type="hidden" name = 'txtNombres' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Nombres;?>' maxlength="50" size="25" style= "text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Nombre:</td>
    <td><input type='text' name = 'txtApellidos' value = '<?php if($_GET['accion']=='ACTUALIZAR') echo $dato->Apellidos;?>' maxlength="50" size="25" style= "text-transform:uppercase">
      <?php }?></td>
  </tr>
  <tr>
    <td class="alignright"><label id="lblnrodoc"><?php if ($_GET['tipo']=='PERSONA'){?>
      DNI :
      <?php }else{?>
      RUC :
      <?php }?></label></td>
    <td><input type='text' name = 'txtNroDoc' value = '<?php if($_GET['accion']=='ACTUALIZAR') {
echo $dato->NroDoc;} else { echo '';}?>' onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
onBlur="verificaNroDoc(this.value);" <?php if ($_GET['tipo']=='PERSONA'){?> size="8" maxlength="8" <?php }else{?> size="11" maxlength="11"<?php }?> >
</td> 
  </tr>
    <tr>
    <td class="alignright"><label id="lblrepresentante"></label></td>
    <td><input type='text' name = 'txtRepresentante' value = '<?php if($_GET['accion']=='ACTUALIZAR') echo $dato->Representante;?>' maxlength="50" size="25" style= "text-transform:uppercase; display:none">
	</td>
  </tr>
<?php if ($_GET['tipo']=='PERSONA'){?>
  <tr>
    <td class="alignright"></td>
    <td><select name="cboSexo" style="display:none">
      <?php
		if($_GET['accion']=="ACTUALIZAR" & $dato->Sexo=="F"){
			echo "<option selected='selected' value='F'>Femenino</option>
			<option value='M'>Masculino</option>";
        }
		else{
	        echo "<option selected='selected' value='M'>Masculino</option>
	  		<option id='F' value='F'>Femenino</option>";  
		  }
	?>
    </select>
      <?php
}else{?>
      <input type="hidden" name="cboSexo" value="M" >
      <?php }?></td>
  </tr>
</table>
<center>
<label id="LabelVerificaNroDoc" style="color: #003399"></label></center>
<div id="DivUbigeo">
<input type="hidden" name="txtSeleccionado" value="0" >
<table width="400" class="tablaint" align="center">
<tr>
<td width="150" class="alignright">Departamento:</td>
<td><div id="DivDpto"><?php if($_GET['accion']=='ACTUALIZAR') {?><script>verDpto(<?php echo $dato->iddepartamento?>);</script><?php }else{?><script>verDpto(14);</script><?php } ?></div></td>
</tr>
<td class="alignright">Provincia</td>
<td><div id="DivProv"><?php if($_GET['accion']=='ACTUALIZAR') {?><script>VerProv2(<?php echo $dato->iddepartamento?>,<?php echo $dato->idprovincia?>);</script><?php }else{?><script>VerProv2(14,125);</script><?php } ?></div></td>
</tr>
<tr>
<td class="alignright">Distrito:</td>
<td><div id="DivDist"><?php if($_GET['accion']=='ACTUALIZAR') {?><script>VerDist2(<?php echo $dato->idprovincia?>,<?php echo $dato->iddistrito?>);</script><?php }else{?><script>VerDist2(125,409);</script><?php } ?></div></td>
</tr>
</table>
</div>
<div>
<table width="400" class="tablaint" align="center">
  <tr>
    <td width="150" class="alignright">Dirección:</td>
    <td><input type='text' name = 'txtDireccion' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Direccion;?>' maxlength="50" size="25" style= "text-transform:uppercase"></td>
  </tr>
  <tr>
    <td class="alignright">Celular:</td>
    <td><input type='text' name = 'txtCelular' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Celular;?>' maxlength="9" size="9" onKeyPress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"></td>
  </tr>
  <tr>
    <td class="alignright">E-mail:</td>
    <td><input type='text' name = 'txtEmail' maxlength="50" size="25" value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->Email;?>'></td>
  </tr>
  <tr>
    <td class="alignright">Sector</td>
    <td><?php 
 if($_GET['accion']=='NUEVO')
{
 echo genera_cboSector(0);
 
 }
elseif($_GET['accion']=='ACTUALIZAR')
{
 echo genera_cboSector($dato->IdSector);
}?></td>
  </tr>
  <tr>
    <td class="alignright">Zona:</td>
    <td><?php 
 if($_GET['accion']=='NUEVO')
{

echo genera_cboZona(0);}
elseif($_GET['accion']=='ACTUALIZAR')
{echo genera_cboZona($dato->IdZona);
}
?></td>
  </tr>
  <tr>
    <td class="alignright"><?php
if($_GET['accion']=="NUEVO")
echo "
ROL: " 
?></td>
    <td><?php if($_GET['accion']=="NUEVO"){ if ($_GET['tipo']=='PERSONA'){ echo genera_cboRol(0);}else{ echo '<select name="cboRol" id="cboRol"><option value="5">SUCURSAL</option></select>';}}?></td>
  </tr>
</table>
</div>
<table width="400" align="center">
  <tr>
    <th><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="<?php if($origen=='LIST'){ echo "javascript:window.open('list_persona.php?tipo=".$_GET['tipo']."','_self')";}else{ echo "javascript:window.close()";}?>"></th>
  </tr>
</table>

</form>
</body>
</html>