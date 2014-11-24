<?php
session_start();
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<?php
require("../datos/cado.php");
//require_once("../negocio/cls_persona.php");

if( !isset($_GET['tipo']) ){
	$_GET['tipo']='PERSONA';}


function genera_cboZona($seleccionado)
{

	require("../negocio/cls_zona.php");
		$objZona = new clsZona();
		$rst2 = $objZona->consultar();

	echo "<select name='cboZona' id='cboZona'>";
	echo "<option selected='selected' value='0'>TODOS</option>";
	while($registro2=$rst2->fetch())
	{
		echo "<option value='".$registro2[0]."' ".$seleccionar.">".$registro2[1]."</option>";
	}
	echo "</select>";
}

function genera_cboSector($seleccionado)
{

	require("../negocio/cls_sector.php");
		$objSector = new clsSector();
		$rst1 = $objSector->consultar();

	echo "<select name='cboSector' id='cboSector'>";
	echo "<option selected='selected' value='0'>TODOS</option>";
	while($registro1=$rst1->fetch())
	{
		echo "<option value='".$registro1[0]."'>".$registro1[1]."</option>";
	}
	echo "</select>";

}

require("xajax_persona.php");
$xajax->printJavascript();
?>
<script>
function listadopersonas(){
<?php if($_GET['tipo']=='EMPRESA'){?>
divParametros.style.display="none";
divlistapersona.style.display="none";
divlistaempresa.style.display="";
<?php }else{?>
divParametros.style.display="";
divlistapersona.style.display="";
divlistaempresa.style.display="none";
<?php 
} $flistadopersona->printScript(); ?>

}</script>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<body onLoad="listadopersonas()">
<div id="barramenusup" style="display:none"> <!-- inicio menu superior -->
			<ul>
                <li><?php echo $_SESSION['Sucursal'];?></li>
                <li>Bienvenido:<img src="../imagenes/user_suit.png" alt="usuario"> <?php echo $_SESSION['Usuario']?></li>
                <li><a href="main.php"><img src="../imagenes/application_home.png">Ir a Menu</a></li>
                <li><a href='../negocio/cont_usuario.php?accion=LOGOUT'><img src="../imagenes/door_in.png" alt="salir" width="16" height="16" longdesc="Cerrar Sesión"> Cerrar sesion </a></li>
            </ul>
</div>
<center>
<img src="../imagenes/encabezado.gif">
</center>
<center><h1>  <?php if($_GET['tipo']=='PERSONA'){?></h1>
<div id="titulo01">Listado de Personas</div>
  <?php }else{?>
<div id="titulo01">Listado de Sucursales</div>  <?php }?>
</center>
<form id="form1" name="form1" method="post" action="mant_persona.php?accion=NUEVO&tipo=<?php echo $_GET['tipo'];?>">
  <div id='divParametros' style="height:100px;overflow:auto;">

    <fieldset>
      <legend>Criterios de b&uacute;squeda</legend>
      <table width="923">
        <tr>
          <td class="alignright">Nombres : </td>
      <td><label>
            <input name="txtNombres" type="text" id="txtNombres" style="text-transform:uppercase">
          </label></td>
          <td class="alignright">Tipo Persona:</td>
          <td><select name="cboTipo" id="cboTipo">
            <option value="NO" selected="selected">TODOS</option>
            <option value="NATURAL">NATURAL</option>
            <option value="JURIDICA">JURIDICA</option>
          </select></td>
          <td class="alignright">Sector :</td>
          <td><label>&nbsp;<?php echo genera_cboSector(0)?></label></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td width="81" class="alignright">Apellidos : </td>
      <td width="193"><label>
            <input name="txtApellidos" type="text" id="txtApellidos" style="text-transform:uppercase">
          </label></td>
          <td width="93" class="alignright">Rol :</td>
          <td width="153"><select name="cboRol" id="cboRol">
            <option value="0" selected="selected">TODOS</option>
            <option value="1">CLIENTE</option>
            <option value="2">PROVEEDOR</option>
            <option value="3">EMPLEADO</option>
            <option value="4">USUARIO</option>
            </select></td>
          <td width="61" class="alignright">Zona :</td>
          <td width="88"><?php echo genera_cboZona(0)?></td>
          <td width="21">&nbsp;</td>
          <td width="63"><input name = 'BUSCAR' type='button' id="BUSCAR" value = 'BUSCAR' onClick="listadopersonas()"></td>
          <td width="56"><input type='submit' name = 'NUEVO2' value = 'NUEVO'></td>
          <td width="72">&nbsp;</td>
        </tr>
      </table>
    </fieldset>
  </div>
  <div id="divlistapersona">
  <fieldset>
  <legend>Lista</legend>
  <div id="divReporte" style=";<?php if(isset($_GET['accion'])) echo 'overflow:auto';?>"> </div>
  </fieldset>
  </div>
  <BR>
  <div id="divlistaempresa">
   <table width="100%" class="tablaint" align="center">
  <tr>
    <th width="62">Código</th>
    <th >Empresa</th>
    <th>RUC</th>
    <th>Distrito</th>
    <th>Provincia</th>
    <th>Departamento</th>
    <th>Dirección</th>
    <th>Celular</th>
    <th>Email</th>
    <th>Sector</th>
    <th>Zona</th>
    <th colspan="3">Operaciones</th>
    </tr>
      <?php
	  
if($_GET['tipo']=='EMPRESA'){
	$rol=5;
	$condicion='';
$objPersona = new clsPersona();
$rst = $objPersona->consultarpersonarollistado($rol,$condicion);

while($dato = $rst->fetchObject())
 {
$cont++;
   if($cont%2) $estilo="par"; else $estilo="impar";
?>
<tr class="<?php echo $estilo;?>">
    <td><?php echo $dato->IdPersona?></td>
    <td><?php echo $dato->Apellidos?></td>
    <td><?php echo $dato->NroDoc?></td>
    <td><?php echo $dato->distrito?></td>
    <td><?php echo $dato->provincia?></td>
    <td><?php echo $dato->departamento?></td>
    <td><?php echo $dato->Direccion?>&nbsp;</td>
    <td><?php echo $dato->Celular?>&nbsp;</td>
    <td><?php echo $dato->Email?>&nbsp;</td>
    <td><?php echo $dato->Sector?></td>
    <td><?php echo $dato->Zona?></td>
    <td width="64"><a href="mant_persona.php?accion=ACTUALIZAR&IdPersona=<?php echo $dato->IdPersona;?>&tipo=<?php echo $_GET['tipo'];?>" ><img src="../imagenes/editar_.jpg" width="16" height="16">Actualizar </a></td>
    <td width="64"><a href="../negocio/cont_persona.php?accion=ELIMINAR&origen=LIST&IdPersona=<?php echo $dato->IdPersona;?>&tipo=EMPRESA"> <img src="../imagenes/eliminar.jpg" width="16" height="16">Eliminar </a></td>
  </tr>
  <?php }}?></table>
<table width="100%" align="center">
  <tr>
    <th><input type='submit' name = 'NUEVO' value = 'NUEVO'></th>
  </tr>
</table>

  </div>
</form>
</body>
</html>
