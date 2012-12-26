<?
$krdOld = $krd;
$carpetaOld = $carpeta;
$tipoCarpOld = $tipo_carp;
session_start();
if(!$krd) $krd=$krdOsld;
$ruta_raiz = ".";
if(!$_SESSION['dependencia']) include "$ruta_raiz/rec_session.php";

$verrad = "";
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
$db = new ConnectionHandler($ruta_raiz);	 
?>
<html>
<head>
<script language="JavaScript" type="text/JavaScript">
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<args.length-2; i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es requerido.\n'; }
  } if (errors) alert('Asegurese de entrar el password Correcto, \N No puede ser Vacio:\n');
  document.MM_returnValue = (errors == '');
}
function validar()
{
    if(document.getElementById('contradrd').value!=document.getElementById('contraver').value)
    {
        alert('!Las contrase\xF1as no coinciden. Verifiquelas!');
        return false;
    }
    return true;
}
</script>
<title>Cambio de Contrase&ntilde;as</title>
<link rel="stylesheet" href="estilos/orfeo.css">
</head><?php 
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);	
$numeroa=0;$numero=0;$numeros=0;$numerot=0;$numerop=0;$numeroh=0;
$isql = "select a.*,b.depe_nomb from usuario a,dependencia b where USUA_LOGIN ='$krd' and a.depe_codi=b.depe_codi";
$rs=$db->query($isql);	
//echo $row["usuario"].$krd;
 echo "<font size=1 face=verdana>";
 $contraxx=$rs->fields["USUA_PASW"];
if (trim($rs->fields["USUA_LOGIN"])==trim($krd))
{
	$dependencia=$rs->fields["DEPE_CODI"];
	$dependencianomb=$rs->fields["DEPE_NOMB"];
	$codusuario =$rs->fields["USUA_CODI"];
	$contraxx=$rs->fields["USUA_PASW"];
	$nivel=$rs->fields["CODI_NIVEL"];
	$iusuario = " and us_usuario='$krd'";
	$perrad = $rs->fields["PERM_RADI"];
	?>
	<body bgcolor="#207385">
	<center>
	<IMG src='<?=$ruta_raiz?>/imagenes/logo2.gif'>
	<form action='usuarionuevo.php?<?=session_name()."=".session_id()?>&krd=<?=$krd?>' method=post onSubmit="MM_validateForm('contradrd','','R','contraver','','R');return document.MM_returnValue">
	<?php 
	 echo "<center><B><FONT color=white face='Verdana, Arial, Helvetica, sans-serif' SIZE=4 >CAMBIO DE CONTRASE&Ntilde;A USUARIOS
	 </font> </CENTER>\n";
	 echo "<P><P><center><FONT face='Verdana, Arial, Helvetica, sans-serif' SIZE=3 color=white >Por favor introduzca la nueva contrase&ntilde;a</font><p></p>\n";
	 echo "<table border=0 class='borde_tab'>\n";
	 echo "<tr ><td class='titulos2'>\n";						 
	 echo "<CENTER><input type=hidden name='usuarionew' value=$krd><B>USUARIO </td>
	 <td class=listado2>$krd</td></tr>\n";
	 echo "<td class=titulos2><center>CONTRASE&Ntilde;A </td>
	 <td class=listado2 ><input type=password name=contradrd id=contradrd vale='' class=tex_area><br></td>\n";
	 echo "</tr>"				 ;
	 echo "<tr ><td class=titulos2><center>RE-ESCRIBA LA CONTRASE&Ntilde;A </td>
	 <td class=listado2><input type=password name=contraver id=contraver class=tex_area vale=''></td>\n";
	 echo "</tr>";							 
	 echo "</table></p></p>\n";
	 echo "";
	 echo "";
	 echo "<center>\n";
	 $isql = "select depe_codi,depe_nomb from DEPENDENCIA ORDER BY DEPE_NOMB";
	 $rs = $db->query($isql);
	 $numerot = $rs->RecordCount();
	 echo "<br><input type=submit value=Aceptar onclick=\"return validar();\"class=botones>\n";
	 echo "<br><input type=hidden value=$depsel name=depsel>\n";
	 ?>
	 
	 </form>
	 <? 
}
else
{	
		echo "<b>No esta Autorizado para entrar </b>";
}					
?>
    </center>
</body>
</html>
