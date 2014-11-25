<?php
class clsProducto
{
function insertar($idproducto, $codigo, $descripcion, $idcategoria, $idmarca, $idunidadbase, $peso, $idmedidapeso, $stockseguridad, $idarmario, $columna, $fila, $kardex, $idcolor=0, $idtalla=0, $recetamedica)
 {
   $sql = "INSERT INTO producto(idproducto, codigo, descripcion, idcategoria, idmarca, idunidadbase, peso, idmedidapeso, stockseguridad, idarmario, columna, fila, kardex, idcolor, idtalla, estado, recetamedica) VALUES(NULL,UPPER('" . $codigo . "'),UPPER('" . $descripcion . "')," . $idcategoria . "," . $idmarca . "," . $idunidadbase . "," . $peso . ",'" . $idmedidapeso. "', " . $stockseguridad . ",". $idarmario . "," . $columna . "," . $fila . ",'" . $kardex . "',".$idcolor.",".$idtalla.",'N',".$recetamedica.")";
   
    global $cnx;
	
   return $cnx->query($sql) or die($sql);
 }
 
 function obtenerLastId()
 {
   $sql = "SELECT LAST_INSERT_ID() as idproducto";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function actualizar($idproducto, $codigo, $descripcion, $idcategoria, $idmarca, $idunidadbase, $peso, $idmedidapeso, $stockseguridad, $idarmario, $columna, $fila, $kardex, $idcolor=0, $idtalla=0, $recetamedica)
 {
  
  if(isset($idunidadbase)){
   $sql = "UPDATE producto SET codigo =UPPER('" . $codigo ."'), descripcion =UPPER('" . $descripcion ."'), idcategoria = " . $idcategoria . ", idmarca = " . $idmarca . ", idunidadbase = " . $idunidadbase . ", peso = " . $peso . ", idmedidapeso = '" . $idmedidapeso . "', stockseguridad = " . $stockseguridad . ", idarmario = " . $idarmario . ", columna=" . $columna . ", fila = '" . $fila ."', kardex='".$kardex."', idcolor=".$idcolor.", idtalla=".$idtalla.", estado = 'N', recetamedica  =".$recetamedica." WHERE idproducto = " . $idproducto ;
   }else{
   $sql = "UPDATE producto SET codigo =UPPER('" . $codigo ."'), descripcion =UPPER('" . $descripcion ."'), idcategoria = " . $idcategoria . ", idmarca = " . $idmarca . ", peso = " . $peso . ", idmedidapeso = '" . $idmedidapeso . "', stockseguridad = " . $stockseguridad . ", idarmario = " . $idarmario . ", columna=" . $columna . ", fila = '" . $fila ."', kardex='".$kardex."', idcolor=".$idcolor.", idtalla=".$idtalla.", estado = 'N', recetamedica  = ".$recetamedica." WHERE idproducto = " . $idproducto ;
   }
      
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }


function eliminar($idproducto)
 {
   $sql = "UPDATE producto SET estado='A' WHERE idproducto = " . $idproducto;
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar()
 {
   $sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion, categoria.descripcion as categoria, marca.descripcion as marca, unidad.Descripcion as unidadbase, peso, idmedidapeso, Um.descripcion as medidapeso, producto.stockseguridad, armario.nombre as armario, producto.columna, producto.fila, producto.kardex, producto.estado, producto.idcolor, color.nombre as color, color.codigo as codigocolor, producto.idtalla, talla.nombre as talla, talla.abreviatura as tallaabreviatura FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase inner join unidad Um on Um.IdUnidad= producto.idmedidapeso and Um.Tipo='M' LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla ON talla.idtalla=producto.idtalla where producto.estado='N'";
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($idproducto, $descripcion, $idcategoria, $idmarca)
 {
   $sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion, producto.recetamedica, categoria.descripcion as categoria, marca.descripcion as marca, unidad.Descripcion as unidadbase, peso, idmedidapeso, Um.descripcion as medidapeso, producto.stockseguridad, armario.nombre as armario, producto.columna, producto.fila, producto.kardex, producto.estado, producto.idunidadbase, producto.idcolor, color.nombre as color, color.codigo as codigocolor, producto.idtalla, talla.nombre as talla, talla.abreviatura as tallaabreviatura FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase inner join unidad Um on Um.IdUnidad= producto.idmedidapeso and Um.Tipo='M' LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla ON talla.idtalla=producto.idtalla WHERE producto.estado='N' ";
   if(isset($idproducto)){
	$sql = $sql . " AND producto.idproducto = " . $idproducto;}
	if(isset($descripcion)){
	$sql = $sql . " AND producto.descripcion LIKE '%" . $descripcion . "%'";}
   if($idcategoria!=""){
	$sql = $sql . " AND producto.idcategoria in (select IdCategoria from categoria tp 
where (tp.IdCategoria = ".$idcategoria.") OR (tp.IdCategoria = tp.IdCategoria - ".$idcategoria.")
or (tp.IdCategoriaRef = ".$idcategoria.") OR (tp.IdCategoriaRef = tp.IdCategoriaRef - ".$idcategoria."))";}
   if($idmarca!=""){
    $sql = $sql . " AND (producto.idmarca =" . $idmarca . " OR producto.idmarca = producto.idmarca - ". $idmarca .")";}
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function buscarProductoventa($idproducto, $descripcion, $idcategoria, $idmarca)
 {
   $sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion as producto, categoria.descripcion as categoria, marca.descripcion as marca, color.nombre as color, talla.abreviatura as talla, unidad.Descripcion as unidadbase, preciocompra, precioventa, precioventaespecial, moneda, obtenerStock(producto.idproducto,producto.idunidadbase,".$_SESSION['IdSucursal'].") as StockActual, armario.nombre as armario, producto.columna, producto.fila FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria LEFT join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase INNER JOIN listaunidad LU ON LU.idproducto=producto.idproducto and LU.idunidad=producto.idunidadbase LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla ON talla.idtalla=producto.idtalla WHERE producto.estado='N'";
   if(isset($idproducto)){
	$sql = $sql . " AND producto.idproducto = " . $idproducto;}
	if(isset($descripcion)){
	$sql = $sql . " AND producto.descripcion LIKE '%" . $descripcion . "%'";}
   if($idcategoria!=""){
	$sql = $sql . " AND producto.idcategoria in (select IdCategoria from categoria tp 
where (tp.IdCategoria = ".$idcategoria.") OR (tp.IdCategoria = tp.IdCategoria - ".$idcategoria.")
or (tp.IdCategoriaRef = ".$idcategoria.") OR (tp.IdCategoriaRef = tp.IdCategoriaRef - ".$idcategoria."))";}
   if($idmarca!=""){
    $sql = $sql . " AND (producto.idmarca =" . $idmarca . " OR producto.idmarca = producto.idmarca - ". $idmarca .")";}
   global $cnx;
   return $cnx->query($sql);  		 	
   //echo $sql;
 }
 
function buscarSiguienteCodigo($idcategoria, $idmarca)
 {
   global $cnx;
   $sql = "SELECT Count(*) + 1 AS Serie FROM producto P WHERE estado='N' and 1=1 ";
   if($idcategoria!=""){
	$sql = $sql . " AND P.idcategoria = ".$idcategoria;}
   if($idmarca!=""){
    $sql = $sql . " AND P.idmarca =" . $idmarca;}
   $rst = $cnx->query($sql);  		 	
   $dato1 = $rst->fetchObject();


   $sql = "SELECT UPPER(C.abreviatura) AS AbreviaturaCat FROM categoria C WHERE 1=1 ";
      if($idcategoria!=""){
	$sql = $sql . " AND C.idcategoria = ".$idcategoria;}
   $rst = $cnx->query($sql);  		 	
   $dato2 = $rst->fetchObject();
	
   $sql = "SELECT UPPER(M.abreviatura) AS AbreviaturaMarca FROM marca M WHERE 1=1 ";
   if($idmarca!=""){
    $sql = $sql . " AND m.idmarca =" . $idmarca;}
   $rst = $cnx->query($sql);  		 	
   $dato3 = $rst->fetchObject();		 	
	   
	return $dato2->AbreviaturaCat.'-'.$dato3->AbreviaturaMarca.'-'.str_pad($dato1->Serie,3,'0',STR_PAD_LEFT);
 }

function buscarconxajax($idcategoria, $campo, $frase, $limite, $idsucursal, $moneda, $tipocambio)
 {
   $sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion as producto, categoria.descripcion as categoria, marca.descripcion as marca, CONCAT(color.nombre,'-',color.codigo) as color, talla.abreviatura as talla, unidad.Descripcion as unidadbase, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN precioventa ELSE precioventa/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN precioventa ELSE precioventa*".$tipocambio." END END,2) as precioventa, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN preciocompra ELSE preciocompra/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN preciocompra ELSE preciocompra*".$tipocambio." END END,2) as preciocompra, obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") as StockActual, armario.nombre as armario, producto.columna, producto.fila FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase INNER JOIN listaunidad LU ON LU.idproducto=producto.idproducto and LU.idunidad=producto.idunidadbase LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla ON talla.idtalla=producto.idtalla WHERE producto.estado='N'";
   if($idcategoria!=0){
	$sql .= " AND producto.idcategoria = ".$idcategoria;}
	$sql .= " AND ".$campo . " LIKE '%" . $frase . "%' ORDER BY 2 Asc ". $limite;

   global $cnx;
   return $cnx->query($sql);  		 	
 }

function buscarxidproductoyidunidad($idproducto, $idunidad)
 {
   $sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion as producto, categoria.descripcion as categoria, marca.descripcion as marca, U.Descripcion as unidad, producto.stockseguridad, armario.nombre as armario, producto.columna, producto.fila, producto.kardex, producto.estado FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join armario on armario.IdArmario= producto.IdArmario inner join listaunidad LU on LU.idproducto= producto.Idproducto INNER JOIN unidad U ON U.idunidad=LU.idunidad WHERE 1=1";
   if(isset($idproducto)){
	$sql = $sql . " AND producto.idproducto = " . $idproducto;}
   if($idunidad!=""){
	$sql = $sql . " AND LU.idunidad =" . $idunidad ;}
   global $cnx;
   return $cnx->query($sql);  		 	
 }
 
function buscarconxajax1($idcategoria, $campo, $frase, $limite, $idsucursal, $moneda, $tipocambio)
 {
   $sql = 
  " SELECT producto.idproducto, producto.codigo, producto.descripcion as producto, categoria.descripcion as categoria, marca.descripcion as marca, CONCAT(color.nombre,'-',color.codigo) as color, talla.abreviatura as talla, unidad.Descripcion as unidadbase, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN precioventa ELSE precioventa/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN precioventa ELSE precioventa*".$tipocambio." END END,2) as precioventa,ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN preciocompra ELSE preciocompra/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN preciocompra ELSE preciocompra*".$tipocambio." END END,2) as preciocompra, obtenerStock(producto.idproducto,producto.idunidadbase,1) as StockActual, armario.nombre as armario, producto.columna, producto.fila, producto.peso as peso, U.Descripcion as medida FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idcategoria inner join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase inner join unidad U on U.idunidad=producto.idmedidapeso INNER JOIN listaunidad LU ON LU.idproducto=producto.idproducto and LU.idunidad=producto.idunidadbase LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla ON talla.idtalla=producto.idtalla WHERE producto.estado='N'";
   
   if($idcategoria!=0){
	$sql .= " AND producto.idcategoria in (select IdCategoria from categoria tp 
where (tp.IdCategoria = ".$idcategoria.") OR (tp.IdCategoria = tp.IdCategoria - ".$idcategoria.")
or (tp.IdCategoriaRef = ".$idcategoria.") OR (tp.IdCategoriaRef = tp.IdCategoriaRef - ".$idcategoria."))";}
	$sql .= " AND ".$campo . " LIKE '%" . $frase . "%' ORDER BY 2 Asc ". $limite;

   global $cnx;
   return $cnx->query($sql);  		 	
 }

function buscarparaventaconxajax($idcategoria, $campo, $frase, $limite, $idsucursal, $moneda, $tipocambio)
 {
   $sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion as producto, categoria.descripcion as categoria, marca.descripcion as marca, CONCAT(color.nombre,'-',color.codigo) as color, talla.abreviatura as talla, unidad.Descripcion as unidadbase, ROUND(CASE WHEN moneda='S' THEN CASE WHEN '".$moneda."'='S' THEN precioventa ELSE precioventa/".$tipocambio." END ELSE CASE WHEN '".$moneda."'='D' THEN precioventa ELSE precioventa*".$tipocambio." END END,2) as precioventa, obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") as StockActual, armario.nombre as armario, producto.columna, producto.fila FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria LEFT join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase INNER JOIN listaunidad LU ON LU.idproducto=producto.idproducto and LU.idunidad=producto.idunidadbase LEFT JOIN color ON color.idcolor=producto.idcolor LEFT JOIN talla ON talla.idtalla=producto.idtalla WHERE producto.estado='N'";
   if($idcategoria!=0){
	$sql .= " AND producto.idcategoria = ".$idcategoria;}
	$sql .= " AND ".$campo." LIKE '%".$frase."%' ORDER BY 2 Asc ". $limite;

   global $cnx;
   return $cnx->query($sql);
   //echo $sql;
 }
 
function consultarproductosmasvendidos($idcategoria,$fechainicio,$fechafinal,$moneda,$marca)
{
$sql = "select count(movimiento.numero) as veces, producto.idproducto, producto.codigo, producto.descripcion, categoria.descripcion as categoria, marca.descripcion as marca, unidad.Descripcion as unidadbase, peso, idmedidapeso, Um.descripcion as medidapeso, producto.stockseguridad, armario.nombre as armario, producto.columna, producto.fila, producto.kardex, producto.estado,round(sum(LU.precioventa*detallemovalmacen.cantidad),2) as total,LU.preciocompra as pcompra,LU.precioventa as pventa,LU.precioventaespecial as pventaespecial FROM movimiento inner join detallemovalmacen on movimiento.idmovimiento=detallemovalmacen.idmovimiento inner join producto on producto.idproducto=detallemovalmacen.idproducto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase inner join unidad Um on Um.IdUnidad= producto.idmedidapeso and Um.Tipo='M' INNER JOIN listaunidad LU ON LU.idproducto=producto.idproducto and LU.idunidad=producto.idunidadbase WHERE producto.estado='N' AND movimiento.idtipomovimiento=2";
 if(isset($idcategoria)){
	$sql = $sql . " AND producto.idcategoria in (select IdCategoria from categoria tp 
where (tp.IdCategoria = ".$idcategoria.") OR (tp.IdCategoria = tp.IdCategoria - ".$idcategoria.")
or (tp.IdCategoriaRef = ".$idcategoria.") OR (tp.IdCategoriaRef = tp.IdCategoriaRef - ".$idcategoria."))";}
 if(isset($fechainicio) && $fechainicio!="")
	$sql = $sql . " AND movimiento.fecha >= '".trim($fechainicio)."'";
 if(isset($fechafin) && $fechafin!="")
	$sql = $sql . " AND movimiento.fecha <= '".trim($fechafin)."'";
if(isset($moneda))
	$sql = $sql . " AND movimiento.moneda='".$moneda."'";	
 if(isset($marca) && $marca!=0)
	$sql = $sql . " AND producto.idmarca = '".$marca."'";		
$sql.=" GROUP BY producto.codigo ORDER BY total DESC LIMIT 0,50";
   global $cnx;
   return $cnx->query($sql);  		
}


function buscarProductosStockSuperado($idsucursal, $idproducto, $descripcion, $idcategoria, $idmarca, $situacionstock)
 {
   	$sql = "SELECT producto.idproducto, producto.codigo, producto.descripcion, categoria.descripcion as categoria, marca.descripcion as marca, unidad.Descripcion as unidadbase, peso, idmedidapeso, Um.descripcion as medidapeso, producto.stockseguridad, armario.nombre as armario, producto.columna, producto.fila, producto.kardex, producto.estado, producto.idunidadbase, obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") as stockactual FROM producto inner join marca on producto.idmarca=marca.idmarca inner join categoria on producto.idcategoria= categoria.idCategoria inner join armario on armario.IdArmario= producto.IdArmario inner join unidad on unidad.IdUnidad= producto.IdUnidadBase inner join unidad Um on Um.IdUnidad= producto.idmedidapeso and Um.Tipo='M' WHERE 1=1";
   	if(isset($idproducto)){
		$sql = $sql . " AND producto.idproducto = " . $idproducto;}
	if(isset($descripcion)){
		$sql = $sql . " AND producto.descripcion LIKE '%" . $descripcion . "%'";}
   	if($idcategoria!=""){
		$sql = $sql . " AND producto.idcategoria in (select IdCategoria from categoria tp 
where (tp.IdCategoria = ".$idcategoria.") OR (tp.IdCategoria = tp.IdCategoria - ".$idcategoria.")
or (tp.IdCategoriaRef = ".$idcategoria.") OR (tp.IdCategoriaRef = tp.IdCategoriaRef - ".$idcategoria."))";}
   	if($idmarca!=""){
    	$sql = $sql . " AND (producto.idmarca =" . $idmarca . " OR producto.idmarca = producto.idmarca - ". $idmarca .")";}
	if($situacionstock==1){//STOCK MINIMO SUPERADO
		$sql = $sql . " AND obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") <= producto.stockseguridad";}
	if($situacionstock==2){//STOCK CERO
		$sql = $sql . " AND obtenerStock(producto.idproducto,producto.idunidadbase,".$idsucursal.") = 0 ";}
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }

} 
?>