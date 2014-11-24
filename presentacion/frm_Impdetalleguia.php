<?php 
session_start();
if(!isset($_SESSION['Usuario'])){header("location: ../presentacion/login.php?error=1");}

require("../datos/cado.php");
require("../negocio/cls_movimiento.php");
$idventa=$_GET['IdVenta'];
$objMovimiento=new clsMovimiento();
$rst=$objMovimiento->consultarventa(2,$idventa,NULL,NULL,5,NULL,NULL,NULL,NULL);
$guia = $rst->fetchObject();

 require('../negocio/cls_ubigeo.php');
	$objUbi=new clsUbigeo();
	$rst1=$objUbi->consultarlugar($guia->idlugarpartida,NULL,NULL);
	$rst2=$objUbi->consultarlugar($guia->idlugardestino,NULL,NULL);
	$partida=$rst1->fetchObject();
	$destino=$rst2->fetchObject();
	
	require('../negocio/cls_persona.php');
	$objP=new clsPersona();
	$rst5=$objP->buscar($guia->idcliente,NULL,NULL);
	$partida2=$rst5->fetchObject();
	$numerodoc=$partida2->NroDoc;
	
	if(strlen($numerodoc)==11){
	$ruc=$numerodoc;
	$dni=' - - ';
	}else{
	$ruc=' - - ';
	$dni=$numerodoc;
	}
	
	include_once('../negocio/cls_transportista.php');
	$objTrans=new clsTransportista();
	if($guia->idtransportista!='0'){
	$rst=$objTrans->consultarajax($guia->idtransportista,NULL,NULL);
	$transporte=$rst->fetchObject();
	$x='1';
	}else{
	$x='0';
	}
?>

<style type="text/css">
<!--
.Estilo1 {
	font-size: 12px;
	font-weight: bold;
	
}

-->
</style>
<style type="text/css">

