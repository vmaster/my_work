<?php
require("../datos/cado.php");
require("cls_categoria.php");
controlador($_GET['accion']);


function controlador($accion)
{
  $ObjCategoria = new clsCategoria();
  if($accion=='NUEVO'){
  
  if($_POST['cboIdCategoriaRef']!=null){
  		$quieroNivel=$ObjCategoria->buscar($_POST['cboIdCategoriaRef'],'','',null,null,null);
	 	$Niv = $quieroNivel->fetchObject();
	 	$nivel=$Niv->Nivel;
		
		$ObjCategoria->insertar($_POST['txtIdCategoria'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], $_POST['cboIdCategoriaRef'], ($nivel+1), 'N'); 
		
	}else{
		$ObjCategoria->insertar($_POST['txtIdCategoria'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], null, 1, 'N'); 
	}
	 
		     
		if($_GET['origen']=='LIST'){
			header('Location: ../presentacion/list_categoria.php');
		}else{?><script>window.open('../presentacion/mant_producto.php?accion=<?php echo $_GET['accionprod'];?>&IdProducto=<?php echo $_GET['idprod'];?>','mainFrame');window.close()</script><?php } 

  }
     
  if($accion=='ACTUALIZAR'){
  
  if($_POST['cboIdCategoriaRef']!=null){
  $quieroNivel=$ObjCategoria->buscar($_POST['cboIdCategoriaRef'],'','',null,null,null);
	 	$Niv = $quieroNivel->fetchObject();
	 	$nivel=$Niv->Nivel;
  $ObjCategoria->actualizar($_POST['txtIdCategoria'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], $_POST['cboIdCategoriaRef'], ($nivel+1), 'N');
  
  }else{
  $ObjCategoria->actualizar($_POST['txtIdCategoria'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], null, 1, 'N');
  }
  header('Location: ../presentacion/list_categoria.php');
  }
	
  if($accion=='ELIMINAR'){
  require("../negocio/cls_producto.php");
	$objProducto = new clsProducto();
  	$rst=$objProducto->buscar(NULL, NULL,$_GET['idCategoria'],NULL);
	$dato = $rst->rowCount();
	
	if($dato>0){
		echo "<script>alert('Esta Categoria No Se Puede Eliminar Ya QUe Se Esta Siendo Usada Por Productos')</script>";
	}else{
		$ObjCategoria->eliminar($_GET['idCategoria']);
	}
	echo "<META HTTP-EQUIV=Refresh CONTENT='0;URL= ../presentacion/list_categoria.php'>";
	}
  
  
  if($accion=='CONSULTAR'){
	return $ObjCategoria->consultar(); 
	}
	
  if($accion=='BUSCAR'){
	return $ObjCategoria->buscar($_POST['txtIdCategoria'], $_POST['txtDescripcion'], $_POST['txtAbreviatura'], $_POST['cboIdCategoriaRef'], $_POST['txtNivel'], $_POST['cboEstado']);
	}
	
}

?>