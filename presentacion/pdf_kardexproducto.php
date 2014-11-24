<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_kardex.php");
$objKardex = new clsKardex();
$rst1 = $objKardex->consultar($_GET['IdProducto']);
$dato1=$rst1->fetchObject();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(180,8,'KARDEX DEL PRODUCTO: '.$dato1->pro,0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(33,8,"TIPO MOVIMIENTO",1,0,'C');
$pdf->Cell(35,8,"Nº DOCUMENTO",1,0,'C');
$pdf->Cell(34,8,"STOCK ANTERIOR",1,0,'C');
$pdf->Cell(18,8,"INGRESO",1,0,'C');
$pdf->Cell(17,8,"SALIDA",1,0,'C');
$pdf->Cell(30,8,"STOCK ACTUAL",1,0,'C');
$pdf->Cell(23,8,"OPERACION",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$rst = $objKardex->consultar($_GET['IdProducto']);
while($dato=$rst->fetchObject()){

if($dato->movimiento=='VENTA' or $dato->tipodoc=='E' or $dato->tipodoc=='GRE'){
 $cant="0"; 
 $cant1=$dato->cantidad;

}else{
 $cant=$dato->cantidad;  
 $cant1= "0";
} 

  if($dato->estado=='N') { $estado= 'NUEVO';} else {$estado= 'ANULACIÓN';}

  $pdf->Cell(33,8,$dato->movimiento,1,0,'C');
  $pdf->Cell(35,8,$dato->tipodoc." ".$dato->doc,1,0,'C');
  $pdf->Cell(34,8,$dato->stockanterior,1,0,'C');
  $pdf->Cell(18,8,$cant,1,0,'C');
  $pdf->Cell(17,8,$cant1,1,0,'C');
  $pdf->Cell(30,8,$dato->stockactual,1,0,'C');
  $pdf->Cell(23,8,$estado,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>