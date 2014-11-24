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
<div id="titulo01">LISTADO DE TALLAS</div>
<form action="mant_talla.php?accion=NUEVO" method="POST">

<table width="400" class="tablaint" align="center">
<tr>
<th>Nombre</th>
<th>Abreviatura</th>
<th colspan="2">Operaciones</th>
</tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_talla.php");
$objTalla = new clsTalla();
$rst = $objTalla->consultar();
while($dato = $rst->fetchObject())
{
$cont++;
   if($cont%2) $estilo="par"; else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo $dato->Nombre?></td>
<td><?php echo $dato->Abreviatura?></td> 


<td><a href="mant_talla.php?accion=ACTUALIZAR&idTalla=<?php echo $dato->IdTalla;?>"> <img src="../imagenes/editar_.jpg" width="16" height="16">Actualizar </a></td>
<td><a href="../negocio/cont_talla.php?accion=ELIMINAR&idTalla=<?php echo $dato->IdTalla;?>"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
</tr>
<?php }?>
  <tr>
    <th colspan="4"><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>

</form>
</body>
</html>