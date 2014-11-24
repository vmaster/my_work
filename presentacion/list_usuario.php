<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<style type="text/css">
<!--
.Estilo1 {font-size: 9px}
.Estilo2 {font-size: 10px; }
.Estilo3 {font-size: 12px; }
.Estilo4 {font-size: 12px; font-weight: bold; }
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
<div id="titulo01">LISTADO DE USUARIOS</div>
<form name="form1" method="post" action="mant_usuario.php?accion=NUEVO" >

  <table width="100%" class="tablaint" align="center">
    <tr>
      <th width="54">Código</th>
      <th width="93">Login</th>
      <!--<td width="104"><div align="center" class="Estilo4">PASSWORD</div></td>-->
      <th width="181">Usuario</th>
      <th width="107">Último acceso</th>
      <th width="127">Tipo usuario </th>
      <th width="60">Estado</th>
      <th colspan="2">Operaciones</th>
    </tr>
    <?php
require("../datos/cado.php");
require("../negocio/cls_usuario.php");
$objUsuario = new clsUsuario();
$rst = $objUsuario->consultar();
while($dato = $rst->fetchObject())
{
	$cont++;
   if($cont%2) $estilo="par";else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
      <td height="23"><?php echo $dato->Idusuario?>&nbsp;</td>
      <td><?php echo $dato->US?> &nbsp;</td>
      <!--<td><div align="center" class="Estilo4"><?php echo md5($dato->PW)?>&nbsp;</div></td>-->
      <td><?php echo $dato->Nombres ." ". $dato->Apellidos?></td>
      <td><?php echo $dato->UltimoAcceso?>&nbsp;</td>
      <td><?php echo $dato->Descripcion?>&nbsp;</td>
      <td><?php echo $dato->Estado?></td>
      <td width="58"><a href="mant_usuario.php?accion=ACTUALIZAR&IdUsuario=<?php echo $dato->Idusuario?>"><img src="../imagenes/editar_.jpg" width="16" height="16" />Actualizar</a></td>
      <td width="59"><a href="../negocio/cont_usuario.php?accion=ELIMINAR&Idusuario=<?php echo $dato->Idusuario;?>"><img src="../imagenes/eliminar.jpg" width="16" height="16" />Eliminar</a></td>
    </tr>
    <?php 
	}
?>
  <tr>
    <th colspan="8"><input name="NUEVO" type="submit" id="NUEVO" value="NUEVO" /></th>
  </tr>
</table>

</form>
