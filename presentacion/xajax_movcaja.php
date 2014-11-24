<?php
session_start();
require('../xajax/xajax_core/xajax.inc.php');
$xajax= new xajax();
$xajax->configure('javascript URI','../xajax/');
//$xajax->configure('debug', true);//ver errores

require("../datos/cado.php");
require("../negocio/cls_movcaja.php");
require("../negocio/cls_persona.php");

$ObjPersona = new clsPersona();

function genera_cboConceptoPago($seleccionado)
{
	$Obj = new clsMovCaja();
	$consulta = $Obj->consultarconceptopago();

	$combo="<select name='cboConceptoPago' id='cboConceptoPago'>";
	$combo.="<option value='0'>TODOS</option>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		$combo.="<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	$combo.="</select>";
	return $combo;
}

function genera_cboRolPersona($seleccionado)
{
	
	$Obj = new clsMovCaja();
	$consulta = $Obj->cargarcomborolpersona();

	$combo="<select name='cboRolPersona' id='cboRolPersona'>";
	$combo.="<option value='0'>TODOS</option>";
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		$combo.="<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}
	$combo.="</select>";
	return $combo;
}

function generarbusqueda()
{

$tabla="<table width='871' height='99' border='0' cellpadding='0' cellspacing='0'>
    <tr>
      <td width='411'><fieldset>
      <legend><strong>B&Uacute;SQUEDAS DEL DIA:</strong></legend>
      <table width='388' border='0' cellspacing='0' cellpadding='0'>
        <tr>
          <td width='187' class='Estilo3'><div align='right'>TIPO DOCUMENTO: </div></td>
          <td width='201'><select name='cboDoc' id='cboDoc'>
              <option value='0'>TODOS</option>
              <option value='10'>INGRESO</option>
              <option value='11'>EGRESO</option>
          </select></td>
          </tr>
        <tr>
          <td class='Estilo3'><div align='right'>ROL PERSONA:</div></td>
          <td>".genera_cboRolPersona(0)."</td>
        </tr>
        <tr>
          <td height='28' class='Estilo3'><div align='right'>CONCEPTO DE PAGO: </div></td>
          <td>".genera_cboConceptoPago(0)."</td>
        </tr>
        <tr>
          <td class='Estilo3'><div align='right'>MONEDA:</div></td>
          <td><select name='cboMoneda' id='cboMoneda'>
              <option value='0'>TODAS</option>
              <option value='S'>SOLES</option>
              <option value='D'>DOLARES</option>
          </select></td>
        </tr>
        <tr>
          <td height='37' colspan='2' class='Estilo3'><div align='center' id='divbotonbuscar'><button type='button' class='boton' onClick='mostrarbusqueda()'>BUSCAR</button><button type='button' class='boton' onClick='cerrar()'>CANCELAR</button> </div></td>
        </tr>
      </table>
      </fieldset>
      <p></p></td>
      <td width='460'>&nbsp;</td>
    </tr>
  </table>
  <div id='divbusqueda'></div>
  ";

	$tabla=utf8_encode($tabla);
	$obj=new xajaxResponse();
	$obj->assign("divgenerarbusqueda","innerHTML",$tabla);
	return $obj;
}



function presentarbusquedamov($idtipodocumento,$idrolpersona,$idconceptopago,$moneda)
{

		$Ob = new clsMovCaja();		
		$cons= $Ob->buscarmovimientos(
$idtipodocumento,$idrolpersona,$idconceptopago,$moneda,'CAJA','N',$_SESSION['FechaProceso'],$_SESSION['IdSucursal']);
		$num=$cons->rowCount();

if($num==0){
		
		$tabla="<fieldset><legend><strong>RESULTADO DE LA B&Uacute;SQUEDA:</strong></legend>No existen movimientos con esa consulta...!</fieldset>";
		
}else{
$tabla="<fieldset>
                <legend><strong>RESULTADO DE LA B&Uacute;SQUEDA:</strong></legend>
				<table align='center' class=registros width='100%'>
                <tr>
                  <th>Nro DOC.</th>
                  <th>TIPO DOC.</th>
                  <th>MONEDA</th>
                  <th>TOTAL</th>
                  <th>PERSONA</th>
                  <th>CONCEPTO PAGO</th>
                  <th>COMENTARIO</th>
                </tr>";
                
		while($registro=$cons->fetch()){
			$cont++;
		   if($cont%2) $estilo="par";else $estilo="impar";
		$tabla.="<tr class='$estilo'>";			
        $tabla.="<td align='center'>".$registro[0]."</td>";
        $tabla.="<td align='center'>".$registro[1]."</td>";
        $tabla.="<td align='center'>".$registro[2]."</td>";
        $tabla.="<td align='center'>".$registro[3]."</td>";
        $tabla.="<td align='center'>".$registro[4]." ".$registro[5]."</td>";
        $tabla.="<td align='center'>".$registro[6]."</td>";
        $tabla.="<td align='center'>".$registro[7]."</td>";
		$tabla.="</tr>";
		}
				
         $tabla.=" </table></fieldset>";
		  
}
	
	
	
	$tabla=utf8_encode($tabla);
	$obj=new xajaxResponse();
	$obj->assign("divbusqueda","innerHTML",$tabla);
	return $obj;
}


