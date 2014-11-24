<?php
session_start();
@require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores

if(isset($_SESSION['FechaProceso'])){
$fechaproceso = $_SESSION['FechaProceso'];
}else {
$fechaproceso = date("Y-m-d");
$_SESSION['FechaProceso']=date("Y-m-d");
}


require("../datos/cado.php");
require("../negocio/cls_producto.php");
require("../negocio/cls_persona.php");
$ObjPersona = new clsPersona();
$EncabezadoTabla=array("C&oacute;digo","Descripci&oacute;n","Categoria","Marca","Color","Talla","Unidad","Precio Venta","Precio Compra","Stock","Armario","Columna","Fila");
$objProducto = new clsProducto();
function listado($categoria,$campo,$frase,$pag,$TotalReg,$moneda){
	Global $objProducto;
	Global $EncabezadoTabla;
	Global $idsucursal;
	Global $tipocambio;
	$regxpag=10;
	$nr1=$TotalReg;
	$inicio=$regxpag*($pag - 1);
	$limite="";
	$frase=utf8_decode($frase);	
	if($inicio==0){
	$rs = $objProducto->buscarconxajax($categoria,$campo,$frase,$limite,$idsucursal,$moneda,$tipocambio);
    $nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $objProducto->buscarconxajax($categoria,$campo,$frase,$limite,$idsucursal,$moneda,$tipocambio);
    $nr=$rs->rowCount()*($pag);	
	$CantCampos=$rs->columnCount();
    $cadena="&nbsp;Registros encontrados: $nr de $nr1";
    $registros="
	<table class=registros width='100%'>
    <tr>";
	for($i=0;$i<count($EncabezadoTabla);$i++){
	$registros.="<th>".$EncabezadoTabla[$i]."</th>";
	}
	$registros.="<th>Selecionar</th></tr>";
	$cont=0;
    while($reg=$rs->fetch()){
	   $cont++;
	   $rojo="";
	   if($reg[10]<=0){$rojo="red";}
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr class='$estilo' style='color:$rojo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				$RegistroEdicion="<td><a href='#agregar' onClick='seleccionar(".$reg[$i].",&quot;".$moneda."&quot;)'>Seleccionar</a></td>";
			}elseif($i==8 or $i==9 or $i==10){
				$registros.="<td align='right'>".$reg[$i]."</td>";
			}elseif($i==6 or $i==11 or $i==12){
				$registros.="<td align='center'>".$reg[$i]."</td>";
			}elseif($i==5){
				$color=split('-',$reg[$i]);
				if($color[1]==''){
					$registros.="<td align='center' title=".$color[0].">".$color[0]."</td>";
				}else{
					$registros.="<td align='center' title=".$color[0]." bgcolor=".$color[1].">&nbsp;</td>";
				}
			}else{
				$registros.= "<td>".$reg[$i]."</td>";
			}
	   }
	   $registros.=$RegistroEdicion;
	   $registros.= "</tr>";
    }
    $registros.="</table></form><center>Pag: ";
	for($i=1;$i<=$nunPag;$i++){
		$registros.='<a href="#" onClick="javascript:document.getElementById(\'pag\').value='.$i.';buscar()">'.$i.' </a>';
	}
	$registros.='</center>';
	$registros=utf8_encode($registros);
	$objResp=new xajaxResponse();
	$objResp->assign('divnumreg','innerHTML',$cadena);
	$objResp->assign('divregistros','innerHTML',$registros);
	$objResp->assign('TotalReg','value',$nr1);
	return $objResp;
}

function genera_cboUnidad($idproducto,$moneda){
	Global $idsucursal;
	Global $tipocambio;
	require("../negocio/cls_listaunidad.php");
	$ObjListaUnidad = new clsListaUnidad();
	$consulta = $ObjListaUnidad->buscarconxajax($idsucursal,$idproducto,$moneda,$tipocambio);

	$Unidads="<table><tr><td><select name='cboUnidad' id='cboUnidad' onchange='cambiaStock()'>";
	while($registro=$consulta->fetchObject()){
		if($registro->idunidad==$registro->idunidadbase){ 
			$seleccionar="Selected";
			$preciocompra=$registro->preciocompra;
			$precioventa=$registro->precioventa;
			$precioventaE=$registro->precioventaespecial;
			if($precioventaE==NULL)
				$precioventaE=$precioventa;
			$producto=$registro->producto;
			$StockActual=$registro->StockActual;
		}else{$seleccionar="";}
		$Unidads=$Unidads."<option value='".$registro->idunidad."' ".$seleccionar.">".$registro->unidad."</option>";
	}
	$Unidads=$Unidads."</select></td><td><a href='list_listaunidad.php?IdProducto=$idproducto' target='_blank' title='Ver formula unidades'>...</a></td></tr></table>";
	$Unidads=utf8_encode($Unidads);
	$obj=new xajaxResponse();
	$obj->assign("DivUnidad","innerHTML",$Unidads);
	$obj->assign("txtPrecioCompra","value",$preciocompra);
	$obj->assign("txtPrecioVenta","value",$precioventa);
	$obj->assign("txtPrecioVentaE","value",$precioventaE);
	$obj->assign("lblProducto","innerHTML",utf8_encode($producto));
	$obj->assign("txtIdProductoSeleccionado","value",$idproducto);
	$obj->assign("txtStockActual","value",$StockActual);
	return $obj;
}

function cambiaStock($idproducto,$idunidad){
	Global $idsucursal;
	require("../negocio/cls_stockproducto.php");
	$ObjStockProducto = new clsStockProducto();
	$consulta = $ObjStockProducto->obtenerStock($idsucursal,$idproducto,$idunidad);
	$registro=$consulta->fetchObject();
	$StockActual=$registro->StockActual;
	
	$obj=new xajaxResponse();
	$obj->assign("txtStockActual","value",$StockActual);
	return $obj;
}

