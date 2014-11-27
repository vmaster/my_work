<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
  header("location: ../presentacion/login.php?error=1");
}

if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else {$idsucursal=1;}
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores

require("../datos/cado.php");
require("../negocio/cls_producto.php");
require("../negocio/cls_persona.php");
require("../negocio/cls_detallemovalmacen.php");
$ObjPersona = new clsPersona();
$EncabezadoTabla=array("C&oacute;digo","Descripci&oacute;n","Categoria","Marca","Unidad","Precio Venta","Precio Compra","Stock","Armario","Columna","Fila","Peso","Medida");
$objProducto = new clsProducto();
$objDetalleMovAlmacen = new clsDetalleMovAlmacen();
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
	$rs = $objProducto->buscarconxajax1($categoria,$campo,$frase,$limite,$idsucursal,$moneda,$tipocambio);
    $nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $objProducto->buscarconxajax1($categoria,$campo,$frase,$limite,$idsucursal,$moneda,$tipocambio);
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
	   if($reg[10]<=$reg[16]){$rojo="red";}
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr class='$estilo' style='color:$rojo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				$RegistroEdicion="<td><a href='#agregar' onClick='seleccionar(".$reg[$i].",&quot;".$moneda."&quot;)'>Seleccionar</a></td>";
			}elseif($i==8 or $i==9){
				$registros.="<td align='right'>".$reg[$i]."</td>";
			}elseif($i==11 or $i==15){
				$registros.="<td align='center'>".$reg[$i]."</td>";
			}elseif($i==5 or $i == 6 or $i == 16){
				$registros.= "";
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
	$consulta = $ObjListaUnidad->buscarconxajax($idsucursal,$idproducto,$moneda,$tipocambio);

	$Unidads="<table><tr><td><select name='cboUnidad' id='cboUnidad' onchange='cambiaStock()'>";
	while($registro=$consulta->fetchObject()){
		if($registro->idunidad==$registro->idunidadbase){ 
			$seleccionar="Selected";
			$precioventa=$registro->precioventa;
			$producto=$registro->producto;
			$StockActual=$registro->StockActual;
			$precioespecial=$registro->precioventaespecial;
			$preciocompra=$registro->preciocompra;
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
	$obj->assign("txtPrecioEspecial","value",$precioespecial);
	$obj->assign("txtPrecioCompra","value",$preciocompra);
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

function agregar($idproducto,$idunidad,$cantidad,$precioventa,$txtDetalle,$StockActual,$moneda,$precioespecial,$preciocompra){
	Global $carroAlmacen;
	Global $objProducto;
	
	if($precioventa>=0 and isset($precioventa) and isset($cantidad) and $precioventa<>'' and $cantidad<>'' and $precioespecial>=0 and $preciocompra>=0 and $precioespecial<>'' and $preciocompra<>'' and isset($precioespecial) and isset($preciocompra)){
		
	if($txtDetalle=='FALSE')$_SESSION['carroAlmacen']='';
	if(isset($_SESSION['carroAlmacen']))
		$carroAlmacen=$_SESSION['carroAlmacen'];
		
	$rs = $objProducto->buscarxidproductoyidunidad($idproducto,$idunidad);	
    $reg=$rs->fetchObject();	
		
	$carroAlmacen[($idproducto)]=array('idproducto'=>($idproducto),'codigo'=>$reg->codigo,'producto'=>$reg->producto,'cantidad'=>$cantidad,'idunidad'=>$idunidad, 'unidad'=>$reg->unidad, 'precioventa'=>$precioventa,'precioventaoriginal'=>$precioventa ,'precioespecial'=>$precioespecial, 'preciocompra'=>$preciocompra,'moneda'=>$moneda);

	$_SESSION['carroAlmacen']=$carroAlmacen;
	
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
	foreach($carroAlmacen as $k => $v){
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["precioventa"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$registros.="</table><div><center>Total: <input type='text' name='txtTotal' id='txtTotal' readonly='true' value='".number_format($suma,2)."' /></center></div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("txtDetalle","value",'TRUE');
	}else{
		if($precioventa<0 or $precioventa==''){
			$mensaje="El precio de venta no puede ser menor a cero!!!";
		}/*else{
			$mensaje="La cantidad no puede ser menor o igual a cero ni mayor al stock actual!!!";}*/
	$obj=new xajaxResponse();
	$obj->alert($mensaje);
	}
	
	return $obj;
}

function quitar($idproducto){
	Global $carroAlmacen;
	if(isset($_SESSION['carroAlmacen']))
		$carroAlmacen=$_SESSION['carroAlmacen'];
		
	unset($carroAlmacen[($idproducto)]);

	$_SESSION['carroAlmacen']=$carroAlmacen;
	
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
	foreach($carroAlmacen as $k => $v){
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["precioventa"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$registros.="</table><div><center> Total: <input type='text' readonly=true name='txtTotal' value='".number_format($suma,2)."'</center></div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}

function cambiarmonedadetalle($moneda){
	Global $carroAlmacen;
	Global $tipocambio;
	if(isset($_SESSION['carroAlmacen']))
		$carroAlmacen=$_SESSION['carroAlmacen'];
		
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
	foreach($carroAlmacen as $k => $v){
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"],2)."</td>";
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
	$registros.="</table><div><center>Total : ".number_format($suma,2)."</center></div>";
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
	   if($cont%2) $estilo="par";else $estilo="impar";
	   
	   $registros.= "<tr class='$estilo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				//$registros.= "<td>".$reg[$i]."</td>";
				//DEPENDIENDO DEL ORIGEN SE LLAMA A LA FUNCION CORRESPONDIENTE
				if($origen=='0'){
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

function generaNroDoc($documento,$fecha){
Global $idsucursal;
$registro.="<input  id='txtNroDoc' name='txtNroDoc' type='text' size='15' maxlength='15' value='";

	require_once('../negocio/cls_Movimiento.php');
	
	$year=substr(trim($fecha),0,4);
	 
	$objMov=new clsMovimiento(); 
	 
	$registro.=$objMov->consultar_numero_sigue(4,$documento,$idsucursal); 
	$registro.='-'.$year;
	$registro.="' >";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divNumero","innerHTML",$registro);
	return $obj;

}


function presentarinicio(){
	$registros.="<table class=registros width='100%'>
	<th>C&oacute;digo</th>
	<th>Producto</th>
	<th>Unidad</th>
	<th>Cantidad</th>
	<th>Precio Ofertado</th>	
	<th>SubTotal</th>
	</table>
	<div><center>
	Total :<input type='text' name='txtTotal' id='txtTotal' value='0.00' readonly='true'>
	</center>
	</div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	return $obj;
}

function agregar_detalles($idmov){
	Global $carroAlmacen;
	Global $objDetalleMovAlmacen;

	if(isset($_SESSION['carroAlmacen']))
		$carroAlmacen=NULL;
unset($carroAlmacen[($reg->idproducto)]);				
	
  	
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
	$rs = $objDetalleMovAlmacen->consultar($idmov);	
	while($reg=$rs->fetchObject()){		
		
	$carroAlmacen[($reg->idproducto)]=array('idproducto'=>($reg->idproducto),'codigo'=>($reg->codpro),'producto'=>($reg->producto),'cantidad'=>($reg->cantidad),'idunidad'=>($reg->idunidad), 'unidad'=>($reg->unidad), 'precioventa'=>($reg->precioventa), 'precioventaoriginal'=>($reg->precioventa),'precioespecial'=>($reg->precioventaespecial), 'preciocompra'=>($reg->preciocompra),'moneda'=>($reg->moneda));

	$_SESSION['carroAlmacen']=$carroAlmacen;}
	
	foreach($carroAlmacen as $k => $v){
		$subto=$v['cantidad']*$v['precioventa'];
		$suma=$suma+$subto;
		$contador++;
		$registros.="<tr><td>".$v["codigo"]."</td>";
		$registros.="<td>".$v["producto"]."</td>";
		$registros.="<td>".$v["unidad"]."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["precioventa"],2)."</td>";
		$registros.="<td align='right'>".number_format($v["cantidad"]*$v["precioventa"],2)."</td>";
		$registros.="<td><a href='#' onClick='quitar(".$v["idproducto"].");'>Quitar</a></td></tr>";
	}
	$registros.="</table><div><center>Total: <input type='text' name='txtTotal' id='txtTotal' readonly='true' value='".number_format($suma,2)."' /></center></div>";
	$registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("txtDetalle","value",'TRUE');
	return $obj;
	
		}

function generaralmacen($numero,$idtipodoc,$fechainicio,$fechafin,$estado){
	
	Global $idsucursal;
	$registro.="<table width='1216' class=registros>
    <tr>
      <th><div align='center'>TIPO DOC</div></th>
	  <th><div align='center'>NRO DOC</div></th>
      <th><div align='center'>FECHA</div></th>
      <th><div align='center'>PERSONA</div></th>
      <th><div align='center'>RESPONSABLE</div></th>
      <th><div align='center'>TOTAL</div></th>
      <!-- <th><div align='center'>MONEDA</div></th> -->
      <th><div align='center'>COMENTARIO</div></th>
      <!-- <th><div align='center'>ESTADO</div></th> -->
      <th colspan='3'><div align='center'>OPERACIONES</div></th>
    </tr>";
	require("../datos/cado.php");
	require("../negocio/cls_movimiento.php");	
	date_default_timezone_set('America/Bogota');
	$objMovimiento = new clsMovimiento();
	$rst = $objMovimiento->consultaralmacen(NULL,$idsucursal,$numero,$idtipodoc,$fechainicio,$fechafin,$estado);
	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";

		if($dato->estado=='A'){$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td align='center'>".$dato->tipodoc."</td>";
		}else{
		$registro.="<tr class='$estilo'><td align='center'>".$dato->tipodoc."</td>";
		$estado='Normal';
		}

	$registro.="<td align='center'>".$dato->numero."</td>";
	$registro.="<td align='center'>".$dato->fecha."</td>";
	$registro.="<td align='center'>".$dato->persona.' '.$dato->apersona."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	//$registro.="<td align='center'>".$dato->moneda."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td align='center'>".$dato->comentario."</td>";
		}
	//$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td width='54' align='center'><a href='list_detallemovalmacen.php?IdMov=".$dato->idmov."&IdTipoDoc=".$dato->idtipodoc."'>Ver Detalles</a></td>";

	if($estado!="Anulado"){
	$registro.="<td width='63' align='center'> <a href='../negocio/cont_almacen.php?accion=ANULAR&IdMov=$dato->idmov&IdTipoDoc=$dato->idtipodoc'>Anular</a></td>";
	}else{
	$registro.="<td width='63' align='center'> <a href='frm_almacen.php?accion=ACTUALIZAR&IdMov=".$dato->idmov."'>Modificar</a></td>";
	}
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaAlmacen","innerHTML",$registro);
	return $obj;
}


function generaralmaceninicio(){
	Global $idsucursal;
	
	$registro.="<table width='1216' class=registros>
    <tr>
      <th><div align='center'>TIPO DOC</div></th>
	  <th><div align='center'>NRO DOC</div></th>
      <th><div align='center'>FECHA</div></th>
      <th><div align='center'>PERSONA</div></th>
      <th><div align='center'>RESPONSABLE</div></th>
      <th><div align='center'>TOTAL</div></th>
      <!-- <th><div align='center'>MONEDA</div></th> -->
      <th><div align='center'>COMENTARIO</div></th>
      <!-- <th><div align='center'>ESTADO</div></th> -->
      <th colspan='3'><div align='center'>OPERACIONES</div></th>
    </tr>";
	require("../datos/cado.php");
	require("../negocio/cls_movimiento.php");	
	date_default_timezone_set('America/Bogota');
	$objMovimiento = new clsMovimiento();

	$m=date('m');
	$m=$m-1;
	$m=substr('00',0,2-strlen($m)).$m;
	$rst = $objMovimiento->consultaralmacen(NULL,$idsucursal,NULL,0,date("Y-".$m."-d"),date("Y-m-d"),0);
	while($dato = $rst->fetchObject()){
		$cont++;
        if($cont%2) $estilo="par"; else $estilo="impar";
	
		if($dato->estado=='A'){$estado='Anulado';
		$registro.="<tr style='color:#FF0000' class='$estilo'><td align='center'>".$dato->tipodoc."</td>";
		}else{
		$registro.="<tr class='$estilo'><td align='center'>".$dato->tipodoc."</td>";
		$estado='Normal';
		}

	$registro.="<td align='center'>".$dato->numero."</td>";
	
	$registro.="<td align='center'>".$dato->fecha."</td>";
	$registro.="<td align='center'>".$dato->persona.' '.$dato->apersona."</td>";
	$registro.="<td align='center'>".$dato->responsable.' '.$dato->aresponsable."</td>";
	$registro.="<td align='center'>".$dato->total."</td>";
	//$registro.="<td align='center'>".$dato->moneda."</td>";
		if($dato->comentario==""){
		$registro.="<td align='center'>".'-'."</td>";
		}else{
		$registro.="<td align='center'>".$dato->comentario."</td>";
		}
	//$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td width='54' align='center'><a href='list_detallemovalmacen.php?IdMov=".$dato->idmov."&IdTipoDoc=".$dato->idtipodoc."'>Ver Detalles</a></td>";

	if($estado!="Anulado"){
	$registro.="<td width='63' align='center'> <a href='../negocio/cont_almacen.php?accion=ANULAR&IdMov=$dato->idmov&IdTipoDoc=$dato->idtipodoc'>Anular</a></td>";
	}else{
	$registro.="<td width='63' align='center'> <a href='frm_almacen.php?accion=ACTUALIZAR&IdMov=".$dato->idmov."'>Modificar</a></td>";
	}
	}
	$registro.="</tr></table>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divListaAlmacen","innerHTML",$registro);
	return $obj;
}
function completarCajaUsuario($idUsuario){
require('../negocio/cls_usuario.php');
$objUser = new clsUsuario();
$us=$objUser->buscar($idUsuario,NULL);
$usuario=$us->fetchObject();
$nombrecompleto=$usuario->apellidos." ". $usuario->nombres;

$nombrecompleto=utf8_encode($nombrecompleto);
$obj=new xajaxResponse();
$obj->assign("txtNombresResponsable","value",$nombrecompleto);
return $obj;
}


$xajax->registerFunction('mostrarPersona');
$xajax->registerFunction('mostrarEmpleado');

$flistadopersona = & $xajax-> registerFunction('listadopersona');
$flistadopersona->setParameter(0,XAJAX_INPUT_VALUE,'campoPersona');
$flistadopersona->setParameter(1,XAJAX_INPUT_VALUE,'frasePersona');
$flistadopersona->setParameter(3,XAJAX_INPUT_VALUE,'TotalRegPersona');
$flistadopersona->setParameter(4,XAJAX_INPUT_VALUE,'txtOrigenPersona');

$fcambiarmonedadetalle = & $xajax-> registerFunction('cambiarmonedadetalle');
$fcambiarmonedadetalle->setParameter(0,XAJAX_INPUT_VALUE,'cboMoneda');

$fcambiaStock = & $xajax->registerFunction("cambiaStock");
$fcambiaStock->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fcambiaStock->setParameter(1,XAJAX_INPUT_VALUE,'cboUnidad');

$xajax->registerFunction("genera_cboUnidad");
$xajax->registerFunction('presentarinicio');

$xajax->registerFunction('agregar_detalles');


$fgeneraNroDoc = & $xajax->registerFunction("generaNroDoc");
$fgeneraNroDoc->setParameter(0,XAJAX_INPUT_VALUE,'cboTipoDocumento');
$fgeneraNroDoc->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro');


$flistado = & $xajax-> registerFunction('listado');
$flistado->setParameter(0,XAJAX_INPUT_VALUE,'cboCategoria');
$flistado->setParameter(1,XAJAX_INPUT_VALUE,'campo');
$flistado->setParameter(2,XAJAX_INPUT_VALUE,'frase');
$flistado->setParameter(3,XAJAX_INPUT_VALUE,'Pag');
$flistado->setParameter(4,XAJAX_INPUT_VALUE,'TotalReg');
$flistado->setParameter(5,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->registerFunction("quitar");
$fagregar = & $xajax-> registerFunction('agregar');
$fagregar->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fagregar->setParameter(1,XAJAX_INPUT_VALUE,'cboUnidad');
$fagregar->setParameter(2,XAJAX_INPUT_VALUE,'txtCantidad');
$fagregar->setParameter(3,XAJAX_INPUT_VALUE,'txtPrecioVenta');
$fagregar->setParameter(4,XAJAX_INPUT_VALUE,'txtDetalle');
$fagregar->setParameter(5,XAJAX_INPUT_VALUE,'txtStockActual');
$fagregar->setParameter(6,XAJAX_INPUT_VALUE,'cboMoneda');
$fagregar->setParameter(7,XAJAX_INPUT_VALUE,'txtPrecioEspecial');
$fagregar->setParameter(8,XAJAX_INPUT_VALUE,'txtPrecioCompra');

$xajax->registerFunction("generaralmaceninicio");
$fcompletarcajausuario = & $xajax-> registerFunction('completarCajaUsuario');
$fcompletarcajausuario->setParameter(0,XAJAX_INPUT_VALUE,'txtIdResponsable');

$fgeneraralmacen = & $xajax->registerFunction("generaralmacen");
$fgeneraralmacen->setParameter(0,XAJAX_INPUT_VALUE,'txtNumero');
$fgeneraralmacen->setParameter(1,XAJAX_INPUT_VALUE,'cboTipoDoc');
$fgeneraralmacen->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgeneraralmacen->setParameter(3,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgeneraralmacen->setParameter(4,XAJAX_INPUT_VALUE,'cboEstado');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>