<?php 
session_start();

if(!isset($_SESSION['Usuario']))
{
            header("location: ../presentacion/login.php?error=1");
}
@require("xajax_compra.php");


//$_SESSION['cuotas']==null;

?>
<?php $xajax->printJavascript();?>
<script>
function mostrarcuotas(){
	xajax_buscarcuotas(frmCuotas.txtIdMovimiento.value);
}
</script>
<html>
<head>

<!--CALENDARIO-->
<script src="../calendario/js/jscal2.js"></script>
    <script src="../calendario/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="../calendario/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/reduce-spacing.css" />
    <link rel="stylesheet" type="text/css" href="../calendario/css/steel/steel.css" />
<!--CALENDARIO-->

<script type="text/javascript">//<![CDATA[
function llamarcalendario(btnCalendar,txtFecha) {
var cal = Calendar.setup({
  onSelect: function(cal) { cal.hide() },
  showTime: false
});
cal.manageFields(btnCalendar, txtFecha, "%Y/%m/%d");
//"%Y-%m-%d %H:%M:%S"
//]]>

}</script>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="mostrarcuotas()">

<form name='frmCuotas' id="frmCuotas" action='../negocio/cont_compra.php?accion=ACTUALIZAR_CUOTA&IdMov=<?php echo $_GET['IdMov']?>' method='POST' >
<input type="hidden" name="txtIdMovimiento" id="txtIdMovimiento" value="<?php echo $_GET['IdMov'];?>">


  <fieldset><legend>Cuotas:</legend>
  <div id='divCuotas' style="height:520px;overflow:auto;">
   
   </div>
  </fieldset>
<table width="100%">
  <tr>
    <th><input type='submit' name = 'grabar' value='GUARDAR'>
      <input type='button' name = 'cancelar' value='SALIR' onClick="window.close()"></th>
  </tr>
</table>

<p>&nbsp;</p>

</form>
</body>
</html>