function agregar($idproducto,$idunidad,$cantidad,$preciocompra,$precioventa,$precioventaE,$txtDetalle,$StockActual,$moneda,$impuesto,$documento){
	Global $carro;
	Global $objProducto;
	
	if($preciocompra>=0 and isset($preciocompra) and isset($cantidad) and $preciocompra<>'' and $cantidad<>'' and $precioventa>=0 and $precioventa<>'' and $precioventaE>=0 and $precioventaE<>''){
		//$cantidad>=0 and $cantidad<=$StockActual and
		 
	if($txtDetalle=='FALSE')
		$_SESSION['carroCompra']='';
	if(isset($_SESSION['carroCompra']))
		$carro=$_SESSION['carroCompra'];
		
	$rs = $objProducto->buscarxidproductoyidunidad($idproducto,$idunidad);	
    $reg=$rs->fetchObject();	
		
	$carro[($idproducto)]=array('idproducto'=>($idproducto),'codigo'=>$reg->codigo,'producto'=>$reg->producto,'cantidad'=>$cantidad,'idunidad'=>$idunidad, 'unidad'=>$reg->unidad, 'preciocompra'=>$preciocompra, 'preciocompraoriginal'=>$preciocompra, 'precioventa'=>$precioventa,'precioventaE'=>$precioventaE, 'moneda'=>$moneda,'precioventaoriginal'=>$precioventa,'precioventaEoriginal'=>$precioventaE);

	$_SESSION['carroCompra']=$carro;
	
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Precio Venta Normal</th>
	<th>Precio Venta Especial</th>
	<th>Cantidad</th>
	<th>Precio Compra</th>	
	<th>SubTotal</th>
	";
	foreach($carro as $k => $v){
		$subto=$v['cantidad']*$v['preciocompra'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["precioventa"]."</td>";
		$registros.="<td align='right'>".$v["precioventaE"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".$v["preciocompra"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["preciocompra"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/($_SESSION['IGV']+100))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	if($documento=='3' || $documento=='8'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
	$registros.="</table>
	<div><center><b>$t2 ";
	
	if($impuesto=='1'){
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($suma,2)."' readonly=''>";}else{
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($sub2,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rSub' id='rSub' value='".number_format($suma,2)."'>
	<input type='hidden' name='rSub2' id='rSub2' value='".number_format($sub2,2)."'>
	$t1";
	
	if($impuesto=='1'){
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv,2)."' readonly='' >";}else{
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($suma-$sub2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($suma-$sub2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma+$igv,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($suma+$igv,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("txtDetalle","value",'TRUE');
	}else{
		if($preciocompra<0 or $preciocompra==''){
			$mensaje.="El precio de compra no puede ser menor a cero!!! \r";
		}
		if($precioventa<0 or $precioventa==''){
			$mensaje.="El precio de venta no puede ser menor a cero!!! \r";
		}
		
		if($precioventaE<0 or $precioventaE==''){
			$mensaje.="El precio de venta especial no puede ser menor a cero!!! \r";
		}
		
		/*else{
			$mensaje="La cantidad no puede ser menor o igual a cero ni mayor al stock actual!!!";}*/
	$obj=new xajaxResponse();
	$obj->alert($mensaje);
	}
	
	return $obj;
}

function quitar($idproducto,$impuesto,$documento){
	Global $carro;
	if(isset($_SESSION['carroCompra']))
		$carro=$_SESSION['carroCompra'];
		
	unset($carro[($idproducto)]);

	$_SESSION['carroCompra']=$carro;
	
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Precio Venta Normal</th>
	<th>Precio Venta Especial</th>
	<th>Cantidad</th>
	<th>Precio Compra</th>	
	<th>SubTotal</th>
	";
	foreach($carro as $k => $v){
		$subto=$v['cantidad']*$v['preciocompra'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["precioventa"]."</td>";
		$registros.="<td align='right'>".$v["precioventaE"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".$v["preciocompra"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["preciocompra"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/($_SESSION['IGV']+100))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	if($documento=='3' || $documento=='8'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
	$registros.="</table>
	<div><center><b>$t2 ";
	
	if($impuesto=='1'){
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($suma,2)."' readonly=''>";}else{
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($sub2,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rSub' id='rSub' value='".number_format($suma,2)."'>
	<input type='hidden' name='rSub2' id='rSub2' value='".number_format($sub2,2)."'>
	$t1";
	
	if($impuesto=='1'){
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv,2)."' readonly='' >";}else{
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($suma-$sub2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($suma-$sub2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma+$igv,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($suma+$igv,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}

function cambiarmonedadetalle($moneda,$impuesto,$documento){
	Global $carro;
	Global $tipocambio;
	if(isset($_SESSION['carroCompra']))
		$carro=$_SESSION['carroCompra'];
		
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Precio Venta Normal</th>
	<th>Precio Venta Especial</th>
	<th>Cantidad</th>
	<th>Precio Compra</th>	
	<th>SubTotal</th>
	";
	foreach($carro as $k => $v){
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		
		if($v["moneda"]=='S'){
			if($moneda=='S'){
				$v["preciocompra"]=$v["preciocompraoriginal"];
				$v["precioventa"]=$v["precioventaoriginal"];
				$v["precioventaE"]=$v["precioventaEoriginal"];
			}else{
				$v["preciocompra"]=$v["preciocompraoriginal"]/$tipocambio;
				$v["precioventa"]=$v["precioventaoriginal"]/$tipocambio;
				$v["precioventaE"]=$v["precioventaEoriginal"]/$tipocambio;
				
				}
		}else{
			if($moneda=='D'){
				$v["preciocompra"]=$v["preciocompraoriginal"];
				$v["precioventa"]=$v["precioventaoriginal"];
				$v["precioventaE"]=$v["precioventaEoriginal"];
			}else{
				$v["preciocompra"]=$v["preciocompraoriginal"]*$tipocambio;
				$v["precioventa"]=$v["precioventaoriginal"]*$tipocambio;
				$v["precioventaE"]=$v["precioventaEoriginal"]*$tipocambio;
				
				}
		}
		$registros.="<td align='right'>".number_format($v["precioventa"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["precioventaE"],2)."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".number_format($v["preciocompra"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["preciocompra"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
		$subto=$v['cantidad']*$v['preciocompra'];
		$suma=$suma+$subto;
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/($_SESSION['IGV']+100))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	if($documento=='3' || $documento=='8'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
	$registros.="</table>
	<div><center><b>$t2 ";
	
	if($impuesto=='1'){
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($suma,2)."' readonly=''>";}else{
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($sub2,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rSub' id='rSub' value='".number_format($suma,2)."'>
	<input type='hidden' name='rSub2' id='rSub2' value='".number_format($sub2,2)."'>
	$t1";
	
	if($impuesto=='1'){
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv,2)."' readonly='' >";}else{
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($suma-$sub2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($suma-$sub2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma+$igv,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($suma+$igv,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}



function listadopersona($campo,$frase,$TotalReg,$origen){
	Global $ObjPersona;
	$EncabezadoTabla=array("Apellidos y Nombres","RUC/DNI");
	$regxpag=10;
	$nr1=$TotalReg;
	$pag=1;
	$inicio=$regxpag*($pag - 1);
	$limite="";
	$frase=utf8_decode($frase);	
	if($inicio==0){		
		$rs = $ObjPersona->consultarpersonarol($origen,$campo,$frase,$limite);	
    	$nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $ObjPersona->consultarpersonarol($origen,$campo,$frase,$limite);	
    $nr=$rs->rowCount()*($pag);	
	$CantCampos=$rs->columnCount();
    $cadena="&nbsp;Registros encontrados: $nr de $nr1";
    $registros="
	<table class=registros>
    <tr>";
	for($i=0;$i<count($EncabezadoTabla);$i++){
	$registros.="<th>".$EncabezadoTabla[$i]."</th>";
	}
	$registros.="<th>Seleccionar</th></tr>";
	$cont=0;
    while($reg=$rs->fetch()){
	   $cont++;
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr class='$estilo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				//$registros.= "<td>".$reg[$i]."</td>";
				//DEPENDIENDO DEL ORIGEN SE LLAMA A LA FUNCION CORRESPONDIENTE
				if($origen=='2'){//PROVEEDORES
				$RegistroSeleccion="<td><a href='#' onClick='mostrarPersona(".$reg[$i].");activarNum();'>Seleccionar</a></td>";		
				}
				if($origen=='3'){//EMPLEADO
				$RegistroSeleccion="<td><a href='#' onClick='mostrarEmpleado(".$reg[$i].");'>Seleccionar</a></td>";		
				}
			}
			else{
				$registros.= "<td>".$reg[$i]."</td>";
			}
	   }
	   $registros.=$RegistroSeleccion;
	   $registros.= "</tr>";
    }
	$registros=utf8_encode($registros);
	$objResp=new xajaxResponse();
	$objResp->assign('divnumregPersona','innerHTML',$cadena);
	$objResp->assign('divregistrosPersona','innerHTML',$registros);
	$objResp->assign('TotalRegPersona','value',$nr1);
	return $objResp;
}

function mostrarPersona($id){
  Global $ObjPersona;
  $rs = $ObjPersona->buscarXId($id);	
  $reg= $rs->fetchObject();
  $objResp=new xajaxResponse();
  $objResp->assign('txtIdPersona','value',$reg->IdPersona);
  $objResp->assign('txtNombresPersona','value',utf8_encode($reg->Nombres));
  return $objResp;
}

function mostrarEmpleado($id){
  Global $ObjPersona;
  $rs = $ObjPersona->buscarXId($id);	
  $reg= $rs->fetchObject();
  $objResp=new xajaxResponse();
  $objResp->assign('txtIdResponsable','value',$reg->IdPersona);
  $objResp->assign('txtNombresResponsable','value',utf8_encode($reg->Nombres));
  return $objResp;
}




function comprobarNumeroDocumento($numero,$idproveedor,$idtipodocumento,$fecha){
     $idtipomovimiento=1; //compras
	Global $idsucursal;
	if(substr(trim($numero),4,6)=="000000"){
	$cantidad='99';
	}else{
	
	require("../negocio/cls_movimiento.php");
	$ObjMovimiento = new clsMovimiento();
	$exist = $ObjMovimiento->consultarExistenciaNumeroCompra($idsucursal,$idproveedor,$fecha,$numero,$idtipomovimiento,$idtipodocumento);
	$existe=$exist->fetchObject();
	$cantidad=$existe->cant;
	}
	if($cantidad=="" || $cantidad==NULL){
	$cantidad=666;
	}
	
	
	$obj=new xajaxResponse();
	$obj->assign("txtExistenciaNumero","value",$cantidad);
	return $obj;
}

function presentarinicio(){
	
	$registros.="<table class=registros width='100%'>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Precio Venta Normal</th>
	<th>Precio Venta Especial</th>
	<th>Cantidad</th>
	<th>Precio Compra</th>	
	<th>SubTotal</th>
	</table>
	<div><center><b><input type='hidden' name='txtSubtotal' id='txtSubtotal' value='0.00'>
	<input type='hidden' name='rSub' id='rSub' value='0.00'>
	<input type='hidden' name='rSub2' id='rSub2' value='0.00'>
	<input type='hidden' name='txtIgv' id='txtIgv' value='0.00' readonly='' >
	<input type='hidden' name='rIgv' id='rIgv' value='0.00'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='0.00'>
	Total :<input type='text' name='txtTotal' id='txtTotal' value='0.00' readonly=''>
	<input type='hidden' name='rTotal' id='rTotal' value='0.00'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='0.00'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}


function generarcuotas($numcuota,$fecha,$total,$inicial,$interes){
	if($numcuota<1){
	if($numcuota==""){$texto="";}else{$texto="1";}
	$numcuota=1;
	
	}else{
	$texto=$numcuota;
	}
	//$total= str_replace(",","",$total);
	$cuota=($total-$inicial)/$numcuota;
	$cuota=number_format($cuota,2,".","");
	$cuotafinal=$total-$inicial -($cuota*($numcuota-1));
	//$cuota=number_format($cuota,2);
	$cuotafinal=number_format($cuotafinal,2,".","");

	$day=substr(trim($fecha),8,2);
	$mes=substr(trim($fecha),5,2);
	$year=substr(trim($fecha),0,4);
	
	for($i=1;$i<=$numcuota;$i++){

	$mes=$mes+1;
	if($mes==13){$mes=1;$year=$year+1;}
	if($day>=29){
		if($mes==2){if(($year%4) > 0){$day=28;}
		}elseif(($mes%2)==1 && $mes!=7){if($day>30){$day=30;}
		}	
	}
	$cero='00';
	$day=substr($cero,0,2-strlen($day)).$day;
	$mes=substr($cero,0,2-strlen($mes)).$mes;
	$fecha=$year.'-'.$mes.'-'.$day;

	$registros.="<table><tr><td width='122'>Cuota N&deg;:</td>";
	$registros.="<td width='219'><input type='text' name = 'txtNumCuota".$i."' value = '".$i."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' readonly></td>
</tr>";
	$registros.="<tr>
  <td>Subtotal Cuota :</td>
  <td><input type='text' name = 'txtSubtotal".$i."'  id= 'txtSubtotal".$i."'";
	  if($i!=$numcuota){$registros.="value = '$cuota'";}
	else{$registros.="value = '$cuotafinal'";}

	$registros.="onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' onKeyUp='totalizar(".$i.','.$numcuota.")'></td></tr><tr>";
	$registros.="<td>Interes:</td>
  <td><input type='text' name = 'txtInteres".$i."' id = 'txtInteres".$i."' value = '".$interes."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' onKeyUp='completar(".$i.','.$numcuota.")'></td>
</tr><tr>";
	$registros.="<td>Fecha Cancelacion :</td>
  <td><input type='text' name='txtFechaRegistro".$i."' id='txtFechaRegistro".$i."' readonly='' value='".$fecha."'><button type='button' id='btnCalendar".$i."' class='boton' onClick='elegirfecha(".$i.");'><img src='../imagenes/b_views.png'> </button></td>";

	$registros.="</tr><tr>  <td>&nbsp;</td>
	<td></td></tr><tr>  <td>&nbsp;</td>  <td>&nbsp;</td></tr></table>";
	}

	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivCuotas","innerHTML",$registros);
	if($numcuota==1){
	$obj->assign("Contador","value",$texto);
	
	//$obj->assign("txtAcuenta","value",number_format($total-$inicial,2));
	}
	return $obj;
}



function vercuotaspendientes($numero,$idventa){
	require('../negocio/cls_Movimiento.php');
	
	$objMov=new clsMovimiento(); 
	$rst=$objMov->consultarcuotaventa($idventa);
	$registros.="Asignar monto a cuota: <select name='cboAsignado".$numero."' id='cboAsignado".$numero."'>";
	while($dato = $rst->fetchObject()){
	if($dato->estado=="N" && $numero!=$dato->nombre){
    $registros.="<option value='".$dato->nombre."'>".$dato->nombre."</option>";
	}
     }
	 $registros.="</select> <button type='button' id='btnok".$numero."' class='boton' onClick='darmonto(".$numero.");'>OK</button>";
	 $registros=utf8_encode($registros);
	 $obj=new xajaxResponse();
	 $obj->assign("DivEliminar".$numero,"innerHTML",$registros);
	 
	 return $obj;
}

function vercuotas($idcompra){
	require('../negocio/cls_Movimiento.php');
	
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->consultarcuotaventa($idcompra); 
	$numcuota=$rst->rowCount();
	$suma=0;
	$i=1;
	while($dato = $rst->fetchObject()){
$registros.="<table>";
if($dato->estado=="A"){

$registros.="<tr>

<input type='hidden' name='txtIdCuota".$i."' id='txtIdCuota".$i."' value='".$dato->idcuota."'>
<input type='hidden' name='txtNumCuota".$i."' id='txtNumCuota".$i."' value='".$i."'>
<input type='hidden' name='txtMoneda".$i."' id='txtMoneda".$i."' value='".$dato->moneda."'>
<input type='hidden' name='txtSubtotal".$i."' id='txtSubtotal".$i."' value='".$dato->monto."'>
<input type='hidden' name='txtSubtotalP".$i."' id='txtSubtotalP".$i."' value='".number_format($dato->montopagado,2)."'>
<input type='hidden' name='txtInteres".$i."' id='txtInteres".$i."' value='".$dato->interes."'>
<input type='hidden' name='txtInteresP".$i."' id='txtInteresP".$i."' value='".number_format($dato->interespagado,2)."'>
<input type='hidden' name='txtFechaRegistro".$i."' id='txtFechaRegistro".$i."' value='".substr($dato->fechacancelacion,0,10)."'>
<input type='hidden' name='txtFechaPago".$i."' id='txtFechaPago".$i."' value='".substr($dato->fechapago,0,10)."'>
<input type='hidden' name='cboEstado".$i."' id='cboEstado".$i."' value='".$dato->estado."'>

</tr>";


}else{
if($dato->estado=="N"){
$registros.="<tr><td><input type='checkbox' name='check".$i."' value='check".$i."' onClick='activar(".$i.")'>Modificar<input type='hidden' name='txtModificar".$i."' id='txtModificar".$i."' value='0'></td><td><input type='checkbox' name='checkeli".$i."' name='checkeli".$i."' value='checkeli".$i."' onClick='eliminar(".$i.",".$idcompra.")'>Eliminar<input type='hidden' name='txtEliminar".$i."' id='txtEliminar".$i."' value='0'>
<div id='divEliminar".$i."'></div></td></tr>";
}
$registros.="<tr><td width='122'>Cuota N&deg;:</td>";
$registros.="<td width='219'> ".$i."<input type='hidden' name = 'txtNumCuota".$i."' value = '".$i."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' readonly><input type='hidden' name='txtIdCuota".$i."' id='txtIdCuota".$i."' value='".$dato->idcuota."'></td>
</tr>";
$registros.="<tr><td>Moneda:</td>
  <td> ".$dato->moneda."<input type='hidden' name = 'txtMoneda".$i."' id = 'txtMoneda".$i."' readonly value = '".$dato->moneda."' ></td>
</tr>";
$registros.="<tr>
  <td>Monto :</td>
  <td><input type='text' name = 'txtSubtotal".$i."'  id= 'txtSubtotal".$i."' readonly ";
  $registros.="value = '".$dato->monto."'";


$registros.="onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' ></td></tr>";

 $registros.="<tr> <td>Monto Pagado:</td>
  <td> ".number_format($dato->montopagado,2)."<input type='hidden' name='txtSubtotalP".$i."' id='txtSubtotalP".$i."' value='".number_format($dato->montopagado,2)."'></td></tr>";


$registros.="<tr><td>Interes:</td>
  <td><input type='text' name = 'txtInteres".$i."' id = 'txtInteres".$i."' readonly value = '".$dato->interes."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' ></td>
</tr>";

 $registros.="<tr> <td>Interes Pagado:</td>
  <td> ".number_format($dato->interespagado,2)."<input type='hidden' name='txtInteresP".$i."' id='txtInteresP".$i."' value='".number_format($dato->interespagado,2)."'></td></tr>";

$registros.="<tr><td>Fecha Cancelación:</td><td>";

$registros.="<input name='txtFechaRegistro".$i."' type='text' id='txtFechaRegistro".$i."' value='".substr($dato->fechacancelacion,0,10)."' size='10' readonly='true'>
        <button type='button' id='btnCalendar".$i."' class='boton' onClick='elegirfecha(".$i.");' disabled ><img src='../imagenes/b_views.png'></button>
      </td></tr>";

//$registros.="<tr><td>Fecha Pago :</td><td>";

if($dato->estado=="C"){
$registros.="<tr><td>Fecha Pago :</td><td>";
 $registros.="".substr($dato->fechapago,0,10)."<input name='txtFechaPago".$i."' type='hidden' id='txtFechaPago".$i."' value='".substr($dato->fechapago,0,10)."' size='10' readonly='true'></td></tr>";

}else{
$registros.="<input name='txtFechaPago".$i."' type='hidden' id='txtFechaPago".$i."' value='".substr($dato->fechapago,0,10)."' size='10' readonly='true'>
        
      </td></tr>";
}
	  
$darEstado="";

if($dato->estado=="N"){$darEstado=" PENDIENTE DE PAGO";}
if($dato->estado=="C"){$darEstado=" PAGADA";}

$registros.="<tr>
  <td>Estado:</td>
  <td> ".$darEstado."<input type='hidden' name='cboEstado".$i."' id='cboEstado".$i."' value='".$dato->estado."'></td></tr>";
 
$registros.="<tr>  <td><div id='DivPasar".$i."' style='display:none;'> <input type=checkbox name='pasar".$i."' value='pasar".$i."'>Pasar Monto:</div></td>";
$registros.="<td><div id='DivPasa".$i."' style='display:none;'><select></select></div>";
$registros.="</td></tr><tr>  <td>&nbsp;</td>  <td>&nbsp;</td></tr></table>";
}
if($dato->estado!='A')
	$suma=$suma+$dato->monto;

$i++;
}
$rst2=$objMov->consultarventa(1,$idcompra,NULL,NULL,NULL,NULL,NULL,NULL);
$dato2=$rst2->fetchObject();

	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivCuotas","innerHTML",$registros);
	$obj->assign("DivCliente","innerHTML",$dato2->cliente.$dato2->acliente);
	$obj->assign("DivFecha","innerHTML",$dato2->fecha);
	$obj->assign("Contador","value",$numcuota);
	$obj->assign("txtTotal","value",$dato2->total);
	$obj->assign("txtAcuenta","value",$suma);
	$obj->assign("txtInicial","value",number_format($dato2->total-$suma,2));
	return $obj;
}



function referenciarcompras($numero,$idtipodoc,$fechainicio,$formapago){
	Global $idsucursal;
	
	$registro.="<table width='500' border='0'>
	<tr>
	<th >DOC.</th>
	<th >FECHA</th>
	<th >PROVEEDOR</th>
	<th >TOTAL</th>
	<th >EST.</th>
	<th >OPER.</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$rst = $objMovimiento->consultarcompra(1,$idsucursal,$idtipodoc,$fechainicio,$fechaproceso,'A',$numero,$formapago);
	while($dato = $rst->fetchObject()){
		if($dato->estado=='A'){
		$estado='Anulado';
		$registro.="<tr style='color:#FF0000'><td>".substr($dato->nomDoc,0,1).'/N.'.substr($dato->numero,0,10)."</td>";
		}else{
		$registro.="<tr><td>".substr($dato->nomDoc,0,1).'/N.'.substr($dato->numero,0,10)."$m</td>";
		$estado='Normal';
		}
		
		$registro.="<td align='center'>".$dato->fecha."</td>";
		
	$registro.="<td align='center'>".substr($dato->nomProveedor,0,1).'. '.substr($dato->apeProveedor,0,strrpos($dato->apeProveedor,' '))."</td>";
	$registro.="<td align='center'>".$dato->Total.'/'.$dato->moneda."</td>";
	$registro.="<td align='center'>".substr($estado,0,1)."</td>";
	$registro.="<td width><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' onClick='verdetalle(".$dato->idmov.")'>Ver</a></td>";

	$registro.="<td><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' 		onClick='referenciar(".$dato->idmov.")'>Ref.</a></td>";
	
	

	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaCompras","innerHTML",$registro);
	return $obj;
}

function referenciar($id){
	Global $idsucursal;
	Global $carro;

	if(isset($_SESSION['carroCompra']))
		$carro=$_SESSION['carroCompra'];
		
	if(isset($_SESSION['IdMovimientoRef']))
		$idmovimientoref=$_SESSION['IdMovimientoRef'];
				
	require('../negocio/cls_movimiento.php');
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->consultarDetalleCompra($id); 
	while($dato=$rst->fetchObject()){
	
	$carro[($dato->IdProducto)]=array('idproducto'=>($dato->IdProducto),'codigo'=>$dato->Codigo,'producto'=>$dato->producto,'cantidad'=>$dato->Cantidad,'idunidad'=>$dato->IdUnidad, 'unidad'=>$dato->unidad, 'preciocompra'=>$dato->PrecioCompra, 'preciocompraoriginal'=>$dato->PrecioCompra,'precioventa'=>$dato->PrecioVenta,'precioventaE'=>$dato->PrecioVentaEspecial, 'moneda'=>$dato->Moneda ,'precioventaoriginal'=>$dato->PrecioVenta,'precioventaEoriginal'=>$dato->PrecioVentaEspecial);
	$_SESSION['carroCompra']=$carro;
	}
	
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Precio Venta Normal</th>
	<th>Precio Venta Especial</th>
	<th>Cantidad</th>
	<th>Precio Compra</th>	
	<th>SubTotal</th>
	";
	foreach($carro as $k => $v){
		$subto=$v['cantidad']*$v['preciocompra'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["precioventa"]."</td>";
		$registros.="<td align='right'>".$v["precioventaE"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".$v["preciocompra"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["preciocompra"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	//$registros.="</table><div><center>Total: <input type='text' name='txtTotal' id='txtTotal' readonly='true' value='".number_format($suma,2)."' /></center></div>";
		
	
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/($_SESSION['IGV']+100))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	
	
	$rst3=$objMov->verCompra($id);
	$detalle = $rst3->fetchObject();
	
	if($detalle->tipoDoc==4){
	$mostrarDivIgv="<input type='checkbox' name='Cigv1' value='1' onClick='asignarCheck();' checked='checked'> Incluir IGV";
	}
	
	$obj=new xajaxResponse();

	$obj->assign("txtSubtotal","value",$detalle->SubTotal);

	$obj->assign("rSub","value",number_format($suma,2));
	$obj->assign("rSub2","value",number_format($sub2,2));
	
	$obj->assign("txtIgv","value",$detalle->Igv);
		
	$obj->assign("rIgv","value",number_format($igv,2));
	$obj->assign("rIgv2","value",number_format($suma-$sub2,2));
	
	$obj->assign("txtTotal","value",$detalle->Total);
	
	$obj->assign("rTotal","value",number_format($suma+$igv,2));
	$obj->assign("rTotal2","value",number_format($suma,2));
	
	
	//return true;


	$obj->assign("txtSucursalRef","value",$detalle->idsucursal);
	$obj->assign("txtIdMovimientoRef","value",$id);
	$obj->assign("txtIdPersona","value",$detalle->IdPersona);
	$obj->assign("txtNombresPersona","value",$detalle->apeProveedor." ".$detalle->nomProveedor);
	$obj->assign("txtIdResponsable","value",$detalle->IdResponsable);
	$obj->assign("txtNombresResponsable","value",$detalle->apeResponsable." ".$detalle->nomResponsable);
	$obj->assign("cboTipoDocumento","value",$detalle->tipoDoc);
	$obj->assign("cboMoneda","value",$detalle->moneda);
	$obj->assign("txtDocRef","value",substr($detalle->nomDoc,0,7)." ".$detalle->numero);
	if($detalle->tipoDoc=='4'){
	$obj->assign("versiigv","value",'1');
	}
	
	if($detalle->tipoDoc=='3' || $detalle->tipoDoc=='8'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
	$registros.="</table>
	<div><center><b>$t2 ";
	
	
	$registros.="<input type='$type' name='txtSubtotal' id='txtSubtotal' value='".number_format($detalle->SubTotal,2)."' readonly=''>";
	
	$registros.="
	<input type='hidden' name='rSub' id='rSub' value='".number_format($suma,2)."'>
	<input type='hidden' name='rSub2' id='rSub2' value='".number_format($sub2,2)."'>
	$t1";
	
	
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($detalle->Igv,2)."' readonly='' >";
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($suma-$sub2,2)."'>
	Total :"; 
	
	
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($detalle->Total,2)."' readonly=''>";
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($suma+$igv,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	
	
	
	
	$obj->assign("DivDetalle","innerHTML",$registros);
	
	if($detalle->tipoDoc==4){
	$obj->assign("igvParaRef","innerHTML",$mostrarDivIgv);
	$obj->assign("tdigv","value",'1');
	}
	
	$obj->assign("txtDetalle","value",'TRUE');
	return $obj;

}

function aperturacaja($Fecha){

Global $idsucursal;
$IdConceptoPago=1;

require('../negocio/cls_movimiento.php');
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->comprobarAperturaCaja($IdConceptoPago,$idsucursal,$Fecha); 
	$dato=$rst->fetchObject();
	
$obj=new xajaxResponse();

$obj->assign("txtAperturaCaja","value",$dato->resultado);

return $obj;

}

function saldocaja($fecha,$monedax,$cajatotal){
Global $idsucursal;
require('../negocio/cls_movcaja.php');
$objCaja=new clsMovCaja();
$monto=0;
if($monedax=='S'){
$monto=$objCaja->montodeaperturasoles();
}else{
$monto=$objCaja->montodeaperturadolares();
}


$obj=new xajaxResponse();

//if($monto>=$cajatotal){
//$loquesea='1';
//}else{
//$loquesea='0';
//}
$obj->assign("txtSaldoCaja","value",$monto);
return $obj;

}

function completarCajaUsuario($idUsuario){
require('../negocio/cls_usuario.php');
$objUser = new clsUsuario();
$us=$objUser->buscar($idUsuario,NULL);
$usuario=$us->fetchObject();
$nombrecompleto=$usuario->apellidos." ".$usuario->nombres;

$nombrecompleto=utf8_encode($nombrecompleto);
$obj=new xajaxResponse();
$obj->assign("txtNombresResponsable","value",$nombrecompleto);
return $obj;
}


function abrirlacaja(){
require('../negocio/cls_movcaja.php');
require('../negocio/cls_movimiento.php');
require("../negocio/cls_bitacora.php");
$ObjMov = new clsMovCaja();
$ObjMovimiento=new clsMovimiento(); 
$ObjBitacora = new clsBitacora();

Global $idsucursal;
if(!isset($_SESSION['IdSucursal'])){
$_SESSION['IdSucursal']= $idsucursal;
}

$txtNroDoc=$ObjMovimiento->consultar_numero_sigue(3,10,$_SESSION['IdSucursal']);
$txtNroDoc=$txtNroDoc."-".date('Y');
$montoAperturaSoles = $ObjMov->montodeaperturasoles();
$montoAperturaDolares = $ObjMov->montodeaperturadolares();


$num_mov=$ObjMov->existenciamov();

if($num_mov==0){

	

	$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$txtNroDoc,10,'A',$_SESSION['FechaProceso'],'S',$montoAperturaSoles/($_SESSION['IGV']/100+1),($montoAperturaSoles/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaSoles,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Caja Aperturada en Soles el ".date('Y-m-d'),'N');
	
		$num=$txtNroDoc;
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente,10,'A',$_SESSION['FechaProceso'],'D',$montoAperturaDolares/($_SESSION['IGV']/100+1),($montoAperturaDolares/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaDolares,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Caja Aperturada en Dolares el ".date('Y-m-d'),'N');
		
		$ultimoId=$ObjMovimiento->obtenerLastId($_POST['txtNroDoc'],3,$_POST['cboDoc']);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],$_SESSION['FechaProceso'],"Apertura de Caja del ".$_SESSION['FechaProceso'],'N');
		


}else{

	$fechacierre1=$ObjMov->consultarmaxfecha();
	$cierre=$ObjMov->consultarcierre($fechacierre1);
	
	if($cierre==0){
		
		$fechacierre=$ObjMov->consultarmaxfecha();
		$yearfecha=substr(trim($fechacierre),0,4);
	 
		$numerodoc=$ObjMovimiento->consultar_numero_sigue(3,11,$_SESSION['IdSucursal']); 
		$numerodoc=$numerodoc."-".date('Y');
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,2,$numerodoc,11,'A',$fechacierre,'S',$montoAperturaSoles/($_SESSION['IGV']/100+1),($montoAperturaSoles/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaSoles,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Cierre de Caja en Soles del".$fechacierre,'N');	
		$num= $numerodoc;
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,2,$numsiguiente,11,'A',$fechacierre,'D',$montoAperturaDolares/($_SESSION['IGV']/100+1),($montoAperturaDolares/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaDolares,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Cierre de Caja en Dolares del".$fechacierre,'N');
		
	$ultimoId=$ObjMovimiento->obtenerLastId($numerodoc,3,11);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],$_SESSION['FechaProceso'],"Cierre de Caja del ".$fechacierre,'N');
		
		//ahora aperturamos
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$txtNroDoc,10,'A',$_SESSION['FechaProceso'],'S',$montoAperturaSoles/($_SESSION['IGV']/100+1),($montoAperturaSoles/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaSoles,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Apertura Caja en Soles del ".$_SESSION['FechaProceso'],'N');
		
		
		$num2= $txtNroDoc;
		$year=substr($num2,11,4);
		$year2=date('Y');
		if($year!=$year2){$num2='001-000000';}
		$serie=substr($num2,0,3)+0;
		if(substr($num2,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num2=substr($num2,4,6)+1;
		$num2= substr($cero,0,6-strlen($num2)).$num2;
		$num2=$serie.'-'.$num2.'-'.date('Y');
		$numsiguiente2=$num2;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente2,10,'A',$_SESSION['FechaProceso'],'D',$montoAperturaDolares/($_SESSION['IGV']/100+1),($montoAperturaDolares/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaDolares,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Apertura Caja en Dolares del ".$_SESSION['FechaProceso'],'N');
		
	$ultimoId=$ObjMovimiento->obtenerLastId($txtNroDoc,3,10);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],$_SESSION['FechaProceso'],"Apertura de Caja del ".$_SESSION['FechaProceso'],'N');
		

		
		}else{
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$txtNroDoc,10,'A',$_SESSION['FechaProceso'],'S',$montoAperturaSoles/($_SESSION['IGV']/100+1),($montoAperturaSoles/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaSoles,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Apertura Caja en Soles del ".$_SESSION['FechaProceso'],'N');
		
		
		$num= $txtNroDoc;
		$year=substr($num,11,4);
		$year2=date('Y');
		if($year!=$year2){$num='001-000000';}
		$serie=substr($num,0,3)+0;
		if(substr($num,4,6)==999999){$serie=$serie+1;$num=0;}
		$cero2='000';
		$serie=substr($cero2,0,3-strlen($serie)).$serie;
		$cero='000000';
		$num=substr($num,4,6)+1;
		$num= substr($cero,0,6-strlen($num)).$num;
		$num=$serie.'-'.$num.'-'.date('Y');
		$numsiguiente=$num;
		
		
		$ObjMov->insertar($_SESSION['IdSucursal'],3,1,$numsiguiente,10,'A',$_SESSION['FechaProceso'],'D',$montoAperturaDolares/($_SESSION['IGV']/100+1),($montoAperturaDolares/($_SESSION['IGV']/100+1))*($_SESSION['IGV']/100),$montoAperturaDolares,$_SESSION['IdUsuario'],$_SESSION['IdSucursal'],$_SESSION['IdUsuario'],'','',"Apertura Caja en Dolares del ".$_SESSION['FechaProceso'],'N');
		
	$ultimoId=$ObjMovimiento->obtenerLastId($txtNroDoc,3,10);
	$idU=$ultimoId->fetchObject();
	$idultimomov=$idU->IdMovimiento;

		$ObjBitacora->insertar(NULL,$idultimomov,$_SESSION['IdUsuario'],$_SESSION['FechaProceso'],"Apertura Caja del ".$_SESSION['FechaProceso'],'N');
		
		
		
		
		
		
		
		}




}




return;
}




$xajax->registerFunction('mostrarPersona');
$xajax->registerFunction('mostrarEmpleado');
$xajax->registerFunction('presentarinicio');
$xajax->registerFunction('abrirlacaja');


$fcompletarcajausuario = & $xajax-> registerFunction('completarCajaUsuario');
$fcompletarcajausuario->setParameter(0,XAJAX_INPUT_VALUE,'txtIdResponsable');


$flistadopersona = & $xajax-> registerFunction('listadopersona');
$flistadopersona->setParameter(0,XAJAX_INPUT_VALUE,'campoPersona');
$flistadopersona->setParameter(1,XAJAX_INPUT_VALUE,'frasePersona');
$flistadopersona->setParameter(3,XAJAX_INPUT_VALUE,'TotalRegPersona');
$flistadopersona->setParameter(4,XAJAX_INPUT_VALUE,'txtOrigenPersona');

$fcambiarmonedadetalle = & $xajax-> registerFunction('cambiarmonedadetalle');
$fcambiarmonedadetalle->setParameter(0,XAJAX_INPUT_VALUE,'cboMoneda');
$fcambiarmonedadetalle->setParameter(1,XAJAX_INPUT_VALUE,'txtImpuesto');
$fcambiarmonedadetalle->setParameter(2,XAJAX_INPUT_VALUE,'cboTipoDocumento');

$xajax->registerFunction('vercuotaspendientes');

$fcambiaStock = & $xajax->registerFunction("cambiaStock");
$fcambiaStock->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fcambiaStock->setParameter(1,XAJAX_INPUT_VALUE,'cboUnidad');

//$fgenerarNumero = & $xajax->registerFunction("generarNumero");
//$fgenerarNumero->setParameter(0,XAJAX_INPUT_VALUE,'cboTipoDocumento');

$xajax->registerFunction("genera_cboUnidad");
$xajax->registerFunction("crearSelectPasar");

$faperturacaja = & $xajax-> registerFunction('aperturacaja');
$faperturacaja->setParameter(0,XAJAX_INPUT_VALUE,'txtFecha');

$flistado = & $xajax-> registerFunction('listado');
$flistado->setParameter(0,XAJAX_INPUT_VALUE,'cboCategoria');
$flistado->setParameter(1,XAJAX_INPUT_VALUE,'campo');
$flistado->setParameter(2,XAJAX_INPUT_VALUE,'frase');
$flistado->setParameter(3,XAJAX_INPUT_VALUE,'Pag');
$flistado->setParameter(4,XAJAX_INPUT_VALUE,'TotalReg');
$flistado->setParameter(5,XAJAX_INPUT_VALUE,'cboMoneda');

//$xajax->registerFunction("generarcuotas");
$fgenerarcuotas=& $xajax->registerFunction("generarcuotas");
$fgenerarcuotas->setParameter(0,XAJAX_INPUT_VALUE,'Contador');
$fgenerarcuotas->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaVenta');
$fgenerarcuotas->setParameter(2,XAJAX_INPUT_VALUE,'txtTotal');
$fgenerarcuotas->setParameter(3,XAJAX_INPUT_VALUE,'txtInicial');
$fgenerarcuotas->setParameter(4,XAJAX_INPUT_VALUE,'txtInteres');

$xajax->registerFunction("vercuotas");

$xajax->registerFunction("referenciar");


$xajax->registerFunction("quitar");



$fcomprobarnumero = & $xajax-> registerFunction('comprobarNumeroDocumento');
$fcomprobarnumero->setParameter(0,XAJAX_INPUT_VALUE,'txtNroDoc');
$fcomprobarnumero->setParameter(1,XAJAX_INPUT_VALUE,'txtIdPersona');
$fcomprobarnumero->setParameter(2,XAJAX_INPUT_VALUE,'cboTipoDocumento');
$fcomprobarnumero->setParameter(3,XAJAX_INPUT_VALUE,'txtFecha');

$freferenciarcompras = & $xajax->registerFunction("referenciarcompras");
$freferenciarcompras->setParameter(0,XAJAX_INPUT_VALUE,'txtNumeroRef');
$freferenciarcompras->setParameter(1,XAJAX_INPUT_VALUE,'cboTipoDocRef');
$freferenciarcompras->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRef');
$freferenciarcompras->setParameter(3,XAJAX_INPUT_VALUE,'cboFormaPagoRef');

$fsaldocaja = & $xajax-> registerFunction('saldocaja');
$fsaldocaja->setParameter(0,XAJAX_INPUT_VALUE,'txtFecha');
$fsaldocaja->setParameter(1,XAJAX_INPUT_VALUE,'cboMoneda');
$fsaldocaja->setParameter(2,XAJAX_INPUT_VALUE,'txtTotal');

$fagregar = & $xajax-> registerFunction('agregar');
$fagregar->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fagregar->setParameter(1,XAJAX_INPUT_VALUE,'cboUnidad');
$fagregar->setParameter(2,XAJAX_INPUT_VALUE,'txtCantidad');
$fagregar->setParameter(3,XAJAX_INPUT_VALUE,'txtPrecioCompra');
$fagregar->setParameter(4,XAJAX_INPUT_VALUE,'txtPrecioVenta');
$fagregar->setParameter(5,XAJAX_INPUT_VALUE,'txtPrecioVentaE');
$fagregar->setParameter(6,XAJAX_INPUT_VALUE,'txtDetalle');
$fagregar->setParameter(7,XAJAX_INPUT_VALUE,'txtStockActual');
$fagregar->setParameter(8,XAJAX_INPUT_VALUE,'cboMoneda');
$fagregar->setParameter(9,XAJAX_INPUT_VALUE,'txtImpuesto');
$fagregar->setParameter(10,XAJAX_INPUT_VALUE,'cboTipoDocumento');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>