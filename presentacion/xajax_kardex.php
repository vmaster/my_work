<?php 
session_start();
/*if(!isset($_SESSION['Usuario']))
{
  header("location: ../presentacion/login.php?error=1");
}*/
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else {$idsucursal=1;}
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores
require("../datos/cado.php");
require("../negocio/cls_producto.php");
$objProducto = new clsProducto();
// require("../negocio/cls_tipocambio.php");
$EncabezadoTabla=array("C&oacute;digo","Descripci&oacute;n","Categoria","Marca","Unidad","Precio Venta","Precio Compra","Stock","Armario","Columna","Fila","Peso","Medida");

function generareporte($idproducto,$fechainicio,$fechafin,$moneda,$categoria){
 require("../negocio/cls_kardex.php");
	
		$objKardex = new clsKardex();
	$encabezado.="
    <tr>
  	  <th rowspan='2'><div align='center'>FECHA</div></th>
      <th rowspan='2'><div align='center'>MOVIMIENTO</div></th>
	  <th rowspan='2'><div align='center'>DOCUMENTO</div></th>
      <th rowspan='2'><div align='center'>ESTADO</div></th>
	  <th colspan='3'><div align='center'>INGRESOS</div></th>
	  <th colspan='3'><div align='center'>SALIDAS</div></th>
      <th colspan='3'><div align='center'>SALDO</div></th></tr>
	  <tr> <th><div align='center'>CANTIDAD</div></th>		         
	  <th><div align='center'>PRECIO UNITARIO</div></th>
      <th><div align='center'>TOTAL</div></th>
      <th><div align='center'>CANTIDAD</div></th>
	  <th><div align='center'>PRECIO UNITARIO</div></th>
	  <th><div align='center'>TOTAL</div></th>
	     <th><div align='center'>CANTIDAD</div></th>
	  <th><div align='center'>PRECIO UNITARIO</div></th>
	  <th><div align='center'>TOTAL</div></th>
      
    </tr>";
	
	date_default_timezone_set('America/Bogota');

$cont=0;   
$objKardex = new clsKardex();
$rst = $objKardex->consultarkardex($idproducto,$fechainicio,$fechafin,$moneda,$categoria);
while($dato = $rst->fetchObject()){
	if($cont==0){
		$cont++;
		$ProdActual=$dato->codigo;
		$registro.="<table class='tablaint'>";
		$registro.="<th>CODIGO:</th><td>".$dato->codigo." </td><br>";
		$registro.="<th>PRODUCTO:</th><td>".$dato->pro." </td>";
		$registro.="</table><br>";
		$registro.="<table class='tablaint'>".$encabezado;
	}
	if ($ProdActual!=$dato->codigo){
		$ProdActual=$dato->codigo;
		$registro.="</table><br>";
		$registro.="<table class='tablaint'>";
		$registro.="<th>CODIGO:</th><td>".$dato->codigo." </td><br>";
		$registro.="<th>PRODUCTO:</th><td>".$dato->pro." </td>";
		$registro.="</table><br>";
		$registro.="<table class='tablaint'>".$encabezado;
		}
	$registro.="<tr>";
		
 	if($moneda=='D')
	$mon='$ ';
	else
	$mon='S/. ';
	
	
	if(($dato->idtipomovimiento==1 and $dato->estado=='N') or ($dato->idtipodocumento==10 and $dato->estado=='N') or ($dato->idtipodocumento==11 and $dato->estado=='A') or  ($dato->idtipomovimiento==2 and $dato->estado=='A')){
	$ingreso=$dato->cantidad;
	$egreso='0.00';
	$preciocompra=$dato->preciocompra;
	$precioventa='0.00';
	$precio=$dato->preciocompra;
	}elseif(($dato->idtipomovimiento==1 and $dato->estado=='A') or ($dato->idtipodocumento==10 and $dato->estado=='A') or ($dato->idtipodocumento==11 and $dato->estado=='N') or  ($dato->idtipomovimiento==2 and $dato->estado=='N')){
	$egreso=$dato->cantidad;
	$ingreso='0.00';
	$precioventa=$dato->precioventa;
	$preciocompra='0.00';
	$precio=$dato->precioventa;
	}
	if($dato->numero==0){
	$mov="Sin Entregar";
	}else $mov=$dato->numero;
	if($dato->mov=='ALMACEN'){
	$movim= $dato->mov.' '.$dato->tipodoc;
	}else
	$movim=$dato->mov;
	if($dato->estado=='N')
	$estado='NORMAL';
	else
	$estado='ANULADO';
	
	if($dato->moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}
	$cont++;
	if($cont%2) $estilo="par"; else $estilo="impar";
	$registro.="<tr class='$estilo'><td align='center'>".$dato->fecha."</td>";
	$registro.="<td align='center'>".$dato->tipodoc.' '.$mov."</td>";
	$registro.="<td align='center'>".$movim."</td>";
		$registro.="<td align='center'>".$estado."</td>";
	$registro.="<td align='center'>".$egreso."</td>";
	$registro.="<td align='center'>".$mon.$precioventa."</td>";
	$registro.="<td align='center'>".$mon.number_format($egreso*$precioventa,2)."</td>"; 
	$registro.="<td align='center'>".$ingreso."</td>";
	$registro.="<td align='center'>".$mon.$preciocompra."</td>";
	$registro.="<td align='center'>".$mon.number_format($ingreso*$preciocompra,2)."</td>";
	$registro.="<td align='center'>".$dato->saldoactual."</td>";
	$registro.="<td align='center'>".$mon.$precio."</td>";
	$registro.="<td align='center'>".$mon.number_format($dato->saldoactual*$precio,2)."</td>";
	$registro.="<td align='center'></td></tr>";
	}
	$registro.="</table>";
	
	$registro.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_kardexvalorizado.php?idpro=$idproducto&inicio=$fechainicio&final=$fechafin&moneda=$moneda&categoria=$categoria','_blank')>";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divReporte","innerHTML",$registro);
	return $obj;
}

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
    $cadena="Registros encontrados: $nr de $nr1";
    $registros="
	<table class=registros width='100%' border=1>
    <tr>";
	for($i=0;$i<count($EncabezadoTabla);$i++){
	$registros.="<th>".$EncabezadoTabla[$i]."</th>";
	}
	$registros.="<th>Selecionar</th></tr>";
	$cont=0;
    while($reg=$rs->fetch()){
	   $cont++;
	   $rojo="";
	   if($reg[8]<=0){$rojo="red";}
	   if($cont%2) $estilo="par";
	   else $estilo="impar";
	   $registros.= "<tr class='$estilo' style='color:$rojo'>";
	   for($i=0;$i<$CantCampos;$i++){
	   		if($i==0){
				$RegistroEdicion="<td><a href='#' onClick='seleccionar(".$reg[$i].",&quot;".$moneda."&quot;)'>Seleccionar</a></td>";
			}elseif($i==6 or $i==7){
				$registros.="<td align='right'>".$reg[$i]."</td>";
			}elseif($i==9 or $i==13){
				$registros.="<td align='center'>".$reg[$i]."</td>";
			}
			else{
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
	$obj->assign("frase","value",$producto);
		$obj->assign("txtIdProductoSeleccionado","value",$idproducto);
	return $obj;
}

$fgenerareporte = & $xajax->registerFunction("generareporte");
$fgenerareporte->setParameter(0,XAJAX_INPUT_VALUE,'txtIdProductoSeleccionado');
$fgenerareporte->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaRegistro');
$fgenerareporte->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaRegistro2');
$fgenerareporte->setParameter(3,XAJAX_INPUT_VALUE,'cboMoneda');
$fgenerareporte->setParameter(4,XAJAX_INPUT_VALUE,'cboCategoria');

$flistado = & $xajax-> registerFunction('listado');
$flistado->setParameter(0,XAJAX_INPUT_VALUE,'cboCategoria');
$flistado->setParameter(1,XAJAX_INPUT_VALUE,'campo');
$flistado->setParameter(2,XAJAX_INPUT_VALUE,'frase');
$flistado->setParameter(3,XAJAX_INPUT_VALUE,'Pag');
$flistado->setParameter(4,XAJAX_INPUT_VALUE,'TotalReg');
$flistado->setParameter(5,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->registerFunction("genera_cboUnidad");

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";

?>