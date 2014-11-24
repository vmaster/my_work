<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require_once("../datos/cado.php");
require_once("../negocio/cls_movcaja.php");
require_once("../negocio/cls_persona.php");


$objmovcaja=new clsMovCaja();
$objpersona=new clsPersona();


$ndoc=$_GET['ndoc'];
$fecha=$_GET['fecha'];
$idpersona=$_GET['persona'];
$total=$_GET['total'];
$moneda=$_GET['moneda'];
$tipo=$_GET['tipo'];
$idconcepto=$_GET['concepto'];
$idmovcaja=$_GET['idmov'];

$comentario=$objmovcaja->consultarcomentario($idmovcaja);



$rst=$objpersona->buscar($idpersona,'','');
$dato=$rst->fetch();
$persona=$dato[2].' '.$dato[3];
$dni=$dato[4];

$concepto=$objmovcaja->consultarconceptopag($idconcepto);


if($moneda=='S'){
$money='S/.';
}elseif($moneda=='D'){
$money='$.';

}


if($tipo=='I'){
$T='INGRESO';
$R='RECIBI DE:';
}elseif($tipo=='E'){
$T='EGRESO';
$R='PAGE A:';
}



define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();

$pdf->AddPage('P','A4');
$pdf->SetFont('Arial','U',25);
$pdf->Cell(190,10,'RECIBO DE '.$T,0,1,'C');
$pdf->Ln();
$pdf->Ln();

$pdf->SetFont('Arial','',14);
$pdf->Cell(80,8,"NRO DOC:  ".$ndoc,1,0,'C');
$pdf->Cell(30,8,"  ",0,0,'L');
$pdf->Cell(80,8,"FECHA:  ".$fecha,1,1,'C');
$pdf->Ln();
$pdf->Cell(190,8,$R.'   '.substr($persona,0,25).'.   CON DNI/RUC:  '.$dni,1,1,'L');
$pdf->Ln();
$pdf->Cell(190,8,'LA CANTIDAD DE: '.$money." ".$total,1,1,'L');
$pdf->Ln();
$pdf->Cell(190,8,'POR CONCEPTO DE: '.$concepto,1,1,'L');
$pdf->MultiCell(190,8,$comentario,1,'L',false);
$pdf->Ln();



		$ObjMov = new clsMovCaja();		
		$consulta= $ObjMov->consultardetallesmovcaja($idmovcaja);
		$numrow=$consulta->rowCount();


if($numrow!=0){

$pdf->SetFont('Arial','B',10);

$pdf->Cell(190,8,'DETALLE DE LAS CUOTAS:',0,1,'L');

$pdf->Cell(53,8,"DOCUMENTO",1,0,'C');
$pdf->Cell(31,8,"NRO DOC.",1,0,'C');
$pdf->Cell(24,8,"NRO CUOTA.",1,0,'C');
$pdf->Cell(24,8,"MONTO.",1,0,'C');
$pdf->Cell(24,8,"INTERES.",1,0,'C');
$pdf->Cell(10,8,"MDA.",1,0,'C');
$pdf->Cell(24,8,"T. CAMBIO.",1,0,'C');
$pdf->Ln();

while($registro=$consulta->fetch()){

$num_doc=$objmovcaja->numero_documento($registro[0]);
$result=$num_doc->fetch();
$pdf->SetFont('Arial','',8);
$pdf->Cell(53,8,$result[1],1,0,'C');
$pdf->Cell(31,8,$result[0],1,0,'C');
$pdf->Cell(24,8,$registro[1],1,0,'C');
$pdf->Cell(24,8,$registro[2],1,0,'C');
$pdf->Cell(24,8,$registro[3],1,0,'C');
$pdf->Cell(10,8,$registro[4],1,0,'C');
if($registro[4]=='S'){
$pdf->Cell(24,8,'--',1,0,'C');
}elseif($registro[4]=='D'){
$pdf->Cell(24,8,$registro[5],1,0,'C');
}
$pdf->Ln();

}

/*$num_doc=$objmovcaja->numero_documento($idcuota);
$result=$num_doc->fetch();*/



}


$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>