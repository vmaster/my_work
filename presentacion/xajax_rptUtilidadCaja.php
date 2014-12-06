<?php
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores

require("../datos/cado.php");
require("../negocio/cls_movcaja.php");


function rptproyeccion($tipomov,$tipodoc,$fechaI,$fechaF,$moneda){

if($fechaI<=$fechaF){
$FI=$fechaI;
$FF=$fechaF;
}else{
$FI=$fechaF;
$FF=$fechaI;
}



		$ObjMov = new clsMovCaja();		
		$consulta= $ObjMov->rptproyeccion($tipomov,$tipodoc,$FI,$FF,$moneda);
		$num_rows=$consulta->rowCount();
		
		if($num_rows==0){
		
		$tabla="<fieldset>
                <legend><strong>REPORTE:</strong></legend>NO EXISTEN REPORTES CON ESTA CONSULTA...!</fieldset>";
		
		}else{
		
		$tabla="<fieldset>
                <legend><strong>REPORTE:</strong></legend>
		<table width='1480'  class=registros>
        <tr>
		  <th width='100' align='center'>TIPO MOV.</th>
		  <th width='200' align='center'>PERSONA</th>
          <th width='100' align='center'>DOC</th>
          <th width='140' align='center'>N&ordm; DOC</th>
          <th width='100' align='center'>FECHA</th>
		  <th width='100' align='center'>FECHA PROX PAGO</th>
		  <th width='80' align='center'>MONEDA</th>
          <th width='110' align='center'>MONTO TOTAL</th>
		  <th width='110' align='center'>INICIAL</th>
		  <th width='110' align='center'>MONTO PAGADO</th>
		  <th width='110' align='center'>INTERES PAGADO</th>
		  <th width='110' align='center'>SALDO MONTO</th>
		  <th width='110' align='center'>SALDO INTERES</th>
		  
		  
        </tr>";
		
		$totalmontopagarS=0;
		$totalinterespagarS=0;
		$totalmontopagarD=0;
		$totalinterespagarD=0;
		$totalmontocobrarS=0;
		$totalinterescobrarS=0;
		$totalmontocobrarD=0;
		$totalinterescobrarD=0;
		
        while($registro=$consulta->fetch()){
			if($cont%2) $estilo="par";else $estilo="impar";
	 $cont++;
		$tabla.="<tr class='$estilo'>";
		$tabla.="<td align='center'>".$registro[7]."</td>";
		$tabla.="<td align='center'>".$registro[6]."</td>";
        $tabla.="<td align='center'>".$registro[1]."</td>";
        $tabla.="<td align='center'>".$registro[2]."</td>";
        $tabla.="<td align='center'>".$registro[3]."</td>";
		$tabla.="<td align='center'>".$ObjMov->fecha_prox_pago($registro[0])."</td>";
		$tabla.="<td align='center'>".$registro[5]."</td>";
        $tabla.="<td align='center'>".$registro[4]."</td>";
		$tabla.="<td align='center'>".number_format(($registro[4]-($ObjMov->monto_pagado($registro[0])+$ObjMov->saldo_monto($registro[0]))),2)."</td>";
		$tabla.="<td align='center'>".number_format((($ObjMov->monto_pagado($registro[0]))+(($registro[4]-($ObjMov->monto_pagado($registro[0])+$ObjMov->saldo_monto($registro[0]))))),2)."</td>";
		$tabla.="<td align='center'>".$ObjMov->interes_pagado($registro[0])."</td>";
		$tabla.="<td align='center'>".$ObjMov->saldo_monto($registro[0])."</td>";
		$tabla.="<td align='center'>".$ObjMov->saldo_interes($registro[0])."</td>";
        $tabla.="</tr>";
		
		if($registro[10]==1 && $registro[5]=='S'){
		
		$totalmontopagarS=$totalmontopagarS+$ObjMov->saldo_monto($registro[0]);
		$totalinterespagarS=$totalinterespagarS+$ObjMov->saldo_interes($registro[0]);
		
		}elseif($registro[10]==1 && $registro[5]=='D'){
		
		$totalmontopagarD=$totalmontopagarD+$ObjMov->saldo_monto($registro[0]);
		$totalinterespagarD=$totalinterespagarD+$ObjMov->saldo_interes($registro[0]);
		
		}elseif($registro[10]==2 && $registro[5]=='S'){
		
		$totalmontocobrarS=$totalmontocobrarS+$ObjMov->saldo_monto($registro[0]);
		$totalinterescobrarS=$totalinterescobrarS+$ObjMov->saldo_interes($registro[0]);
		
		}elseif($registro[10]==2 && $registro[5]=='D'){
		
		$totalmontocobrarD=$totalmontocobrarD+$ObjMov->saldo_monto($registro[0]);
		$totalinterescobrarD=$totalinterescobrarD+$ObjMov->saldo_interes($registro[0]);
		
		}
		}
		
      	$tabla.="</table>";
		
		$tabla.="<table width='490' class=registros align='center'>
            <tr>
              <td width='150'>&nbsp;</td>
              <th width='170' align='center'>TOTAL SOLES </th>
              <th width='170' align='center'>TOTAL DOLARES </th>
            </tr>";
			
		if($tipomov==0){
			
   		$tabla.="<tr>
              <th align='right'>Monto por Pagar:</th>
              <td align='center' class='par'>S/. ".number_format($totalmontopagarS,2)."</td>
              <td align='center' class='par'>$. ".number_format($totalmontopagarD,2)."</td>
            </tr>
            <tr>
              <th align='right'>Interes por Pagar:</th>
              <td align='center' class='impar'>S/. ".number_format($totalinterespagarS,2)."</td>
              <td align='center' class='impar'>$. ".number_format($totalinterespagarD,2)."</td>
            </tr>";
			
     	$tabla.="<tr>
              <th align='right'> Monto por Cobrar:</th>
              <td align='center' class='par'>S/. ".number_format($totalmontocobrarS,2)."</td>
              <td align='center' class='par'>$. ".number_format($totalmontocobrarD,2)."</td>
            </tr>
            <tr>
              <th align='right'>Interes por Cobrar:</th>
              <td align='center' class='impar'>S/. ".number_format($totalinterescobrarS,2)."</td>
              <td align='center' class='impar'>$. ".number_format($totalinterescobrarD,2)."</td>
            </tr>";
		}elseif($tipomov==1){
		 $tabla.="<tr>
              <th align='right'>Monto por Pagar:</th>
              <td align='center' class='par'>S/. ".number_format($totalmontopagarS,2)."</td>
              <td align='center' class='par'>$. ".number_format($totalmontopagarD,2)."</td>
            </tr>
            <tr>
              <th align='right'>Interes por Pagar:</th>
              <td align='center' class='impar'>S/. ".number_format($totalinterespagarS,2)."</td>
              <td align='center' class='impar'>$. ".number_format($totalinterespagarD,2)."</td>
            </tr>";
		
		
		
		}elseif($tipomov==2){
		
			$tabla.="<tr>
              <th align='right'> Monto por Cobrar:</th>
              <td align='center' class='par'>S/. ".number_format($totalmontocobrarS,2)."</td>
              <td align='center' class='par'>$. ".number_format($totalmontocobrarD,2)."</td>
            </tr>
            <tr>
              <th align='right'>Interes por Cobrar:</th>
              <td align='center' class='impar'>S/. ".number_format($totalinterescobrarS,2)."</td>
              <td align='center' class='impar'>$. ".number_format($totalinterescobrarD,2)."</td>
            </tr>";
			
		}
				
         $tabla.="</table>";
		
		$tabla.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_proyeccionCP.php?tipomov=$tipomov&tipodoc=$tipodoc&fechaI=$fechaI&fechaF=$fechaF&moneda=$moneda','_blank')></fieldset>";
		}
	  $tabla=utf8_encode($tabla);
		$objResp = new xajaxResponse();
		$objResp->assign('divreporte','innerHTML',$tabla);
		return $objResp;	


}



