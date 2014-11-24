<?php
$manejador="mysql";
$servidor="localhost";
$usuario="root";
$pass="";
$base="dbtienda2";
$cadena="$manejador:host=$servidor;port=3306;dbname=$base";

$cnx = new PDO($cadena,$usuario,$pass,array(PDO::ATTR_PERSISTENT => true));
?>
