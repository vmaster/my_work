<?php
require("../datos/cado.php");
require("cls_color.php");
controlador($_GET['accion']);
//header('Location: ../presentacion/list_color.php');

function controlador($accion)
{
  $ObjColor = new clsColor();
  if($accion=='NUEVO'){  
	$ObjColor->insertar($_POST['txtIdColor'], $_POST['txtNombre'], $_POST['txtCodigo'], 'N');  
	
	if($_GET['origen']=='LIST'){
		header('Location: ../presentacion/list_color.php');
	}else{?><script>window.open('../presentacion/mant_producto.php?accion=<?php echo $_GET['accionprod'];?>&IdProducto=<?php echo $_GET['idprod'];?>','mainFrame');window.close()</script><?php } 
	}    
  if($accion=='ACTUALIZAR'){
	$ObjColor->actualizar($_POST['txtIdColor'], $_POST['txtNombre'], $_POST['txtCodigo'], 'N');
	header('Location: ../presentacion/list_color.php');
	}
  if($accion=='ELIMINAR'){
  	require("../negocio/cls_producto.php");
	$objProducto = new clsProducto();
  	$rst=$objProducto->buscar(NULL, NULL, NULL, $_GET['idColor']);
	$dato = $rst->rowCount();
	
	if($dato>0){
		echo "<script>alert('Esta color no se puede eliminar ya que esta siendo usada por algun producto')</script>";
	}else{
		$ObjColor->eliminar($_GET['idColor']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_color.php'>";
	}
	
  if($accion=='CONSULTAR')
	return $ObjColor->consultar(); 
  if($accion=='BUSCAR')
	return $ObjColor->buscar($_POST['txtIdColor'], $_POST['txtNombre'], $_POST['txtCodigo'], $_POST['cboEstado']); 
}
?>