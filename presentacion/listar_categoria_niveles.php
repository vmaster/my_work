<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
require("../datos/cado.php");
require("../negocio/cls_categoria.php");
$objCategoria = new clsCategoria();
$mn=$objCategoria->maxNivel();
$maxi=($mn->fetchObject()->maximo);
$espacio="&nbsp&nbsp&nbsp&nbsp&nbsp";
echo "COMBO BOX QUE ACEPTA UN MAXIMO DE 10 SUB-NIVELES<br>";
?>
<select>
<?php

//nivel 1
$rst = $objCategoria->buscar(null,'','',null,1,null);
while($dato = $rst->fetchObject()){

	echo "<option>".$dato->Descripcion."</option>";
		//nivel 2
		$rst2 = $objCategoria->buscar(null,'','',$dato->IdCategoria,2,null);
		while($dato2 = $rst2->fetchObject()){

			echo "<option>".$espacio.$dato2->Descripcion."</option>";
			//nivel 3
				$rst3 = $objCategoria->buscar(null,'','',$dato2->IdCategoria,3,null);
				while($dato3 = $rst3->fetchObject()){
				//nivel 4
					echo "<option>".$espacio.$espacio.$dato3->Descripcion."</option>";
						//nivel 5
						$rst4 = $objCategoria->buscar(null,'','',$dato3->IdCategoria,4,null);
						while($dato4 = $rst4->fetchObject()){

							echo "<option>".$espacio.$espacio.$espacio.$dato4->Descripcion."</option>";
							
							//nivel 6
							$rst5 = $objCategoria->buscar(null,'','',$dato4->IdCategoria,5,null);
							while($dato5 = $rst5->fetchObject()){

								echo "<option>".espacio.$espacio.$espacio.$espacio.$dato5->Descripcion."</option>";
								//nivel 7
									$rst6 = $objCategoria->buscar(null,'','',$dato5->IdCategoria,6,null);
									while($dato6 = $rst6->fetchObject()){

										echo "<option>".$espacio.espacio.$espacio.$espacio.$espacio.$dato6->Descripcion."</option>";
										//nivel 8
										$rst7 = $objCategoria->buscar(null,'','',$dato6->IdCategoria,7,null);
											while($dato7 = $rst7->fetchObject()){

											echo "<option>".$espacio.$espacio.espacio.$espacio.$espacio.$espacio.$dato7->Descripcion."</option>";								
											
											//nivel 9
										$rst8 = $objCategoria->buscar(null,'','',$dato7->IdCategoria,8,null);
											while($dato8 = $rst8->fetchObject()){

											echo "<option>".$espacio.$espacio.$espacio.espacio.$espacio.$espacio.$espacio.$dato8->Descripcion."</option>";					
											
											//nivel 10
										$rst9 = $objCategoria->buscar(null,'','',$dato8->IdCategoria,9,null);
											while($dato9 = $rst9->fetchObject()){

											echo "<option>".$espacio.$espacio.$espacio.$espacio.espacio.$espacio.$espacio.$espacio.$dato9->Descripcion."</option>";
										}
										}
											
										}
									
									}
							}
						}
				}
			
		}
}

?>

</select>
</body>
</html>
