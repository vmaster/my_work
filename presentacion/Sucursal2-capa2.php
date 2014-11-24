<html>
<head>
<?php $xajax->printJavascript();?>
<script>
function buscar(){
  <?php $flistado->printScript() ?>
}
function inicio(){
 divformulario.style.display="none";
  <?php $flistado->printScript() ?>
}
function nuevo(){
  divformulario.style.display="";
  frm1.txtIdSucursal.value="0";
  frm1.txtNombre.value="";
  frm1.txtDireccion.value="";
}
function cancelar(){
  divformulario.style.display="none";
}
function guardar(){
  <?php $fguardar->printScript() ?>;
  divformulario.style.display="none";
  <?php $flistado->printScript() ?>;
}
function editar(id){
   xajax_editar(id);
   divformulario.style.display="";
}
function botones(){
	xajax_botones();
}
function confirmar(origen){
	xajax_confirmar(origen);
}
function eliminar(){
	xajax_eliminar(xajax.tools.getFormValues('frmlista'));
	botones();
	<?php $flistado->printScript() ?>;
}
function vaciar()
{
	<?php $fvaciar->printScript() ?>;
	<?php $flistado->printScript() ?>;
}
function seleccionar_todo(){ 
   for (i=0;i<document.frmlista.elements.length;i++) 
      if(document.frmlista.elements[i].type == "checkbox") 
         document.frmlista.elements[i].checked=1 
} 
function deseleccionar_todo(){ 
   for (i=0;i<document.frmlista.elements.length;i++) 
      if(document.frmlista.elements[i].type == "checkbox") 
         document.frmlista.elements[i].checked=0 
} 
</script>
<link rel="stylesheet" href="../estilo/estilos.css" type="text/css">
<link href="../estilo/estilo.css" rel="stylesheet" type="text/css">
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="inicio()">
<div id="divmenu">
  <center>MANTENIMIENTO DE SUCURSAL</center><br />
<button type="button" class="boton" onClick="nuevo()"><img src="../imagenes/b_views.png" width="16" height="16"> Nuevo</button>
</div>
<div id="divformulario">
<form name="frm1">
<input type="hidden" name="pag" id="pag" value="1">
<input type="hidden" name="TotalReg" id="TotalReg">
<input type="hidden" name="txtIdSucursal" id="txtIdSucursal" value="">
<table class="tablaint">
<tr>
  <td class="alignright">Nombre:</td>
  <td><input name="txtNombre" id="txtNombre"></td></tr>
<tr>
  <td class="alignright">Direcci&oacute;n:</td>
  <td><input name="txtDireccion" id="txtDireccion"></td></tr>
<tr><td colspan="2">
<button type="button" class="boton" onClick="guardar()"><img src="../imagenes/b_save.png" width="16" height="16"> Guardar</button>
<button type="button" class="boton" onClick="cancelar()"><img src="../imagenes/s_cancel.png" width="16" height="16"> Cancelar</button></td></tr>
</table>
</form>
</div>
<div>B&uacute;squeda</div>
<div id="divbusqueda" class="textoazul">
<select name="campo" id="campo" onChange="buscar()" style="display:none">
<option value="Nombre">Nombre</option>
</select>Nombre:
<input name="frase" id="frase" onKeyUp="buscar()">
</div>
<div id="divnumreg" class="registros"></div>
<div id="divregistros"></div>
<div>
<table class="tablaint">
<tr><td>
<img src="../imagenes/arrow_ltr.png">
<a href="javascript:seleccionar_todo()">Marcar Totos</a> / <a href="javascript:deseleccionar_todo
()">Desmarcar Totos</a> Para los elementos que están marcados: 
</td><td>
<div id="DivBotones"><button type="button" class="boton" onClick="confirmar(1)"><img src="../imagenes/b_drop.png" width="16" height="16"> Eliminar</button>
<button type="button" class="boton" onClick="confirmar(2)"><img src="../imagenes/b_empty.png" width="16" height="16"> Vaciar</button></div>
</td></tr>
<tr><td><center><div id="DivConfirmar"></div></center></td><td></td></tr>
</table>
</div>
</body>
</html>