function generarnumero($documento,$fecha){
$registro.="<input  id='txtNroDoc' name='txtNroDoc' type='text' size='15' maxlength='15' value='";

	require('../negocio/cls_movimiento.php');
	
	$year=substr(trim($fecha),0,4);
	 
	$objMov=new clsMovimiento(); 
	 
	$registro.=$objMov->consultar_numero_sigue(3,$documento,$_SESSION['IdSucursal']); 
	$registro.='-'.$year;
	$registro.="' readonly='' >";
	$registro=utf8_encode($registro);
	$obj=new xajaxResponse();
	$obj->assign("divNumero","innerHTML",$registro);
	return $obj;
}

function presentarmovimientos()
{
$tabla="<table class='registros' width='100%'>
    <tr>
      <th align='center'>Nro DOC.</th>
      <th align='center'>MONEDA</th>
      <th align='center'>INGRESO</th>
      <th align='center'>EGRESO</th>
      <th align='center'>INGRESO S/.</th>
      <th align='center'>EGRESO S/.</th>
      <th align='center'>CONCEPTO PAGO</th>
	  <th align='center'>PERSONA</th>
      <th align='center'>COMENTARIO</th>
	  <th colspan='2' align='center'>OPERACIONES</th>
    </tr>";
	
$objMov3 = new clsMovCaja();
$rst3 = $objMov3->consultar();
while($dato = $rst3->fetchObject())
{
	$cont++;
	if($cont%2) $estilo="par";else $estilo="impar";
   $tabla.="<tr class='$estilo'>
      <td align='center'>".$dato->tipo." / ".$dato->numero."</td>
      <td align='center'>".$dato->moneda."</td>";
   
	  if($dato->tipo=='I')
	  { 
	  	  
	$tabla.= "<td align='center'>".$dato->total."</td>
	  <td align='center'>0.00</td>";
	 
	  }elseif($dato->tipo=='E'){
	 
	  $tabla.="<td align='center'>0.00</td>
      <td align='center'>".$dato->total."</td>";
	 }
	 
  if($dato->tipo=='I'){
				  $tabla.="<td align='center'>";
			  if($dato->moneda=='D')
			  {
			   $tabla.=number_format($dato->total*$_SESSION['TipoCambio'],2);
			  } elseif($dato->moneda=='S')
			  {
			  $tabla.=$dato->total;
			  }
				  
				 $tabla.="</td>
				  <td align='center'>0.00</td>";
	  
  }elseif($dato->tipo=='E'){
			   $tabla.="<td align='center'>0.00</td>
			  <td align='center'>";
				if($dato->moneda=='D')
				{
				$tabla.=number_format($dato->total*$_SESSION['TipoCambio'],2);
				}
				elseif($dato->moneda=='S')
				{
				$tabla.=$dato->total;
				} 
			  $tabla.="</td>";
			  
 }
	  
       $tabla.="<td align='center'>".$dato->descripcion."</td>";
	   $tabla.="<td align='center'>".$dato->nombres." ".$dato->apellidos."</td>";
       $tabla.="<td align='center'>".$dato->comentario."</td>";
	   
	   
	   $cierre = $objMov3->consultarcierre($_SESSION['FechaProceso']);
	   
	   if($cierre==0){
	   
			   if($dato->idconceptopago!='1' && $dato->idconceptopago!='2' && $dato->idmovimientoref!='0' && $dato->formapago=='A' ){
			   
				$tabla.="<td align='center'><a href='../negocio/cont_movcaja.php?accion=ANULAR&idmovcaja=".$dato->idmovimiento."'>Anular</a></td>";				
			   }elseif($dato->idconceptopago!='1' && $dato->idconceptopago!='2' && $dato->idmovimientoref!='0' && $dato->formapago=='B'){  
			   
			   $tabla.="<td align='center'>-</td>";
			   
			   }elseif($dato->idconceptopago!='1' && $dato->idconceptopago!='2' && $dato->idmovimientoref=='0' && $dato->formapago=='A' ){
			   
				$tabla.="<td align='center'><a href='../negocio/cont_movcaja.php?accion=ANULAR&idmovcaja=".$dato->idmovimiento."'>Anular</a></td>";
			  }else{
			   $tabla.="<td align='center'>-</td>";
			  }
			   
	   }else{
	   $tabla.="<td align='center'>-</td>";	   
	   }
	   
	   
	   
	   if($dato->idconceptopago!='1' && $dato->idconceptopago!='2'){
	   $tabla.="<td align='center'><a href='#' onClick=javascript:window.open('pdf_ImprimirMovCaja.php?fecha=$dato->fecha&persona=$dato->idpersona&total=$dato->total&moneda=$dato->moneda&tipo=$dato->tipo&concepto=$dato->idconceptopago&idmov=$dato->idmovimiento&ndoc=$dato->numero','_blank')>Imprimir</a></td>";
	      
	   }else{
	   $tabla.="<td align='center'>-</td>";
	   }
	   
	   
   	   $tabla.="</tr>";
}  


  $tabla.="</table>";
  
  $tabla=utf8_encode($tabla);
	$objResp = new xajaxResponse();
	$objResp->assign('divtablamovimientos','innerHTML',$tabla);
	return $objResp;	
}


