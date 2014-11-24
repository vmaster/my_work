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
$pdf->Cell(280,10,'REPORTE DE MOVIMIENTO DE CAJA POR DIA',0,1,'C');
$pdf->Ln();

$pdf->SetFont('Arial','B',10);

$pdf->Cell(30,8,"FECHA DEL REPORTE: ".$_SESSION['FechaProceso'],0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"FECHA DE LA CONSULTA: ".$fecha,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"TIPO DOCUMENTO: ".$doc,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"CONCEPTO DE PAGO: ".$cp,0,0,'L');
$pdf->Ln();
$pdf->Cell(30,8,"ROL PERSONA: ".$rol,0,0,'L');
$pdf->Ln();



if($moneda=='0'){
$pdf->Cell(25,8,"MONEDA: SOLES",0,0,'L');
$pdf->Ln();
$pdf->Cell(40,8,"NRO DOC",1,0,'C');
$pdf->Cell(25,8,"INGRESO",1,0,'C');
$pdf->Cell(25,8,"EGRESO",1,0,'C');
$pdf->Cell(30,8,"SALDO",1,0,'C');
$pdf->Cell(50,8,"CONCEPTO PAGO",1,0,'C');
$pdf->Cell(60,8,"PERSONA",1,0,'C');
$pdf->Cell(50,8,"COMENTARIO",1,0,'C');
$pdf->SetFont('Arial','',9);
$pdf->Ln();

$Ob = new clsMovCaja();		
$cons= $Ob-> buscarmovimientosrpt(
$idtipodocumento,$idrolpersona,$idconceptopago,'S','CAJA','N',$fecha,$_SESSION['IdSucursal']);

$suma=0;
while($registro=$cons->fetch()){		
		
		$pdf->Cell(40,8,$registro[0],1,0,'C');
		
		if($registro[1]=='INGRESO'){
		
		$suma=$suma+$registro[3];
		$pdf->Cell(25,8,number_format($registro[3],2),1,0,'C');
        $pdf->Cell(25,8,0.00,1,0,'C');
		$pdf->Cell(30,8,number_format($suma,2),1,0,'C');
		
		}else if($registro[1]=='EGRESO'){
		
		$suma=$suma-$registro[3];
		$pdf->Cell(25,8,0.00,1,0,'C');
        $pdf->Cell(25,8,number_format($registro[3],2),1,0,'C');
		$pdf->Cell(30,8,number_format($suma,2),1,0,'C');
		
		}
		
		$pdf->Cell(50,8,substr($registro[6],0,24).".",1,0,'C');
		$pdf->Cell(60,8,substr($registro[4]." ".$registro[5],0,27).".",1,0,'C');
		$pdf->Cell(50,8,substr($registro[7],0,24).".",1,0,'C');
		$pdf->Ln();
}

$pdf->Cell(25,8,"SALDO S/. ".number_format($suma,2),0,0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,8,"MONEDA: DOLARES",0,0,'L');
$pdf->Ln();

$pdf->Cell(40,8,"NRO DOC",1,0,'C');
$pdf->Cell(25,8,"INGRESO",1,0,'C');
$pdf->Cell(25,8,"EGRESO",1,0,'C');
$pdf->Cell(30,8,"SALDO",1,0,'C');
$pdf->Cell(50,8,"CONCEPTO PAGO",1,0,'C');
$pdf->Cell(60,8,"PERSONA",1,0,'C');
$pdf->Cell(50,8,"COMENTARIO",1,0,'C');
$pdf->SetFont('Arial','',9);
$pdf->Ln();

$Ob2 = new clsMovCaja();		
$cons2= $Ob2-> buscarmovimientosrpt(
$idtipodocumento,$idrolpersona,$idconceptopago,'D','CAJA','N',$fecha,$_SESSION['IdSucursal']);

$suma2=0;
while($registro2=$cons2->fetch()){		
		
		$pdf->Cell(40,8,$registro2[0],1,0,'C');
		
		if($registro2[1]=='INGRESO'){
		
		$suma2=$suma2+$registro2[3];
		$pdf->Cell(25,8,number_format($registro2[3],2),1,0,'C');
        $pdf->Cell(25,8,0.00,1,0,'C');
		$pdf->Cell(30,8,number_format($suma2,2),1,0,'C');
		
		}else if($registro2[1]=='EGRESO'){
		
		$suma2=$suma2-$registro2[3];
		$pdf->Cell(25,8,0.00,1,0,'C');
        $pdf->Cell(25,8,$registro2[3],1,0,'C');
		$pdf->Cell(30,8,number_format($suma2,2),1,0,'C');
		
		}
		
		$pdf->Cell(50,8,substr($registro2[6],0,24).".",1,0,'C');
		$pdf->Cell(60,8,substr($registro2[4]." ".$registro2[5],0,27).".",1,0,'C');
		$pdf->Cell(50,8,substr($registro2[7],0,24).".",1,0,'C');
		$pdf->Ln();
}

$pdf->Cell(25,8,"SALDO $. ".number_format($suma2,2),0,0,'L');
$pdf->Ln();

}elseif($moneda=='S' || $moneda=='D'){

if($moneda=='S'){
$mon='SOLES';
$ico="S/.";
}else if($moneda=='D'){
$mon='DOLARES';
$ico="$.";
}

$pdf->Cell(25,8,"MONEDA: ".$mon."",0,0,'L');
$pdf->Ln();
$pdf->Cell(40,8,"NRO DOC",1,0,'C');
$pdf->Cell(25,8,"INGRESO",1,0,'C');
$pdf->Cell(25,8,"EGRESO",1,0,'C');
$pdf->Cell(30,8,"SALDO",1,0,'C');
$pdf->Cell(50,8,"CONCEPTO PAGO",1,0,'C');
$pdf->Cell(60,8,"PERSONA",1,0,'C');
$pdf->Cell(50,8,"COMENTARIO",1,0,'C');
$pdf->SetFont('Arial','',9);
$pdf->Ln();

$Ob = new clsMovCaja();		
$cons= $Ob-> buscarmovimientosrpt(
$idtipodocumento,$idrolpersona,$idconceptopago,$moneda,'CAJA','N',$fecha,$_SESSION['IdSucursal']);

$suma=0;
while($registro=$cons->fetch()){		
		
		$pdf->Cell(40,8,$registro[0],1,0,'C');
		
		if($registro[1]=='INGRESO'){
		
		$suma=$suma+$registro[3];
		$pdf->Cell(25,8,number_format($registro[3],2),1,0,'C');
        $pdf->Cell(25,8,0.00,1,0,'C');
		$pdf->Cell(30,8,number_format($suma,2),1,0,'C');
		
		}else if($registro[1]=='EGRESO'){
		
		$suma=$suma-$registro[3];
		$pdf->Cell(25,8,0.00,1,0,'C');
        $pdf->Cell(25,8,number_format($registro[3],2),1,0,'C');
		$pdf->Cell(30,8,number_format($suma,2),1,0,'C');
		
		}
		
		$pdf->Cell(50,8,substr($registro[6],0,24).".",1,0,'C');
		$pdf->Cell(60,8,substr($registro[4]." ".$registro[5],0,27).".",1,0,'C');
		$pdf->Cell(50,8,substr($registro[7],0,24).".",1,0,'C');
		$pdf->Ln();
}
$pdf->Cell(25,8,"SALDO ".$ico." ".number_format($suma,2),0,0,'L');
$pdf->Ln();


}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();



?>