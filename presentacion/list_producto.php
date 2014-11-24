<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require("../datos/cado.php");

function genera_cboCategoria($seleccionado)
{
	require("../negocio/cls_categoria.php");
	$objCategoria = new clsCategoria();
	$rst2 = $objCategoria->MostrarArbolCategorias();
	echo "<select name='cboCategoria' id='cboCategoria'>";
	echo "<option value='0' selected='selected'>TODAS</option>";
	while($dato2 = $rst2->fetchObject())
	{
	
		if(trim($dato2->Descripcion)==$seleccionado);
		echo "<option value='".$dato2->IdCategoria."' ".$seleccionar.">";
		echo str_replace(' ','&nbsp;',$dato2->Descripcion);
		echo "</option>";
	}
	echo "</select>";
	global $cnx;
	$cnx=null;
	require("../datos/cado.php");
}

function genera_cboMarca($seleccionado)
{
	require("../negocio/cls_marca.php");
	$objMarca = new clsMarca();
	$rst = $objMarca->consultar();

	echo "<select name='cboMarca' id='cboMarca'>";
	echo "<option value='0' selected='selected'>TODAS</option>";
	while($registro = $rst->fetch())
	{
		
		
		echo "<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	echo "</select>";
}
require("xajax_producto.php");
$xajax->printJavascript();
?>
<script>
function listadoproductos(){
<?php 	$flistadoproductos->printScript(); ?>
}
</script>
<html>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<body onLoad="listadoproductos()">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<center>
<img src="../imagenes/encabezado.gif">
</center>
<div id="titulo01">LISTADO DE PRODUCTOS</div>
 <form id="form1" name="form1" method="post" action="mant_producto.php?accion=NUEVO">

<div id='divParametros' style="height:100px;overflow:auto;">
  <fieldset>
  <legend>Criterios de búsqueda</legend>
  <table  border="0">
  <tr>
  <td width="98" class="alignright">Descripcion : </td>
  <td><label>
    <input name="txtDescripcion" type="text" id="txtDescripcion" style="text-transform:uppercase">
  </label></td>
  <td width="60" class="alignright">Marca :</td>
  <td width="99"><?php echo genera_cboMarca(""); ?></td>
  <td>&nbsp;</td>
  <td width="51">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="120">&nbsp;</td>
  <td width="123"><label></label></td>
  <td></td>
  </tr>
    <tr>
      <td width="98" class="alignright">Categoria : </td>
      <td width="213"><?php echo genera_cboCategoria("");?></td>
      <td>&nbsp;</td>
      <td>          <input name = 'BUSCAR' type='button' id="BUSCAR" value = 'BUSCAR' onClick="listadoproductos()" />          </td>
      <td width="62"><input type='submit' name = 'NUEVO2' value = 'NUEVO'></td>
      <td colspan="2">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td width="76">&nbsp;</td>
    </tr>
  </table>
  </fieldset>
</div>

  <fieldset>
  <legend>Lista</legend>
  <div id="divReporte" style=";<?php if(isset($_GET['accion'])) echo 'overflow:auto';?>"> </div>
  </fieldset>
  <BR>
  


</form>
  <p><a href="list_unidad.php">VER UNIDADES</a></p>
</body>
</html>