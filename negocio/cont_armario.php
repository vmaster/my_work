<?php
require("../datos/cado.php");
require("cls_armario.php");
controlador($_GET['accion']);

if($_GET['origen']=='LIST'){
	header('Location: ../presentacion/list_armario.php');
}else{?><script>window.open('../presentacion/mant_producto.php?accion=<?php echo $_GET['accionprod'];?>&IdProducto=<?php echo $_GET['idprod'];?>','mainFrame');window.close()</script><?php } 


function controlador($accion)
{
  $ObjArmario = new clsArmario();
  if($accion=='NUEVO')	      
	return $ObjArmario->insertar($_POST['txtIdArmario'], $_POST['txtCodigo'], $_POST['txtNombre'], $_POST['txtTotalColumnas'], $_POST['txtTotalFilas']);      
  if($accion=='ACTUALIZAR')
	return $ObjArmario->actualizar($_POST['txtIdArmario'], $_POST['txtCodigo'], $_POST['txtNombre'], $_POST['txtTotalColumnas'], $_POST['txtTotalFilas']);
  if($accion=='ELIMINAR')
	return $ObjArmario->eliminar($_GET['IdArmario']);
  if($accion=='CONSULTAR')
	return $ObjArmario->consultar(); 
  if($accion=='BUSCAR')
	return $ObjArmario->buscar($_POST['txtIdArmario'], $_POST['txtCodigo'], $_POST['txtNombre']); 
}
?>