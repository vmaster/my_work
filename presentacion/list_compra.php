<?php
session_start();

if(!isset($_SESSION['Usuario']))
{
            header("location: ../presentacion/login.php?error=1");
}


if(isset($_SESSION['FechaProceso'])){
$fechaproceso = $_SESSION['FechaProceso'];
}else {
$fechaproceso = date("Y-m-d");
$_SESSION['FechaProceso']=date("Y-m-d");
}



if(isset($_SESSION['IdSucursal']))
$idsucursal=$_SESSION['IdSucursal'];else $idsucursal=1;


require("../datos/cado.php");
require("../negocio/cls_sucursal.php");
//include_once("../negocio/cls_movimiento.php"); 

date_default_timezone_set('America/Bogota');

@require("xajax_compra.php");
?>

<html> 

<LINK REL="stylesheet" TYPE="text/css" HREF="estilo.css">
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<head>

<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->


<script language="JavaScript" src="cal.js"></script>

<style>
.fc_main { background: #DDDDDD; border: 1px solid #000000; font-family: Verdana; font-size: 10px; }
.fc_date { border: 1px solid #D9D9D9;  cursor:pointer; font-size: 10px; text-align: center;}
.fc_dateHover, TD.fc_date:hover { cursor:pointer; border-top: 1px solid #FFFFFF; border-left: 1px solid #FFFFFF; border-right: 1px solid #999999; border-bottom: 1px solid #999999; background: #E7E7E7; font-size: 10px; text-align: center; }
.fc_wk {font-family: Verdana; font-size: 10px; text-align: center;}
.fc_wknd { color: #FF0000; font-weight: bold; font-size: 10px; text-align: center;}
.fc_head { background: #000066; color: #FFFFFF; font-weight:bold; text-align: left;  font-size: 11px; }
</style>



</script>
<?php $xajax->printJavascript();?>
<script>

function nuevaCompra(){
error=true;
if(reporte.txtAperturaCaja.value=='0'){
//alert('              NO HAY APERTURA DE CAJA       \r  (No Se Guardarán Los Documentos Al Contado)');
xajax_abrirlacaja();
open("frm_compra.php",'_self');


}
open("frm_compra.php",'_self');
}

</script>
<title>COMPRAS</title>
</head>

<body> 
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">LISTADO COMPRAS</div>
<?php 

require("../negocio/cls_movimiento.php");
$objMovimiento = new clsMovimiento();
		
	  $apert=$objMovimiento->comprobarAperturaCaja(1,$idsucursal,$fechaproceso);
	  $aperturaCaja = $apert->fetchObject();
	  
?>

<form name='reporte' action="list_compra.php" method="POST" >
<fieldset ><legend>Compras</legend>
<table>
 <tr>
 <td id='aki'> </td>
 </tr>
 <tr><td></td></tr>
<tr><td> </td></tr>
<tr><td> </td></tr>
<tr><td> </td></tr>
<tr>
  <td>

    
  <table width="923">
    <tr>
      <td class="alignright">Tipo:</td>
        <td><select name="cboFP">
          <option value="0">Todas</option>
          <option value="A">Contado</option>
          <option value="B">Credito &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   </option>
          </select>      </td>
        <td class="alignright">Fecha Inicial:</td>
        <td><INPUT name="txtFecha1" value="<?php 
	function restaUnMEs($fecha){	
	list($year,$mon,$day) = explode('-',$fecha);	
	return date('Y-m-d',mktime(0,0,0,$mon-1,$day,$year));		
	} 
	
	if(isset($_POST['txtFecha1'])){ echo $_POST['txtFecha1'];}else{ echo restaUnMEs($fechaproceso);}


?>" size="12" title="año-mes-dia" READONLY>
          <button type="button" id="btnCalendar1" class="boton"><img src="../imagenes/b_views.png"> </button>
        <script type="text/javascript">//<![CDATA[
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields("btnCalendar1", "txtFecha1", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td class="alignright">Nro. Doc.:</td>
        <td><input type="text" name="txtNumero" size="13"></td>
        <td>&nbsp;</td>
        <td><input type="submit" name="Generar" value="BUSCAR" ></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    <tr>
      <td width="85" class="alignright">Tipo Doc.:</td>
        <td width="133"><select name="cboTD">
          <option value="0">Todos</option>
          <option value="3">BOLETA </option>
          <option value="4">FACTURA</option>
          </select></td>
        <td width="101" class="alignright">Fecha Final:</td>
        <td width="146"><INPUT name="txtFecha2" value="<?php echo $fechaproceso;?>" size="12" title="año-mes-dia" READONLY>
          <button type="button" id="btnCalendar2" class="boton"><img src="../imagenes/b_views.png"> </button>
        <script type="text/javascript">//<![CDATA[
var cal2 = Calendar.setup({
  onSelect: function(cal2) { cal2.hide() },
  showTime: false
});
cal.manageFields("btnCalendar2", "txtFecha2", "%Y-%m-%d");
//"%Y-%m-%d %H:%M:%S"
//]]></script></td>
        <td width="87" class="alignright">Estado:</td>
        <td width="108"><select name="estado"><option value="0">Todas</option><option value="N">Normal</option><option value="A">Anuladas</option></select></td>
        <td width="39">&nbsp;</td>
        <td width="107"><input type="hidden" name="txtAperturaCaja" id="txtAperturaCaja" value="<?php echo $aperturaCaja->resultado?>" >
  <input type="button" value="NUEVA COMPRA" onClick="nuevaCompra();">  </td>
        <td width="15">&nbsp;</td>
        <td width="60">&nbsp;</td>
      </tr>
    </table>
</td></tr>
</table>
</fieldset>
</form>
<br>
<div id="divListaCompra" style="height:500px;">
<fieldset id="Filed1" style="height:500px;"><legend>Lista</legend>

<?php

if(isset($_POST['txtFecha1'])){
	if($_POST['txtFecha1']>$_POST['txtFecha2']){
		echo "<script>alert('Fecha Final Debe Ser Mayor a la Fecha Inicial')</script>";
		echo "<META HTTP-EQUIV=Refresh CONTENT='1;URL= list_compra.php'>";
	
	}
	}

echo "<table class='registros' name='resultado' width='100%'  >";

echo "<tr><th>Numero Documento</th>";
echo "<th>Tipo Documento</th>";
echo "<th>Forma de Pago</th>";
echo "<th>Fecha</th>";

echo "<th>SubTotal</th>";
echo "<th> IGV </th>";
echo "<th> Total </th>";

echo "<th>Proveedor</th>";
echo "<th>Responsable</th>";
echo "<th>Comentario</th>";
echo "<th>Estado</th>";
echo "<th colspan='3'>OPCION</th></tr>";


//1 -> compra


if(isset($_POST['txtFecha1'])){
	$rstCompras=$objMovimiento->consultarCompra(1,$idsucursal,$_POST['cboTD'],$_POST['txtFecha1'],$_POST['txtFecha2'],$_POST['estado'],$_POST['txtNumero'],$_POST['cboFP']);
}else{
$rstCompras=$objMovimiento->consultarCompra(1,$idsucursal,0,NULL,NULL,0,NULL,0);
}

while($datoCompras = $rstCompras->fetchObject()){
$cont++;
if($cont%2) $estilo="par"; else $estilo="impar";
?>
<tr <?php if($datoCompras->estado=='A'){echo "style='color:#FF0000'";}?> align="center" class="<?php echo $estilo?>">
<td><?php echo $datoCompras->numero?></td>
<td><?php echo $datoCompras->nomDoc?></td>
<td><?php if($datoCompras->FormaPago=='A'){ echo "Contado"; }else{ echo "Credito";}?></td>
<td><?php echo $datoCompras->fecha?></td>

<td><?php if($datoCompras->moneda=='S'){echo "S/. ";}else{echo "$ ";}?><?php echo $datoCompras->SubTotal?></td>
<td><?php if($datoCompras->moneda=='S'){echo "S/. ";}else{echo "$ ";}?><?php echo $datoCompras->Igv?></td>
<td><?php if($datoCompras->moneda=='S'){echo "S/. ";}else{echo "$ ";}?><?php echo $datoCompras->Total?></td>

<td><?php echo $datoCompras->nomProveedor ." ".$datoCompras->apeProveedor?></td>
<td><?php echo $datoCompras->nomResponsable ." ".$datoCompras->apeResponsable?></td>
<td><?php if($datoCompras->comentario!=""){echo $datoCompras->comentario;}else{echo "-";}?></td>
<td><?php if($datoCompras->estado=='N'){echo "Normal";}else{ echo "Anulado";} ?></td>
<td><a href="frm_detallecompra.php?IdMov=<?php echo $datoCompras->idmov?>">Ver Detalle</a></td>
<td>
<?php
$abrir="window.open('frm_cuotascompras.php?accion=VER&origen=DOC&idcompra=".$datoCompras->idmov."','_blank','width=380,height=700');";

if($datoCompras->FormaPago=='B'){
	echo "<a href='#' onClick=".$abrir.">Ver Cuotas</a>";
}else{
	?>
-
<?php
}
?>
</td>
<td><a <?php if($datoCompras->estado=='N') echo 'href="../negocio/cont_compra.php?accion=ANULAR&IdMov='.$datoCompras->idmov.'"' ?>>Anular</a></td>
</tr>


<?php

}
//fin while
echo "</table>";


?>

</fieldset>
</div>

</body>
</html>