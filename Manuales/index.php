<?php

////// Listado de la carpeta plantillas //////
$dir = ("../plantillas");
$directorio = opendir($dir);
while ($archivo = readdir($directorio))
{
	if($archivo != '.' && $archivo != '..')
		$listaDir .= "<tr><td class='listado2'><a href='downloadPlantilla.php?file=$archivo'>$archivo</a></td></tr>"; 
} 
closedir($directorio); 
//////////////////////////////////////////////
?>
<html>
<head>
<title>.: AYUDAS de Orfeo :.</title>
<link rel="stylesheet" href="../estilos/orfeo.css">
</head>
<body>
<table align="center" width="80%" border="0">
<tr align="center"><td class="titulos4">&Iacute;NDICE</td></tr>
<tr><td bgcolor="#cccccc" class="titulos2">&nbsp;&nbsp;Manuales&nbsp;&nbsp;</td></tr>
<tr><td class='listado2'><a href="manual_usuario.pdf" target="_blank">Usuario</a></td></tr>
<tr><td class='listado2'><a href="manual_administrador.pdf" target="_blank">Administrador</a></td></tr>
<tr><td class='listado2'><a href="manual_digitalizador.pdf" target="_blank">Digitalizador</a></td></tr>
<tr><td bgcolor="#cccccc" class="titulos2">&nbsp;&nbsp;Instructivos&nbsp;&nbsp;</td></tr>
<tr><td class='listado2'><a href="instructivo_rad_entrada.pdf" target="_blank">Radicaci&oacute;n de Entrada</a></td></tr>
<tr><td class='listado2'><a href="instructivo_rad_salida_como_respuesta_rad_entrada.pdf" target="_blank">Radicaci&oacute;n de un Documento de Salida como Anexo a un Documento de Entrada</a></td></tr>
<tr><td class='listado2'><a href="instructivo_rad_salida_nuevos.pdf" target="_blank">Radicaci&oacute;n de Salida Nuevo</a></td></tr>
<tr><td class='listado2'><a href="instructivo_rad_memos.pdf" target="_blank">Radicaci&oacute;n de Memorandos</a></td></tr>
<tr><td class='listado2'><a href="flujo_normal_correspondencia_en_orfeo" target="_blank">Flujo normal correspondencia en orfeo</a></td></tr>
<tr><td bgcolor="#cccccc" class="titulos2">&nbsp;&nbsp;Plantillas&nbsp;&nbsp;</td></tr>
<?php echo $listaDir ?>
</table>
</body>
</html>