<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<html>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<body>
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
<div id="titulo01">LISTADO DE OPCIONES DE MEN&Uacute;</div>
<form action="mant_menu.php?accion=NUEVO" method="POST">

<table width="500" class="tablaint" align="center">
<tr>
<th>Código</th>
<th>Link menú</th>
<th>Nombre menú</th>
<th>Modulo</th>
<th colspan="2">Operaciones</th>
</tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_menu.php");
$ObjMenu = new clsMenu();
$rst = $ObjMenu->consultar(0);
while($dato = $rst->fetchObject())
{
	$cont++;
   if($cont%2) $estilo="par";else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo $dato->idopcionmenu?></td>
<td><?php echo $dato->linkmenu?></td>
<td><?php echo $dato->nombremenu?></td>
<td><?php echo $dato->modulo?></td> 
<td><a href="mant_menu.php?accion=ACTUALIZAR&idopcionmenu=<?php echo $dato->idopcionmenu;?>"> <img src="../imagenes/editar_.jpg" width="16" height="16">Actualizar </a></td>
<td><a href="../negocio/cont_menu.php?accion=ELIMINAR&idopcionmenu=<?php echo $dato->idopcionmenu;?>"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
</tr>
<?php }?>
  <tr>
    <th colspan="6"><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>

</form>
</body>
</html>