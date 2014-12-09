<?php
@require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores

require("../datos/cado.php");
require("../negocio/cls_producto.php");
require("../negocio/cls_persona.php");
$ObjPersona = new clsPersona();
$EncabezadoTabla=array("C&oacute;digo","Descripci&oacute;n","Categoria","Marca","Unidad","Precio Venta","Stock","Armario","Columna","Fila");
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
	$rs = $objProducto->buscarparaventaconxajax($categoria,$campo,$frase,$limite,$idsucursal,$moneda,$tipocambio);
    $nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $objProducto->buscarparaventaconxajax($categoria,$campo,$frase,$limite,$idsucursal,$moneda,$tipocambio);
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
	   if($reg[9]<=$reg[13]){$rojo="red";}
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr class='$estilo' style='color:$rojo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				$RegistroEdicion="<td><a href='#agregar' onClick='seleccionar(".$reg[$i].",&quot;".$moneda."&quot;)'>Seleccionar</a></td>";
			}elseif($i==8 or $i==9){
				$registros.="<td align='right'>".$reg[$i]."</td>";
			}elseif($i==11 or $i==12){
				$registros.="<td align='center'>".$reg[$i]."</td>";
			}elseif($i==5 or $i==6 or $i == 13){
				$registros .= "";
				/*$color=split('-',$reg[$i]);
				if($color[1]==''){
					$registros.="<td align='center' title=".$color[0].">".$color[0]."</td>";
				}else{
					$registros.="<td align='center' title=".$color[0]." bgcolor=".$color[1].">&nbsp;</td>";
				}*/
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
	$consulta = $ObjListaUnidad->buscarparaventaconxajax($idsucursal,$idproducto,$moneda,$tipocambio);

	$Unidads="<table><tr><td><select name='cboUnidad' id='cboUnidad' onchange='cambiaStock()'>";
	while($registro=$consulta->fetchObject()){
		if($registro->idunidad==$registro->idunidadbase){ 
			$seleccionar="Selected";
			$precioventa=$registro->precioventa;
			$precioespecial=$registro->precioventaespecial;
			$producto=$registro->producto;
			$StockActual=$registro->StockActual;
		}else{$seleccionar="";}
		$Unidads=$Unidads."<option value='".$registro->idunidad."' ".$seleccionar.">".$registro->unidad."</option>";
	}
	$Unidads=$Unidads."</select></td><td><a href='list_listaunidad.php?IdProducto=$idproducto' target='_blank' title='Ver formula unidades'>...</a></td></tr></table>";
	$Unidads=utf8_encode($Unidads);
	$obj=new xajaxResponse();
	$obj->assign("DivUnidad","innerHTML",$Unidads);
	$obj->assign("txtPrecioVenta","value",$precioventa);
	$obj->assign("lblProducto","innerHTML",utf8_encode($producto));
	$obj->assign("txtIdProductoSeleccionado","value",$idproducto);
	$obj->assign("txtStockActual","value",$StockActual);
	$obj->assign("txtPrecioNormal","value",$precioventa);
	$obj->assign("txtPrecioEspecial","value",$precioespecial);
	return $obj;
}


function cambiaStock($idproducto,$idunidad,$moneda){
	Global $idsucursal;
	Global $tipocambio;
	require("../negocio/cls_stockproducto.php");
	$ObjStockProducto = new clsStockProducto();
	$consulta = $ObjStockProducto->obtenerStock($idsucursal,$idproducto,$idunidad);
	$registro=$consulta->fetchObject();
	$StockActual=$registro->StockActual;
	
	require("../negocio/cls_listaunidad.php");
	$ObjListaUnidad = new clsListaUnidad();
	$consulta2 = $ObjListaUnidad->buscarprecioventa(NULL,$idunidad,$idproducto,NULL);
	$dato=$consulta2->fetchObject();
	$precioventa=$dato->precioventa;
	$precioespecial=$dato->precioventaespecial;
	
	if($dato->moneda!=$moneda){
	if($moneda=='S'){$precioventa=$precioventa*$tipocambio;$precioespecial=$precioespecial*$tipocambio;
	}else{$precioventa=$precioventa/$tipocambio;$precioespecial=$precioespecial/$tipocambio;}
	}
	
	$obj=new xajaxResponse();
	$obj->assign("txtStockActual","value",$StockActual);
	$obj->assign("txtPrecioVenta","value",number_format($precioventa,2));
	$obj->assign("txtPrecioNormal","value",number_format($precioventa,2));
	$obj->assign("txtPrecioEspecial","value",number_format($precioespecial,2));
	$obj->assign("txtStockActual","value",$StockActual);
	return $obj;
}

