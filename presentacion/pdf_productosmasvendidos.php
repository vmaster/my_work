<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_producto.php");
$categoria=$_GET['idcategoria'];
$fechainicio=$_GET['inicio'];
$fechafin=$_GET['final'];
$moneda=$_GET['moneda'];
$marca=$_GET['marca'];
$objProducto = new clsProducto();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(290,8,'LISTA DE PRODUCTOS MAS VENDIDOS',0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',9);
if($fechainicio!=""){ 
$pdf->Cell(35,8,"DESDE: ".$fechainicio,0,0,'L');
$pdf->Ln();}
if($fechafin!=""){
$pdf->Cell(34,8,"HASTA: ".$fechafin,0,0,'L');
$pdf->Ln();
}
$pdf->Cell(25,8,"CODIGO",1,0,'C');
$pdf->Cell(27,8,"DESCRIPCION",1,0,'C');
$pdf->Cell(22,8,"CATEGORIA",1,0,'C');
$pdf->Cell(19,8,"MARCA",1,0,'C');
$pdf->Cell(25,8,"UNIDAD BASE",1,0,'C');
$pdf->Cell(13,8,"PESO",1,0,'C');
$pdf->Cell(24,8,"MEDIDA",1,0,'C');
$pdf->Cell(28,8,"PRECIO COMPRA",1,0,'C');
$pdf->Cell(20,8,"PRECIO VTA",1,0,'C');
$pdf->Cell(37,8,"PRECIO VTA ESPECIAL",1,0,'C');
$pdf->Cell(21,8,"CANT VECES",1,0,'C');
$pdf->Cell(20,8,"TOTAL",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$rst = $objProducto->consultarproductosmasvendidos($categoria,$fechainicio,$fechafin,$moneda,$marca);
while($dato=$rst->fetchObject()){

  if($dato->estado=='N') { $estado= 'NUEVO';} else {$estado= 'ANULADO';}
  
  	if($moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}

  $pdf->Cell(25,8,$dato->codigo,1,0,'C');
  $pdf->Cell(27,8,substr($dato->descripcion,0,11),1,0,'C');
  $pdf->Cell(22,8,substr($dato->categoria,0,8),1,0,'C');
  $pdf->Cell(19,8,substr($dato->marca,0,7),1,0,'C');
  $pdf->Cell(25,8,substr($dato->unidadbase,0,10),1,0,'C');
  $pdf->Cell(13,8,$dato->peso,1,0,'C');
  $pdf->Cell(24,8,substr($dato->medidapeso,0,9),1,0,'C');
  $pdf->Cell(28,8,$mon.$dato->pcompra,1,0,'C');
  $pdf->Cell(20,8,$mon.$dato->pventa,1,0,'C');
  $pdf->Cell(37,8,$mon.$dato->pventaespecial,1,0,'C');
    $pdf->Cell(21,8,$dato->veces,1,0,'C');
   $pdf->Cell(20,8,$mon.$dato->total,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>