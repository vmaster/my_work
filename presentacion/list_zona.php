<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
.Estilo2 {font-size: 12px; font-weight: bold; }
-->
</style>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css" />
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
<div id="titulo01">LISTADO DE ZONAS</div>
<form name="form1" method="post" action="mant_zona.php?accion=NUEVO">

  <table width="500" class="tablaint" align="center">
    <tr>
      <th>Código</td>
      <th>Descripción</td>
    <th colspan="2">Operaciones</td>    </tr>
	<?php
	require("../datos/cado.php");
	require("../negocio/cls_zona.php");
	$objZona = new clsZona();
	$rst = $objZona->consultar();
	while($dato=$rst->fetchObject())
	{
	?>
    <tr>
      <td><div align="center"><?php echo $dato->idzona?></div></td>
      <td><div align="center"><?php echo $dato-> descripcion?></div></td>
      <td><div align="center"><a href="mant_zona.php?accion=ACTUALIZAR&IdZona=<?php echo $dato->idzona?>&descrip=<?php echo $dato-> descripcion?>"><img src="../imagenes/editar_.jpg" width="16" height="16" />Actualizar</a></div></td>
      <td><div align="center"><a href="../negocio/cont_zona.php?accion=ELIMINAR&IdZona=<?php echo $dato->idzona;?>"><img src="../imagenes/eliminar.jpg" width="16" height="16" />Eliminar</a></div></td>
    </tr>
	<?php
	}
	?>
  <tr>
    <th colspan="4"><input name="NUEVO" type="submit" id="NUEVO" value="NUEVO" /></th>
  </tr>
</table>

</form>
