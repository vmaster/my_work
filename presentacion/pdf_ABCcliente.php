<?php
require_once("../datos/cado.php");
require_once("../negocio/cls_movimiento.php");
$idzona=$_GET['idzona'];
$idsector=$_GET['idsector'];
$fechainicio=$_GET['inicio'];
$fechafin=$_GET['final'];
$idtipodocumento=$_GET['tipodoc'];
$formapago=$_GET['formap'];
$moneda=$_GET['moneda'];

define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();
$objMovimiento = new clsMovimiento();
$rst1 = $objMovimiento->consultarrpt(2,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda);
while($dato1 = $rst1->fetchObject()){
		/*if($dato1->moneda=='D'){
		$totalcliente=number_format($dato1->totalcli*$cambio,2);
		}else{*/
		$totalcliente=$dato1->totalcli;
	//	}
	$total=$total+$totalcliente;
	if($dato1->moneda=='D'){
	$mon='$ ';
	}else{
	$mon='S/. ';
	}
	if($formapago=='A'){
	$formap="CONTADO";
	}elseif($formapago=='B'){
	$formap="CREDITO";
	}else
	$formap="TODOS";
	

	if($idtipodocumento=='1'){
	$doc="BOLETA VENTA";
	}elseif($formapago=='2'){
	$doc="FACTURA VENTA";
	}else
	$doc="TODOS";
}
$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(280,8,'ABC DE CLIENTES',0,1,'C');
$pdf->Ln();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(33,8,"Categoria A : Mayor o Igual al 70% del Total",0,0,'L');
$pdf->Ln();
$pdf->Cell(35,8,"Categoria B: Comprendida entre el 30% y 70% del Total",0,0,'L');
$pdf->Ln();
$pdf->Cell(34,8,"Categoria C: Menor o Igual 30% del Total",0,0,'L');
$pdf->Ln();
if($fechainicio!=""){ 
$pdf->Cell(35,8,"DESDE: ".$fechainicio,0,0,'L');
$pdf->Ln();}
if($fechafin!=""){
$pdf->Cell(34,8,"HASTA: ".$fechafin,0,0,'L');
$pdf->Ln();
}

$pdf->Cell(30,8,"TIPO DOC: ".$doc,0,0,'L');
$pdf->Ln();
$pdf->Cell(25,8,"FORMA PAGO: ".$formap,0,0,'L');
$pdf->Ln();
$pdf->Cell(34,8,"TOTAL: ".$mon.$total,0,0,'L');
$pdf->Ln();
$pdf->Cell(28,8,"TIPO CLIENTE",1,0,'C');
$pdf->Cell(80,8,"CLIENTE",1,0,'C');
$pdf->Cell(25,8,"NRO DOC",1,0,'C');
$pdf->Cell(28,8,"SECTOR",1,0,'C');
$pdf->Cell(28,8,"ZONA",1,0,'C');
$pdf->Cell(30,8,"TOTAL VENTAS",1,0,'C');
$pdf->Cell(25,8,"CATEGORIA",1,0,'C');
$pdf->Cell(30,8,"ULTIMA VENTA",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();

$objMovimiento = new clsMovimiento();
$rst = $objMovimiento->consultarrpt(2,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda);
while($dato=$rst->fetchObject()){

	
	if($dato->totalcli>=($total)*0.7){
	$cat='A';
	}else{if($dato->totalcli<=($total)*0.3){
	$cat='C';
	}else $cat='B';
	}

  $pdf->Cell(28,8,$dato->tipopersona,1,0,'C');
  $pdf->Cell(80,8,substr($dato->cliente.' '.$dato->acliente,0,34),1,0,'C');
  $pdf->Cell(25,8,$dato->nrodoc,1,0,'C');
  $pdf->Cell(28,8,$dato->sector,1,0,'C');
  $pdf->Cell(28,8,$dato->zona,1,0,'C');
  $pdf->Cell(30,8,$mon.' '.$dato->totalcli,1,0,'C');
  $pdf->Cell(25,8,$cat,1,0,'C');
  $pdf->Cell(30,8,$dato->fecha,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>