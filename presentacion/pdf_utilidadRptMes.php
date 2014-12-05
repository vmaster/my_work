<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require_once("../datos/cado.php");
require_once("../negocio/cls_movcaja.php");

$idtipodocumento=$_GET['idtipodoc'];
$idrolpersona=$_GET['idrolpersona'];
$mesinicio=$_GET['mesI'];
$mesfin=$_GET['mesF'];
$idconceptopago=$_GET['idconceptopago'];
$moneda=$_GET['moneda'];
$year=$_GET['year'];

define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();


if($_GET['idconceptopago']=='0'){
$cp='TODOS';
}else{
$Ob1 = new clsMovCaja();
$rst1=$Ob1->consultarconceptopag($_GET['idconceptopago']);
$cp=$rst1;
}

if($_GET['idrolpersona']=='0'){
$rol='TODOS';
}else{
$Ob1 = new clsMovCaja();
$rst1=$Ob1->consultarrolper($_GET['idrolpersona']);
$rol=$rst1;
}

$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',18);
$pdf->Cell(280,10,'REPORTE DE UTILIDAD POR MES',0,1,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,8,"FECHA DEL REPORTE: ".$_SESSION['FechaProceso'],0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"AÑO: ".$year,0,0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','B',11);
$pdf->Cell(35,8,"MES",1,0,'C');
$pdf->Cell(55,8,"VENTAS POR MES (S/.)",1,0,'C');
$pdf->Cell(55,8,"COMPRAS POR MES (S/.)",1,0,'C');
$pdf->Cell(55,8,"UTILIDAD POR MES (S/.)",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();

if($mesinicio<=$mesfin){
$num=$mesfin-$mesinicio;
$mesI=$mesinicio;
$mesF=$mesfin;
}else{
$num=$mesinicio-$mesfin;
$mesI=$mesfin;
$mesF=$mesinicio;
}

	$Ob = new clsMovCaja();		
	
	$IS=0;
	$ID=0;
	$ES=0;
	$ED=0;
	
	for($i=$mesI;$i<=$mesF;$i++){
	$ISmes=$Ob->reporteutilidadmes(1,$idrolpersona,$idconceptopago,'S','VENTA','N',$i,$_SESSION['IdSucursal'],$year);
	$registro = $ISmes->fetch();
	$pdf->Cell(35,8,$Ob->nombremes($i),1,0,'C');
	$pdf->Cell(55,8,number_format($registro[0],2),1,0,'C');
	$pdf->Cell(55,8,number_format($registro[1],2),1,0,'C');
	$pdf->Cell(55,8,(number_format($registro[0],2) - number_format($registro[1],2)),1,0,'C');
	
	$pdf->SetFont('Arial','',10);
	$pdf->Ln();
	
	}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>