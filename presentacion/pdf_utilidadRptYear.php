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
$yearinicio=$_GET['yearI'];
$yearfin=$_GET['yearF'];
$idconceptopago=$_GET['idconceptopago'];
$moneda=$_GET['moneda'];


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
$pdf->Cell(280,10,'REPORTE DE UTILIDAD POR AÑO',0,1,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B',11);
$pdf->Cell(30,8,"FECHA DEL REPORTE: ".$_SESSION['FechaProceso'],0,0,'L');
$pdf->Ln();
//$pdf->Cell(30,8,"CONCEPTO DE PAGO: ".$cp,0,0,'L');
//$pdf->Ln();
//$pdf->Cell(30,8,"ROL PERSONA: ".$rol,0,0,'L');
//$pdf->Ln();

$pdf->SetFont('Arial','B',11);
$pdf->Cell(35,8,"AÑO",1,0,'C');
$pdf->Cell(50,8,"VENTAS POR AÑO",1,0,'C');
$pdf->Cell(50,8,"COMPRAS POR AÑO",1,0,'C');
$pdf->Cell(50,8,"UTILIDAD POR AÑO",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();

if($yearinicio<=$yearfin){
$num=$yearfin-$yearinicio;
$yearI=$yearinicio;
$yearF=$yearfin;
}else{
$num=$yearinicio-$yearfin;
$yearI=$yearfin;
$yearF=$yearinicio;
}

	$Ob = new clsMovCaja();		
	
	$IS=0;
	$ID=0;
	$ES=0;
	$ED=0;
	
	for($i=$yearI;$i<=$yearF;$i++){
	
	$ISano=$Ob->reporteutilidadyear(1,$idrolpersona,$idconceptopago,'S','VENTA','N',$i,$_SESSION['IdSucursal'],$year);
	$registro = $ISano->fetch();
	
	$pdf->Cell(35,8,$i,1,0,'C');
	$pdf->Cell(50,8,number_format($registro[0],2),1,0,'C');
	$pdf->Cell(50,8,number_format($registro[1],2),1,0,'C');
	$pdf->Cell(50,8,(number_format($registro[0],2) - number_format($registro[1],2)),1,0,'C');
	
	$pdf->SetFont('Arial','',10);
	$pdf->Ln();
	
	/*$IS=$IS+$Ob->reportesyear(10,$idrolpersona,$idconceptopago,'S','CAJA','N',$i,$_SESSION['IdSucursal'],$year);
	$ID=$ID+$Ob->reportesyear(10,$idrolpersona,$idconceptopago,'D','CAJA','N',$i,$_SESSION['IdSucursal'],$year);
	$ES=$ES+$Ob->reportesyear(11,$idrolpersona,$idconceptopago,'S','CAJA','N',$i,$_SESSION['IdSucursal'],$year);
	$ED=$ED+$Ob->reportesyear(11,$idrolpersona,$idconceptopago,'D','CAJA','N',$i,$_SESSION['IdSucursal'],$year);*/
	}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>