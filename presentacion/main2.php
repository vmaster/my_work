<?php
session_start();
//if(!isset($_SESSION['usuario'])) die("Acceso denegado");
if(!isset($_SESSION['Usuario']) && !isset($_SESSION['IdTipoUsuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<html>
<head>
<title>SISTEMA DE COMERCIALIZACIÓN IMAN</title>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<script language="javascript">
function Desplegar(idmodulo){
	if(document.getElementById(idmodulo).style.display==""){
		document.getElementById(idmodulo).style.display="none";
	}else{
		document.getElementById(idmodulo).style.display="";
	}
}
</script>
</head>

<body>
<div id="barramenusup"> <!-- inicio menu superior -->
			<ul>
            		<li><a><?php echo $_SESSION['Sucursal'];?></a></li>
            		<li><a>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></a></li>
                    <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesión </a></li>
            </ul>
</div>
<div id="menu">
<table>
<tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_acceso.php");
global $cnx;
$rst1 = $cnx->query("select distinct m.idmodulo,m.descripcion from modulo m inner join opcionmenu op on op.idmodulo=m.idmodulo inner join acceso a on a.idopcionmenu=op.idopcionmenu where idtipousuario=".$_SESSION['IdTipoUsuario']." order by m.idmodulo asc");
	while($dato1 = $rst1->fetchObject())
	{
			?><td valign="top"><ul>
			 <li><a href="#" onClick="Desplegar(<?php echo $dato1->idmodulo;?>)"><img src="../imagenes/vineta01.gif" width="16" height="16"> <?php echo $dato1->descripcion;?></a></li></ul>
             <div id="<?php echo $dato1->idmodulo;?>" style="display:none;"><ul>
			<?php 
		$ObjAcceso = new clsAcceso();
		$rst = $ObjAcceso->consultar($_SESSION['IdTipoUsuario'],$dato1->idmodulo);
			while($dato = $rst->fetchObject())
			{
			?>
			 <li><a href="<?php echo $dato->linkmenu;?>">&nbsp;&nbsp;&nbsp;&nbsp;<img src="../imagenes/vineta01.gif" width="16" height="16">&nbsp;&nbsp; <?php echo $dato->nombremenu;?></a></li>
			<?php 
			}	?>
            </ul>
            </div>
</td>
<?php }
?>
</table>
</div>
</body>
</html>