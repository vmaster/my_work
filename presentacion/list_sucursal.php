<?php
/*session_start();
//if(!isset($_SESSION['usuario'])) die("Acceso denegado");
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}*/
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
<form action="mant_sucursal.php?accion=NUEVO" method="POST">

<table width="400" class="tablaint">
<tr>
<th>Código</th>
<th>Nombre</th>
<th>Dirección</th>
<th>RUC</th>
<th colspan="2">Operaciones</th>
</tr>
<tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_sucursal.php");
$objSucursal = new clsSucursal();
$rst = $objSucursal->consultar();
while($dato = $rst->fetchObject())
{
?>
<td><?php echo $dato->IdSucursal?></td>
<td><?php echo $dato->Nombre?></td>
<td><?php echo $dato->Direccion?></td> 
<td><?php echo $dato->Ruc?></td> 
<td><a href="mant_sucursal.php?accion=ACTUALIZAR&IdSucursal=<?php echo $dato->IdSucursal;?>"> <img src="../imagenes/editar_.jpg" width="16" height="16">Actualizar </a></td>
<td><a href="../negocio/cont_sucursal.php?accion=ELIMINAR&IdSucursal=<?php echo $dato->IdSucursal;?>"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
</tr>
<?php }?>
</table>
<table width="400">
  <tr>
    <th><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>

</form>
</body>
</html>