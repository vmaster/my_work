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

<body class="barramenusup">
<div id="menu">
<?php
require("../datos/cado.php");
require("../negocio/cls_acceso.php");
global $cnx;
$rst1 = $cnx->query("select distinct m.idmodulo,m.descripcion from modulo m inner join opcionmenu op on op.idmodulo=m.idmodulo inner join acceso a on a.idopcionmenu=op.idopcionmenu where idtipousuario=".$_SESSION['IdTipoUsuario']." order by m.idmodulo asc");
	while($dato1 = $rst1->fetchObject())
	{
			?><ul>
			 <li><a href="#" onClick="Desplegar(<?php echo $dato1->idmodulo;?>)"><img src="../imagenes/vineta01.gif" width="16" height="16"> <?php echo $dato1->descripcion;?></a></li></ul>
             <div id="<?php echo $dato1->idmodulo;?>" style="display:none;"><ul>
			<?php 
		$ObjAcceso = new clsAcceso();
		$rst = $ObjAcceso->consultar($_SESSION['IdTipoUsuario'],$dato1->idmodulo);
			while($dato = $rst->fetchObject())
			{
			?>
			 <li><a href="<?php echo $dato->linkmenu;?>" target="mainFrame">&nbsp;&nbsp;&nbsp;&nbsp;<img src="../imagenes/vineta02.png" width="16" height="16">&nbsp;&nbsp; <?php echo utf8_encode($dato->nombremenu);?></a></li>
			<?php 
			}	?>
            </ul>
            </div>
<?php }
?>
</div>
</body>
</html>
