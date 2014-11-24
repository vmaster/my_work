<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
require_once("../datos/cado.php");
require_once("../negocio/cls_movcaja.php");


$tipomov=$_GET['tipomov'];
$tipodoc=$_GET['tipodoc'];
$fechaI=$_GET['fechaI'];
$fechaF=$_GET['fechaF'];
$moneda=$_GET['moneda'];

if($fechaI<=$fechaF){
$FI=$fechaI;
$FF=$fechaF;
}else{
$FI=$fechaF;
$FF=$fechaI;
}


if($_GET['tipodoc']=='0'){
$doc='TODOS';
}else{
$Ob1 = new clsMovCaja();
$rst1=$Ob1->consultartipodoc($_GET['tipodoc']);
$doc=$rst1;
}

if($_GET['tipomov']=='0'){
$mov='TODOS';
}elseif($_GET['tipomov']=='1'){
$mov='COMPRAS';
}elseif($_GET['tipomov']=='2'){
$mov='VENTAS';
}

define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();

$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',18);
$pdf->Cell(280,10,'REPORTE DE PROYECCION DE COBROS Y PAGOS',0,1,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B',10);

$pdf->Cell(30,8,"FECHA DEL REPORTE: ".$_SESSION['FechaProceso'],0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"FECHAS DE LA CONSULTA: ".$FI."-/-".$FF,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"TIPO DE MOVIMIENTO: ".$mov,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"TIPO DOCUMENTO: ".$doc,0,0,'L');
$pdf->Ln();
$pdf->Ln();


$pdf->SetFont('Arial','B',8);
$pdf->Cell(50,8,"PERSONA",1,0,'C');
$pdf->Cell(10,8,"DOC.",1,0,'C');
$pdf->Cell(25,8,"NRO-DOC",1,0,'C');
$pdf->Cell(20,8,"FECHA",1,0,'C');
$pdf->Cell(20,8,"F-PROX-P",1,0,'C');
$pdf->Cell(8,8,"MDA.",1,0,'C');
$pdf->Cell(24,8,"MTO TOT.",1,0,'C');
$pdf->Cell(24,8,"INIC.",1,0,'C');
$pdf->Cell(24,8,"MTO PAG.",1,0,'C');
$pdf->Cell(24,8,"INT. PAG.",1,0,'C');
$pdf->Cell(24,8,"SDO MT.",1,0,'C');
$pdf->Cell(24,8,"SDO INT.",1,0,'C');
$pdf->SetFont('Arial','',8);
$pdf->Ln();


		$ObjMov = new clsMovCaja();		
		$consulta= $ObjMov->rptproyeccion($tipomov,$tipodoc,$FI,$FF,$moneda);

		$totalmontopagarS=0;
		$totalinterespagarS=0;
		$totalmontopagarD=0;
		$totalinterespagarD=0;
		$totalmontocobrarS=0;
		$totalinterescobrarS=0;
		$totalmontocobrarD=0;
		$totalinterescobrarD=0;


 while($registro=$consulta->fetch()){
		
		$pdf->Cell(50,8,substr($registro[6],0,25).".",1,0,'C');
		$pdf->Cell(10,8,$registro[1],1,0,'C');
		$pdf->Cell(25,8,$registro[2],1,0,'C');
		$pdf->Cell(20,8,$registro[3],1,0,'C');
		$pdf->Cell(20,8,$ObjMov->fecha_prox_pago($registro[0]),1,0,'C');
		$pdf->Cell(8,8,$registro[5],1,0,'C');
		$pdf->Cell(24,8,number_format($registro[4],2),1,0,'C');
		$pdf->Cell(24,8,number_format(($registro[4]-($ObjMov->monto_pagado($registro[0])+$ObjMov->saldo_monto($registro[0]))),2),1,0,'C');
		$pdf->Cell(24,8,number_format((($ObjMov->monto_pagado($registro[0]))+(($registro[4]-($ObjMov->monto_pagado($registro[0])+$ObjMov->saldo_monto($registro[0]))))),2),1,0,'C');
		$pdf->Cell(24,8,number_format($ObjMov->interes_pagado($registro[0]),2),1,0,'C');
		$pdf->Cell(24,8,number_format($ObjMov->saldo_monto($registro[0]),2),1,0,'C');
		$pdf->Cell(24,8,number_format($ObjMov->saldo_interes($registro[0]),2),1,0,'C');
		$pdf->SetFont('Arial','',8);
		$pdf->Ln();
		
		if($registro[10]==1 && $registro[5]=='S'){
		
		$totalmontopagarS=$totalmontopagarS+$ObjMov->saldo_monto($registro[0]);
		$totalinterespagarS=$totalinterespagarS+$ObjMov->saldo_interes($registro[0]);
		
		}elseif($registro[10]==1 && $registro[5]=='D'){
		
		$totalmontopagarD=$totalmontopagarD+$ObjMov->saldo_monto($registro[0]);
		$totalinterespagarD=$totalinterespagarD+$ObjMov->saldo_interes($registro[0]);
		
		}elseif($registro[10]==2 && $registro[5]=='S'){
		
		$totalmontocobrarS=$totalmontocobrarS+$ObjMov->saldo_monto($registro[0]);
		$totalinterescobrarS=$totalinterescobrarS+$ObjMov->saldo_interes($registro[0]);
		
		}elseif($registro[10]==2 && $registro[5]=='D'){
		
		$totalmontocobrarD=$totalmontocobrarD+$ObjMov->saldo_monto($registro[0]);
		$totalinterescobrarD=$totalinterescobrarD+$ObjMov->saldo_interes($registro[0]);
		
		}
		
		
		}
		$pdf->Ln();
		
		$pdf->AddPage('P','A4');
		$pdf->Ln();
		
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(190,8,"RESUMEN DE COBROS Y PAGOS",0,0,'C');
		
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('Arial','B',10);

$pdf->Cell(30,8,"FECHA DEL REPORTE: ".$_SESSION['FechaProceso'],0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"FECHAS DE LA CONSULTA: ".$FI."-/-".$FF,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"TIPO DE MOVIMIENTO: ".$mov,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"TIPO DOCUMENTO: ".$doc,0,0,'L');
$pdf->Ln();

		$pdf->Ln();
		$pdf->Ln();
	
		$pdf->SetFont('Arial','B',13);
		$pdf->Cell(70,12,"",0,0,'C');
		$pdf->Cell(60,12,"TOTAL SOLES",1,0,'C');
		$pdf->Cell(60,12,"TOTAL DOLARES",1,0,'C');
		$pdf->Ln();
		
		if($tipomov==0){
		
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(70,10,"MONTO POR PAGAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalmontopagarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalmontopagarD,2),1,0,'C');
		$pdf->Ln();
		
		$pdf->Cell(70,10,"INTERES POR PAGAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalinterespagarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalinterespagarD,2),1,0,'C');
		$pdf->Ln();
		
		$pdf->Cell(70,10,"MONTO POR COBRAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalmontocobrarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalmontocobrarD,2),1,0,'C');
		$pdf->Ln();
		
		$pdf->Cell(70,10,"INTERES POR COBRAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalinterescobrarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalinterescobrarD,2),1,0,'C');
		$pdf->Ln();
		
		}elseif($tipomov==1){
		
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(70,10,"MONTO POR PAGAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalmontopagarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalmontopagarD,2),1,0,'C');
		$pdf->Ln();
		
		$pdf->Cell(70,10,"INTERES POR PAGAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalinterespagarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalinterespagarD,2),1,0,'C');
		$pdf->Ln();
		
		}elseif($tipomov==2){
		
		$pdf->SetFont('Arial','',13);
		$pdf->Cell(70,10,"MONTO POR COBRAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalmontocobrarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalmontocobrarD,2),1,0,'C');
		$pdf->Ln();
		
		$pdf->Cell(70,10,"INTERES POR COBRAR:",1,0,'R');
		$pdf->Cell(60,10,"S/. ".number_format($totalinterescobrarS,2),1,0,'C');
		$pdf->Cell(60,10,"$. ".number_format($totalinterescobrarD,2),1,0,'C');
		$pdf->Ln();
		
		
		}




$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>