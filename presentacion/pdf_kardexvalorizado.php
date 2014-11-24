<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_kardex.php");
$idproducto=$_GET['idpro'];
$fechainicio=$_GET['inicio'];
$fechafin=$_GET['final'];
$moneda=$_GET['moneda'];
$categoria=$_GET['categoria'];

$objKardex = new clsKardex();
$rst1 = $objKardex->consultarkardex($idproducto,$fechainicio,$fechafin,$moneda,$categoria);
$dato1=$rst1->fetchObject();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(180,8,'KARDEX VALORIZADO',0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',7);
if($fechainicio!=""){ 
$pdf->Cell(35,8,"DESDE: ".$fechainicio,0,0,'L');
$pdf->Ln();}
if($fechafin!=""){
$pdf->Cell(34,8,"HASTA: ".$fechafin,0,0,'L');
$pdf->Ln();
}

$cont=0; 
$rst = $objKardex->consultarkardex($idproducto,$fechainicio,$fechafin,$moneda,$categoria);
while($dato=$rst->fetchObject()){


	$mon='S/. ';
	if($cont==0){
		$cont++;
		$ProdActual=$dato->codigo;
				$pdf->SetFont('Arial','B',7);
		$pdf->Cell(33,8,"CODIGO: ".$dato->codigo,0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,8,"PRODUCTO: ".$dato->pro,0,0,'L');
		$pdf->Ln();
$pdf->Cell(13,16,"FECHA",1,0,'C');
$pdf->Cell(25,16,"NRO DOC",1,0,'C');
$pdf->Cell(15,16,"CONCEPTO",1,0,'C');
$pdf->Cell(12,16,"ESTADO",1,0,'C');
$pdf->Cell(44,8,"INGRESOS",1,0,'C');
$pdf->Cell(44,8,"SALIDAS",1,0,'C');
$pdf->Cell(44,8,"SALDOS",1,0,'C');
$pdf->Ln();
$pdf->Cell(13,8,"",0,0,'C');
$pdf->Cell(25,8,"",0,0,'C');
$pdf->Cell(15,8,"",0,0,'C');
$pdf->Cell(12,8,"",0,0,'C');
$pdf->Cell(13,8,"CANTIDAD",1,0,'C');
$pdf->Cell(16,8,"PRECIO UNIT",1,0,'C');
$pdf->Cell(15,8,"TOTAL",1,0,'C');
$pdf->Cell(13,8,"CANTIDAD",1,0,'C');
$pdf->Cell(16,8,"PRECIO UNIT",1,0,'C');
$pdf->Cell(15,8,"TOTAL",1,0,'C');
$pdf->Cell(13,8,"CANTIDAD",1,0,'C');
$pdf->Cell(16,8,"PRECIO UNIT",1,0,'C');
$pdf->Cell(15,8,"TOTAL",1,0,'C');

$pdf->SetFont('Arial','',7);
$pdf->Ln();
	}
	if ($ProdActual!=$dato->codigo){
		$ProdActual=$dato->codigo;
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(33,8,"CODIGO: ".$dato->codigo,0,0,'L');
		$pdf->Ln();
		$pdf->Cell(35,8,"PRODUCTO: ".$dato->pro,0,0,'L');
		$pdf->Ln();
$pdf->Cell(13,16,"FECHA",1,0,'C');
$pdf->Cell(25,16,"NRO DOC",1,0,'C');
$pdf->Cell(15,16,"CONCEPTO",1,0,'C');
$pdf->Cell(12,16,"ESTADO",1,0,'C');
$pdf->Cell(44,8,"INGRESOS",1,0,'C');
$pdf->Cell(44,8,"SALIDAS",1,0,'C');
$pdf->Cell(44,8,"SALDOS",1,0,'C');
$pdf->Ln();
$pdf->Cell(13,8,"",0,0,'C');
$pdf->Cell(25,8,"",0,0,'C');
$pdf->Cell(15,8,"",0,0,'C');
$pdf->Cell(12,8,"",0,0,'C');
$pdf->Cell(13,8,"CANTIDAD",1,0,'C');
$pdf->Cell(16,8,"PRECIO UNIT",1,0,'C');
$pdf->Cell(15,8,"TOTAL",1,0,'C');
$pdf->Cell(13,8,"CANTIDAD",1,0,'C');
$pdf->Cell(16,8,"PRECIO UNIT",1,0,'C');
$pdf->Cell(15,8,"TOTAL",1,0,'C');
$pdf->Cell(13,8,"CANTIDAD",1,0,'C');
$pdf->Cell(16,8,"PRECIO UNIT",1,0,'C');
$pdf->Cell(15,8,"TOTAL",1,0,'C');

$pdf->SetFont('Arial','',7);
$pdf->Ln();
	}	
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
  if($dato->estado=='N') { $estado= 'NUEVO';} else {$estado= 'ANULADO';}

  $pdf->Cell(13,8,$dato->fecha,1,0,'C');
  $pdf->Cell(25,8,$dato->tipodoc.' '.$mov,1,0,'C');
  $pdf->Cell(15,8,$movim,1,0,'C');
  $pdf->Cell(12,8,$estado,1,0,'C');
  $pdf->Cell(13,8,$egreso,1,0,'C');
  $pdf->Cell(16,8,$mon.$precioventa,1,0,'C');
  $pdf->Cell(15,8,$mon.number_format($egreso*$precioventa,2),1,0,'C');
 $pdf->Cell(13,8,$ingreso,1,0,'C');
  $pdf->Cell(16,8,$mon.$preciocompra,1,0,'C');
   $pdf->Cell(15,8,$mon.number_format($ingreso*$preciocompra,2),1,0,'C');
 $pdf->Cell(13,8,$dato->saldoactual,1,0,'C');
  $pdf->Cell(16,8,$mon.$precio,1,0,'C');
  $pdf->Cell(15,8,$mon.number_format($dato->saldoactual*$precio,2),1,0,'C');

 
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>