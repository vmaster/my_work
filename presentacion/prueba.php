<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
<table>
<tr>
<td>Id</td>
<td>Cat</td>
<td>CatRef</td>
<td>Nivel</td>
</tr>
<?php
require("../datos/cado.php");
require("../negocio/cls_categoria.php");
$objCategoria = new clsCategoria();
$rst = $objCategoria->MostrarArbolCategorias();
while($dato = $rst->fetchObject())
{
?>
<tr>
<td><?php echo $dato->IdCategoria;?></td>
<td><?php echo str_replace(' ','&nbsp;',$dato->Descripcion);?></td>
<td><?php echo $dato->IdCategoriaRef;?></td>
<td><?php echo $dato->Nivel;?></td>
</tr><?php
}?>
</table>
</body>
</html>