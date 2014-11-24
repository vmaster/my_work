<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo2 {font-size: 12px}
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
<div id="titulo01">LISTADO DE CONCEPTOS DE PAGO</div>
<form id="form1" name="form1" method="post" action="mant_conceptopago.php?accion=NUEVO">
  <table width="700" class="tablaint" align="center">
    <tr>
      <th>Código</td>
      <th>Descripción</td>
      <th>Tipo</th>
      <th colspan="2">Operaciones</th>
    </tr>
	<?php
	require("../datos/cado.php");
	require("../negocio/cls_conceptopago.php");
	$objConcepto = new clsConceptoPago();
	$rst = $objConcepto->consultar();
	while($dato = $rst->fetchObject())
	{
		$cont++;
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	?>
    <tr class="<?php echo $estilo;?>">
      <td><div align="center"><span class="Estilo2"><?php echo $dato->idconceptopago?></span></div></td>
      <td><span class="Estilo2"><?php echo $dato->descripcion?></span></td>
      <td><div align="center"><span class="Estilo2"><?php echo $dato->tipo?></span></div></td>
      <td><div align="center" class="Estilo2"><strong><a href="mant_conceptopago.php?accion=ACTUALIZAR&IdConcepto=<?php echo $dato->idconceptopago?>&descrip=<?php echo $dato->descripcion?>&tip=<?php echo $dato->tipo?>"><img src="../imagenes/editar_.jpg" width="16" height="16" />Actualizar</a></strong></div></td>
      <td><div align="center" class="Estilo2"><strong><a href="../negocio/cont_conceptopago.php?accion=ELIMINAR&amp;IdConcepto=<?php echo $dato->idconceptopago;?>"><img src="../imagenes/eliminar.jpg" width="16" height="16" />Eliminar</a></strong></div></td>
    </tr>
    
	<?php
	}
	?>
<tr>
    <th colspan="5"><input name="Nuevo" type="submit" id="Nuevo" value="Nuevo" /></th>
  </tr>
</table>

</form>
