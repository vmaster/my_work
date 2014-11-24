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
<div id="titulo01">LISTADO DE BIT&Aacute;CORAS</div>
<form action="mant_bitacora.php?accion=NUEVO" method="POST">
<!--<input type='submit' name = 'NUEVO' value = 'AGREGAR OPERACION'>
--></form>

<form action="list_bitacora.php?accion=BUSCAR" method="POST">
<table width="400" class="tablaint" align="center">
  <tr>
    <th colspan="2">BUSCAR OEPRACI&Oacute;N EN BIT&Aacute;CORA:</th>
    </tr>
  <tr>
    <td>Seleccione Tipo de Operaci&oacute;n:</td>
    <td><select name="TipoMovimiento">
      <option value="1">COMPRA</option>
      <option value="2">VENTA</option>
      <option value="3">CAJA</option>
      <option value="4">ALMACEN</option>
      <option value="5">PEDIDO</option>
    </select></td>
  </tr>
  <tr>
    <td>Ingrese Fecha :</td>
    <td><input type="text" name="fecha"></td>
  </tr>
  <tr>
    <td>Ingrese Apellido del Cliente:</td>
    <td><input type="text" name="ApellidoCliente"></td>
  </tr>
  <tr>
    <td>Ingrese Apellido del Responsable:</td>
    <td><input type="text" name="ApellidoResponsable"></td>
  </tr>
  <tr>
    <th colspan="2"><input type="submit" value="Enviar"></th>
    </tr>
</table>

<p>BIT&Aacute;CORA:<br></p>
<table class="tablaint">
  <tr>
<th>Código</th>
<th>Descripción</th>
<th>Operación</th>
<th>Tipo Doc</th>
<th>Número</th>
<th>Fecha y hora</th>
<th>Realizado a</th>
<th>Registrado por</th>
<th>Comentario</th>
</tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_bitacora.php");
$objBitacora = new clsBitacora();

if(isset($_GET['accion'])){
	if($_GET['accion']=='BUSCAR'){
		$rst = $objBitacora->buscar($_POST['fecha'],$_POST['ApellidoCliente'], $_POST['ApellidoResponsable'], $_POST['TipoMovimiento']);
	}
	
}else{
	$rst = $objBitacora->consultar();
}


while($dato = $rst->fetchObject())
{
	$cont++;
   if($cont%2) $estilo="par"; else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
<td><?php echo $dato->IdBitacora?></td>
<td><?php echo $dato->Descripcion?></td> 
<td><?php if($dato->Operacion=='N') echo 'Nuevo'; else echo 'Anulaci&oacute;n';?></td>
<td><?php echo $dato->abreviatura?></td> 
<td><?php echo $dato->numero?></td> 
<td><?php echo $dato->FechaHora?></td> 
<td><?php echo $dato->ApeRealizadoA.", ".$dato->NomRealizadoA?></td>
<td><?php echo $dato->ApeRealizadoPor.", ".$dato->NomRealizadoPor?></td>
<td><?php echo $dato->Comentario?></td>
</tr>
<?php }?>
</table>
<!--<p>
<a href="../negocio/cont_bitacora.php?accion=ELIMINAR">VACIAR BITACORA</a>
<p>-->
<a href="list_bitacora.php">MOSTRAR TODO</a>
</form>
</body>
</html>