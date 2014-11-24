<?php
session_start();
class clsBitacora
{
function insertar($idBitacora, $idMovimiento, $idUsuario, $FechaHora, $Comentario, $Operacion)
 {

   $sql = "INSERT INTO bitacora(IdBitacora, IdMovimiento, IdUsuario, FechaHora, Comentario, Operacion, IdSucursal) VALUES(NULL," . $idMovimiento . "," . $idUsuario . ",'". $FechaHora ."','". $Comentario . "','".$Operacion."',".$_SESSION['IdSucursal'].")";
   global $cnx;
   return $cnx->query($sql) or die($sql);  	 	 	
 }

function actualizar($idBitacora, $idMovimiento, $idUsuario, $FechaHora, $Comentario)
 {
   $sql = "UPDATE bitacora SET IdMovimiento=" . $idMovimiento .", IdUsuario=" . $idUsuario .", FechaHora = '" . $FechaHora . "', Comentario = UPPER('" . $Comentario . "') WHERE IdBitacora = " . $idBitacora ;
   global $cnx;
   return $cnx->query($sql);  	 	 	
 }

function eliminar()
 {
   $sql = "DELETE FROM bitacora WHERE IdBitacora <>0";
   global $cnx;
   return $cnx->query($sql);  		 	
 }

function consultar()
 {
   			$sql = "SELECT
					bitacora.IdBitacora,
					tipomovimiento.Descripcion,
					tipodocumento.abreviatura,
					numero,
					bitacora.FechaHora,
					RealizadoA.Nombres AS NomRealizadoA,
					RealizadoA.Apellidos AS ApeRealizadoA,
					RealizadoPor.Nombres AS NomRealizadoPor,
					RealizadoPor.Apellidos AS ApeRealizadoPor,
					bitacora.Comentario,
					bitacora.Operacion				
				FROM
					bitacora
					Inner Join movimiento ON bitacora.IdMovimiento = movimiento.IdMovimiento
					Inner Join tipomovimiento ON movimiento.IdTipoMovimiento = tipomovimiento.IdTipoMovimiento
					Inner Join tipodocumento ON movimiento.IdTipoDocumento = tipodocumento.IdTipoDocumento
					Inner Join usuario ON bitacora.IdUsuario = usuario.IdUsuario
					Inner Join persona AS RealizadoPor ON usuario.IdUsuario = RealizadoPor.IdPersona
					Inner Join persona AS RealizadoA ON movimiento.IdPersona = RealizadoA.IdPersona
					WHERE bitacora.idsucursal=".$_SESSION['IdSucursal'];

    $sql = $sql . " ORDER BY bitacora.FechaHora desc, bitacora.IdBitacora desc";
						
   global $cnx;
   return $cnx->query($sql);  	 	
 }

function buscar($fecha,$ApellidoCliente, $ApellidoResponsable, $TipoMovimiento)
 {
 /*  $sql = "SELECT
			bitacora.IdBitacora,
			tipomovimiento.Descripcion,
			bitacora.FechaHora,
			RealizadoA.Nombres AS NomRealizadoA,
			RealizadoA.Apellidos AS ApeRealizadoA,
			RealizadoPor.Nombres AS NomRealizadoPor,
			RealizadoPor.Apellidos AS ApeRealizadoPor
		FROM
			bitacora
			Inner Join movimiento ON bitacora.IdMovimiento = movimiento.IdMovimiento
			Inner Join tipomovimiento ON movimiento.IdTipoMovimiento = tipomovimiento.IdTipoMovimiento
			Inner Join usuario ON bitacora.Idusuario = usuario.Idusuario
			Inner Join persona AS RealizadoPor ON usuario.IdPersona = RealizadoPor.IdPersona
			Inner Join persona AS RealizadoA ON movimiento.IdPersona = RealizadoA.IdPersona
		WHERE
			1=1
			";*/
			
			$sql="SELECT
					bitacora.IdBitacora,
					tipomovimiento.Descripcion,
					tipodocumento.abreviatura,
					numero,
					bitacora.FechaHora,
					RealizadoA.Nombres AS NomRealizadoA,
					RealizadoA.Apellidos AS ApeRealizadoA,
					RealizadoPor.Nombres AS NomRealizadoPor,
					RealizadoPor.Apellidos AS ApeRealizadoPor,
					bitacora.Comentario,
					bitacora.Operacion
				FROM
					bitacora
					Inner Join movimiento ON bitacora.IdMovimiento = movimiento.IdMovimiento
					Inner Join tipomovimiento ON movimiento.IdTipoMovimiento = tipomovimiento.IdTipoMovimiento
					Inner Join tipodocumento ON movimiento.IdTipoDocumento = tipodocumento.IdTipoDocumento
					Inner Join usuario ON bitacora.IdUsuario = usuario.IdUsuario
					Inner Join persona AS RealizadoPor ON usuario.IdUsuario = RealizadoPor.IdPersona
					Inner Join persona AS RealizadoA ON movimiento.IdPersona = RealizadoA.IdPersona
				WHERE bitacora.idsucursal=".$_SESSION['IdSucursal']." and
					1=1";
	if(isset($fecha))
	  $sql = $sql . " AND bitacora.FechaHora LIKE '".$fecha."%'";	
	if(isset($ApellidoResponsable))
	  $sql = $sql . " AND RealizadoPor.Apellidos LIKE '".$ApellidoResponsable."%'";
	if(isset($ApellidoCliente))
	  $sql = $sql . " AND RealizadoA.Apellidos LIKE '".$ApellidoCliente."%'";
    if(isset($TipoMovimiento))
      $sql = $sql . " AND tipomovimiento.IdTipoMovimiento = ".$TipoMovimiento;
	  
    $sql = $sql . " ORDER BY bitacora.FechaHora desc, bitacora.IdBitacora desc";
	
   global $cnx;
   return $cnx->query($sql);  		 	
 }
} 
?>