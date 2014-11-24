<?php
session_start();
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
$pdf->Cell(20,8,"COLOR",1,0,'C');
$pdf->Cell(20,8,"TALLA",1,0,'C');
$pdf->Cell(27,8,"UNIDAD BASE",1,0,'C');
$pdf->Cell(20,8,"STOCK",1,0,'C');
$pdf->Cell(15,8,"MONEDA",1,0,'C');
$pdf->Cell(25,8,"P. COMPRA",1,0,'C');
$pdf->Cell(25,8,"P. VENTA",1,0,'C');
$pdf->Cell(25,8,"P. VENTA 2",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$rst = $objProducto->buscarProductoventa(NULL,$desc,$categoria,$marca);
while($dato=$rst->fetchObject()){

  $pdf->Cell(30,8,$dato->codigo,1,0,'C');
  $pdf->Cell(32,8,substr($dato->producto,0,14),1,0,'C');
  $pdf->Cell(25,8,substr($dato->categoria,0,10),1,0,'C');
  $pdf->Cell(20,8,substr($dato->marca,0,8),1,0,'C');
  $pdf->Cell(20,8,substr($dato->color,0,11),1,0,'C');
  $pdf->Cell(20,8,substr($dato->talla,0,11),1,0,'C');
  $pdf->Cell(27,8,substr($dato->unidadbase,0,11),1,0,'C');
  $pdf->Cell(20,8,$dato->StockActual,1,0,'C');
  $pdf->Cell(15,8,$dato->moneda,1,0,'C');
  $pdf->Cell(25,8,$dato->preciocompra,1,0,'C');
  $pdf->Cell(25,8,$dato->precioventa,1,0,'C');
  $pdf->Cell(25,8,$dato->precioventaespecial,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>