<?php
session_start();
unset($_SESSION['carrocuota']);
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
if(isset($_SESSION['TipoCambio']))
{
$tipocambio=$_SESSION['TipoCambio'];
}else{ 
$tipocambio=2.8;
$_SESSION['TipoCambio']=$tipocambio;
}

if(isset($_SESSION['FechaProceso']))
{
$fechaproceso = $_SESSION['FechaProceso'];

}
else 
{
$fechaproceso = date("Y-m-d");
$_SESSION['FechaProceso']=date("Y-m-d");
}
?>
<?php
require("xajax_movcaja.php");
?>
<?php $xajax->printJavascript();?>
<script>
function inicio(){
<?php $fpresentarmovimientos->printScript() ?>;
divgenerarbusqueda.style.display="none";
//divbusqueda.style.display="none";
}
function mostrarbusqueda(){
<?php $fpresentarbusquedamov->printScript() ?>;
divbusqueda.style.display="";
}
function generarbusqueda(){
<?php $fgenerarbusqueda->printScript() ?>;
divgenerarbusqueda.style.display="";
}
function cerrar(){
divgenerarbusqueda.style.display="none";
divbusqueda.style.display="none";
}
</script>
<style type="text/css">
<!--
.Estilo3 {font-size: 14px; font-weight: bold; }
.Estilo4 {font-size: 14px}
.Estilo5 {
	font-size: 24px;
	font-weight: bold;
	font-family: "Arial Black";
}
.Estilo8 {font-size: 12px}
-->
</style>
<html>
<head>
<title>CAJA</title>
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
<div id="titulo01">MOVIMIENTOS DE CAJA</div>
<form name="form1" method="post" action="">
  <fieldset>
 <legend>Ingresos y egresos del día</legend>
  <table width="860" height="72" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="496" rowspan="2"><div align="center" id="divbotones">
	  
	  
	  <?php
	  $objMov = new clsMovCaja();
	  $apertura = $objMov->consultarapertura();
	  $cierre = $objMov->consultarcierre($_SESSION['FechaProceso']);
	  
	  if($apertura==0 && $cierre==0)
	  {
	  ?>
	  
	 <input name="btnOperacion" type="button" id="btnOperacion" value="NUEVO" onClick="window.location.replace('frm_nuevomovcaja.php?accion=NUEVO');"  disabled="disabled"/>
	<input name="btnApertura" type="button" id="btnApertura" value="APERTURA CAJA" onClick="window.location.replace('frm_nuevomovcaja.php?accion=APERTURA');" >	
	<input name="btnCierre" type="button" id="btnCierre" value="CIERRE CAJA" onClick="window.location.replace('frm_nuevomovcaja.php?accion=CIERRE');" disabled="disabled"/>
	  <?php
	  }elseif($apertura!=0 && $cierre==0){
	  ?>
	  	 <input name="btnOperacion" type="button" id="btnOperacion" value="NUEVO" onClick="window.location.replace('frm_nuevomovcaja.php?accion=NUEVO');"/>
	<input name="btnApertura" type="button" id="btnApertura" value="APERTURA CAJA" onClick="window.location.replace('frm_nuevomovcaja.php?accion=APERTURA');" disabled="disabled">	
	<input name="btnCierre" type="button" id="btnCierre" value="CIERRE CAJA" onClick="window.location.replace('frm_nuevomovcaja.php?accion=CIERRE');"/>
	  <?php
	  }elseif($apertura!=0 && $cierre!=0){
	  ?>
	  <input name="btnOperacion" type="button" id="btnOperacion" value="NUEVO" onClick="window.location.replace('frm_nuevomovcaja.php?accion=NUEVO');" disabled="disabled"/>
	<input name="btnApertura" type="button" id="btnApertura" value="APERTURA CAJA" onClick="window.location.replace('frm_nuevomovcaja.php?accion=APERTURA');" disabled="disabled">	
	<input name="btnCierre" type="button" id="btnCierre" value="CIERRE CAJA" onClick="window.location.replace('frm_nuevomovcaja.php?accion=CIERRE');" disabled="disabled"/>
	  
	  <?php }?>
	  
	  </div></td>
      <td width="171" height="39" class="alignright">Fecha del Proceso:</td>
      <td width="147"><div align="center">
        <input name="txtFecha"  type="text" id="txtFecha" value="<?php echo $fechaproceso;?>" readonly="">
      </div></td>
      <td width="34">&nbsp;</td>
      <td width="12" rowspan="2"><img src="../imagenes/Gloss PNGCalculator.png" width="100" height="100"></td>
    </tr>
    <tr>
      <td height="24" class="alignright">Tipo Cambio:</td>
      <td><label>
        <input name="txtTipoCambio" type="text" id="txtTipoCambio" value="<?php echo $tipocambio;?>" readonly="" />        </label></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div id="divtablamovimientos">
  </div>
  <table width="846" height="81">
    <tr>
      <td width="83">&nbsp;</td>
      <td width="154">&nbsp;</td>
      <td width="113">&nbsp;</td>
      <td width="164">&nbsp;</td>
      <td width="157">&nbsp;</td>
      <td width="175">&nbsp;</td>
    </tr>
	
<?php
$objMov6 = new clsMovCaja();
$rst6 = $objMov6->consultar();
$rst7 = $objMov6->consultarcierre($_SESSION['FechaProceso']);
$totalIS=0;
$totalES=0;
$totalID=0;
$totalED=0;

if($rst7==0){
if(isset($rst6)){
while($dato6 = $rst6->fetchObject())
{
if($dato6->moneda=='S' && $dato6->tipo=='I')
$totalIS=$totalIS+($dato6->total);

if($dato6->moneda=='S' && $dato6->tipo=='E')
$totalES=$totalES+($dato6->total);

if($dato6->moneda=='D' && $dato6->tipo=='I')
$totalID=$totalID+($dato6->total);

if($dato6->moneda=='D' && $dato6->tipo=='E')
$totalED=$totalED+($dato6->total);
}
}
}
$saldos=$totalIS-$totalES;
$_SESSION['saldosoles']=$saldos;
$saldod=$totalID-$totalED;
$_SESSION['saldodolares']=$saldod;
?>
	
	
    <tr>
      <td height="30" class="alignright">Saldo (S/.) :</td>
      <td><input name="txtSaldoSoles" type="text" id="txtSaldoSoles"  value="<?php echo number_format($saldos,2); ?>"   readonly=""/></td>
      <td class="alignright">Saldo Total S/. :</td>
      <td><input name="txtSaldoTotal" type="text" id="txtSaldoTotal" value="<?php echo number_format((($saldos)+($saldod*$tipocambio)),2) ;?>" readonly=""/></td>
      <td>&nbsp;</td>
      <td><div align="center">
        <input name="btnBusqueda" type="button" id="btnBusqueda" value="B&Uacute;SQUEDAS DEL DIA" onClick="generarbusqueda()">
      </div></td>
    </tr>
    <tr>
      <td class="alignright">Saldo ($.) :</td>
      <td><input name="txtSaldoDolares" type="text" id="txtSaldoDolares" value="<?php echo number_format($saldod,2);?>" readonly=""/></td>
      <td>&nbsp;</td>
  
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  </fieldset>
<div id="divgenerarbusqueda"></div>
</form>
</body>
</html>