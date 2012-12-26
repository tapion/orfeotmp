<?php
error_reporting(0);
session_start();
$krd=strtoupper($krd);
$ruta_raiz = "."; 
if(!isset($_SESSION['dependencia'])) include "./rec_session.php";
?>
<html>
<head>
<title>.:: Sistema de Gesti&oacute;n Documental ::.</title>
<link rel="shortcut icon" href="imagenes/arpa.gif">
<script>
  function cerrar_ventana()
        {
           window.close();
        }
</script>
</head>
<frameset rows="75,864*" frameborder="NO" border="0" framespacing="0" cols="*">
  <frame name="topFrame" scrolling="NO" noresize src='f_top.php'>
  <frameset cols="175,947*" border="0" framespacing="0" rows="*">
          <frame name='leftFrame' scrolling='AUTO' src='correspondencia.php' marginwidth='0' marginheight='0' scrolling='AUTO'>
    <frame name='mainFrame' src='cuerpo.php' scrolling='AUTO'>
  </frameset>
</frameset>
<noframes></noframes>
</html>
