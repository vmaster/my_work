<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_producto.php");
$objProducto = new clsProducto();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$desc=$_GET['desc'];
$categoria=$_GET['categoria'];
$marca=$_GET['marca'];
$pdf=new FPDF();
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(290,8,'LISTA DE PRODUCTOS',0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,8,"CODIGO",1,0,'C');
$pdf->Cell(32,8,"DESCRIPCION",1,0,'C');
$pdf->Cell(25,8,"CATEGORIA",1,0,'C');
$pdf->Cell(20,8,"MARCA",1,0,'C');
$pdf->Cell(27,8,"UNIDAD BASE",1,0,'C');
$pdf->Cell(15,8,"PESO",1,0,'C');
$pdf->Cell(25,8,"MEDIDA",1,0,'C');
$pdf->Cell(35,8,"STOCK SEGURIDAD",1,0,'C');
$pdf->Cell(25,8,"ARMARIO",1,0,'C');
$pdf->Cell(19,8,"COLUMNA",1,0,'C');
$pdf->Cell(10,8,"FILA",1,0,'C');
$pdf->Cell(15,8,"KARDEX",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$rst = $objProducto->buscar(NULL,$desc,$categoria,$marca);
while($dato=$rst->fetchObject()){

  $pdf->Cell(30,8,$dato->codigo,1,0,'C');
  $pdf->Cell(32,8,substr($dato->descripcion,0,14),1,0,'C');
  $pdf->Cell(25,8,substr($dato->categoria,0,10),1,0,'C');
  $pdf->Cell(20,8,substr($dato->marca,0,8),1,0,'C');
  $pdf->Cell(27,8,substr($dato->unidadbase,0,11),1,0,'C');
  $pdf->Cell(15,8,$dato->peso,1,0,'C');
  $pdf->Cell(25,8,substr($dato->medidapeso,0,10),1,0,'C');
  $pdf->Cell(35,8,$dato->stockseguridad,1,0,'C');
  $pdf->Cell(25,8,$dato->armario,1,0,'C');
  $pdf->Cell(19,8,$dato->columna,1,0,'C');
  $pdf->Cell(10,8,$dato->fila,1,0,'C');
  $pdf->Cell(15,8,$dato->kardex,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>