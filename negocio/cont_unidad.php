<?php
require("../datos/cado.php");
require("cls_unidad.php");
controlador($_GET['accion']);
if($_GET['origen']=='LIST'){
	header('Location: ../presentacion/list_unidad.php');
}else{?><script>window.open('../presentacion/mant_producto.php?accion=<?php echo $_GET['accionprod'];?>&IdProducto=<?php echo $_GET['idprod'];?>','mainFrame');window.close()</script><?php } 

function controlador($accion)
{
  $ObjUnidad = new clsUnidad();
  if($accion=='NUEVO')	      
	return $ObjUnidad->insertar($_POST['txtIdUnidad'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], $_POST['cboTipo']);   
  if($accion=='ACTUALIZAR')
	return $ObjUnidad->actualizar($_POST['txtIdUnidad'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], $_POST['cboTipo']);
  if($accion=='ELIMINAR')
	return $ObjUnidad->eliminar($_GET['IdUnidad']);
  if($accion=='CONSULTAR')
	return $ObjUnidad->consultar(); 
  if($accion=='BUSCAR')
	return $ObjUnidad->buscar($_GET['txtUnidad'], $_POST['txtDescripcion']); 
}
?>