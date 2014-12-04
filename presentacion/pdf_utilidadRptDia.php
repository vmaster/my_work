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
$fecha=$_GET['fech'];
$idconceptopago=$_GET['idconceptopago'];
$moneda=$_GET['moneda'];

define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();

if($_GET['idtipodoc']=='0'){
$doc='TODOS';
}else{
$Ob1 = new clsMovCaja();
$rst1=$Ob1->consultartipodoc($_GET['idtipodoc']);
$doc=$rst1;
}

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
$pdf->Cell(280,10,'REPORTE DE UTILIDAD POR DIA',0,1,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B',10);

$pdf->Cell(30,8,"FECHA DEL REPORTE: ".$_SESSION['FechaProceso'],0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"FECHA DE LA CONSULTA: ".$fecha,0,0,'L');
$pdf->Ln();



if($moneda=='0'){
	$pdf->Cell(25,8,"MONEDA: SOLES",0,0,'L');
	$pdf->Ln();
	$pdf->Cell(40,8,"NRO DOC",1,0,'C');
	$pdf->Cell(35,8,"TOTAL P.VENTA",1,0,'C');
	$pdf->Cell(40,8,"TOTAL P.COMPRA",1,0,'C');
	$pdf->Cell(50,8,"CONCEPTO PAGO",1,0,'C');
	$pdf->Cell(60,8,"PERSONA",1,0,'C');
	$pdf->Cell(50,8,"COMENTARIO",1,0,'C');
	$pdf->SetFont('Arial','',9);
	$pdf->Ln();

	$Ob = new clsMovCaja();
	$cons= $Ob-> buscarmovimientosrpt(
			$idtipodocumento,$idrolpersona,$idconceptopago,'S','VENTA','N',$fecha,$_SESSION['IdSucursal']);

	$suma=0;
	while($registro=$cons->fetch()){

		$pdf->Cell(40,8,$registro[0],1,0,'C');

		$suma=$suma+$registro[3];
		$pdf->Cell(35,8,number_format($registro[3],2),1,0,'C');
		$ObjMovCaja = new clsMovCaja();
		$cons_precio_compra = $ObjMovCaja->consultarDetPrecCompPorMovimiento($registro[8]);
		$precio_compra = $cons_precio_compra->fetch();
		$pdf->Cell(40,8,number_format($precio_compra[0],2),1,0,'C');


		$pdf->Cell(50,8,substr($registro[6],0,24).".",1,0,'C');
		$pdf->Cell(60,8,substr($registro[4]." ".$registro[5],0,27).".",1,0,'C');
		$pdf->Cell(50,8,substr($registro[7],0,24).".",1,0,'C');
		$pdf->Ln();
		$suma_precio_compra= $suma_precio_compra + $precio_compra[0];
	}

	$pdf->Cell(25,8,"SUMA PRECIO DE VENTA S/. ".number_format($suma,2),0,0,'L');
	$pdf->Ln();
	$pdf->Cell(25,8,"SUMA PRECIO DE COMPRA S/. ".number_format($suma_precio_compra,2),0,0,'L');
	$pdf->Ln();
	$pdf->Cell(25,8,"UTILIDAD S/. ".(number_format($suma,2) - number_format($suma_precio_compra,2)),0,0,'L');
	$pdf->Ln();



}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();



?>