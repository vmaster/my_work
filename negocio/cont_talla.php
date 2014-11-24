<?php
require("../datos/cado.php");
require("cls_talla.php");
controlador($_GET['accion']);
//header('Location: ../presentacion/list_talla.php');

function controlador($accion)
{
  $ObjTalla = new clsTalla();
  if($accion=='NUEVO'){  
	$ObjTalla->insertar($_POST['txtIdTalla'], $_POST['txtNombre'], $_POST['txtAbreviatura'], 'N');  
	
	if($_GET['origen']=='LIST'){
		header('Location: ../presentacion/list_talla.php');
	}else{?><script>window.open('../presentacion/mant_producto.php?accion=<?php echo $_GET['accionprod'];?>&IdProducto=<?php echo $_GET['idprod'];?>','mainFrame');window.close()</script><?php } 
	}    
  if($accion=='ACTUALIZAR'){
	$ObjTalla->actualizar($_POST['txtIdTalla'], $_POST['txtNombre'], $_POST['txtAbreviatura'], 'N');
	header('Location: ../presentacion/list_talla.php');
	}
  if($accion=='ELIMINAR'){
  	require("../negocio/cls_producto.php");
	$objProducto = new clsProducto();
  	$rst=$objProducto->buscar(NULL, NULL, NULL, $_GET['idTalla']);
	$dato = $rst->rowCount();
	
	if($dato>0){
		echo "<script>alert('Esta Talla No Se Puede Eliminar Ya QUe Se Esta Siendo Usada Por Productos')</script>";
	}else{
		$ObjTalla->eliminar($_GET['idTalla']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_talla.php'>";
	}
	
  if($accion=='CONSULTAR')
	return $ObjTalla->consultar(); 
  if($accion=='BUSCAR')
	return $ObjTalla->buscar($_POST['txtIdTalla'], $_POST['txtNombre'], $_POST['txtAbreviatura'], $_POST['cboEstado']); 
}
?>