function agregardetallepedido($idpedido){
	Global $carroventa;
	Global $objProducto;
	

	$_SESSION['carroventa']='';
	if(isset($_SESSION['carroventa']))
		$carroventa=$_SESSION['carroventa'];
		
	require "../negocio/cls_movimiento.php";
	$objMovimiento = new clsMovimiento();

	$rs = $objMovimiento->consultardetallepedido($idpedido);	
	while($reg=$rs->fetchObject()){
		$idtipomovimiento=$reg->idtipomovimiento;
		$idcliente=$reg->idpersona;
		$cliente=$reg->cliente;
		$idsucursal=$reg->idsucursal;
		$sucursal=$reg->sucursal;
		$numpedido=$reg->numero;
		$carroventa[($reg->idproducto)]=array('idproducto'=>($reg->idproducto),'codigo'=>($reg->codigo),'producto'=>($reg->producto),'cantidad'=>($reg->cantidad),'idunidad'=>($reg->idunidad), 'unidad'=>($reg->unidad), 'precioventa'=>$reg->precioventa, 'precioventaoriginal'=>$reg->precioventa, 'moneda'=>'S');
	
	}
			
	$_SESSION['carroventa']=$carroventa;
	
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Precio Ofertado</th>	
	<th>SubTotal</th>
	";
	foreach($carroventa as $k => $v){
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".$v["precioventa"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/(100+$_SESSION['IGV']))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	$documento='1';
	$impuesto='';
	if($documento=='1' or $documento=='13'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
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
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($igv2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($total,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($total,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("txtDetalle","value",'TRUE');
	if($idtipomovimiento=='5'){
		$obj->assign("txtIdPersona","value",$idsucursal);
		$obj->assign("txtNombresPersona","value",$sucursal);	
	}else{
		$obj->assign("txtIdPersona","value",$idcliente);
		$obj->assign("txtNombresPersona","value",$cliente);	
	}	
	$obj->assign("txtIdMovimientoRef","value",$idpedido);
	$obj->assign("txtIdSucursalRef","value",$idsucursal);
	$obj->assign("txtReferencia","value",$numpedido);
	
	return $obj;
}

function agregar($idproducto,$idunidad,$cantidad,$precioventa,$txtDetalle,$StockActual,$moneda,$impuesto,$documento){
	Global $carroventa;
	Global $objProducto;
	
	if($cantidad>0 and $cantidad<=$StockActual and $precioventa>=0 and isset($precioventa) and isset($cantidad) and $precioventa<>'' and $cantidad<>''){
		
	if($txtDetalle=='FALSE')$_SESSION['carroventa']='';
	if(isset($_SESSION['carroventa']))
		$carroventa=$_SESSION['carroventa'];
		
	$rs = $objProducto->buscarxidproductoyidunidad($idproducto,$idunidad);	
    $reg=$rs->fetchObject();	
		
	$carroventa[($idproducto)]=array('idproducto'=>($idproducto),'codigo'=>$reg->codigo,'producto'=>$reg->producto,'cantidad'=>$cantidad,'idunidad'=>$idunidad, 'unidad'=>$reg->unidad, 'precioventa'=>$precioventa, 'precioventaoriginal'=>$precioventa, 'moneda'=>$moneda);

	$_SESSION['carroventa']=$carroventa;
	
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Precio Ofertado</th>	
	<th>SubTotal</th>
	";
	foreach($carroventa as $k => $v){
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".$v["precioventa"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/(100+$_SESSION['IGV']))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	if($documento=='1' or $documento=='13'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
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
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($igv2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($total,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($total,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("txtDetalle","value",'TRUE');
	}else{
		if($precioventa<0 or $precioventa==''){
			$mensaje="El precio de venta no puede ser menor a cero!!!";
		}else{
			$mensaje="La cantidad no puede ser menor o igual a cero ni mayor al stock actual!!!";}
	$obj=new xajaxResponse();
	$obj->alert($mensaje);
	}
	
	
	
	
	return $obj;
}



function quitar($idproducto,$impuesto,$documento){
	Global $carroventa;
	if(isset($_SESSION['carroventa']))
		$carroventa=$_SESSION['carroventa'];
		
	unset($carroventa[($idproducto)]);

	$_SESSION['carroventa']=$carroventa;
	
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Precio Ofertado</th>	
	<th>SubTotal</th>
	";
	foreach($carroventa as $k => $v){
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".$v["precioventa"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/(100+$_SESSION['IGV']))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	if($documento=='1'  or $documento=='13'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
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
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($igv2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($total,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($total,2)."'>
	<input type='hidden' name='rTotal2' id='rTotal2' value='".number_format($suma,2)."'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}



function cambiarmonedadetalle($moneda,$impuesto,$documento){
	Global $carroventa;
	Global $tipocambio;
	
	if(isset($_SESSION['carroventa']))
		$carroventa=$_SESSION['carroventa'];
			
	$contador=0;
	$suma=0;
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Precio Ofertado</th>	
	<th>SubTotal</th>
	";
	foreach($carroventa as $k => $v){
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		if($v["moneda"]=='S'){
			if($moneda=='S'){
				$v["precioventa"]=$v["precioventaoriginal"];
			}else{
				$v["precioventa"]=$v["precioventaoriginal"]/$tipocambio;}
		}else{
			if($moneda=='D'){
				$v["precioventa"]=$v["precioventaoriginal"];
			}else{
				$v["precioventa"]=$v["precioventaoriginal"]*$tipocambio;}
		}
		$registros.="<td align='right'>".number_format($v["precioventa"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
	}
	$igv=($_SESSION['IGV']/100)*$suma;
	$total=number_format($suma,2)+number_format($igv,2);
		
	$sub2=(100/(100+$_SESSION['IGV']))*$suma;
	$igv2=number_format($suma,2)-number_format($sub2,2);
	
	
	if($documento=='1' or $documento=='13'){	$type='hidden';	$t1='';$t2='';}else{$type ='text';$t1='Igv: ';$t2='Subtotal: ';}
	
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
	$registros.="<input type='$type' name='txtIgv' id='txtIgv' value='".number_format($igv2,2)."' readonly='' >";}
	
	$registros.="
	<input type='hidden' name='rIgv' id='rIgv' value='".number_format($igv,2)."'>
	<input type='hidden' name='rIgv2' id='rIgv2' value='".number_format($igv2,2)."'>
	Total :"; 
	
	if($impuesto=='1'){
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($total,2)."' readonly=''>";}else{
	$registros.="<input type='text' name='txtTotal' id='txtTotal' value='".number_format($suma,2)."' readonly=''>";}
	
	$registros.="
	<input type='hidden' name='rTotal' id='rTotal' value='".number_format($total,2)."'>
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
	$EncabezadoTabla=array("Nombres y Apellidos","RUC/DNI");
	$regxpag=10;
	$nr1=$TotalReg;
	$pag=1;
	$inicio=$regxpag*($pag - 1);
	$limite="";
	$frase=utf8_decode($frase);	
	if($inicio==0){		
		$rs = $ObjPersona->consultarpersonarolventa($origen,$campo,$frase,$limite);	
    	$nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $ObjPersona->consultarpersonarolventa($origen,$campo,$frase,$limite);	
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
				if($origen=='0'){//TODOS
				$RegistroSeleccion="<td><a href='#' onClick='mostrarPersona(".$reg[$i].")'>Seleccionar</a></td>";		
				}
				if($origen=='3'){//EMPLEADO
				$RegistroSeleccion="<td><a href='#' onClick='mostrarEmpleado(".$reg[$i].")'>Seleccionar</a></td>";		
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

function generarventas($numero,$idtipodoc,$fechainicio,$fechafin,$formapago, $conguia){
	Global $idsucursal;
	Global $idusuario;
	if($idtipodoc==0){$idtipodoc=NULL;}
	if($formapago=='F'){$formapago=NULL;}
	if($conguia=='0'){$conguia=NULL;}
	$registro.="<table width='1031' class='registros'>
	<tr>
	<th width='74'>DOC.</th>
	<th width='129'>N&deg;</th>
	<th width='144'>FECHA</th>
	<!--<th width='159'>FORMA PAGO </th>-->
	<th width='106'>CLIENTE</th>
	<th width='144'>RESPONSABLE</th>
	<!--<th width='76'>SUBTOTAL</th>-->
	<!--<th width='76'>IGV</th>-->
	<th width='76'>TOTAL</th>
	<!--<th width='76'>MONEDA</th>-->
	<th width='76'>COMENTARIO</th>
	<!--<th width='144'>ESTADO</th>-->
	<th colspan='5'>OPERACIONES</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$rst = $objMovimiento->consultarventa(2,NULL,$idsucursal,$numero,$idtipodoc,$fechainicio,$fechafin,$formapago, $conguia);

	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
	   
		if($dato->estado=='A'){
		$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".$dato->documento."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$dato->documento."$m</td>";
		$estado='Normal';
		}
		if($dato->numero=="0"){
		$registro.="<td>Sin Entregar</td>";
		}else{
		$registro.="<td align='center'>".$dato->numero."</td>";
		}
		$registro.="<td align='center'>".$dato->fecha."</td>";
		/*if($dato->formapago=="A"){
		$formapago="Contado";}
		else{
		$formapago="Credito";}*/

	//$registro.="<td>".$formapago."</td>";
	$registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	/*$registro.="<td align='center'>".$dato->subtotal."</td>";
	$registro.="<td align='center'>".$dato->igv."</td>";*/
	$registro.="<td align='center'>".$dato->total."</td>";
	//$registro.="<td align='center'>".$dato->moneda."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td>".$dato->comentario."</td>";
		}
	//$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td width='54'><a href='frm_detalleventa.php?IdVenta=".$dato->idmovimiento."'>Ver Detalle </a></td>";

	if($formapago=="Credito"){
	$registro.="<td><a onClick='vercuotas(".$dato->idmovimiento.")'>VerCuotas</a></td>";
	}/*else{
	$registro.="<td>   -   </td>";
	}*/
	if($dato->idtipodocumento==1){
	$registro.="<td><a href='frm_comprobanteBA.php?idventa=".$dato->idmovimiento."&origen=VENTA'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==2){
	$registro.="<td><a href='frm_comprobanteF.php?idventa=".$dato->idmovimiento."&T=".$dato->total."&M=".$dato->moneda."&origen=VENTA'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==12){
	$registro.="<td><a href='frm_comprobanteBA.php?idventa=".$dato->idmovimiento."&origen=VENTA'>Ver Comprobante</a></td>";
	}
	
	if($conguia=='1'){
	$registro.="<td><a href='frm_guiaremision.php?IdVenta=".$dato->idmovimiento."'>Generar Guia</a></td>";
	}else{
		if($conguia=='2'){
			if($dato->numguia=='5'){
		$registro.="<td><a href='frm_detalleguia.php?IdVenta=".$dato->idguia."'>Ver Guia Rem.</a></td>";
			}else{
			$registro.="<td>-</td>";
			}		
		}
	}	
	
	if($estado!="Anulado"){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=ANULAR&IdVenta=".$dato->idmovimiento."&IdUsuario=".$idusuario."&IdSucursal=".$idsucursal."'>Anular</a>";
	}else{
	$registro.="<td> - ";
	}
	$registro.="    </td>";
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVenta","innerHTML",$registro);
	return $obj;
}


function generarventasalamcen($numero,$idtipodoc,$fechainicio,$fechafin,$formapago, $conguia){
	Global $idsucursal;
	Global $idusuario;
	if($idtipodoc==0){$idtipodoc=NULL;}
	if($formapago=='F'){$formapago=NULL;}
	if($conguia=='0'){$conguia=NULL;}
	$registro.="<table width='1031' class='registros'>
	<tr>
	<th width='74'>DOC.</th>
	<th width='129'>N&deg;</th>
	<th width='144'>FECHA</th>
	<th width='159'>FORMA PAGO </th>
	<th width='106'>CLIENTE</th>
	<th width='144'>RESPONSABLE</th>
	<th width='76'>SUBTOTAL</th>
	<th width='76'>IGV</th>
	<th width='76'>TOTAL</th>
	<th width='76'>MONEDA</th>
	<th width='76'>COMENTARIO</th>
	<th width='144'>ESTADO</th>
	<th colspan='5'>OPERACIONES</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$rst = $objMovimiento->consultarventa(2,NULL,$idsucursal,$numero,$idtipodoc,$fechainicio,$fechafin,$formapago, $conguia);

	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
		
		if($dato->estado=='A'){
		$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".$dato->documento."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$dato->documento."$m</td>";
		$estado='Normal';
		}
		if($dato->numero=="0"){
		$registro.="<td>Sin Entregar</td>";
		}else{
		$registro.="<td align='center'>".$dato->numero."</td>";
		}
		$registro.="<td align='center'>".$dato->fecha."</td>";
		if($dato->formapago=="A"){
		$formapago="Contado";}
		else{
		$formapago="Credito";}

	$registro.="<td>".$formapago."</td>";
	$registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	$registro.="<td align='center'>".$dato->subtotal."</td>";
	$registro.="<td align='center'>".$dato->igv."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	$registro.="<td align='center'>".$dato->moneda."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td>".$dato->comentario."</td>";
		}
	$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td width='54'><a href='frm_detalleventaalmacen.php?IdVenta=".$dato->idmovimiento."'>Ver Detalle </a></td>";

/*	if($formapago=="Credito"){
	$registro.="<td><a onClick='vercuotas(".$dato->idmovimiento.")'>VerCuotas</a></td>";
	}else{
	$registro.="<td>   -   </td>";
	}
	if($dato->idtipodocumento==1){
	$registro.="<td><a href='frm_comprobanteBA.php?idventa=".$dato->idmovimiento."'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==2){
	$registro.="<td><a href='frm_comprobanteF.php?idventa=".$dato->idmovimiento."&T=".$dato->total."&M=".$dato->moneda."'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==12){
	$registro.="<td><a href='frm_comprobanteBA.php?idventa=".$dato->idmovimiento."'>Ver Comprobante</a></td>";
	}
*/	
	/*if($conguia=='1'){
	$registro.="<td><a href='frm_guiaremision.php?IdVenta=".$dato->idmovimiento."'>Generar Guia</a></td>";
	}else{
		if($conguia=='2'){
			if($dato->numguia=='5'){
		$registro.="<td><a href='frm_detalleguia.php?IdVenta=".$dato->idguia."'>Ver Guia Rem.</a></td>";
			}else{
			$registro.="<td>Despachado</td>";
			}		
		}
	}	*/
	
	if(!isset($dato->kardex)){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=DESPACHAR&IdVenta=".$dato->idmovimiento."'>Despachar sin Guia</a></td>";
	$registro.="<td><a href='frm_guiaremision.php?IdVenta=".$dato->idmovimiento."'>Despachar con Guia</a></td>";
	}else{
		$registro.="<td>Despachado</td>";
	}	
/*	if($estado!="Anulado"){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=ANULAR&IdVenta=".$dato->idmovimiento."&IdUsuario=".$idusuario."&IdSucursal=".$idsucursal."'>Anular</a>";
	}else{
	$registro.="<td> - ";
	}
	$registro.="    </td>";
*/	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVenta","innerHTML",$registro);
	return $obj;
}

function referenciarventas($numero,$idtipodoc,$fechainicio,$formapago){
	if($idtipodoc=='0'){$idtipodoc=NULL;}
	if($formapago=='F'){$formapago=NULL;}
	$registro.="<table width='500' border='0' class='registros'>
	<tr>
	<th >Doc.</th>
	<th >Fecha</th>
	<th >Cliente</th>
	<th >Total</th>
	<th >Est.</th>
	<th >Oper.</th>
	</tr>";
	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();
	//si consultamos todasd:
	//$rst = $objMovimiento->consultarventa(2,NULL,NULL,$numero,$idtipodoc,$fechainicio,NULL,$formapago);
	
	//si consultamo solo anuladas
		$rst = $objMovimiento->consultarventaanulada(2,$numero, $idtipodoc,$fechainicio, $formapago);
	while($dato = $rst->fetchObject()){
		if($dato->numero=="0"){
		$numero='Sin Ent.';
		}else{
		$numero=substr($dato->numero,0,10);
		}
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";	
	
		if($dato->estado=='A'){
			$estado='Anulado';
			$registro.="<tr style='color:#FF0000' class='$estilo'><td>".substr($dato->documento,0,1).'/N.'.$numero."</td>";
			
		}else{
		$registro.="<tr class='$estilo'><td>".substr($dato->documento,0,1).'/N.'.substr($dato->numero,0,10)."$m</td>";
		$estado='Normal';
		}
		
		$registro.="<td align='center'>".$dato->fecha."</td>";
		if(strrpos($dato->acliente,' ')>0){
		$apellido=substr($dato->acliente,0,strrpos($dato->acliente,' '));
		}else{
		$apellido=$dato->acliente;
		}
		
	$registro.="<td align='center'>".substr($dato->cliente,0,1).'. '.$apellido."</td>";
	$registro.="<td align='center'>".$dato->total.'/'.$dato->moneda."</td>";
	$registro.="<td align='center'>".substr($estado,0,1)."</td>";
	$registro.="<td width><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' onClick='verdetalle(".$dato->idmovimiento.")'>Ver</a></td>";

	$registro.="<td><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' 		onClick='referenciar(".$dato->idmovimiento.")'>Ref.</a></td>";
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVentas","innerHTML",$registro);
	return $obj;
}

function verbuscarpersona($origen){

if($origen=='100'){
$registro.="<fieldset><legend><strong>BUSQUEDA TRANSPORTISTAS:</strong></legend>
  <div id='divbusquedaPersona' class='textoazul'>   
    <div align='left' class='alignleft'>&nbsp;Por:
  <select name='campoPersona' id='campoPersona' onChange='buscarchofer()'>
    <option value='1'>Nombre/Razon Social de Empresa</option>
	<option value='2'>NroDoc. Empresa</option>
	<option value='3'>Chofer</option>
  </select><br>
      &nbsp;Descripción:
  <input type='text' name='frasePersona' id='frasePersona' onKeyUp='buscarchofer()'>
  <button type='button' class='boton' onClick=\"window.open('mant_transportista.php?accion=NUEVO&origen=DOC','_blank','width=380,height=480');\">NUEVO</button>
    </div>
  </div>
<div id='divregistrosPersona'></div>
</fieldset>
";
}
else if($origen=='0' || $origen=='3'){
$registro.="<fieldset><legend><strong>BUSQUEDA PERSONAS:</strong></legend>
<input type='hidden' name='pagPersona' id='pagPersona' value='1'>
<input type='hidden' name='TotalRegPersona' id='TotalRegPersona'>
<input type='hidden' name = 'txtOrigenPersona' id='txtOrigenPersona' value = '".$origen."'>
  <div id='divbusquedaPersona' class='textoazul'>   
    <div align='left'class='alignleft'>&nbsp;Por:
  <select name='campoPersona' id='campoPersona' onChange='buscarPersona()'>
    <option value=\"CONCAT(apellidos,'',nombres)\">Apellidos y Nombres</option>
	<option value='NroDoc'>Nro Doc.</option>
  </select><br>
      &nbsp;Descripción:
  <input type='text' name='frasePersona' id='frasePersona' onKeyUp='buscarPersona()'>
  <button type='button' class='boton' onClick=\"window.open('mant_persona.php?accion=NUEVO&origen=DOC','_blank','width=380,height=480');\">NUEVO</button>
    </div>
  </div>
<div id='divnumregPersona' class='registros'></div>
<div id='divregistrosPersona'></div>
</fieldset>
";
}else{
$registro.="<fieldset><legend><strong>REFERENCIAR VENTAS:</strong></legend> <table width='400' border='0' class='alignleft'>
    <tr width='150'>
      <td>Tipo: <select name='cboFormaPago' id='cboFormaPago'>
	    <option value='F'>Todos..</option>
        <option value='A'>Contado</option>
        <option value='B'>Credito</option>
      </select>	  </td>
      <td>A partir de: <input name='txtFechaRegistro2' type='text' id='txtFechaRegistro2' size='10' value=''>
	  <button type='button' id='btnCalendar2' class='boton' onClick='vercalendar()'> <img src='../imagenes/b_views.png'> </button></td>
      </tr>
    <tr width='150'>
      <td>Tipo Doc.:<select name='cboTipoDoc' id='cboTipoDoc'>
	    <option value='0'>Todos..</option>
        <option value='1'>Boleta</option>
        <option value='2'>Factura</option>
      </select></td>
             
      <td >Nro.: <input name='txtNumero' type='text' id='txtNumero' size='10' maxlength='10'> 
	  <input name = 'BUSCAR' type='button' id='BUSCAR' value = 'BUSCAR' onClick='verlistaventas()'>
	  </td>
	  </tr>
  </table><BR><div id='divListaVentas'></div>
  </fieldset>
  ";
}

$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divBuscarPersona","innerHTML",$registro);
	return $obj;
}

function generarventasinicio(){
	Global $idsucursal;
	Global $idusuario;
	Global $fechaproceso;
	$registro.="<table width='1031' class='registros'>
	<tr>
	<th width='74'>DOC.</th>
	<th width='129'>N&deg;</th>
	<th width='144'>FECHA</th>
	<!--<th width='159'>FORMA PAGO </th>-->
	<th width='106'>CLIENTE</th>
	<th width='144'>RESPONSABLE</th>
	<!--<th width='76'>SUBTOTAL</th>
	<th width='76'>IGV</th>-->
	<th width='76'>TOTAL</th>
	<!--<th width='76'>MONEDA</th>-->
	<th width='76'>COMENTARIO</th>
	<!--<th width='144'>ESTADO</th>-->
	<th colspan='4'>OPERACIONES</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$m=substr($fechaproceso,5,2);
	$m=$m-1;
	$m=substr('00',0,2-strlen($m)).$m;
	$rst = $objMovimiento->consultarventa(2,NULL,$idsucursal,NULL,NULL,$fechaproceso/*date("Y-".$m."-d")*/,$fechaproceso,NULL,NULL);

	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
		
		if($dato->estado=='A'){$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".$dato->documento."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$dato->documento."</td>";
		$estado='Normal';
		}
	if($dato->numero=="0"){
	$registro.="<td>Sin Entregar</td>";
	}else{
	$registro.="<td align='center'>".$dato->numero."</td>";
	}
	$registro.="<td align='center'>".$dato->fecha."</td>";
	/*if($dato->formapago=="A"){
	$formapago="Contado";}
	else{
	$formapago="Credito";}

	$registro.="<td>".$formapago."</td>";*/
	$registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	//$registro.="<td align='center'>".$dato->subtotal."</td>";
	//$registro.="<td align='center'>".$dato->igv."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	//$registro.="<td align='center'>".$dato->moneda."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td>".$dato->comentario."</td>";
		}
	//$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td width='54'><a href='frm_detalleventa.php?IdVenta=".$dato->idmovimiento."'>Ver Detalle </a></td>";
if($formapago=="Credito"){
	$registro.="<td><a onClick='vercuotas(".$dato->idmovimiento.")'>VerCuotas</a></td>";
	}else{
	//$registro.="<td>-</td>";
	}
	
	if($dato->idtipodocumento==1){
	$registro.="<td><a href='frm_comprobanteBA.php?idventa=".$dato->idmovimiento."&origen=VENTA'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==2){
	$registro.="<td><a href='frm_comprobanteF.php?idventa=".$dato->idmovimiento."&T=".$dato->total."&M=".$dato->moneda."&origen=VENTA'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==12){
	$registro.="<td><a href='frm_comprobanteR.php?idventa=".$dato->idmovimiento."&origen=VENTA'>Ver Comprobante</a></td>";
	}
		
	if($estado!="Anulado"){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=ANULAR&IdVenta=".$dato->idmovimiento."&IdUsuario=".$idusuario."&IdSucursal=".$idsucursal."'>Anular</a>";
	}else{
	$registro.="<td> - ";
	}
	$registro.="</td>";
	
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVenta","innerHTML",$registro);
	return $obj;
}

function generarventasalmaceninicio(){
	Global $idsucursal;
	Global $idusuario;
	Global $fechaproceso;
	$registro.="<table width='1031' class='registros'>
	<tr>
	<th width='74'>DOC.</th>
	<th width='129'>N&deg;</th>
	<th width='144'>FECHA</th>
	<th width='159'>FORMA PAGO </th>
	<th width='106'>CLIENTE</th>
	<th width='144'>RESPONSABLE</th>
	<th width='76'>SUBTOTAL</th>
	<th width='76'>IGV</th>
	<th width='76'>TOTAL</th>
	<th width='76'>MONEDA</th>
	<th width='76'>COMENTARIO</th>
	<th width='144'>ESTADO</th>
	<th colspan='4'>OPERACIONES</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$m=substr($fechaproceso,5,2);
	$m=$m-1;
	$m=substr('00',0,2-strlen($m)).$m;
	$rst = $objMovimiento->consultarventa(2,NULL,$idsucursal,NULL,NULL,date("Y-".$m."-d"),$fechaproceso,NULL,NULL);

	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
		
		if($dato->estado=='A'){$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".$dato->documento."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$dato->documento."</td>";
		$estado='Normal';
		}
	if($dato->numero=="0"){
	$registro.="<td>Sin Entregar</td>";
	}else{
	$registro.="<td align='center'>".$dato->numero."</td>";
	}
	$registro.="<td align='center'>".$dato->fecha."</td>";
	if($dato->formapago=="A"){
	$formapago="Contado";}
	else{
	$formapago="Credito";}

	$registro.="<td>".$formapago."</td>";
	$registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	$registro.="<td align='center'>".$dato->subtotal."</td>";
	$registro.="<td align='center'>".$dato->igv."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	$registro.="<td align='center'>".$dato->moneda."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td>".$dato->comentario."</td>";
		}
	$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td width='54'><a href='frm_detalleventaalmacen.php?IdVenta=".$dato->idmovimiento."'>Ver Detalle </a></td>";
/*if($formapago=="Credito"){
	$registro.="<td><a onClick='vercuotas(".$dato->idmovimiento.")'>VerCuotas</a></td>";
	}else{
	$registro.="<td>-</td>";
	}
*/	
/*	if($dato->idtipodocumento==1){
	$registro.="<td><a href='frm_comprobanteBA.php?idventa=".$dato->idmovimiento."'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==2){
	$registro.="<td><a href='frm_comprobanteF.php?idventa=".$dato->idmovimiento."&T=".$dato->total."&M=".$dato->moneda."'>Ver Comprobante</a></td>";
	}
	if($dato->idtipodocumento==12){
	$registro.="<td><a href='frm_comprobanteR.php?idventa=".$dato->idmovimiento."'>Ver Comprobante</a></td>";
	}*/
		
/*	if($estado!="Anulado"){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=ANULAR&IdVenta=".$dato->idmovimiento."&IdUsuario=".$idusuario."&IdSucursal=".$idsucursal."'>Anular</a>";
	}else{
	$registro.="<td> - ";
	}
	$registro.="</td>";*/	
	if(!isset($dato->kardex)){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=DESPACHAR&IdVenta=".$dato->idmovimiento."'>Despachar sin Guia</a></td>";
	$registro.="<td><a href='frm_guiaremision.php?IdVenta=".$dato->idmovimiento."'>Despachar con Guia</a></td>";
	}else{
		$registro.="<td>Despachado</td>";
	}	

	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVenta","innerHTML",$registro);
	return $obj;
}

function generarnumero($documento,$fecha){
Global $idsucursal;
$registro.="<input  id='txtNroDoc' name='txtNroDoc' type='text' size='15' maxlength='15' value='";

	require('../negocio/cls_movimiento.php');
	
	$year=substr(trim($fecha),0,4);
	 
	$objMov=new clsMovimiento(); 
	if($documento==15){
		$tipomov=5;
	}else{
		$tipomov=2;
	}
	$registro.=$objMov->consultar_numero_sigue($tipomov,$documento,$idsucursal); 
	$registro.='-'.$year;
	$registro.="' >";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divNumero","innerHTML",$registro);
	
	if($documento==2){
	$obj->assign("txtNombresPersona","value","");
	$obj->assign("txtIdPersona","value","");
	}elseif($documento==15){
	$obj->assign("txtNombresPersona","value","FALLO LA CONEXION");
	$obj->assign("txtIdPersona","value","");
	}else{
	$obj->assign("txtNombresPersona","value","CLIENTE VARIOS");
	$obj->assign("txtIdPersona","value","5");

	}
	return $obj;
}

function presentarinicio(){
	
	
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Precio Ofertado</th>	
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
	
	if(isset($_SESSION['IdUsuario'])){
	  Global $ObjPersona;
	  $rs = $ObjPersona->buscarXId($_SESSION['IdUsuario']);	
	  $reg= $rs->fetchObject();
	  $obj->assign('txtIdResponsable','value',$reg->IdPersona);
	  $obj->assign('txtNombresResponsable','value',utf8_encode($reg->Nombres));
	}
	
	Global $idsucursal;
	Global $fechaproceso;
require('../negocio/cls_movimiento.php');
	$objMov=new clsMovimiento(); 
	$numero=$objMov->verificarcierrecaja($fechaproceso,$idsucursal);
	
	if($numero!=1){
	require('../negocio/cls_movcaja.php');
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
	
	}
	return $obj;
}

function generarcuotas($numcuota,$fecha,$total,$inicial,$interes){
	if($numcuota<1){
	if($numcuota==""){$texto="";}else{$texto="1";}
	$numcuota=1;
	
	}else{
	$texto=$numcuota;
	}
	
	$cuota=($total-$inicial)/$numcuota;
	$cuotafinal=$total-$inicial -($cuota*($numcuota-1));
	$cuota=number_format($cuota,2);
	$cuotafinal=number_format($cuotafinal,2);

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
  <td><input type='text' name = 'txtInteres".$i."' id = 'txtInteres".$i."' value = '$interes' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' onKeyUp='completar(".$i.','.$numcuota.")'></td>
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
	}
	return $obj;
}

function vercuotas($idventa){
	require('../negocio/cls_movimiento.php');
	
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->consultarcuotaventa($idventa); 
	$numcuota=$rst->rowCount();
	$suma=0;
	$numerocuota=0;
	$i=1;
	while($dato = $rst->fetchObject()){
if($dato->estado!="A"){
$registros.="<table>";
if($dato->estado=="N"){
$nuevointeres=number_format($dato->interes);
$nuevafecha=substr($dato->fechacancelacion,0,10);
$nuevamoneda=$dato->moneda;

$registros.="<tr><td><input type='checkbox' name='check".$i."' value='check".$i."' onClick='activar(".$i.")'>Modificar<input type='hidden' name='txtModificar".$i."' id='txtModificar".$i."' value='0'></td><td>
<input type='checkbox' name='checkeli".$i."' name='checkeli".$i."' value='checkeli".$i."' onClick='eliminar(".$i.",".$idventa.")'>Eliminar<input type='hidden' name='txtEliminar".$i."' id='txtEliminar".$i."' value='0'>
<div id='divEliminar".$i."'></div>
</td></tr>";
}
$registros.="<tr><td width='122'>Cuota N&deg;:</td>";
$registros.="<td width='219'>".$dato->nombre."<input type='hidden' name = 'txtNumCuota".$i."' value = '".$dato->nombre."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' readonly><input type='hidden' name='txtIdCuota".$i."' id='txtIdCuota".$i."' value='".$dato->idcuota."'></td>
</tr>";
if($dato->estado!="C"){
$registros.="<tr>
  <td>Monto :</td>
  <td><input type='text' name = 'txtSubtotal".$i."'  id= 'txtSubtotal".$i."' readonly ";
  $registros.="value = '".$dato->monto."'";
}else{
$registros.="<tr>
  <td>Monto :</td>
  <td>".$dato->monto."<input type='hidden' name = 'txtSubtotal".$i."'  id= 'txtSubtotal".$i."' readonly ";
  $registros.="value = '".$dato->monto."'";
}

$registros.="onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' onKeyUp=''></td></tr>";

 $registros.="<tr> <td>Monto Pagado:</td>
  <td>".number_format($dato->montopagado,2)."<input type='hidden' name = 'txtMontoPagado".$i."' readonly  id= 'txtMontoPagado".$i."'";
  $registros.="value = '".number_format($dato->montopagado,2)."' readonly  onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;'></td></tr>";

$registros2="<td>Moneda:</td>
  <td>".$dato->moneda."<input type='hidden' name = 'txtMoneda' id = 'txtMoneda' readonly value = '".$dato->moneda."' ></td>
";

if($dato->estado!="C"){
$registros.="<tr><td>Interes:</td>
  <td><input type='text' name = 'txtInteres".$i."' id = 'txtInteres".$i."' readonly value = '".$dato->interes."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' onKeyUp=''></td>
</tr>";
}else{
$registros.="<tr><td>Interes:</td>
  <td>".$dato->interes."<input type='hidden' name = 'txtInteres".$i."' id = 'txtInteres".$i."' readonly value = '".$dato->interes."' onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;' onKeyUp=''></td>
</tr>";
}


 $registros.="<tr> <td>Interes Pagado:</td>
  <td>".number_format($dato->interespagado,2)."<input type='hidden' name = 'txtInteresPagado".$i."'  id= 'txtInteresPagado".$i."'";
  $registros.="value = '".number_format($dato->interespagado,2)."' readonly  onKeyPress='if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;'></td></tr><tr>";

$registros.="<td>Fecha Cancelación:</td>";
if($dato->estado!="C"){
$registros.="<td><input name='txtFechaRegistro".$i."' type='text' id='txtFechaRegistro".$i."' value='".substr($dato->fechacancelacion,0,10)."' size='10' readonly='true'>
        <button type='button' id='btnCalendar".$i."' class='boton' onClick='elegirfecha(".$i.");' disabled ><img src='../imagenes/b_views.png'></button>
      </td></tr>";
}else{
$registros.="<td>".substr($dato->fechacancelacion,0,10)."<input name='txtFechaRegistro".$i."' type='hidden' id='txtFechaRegistro".$i."' value='".substr($dato->fechacancelacion,0,10)."' size='10' readonly='true'>
 </td></tr>";
}	  
	  
if(substr($dato->fechapago,0,10)!="0000-00-00"){
$registros.="<tr><td>Fecha Pago :</td><td>";
$registros.=substr($dato->fechapago,0,10)."<input name='txtFechaPago".$i."' type='hidden' id='txtFechaPago".$i."' value='".substr($dato->fechapago,0,10)."' size='10' readonly='true'>
        </td></tr>";
}	  
if($dato->estado=="N"){$estado="PENDIENTE"; }
if($dato->estado=="A"){$estado="ANULADA";}
if($dato->estado=="C"){$estado="CANCELADA"; }

$registros.="<tr>
  <td>Estado:</td>
  <td>$estado</td></tr>";
$registros.="<tr>  <td></td><td>";
$registros.="</td></tr><tr>  <td>&nbsp;</td>  <td>&nbsp;</td></tr></table>";
	if($dato->estado!="A"){
		$suma=$suma+$dato->monto;
		}
	$numerocuota++;
}else{
$registros.="<div><input type='hidden' name = 'txtSubtotal".$i."'  id= 'txtSubtotal".$i."' readonly ";
  $registros.=" value = '0.00' ></div>";
}

$i++;
}
$rst2=$objMov->consultarventa(2,$idventa,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
$dato2=$rst2->fetchObject();

$day=substr(trim($nuevafecha),8,2);
	$mes=substr(trim($nuevafecha),5,2);
	$year=substr(trim($nuevafecha),0,4);
	
	$mes=$mes+1;
	if($mes==13){$mes=1;$year=$year+1;}
	if($day>=29){
		if($mes==2){  if(($year%4) > 0){$day=28;}
		}elseif(($mes%2)==1 && $mes!=7){   
			if($day>30){$day=30;}
		}
	}	
	$cero='00';
	$day=substr($cero,0,2-strlen($day)).$day;
	$mes=substr($cero,0,2-strlen($mes)).$mes;
	$nuevafecha=$year.'-'.$mes.'-'.$day;

	$registros=utf8_encode($registros);
	$registros2=utf8_encode($registros2);
	$obj=new xajaxResponse();
	$obj->assign("DivCuotas","innerHTML",$registros);
	$obj->assign("divMoneda","innerHTML",$registros2);
	$obj->assign("DivCliente","innerHTML",$dato2->cliente.' '.$dato2->acliente);
	$obj->assign("DivFecha","innerHTML",$dato2->fecha);
	$obj->assign("Contador","value",$numerocuota);
	$obj->assign("txtContador","value",$numcuota);
	$obj->assign("txtTotal","value",$dato2->total);
	$obj->assign("txtAcuenta","value",number_format($suma,2));
	$obj->assign("txtInicial","value",number_format($dato2->total-$suma,2));
	
	$obj->assign("txtInteresNuevo","value",$nuevointeres);
	$obj->assign("txtFechaNueva","value",$nuevafecha);
	$obj->assign("txtMonedaNueva","value",$nuevamoneda);
	return $obj;
}


function vercuotaspendientes($numero,$idventa){
	require('../negocio/cls_movimiento.php');
	
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

function referenciar($idventa){
	Global $idsucursal;
	Global $carroventa;

	if(isset($_SESSION['carroventa']))
		$carroventa='';
		
	if(isset($_SESSION['IdMovimientoRef']))
		$idmovimientoref=$_SESSION['IdMovimientoRef'];
				
	require('../negocio/cls_movimiento.php');
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->consultardetalleventa($idventa); 
	while($dato=$rst->fetchObject()){
	$carroventa[($dato->idproducto)]=array('idproducto'=>($dato->idproducto),'codigo'=>$dato->codigo,'producto'=>$dato->producto,'cantidad'=>$dato->cantidad,'idunidad'=>$dato->idunidad, 'unidad'=>$dato->unidad, 'precioventa'=>$dato->precioventa, 'precioventaoriginal'=>$dato->precioventa, 'moneda'=>$dato->moneda);
	}
	$_SESSION['carroventa']=$carroventa;
	
	
	$rst3=$objMov->consultarventa(2,$idventa,$idsucursal,NULL,NULL,NULL,NULL,NULL,NULL);
	$detalle = $rst3->fetchObject();
	
	$obj=new xajaxResponse();
	$obj->assign("txtNombresPersona","value",$detalle->cliente.' '.$detalle->acliente);
	$obj->assign("txtIdPersona","value",$detalle->idcliente);
	$obj->assign("txtNombresResponsable","value",$detalle->responsable.' '.$detalle->aresponsable);
	$obj->assign("txtIdResponsable","value",$detalle->idresponsable);
	$obj->assign("cboTipoDocumento","value",$detalle->idtipodocumento);
	$obj->assign("cboMoneda","value",$detalle->moneda);
	if($detalle->numero=='0'){
	$obj->assign("txtReferencia","value",substr($detalle->documento,0,3).'. SIN ENTREGAR');
	}else{
	$obj->assign("txtReferencia","value",substr($detalle->documento,0,3).'. '.$detalle->numero);
	}
	$obj->assign("txtIdMovimientoRef","value",$idventa);
	$obj->assign("txtIdSucursalRef","value",$detalle->idsucursal);
	
	if($detalle->formapago=='A'){
	$registro.="<input  id='txtNroDoc' name='txtNroDoc' type='text' size='15' maxlength='15' value='";
	$year=substr(trim(date('Y-m-d')),0,4);
	$registro.=$objMov->consultar_numero_sigue(2,$detalle->idtipodocumento,$idsucursal); 
	$registro.='-'.$year;
	$registro.="' >";
	$registro=utf8_encode($registro);
	$obj->assign("divNumero","innerHTML",$registro);
	}
	
	if($detalle->idtipodocumento==2){
	$registro2="";
	$registro2.="<div id='divImpuesto'><input name='checkIgv' type='checkbox' onClick='asignar()' value='checkIgv' checked>Incluido IGV</div>";
	$registro2=utf8_encode($registro2);
	$obj->assign("divImpuestoAyax","innerHTML",$registro2);
	}
	$obj->assign("txtImpuesto","value",'2');
	
	$registro3="<button type='button' class='boton' onClick='cambiarmonedadetalle();'>Actualizar</button>";
	$obj->assign("divactualizar","innerHTML",$registro3);
	return $obj;
}

function verificarcaja($fecha, $numero,$idtipodoc){
Global $idsucursal;
Global $fechaproceso;
require('../negocio/cls_movimiento.php');
	$objMov=new clsMovimiento(); 
	$obj=new xajaxResponse();
	$dato=$objMov->consultarventa(2,NULL,$idsucursal,$numero,$idtipodoc,NULL,NULL,NULL,NULL);
	if($dato->rowCount()>0){$alerta="=> Numero de documento incorrecto, numero ya existe.\n";}
	$numero=$objMov->verificarcierrecaja($fechaproceso,$idsucursal);
	if($numero!=1){
		$inicio="Debe tener en cuenta lo siguiente:\n\n";
		$obj->assign("txtCaja","value","no");
		if($numero==2){
		$frase="=> CAJA esta cerrada, NO puede registrar venta al contado!!\n";}
		if($numero==3){
		$frase="=> CAJA aun no ha sido aperturada, \n NO puede registrar venta al contado!!\n";
		}
		$obj->alert($inicio.$frase.$alerta);
	}
		
	
	return $obj;
}

function detallarcuota($idventa){
require('../negocio/cls_movimiento.php');
	
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->consultarcuotaventa($idventa); 
	$numcuota=$rst->rowCount();
	$suma=0;
	$i=1;
	while($dato = $rst->fetchObject()){$suma=$suma+$dato->monto;$i++;}
$rst2=$objMov->consultarventa(2,$idventa,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
$dato2=$rst2->fetchObject();

	$obj=new xajaxResponse();
	if($dato2->formapago=="A"){
	$obj->assign("divFormaPago","innerHTML",utf8_encode("CONTADO"));
	}else{
	$link="<a onClick='vercuotas(".$idventa.")'>VerCuotas</a>";
	
	$obj->assign("divFormaPago","innerHTML","CREDITO");
	$obj->assign("divCuotas","innerHTML",$numcuota);
	$obj->assign("divTextoCuotas","innerHTML",utf8_encode("<strong>CUOTAS:</strong>"));
	$obj->assign("divTextoInicial","innerHTML",utf8_encode("<strong>INICIAL:</strong>"));
	$obj->assign("divInicial","innerHTML",$dato2->total-$suma);
	$obj->assign("divLink","innerHTML",$link);
	}
	return $obj;
}


function verbucarventasguia(){

$registro.="<fieldset><legend><strong>VENTAS PARA DESPACHO:</strong></legend> <table width='500' border='0'>
    <tr width='250'>
      <td>Tipo: <select name='cboFormaPago' id='cboFormaPago'>
	  	<option value='F'>Todos..</option>
        <option value='A'>Contado</option>
        <option value='B'>Credito</option>
      </select>	  </td>
      <td>Nro Factura.: <input name='txtNumero' type='text' id='txtNumero' size='10' maxlength='10'></td>
      </tr>
    <tr width='250'>
      <td > 
	  A partir de: <input name='txtFechaRegistro2' type='text' id='txtFechaRegistro2' size='10' value=''>
	  <button type='button' id='btnCalendar2' class='boton' onClick='vercalendar()'> <img src='../imagenes/b_views.png'> </button>
	  </td>
	  <td><input name = 'BUSCAR' type='button' id='BUSCAR' value = 'BUSCAR' onClick='verparaguias()'> </td>
	  </tr>
  </table><BR><div id='divListaVentas'></div>
  </fieldset>
  ";

$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divBuscarPersona","innerHTML",$registro);
	return $obj;

}

function presentarmotivos(){
require('../negocio/cls_movimiento.php');
$objMov=new clsMovimiento();
$rst=$objMov->consultarmotivotraslado(NULL);
$registro.="<select name='cboMotivoTraslado' id='cboMotivoTraslado'>";
	while($dato=$rst->fetchObject()){
		$registro.="<option value='".$dato->idmotivo."'>".$dato->motivo."</option>";
	}
$registro.="</select>";
$registro=utf8_encode($registro);
$obj=new xajaxResponse();
$obj->assign("divMotivoTraslado","innerHTML",$registro);
	return $obj;
}

function presentardepartamentos(){
Global $idsucursal;
require('../negocio/cls_ubigeo.php');
$objUbi=new clsUbigeo();
$rst=$objUbi->consultardepartamentos(1);

$registro1="<select name='cboDepartamento' id='cboDepartamento' onChange='generarprovincias(1)'>";
$registro2="<select name='cboDepartamento2' id='cboDepartamento2' onChange='generarprovincias(2)'>";
	$registro.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst->fetchObject()){
		$registro.="<option value='".$dato->iddepar."'>".$dato->departamento."</option>";
	}
$registro.="</select>";
$registro1=$registro1.$registro;
$registro2=$registro2.$registro;
$registro1=utf8_encode($registro1);
$registro2=utf8_encode($registro2);
$obj=new xajaxResponse();
$obj->assign("divDepartamento","innerHTML",$registro1);
$obj->assign("divDepartamento2","innerHTML",$registro2);

$ubicacion=$objUbi->consultarporpersona($idsucursal);
$partida=$ubicacion->fetchObject();
	
//provincias

$rst2=$objUbi->consultarprovincias($partida->iddepartamento);
$registro3.="<select name='cboProvincia' id='cboProvincia' onChange='generardistritos(1)'>";
	$registro3.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst2->fetchObject()){
		$registro3.="<option value='".$dato->idprov."'>".$dato->provincia."</option>";
	}
$registro3.="</select>";
$registro3=utf8_encode($registro3);
$obj->assign("divProvincia","innerHTML",$registro3);

//distritos

$rst3=$objUbi->consultardistritos($partida->idprovincia);
$registro4.="<select name='cboDistrito' id='cboDistrito' onChange=''>";
	$registro4.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst3->fetchObject()){
		$registro4.="<option value='".$dato->iddist."'>".$dato->distrito."</option>";
	}
$registro4.="</select>";
$registro4=utf8_encode($registro4);
$obj->assign("divDistrito","innerHTML",$registro4);


$obj->assign("cboDepartamento","value",$partida->iddepartamento);
$obj->assign("cboProvincia","value",$partida->idprovincia);
$obj->assign("cboDistrito","value",$partida->iddistrito);

return $obj;
}

function presentarprovincias($iddepartamento){
Global $idsucursal;
require('../negocio/cls_ubigeo.php');
$objUbi=new clsUbigeo();
$rst=$objUbi->consultarprovincias($iddepartamento);
$registro.="<select name='cboProvincia' id='cboProvincia' onChange='generardistritos(1)'>";
	$registro.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst->fetchObject()){
		$registro.="<option value='".$dato->idprov."'>".$dato->provincia."</option>";
	}
$registro.="</select>";
$registro=utf8_encode($registro);
$obj=new xajaxResponse();
$obj->assign("divProvincia","innerHTML",$registro);

return $obj;
}

function presentarprovincias2($iddepartamento){
require('../negocio/cls_ubigeo.php');
$objUbi=new clsUbigeo();
$rst=$objUbi->consultarprovincias($iddepartamento);
$registro.="<select name='cboProvincia2' id='cboProvincia2' onChange='generardistritos(2)'>";
	$registro.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst->fetchObject()){
		$registro.="<option value='".$dato->idprov."'>".$dato->provincia."</option>";
	}
$registro.="</select>";
$registro=utf8_encode($registro);
$obj=new xajaxResponse();
$obj->assign("divProvincia2","innerHTML",$registro);
	return $obj;
}

function presentardistritos($idprovincia){
require('../negocio/cls_ubigeo.php');
$objUbi=new clsUbigeo();
$rst=$objUbi->consultardistritos($idprovincia);
$registro.="<select name='cboDistrito' id='cboDistrito' onChange=''>";
	$registro.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst->fetchObject()){
		$registro.="<option value='".$dato->iddist."'>".$dato->distrito."</option>";
	}
$registro.="</select>";
$registro=utf8_encode($registro);
$obj=new xajaxResponse();
$obj->assign("divDistrito","innerHTML",$registro);

return $obj;
}

function presentardistritos2($idprovincia){
require('../negocio/cls_ubigeo.php');
$objUbi=new clsUbigeo();
$rst=$objUbi->consultardistritos($idprovincia);
$registro.="<select name='cboDistrito2' id='cboDistrito2' onChange=''>";
	$registro.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst->fetchObject()){
		$registro.="<option value='".$dato->iddist."'>".$dato->distrito."</option>";
	}
$registro.="</select>";
$registro=utf8_encode($registro);
$obj=new xajaxResponse();
$obj->assign("divDistrito2","innerHTML",$registro);
	return $obj;
}

function buscarventasguia($numero,$fechainicio,$formapago){
	if($formapago=='F'){$formapago=NULL;}
	$registro.="<table width='500' border='0' class='registros'>
	<tr>
	<th >Nro Fact.</th>
	<th >Fecha</th>
	<th >Cliente</th>
	<th >Total</th>
	<th >Oper.</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();
	//si consultamos todasd:
	$rst = $objMovimiento->consultarventa(2,NULL,NULL,$numero,2,$fechainicio,NULL,$formapago,'1');
	
	while($dato = $rst->fetchObject()){
	if($dato->numero=="0"){$numero='Sin Ent.';}else{$numero=substr($dato->numero,0,10);}
	
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
		
		if($dato->estado=='A'){
		$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".substr($dato->documento,0,1).'/N.'.$numero."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$numero."$m</td>";
		$estado='Normal';
		}
		
		$registro.="<td align='center'>".$dato->fecha."</td>";
		
		if(strrpos($dato->acliente,' ')>0){
		$apellido=substr($dato->acliente,0,strrpos($dato->acliente,' '));
		}else{
		$apellido=$dato->acliente;
		}
		
	$registro.="<td align='center'>".substr($dato->cliente,0,1).'. '.$apellido."</td>";
	$registro.="<td align='center'>".$dato->total.'/'.$dato->moneda."</td>";
	$registro.="<td width><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' onClick='verdetalle(".$dato->idmovimiento.")'>Ver</a></td>";

	$registro.="<td><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' 		onClick='referenciar(".$dato->idmovimiento.")'>Ref.</a></td>";
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVentas","innerHTML",$registro);
	return $obj;
}

function referenciarguiaremision($idventa){
	Global $idsucursal;
	Global $carroguia;

	if(isset($_SESSION['carroguia']))
		$carroventa='';
	
	require('../negocio/cls_movimiento.php');
	$objMov=new clsMovimiento(); 
	 
	$rst=$objMov->consultardetalleventa($idventa); 
	while($dato=$rst->fetchObject()){
	$carroguia[($dato->idproducto)]=array('idproducto'=>($dato->idproducto),'codigo'=>$dato->codigo,'producto'=>$dato->producto,'cantidad'=>$dato->cantidad,'idunidad'=>$dato->idunidad, 'unidad'=>$dato->unidad, 'unidadpeso'=>$dato->unidadpeso, 'peso'=>$dato->peso, 'categoria'=>$dato->categoria, 'marca'=>$dato->marca, 'idunidadpeso'=>$dato->idunidadpeso,'precioventa'=>$dato->precioventa, 'moneda'=>$dato->moneda);
	}
	$_SESSION['carroguia']=$carroguia;
	
	
	$rst3=$objMov->consultarventa(2,$idventa,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
	$detalle = $rst3->fetchObject();
	
	$obj=new xajaxResponse();
	$obj->assign("txtNombresPersona","value",$detalle->cliente.' '.substr($detalle->acliente,0,strrpos($detalle->acliente,' ')));
	$obj->assign("txtIdPersona","value",$detalle->idcliente);
	$obj->assign("divtxtDocumento","innerHTML",$detalle->documento);
	$obj->assign("txtMoneda","value",$detalle->moneda);
	if($detalle->numero=='0'){
	$obj->assign("divtxtNumeroDocumento","innerHTML",'Sin Entregar');
	}else{
	$obj->assign("divtxtNumeroDocumento","innerHTML",$detalle->numero);
	}
	$obj->assign("txtTipoVenta","value",$detalle->formapago);
	
	$obj->assign("txtIdMovimientoRef","value",$idventa);
	$obj->assign("txtIdSucursalRef","value",$detalle->idsucursal);
	
	require('../negocio/cls_ubigeo.php');
	$objUbi=new clsUbigeo();
	$ubicacion=$objUbi->consultarporpersona($idsucursal);
	$partida=$ubicacion->fetchObject();
	$obj->assign("txtDireccionPartida","value",$partida->direccion);
	
	
	$ubicacion2=$objUbi->consultarporpersona($detalle->idresponsable);
	$destino=$ubicacion2->fetchObject();
	$obj->assign("txtDireccionLlegada","value",$destino->direccion);
		
		
	$obj->assign("cboDepartamento2","value",$destino->iddepartamento);
	
	//creando provincias destino
$rst22=$objUbi->consultarprovincias($destino->iddepartamento);
$registro33.="<select name='cboProvincia2' id='cboProvincia2' onChange='generardistritos(2)'>";
	$registro33.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst22->fetchObject()){
		$registro33.="<option value='".$dato->idprov."'>".$dato->provincia."</option>";
	}
$registro32.="</select>";
$registro33=utf8_encode($registro33);
$obj->assign("divProvincia2","innerHTML",$registro33);

	//creando distritos destino
$rst33=$objUbi->consultardistritos($destino->idprovincia);
$registro44.="<select name='cboDistrito2' id='cboDistrito2' onChange=''>";
	$registro44.="<option value='0'>Seleccionar uno</option>";
	while($dato=$rst33->fetchObject()){
		$registro44.="<option value='".$dato->iddist."'>".$dato->distrito."</option>";
	}
$registro44.="</select>";
$registro44=utf8_encode($registro44);
$obj->assign("divDistrito2","innerHTML",$registro44);

	//asignamos valores de ubigeo
	
	$obj->assign("cboProvincia2","value",$destino->idprovincia);
	$obj->assign("cboDistrito2","value",$destino->iddistrito);	
	
	$registros.="<table class=registros width='100%' border=1>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Peso</th>
	<th>Unidad Peso </th>	
	<th>Peso Equivalente</th>
	";
$kg=0;
$gr=0;
foreach($carroguia as $k => $v){
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".$v["cantidad"]."</td>";
		$registros.="<td align='right'>".number_format($v["peso"],2)."</td>";
		$registros.="<td align='right'>".$v["unidadpeso"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["peso"],2)."</td>";
		if($v["idunidadpeso"]==5){
		$kg=$kg+($v["cantidad"]*$v["peso"]);
		}
		if($v["idunidadpeso"]==4){
		$gr=$gr+($v["cantidad"]*$v["peso"]);
		}
		
	}
	
	if($gr>=1000){
		$a=strpos($gr/1000,'.');
		if($a>0){
		$kg=$kg+substr($gr/1000,0,$a);
		}else{
		$kg=$kg+($gr/1000);
		}
	$gr=$gr%1000;}
	$registros.="</table><BR>";
	$registros.="<center><table border=0> <th>Total KG: </th><td><input type='text' name='txtTotalKg' id='txtTotalKg' value='".number_format($kg,2)."' readonly='' ></td><th>Total GR: </th><td><input type='text' name='txtTotalGr' id='txtTotalGr' value='".number_format($gr,2)."' readonly=''></td> </table></center>";
	$registros=utf8_encode($registros);
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}

function generarnumeroguia($documento,$fecha){
Global $idsucursal;
$registro.="<input  id='txtNroDoc' name='txtNroDoc' type='text' size='15' maxlength='15' value='";

	require('../negocio/cls_movimiento.php');
	
	$year=substr(trim($fecha),0,4);
	 
	$objMov=new clsMovimiento(); 
	 
	$registro.=$objMov->consultar_numero_sigue(2,$documento,$idsucursal); 
	$registro.='-'.$year;
	$registro.="' >";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();

	if(isset($_SESSION['IdUsuario'])){
	  Global $ObjPersona;
	  $rs = $ObjPersona->buscarXId($_SESSION['IdUsuario']);	
	  $reg= $rs->fetchObject();
	  $obj->assign('txtIdResponsable','value',$reg->IdPersona);
	  $obj->assign('txtNombresResponsable','value',utf8_encode($reg->Nombres));
	} 
	
	$obj->assign("divNumero","innerHTML",$registro);
	return $obj;
}


function listadotransportista($campo,$frase){
	$registro.="<table width='500' border='0 class='registros''>
	<tr>
	<th >Trasnportista</th>
	<th >DNI/RUC</th>
	<th >Chofer</th>
	</tr>";

	require("../negocio/cls_transportista.php");
	$objTrans = new clsTransportista();
		$rst = $objTrans->consultarajax(NULL,$campo, $frase);
	while($dato = $rst->fetchObject()){
		
		$registro.="<tr><td>".$dato->transportista."</td>";
		$registro.="<td align='center'>".$dato->documento."</td>";
		$registro.="<td align='center'>".$dato->chofer."</td>";

	$registro.="<td><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' 		onClick='mostrarTransportista(".$dato->id.")'>REF</a></td>
	<td><a style='color:#0033FF' style='text-decoration:underline' style='cursor:pointer' 		onClick=\"window.open('mant_transportista.php?IdTransportista=".$dato->id."&accion=ACTUALIZAR&origen=DOC','_blank','width=380,height=480');\">VER</a></td></tr>";
	}
	$registro.="</table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divregistrosPersona","innerHTML",$registro);
	return $obj;
}

function mostrartransportista($id){
 require("../negocio/cls_transportista.php");
 $objTrans = new clsTransportista();
$rst = $objTrans->consultarajax($id,NULL, NULL);	
  $reg= $rst->fetchObject();
  $objResp=new xajaxResponse();
  $objResp->assign('txtIdTransportista','value',utf8_encode($reg->id));
  $objResp->assign('txtTransportista','value',utf8_encode($reg->transportista));
  $objResp->assign('divdireccionT','innerHTML',utf8_encode($reg->direccion));
  $objResp->assign('divchoferT','innerHTML',utf8_encode($reg->chofer));
  $objResp->assign('divmarcavehiculoT','innerHTML',utf8_encode($reg->marcavehiculo));
  $objResp->assign('divbreveteT','innerHTML',utf8_encode($reg->licenciabrevete));
  $objResp->assign('divplacaT','innerHTML',utf8_encode($reg->numeroplaca));
  $objResp->assign('divdocumentoT','innerHTML',utf8_encode($reg->documento));
  $objResp->assign('divconstanciaT','innerHTML',utf8_encode($reg->numeroconstancia));

  return $objResp;
}

function generarguias($numero,$fechainicio,$fechafin){
	Global $idsucursal;
	Global $idusuario;
	
	$registro.="<table width='1031' class='registros'>
	<tr>
	<th width='74'>DOC.</th>
	<th width='129'>N&deg;</th>
	<th width='129'>N&deg; FACTURA</th>
	<th width='144'>FECHA</th>
	<th width='106'>CLIENTE</th>
	<th width='144'>RESPONSABLE</th>
	<th width='76'>TOTAL KG</th>
	<th width='76'>TOTAL GR</th>
	<th width='76'>COMENTARIO</th>
	<th width='144'>ESTADO</th>
	<th colspan='3'>OPERACIONES</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$rst = $objMovimiento->consultarventa(2,NULL,$idsucursal,$numero,5,$fechainicio,$fechafin,NULL, NULL);

	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
		
		if($dato->estado=='A'){
		$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".$dato->documento."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$dato->documento."$m</td>";
		$estado='Normal';
		}
		$registro.="<td align='center'>".$dato->numero."</td>";
	$rst2=$objMovimiento->consultarventa(2,$dato->idmovimientoref,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
	$dato2=$rst2->fetchObject();
	if($dato2->numero=='0'){
	$registro.="<td align='center'>Sin entregar</td>";
	}else{
	$registro.="<td align='center'>".$dato2->numero."</td>";
	}
		$registro.="<td align='center'>".$dato->fecha."</td>";
    $registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	$registro.="<td align='center'>".$dato->subtotal."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td>".$dato->comentario."</td>";
		}
	$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td ><a href='frm_detalleguia.php?IdVenta=".$dato->idmovimiento."'>Ver detalle Guia </a></td>";
	$registro.="<td><a href='frm_Impdetalleguia.php?IdVenta=".$dato->idmovimiento."'>Ver Documento</a></td>";
	$registro.="<td><a href='frm_comprobanteF.php?idventa=".$dato2->idmovimiento."&T=".$dato2->total."&M=".$dato2->moneda."'>Ver Factura</a></td>";
/*	if($estado!="Anulado"){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=ANULAR&IdVenta=".$dato->idmovimiento."&IdUsuario=".$idusuario."&IdSucursal=".$idsucursal."'>Anular</a>";
	}else{
	$registro.="<td> - ";
	}
	$registro.="    </td>";*/
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVenta","innerHTML",$registro);
	return $obj;
}

function generarguiasinicio(){
	Global $idsucursal;
	Global $idusuario;
	Global $fechaproceso;
	$registro.="<table width='1031' class='registros'>
	<tr>
	<th width='74'>DOC.</th>
	<th width='129'>N&deg;</th>
	<th width='129'>N&deg; FACTURA</th>
	<th width='144'>FECHA</th>
	<th width='106'>CLIENTE</th>
	<th width='144'>RESPONSABLE</th>
	<th width='76'>TOTAL KG</th>
	<th width='76'>TOTAL GR</th>
	<th width='76'>COMENTARIO</th>
	<th width='144'>ESTADO</th>
	<th colspan='3'>OPERACIONES</th>
	</tr>";

	require("../negocio/cls_movimiento.php");
	$objMovimiento = new clsMovimiento();

	$m=substr($fechaproceso,5,2);
	$m=$m-1;
	$m=substr('00',0,2-strlen($m)).$m;
	$rst = $objMovimiento->consultarventa(2,NULL,$idsucursal,NULL,5,date("Y-".$m."-d"),$fechaproceso,NULL,NULL);

	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
		
		if($dato->estado=='A'){$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td>".$dato->documento."</td>";
		}else{
		$registro.="<tr class='$estilo'><td>".$dato->documento."</td>";
		$estado='Normal';
		}
		$registro.="<td align='center'>".$dato->numero."</td>";
		$rst2=$objMovimiento->consultarventa(2,$dato->idmovimientoref,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
	$dato2=$rst2->fetchObject();
	if($dato2->numero=='0'){
	$registro.="<td align='center'>Sin entregar</td>";
	}else{
	$registro.="<td align='center'>".$dato2->numero."</td>";
	}
		
		$registro.="<td align='center'>".$dato->fecha."</td>";
	
	$registro.="<td align='center'>".$dato->cliente.' '.$dato->acliente."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	$registro.="<td align='center'>".$dato->subtotal."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td>".$dato->comentario."</td>";
		}
	$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td ><a href='frm_detalleguia.php?IdVenta=".$dato->idmovimiento."'>Ver detalle Guia </a></td>";
	$registro.="<td><a href='frm_Impdetalleguia.php?IdVenta=".$dato->idmovimiento."'>Ver Documento</a></td>";
	$registro.="<td><a href='frm_comprobanteF.php?idventa=".$dato2->idmovimiento."&T=".$dato2->total."&M=".$dato2->moneda."'>Ver Factura</a></td>";
	/*if($estado!="Anulado"){
	$registro.="<td><a href='../negocio/cont_venta.php?accion=ANULAR&IdVenta=".$dato->idmovimiento."&IdUsuario=".$idusuario."&IdSucursal=".$idsucursal."'>Anular</a>";
	}else{
	$registro.="<td> - ";
	}
	$registro.="</td>";*/
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaVenta","innerHTML",$registro);
	return $obj;
}

$flistadotransportista = & $xajax-> registerFunction('listadotransportista');
$flistadotransportista->setParameter(0,XAJAX_INPUT_VALUE,'campoPersona');
$flistadotransportista->setParameter(1,XAJAX_INPUT_VALUE,'frasePersona');

$fgenerarnumeroguia = & $xajax-> registerFunction('generarnumeroguia');
$fgenerarnumeroguia->setParameter(0,XAJAX_INPUT_VALUE,'cboTipoDocumento');
$fgenerarnumeroguia->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro');

$fgenerarnumero = & $xajax-> registerFunction('generarnumero');
$fgenerarnumero->setParameter(0,XAJAX_INPUT_VALUE,'cboTipoDocumento');
$fgenerarnumero->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro');

$xajax->registerFunction('mostrartransportista');
$xajax->registerFunction('mostrarPersona');
$xajax->registerFunction('verbuscarpersona');
$xajax->registerFunction('mostrarEmpleado');
$xajax->registerFunction('presentarinicio');
$xajax->registerFunction('presentarmotivos');
$xajax->registerFunction('presentardepartamentos');

$xajax->registerFunction('generarguiasinicio');

$fpresentarprovincias = & $xajax->registerFunction('presentarprovincias');
$fpresentarprovincias->setParameter(0,XAJAX_INPUT_VALUE,'cboDepartamento');

$fpresentardistritos = & $xajax->registerFunction('presentardistritos');
$fpresentardistritos->setParameter(0,XAJAX_INPUT_VALUE,'cboProvincia');

$fpresentarprovincias2 = & $xajax->registerFunction('presentarprovincias2');
$fpresentarprovincias2->setParameter(0,XAJAX_INPUT_VALUE,'cboDepartamento2');

$fpresentardistritos2 = & $xajax->registerFunction('presentardistritos2');
$fpresentardistritos2->setParameter(0,XAJAX_INPUT_VALUE,'cboProvincia2');

$xajax->registerFunction('referenciarguiaremision');
$xajax->registerFunction('verbucarventasguia');
$fbucarventasguia = & $xajax->registerFunction('buscarventasguia');
$fbucarventasguia->setParameter(0,XAJAX_INPUT_VALUE,'txtNumero');
$fbucarventasguia->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fbucarventasguia->setParameter(2,XAJAX_INPUT_VALUE,'cboFormaPago');

$xajax->registerFunction('vercuotaspendientes');

$fverificarcaja = & $xajax->registerFunction('verificarcaja');
$fverificarcaja->setParameter(0,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fverificarcaja->setParameter(1,XAJAX_INPUT_VALUE,'txtNroDoc');
$fverificarcaja->setParameter(2,XAJAX_INPUT_VALUE,'cboTipoDocumento');


$flistadopersona = & $xajax-> registerFunction('listadopersona');
$flistadopersona->setParameter(0,XAJAX_INPUT_VALUE,'campoPersona');
$flistadopersona->setParameter(1,XAJAX_INPUT_VALUE,'frasePersona');
$flistadopersona->setParameter(3,XAJAX_INPUT_VALUE,'TotalRegPersona');
$flistadopersona->setParameter(4,XAJAX_INPUT_VALUE,'txtOrigenPersona');

$fcambiarmonedadetalle = & $xajax-> registerFunction('cambiarmonedadetalle');
$fcambiarmonedadetalle->setParameter(0,XAJAX_INPUT_VALUE,'cboMoneda');
$fcambiarmonedadetalle->setParameter(1,XAJAX_INPUT_VALUE,'txtImpuesto');
$fcambiarmonedadetalle->setParameter(2,XAJAX_INPUT_VALUE,'cboTipoDocumento');

$fcambiaStock = & $xajax->registerFunction("cambiaStock");
$fcambiaStock->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fcambiaStock->setParameter(1,XAJAX_INPUT_VALUE,'cboUnidad');
$fcambiaStock->setParameter(2,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->registerFunction("genera_cboUnidad");

$xajax->registerFunction("referenciar");

$flistado = & $xajax-> registerFunction('listado');
$flistado->setParameter(0,XAJAX_INPUT_VALUE,'cboCategoria');
$flistado->setParameter(1,XAJAX_INPUT_VALUE,'campo');
$flistado->setParameter(2,XAJAX_INPUT_VALUE,'frase');
$flistado->setParameter(3,XAJAX_INPUT_VALUE,'Pag');
$flistado->setParameter(4,XAJAX_INPUT_VALUE,'TotalReg');
$flistado->setParameter(5,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->registerFunction("quitar");

$fgenerarventas = & $xajax->registerFunction("generarventas");
$fgenerarventas->setParameter(0,XAJAX_INPUT_VALUE,'txtNumero');
$fgenerarventas->setParameter(1,XAJAX_INPUT_VALUE,'cboTipoDoc');
$fgenerarventas->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerarventas->setParameter(3,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgenerarventas->setParameter(4,XAJAX_INPUT_VALUE,'cboFormaPago');
$fgenerarventas->setParameter(5,XAJAX_INPUT_VALUE,'cboVer');

$fgenerarventasalmacen = & $xajax->registerFunction("generarventasalamcen");
$fgenerarventasalmacen->setParameter(0,XAJAX_INPUT_VALUE,'txtNumero');
$fgenerarventasalmacen->setParameter(1,XAJAX_INPUT_VALUE,'cboTipoDoc');
$fgenerarventasalmacen->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerarventasalmacen->setParameter(3,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgenerarventasalmacen->setParameter(4,XAJAX_INPUT_VALUE,'cboFormaPago');
$fgenerarventasalmacen->setParameter(5,XAJAX_INPUT_VALUE,'cboVer');

$fgenerarguias = & $xajax->registerFunction("generarguias");
$fgenerarguias->setParameter(0,XAJAX_INPUT_VALUE,'txtNumero');
$fgenerarguias->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerarguias->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro2');


$freferenciarventas = & $xajax->registerFunction("referenciarventas");
$freferenciarventas->setParameter(0,XAJAX_INPUT_VALUE,'txtNumero');
$freferenciarventas->setParameter(1,XAJAX_INPUT_VALUE,'cboTipoDoc');
$freferenciarventas->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$freferenciarventas->setParameter(3,XAJAX_INPUT_VALUE,'cboFormaPago');

$fgenerarcuotas=& $xajax->registerFunction("generarcuotas");
$fgenerarcuotas->setParameter(0,XAJAX_INPUT_VALUE,'Contador');
$fgenerarcuotas->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaVenta');
$fgenerarcuotas->setParameter(2,XAJAX_INPUT_VALUE,'txtTotal');
$fgenerarcuotas->setParameter(3,XAJAX_INPUT_VALUE,'txtInicial');
$fgenerarcuotas->setParameter(4,XAJAX_INPUT_VALUE,'txtInteres');

$xajax->registerFunction("vercuotas");

$xajax->registerFunction("generarventasinicio");
$xajax->registerFunction("generarventasalmaceninicio");

$xajax->registerFunction("referenciarventas2");

$xajax->registerFunction("agregardetallepedido");

$fagregar = & $xajax-> registerFunction('agregar');
$fagregar->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fagregar->setParameter(1,XAJAX_INPUT_VALUE,'cboUnidad');
$fagregar->setParameter(2,XAJAX_INPUT_VALUE,'txtCantidad');
$fagregar->setParameter(3,XAJAX_INPUT_VALUE,'txtPrecioVenta');
$fagregar->setParameter(4,XAJAX_INPUT_VALUE,'txtDetalle');
$fagregar->setParameter(5,XAJAX_INPUT_VALUE,'txtStockActual');
$fagregar->setParameter(6,XAJAX_INPUT_VALUE,'cboMoneda');
$fagregar->setParameter(7,XAJAX_INPUT_VALUE,'txtImpuesto');
$fagregar->setParameter(8,XAJAX_INPUT_VALUE,'cboTipoDocumento');
$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>