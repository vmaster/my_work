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
$objMovimiento = new clsMovimiento();
define('FPDF_FONTPATH','font/'); //directorio de las fuentes
include('../fpdf.php');
$pdf=new FPDF();
$rst1 = $objMovimiento->consultarrpt(1,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda);
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
	
	if($idtipodocumento=='3'){
	$doc="BOLETA VENTA";
	}elseif($formapago=='4'){
	$doc="FACTURA VENTA";
	}else
	$doc="TODOS";
	}

$pdf->AddPage('L','A4');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(280,8,'ABC DE PROVEEDORES',0,1,'C');
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
$pdf->Cell(34,8,"TOTAL: ".$mon.number_format($total,2),0,0,'L');
$pdf->Ln();
$pdf->Cell(33,8,"TIPO PROVEEDOR",1,0,'C');
$pdf->Cell(75,8,"PROVEEDOR",1,0,'C');
$pdf->Cell(25,8,"NRO DOC",1,0,'C');
$pdf->Cell(28,8,"SECTOR",1,0,'C');
$pdf->Cell(28,8,"ZONA",1,0,'C');
$pdf->Cell(33,8,"TOTAL COMPRAS",1,0,'C');
$pdf->Cell(25,8,"CATEGORIA",1,0,'C');
$pdf->Cell(31,8,"ULTIMA COMPRA",1,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();


$rst = $objMovimiento->consultarrpt(1,$idzona,$idsector,$fechainicio,$fechafin,$idtipodocumento,$formapago,$moneda);
while($dato=$rst->fetchObject()){

	
	if($dato->totalcli>=($total)*0.7){
	$cat='A';
	}else{if($dato->totalcli<=($total)*0.3){
	$cat='C';
	}else $cat='B';
	}

  $pdf->Cell(33,8,$dato->tipopersona,1,0,'C');
  $pdf->Cell(75,8,substr($dato->cliente.' '.$dato->acliente,0,28),1,0,'C');
  $pdf->Cell(25,8,$dato->nrodoc,1,0,'C');
  $pdf->Cell(28,8,substr($dato->sector,0,13),1,0,'C');
  $pdf->Cell(28,8,substr($dato->zona,0,13),1,0,'C');
  $pdf->Cell(33,8,$mon.' '.$dato->totalcli,1,0,'C');
  $pdf->Cell(25,8,$cat,1,0,'C');
  $pdf->Cell(31,8,$dato->fecha,1,0,'C');
  $pdf->Ln();
}

$pdf->SetAutoPageBreak(auto,2); 
$pdf->Output();
?>