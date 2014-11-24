<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_movimiento.php");
$fechainicio=$_GET['inicio'];
$fechafin=$_GET['final'];
$agrupado=$_GET['agrupado'];
$formapago=$_GET['formap'];
$moneda=$_GET['moneda'];
$objMovimiento = new clsMovimiento();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();
	if($dato1->moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}

$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(280,10,'REPORTE DE VENTAS',0,1,'C');
$pdf->Ln();
if($fechainicio!=""){ 
$pdf->Cell(35,8,"DESDE: ".$fechainicio,0,0,'L');
$pdf->Ln();}
if($fechafin!=""){
$pdf->Cell(34,8,"HASTA: ".$fechafin,0,0,'L');
$pdf->Ln();
}
if($agrupado=='mes')
$pdf->Cell(33,16,"MES",1,0,'C');
else
$pdf->Cell(33,16,"AÑO",1,0,'C');

$pdf->Cell(56,8,"BOLETAS",1,0,'C');
$pdf->Cell(56,8,"FACTURAS",1,0,'C');
$pdf->Cell(50,16,"TOTAL CREDITO",1,0,'C');
$pdf->Cell(50,16,"TOTAL CONTADO",1,0,'C');
$pdf->Cell(30,16,"TOTAL",1,0,'C');
$pdf->Cell(1,8,"",0,'C');
$pdf->Ln();
$pdf->Cell(33,8,"",0,'C');
$pdf->Cell(28,8,"CREDITO",1,0,'C');
$pdf->Cell(28,8,"CONTADO",1,0,'C');
$pdf->Cell(28,8,"CREDITO",1,0,'C');
$pdf->Cell(28,8,"CONTADO",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();

$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$rst = $objMovimiento->consultarreporte($fechainicio,$fechafin,$moneda,$agrupado);
while($dato=$rst->fetchObject()){
  if($agrupado=='mes')
  $pdf->Cell(33,8,$mes=$meses[date(($dato->mes)-1)],1,0,'C');
else
$pdf->Cell(33,8,$dato->ano,1,0,'C');
  
  $pdf->Cell(28,8,$mon.$dato->totalBB,1,0,'R');
  $pdf->Cell(28,8,$mon.$dato->totalAB,1,0,'R');
  $pdf->Cell(28,8,$mon.$dato->totalBF,1,0,'R');
  $pdf->Cell(28,8,$mon.$dato->totalAF,1,0,'R');
  $pdf->Cell(50,8,$mon.number_format($dato->totalBF+$dato->totalBB,2),1,0,'R');
  $pdf->Cell(50,8,$mon.number_format($dato->totalAB+$dato->totalAF,2),1,0,'R');

  $pdf->Cell(30,8,$mon.number_format($dato->totalAB+$dato->totalAF+$dato->totalBF+$dato->totalBB,2),1,0,'R');
  $pdf->Ln();
  $tot= $dato->totalAB+$dato->totalAF+$dato->totalBF+$dato->totalBB;
	$total = number_format($total + $tot,2); 	
}
$pdf->SetFont('Arial','B',10);
  $pdf->Cell(245,8,"TOTAL",1,0,'R');
  $pdf->Cell(30,8,$mon.$total,1,0,'R');
$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>