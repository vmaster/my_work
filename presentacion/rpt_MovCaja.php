<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
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
require("xajax_rptMovCaja.php");
?>
<?php $xajax->printJavascript();?>
<script>
function activarparametros(){

if(frmReporteMovCaja.cboRango.value=='M'){
divMes.style.display="";
divDia.style.display="none";
divA.style.display="none";
frmReporteMovCaja.cboTipo.disabled='disabled';
frmReporteMovCaja.cboMoneda.disabled='disabled';

}

if(frmReporteMovCaja.cboRango.value=='D'){
divMes.style.display="none";
divDia.style.display="";
divA.style.display="none";
frmReporteMovCaja.cboTipo.disabled='';
frmReporteMovCaja.cboMoneda.disabled='';
}

if(frmReporteMovCaja.cboRango.value=='A'){
divMes.style.display="none";
divDia.style.display="none";
divA.style.display="";
frmReporteMovCaja.cboTipo.disabled='disabled';
frmReporteMovCaja.cboMoneda.disabled='disabled';
}

divresultadosreporte.style.display="none";
divresultadosreporte.innerHTML="";
}

function generarreporte(){
if(frmReporteMovCaja.cboRango.value=='D'){
<?php $frptdia->printScript()?>;
}
if(frmReporteMovCaja.cboRango.value=='M'){
<?php $frptmes->printScript()?>;
}
if(frmReporteMovCaja.cboRango.value=='A'){
<?php $frptyear->printScript()?>;
}
divresultadosreporte.style.display="";
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
function genera_cboConceptoPago($seleccionado)
{
	$Obj = new clsMovCaja();
	$consulta = $Obj->consultarconceptopago();

	echo "<select name='cboConceptoPago' id='cboConceptoPago'>";
	echo "<option value='0'>TODOS</option>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

function genera_cboRolPersona($seleccionado)
{
	
	$Obj = new clsMovCaja();
	$consulta = $Obj->cargarcomborolpersona();

	echo "<select name='cboRolPersona' id='cboRolPersona'>";
	echo "<option value='0'>TODOS</option>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}

function genera_year(){

	$Obj = new clsMovCaja();
	$consulta = $Obj->year_min_max();
	$rst=$consulta->fetch();
	$minyear=$rst[0];
	$maxyear=$rst[1];
	$num=$maxyear-$minyear;

	echo "<select name='cboYear' id='cboYear'>";
	for($i=0;$i<=$num;$i++){	
	echo "<option value='".($maxyear-$i)."'>".($maxyear-$i)."</option>";
	}
	echo "</select>";
}

function genera_year1(){

	$Obj = new clsMovCaja();
	$consulta = $Obj->year_min_max();
	$rst=$consulta->fetch();
	$minyear=$rst[0];
	$maxyear=$rst[1];
	$num=$maxyear-$minyear;

	echo "<select name='cboYearInicio' id='cboYearInicio'>";
	for($i=0;$i<=$num;$i++){	
	echo "<option value='".($maxyear-$i)."'>".($maxyear-$i)."</option>";
	}
	echo "</select>";
}

function genera_year2(){

	$Obj = new clsMovCaja();
	$consulta = $Obj->year_min_max();
	$rst=$consulta->fetch();
	$minyear=$rst[0];
	$maxyear=$rst[1];
	$num=$maxyear-$minyear;

	echo "<select name='cboYearFin' id='cboYearFin'>";
	for($i=0;$i<=$num;$i++){	
	echo "<option value='".($maxyear-$i)."'>".($maxyear-$i)."</option>";
	}
	echo "</select>";
}

?>

<style type="text/css">
<!--
.Estilo1 {
	font-size: 24px;
	font-weight: bold;
}
.Estilo2 {
	font-size: 14px;
	font-weight: bold;
}
-->
</style>
<style type="text/css">
<!--
.Estilo3 {font-family: "Arial Black"}
-->
</style>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
    <body onLoad="activarparametros()">
    <div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
    <div id="titulo01">REPORTES DE MOVIMIENTOS DE CAJA </div>
<form id="frmReporteMovCaja" name="frmReporteMovCaja" method="post" action="">
  <fieldset>
  <legend>Parámetros de búsqueda</legend>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="99" height="23" align="right" class="Estilo2">&nbsp;</td>
      <td width="201">&nbsp;</td>
      <td align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="31" class="alignright">Reporte Por:</td>
      <td><select name="cboRango" id="cboRango" onChange="activarparametros()">
          <option value="D">Dia</option>
          <option value="M">Mes</option>
          <option value="A">A&ntilde;o</option>
      </select></td>
      <td align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td height="20" align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right" class="Estilo2">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" rowspan="3" align="center"><div id="divDia">
          <table width="245" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="71" class="alignright">Dia:</td>
              <td width="174"><input name="txtFechaDia" type="text" id="txtFechaDia" value="<?php echo $fechaproceso;?>" size="10" readonly=""/>
        <button type="button" id="btnCalendar" class="boton">...</button>
      <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar", "txtFechaDia", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
            </tr>
          </table>
      </div>
          <div id="divMes">
            <table width="256" height="107" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="42" class="alignright">Mes:</td>
                <td width="54" class="alignright" >Desde</td>
            <td width="130"><select name="cboMesInicio" id="cboMesInicio">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select></td>
              </tr>
              <tr>
                <td height="33">&nbsp;</td>
                <td class="alignright">Hasta</td>
            <td><select name="cboMesFin" id="cboMesFin">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select></td>
              </tr>
              <tr>
                <td width="72" class="alignright">A&ntilde;o:</td>
                <td >&nbsp;</td>
                <td align="left" class="Estilo2"><?php echo genera_year();?></td>
              </tr>
            </table>
          </div>
        <div id="divA">
            <table width="216" height="69" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="52" height="33" class="alignright">A&ntilde;o:</td>
                <td width="54" class="alignright">Desde</td>
                <td width="110"><span class="Estilo2"><?php echo genera_year1();?></span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td class="alignright">Hasta</td>
                <td><span class="Estilo2"><?php echo genera_year2();?></span></td>
              </tr>
          </table>
        </div></td>
      <td height="49" class="alignright">Tipo:</td>
      <td><select name="cboTipo" id="cboTipo">
        <option value="0">TODOS</option>
        <option value="10">INGRESO</option>
        <option value="11">EGRESO</option>
      </select></td>
      <td class="alignright">Concepto Pago:</td>
      <td><?php echo genera_cboConceptoPago(0);?></td>
    </tr>
    <tr>
      <td width="58" height="47" class="alignright">Modena:</td>
      <td width="103"><select name="cboMoneda" id="cboMoneda">
        <option value="0">TODAS</option>
        <option value="S">SOLES</option>
        <option value="D">DOLARES</option>
      </select></td>
      <td width="120" class="alignright">Rol Persona:</td>
      <td width="219"><?php echo genera_cboRolPersona(0);?></td>
    </tr>
    <tr>
      <td height="19" colspan="4" align="left" class="Estilo2"><input name="btnGenerarR" type="button" id="btnGenerarR" value="GENERAR REPORTE" onClick="generarreporte()" /></td>
    </tr>
  </table>
  </fieldset>
  <div id="divresultadosreporte"></div>
  <P></P>
</form>
</body>
