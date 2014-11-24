<?php
session_start();
//if(!isset($_SESSION['usuario'])) die("Acceso denegado");
if(!isset($_SESSION['Usuario']))
{
	header("location: ../presentacion/login.php?error=1");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/estilosistema.css" rel="stylesheet" type="text/css">
<title>SISTEMA DE COMERCIALIZACI&Oacute;N</title>
</head>
<frameset rows="40,*,15" cols="*" frameborder="no" border="0" framespacing="0">
  	<frame src="frame_top.php" name="topFrame" scrolling="No" noresize="noresize" id="topFrame" title="" />
  <frameset cols="250,*" frameborder="no" border="0" framespacing="0">
    <frame src="frame_left.php" name="leftFrame" id="leftFrame" title="" />
    <frame src="frame_main.php" name="mainFrame" id="mainFrame" title="" />
  </frameset>
	<frame src="frame_copy.php" name="copyFrame" scrolling="No" noresize="noresize" id="copyFrame" title="copyFrame" />
</frameset>
<noframes><body>
</body></noframes>
</html>