function listadocombo($tipo){

if($tipo==1){

	$combo="<select name='cboDoc' id='cboDoc'>
			<option value='0'>TODOS</option>
            <option value='3'>BOLETA COMPRA</option>
            <option value='4'>FACTURA COMPRA</option>
          </select>";
		  
	$objResp = new xajaxResponse();
	$objResp->assign('divtipodoc','innerHTML',$combo);
	return $objResp;
	
}elseif($tipo==2){

$combo2="<select name='cboDoc' id='cboDoc'>
			<option value='0'>TODOS</option>
            <option value='1'>BOLETA VENTA</option>
            <option value='2'>FACTURA VENTA</option>
          </select>";
	$combo2=utf8_encode($combo2);
	$objResp2 = new xajaxResponse();
	$objResp2->assign('divtipodoc','innerHTML',$combo2);
	return $objResp2;	

}elseif($tipo==0){

$combo2="<select name='cboDoc' id='cboDoc'>
            <option value='0'>TODOS</option>
          </select>";
	$combo2=utf8_encode($combo2);
	$objResp2 = new xajaxResponse();
	$objResp2->assign('divtipodoc','innerHTML',$combo2);
	return $objResp2;	

}


	
}






function rptyear($idtipodocumento,$yearinicio,$yearfin,$idconceptopago,$idrolpersona,$moneda){
	if($yearinicio<=$yearfin){
		$num=$yearfin-$yearinicio;
		$yearI=$yearinicio;
		$yearF=$yearfin;
	}else{
		$num=$yearinicio-$yearfin;
		$yearI=$yearfin;
		$yearF=$yearinicio;
	}

	$tabla="<fieldset>
			<legend><strong>REPORTE:</strong></legend>
			<table width='1150' class=registros>
			<tr>
			<th align='center'>AÑO</th>
			<th align='center'>VENTAS POR AÑO</th>
			<th align='center'>COMPRAS POR AÑO</th>
	  <th align='center'>UTILIDAD POR AÑO</th>
			</tr>";
	$Ob = new clsMovCaja();

	$IS=0;
	$ID=0;
	$ES=0;
	$ED=0;

	for($i=$yearI;$i<=$yearF;$i++){
		$cont++;
		if($cont%2) $estilo="par"; else $estilo="impar";

		$ISano=$Ob->reporteutilidadyear(1,$idrolpersona,$idconceptopago,'S','VENTA','N',$i,$_SESSION['IdSucursal'],$year);

		$registro = $ISano->fetch();
		$tabla.="<tr class='$estilo'>";
		$tabla.="<td align='center'>".$i."</td>";
		$tabla.="<td align='center'>".number_format($registro[0],2)."</td>";
		$tabla.="<td align='center'>".number_format($registro[1],2)."</td>";
		$tabla.="<td align='center'>".(number_format($registro[0],2) - number_format($registro[1],2))."</td>";
		$tabla.="</tr>";

		$IS=$IS+$ISano;
		$ID=$ID+$IDano;
		$ES=$ES+$ESano;
		$ED=$ED+$EDano;
		$datos1[$i]=$ISano;
		$datos2[$i]=$ESano;
	}

	$tabla.="</table></fieldset>";

	$tabla.="<center><a href=\"#\" onClick=javascript:window.open('pdf_utilidadRptYear.php?idtipodoc=$idtipodocumento&idrolpersona=$idrolpersona&yearI=$yearinicio&yearF=$yearfin&idconceptopago=$idconceptopago&moneda=$moneda','_blank')><img src=\"../imagenes/print_f2.png\" with=20 height=20>&nbsp;Imprimir</a>&nbsp;";
	$_SESSION['data1']=$datos1;
	$_SESSION['data2']=$datos2;
		
	$tabla=utf8_encode($tabla);
	$obj=new xajaxResponse();
	$obj->assign("divresultadosreporte","innerHTML",$tabla);
	return $obj;

}



