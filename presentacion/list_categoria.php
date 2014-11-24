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
<div id="titulo01">LISTADO DE CATEGOR&Iacute;AS</div>
<form action="mant_categoria.php?accion=NUEVO" method="POST">

<table width="550" class="tablaint" align="center">
<tr>
<th>Descripción</td>
<th>Abreviatura</td>
<th>Categoría Ref</td>
<th>Nivel</td>
<!--<td>ESTADO</td>-->
<th colspan="2">Operaciones</th>
</tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_categoria.php");
$objCategoria = new clsCategoria();
$rst = $objCategoria->MostrarArbolCategorias();
while($dato = $rst->fetchObject())
{
$cont++;
   if($cont%2) $estilo="par"; else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo str_replace(' ','&nbsp;',$dato->Descripcion);?></td>
<td><?php echo $dato->Abreviatura?></td> 
<td><?php 

if(isset($dato->DescripcionRef)){

echo $dato->DescripcionRef;

}else{
echo "** No Ref **";
}

?></td>
<td><?php echo $dato->Nivel?></td>
<!--<td><?php //if($dato->Estado='N'){echo "NORMAL"; }else{ echo "DESACTIVADO";}?></td> -->

<td><a href="mant_categoria.php?accion=ACTUALIZAR&idCategoria=<?php echo $dato->IdCategoria;?>"> <img src="../imagenes/editar_.jpg" width="16" height="16">Actualizar </a></td>
<td><a href="../negocio/cont_categoria.php?accion=ELIMINAR&idCategoria=<?php echo $dato->IdCategoria;?>"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
</tr>
<?php 

}
?>
  <tr>
    <th colspan="6"><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>

</form>
</body>
</html>