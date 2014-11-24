<?php
session_start();
//if(!isset($_SESSION['usuario'])) die("Acceso denegado");
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<title>Documento sin título</title>
</head>

<body>
<div id="barramenusup" align="right" style="height:40px"> <!-- inicio menu superior -->
<table width="100%">
<tr>
<td width="250"></td>
<td align="center" width="50%"><font color="#003333" size="+1"><b>SISTEMA DE COMERCIALIZACI&Oacute;N</b></font></td>
<td>
<ul style="float:right">
	<li><?php echo $_SESSION['Sucursal'];?></li>
	<li><b>Bienvenido:</b><img src="../imagenes/user_suit.png" alt="usuario"><?php echo $_SESSION['Usuario']?></li>
    <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesi&oacute;n"> Cerrar sesi&oacute;n </a></li>
</ul>
</td>
</tr>
</table>
</div><!-- inicio menu superior -->
</body>
</html>
