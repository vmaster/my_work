<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_persona.php");
$zona=$_GET['zona'];
$sector=$_GET['sector'];
$nombres=$_GET['nombres'];
$apellidos=$_GET['apellidos'];
$tipoper=$_GET['tipoper'];
$rol=$_GET['rol'];
$objPersona = new clsPersona();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
	if($rol==1){
	$per=" CLIENTES";
	}elseif($rol==2){
	$per=" PROVEEDORES";
	}else	if($rol==3){
	$per=" EMPLEADOS";
	}else	if($rol==4){
	$per=" USUARIOS";
	}else
	$per=" PERSONAS";
$pdf=new FPDF();
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',14);
$pdf->Cell(280,8,'LISTADO DE'.$per,0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Ln();
$pdf->Cell(28,8,"TIPO PERSONA",1,0,'C');
$pdf->Cell(25,8,"NOMBRES",1,0,'C');
$pdf->Cell(45,8,"APELLIDOS",1,0,'C');
$pdf->Cell(25,8,"NRO DOC",1,0,'C');
$pdf->Cell(10,8,"SEXO",1,0,'C');
$pdf->Cell(40,8,"DIRECCION",1,0,'C');
$pdf->Cell(20,8,"CELULAR",1,0,'C');
$pdf->Cell(45,8,"EMAIL",1,0,'C');
$pdf->Cell(20,8,"SECTOR",1,0,'C');
$pdf->Cell(20,8,"ZONA",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();


$rst = $objPersona->buscar1(NULL,$nombres,$apellidos,$tipoper,$rol,$sector,$zona);
while($dato=$rst->fetchObject()){

  $pdf->Cell(28,8,$dato->tipopersona,1,0,'C');
  $pdf->Cell(25,8,substr($dato->nombres,0,9),1,0,'C');
  $pdf->Cell(45,8,substr($dato->apellidos,0,20),1,0,'C');
  $pdf->Cell(25,8,$dato->nrodoc,1,0,'C');
  $pdf->Cell(10,8,$dato->sexo,1,0,'C');
  $pdf->Cell(40,8,substr($dato->direccion,0,17),1,0,'C');
  $pdf->Cell(20,8,$dato->celular,1,0,'C');
  $pdf->Cell(45,8,substr($dato->email,0,23),1,0,'C');
  $pdf->Cell(20,8,substr($dato->sector,0,8),1,0,'C');
  $pdf->Cell(20,8,substr($dato->zona,0,8),1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>