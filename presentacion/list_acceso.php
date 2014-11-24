<?php
session_start();
if(!isset($_SESSION['Usuario']) && !isset($_GET['idtipousuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require("../datos/cado.php");
require("../negocio/cls_menu.php");
require("../negocio/cls_acceso.php");
require("../negocio/cls_tipousuario.php");
//$_GET['idtipousuario']=1;
if (isset($_GET['idtipousuario']))
{
$_SESSION['idtipousuario']=$_GET['idtipousuario'];
$tipousuario=$_SESSION['idtipousuario'];
}elseif (isset($_SESSION['idtipousuario']))
{
$tipousuario=$_SESSION['idtipousuario'];
}
$ObjTipoUsuario = new clsTipoUsuario();
$rst = $ObjTipoUsuario->buscar($tipousuario,'');
$dato = $rst->fetchObject();
$descripcion=$dato->descripcion;
?>
<html>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<body>
<div id="titulo01">PERMISOS</div>
<table width="100%" class="tablaint" align="center">
<tr>
<td colspan="2">TIPO DE USUARIO: <b><?php echo strtoupper($descripcion); ?></b></td>
</tr>
<tr valign="top">
<td width="50%">
<table width="100%" class="tablaint">
<tr>
<th width="22">N&ordm;</th>
<th width="362">MENU</th>
<th width="73">AGREGAR</th>
</tr>
<?php
$ObjMenu = new clsMenu();
$rst = $ObjMenu->consultar($tipousuario);
$j=1;
while($dato = $rst->fetchObject())
{
	$cont++;
   if($cont%2) $estilo="par";else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo $j;?></td>
<td><?php echo $dato->nombremenu?></td>
<td><a href="../negocio/cont_acceso.php?accion=NUEVO&idtipousuario=<?php echo $tipousuario; ?>&idopcionmenu=<?php echo $dato->idopcionmenu;?>"><img src="../imagenes/agregar.JPG" width="16" height="16">agregar</a></td>
</tr>
<?php 
$j++;
}?>
</table></td>
<td>
<table width="100%" class="tablaint">
<tr>
<th width="22">N&ordm;</th>
<th width="362">ACCESO</th>
<th width="73">ELIMINAR</th>
</tr>
<?php
$ObjAcceso = new clsAcceso();
$rst = $ObjAcceso->consultar($tipousuario);
$j=1;
while($dato =  $rst->fetchObject())
{
	$cont++;
   if($cont%2) $estilo="par";else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo $j;?></td>
<td><?php echo $dato->nombremenu;?></td>
<td><a href="../negocio/cont_acceso.php?accion=ELIMINAR&idacceso=<?php echo $dato->idacceso;?>"><img src="../imagenes/eliminar.jpg" width="16" height="16"></a><a href="../negocio/cont_acceso.php?accion=ELIMINAR&idacceso=<?php echo $dato->idacceso;?>">eliminar</a></td>
</tr>
<?php 
$j++;
}?>
</table></td>
</tr>
</table>
<p align="center"><a href="list_tipousuario.php">TIPO USUARIO</a></p>
</body>
</html>