<html>
<head>
</head>

<body>
<?php  
require("../datos/cado.php");

function genera_cboSucursal($seleccionado)
{

	require("../negocio/cls_sucursal.php");
	$ObjLibro = new clsSucursal();
	$consulta = $ObjLibro->consultar();

	echo "<select name='cboSucursal' id='cboSucursal'>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}
?>

Sucursal: <?php echo genera_cboSucursal(0)?>
</body>
</html>
