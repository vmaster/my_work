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
<div id="titulo01">LISTADO DE ROLES ASIGNADOS</div>
<form action="mant_rolpersona.php?accion=NUEVO&IdPersona=<?php echo $_GET['IdPersona'];?>" method="POST">
<table width="400" class="tablaint" align="center">
<tr>
<th>Código</th>
<th>Persona</th>
<th>Rol</th>
<th colspan="2">Operaciones</th>
</tr>
<tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_rolpersona.php");
$objRolPersona = new clsRolPersona();
$rst = $objRolPersona->consultarrolpersona($_GET['IdPersona'],NULL);
while($dato = $rst->fetchObject())
{
?>
<td><?php echo $dato->IdRolPersona?></td>
<td><?php echo $dato->Nombres." ".$dato->Apellidos?></td>
<td><?php echo $dato->Descripcion?></td>

<td><a href="../negocio/cont_rolpersona.php?accion=ELIMINAR&IdRolPersona=<?php echo $dato->IdRolPersona;?>&IdPersona=<?php echo $_GET['IdPersona']?>"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
</tr>
<?php }?>
</table>
<table width="400" align="center">
  <tr>
    <th><input type='submit' name = 'NUEVO' value = 'NUEVO'>
      <input type='button' name = 'cancelar' value='CANCELAR' onClick="javascript:window.open('list_persona.php','_self')"></th>
  </tr>
</table>

<p><a href="main.php">  </p>

</form>
</body>
</html>