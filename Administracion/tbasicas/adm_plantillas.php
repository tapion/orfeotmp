<?php
session_start();
$ruta_raiz = "../..";
if($_SESSION['usua_admin_sistema'] !=1 ) die(include "$ruta_raiz/errorAcceso.php");

if (isset($_POST['btn_cargar']))
{
	include ("$ruta_raiz/include/upload/upload_class.php");
	$my_upload = new file_upload;
	$my_upload->upload_dir = $ruta_raiz."/plantillas/";
	$my_upload->extensions = array(".odt", ".doc", ".docx", ".xls", ".xlsx");
	$my_upload->rename_file = false;
	$my_upload->do_filename_check = 'n';
	$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
	$my_upload->the_file = $_FILES['upload']['name'];
	$my_upload->http_error = $_FILES['upload']['error'];
	$my_upload->replace = 'y';
	if ($my_upload->upload())
	{
	} 
	$msg = $my_upload->show_error_string();
}
if (isset($_POST['btn_borrar']))
{
	if (!@unlink($ruta_raiz. "/plantillas/" . $_POST['plantilla']))
	{
		$msg = "Error al eliminar el archivo ";
	}
	else
	{}
}
?>
<html>
<head>
<title>Administrador de Plantillas</title>
<link rel="stylesheet" href="<?=$ruta_raiz?>/estilos/orfeo.css">
</head>
<body>
<form name="form1" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<?php
//$sizekb = 0.0 ;
//$sizemb = 0.0 ;
echo "<table border='0' align='center' cellpadding='0' cellspacing='5' class='borde_tab'>";
echo "<tr><td colspan='2' align='center' class='titulos4'>ADMINISTRADOR DE PLANTILLAS</td></tr>
		<tr class='listado1'><td colspan='2' align='center'>$msg</td></tr>
		<tr>
		<td>
			<input type='file' name='upload' size='30'><input type='submit' name='btn_cargar' value='Cargar' class='botones'>
		</td>
		<td align='center'>
			<input type='submit' name='btn_borrar' value='Borrar' class='botones'>
		</td>
	</tr>";
if ($handle = opendir($ruta_raiz."/plantillas/"))
{	$i=2;
	while ($file = readdir($handle))
	{	
		if ($file != "." && $file != ".." && !is_dir($file))
		{
			//$sizekb = filesize($file)/1024 ; // Lo pasa a Kbytes
			echo "<TR class='listado$i'><TD>$file</TD><TD align='center'><input type='radio' name='plantilla' value='".$file."'></TD></TR>";
			$i = (($i==1)? 2 : 1); 
		}
	}
	echo "</table>";
	closedir($handle);
}
?>
</form>
</body>
</html>