function rptmes($idtipodocumento,$mesinicio,$mesfin,$idconceptopago,$idrolpersona,$moneda,$year){
	if($mesinicio<=$mesfin){
		$num=$mesfin-$mesinicio;
		$mesI=$mesinicio;
		$mesF=$mesfin;
	}else{
		$num=$mesinicio-$mesfin;
		$mesI=$mesfin;
		$mesF=$mesinicio;
	}


	$tabla="<fieldset>
			<legend><strong>REPORTE:</strong></legend>
			<table width='1250' class=registros>
			<tr>
			<th align='center'>MES</th>
			<th align='center'>VENTAS POR MES (S/.)</th>
	  <th align='center'>COMPRAS POR MES (S/.)</th>
			<th align='center'>UTILIDAD POR MES </th>
			</tr>";
	$Ob = new clsMovCaja();

	$IS=0;
	$ID=0;
	$ES=0;
	$ED=0;

	for($i=$mesI;$i<=$mesF;$i++){
		$cont++;
		if($cont%2) $estilo="par"; else $estilo="impar";
		$ISmes=$Ob->reporteutilidadmes(1,$idrolpersona,$idconceptopago,'S','VENTA','N',$i,$_SESSION['IdSucursal'],$year);

		$tabla.="<tr class='$estilo'>";
		$tabla.="<td align='center'>".$Ob->nombremes($i)."</td>";
		$registro = $ISmes->fetch();
		$tabla.="<td align='center'>".number_format($registro[0],2)."</td>";
		$tabla.="<td align='center'>".number_format($registro[1],2)."</td>";

		$tabla.="<td align='center'>".number_format((number_format($registro[0],2)-number_format($registro[1],2)),2)."</td>";
		$tabla.="</tr>";

		$IS=$IS+$ISmes;
		$ID=$ID+$IDmes;
		$ES=$ES+$ESmes;
		$ED=$ED+$EDmes;
		$datos1[$i]=$ISmes;
		$datos2[$i]=$ESmes;
	}


	$tabla.="</table></fieldset>";

	$tabla.="<center><a href=\"#\" onClick=javascript:window.open('pdf_utilidadRptMes.php?idtipodoc=$idtipodocumento&idrolpersona=$idrolpersona&mesI=$mesinicio&mesF=$mesfin&idconceptopago=$idconceptopago&moneda=$moneda&year=$year','_blank')><img src=\"../imagenes/print_f2.png\" with=20 height=20>&nbsp;Imprimir</a>&nbsp;";
	$_SESSION['data1']=$datos1;
	$_SESSION['data2']=$datos2;
		
	$tabla=utf8_encode($tabla);
	$obj=new xajaxResponse();
	$obj->assign("divresultadosreporte","innerHTML",$tabla);
	return $obj;

}




