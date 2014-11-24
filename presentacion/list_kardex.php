<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_kardex.php");
$objKardex = new clsKardex();
$rst1 = $objKardex->consultar($_GET['IdProducto']);
$dato1 = $rst1->fetchObject()
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<div id="titulo01">KARDEX DEL PRODUCTO: <?php echo  $dato1->pro?></div>
<form action="" method="get">
<table width="800" class="tablaint" align="center">
<tr>
    <th>Tipo movimiento</th>
    <th>Documento</th>
    <th>Stock anterior</th>
    <th>Ingreso</th>
    <th>Salida</th>
    <th>Stock actual</th>
	<th>Operación</th>
  </tr>
  
<?php
$rst = $objKardex->consultar($_GET['IdProducto']);
while($dato = $rst->fetchObject())
{
	$cont++;
	   if($cont%2) $estilo="par"; else $estilo="impar";
?>
  <tr class="<?php echo $estilo;?>">
     <td><div align="center"><?php echo $dato->movimiento ?>&nbsp;</div></td>
    <td width="169"><div align="center"><?php echo $dato->tipodoc." ".$dato->doc ?>&nbsp;</div></td>
    <td><div align="center"><?php echo $dato->stockanterior ?>&nbsp;</div></td>
    <?php if($dato->estado=='N'){?>
	<td><div align="center"><?php if($dato->movimiento=='VENTA' or $dato->tipodoc=='E' or $dato->tipodoc=='GRE') echo "0"; else  echo $dato->cantidad ?>&nbsp;</div></td>
    <td><div align="center"><?php if($dato->movimiento=='VENTA' or $dato->tipodoc=='E' or $dato->tipodoc=='GRE') echo $dato->cantidad; else  echo "0" ?>&nbsp;</div></td>
	<?php }else{?>
	<td><div align="center"><?php if($dato->movimiento=='VENTA' or $dato->tipodoc=='E' or $dato->tipodoc=='GRE') echo $dato->cantidad; else  echo "0"; ?>&nbsp;</div></td>
    <td><div align="center"><?php if($dato->movimiento=='VENTA' or $dato->tipodoc=='E' or $dato->tipodoc=='GRE') echo "0"; else  echo $dato->cantidad; ?>&nbsp;</div></td>
	<?php }?>
    <td><div align="center"><?php //if($dato->movimiento=='VENTA' or $dato->tipodoc=='E' or $dato->tipodoc=='GRE') echo ($dato->stockanterior - $dato->cantidad); else echo ($dato->stockanterior + $dato->cantidad) ; 
	echo $dato->stockactual?>&nbsp;</div></td>
  <td><div align="center"> <?php if($dato->estado=='N') { echo 'NUEVO';} else {echo 'ANULACI&Oacute;N';} ?></div></td>
  </tr> 
  <?php  } ?>
</table>
<table width="800" align="center">
  <tr>
    <th><input name="txtCancelar" type="button" id="txtCancelar" value="CANCELAR" onClick="javascript:window.open('list_producto.php','_self')"/>
      <input name="btnImprimir" type="button" id="btnImprimir" value="IMPRIMIR" onClick="javascript:window.open('pdf_kardexproducto.php?IdProducto=<?php echo $_GET['IdProducto']?>','_blank')"/></th>
  </tr>
</table>

</form>
<p>
  <label></label>
</p>
<p>&nbsp;</p>
</body>
</html>
