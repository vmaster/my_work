<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
$idventa=$_GET['IdVenta'];
$objMovimiento=new clsMovimiento();
$rst=$objMovimiento->consultarventa(2,$idventa,NULL,NULL,5,NULL,NULL,NULL,NULL);
$guia = $rst->fetchObject();
?>
<script>
function vercuotas(idventa){
window.open('frm_cuotasventas.php?accion=VER&origen=DOC&idventa='+idventa,'_blank','width=380,height=630');
}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="">
<table width="917" height="490" border="0">
  <tr>
    <td height="117" colspan="2"><table width="910" height="101" border="0">
      <tr>
        <td width="638" height="57">
		<fieldset>
        <legend><strong></strong></legend>
		<table width="636" height="70" border="0">
          <tr>
            <td width="83" class="alignright">Fecha de emisión:</td>
            <td width="194"><?php echo substr(trim($guia->fecha),0,10);?></td>
            <td width="175" class="alignright">Fecha de inicio de traslado:</td>
            <td width="166"><?php echo substr(trim($guia->fechatraslado),0,10);?></td>
          </tr>
        </table>
		</fieldset>		</td>
        <td width="258">
		<fieldset>
        <legend><strong></strong></legend>
		
		<table width="263" border="0">
          <tr>
            <td colspan="2" class="titulo">Guia de remisión</td>
            </tr>
          <tr>
            <td colspan="2" class="titulo">REMITENTE</td>
            </tr>
          <tr>
            <td width="68" class="alignright">N&deg;</td>
            <td width="178"><div ><?php echo $guia->numero; ?></div></td>
          </tr>
        </table>
		</fieldset>		</td>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td width="455" height="69">
	<?php include_once('../negocio/cls_ubigeo.php');
	$objUbi=new clsUbigeo();
	$rst1=$objUbi->consultarlugar($guia->idlugarpartida,NULL,NULL);
	$rst2=$objUbi->consultarlugar($guia->idlugardestino,NULL,NULL);
	$partida=$rst1->fetchObject();
	$destino=$rst2->fetchObject();
	?>
		<fieldset>
        <legend>Punto de partida</legend>
	<table width="454" border="0">
      <tr>
        <td class="alignright">Dirección </td>
        <td colspan="5"><?php echo $partida->direccion;?></td>
      </tr>
      <tr>
        <td width="40" class="alignright">DIST.</td>
        <td width="120"><?php echo $partida->distrito;?></td>
        <td width="47" class="alignright">PROV.</td>
        <td width="86"><?php echo $partida->provincia;?></td>
        <td width="26" class="alignright">DEP.</td>
        <td width="109"><?php echo $partida->departamento;?></td>
      </tr>
    </table>
	</fieldset>	</td>
    <td width="452">
	<fieldset>
        <legend><strong>Punto de llegada </strong></legend>
	    <table width="450" border="0">
      <tr>
        <td class="alignright" >Dirección:</td>
        <td colspan="5"><?php echo $destino->direccion;?></td>
      </tr>
      <tr>
        <td width="40" class="alignright">DIST.</td>
        <td width="119"><?php echo $destino->distrito;?></td>
        <td width="47" class="alignright">PROV.</td>
        <td width="86"><?php echo $destino->provincia;?></td>
        <td width="35" class="alignright">DEP.</td>
        <td width="97"><?php echo $destino->departamento;?></td>
      </tr>
    </table>
	</fieldset>	</td>
  </tr>
  <tr>
    <td height="68">
	
    <?php include_once('../negocio/cls_persona.php');
	$objP=new clsPersona();
	$rst5=$objP->buscar($guia->idcliente,NULL,NULL);
	$partida=$rst5->fetchObject();
	$numerodoc=$partida->NroDoc;
	
	if(strlen($numerodoc)==11){
	$ruc=$numerodoc;
	$dni=' - - ';
	}else{
	$ruc=' - - ';
	$dni=$numerodoc;
	}
	?>
       
	<fieldset>
        <legend>Destinatario</legend>
	    <table width="448" border="0">
      <tr>
        <td colspan="4">	
		<strong>APELLIDOS Y NOMBRES / RAZ. SOCIAL: </strong></td>
        </tr>
      <tr>
        <td colspan="4">
          <div align="center"><?php echo $guia->acliente.' '.$guia->cliente;?></div></td>
        </tr>
      <tr>
        <td width="37" class="alignright">RUC:  </td>
        <td width="178"><?php echo $ruc;?></td>
        <td class="alignright">DOC. IDENT.: </td>
        <td><?php echo $dni;?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td width="78">&nbsp;</td>
        <td width="137">&nbsp;</td>
      </tr>
    </table>
	</fieldset>	</td>
    <td>
	<?php include_once('../negocio/cls_transportista.php');
	$objTrans=new clsTransportista();
	if($guia->idtransportista!='0'){
	$rst=$objTrans->consultarajax($guia->idtransportista,NULL,NULL);
	$transporte=$rst->fetchObject();
	$x='1';
	}else{
	$x='0';
	}
	?>
	<fieldset>
        <legend>Unidad de transporte / Condusctor</legend>
        <table width="447" border="0">
          <tr>
            <td width="164" class="alignright">Vehículo Marca: </td>
            <td width="116"><?php if($x!='0') echo $transporte->marcavehiculo;?></td>
            <td width="58" class="alignright">Placa N&deg;: </td>
            <td width="91"><?php if($x!='0') echo $transporte->numeroplaca;?></td>
          </tr>
          <tr>
            <td class="alignright">Certificado de inscripción N&deg;:</td>
            <td colspan="3"><?php if($x!='0') echo $transporte->numeroconstancia;?></td>
          </tr>
          <tr>
            <td class="alignright">Licencia de conducir N&deg;:</td>
            <td colspan="3"><?php if($x!='0') echo $transporte->licenciabrevete;?></td>
          </tr>
          <tr>
            <td class="alignright">Chofer:</td>
            <td colspan="3"><?php if($x!='0') echo $transporte->chofer;?></td>
          </tr>
        </table>
	</fieldset>	</td>
  </tr>
  <tr>
    <td height="92" colspan="2">
	<fieldset>
        <legend>Detalle</legend>
	<table width="905" border="1">

      <tr>
        <th width="64">Cant.</th>
        <th width="378">Descripción (Detalle de los Bienes)</th>
        <th width="221">Unidad de medida </th>
        <th width="224">Peso Unit.</th>
        <th width="224">Peso total</th>
      </tr>
	  <?php
	$detalleguia=$objMovimiento->consultardetalleventa($idventa);
	while($dato = $detalleguia->fetchObject()){
	?>
      <tr>
        <td><?php echo $dato->cantidad;?></td>
        <td><?php echo $dato->producto.' /'.$dato->categoria.'-'.$dato->marca.'-'.$dato->color.'-'.$dato->talla;?></td>
        <td align="center"><?php echo $dato->unidad;?></td>
        <td align="center"><?php echo number_format($dato->peso).' '.$dato->unidadpeso;?></td>
        <td align="center"><?php echo number_format(($dato->peso*$dato->cantidad)).' '.$dato->unidadpeso;?></td>
      </tr>
	  <?php } ?>
    </table>
	</fieldset>    </td>
  </tr>
  <tr>
    <td height="81">
	<fieldset>
        <legend>Transportista</legend>
	<table width="453" border="0">
      <tr>
        <td width="93" class="alignright">Nombre:</td>
        <td width="350"><?php if($x!='0') echo $transporte->transportista;?></td>
      </tr>
      <tr>
        <td class="alignright">Dirección: </td>
        <td><?php if($x!='0') echo $transporte->direccion;?></td>
      </tr>
      <tr>
        <td class="alignright">RUC:</td>
        <td><?php if($x!='0') echo $transporte->documento;?></td>
      </tr>
    </table>
	</fieldset>	</td>
    <td>
	  <?php 
	$rst3=$objMovimiento->consultarmotivotraslado($guia->idmotivotraslado);
	$motivo=$rst3->fetchObject();
	?>
	<fieldset>
        <legend>Motivo del traslado</legend>
	<table width="450" border="0">
      <tr>
        <td width="89">&nbsp;</td>
        <td width="351">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center"><?php echo $motivo->motivo?></div></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	</fieldset>	</td>
  </tr>
</table>
<p><button type="button" class="boton" onClick="javascript:window.open('list_guias.php','_self')">IR A GUIAS</button>   
  <button type="button" class="boton" onClick="javascript:window.open('list_ventas.php','_self')">IR A VENTAS</button></p>
</body>