function rptdia($idtipodocumento,$fech,$idconceptopago,$idrolpersona,$moneda){
		
if($moneda=='0'){

		$Ob = new clsMovCaja();		
		$cons= $Ob-> buscarmovimientosrpt(
		$idtipodocumento,$idrolpersona,$idconceptopago,'S','VENTA','N',$fech,$_SESSION['IdSucursal']);
		$num=$cons->rowCount();

	if($num==0){
		
		$tabla="<b style = 'display:none'>MONEDA: SOLES</b><p>No existen Reportes..!</p>";
		
	}else{
		$tabla="<fieldset>
                <legend><strong>REPORTE:</strong></legend>
				<b style = 'display:none'>MONEDA: SOLES</b>
				<table width='1300' class=registros>
                <tr>
                  <th width='150' align='center'>Nro DOC.</th>
                  <th width='120' align='center'>TOTAL P.VENTA</th>
                  <th width='120' align='center' style = 'display:none'>EGRESO</th>
				  <th width='120' align='center'>TOTAL P.COMPRA</th>
                  <th width='320' align='center'>CONCEPTO PAGO</th>
				  <th width='320' align='center'>PERSONA</th>
                  <th width='200' align='center'>COMENTARIO</th>
                </tr>";
        $suma=0;
        $suma_precio_compra=0;         
		while($registro=$cons->fetch()){
		$cont++;
	   	if($cont%2) $estilo="par"; else $estilo="impar";
		$tabla.="<tr class='$estilo'>";			
        $tabla.="<td align='center'>".$registro[0]."</td>";
		
		$suma=$suma+$registro[3];
		$tabla.="<td align='center'>".number_format($registro[3],2)."</td>";
        $tabla.="<td align='center' style = 'display:none'>0.00</td>";
		$tabla.="<td align='center'>";
		$ObjMovCaja = new clsMovCaja();
		$cons_precio_compra = $ObjMovCaja->consultarDetPrecCompPorMovimiento($registro[8]);
		$precio_compra = $cons_precio_compra->fetch();
		$tabla.=number_format($precio_compra[0],2)."</td>";
		$tabla.="<td align='center'>".$registro[6]."</td>";
        $tabla.="<td align='center'>".$registro[4]." ".$registro[5]."</td>";
        $tabla.="<td align='center'>".$registro[7]."</td>";
		$tabla.="</tr>";
		$suma_precio_compra= $suma_precio_compra + $precio_compra[0];  
		}
				
         $tabla.=" </table>";
		 $tabla.="<p></p>";
		 $tabla.="<p align='center'>SUMA PRECIO DE VENTA S/. ".number_format($suma,2)."</p>";
		 $tabla.="<p align='center'>SUMA PRECIO DE COMPRA S/.". number_format($suma_precio_compra,2)."</p>";
		 $tabla.="<p align='center'>UTILIDAD S/.". (number_format($suma,2) - number_format($suma_precio_compra,2))."</p>";
	}


		$tabla.="<input name='txtImprimir' type='button' id='IMPRIMIR' value='IMPRIMIR' onClick=javascript:window.open('pdf_utilidadRptDia.php?idtipodoc=$idtipodocumento&idrolpersona=$idrolpersona&fech=$fech&idconceptopago=$idconceptopago&moneda=$moneda','_blank')></fieldset>";


	
}
	
	$tabla=utf8_encode($tabla);
	$obj=new xajaxResponse();
	$obj->assign("divresultadosreporte","innerHTML",$tabla);
	return $obj;

}

