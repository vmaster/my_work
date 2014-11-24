<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<html>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<head><title>OPCI&Oacute;N MEN&Uacute;</title></head>
<body>
<br>
<form action=<?php echo '../negocio/cont_menu.php?accion='.$_GET['accion']?> method='POST'>
<table width="400" class="tablaint">
<?php if($_GET['accion']=='ACTUALIZAR'){?>
  <tr>
    <td class="alignright">Código :</td>
    <td><input type='text' name = 'idopcionmenu' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $_GET['idopcionmenu'];?>' readonly="true">
</td>
  </tr>
      <?php 
}
else
{
?>
      <input type='text' name = 'idopcionmenu' value = '<?php echo $_GET['idopcionmenu'];?>' readonly="true" style="display:none">
      <?php
}?>
      <?php
require("../datos/cado.php");
if($_GET['accion']=='ACTUALIZAR'){
require("../negocio/cls_menu.php");
$ObjMenu = new clsMenu();
$rst = $ObjMenu->buscar($_GET['idopcionmenu'],'','');
$dato = $rst->fetchObject();
}?>
  <tr>
    <td class="alignright">Link menu :</td>
    <td><input type='text' name = 'linkmenu' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->linkmenu;?>'></td>
  </tr>
  <tr>
    <td class="alignright">Nombre menu :</td>
    <td><input type='text' name = 'nombremenu' value = '<?php if($_GET['accion']=='ACTUALIZAR')
echo $dato->nombremenu;?>'></td>
  </tr>
  <?php
function genera_cboModulo($seleccionado)
{

   global $cnx;
   $rst2 =$cnx->query("select * from modulo"); 

	echo "<select name='cboModulo' id='cboModulo'>";
	while($registro=$rst2->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
				echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}?>
  <tr>
    <td class="alignright">Modulo :</td>
    <td><?php if($_GET['accion']=='ACTUALIZAR')
echo genera_cboModulo($dato->idmodulo); else genera_cboModulo(0);?></td>
  </tr>
  <tr>
    <th colspan="2"><input type='submit' name = 'grabar' value='GRABAR'>
      <input type='reset' name = 'limpiar' value='LIMPIAR'></th>
    </tr>
</table>
</form>
</body>
</html>