function listadocombo($tipo){

if($tipo==10){
	$combo="<select name='cboConceptoPago' id='cboConceptoPago'>";
    $ObjMov = new clsMovCaja();		
	$consulta= $ObjMov->cargarcombo('I');
			
	while($registro=$consulta->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		$combo.="<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}  	
	$combo.="</select>";
	$objResp = new xajaxResponse();
	$objResp->assign('divconceptopago','innerHTML',$combo);
	return $objResp;
	
}elseif($tipo==11){

$combo2="<select name='cboConceptoPago' id='cboConceptoPago'>";
    $ObjMov2 = new clsMovCaja();		
	$consulta2= $ObjMov2->cargarcombo('E');
			
	while($registro=$consulta2->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		$combo2.="<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}  	
	$combo2.="</select>";
	$combo2=utf8_encode($combo2);
	$objResp2 = new xajaxResponse();
	$objResp2->assign('divconceptopago','innerHTML',$combo2);
	return $objResp2;	

}	
}


function comboapertura(){
	$combo2="<select name='cboConceptoPago' id='cboConceptoPago'>";
    $ObjMov2 = new clsMovCaja();		
	$consulta2= $ObjMov2->comboapertura();
			
	while($registro=$consulta2->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		$combo2.="<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}  	
	$combo2.="</select>";
	$combo2=utf8_encode($combo2);
	$objResp2 = new xajaxResponse();
	$objResp2->assign('divconceptopago','innerHTML',$combo2);
	return $objResp2;	
}

function combocierre(){
	$combo2="<select name='cboConceptoPago' id='cboConceptoPago'>";
    $ObjMov2 = new clsMovCaja();		
	$consulta2= $ObjMov2->combocierre();
			
	while($registro=$consulta2->fetch())
	{
		$seleccionar="";
		if($registro[0]==$seleccionado) $seleccionar="selected";
		$combo2.="<option value='".$registro[0]."' ".$seleccionar.">".$registro[1]."</option>";
	}  	
	$combo2.="</select>";
	$combo2=utf8_encode($combo2);
	$objResp2 = new xajaxResponse();
	$objResp2->assign('divconceptopago','innerHTML',$combo2);
	return $objResp2;	
}

function estadocuenta($id,$money,$doc){
		$ObjMov = new clsMovCaja();		
		$consulta= $ObjMov->listarestadodecuenta($id,$money,$doc);
		$num_rows=$consulta->rowCount();
		
		if($num_rows==0){
		
		$tabla="No existen estados de cuenta pendientes con esta persona o moneda...!";
		
		}else{
		
		$tabla="<table class=registros width='100%'>
        <tr>
          <th>DOC</th>
          <th>N&ordm; DOC</th>
          <th>FECHA</th>
          <th>MONTO TOTAL</th>
		  <th>INICIAL</th>
		  <th>MONTO PAGADO</th>
		  <th>INTERES PAGADO</th>
		  <th>SALDO MONTO</th>
		  <th>SALDO INTERES</th>
		  <th>MONEDA</th>
          <th>VER CUOTAS</th>
        </tr>";
		
        while($registro=$consulta->fetch()){
		$cont++;
	    if($cont%2) $estilo="par";else $estilo="impar";
		$tabla.="<tr class='$estilo'>";
        $tabla.="<td align='center'>".$registro[1]."</td>";
        $tabla.="<td align='center'>".$registro[2]."</td>";
        $tabla.="<td align='center'>".$registro[3]."</td>";
        $tabla.="<td align='center'>".$registro[4]."</td>";
		$tabla.="<td align='center'>".number_format(($registro[4]-($ObjMov->monto_pagado($registro[0])+$ObjMov->saldo_monto($registro[0]))),2)."</td>";
		$tabla.="<td align='center'>".number_format((($ObjMov->monto_pagado($registro[0]))+(($registro[4]-($ObjMov->monto_pagado($registro[0])+$ObjMov->saldo_monto($registro[0]))))),2)."</td>";
		$tabla.="<td align='center'>".$ObjMov->interes_pagado($registro[0])."</td>";
		$tabla.="<td align='center'>".$ObjMov->saldo_monto($registro[0])."</td>";
		$tabla.="<td align='center'>".$ObjMov->saldo_interes($registro[0])."</td>";
		$tabla.="<td align='center'>".$registro[5]."</td>";
        $tabla.="<td align='center'><a href='#' onClick='mostrarcuotas(".$registro[0].")'>ver</a></td>";
        $tabla.="</tr>";
		}
      	$tabla.="</table>";
		
		}
	  $tabla=utf8_encode($tabla);
		$objResp = new xajaxResponse();
		$objResp->assign('divestadocuenta','innerHTML',$tabla);
		return $objResp;	

}

function mostrarcuotas($id){

$tabla2="<fieldset>
                <legend><strong>CUOTAS:</strong></legend>
				<table class=registros width='100%'>
        <tr>
          <th>Nro</th>
		  <th>FECHA CANC.</th>
		  <th>MONEDA</th>
          <th>MONTO</th>
          <th>INTERES</th>
          <th>MONTO PAGADO</th>
          <th>INTERES PAGADO</th>
          <th>SALDO MONTO</th>
		  <th>SALDO INTERES</th>
          <th>SELECCIONAR</th>
        </tr>";
		
		$ObjMov = new clsMovCaja();		
		$consulta= $ObjMov->listarcuotas($id);
        while($registro=$consulta->fetch()){
		$cont++;
	    if($cont%2) $estilo="par";else $estilo="impar";
		
        $tabla2.="<tr class='$estilo'>";
         $tabla2.="<td align='center'>".$registro[1]."</td>";
         $tabla2.="<td align='center'>".$registro[2]."</td>";
         $tabla2.="<td align='center'>".$registro[3]."</td>";
         $tabla2.="<td align='center'>".$registro[4]."</td>";
         $tabla2.="<td align='center'>".$registro[5]."</td>";
		 $tabla2.="<td align='center'>".$registro[6]."</td>";
		 $tabla2.="<td align='center'>".$registro[7]."</td>";
		 $tabla2.="<td align='center'>".$registro[8]."</td>";
		 $tabla2.="<td align='center'>".$registro[9]."</td>";
         $tabla2.="<td align='center'><a href='#' onClick='actualizarcuota(".$id." ,".$registro[0].",".$registro[1]." , ".$registro[8]." , ".$registro[9].")'>seleccionar</a></td>";
         $tabla2.= "</tr>";
		}
      $tabla2.="</table></fieldset>";
	  
	  $tabla2=utf8_encode($tabla2);
	  $objResp3 = new xajaxResponse();
	  $objResp3->assign('divcuotas','innerHTML',$tabla2);
	  return $objResp3;	
}

function actualizarcuota($id,$idcuota,$numero,$saldomonto,$saldointeres){
	
	$objResp4 = new xajaxResponse();
	$objResp4->assign('txtIdMov','value',$id);
	$objResp4->assign('txtIdCuota','value',$idcuota);
	$objResp4->assign('txtnumerocuota','value',$numero);
	$objResp4->assign('divnumerocuota','innerHTML',$numero);
	
	if($saldomonto==""){
	$objResp4->assign('divsaldomonto','innerHTML','0.00');
	$objResp4->assign('txtSaldoMonto','value','0');
	$objResp4->assign('txtMontoPagar','value','0.00');
	}else{
	
	$objResp4->assign('divsaldomonto','innerHTML',number_format($saldomonto,2));
	$objResp4->assign('txtSaldoMonto','value',$saldomonto);
	$objResp4->assign('txtMontoPagar','value',$saldomonto);
	
	}
	if($saldointeres==""){
	$objResp4->assign('divsaldointeres','innerHTML','0.00');
	$objResp4->assign('txtSaldoInteres','value','0');
	$objResp4->assign('txtInteres','value','0.00');
	}else{
	$objResp4->assign('divsaldointeres','innerHTML',number_format($saldointeres,2));
	$objResp4->assign('txtSaldoInteres','value',$saldointeres);
	$objResp4->assign('txtInteres','value',$saldointeres);
	}
	return $objResp4;

}

function agregar($idMov,$idCuota,$numerocuota,$monto,$interes,$saldomonto,$saldointeres,$fecha){
 	
	$ObjMov = new clsMovCaja();	
	
	if( $monto>=0 && $monto!='' && $interes!='' && $monto<=$saldomonto && $interes<=$saldointeres && $interes>=0 ){
	
	if($monto==$saldomonto && $interes==$saldointeres){
	$fecha='NO';
	}else{
	$fecha=$fecha;
	}
	
	
	
	Global $carro;
	$carro=$_SESSION['carrocuota'];

	$carro[($idCuota)]=array('idMov'=>$idMov,'idCuota'=>($idCuota),'numerocuota'=>($numerocuota),'monto'=>$monto,'interes'=>$interes,'total'=>($monto+$interes),'fechaprox'=>($fecha));

	$_SESSION['carrocuota']=$carro;
	$contador=0;
	$registros.="<fieldset>
                <legend><strong>DETALLE:</strong></legend>
	<table class=registros width='100%'>
        <tr>
          <th>DOC.</th>
          <th>Nro DOC.</th>
		  <th>Nro CUOTA</th>
          <th>MONTO</th>
          <th>INTERES</th>
		  <th>TOTAL</th>
		  <th>FECHA PROX. CANC.</th>
          <th>QUITAR</th>
        </tr>";
		
		foreach($carro as $k => $v){
		$suma1=$v["total"];
		$suma=$suma+$suma1;
        $contador++;
		
		$cont++;
	   if($cont%2) $estilo="par";else $estilo="impar";
		$registros.="<tr class='$estilo'>";
		
		$rst=$ObjMov->numero_documento($v["idCuota"]);
		$result=$rst->fetch();
		
          $registros.="<td align='center'>".$result[1]."</td>";
          $registros.="<td align='center'>".$result[0]."</td>";
		  $registros.="<td align='center'>".$v["numerocuota"]."</td>";
          $registros.="<td align='center'>".number_format($v["monto"],2)."</td>";
          $registros.="<td align='center'>".number_format($v["interes"],2)."</td>";
		  $registros.="<td align='center'>".number_format($v["total"],2)."</td>";
		  $registros.="<td align='center'>".$v["fechaprox"]."</td>";
          $registros.="<td align='center'><a href='#' onClick='quitar(".$v["idCuota"].")'>Quitar</a></td>";
      	  $registros.="</tr>";
		
		}
		
		$total=$suma;
      $registros.="</table></fieldset>";
	  
	 $registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("txtIdMov","value","");
	$obj->assign("txtIdCuota","value","");
	
	$obj->assign("txtnumerocuota","value","");
	$obj->assign("txtSaldoMonto","value","");
	$obj->assign("txtSaldoInteres","value","");
	
	$obj->assign("divnumerocuota","innerHTML","--");
	$obj->assign("divsaldomonto","innerHTML","--");
	$obj->assign("divsaldointeres","innerHTML","--");
	
	$obj->assign("txtMontoPagar","value","");
	$obj->assign("txtInteres","value","");	
	$obj->assign("divMontoTotal","innerHTML",number_format($total,2));
	if($total!=''){
	$obj->assign("txtMontoTotal","value",$total);	
	}else{
	$obj->assign("txtMontoTotal","value",0.00);
	}		
}else{

	$mensaje="Realize las siguientes correcciones:\n\n";
	if($monto<0 or $monto=='' or $interes<0 or $interes==''){
		$mensaje.="  * Ingrese un Monto o Interes a pagar correcto!!!\n";
	}else{
		$mensaje.="  *Ingrese un Monto o Interes a pagar menor o igual a lo indicado!!!\n";
	}

	$obj=new xajaxResponse();
	$obj->alert($mensaje);
}
return $obj;
}

function quitar($idCuota){

$ObjMov = new clsMovCaja();	
	Global $carro;
	if(isset($_SESSION['carrocuota']))
		$carro=$_SESSION['carrocuota'];
		
	unset($carro[($idCuota)]);

	$_SESSION['carrocuota']=$carro;

	$registros.="<fieldset>
                <legend><strong>DETALLE:</strong></legend>
				<table class=registros width='100%'>
       <tr>
          <th>DOC.</th>
          <th>Nro DOC.</th>
		  <th>Nro CUOTA</th>
          <th>MONTO</th>
          <th>INTERES</th>
		  <th>TOTAL</th>
		  <th>FECHA PROX. CANC.</th>
          <th>QUITAR</th>
        </tr>";
		
		foreach($carro as $k => $v){
        $suma1=$v["total"];
		$suma=$suma+$suma1;
		
		$cont++;
	   if($cont%2) $estilo="par";else $estilo="impar";
		$registros.="<tr class='$estilo'>";
        
		$rst=$ObjMov->numero_documento($v["idCuota"]);
		$result=$rst->fetch();
		
       $registros.="<td align='center'>".$result[1]."</td>";
          $registros.="<td align='center'>".$result[0]."</td>";
		   $registros.="<td align='center'>".$v["numerocuota"]."</td>";
          $registros.="<td align='center'>".number_format($v["monto"],2)."</td>";
          $registros.="<td align='center'>".number_format($v["interes"],2)."</td>";
		  $registros.="<td align='center'>".number_format($v["total"],2)."</td>";
		  $registros.="<td align='center'>".$v["fechaprox"]."</td>";
          $registros.="<td align='center'><a href='#' onClick='quitar(".$v["idCuota"].")'>Quitar</a></td>";
      	  $registros.="</tr>";
		
		}
		
	  $total=$suma;
      $registros.="</table></fieldset>";
	  
	 $registros=utf8_encode($registros);
	$obj=new xajaxResponse();
	$obj->assign("DivDetalle","innerHTML",$registros);
	$obj->assign("divMontoTotal","innerHTML",number_format($total,2));
	if($total!=''){
	$obj->assign("txtMontoTotal","value",$total);	
	}else{
	$obj->assign("txtMontoTotal","value",0.00);
	}	
	
	
	return $obj;
}

function listadopersona($campo,$frase,$TotalReg,$origen){
	$Obj = new clsMovCaja();
	$EncabezadoTabla=array("Nombres y Apellidos","RUC/DNI");
	$regxpag=10;
	$nr1=$TotalReg;
	$pag=1;
	$inicio=$regxpag*($pag - 1);
	$limite="";
	
	if($inicio==0){		
		$rs = $Obj->consultarpersonarol($origen,$campo,$frase,$limite);	
    	$nr1=$rs->rowCount();
	}
	$nunPag=ceil($nr1/$regxpag);
	$limite=" limit $inicio,$regxpag";
	$rs = $Obj->consultarpersonarol($origen,$campo,$frase,$limite);	
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
  $objResp2=new xajaxResponse();
  $objResp2->assign('txtIdPersona','value',$reg->IdPersona);
  $objResp2->assign('txtPersona','value',utf8_encode($reg->Nombres));
  return $objResp2;
}

function eliminarvariablesession(){
if(isset($_SESSION['carrocuota'])){
session_start();
unset($_SESSION['carrocuota']);
}
return;
}

function cajadeshabilitada(){
$caja="<input name='txtMontoTotal' type='text' id='txtMontoTotal' size='15' maxlength='11'  readonly='' >";
$obj=new xajaxResponse();
$obj->assign("divcajamonto","innerHTML",$caja);
return $obj;
}
function cajahabilitada(){
$caja="<input name='txtMontoTotal' type='text' id='txtMontoTotal' size='15' maxlength='11' >";
$obj=new xajaxResponse();
$obj->assign("divcajamonto","innerHTML",$caja);
return $obj;
}

$fgenerarnumero = & $xajax-> registerFunction('generarnumero');
$fgenerarnumero->setParameter(0,XAJAX_INPUT_VALUE,'cboDoc');
$fgenerarnumero->setParameter(1,XAJAX_INPUT_VALUE,'txtFecha');

$xajax->registerFunction('mostrarPersona');
$xajax->registerFunction('mostrarcuotas');
$xajax->registerFunction('actualizarcuota');
$xajax->registerFunction('eliminarvariablesession');
$xajax->registerFunction('cajadeshabilitada');
$xajax->registerFunction('cajahabilitada');

$festadocuenta= & $xajax-> registerFunction('estadocuenta');
$festadocuenta->setParameter(0,XAJAX_INPUT_VALUE,'txtIdPersona');
$festadocuenta->setParameter(1,XAJAX_INPUT_VALUE,'cboMoney');
$festadocuenta->setParameter(2,XAJAX_INPUT_VALUE,'cboDoc');

$xajax->registerFunction("quitar");

$fagregar= & $xajax-> registerFunction('agregar');
$fagregar->setParameter(0,XAJAX_INPUT_VALUE,'txtIdMov');
$fagregar->setParameter(1,XAJAX_INPUT_VALUE,'txtIdCuota');
$fagregar->setParameter(2,XAJAX_INPUT_VALUE,'txtnumerocuota');
$fagregar->setParameter(3,XAJAX_INPUT_VALUE,'txtMontoPagar');
$fagregar->setParameter(4,XAJAX_INPUT_VALUE,'txtInteres');
$fagregar->setParameter(5,XAJAX_INPUT_VALUE,'txtSaldoMonto');
$fagregar->setParameter(6,XAJAX_INPUT_VALUE,'txtSaldoInteres');
$fagregar->setParameter(7,XAJAX_INPUT_VALUE,'txtFechaProx');


$flistadopersona = & $xajax-> registerFunction('listadopersona');
$flistadopersona->setParameter(0,XAJAX_INPUT_VALUE,'campoPersona');
$flistadopersona->setParameter(1,XAJAX_INPUT_VALUE,'frasePersona');
$flistadopersona->setParameter(3,XAJAX_INPUT_VALUE,'TotalRegPersona');
$flistadopersona->setParameter(4,XAJAX_INPUT_VALUE,'txtOrigenPersona');

$flistadocombo= & $xajax-> registerFunction('listadocombo');
$flistadocombo->setParameter(0,XAJAX_INPUT_VALUE,'cboDoc');

$fgenerarbusqueda = & $xajax-> registerFunction('generarbusqueda');


$fpresentarmovimientos = & $xajax-> registerFunction('presentarmovimientos');
$fcombocierre = & $xajax-> registerFunction('combocierre');
$fcomboapertura= & $xajax-> registerFunction('comboapertura');

$fpresentarbusquedamov = & $xajax-> registerFunction('presentarbusquedamov');
$fpresentarbusquedamov->setParameter(0,XAJAX_INPUT_VALUE,'cboDoc');
$fpresentarbusquedamov->setParameter(1,XAJAX_INPUT_VALUE,'cboRolPersona');
$fpresentarbusquedamov->setParameter(2,XAJAX_INPUT_VALUE,'cboConceptoPago');
$fpresentarbusquedamov->setParameter(3,XAJAX_INPUT_VALUE,'cboMoneda');

$xajax->processRequest();
echo"<?xml version='1.0' encoding='UTF-8'?>";
?>