table.botonera {
    margin: auto;
    border-spacing: 0px;
    border-collapse: collapse;
    empty-cells: show;
    width: auto;
    background: url(../imagenes/background-botoneragral.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera table {
    border-spacing: 0px;
    border-collapse: collapse;
    empty-cells: show;
    width: 100%;
	background-color: #FFFFFF;
}

table.botonera td.puntos {
    height: 3px;
    background: url(../imagenes/background-plkpuntos-hor.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera td.frameTL {
    width: 6px;
    height: 6px;
    padding: 0;
    background: url(../imagenes/esq-formato-interior-izq-sup.gif) no-repeat left top;
	background-color: #FFFFFF;
}

table.botonera td.frameTC {
    padding: 0;
    background: url(../imagenes/background-formato-interior-sup.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera td.frameTR {
    width: 6px;
    height: 6px;
    padding: 0;
    background: url(../imagenes/esq-formato-interior-der-sup.gif) no-repeat right top;
}

table.botonera td.frameBL {
	width: 3px;
	height: 3px;
	padding: 0;
	background: url(../imagenes/esq-formato-interior-izq-inf.gif) no-repeat left bottom;
	background-color: #FFFFFF;
}

table.botonera td.frameBC {
    padding: 0;
    background: url(../imagenes/background-formato-interior-inf.gif) repeat-x;
	background-color: #FFFFFF;
}

table.botonera td.frameBR {
    width: 6px;
    height: 6px;
    padding: 0;
    background: url(../imagenes/esq-formato-interior-der-inf.gif) no-repeat right bottom;
	background-color: #FFFFFF;
}

table.botonera td.frameCL {
    padding: 0;
    background: url(../imagenes/background-formato-interior-izq.gif) repeat-y;
	background-color: #FFFFFF;
}


table.botonera td.frameC {
    padding: 0;
    
	background-color: #FFFFFF;
}

table.botonera td.frameCR {
    padding: 0;
    background: url(../imagenes/background-formato-interior-der.gif) repeat-y;
	background-color: #FFFFFF;
}

table.botonera td.linkItem {
    height: 25px;
	background-color: #FFFFFF;
}

table.botonera a:link, table.botonera a:active, table.botonera a:visited {
    color: #3F4C69;
    text-decoration: none;
	background-color: #FFFFFF;
}

table.botonera a:hover { 
    color: #C82E28;
    text-decoration: underline;
	background-color: #FFFFFF;
}

.Estilo2 {
	font-size: 24px;
	font-weight: bold;
}
.Estilo3 {font-size: 12px}
.Estilo5 {font-size: 9px}
.Estilo10 {font-size: 10px}
.Estilo11 {font-size: 14px}
</style>
<script>

	function imprimir(que) {
	var ventana = window.open("", '', '');
	var contenido = "<html><body onload='window.print();window.close();'>";
	contenido = contenido + document.getElementById(que).innerHTML + "</body></html>";
	ventana.document.open();
	ventana.document.write(contenido);
	ventana.document.close();
}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body onLoad="">
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
  <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 
<div id="">
<table width="789" height="679" border="0">
  <tr>
    <td height="172" colspan="2"><table width="787" height="170" border="0">
      <tr>
        <td width="512" height="166" style>
		
		<table width="515" height="163" border="0">
          <tr>
            <td height="54">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="64">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="254" height="37"><fieldset class="Estilo3">
              <strong>FECHA DE <BR>EMISION:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   </strong>
                </fieldset></td>
            <td width="248"><fieldset><strong><span class="Estilo3">FECHA DE INICIO <BR>
            DEL TRASLADO: &nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>        
            </fieldset>      </td>
            </tr>
        </table>		</td>
        <td width="265">
		<fieldset>
        <legend><strong></strong></legend>
		
		<table width="276" height="156" border="0">
          <tr>
            <td colspan="2"><BR><div align="center" class="Estilo2"><strong>GUIA DE REMISION </strong></div></td>
            </tr>
          <tr>
            <td colspan="2"><div align="center" class="Estilo2"><strong>REMITENTE</strong></div></td>
            </tr>
          <tr>
            <td width="127" height="38" class="Estilo1"><div align="right">
              <h4><strong>N&deg;</strong></h4>
            </div></td>
            <td width="139"  class="Estilo1"><div >
              <h4>&nbsp;</h4>
            </div></td>
          </tr>
        </table>
		</fieldset>		</td>
      </tr>
    </table>	</td>
  </tr>
  <tr>
    <td width="389" height="69"><fieldset>
        <table width="384" height="65" border="0">
      <tr>
        <td colspan="6" bgcolor="#CCCCCC" class="frameBC"><center>
          <span class="Estilo1">PUNTO DE PARTIDA</span>
        </center></td>
      </tr>
      <tr>
        <td colspan="6" class="frameBC"><strong class="frameC Estilo3">DIRECCION:  </strong></td>
        </tr>
      <tr>
        <td width="34" height="21"><span class="Estilo3"><strong>DIST.</strong></span></td>
        <td width="86"><span class="Estilo3"></span></td>
        <td width="40"><span class="Estilo3"><strong>PROV.</strong></span></td>
        <td width="82"><span class="Estilo3"></span></td>
        <td width="26"><span class="Estilo3"><strong>DEP.</strong></span></td>
        <td width="90"><span class="Estilo3"></span></td>
      </tr>
    </table>
	</fieldset>	</td>
    <td width="390">
	<fieldset>
       <table width="393" border="0">
      <tr>
        <td colspan="6" bgcolor="#CCCCCC"><div align="center"><span class="Estilo3"><strong>PUNTO DE LLEGADA</strong></span></div></td>
      </tr>
      <tr>
        <td colspan="6"><span class="Estilo1">DIRECCION:  </span></td>
        </tr>
      <tr>
        <td width="40" height="27"><span class="Estilo1">DIST.</span></td>
        <td width="88">&nbsp;</td>
        <td width="48"><span class="Estilo1">PROV.</span></td>
        <td width="91">&nbsp;</td>
        <td width="36"><span class="Estilo1">DEP.</span></td>
        <td width="78">&nbsp;</td>
      </tr>
    </table>
	</fieldset>	</td>
  </tr>
  <tr>
    <td height="68"><fieldset>
        <table width="386" height="98" border="0">
      <tr>
        <td colspan="3" bgcolor="#CCCCCC"><div align="center" class="Estilo3"><strong>DESTINATARIO</strong></div></td>
      </tr>
      
      <tr>
        <td colspan="3">
          <div align="center" class="Estilo3">
            <div align="left"><strong>APELLIDOS Y NOMBRES / RAZ. SOCIAL: </strong></div>
          </div></td>
        </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
        </tr>
      <tr>
        <td width="139"><span class="Estilo3"><strong>RUC:  </strong></span></td>
        <td width="237"><span class="Estilo3"><strong>DOC. IDENT.:</strong></span></td>
        </tr>
    </table>
	</fieldset>	</td>
    <td><fieldset>
       <table width="394" border="0">
      <tr class="frameBC">
        <td colspan="2" bgcolor="#CCCCCC"><div align="center" class="Estilo3"><span class="Estilo3"><strong>UNIDAD DE TRASNPORTE / CONDUCTOR</strong></span></div></td>
        </tr>
      <tr class="frameBC">
        <td width="223"><span class="Estilo3"><strong>VEHICULO MARCA:</strong></span></td>
        <td width="161"><span class="Estilo3"><strong>PLACA N&deg;:</strong></span></td>
        </tr>
      <tr>        <td colspan="3" class="frameBC"><span class="Estilo3"><strong>CERTIFICADO DE INCRIPCION N&deg;: </strong></span></td>
		</tr>
      <tr class="frameBC">
        <td colspan="3"><span class="Estilo3"><strong>LICENCIA DE CONDUCIR N&deg;: </strong></span></td>
        </tr>
      <tr class="frameBC">
        <td colspan="3"><span class="Estilo3"><strong>CHOFER:</strong></span></td>
        </tr>
    </table>
	</fieldset>	</td>
  </tr>
  <tr>
    <td height="39" colspan="2">
	<fieldset>
 <table width="789" border="0">

      <tr bgcolor="#CCCCCC">
        <th width="52"><span class="Estilo3">CANT.</span></th>
        <th width="387"><span class="Estilo3">DESCRIPCION (Detalle de los Bienes)</span></th>
        <th width="95"><span class="Estilo3">UNIDAD DE MEDIDA </span></th>
        <th width="68"><span class="Estilo3">PESO UNIT.</span></th>
        <th width="82"><span class="Estilo3">PESO TOTAL</span></th>
		<th width="79"><span class="Estilo5">COSTO MINIMO DEL TRASLADO</span></th>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	   <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
	  <tr height="20">
        <td height="20" ></td>
        <td height="20" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
        <td height="20" align="center" ></td>
		<td height="20" align="center" ></td>
      </tr>
	   <tr height="20">
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
        <td height="20" align="center" bgcolor="#CCCCCC" ></td>
		<td height="20" align="center" bgcolor="#CCCCCC" ></td>
      </tr>
    </table>
	</fieldset>    </td>
  </tr>
  <tr>
    <td height="39">
	<fieldset>
	<table width="390" border="0">
      <tr>
        <td colspan="2" bgcolor="#CCCCCC"><div align="center" class="Estilo3"><strong>TRANSPORTISTA</strong></div></td>
        </tr>
      <tr>
        <td width="93"><span class="Estilo3"><strong>NOMBRE: </strong></span></td>
        <td width="350"></td>
      </tr>
      <tr>
        <td><span class="Estilo3"><strong>DIRECCION: </strong></span></td>
        <td></td>
      </tr>
      <tr>
        <td><span class="Estilo3"><strong>RUC: </strong></span></td>
        <td></td>
      </tr>
    </table>
	</fieldset></td>
    <td rowspan="3"><fieldset>
       	<table width="395" border="0">
      <tr>
        <td colspan="4" bgcolor="#CCCCCC"><div align="center"><span class="Estilo3"><strong>MOTIVO DEL TRASLADO</strong></span></div></td>
        </tr>
      <tr>
        <td width="23"><input name="checkbox" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td width="209" class="Estilo5">VENTA</td>
        <td width="24"><input name="checkbox10" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td width="121"><span class="Estilo5">TRASLADO ZONA PRIM.</span></td>
      </tr>
      <tr>
        <td><input name="checkbox2" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">VENTA SUJETA A CONFIRMACION DEL COMPRADOR</span></td>
        <td><input name="checkbox11" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">IMPORTACION</span></td>
      </tr>
      <tr>
        <td><input name="checkbox3" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">COMPRA</span></td>
        <td><input name="checkbox12" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">EXPORTACION</span></td>
      </tr>
      <tr>
        <td><input name="checkbox4" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">CONCIGNACION</span></td>
        <td><input name="checkbox13" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">EXHIBICION</span></td>
      </tr>
      <tr>
        <td><input name="checkbox5" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">DEVOLUCION</span></td>
        <td><input name="checkbox14" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">DEMOTRACION</span></td>
      </tr>
      <tr>
        <td><input name="checkbox6" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA</span></td>
        <td><input name="checkbox15" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">OTROS</span></td>
      </tr>
      <tr>
        <td><input name="checkbox7" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">TRASLADO DE BIENES PARA TRANSFORMAS</span></td>
        <td><span class="Estilo5"></span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><input name="checkbox8" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">RECOJO DE BIENES PARA TRASFORMAR</span></td>
        <td><span class="Estilo5"></span></td>
        <td></td>
        </tr>
      <tr>
        <td><input name="checkbox9" type="checkbox" style="border:#999999" value="checkbox"></td>
        <td><span class="Estilo5">TRASLADO POR EMISOR INTINERANTE DE COMPROBANTES DE PAGO </span></td>
        <td><span class="Estilo5"></span></td>
        <td>&nbsp;</td>
        </tr>
    </table>
	</fieldset>	</td>
  </tr>
  <tr>
    <td height="40">
	
	<fieldset>
	<table width="391" border="0">
      <tr>
        <td colspan="4" bgcolor="#CCCCCC">
		<div align="center" class="Estilo3"><strong>COMPROBANTE DE PAGO </strong></div>		</td>
      </tr>
      <tr>
        <td width="38" height="35"><span class="Estilo3"><strong>TIPO: </strong></span></td>
        <td width="116"></td>
        <td width="17"><span class="Estilo3"><strong>N&deg;</strong></span></td>
        <td width="130"></td>
      </tr>
    </table>
	</fieldset>	
	</td>
  </tr>
  <tr>
    <td height="40">
	<fieldset>
	<table width="391">
	<tr><td><div align="center">___________________</div></td></tr>
	<tr><td height="21">                   <div align="center"></div></td>
	</tr>
	<tr><td><div align="center">_____________________________</div></td></tr>
	<tr><td><div align="center"><span class="Estilo10">CONFORMIDAD DEL CLIENTE</span></div></td>
	</tr>
	</table>
	</fieldset>
	</td>
  </tr>
</table>
<div id='guia' style="position:absolute; left: 11px; top: 36px; width: 700px; height: 495px; z-index:4"> <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div id="div2">
      <table width="789" height="1025" border="0">
        <tr>
          <td height="172" colspan="2"><table width="787" height="170" border="0">
              <tr>
                <td width="512" height="166" style><table width="515" height="163" border="0">
                    <tr>
                      <td colspan="2"><div id='cabecera' style="position:absolute; left: 30px; top: 53px; width: 700px; height: 495px; z-index:1"   >
                        <br><br><div style="width:480" align="center"><?php echo $_SESSION['Sucursal'];?></div><img src="../imagenes/cabeceraGuia.PNG">
                        </div></td>
                    </tr>
                    
                    <tr>
                      <td width="254" height="37">
                        
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="Estilo11"><?php echo substr(trim($guia->fecha),0,10);?>
                      </span>                     </td>
                      <td width="248">
                        <span class="Estilo11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo substr(trim($guia->fechatraslado),0,10);?></span></td>
                    </tr>
                </table></td>
                <td width="265"><fieldset>
                  <legend><strong></strong></legend>
                  <table width="276" height="156" border="0">
                    <tr>
                      <td colspan="2"><BR>
                          <div align="center" class="Estilo2"></div></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div align="center" class="Estilo2">
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                      </div></td>
                    </tr>
                    <tr>
                      <td width="86" height="38" class="Estilo1"><div align="right">
                          <h4><?php echo substr($guia->numero,0,3); ?></h4>
                      </div></td>
                      <td width="180"  class="Estilo1"><div >
                          <h4>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo substr($guia->numero,4,6); ?></h4>
                      </div></td>
                    </tr>
                  </table>
                </fieldset></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td width="389" height="69">
            <table width="397" height="85" border="0">
              
              <tr>
                <td height="26" colspan="6" class="Estilo3">&nbsp;</td>
              </tr>
              <tr>
                <td height="26" colspan="6" class="Estilo3">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $partida->direccion;?></td>
              </tr>
              <tr>
                <td width="34" height="25">&nbsp;</td>
                <td width="86"><span class="Estilo3"><?php echo $partida->distrito;?></span></td>
                <td width="40">&nbsp;</td>
                <td width="82"><span class="Estilo3"><?php echo $partida->provincia;?></span></td>
                <td width="26">&nbsp;</td>
                <td width="90"><span class="Estilo3">&nbsp;&nbsp;&nbsp;<?php echo $partida->departamento;?></span></td>
              </tr>
          </table>          </td>
          <td width="390">
            <table width="393" height="84" border="0">
              <tr>
                <td colspan="6" ><div align="center"></div></td>
              </tr>
              <tr>
                <td colspan="6"><span class="Estilo3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $destino->direccion;?></span></td>
              </tr>
              <tr>
                <td width="40" height="27">&nbsp;</td>
                <td width="82"><span class="Estilo3"><?php echo $destino->distrito;?></span></td>
                <td width="58">&nbsp;</td>
                <td width="68"><span class="Estilo3"><?php echo $destino->provincia;?></span></td>
                <td width="41">&nbsp;</td>
                <td width="78"><span class="Estilo3"><?php echo '  '.$destino->departamento;?></span></td>
              </tr>
            </table>         </td>
        </tr>
        <tr>
          <td height="151"><table width="386" height="73" border="0">
            <tr>
              <td colspan="3"></td>
            </tr>
            <tr>
              <td colspan="3"><center>
                <?php echo $guia->acliente.' '.$guia->cliente;?>
              </center></td>
            </tr>
            <tr>
              <td width="139" height="9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $ruc;?></td>
              <td width="237">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $dni;?></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td width="237">&nbsp;</td>
            </tr>
          </table></td>
          <td><table width="401" border="0">
              <tr class="frameBC">
                <td colspan="2"></td>
              </tr>
              <tr class="frameBC">
                <td width="223"><span class="Estilo3">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if($x!='0') echo $transporte->marcavehiculo;?>
                </span></td>
                <td width="161"><span class="Estilo3">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if($x!='0') echo $transporte->numeroplaca;?>
                </span></td>
              </tr>
              <tr>
                <td colspan="3" class="frameBC Estilo3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($x!='0') echo $transporte->numeroconstancia;?></td>
              </tr>
              <tr class="frameBC">
                <td colspan="3"><span class="Estilo3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if($x!='0') echo $transporte->licenciabrevete;?>
                </span></td>
              </tr>
              <tr class="frameBC">
                <td height="18" colspan="3"><span class="Estilo3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php if($x!='0') echo $transporte->chofer;?>
                </span></td>
              </tr>
              <tr class="frameBC">
                <td height="29" colspan="3">&nbsp;</td>
              </tr>
          </table>          </td>
        </tr>
        <tr>
          <td height="300" colspan="2">
            <table width="789" height="50" border="0">
			<?php
	$detalleguia=$objMovimiento->consultardetalleventa($idventa);
	$num=$detalleguia->rowCount();
	while($dato = $detalleguia->fetchObject()){
	?>
              <tr >
                <th width="52" height="23"><span class="Estilo3"><?php echo $dato->cantidad;?></span></th>
                <th width="387"><span class="Estilo3"><?php echo $dato->producto.' /'.$dato->categoria.'_'.$dato->marca?></span></th>
                <th width="95"><span class="Estilo3"><?php echo $dato->unidad;?></span></span></th>
                <th width="68"><span class="Estilo3"><?php echo number_format($dato->peso).' '.$dato->unidadpeso;?></span></th>
                <th width="82"><span class="Estilo3"><?php echo number_format(($dato->peso*$dato->cantidad)).' '.$dato->unidadpeso;?></span></th>
                <th width="79"></th>
              </tr>
	<?php } ?>
	<?php for($i=0;$i<(15-$num);$i++){?>
              <tr height="22">
                <td height="21" ></td>
                <td height="21" ></td>
                <td height="21" align="center" ></td>
                <td height="21" align="center" ></td>
                <td height="21" align="center" ></td>
                <td height="21" align="center" ></td>
              </tr>
	<?php } ?>
          </table>          </td>
        </tr>
        <tr>
          <td height="39">
            <table width="390" border="0">
              <tr>
                <td colspan="2" ><BR><BR><BR></td>
              </tr>
              <tr>
                <td width="93"></td>
                <td width="350"><BR><BR><?php if($x!='0') echo $transporte->transportista.'.';?></td>
              </tr>
              <tr>
                <td></td>
                <td><?php if($x!='0') echo $transporte->direccion.'.';?></td>
              </tr>
              <tr>
                <td></td>
                <td><?php if($x!='0') echo $transporte->documento.'.';?></td>
              </tr>
            </table>          </td>
          <td rowspan="3">
		  <BR><BR><BR><BR>
            <table width="395" height="206" border="0">
              <tr>
                <td colspan="4" ></td>
              </tr>
              <tr>
                <td width="37">
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='1') echo 'X'?>
                  </div></td>
                <td width="195" class="Estilo5">&nbsp;</td>
                <td width="24"><?php if($guia->idmotivotraslado=='10') echo 'X'?></td>
                <td width="121"></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='2') echo 'X'?>
                  </div></td>
                <td></td>
                <td><?php if($guia->idmotivotraslado=='11') echo 'X'?></td>
                <td></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='3') echo 'X'?>
                  </div></td>
                <td></td>
                <td><?php if($guia->idmotivotraslado=='12') echo 'X'?></td>
                <td></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='4') echo 'X'?>
                  </div></td>
                <td></td>
                <td><?php if($guia->idmotivotraslado=='13') echo 'X'?></td>
                <td></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='5') echo 'X'?>
                  </div></td>
                <td></td>
                <td><?php if($guia->idmotivotraslado=='14') echo 'X'?></td>
                <td></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='6') echo 'X'?>
                  </div></td>
                <td></td>
                <td><?php if($guia->idmotivotraslado=='15') echo 'X'?></td>
                <td></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='7') echo 'X'?>
                  </div></td>
                <td></td>
                <td></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='8') echo 'X'?>
                  </div></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>
                  
                  <div align="center">
                    <?php if($guia->idmotivotraslado=='9') echo 'X'?>
                  </div></td>
                <td></td>
                <td></td>
                <td>&nbsp;</td>
              </tr>
          </table>          </td>
        </tr>
        <tr>
          <td height="40">
            <table width="391" border="0">
              <tr>
                <td colspan="4" ><?Php $rst22=$objMovimiento->consultarventa(2,$guia->idmovimientoref,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
$ventita = $rst22->fetchObject();?></td>
              </tr>
              <tr>
                <td width="38" height="35" rowspan="2"></td>
                <td width="116"><BR><BR><span class="Estilo3"><?php echo $ventita->documento?></span></td>
                <td width="17" rowspan="2"></td>
                <td width="130"><BR><br><span class="Estilo3"><?php echo $ventita->numero?></span></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td width="130">&nbsp;</td>
              </tr>
          </table>          </td>
        </tr>
        <tr>
          <td height="40"></td>
        </tr>
      </table>
    </div>
  <br>
    <br>
    <!--<img src="../imagenes/cabeceraFacturaAbajo.PNG">-->
</div>
</div>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center">&nbsp;</p>
 <p align="center"><a href="javascript:imprimir('guia')" class="Estilo16">IMPRIMIR GUIA REMISION </a> </p>
 <p align="center"><a href="list_guias.php" class="Estilo16"> IR A GUIAS </a> </p>
  <p align="center"><a href="list_ventas.php" class="Estilo16"> IR A VENTAS </a> </p>
</body>

