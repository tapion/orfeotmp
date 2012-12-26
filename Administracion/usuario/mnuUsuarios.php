<?
/*************************************************************************************/
/* ORFEO GPL:Sistema de Gestion Documental		http://www.orfeogpl.org	     */
/*	Idea Original de la SUPERINTENDENCIA DE SERVICIOS PUBLICOS DOMICILIARIOS     */
/*				COLOMBIA TEL. (57) (1) 6913005  orfeogpl@gmail.com   */
/* ===========================                                                       */
/*                                                                                   */
/* Este programa es software libre. usted puede redistribuirlo y/o modificarlo       */
/* bajo los terminos de la licencia GNU General Public publicada por                 */
/* la "Free Software Foundation"; Licencia version 2. 			             */
/*                                                                                   */
/* Copyright (c) 2005 por :	  	  	                                     */
/* SSPS "Superintendencia de Servicios Publicos Domiciliarios"                       */
/*   Jairo Hernan Losada  jlosada@gmail.com                Desarrollador             */
/*   Sixto Angel Pinzón López --- angel.pinzon@gmail.com   Desarrollador             */
/* C.R.A.  "COMISION DE REGULACION DE AGUAS Y SANEAMIENTO AMBIENTAL"                 */
/*   Liliana Gomez        lgomezv@gmail.com                Desarrolladora            */
/*   Lucia Ojeda          lojedaster@gmail.com             Desarrolladora            */
/* D.N.P. "Departamento Nacional de Planeación"                                      */
/*   Hollman Ladino       hladino@gmail.com                Desarrollador             */
/*                                                                                   */
/* Colocar desde esta lInea las Modificaciones Realizadas Luego de la Version 3.5    */
/*  Nombre Desarrollador   Correo     Fecha   Modificacion                           */
/*************************************************************************************/
?>
<?
	$ruta_raiz = "../..";
	session_start();
	if(!$dependencia or !$tpDepeRad) include "$ruta_raiz/rec_session.php";
	$phpsession = session_name()."=".session_id();
?>
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<link rel="stylesheet" href="../../estilos/orfeo.css">
</head>
<body>
<form name='frmMnuUsuarios' action='../formAdministracion.php?<?=session_name()."=".session_id()?>&krd=<?=$krd?>' method="post">
  <table width="32%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
  <tr bordercolor="#FFFFFF">
    <td colspan="2" class="titulos4"><div align="center"><strong>ADMINISTRACION DE USUARIOS Y PERFILES</strong></div></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td align="center" class="listado2" width="98%"><a href='crear.php?usModo=1&<?=$phpsession ?>&krd=<?=$krd?>' class="vinculos" target='mainFrame'>1. Crear Usuario</a></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td align="center" class="listado2" width="98%"><a href='cuerpoEdicion.php?usModo=2&<?=$phpsession ?>&krd=<?=$krd?>' class="vinculos" target='mainFrame'>2. Editar Usuario</a></td>
  </tr>
  <tr bordercolor="#FFFFFF">
    <td align="center" class="listado2" width="98%"><a href='cuerpoConsulta.php?<?=$phpsession ?>&krd=<?=$krd?>' class="vinculos" target='mainFrame'>3. Consultar Usuario</a></td>
  </tr>
  <tr bordercolor="#FFFFFF">
  	<td align="center" class="listado2">
	<center><input align="middle" class="botones" type="submit" name="Submit" value="Cerrar"></center>
	</td> </tr>
</table>
</form>
</body>
</html>
