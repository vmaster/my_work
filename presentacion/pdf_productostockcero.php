<?php
session_start();
if(isset($_SESSION['IdSucursal'])){
$idsucursal=$_SESSION['IdSucursal'];}else {$idsucursal=1;}
require_once("../datos/cado.php");
require_once("../negocio/cls_producto.php");
$objProducto = new clsProducto();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$desc=$_GET['desc'];
$categoria=$_GET['categoria'];
$marca=$_GET['marca'];
$situacionstock=$_GET['situacionstock'];
$pdf=new FPDF();
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(290,8,'LISTA DE PRODUCTOS CON STOCK MINIMO SUPERADO',0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,8,"CODIGO",1,0,'C');
$pdf->Cell(70,8,"DESCRIPCION",1,0,'C');
$pdf->Cell(25,8,"CATEGORIA",1,0,'C');
$pdf->Cell(20,8,"MARCA",1,0,'C');
$pdf->Cell(27,8,"UNIDAD BASE",1,0,'C');
$pdf->Cell(15,8,"PESO",1,0,'C');
$pdf->Cell(25,8,"MEDIDA",1,0,'C');
$pdf->Cell(35,8,"STOCK SEGURIDAD",1,0,'C');
$pdf->Cell(35,8,"STOCK ACTUAL",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$rst = $objProducto->buscarProductosStockSuperado($idsucursal,NULL,$desc,$categoria,$marca,$situacionstock);
while($dato=$rst->fetchObject()){

  $pdf->Cell(30,8,$dato->codigo,1,0,'C');
  $pdf->Cell(70,8,substr($dato->descripcion,0,35),1,0,'C');
  $pdf->Cell(25,8,substr($dato->categoria,0,10),1,0,'C');
  $pdf->Cell(20,8,substr($dato->marca,0,8),1,0,'C');
  $pdf->Cell(27,8,substr($dato->unidadbase,0,11),1,0,'C');
  $pdf->Cell(15,8,$dato->peso,1,0,'C');
  $pdf->Cell(25,8,substr($dato->medidapeso,0,10),1,0,'C');
  $pdf->Cell(35,8,$dato->stockseguridad,1,0,'C');
  $pdf->Cell(35,8,$dato->stockactual,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>