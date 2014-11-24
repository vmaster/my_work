<?php
require("../datos/cado.php");
require("cls_marca.php");
controlador($_GET['accion']);
//header('Location: ../presentacion/list_marca.php');

function controlador($accion)
{
  $ObjMarca = new clsMarca();
  if($accion=='NUEVO'){  
	$ObjMarca->insertar($_POST['txtIdMarca'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], 'N');  
	
	if($_GET['origen']=='LIST'){
		header('Location: ../presentacion/list_marca.php');
	}else{?><script>window.open('../presentacion/mant_producto.php?accion=<?php echo $_GET['accionprod'];?>&IdProducto=<?php echo $_GET['idprod'];?>','mainFrame');window.close()</script><?php } 
	}    
  if($accion=='ACTUALIZAR'){
	$ObjMarca->actualizar($_POST['txtIdMarca'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], 'N');
	header('Location: ../presentacion/list_marca.php');
	}
  if($accion=='ELIMINAR'){
  	require("../negocio/cls_producto.php");
	$objProducto = new clsProducto();
  	$rst=$objProducto->buscar(NULL, NULL, NULL, $_GET['idMarca']);
	$dato = $rst->rowCount();
	
	if($dato>0){
		echo "<script>alert('Esta Marca No Se Puede Eliminar Ya QUe Se Esta Siendo Usada Por Productos')</script>";
	}else{
		$ObjMarca->eliminar($_GET['idMarca']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_marca.php'>";
	}
	
  if($accion=='CONSULTAR')
	return $ObjMarca->consultar(); 
  if($accion=='BUSCAR')
	return $ObjMarca->buscar($_POST['txtIdMarca'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], $_POST['cboEstado']); 
}
?>