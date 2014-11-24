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
<div id="titulo01">LISTADO DE UNIDADES</div>
<form action="mant_unidad.php?accion=NUEVO" method="POST">

<table width="600" class="tablaint" align="center">
<tr>
<th>ID Unidad</th>
<th>Descripción</th>
<th>Abreviatura</th>
<th>Tipo</th>
<th>Estado</th>
<th colspan="2">Operaciones</th>
</tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_unidad.php");
$objUnidad = new clsUnidad();
$rst = $objUnidad->consultar();
while($dato = $rst->fetchObject())
{
	$cont++;
   if($cont%2) $estilo="par";else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo $dato->idunidad?></td>
<td><?php echo $dato->descripcion?></td> 
<td><?php echo $dato->abreviatura?></td> 
<td><?php if($dato->tipo=='M') { echo 'Masa';}
			elseif($dato->tipo=='L') { echo 'Longitud';}
			elseif($dato->tipo=='A') { echo 'Area';}
			elseif($dato->tipo=='O') { echo 'Otro';}?></td> 
<td><?php echo $dato->estado?></td> 
<td><a href="mant_unidad.php?accion=ACTUALIZAR&IdUnidad=<?php echo $dato->idunidad?>"> <img src="../imagenes/editar_.jpg" width="16" height="16">Actualizar </a></td>
<td><a href="../negocio/cont_unidad.php?accion=ELIMINAR&IdUnidad=<?php echo $dato->idunidad?>"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
</tr>
<?php }?>
  <tr>
    <th colspan="7"><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>

</form>
<p align="center"><a href="list_producto.php">IR A PRODUCTO</a></p>
</body>
</html>