$frptproyeccion = & $xajax-> registerFunction('rptproyeccion');
$frptproyeccion ->setParameter(0,XAJAX_INPUT_VALUE,'cboTipoMov');
$frptproyeccion ->setParameter(1,XAJAX_INPUT_VALUE,'cboDoc');
$frptproyeccion ->setParameter(2,XAJAX_INPUT_VALUE,'txtFechaInicial');
$frptproyeccion ->setParameter(3,XAJAX_INPUT_VALUE,'txtFechaFinal');
$frptproyeccion ->setParameter(4,XAJAX_INPUT_VALUE,'cboMoneda');



$flistadocombo= & $xajax-> registerFunction('listadocombo');
$flistadocombo->setParameter(0,XAJAX_INPUT_VALUE,'cboTipoMov');


$frptyear = & $xajax-> registerFunction('rptyear');
$frptyear->setParameter(0,XAJAX_INPUT_VALUE,'cboTipo');
$frptyear->setParameter(1,XAJAX_INPUT_VALUE,'cboYearInicio');
$frptyear->setParameter(2,XAJAX_INPUT_VALUE,'cboYearFin');
$frptyear->setParameter(3,XAJAX_INPUT_VALUE,'cboConceptoPago');
$frptyear->setParameter(4,XAJAX_INPUT_VALUE,'cboRolPersona');
$frptyear->setParameter(5,XAJAX_INPUT_VALUE,'cboMoneda');

$frptmes = & $xajax-> registerFunction('rptmes');
$frptmes->setParameter(0,XAJAX_INPUT_VALUE,'cboTipo');
$frptmes->setParameter(1,XAJAX_INPUT_VALUE,'cboMesInicio');
$frptmes->setParameter(2,XAJAX_INPUT_VALUE,'cboMesFin');
$frptmes->setParameter(3,XAJAX_INPUT_VALUE,'cboConceptoPago');
$frptmes->setParameter(4,XAJAX_INPUT_VALUE,'cboRolPersona');
$frptmes->setParameter(5,XAJAX_INPUT_VALUE,'cboMoneda');
$frptmes->setParameter(6,XAJAX_INPUT_VALUE,'cboYear');

$frptdia = & $xajax-> registerFunction('rptdia');
$frptdia ->setParameter(0,XAJAX_INPUT_VALUE,'cboTipo');
$frptdia ->setParameter(1,XAJAX_INPUT_VALUE,'txtFechaDia');
$frptdia ->setParameter(2,XAJAX_INPUT_VALUE,'cboConceptoPago');
$frptdia ->setParameter(3,XAJAX_INPUT_VALUE,'cboRolPersona');
$frptdia ->setParameter(4,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>