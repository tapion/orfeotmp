<?php
session_start();
//empieza Anexos   por  Julian Rolon
//lista los documentos del radicado y proporciona links para ver historicos de cada documento
//este archivo se incluye en la pagina verradicado.php
//print ("uno");
if (!$ruta_raiz) $ruta_raiz= ".";
include_once("$ruta_raiz/class_control/anexo.php");
include_once "$ruta_raiz/include/db/ConnectionHandler.php";
require_once("$ruta_raiz/class_control/TipoDocumento.php");
include_once "$ruta_raiz/class_control/firmaRadicado.php";
include "$ruta_raiz/config.php";
require_once("$ruta_raiz/class_control/ControlAplIntegrada.php");
require_once("$ruta_raiz/class_control/AplExternaError.php");

$db = new ConnectionHandler(".");
//$db->conn->debug = true;
$objTipoDocto = new TipoDocumento($db);
$objTipoDocto->TipoDocumento_codigo($tdoc);
$objFirma = new  FirmaRadicado($db);
$objCtrlAplInt = new ControlAplIntegrada($db);
//if (!$db)
//$db2 = new ConnectionHandler(".");

//$db2->conn->debug = true;

//$db->conn->SetFetchMode(ADODB_FETCH_NUM);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//$db2->conn->SetFetchMode(ADODB_FETCH_ASSOC);
$num_archivos=0;

$anex = & new Anexo($db);
$sqlFechaDocto = $db->conn->SQLDate("Y-m-D H:i:s A","sgd_fech_doc");
$sqlFechaAnexo = $db->conn->SQLDate("Y-m-D H:i:s A","anex_fech_anex");
//$sqlFechaAnexo = "to_char(anex_fech_anex, 'YYYY/DD/MM HH:MI:SS')";
$sqlSubstDesc =  $db->conn->substr."(anex_desc, 0, 50)";
include_once("include/query/busqueda/busquedaPiloto1.php");
// Modificado SGD 06-Septiembre-2007
$isql = "select anex_codigo AS DOCU
            ,anex_tipo_ext AS EXT
			,anex_tamano AS TAMA
			,anex_solo_lect AS RO
            ,usua_nomb AS CREA
			,$sqlSubstDesc AS DESCR
			,anex_nomb_archivo AS NOMBRE
			,ANEX_CREADOR
			,ANEX_ORIGEN
			,ANEX_SALIDA
			,$radi_nume_salida as RADI_NUME_SALIDA
			,ANEX_ESTADO
			,SGD_PNUFE_CODI
			,SGD_DOC_SECUENCIA
			,SGD_DIR_TIPO
			,SGD_DOC_PADRE
			,SGD_TPR_CODIGO
			,SGD_APLI_CODI
			,SGD_TRAD_CODIGO
			,SGD_TPR_CODIGO
			,ANEX_TIPO
			,$sqlFechaDocto as FECDOC
			,$sqlFechaAnexo as FEANEX
			,ANEX_TIPO as NUMEXTDOC
            ,SGD_REM_DESTINO
		   from anexos, anexos_tipo,usuario
           where anex_radi_nume=$verrad and anex_tipo=anex_tipo_codi
		   and anex_creador=usua_login and anex_borrado='N'
	   order by anex_radi_nume,radi_nume_salida asc, anex_codigo";
     error_reporting(7);
?>
<script>
swradics=0;
radicando=0;
function verDetalles(anexo,tpradic,radicado_rem,id_Dir_otro,sol_lect,numextdoc){
optAsigna = "";
if (swradics==0){
	optAsigna="&verunico=1";
}
contadorVentanas=contadorVentanas+1;
nombreventana="ventanaDetalles"+contadorVentanas;
url="detalle_archivos.php?usua=<?=$krd?>&radi=<?=$verrad?>&anexo="+anexo+"&tpradic="+tpradic;
url="<?=$ruta_raiz?>/nuevo_archivo.php?codigo="+anexo+"&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&usua=<?=$krd?>&numrad=<?=$verrad ?>&contra=<?=$drde?>&radi=<?=$verrad?>&tipo=<?=$tipo?>&ent=<?=$ent?><?=$datos_envio?>&ruta_raiz=<?=$ruta_raiz?>&tpradic="+tpradic+"&radicado_rem="+radicado_rem+"&id_Dir_otro="+id_Dir_otro+"&sol_lect="+sol_lect+"&tipo="+numextdoc;//+"&tpradic="+tpradic+"&aplinteg="+aplinteg+optAsigna;
window.open(url,nombreventana,'top=0,height=580,width=640,scrollbars=yes,resizable=yes');
return;
}
function borrarArchivo(anexo,linkarch,radicar_a,procesoNumeracionFechado){
	if (confirm('Estas seguro de borrar este archivo anexo ?'))
	{
		contadorVentanas=contadorVentanas+1;
		nombreventana="ventanaBorrar"+contadorVentanas;
		//url="borrar_archivos.php?usua=<?=$krd?>&contra=<?=$drde?>&radi=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch;
		
		url="lista_anexos_seleccionar_transaccion.php?borrar=1&usua=<?=$krd?>&numrad=<?=$verrad?>&&contra=<?=$drde?>&radi=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"&numfe="+procesoNumeracionFechado+"&dependencia=<?=$dependencia?>&codusuario=<?=$codusuario?>";
		window.open(url,nombreventana,'height=100,width=180');
	}
return;
}
function radicarArchivo(anexo,linkarch,radicar_a,procesoNumeracionFechado,tpradic,aplinteg,numextdoc,radicado_rem,sol_lect,rege){
	if (radicando>0){
	 	alert ("Ya se esta procesando una radicacion, para re-intentarlo hagla click sobre la pesta\ufffda de documentos");
	 	return;
     }

      radicando++;

	if (confirm('Se asignar\xe1 un n\xfamero de radicado a \xe9ste documento. Est\xe1 seguro  ?'))
	{
		contadorVentanas=contadorVentanas+1;
		nombreventana="mainFrame";
		
		url="<?=$ruta_raiz?>/lista_anexos_seleccionar_transaccion.php?radicar=1&radicar_a="+radicar_a+"&vp=n&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&numfe="+procesoNumeracionFechado+"&tpradic="+tpradic+"&aplinteg="+aplinteg+"&numextdoc="+numextdoc+"&radicado_rem="+radicado_rem+"&sol_lect="+sol_lect+"&rege="+rege;
		window.open(url,nombreventana,'height=450,width=600');
	}
return;
}


function numerarArchivo(anexo,linkarch,radicar_a,procesoNumeracionFechado){
if (confirm('Se asignar\xe1 un n\xfamero a \xe9ste documento. Est\xe1 seguro ?'))
	{
		contadorVentanas=contadorVentanas+1;
		nombreventana="mainFrame";
		url="<?=$ruta_raiz?>/lista_anexos_seleccionar_transaccion.php?numerar=1"+"&vp=n&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&numfe="+procesoNumeracionFechado;
		window.open(url,nombreventana,'height=450,width=600');
	}
return;
}


function asignarRadicado(anexo,linkarch,radicar_a,numextdoc,radicado_rem,sol_lect){

	if (radicando>0){
	 	alert ("Ya se esta procesando una radicacion, para re-intentarlo hagla click sobre la pesta\ufffda de documentos");
	 	return;
     }

     radicando++;

	if (confirm('Esta seguro de asignarle el numero de Radicado a este archivo ?'))
	{
		contadorVentanas=contadorVentanas+1;
		nombreventana="mainFrame";
		url="<?=$ruta_raiz?>/genarchivo.php?generar_numero=no&radicar_a="+radicar_a+"&vp=n&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>"+"&numextdoc="+numextdoc+"&radicado_rem="+radicado_rem+"&sol_lect="+sol_lect;
		window.open(url,nombreventana,'height=450,width=600');
	}

return;
}
function ver_tipodocuATRD(anexo,codserie,tsub)
{
  <?php
		$isqlDepR = "SELECT RADI_DEPE_ACTU,RADI_USUA_ACTU from radicado
		            WHERE RADI_NUME_RADI = '$numrad'";
		$rsDepR = $db->conn->Execute($isqlDepR);
	    $coddepe = $rsDepR->fields['RADI_DEPE_ACTU'];
		$codusua = $rsDepR->fields['RADI_USUA_ACTU'];
		$ind_ProcAnex="S";
  ?>
  window.open("./radicacion/tipificar_documento.php?krd=<?=$krd?>&nurad="+anexo+"&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>&coddepe=<?=$coddepe?>&tsub="+tsub+"&codserie="+codserie+"&texp=<?=$texp?>","Tipificacion_Documento_Anexos","height=500,width=750,scrollbars=yes");
}



function ver_tipodocuAnex(cod_radi,codserie,tsub,coddocu)
{ 
 
  window.open("./radicacion/tipificar_anexo.php?krd=<?=$krd?>&nurad="+cod_radi+"&ind_ProcAnex=<?=$ind_ProcAnex?>&codusua=<?=$codusua?>&coddepe=<?=$coddepe?>&tsub="+tsub+"&codserie="+codserie+"&coddocu="+coddocu,"Tipificacion_Documento_Anexos","height=300,width=750,scrollbars=yes");
}


function vistaPreliminar(anexo,linkarch,linkarchtmp){
		contadorVentanas=contadorVentanas+1;
		nombreventana="mainFrame";
		url="<?=$ruta_raiz?>/genarchivo.php?vp=s&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&radicar_documento=<?=$verrad?>&numrad=<?=$verrad?>&anexo="+anexo+"&linkarchivo="+linkarch+"&linkarchivotmp="+linkarchtmp+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>";
		window.open(url,nombreventana,'height=450,width=600');
return;
}
function nuevoArchivo(asigna){
contadorVentanas=contadorVentanas+1;
optAsigna="";
if (asigna==1){
	optAsigna="&verunico=1";
}


nombreventana="ventanaNuevo"+contadorVentanas;
url="<?=$ruta_raiz?>/nuevo_archivo.php?codigo=&<?="krd=$krd&".session_name()."=".trim(session_id()) ?>&usua=<?=$krd?>&numrad=<?=$verrad ?>&contra=<?=$drde?>&radi=<?=$verrad?>&tipo=<?=$tipo?>&ent=<?=$ent?>"+"<?=$datos_envio?>"+"&ruta_raiz=<?=$ruta_raiz?>&tdoc=<?=$tdoc?>"+optAsigna;
window.open(url,nombreventana,'height=580,width=640,scrollbars=yes,resizable=yes');
return;
}

function Plantillas(plantillaper1){
if(plantillaper1==0)
{
  plantillaper1="";
}
contadorVentanas=contadorVentanas+1;
nombreventana="ventanaNuevo"+contadorVentanas;
urlp="plantilla.php?<?="krd=$krd&".session_name()."=".trim(session_id()); ?>&verrad=<?=$verrad ?>&numrad=<?=$numrad ?>&plantillaper1="+plantillaper1;
window.open(urlp,nombreventana,'top=0,left=0,height=800,width=850');
return;
}
function Plantillas_pb(plantillaper1){
if(plantillaper1==0)
{
  plantillaper1="";
}
contadorVentanas=contadorVentanas+1;
nombreventana="ventanaNuevo"+contadorVentanas;
urlp="crea_plantillas/plantilla.php?<?="krd=$krd&".session_name()."=".trim(session_id()); ?>&verrad=<?=$verrad ?>&numrad=<?=$numrad ?>&plantillaper1="+plantillaper1;
window.open(urlp,nombreventana,'top=0,left=0,height=800,width=850');
return;
}

function regresar(){
	//window.history.go(0);
	window.location.reload();
	window.close();

}

</script>
<link rel="stylesheet" href="estilos/orfeo.css">

<body bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td height="25" class="titulos4" colspan="10">GENERACION DE DOCUMENTOS </td></tr>
</table>
<table WIDTH="100%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab" >
<tr class="t_bordeGris"><td colspan="15" class="timpar"><img src="<?=$ruta_raiz?>/imagenes/estadoDocInfo.gif" width="320" height="35"></td></tr>
<tr bgcolor='#6699cc' class='etextomenu' align='middle'>
	<th width='10%' class="titulos2" align="left">
		<img src="<?=$ruta_raiz?>/imagenes/estadoDoc.gif" width="130" height="32">
	</th>
    <th width='15%'  class="titulos2">RADICADO</th>
    <th  width='5%' class="titulos2">TIPO</th>
	<th  width='5%' class="titulos2">TRD</th>
    <th  width='1%' class="titulos2"></th>
    <th  width='5%' class="titulos2" >TAMA&Ntilde;O (Kb)</th>
    <th  width='5%' class="titulos2" >SOLO LECTURA</th>
    <th  width='20%' class="titulos2" >CREADOR</th>
    <th  width='20%' class="titulos2">DESCRIPCION</th>
    <th  width='12%' class="titulos2">ANEXADO</th>
    <th  width='13%' class="titulos2">NUMERADO</th>
    <th  width='35%' colspan="5" class="titulos2"  >ACCION</th>
</tr>
<?php
$rowan = array();
$rs=$db->query($isql);

if (!$ruta_raiz_archivo) $ruta_raiz_archivo = $ruta_raiz;
$directoriobase="$ruta_raiz_archivo/bodega/";
//Flag que indica si el radicado padre fue generado desde esta Ã¡rea de anexos
$swRadDesdeAnex=$anex->radGeneradoDesdeAnexo($verrad);
while(!$rs->EOF)
{
    $bandEnv=0;
    $aplinteg = $rs->fields["SGD_APLI_CODI"];
    $numextdoc = $rs->fields["NUMEXTDOC"];
    $tpradic  = $rs->fields["SGD_TRAD_CODIGO"];
    $coddocu=$rs->fields["DOCU"];
    $origen=$rs->fields["ANEX_ORIGEN"];
    $radicado_rem=$rs->fields["SGD_REM_DESTINO"];
    $sol_lect=$rs->fields["RO"];
    $id_Dir_otro="";
    if($coddocu and $radicado_rem==7)
    {
        $q_Dir_otro="select sgd_dir_codigo from sgd_dir_drecciones where sgd_anex_codigo=$coddocu and sgd_dir_tipo=1";
        $rs_Dir_otro=$db->query($q_Dir_otro);
        $id_Dir_otro=$rs_Dir_otro->fields["SGD_DIR_CODIGO"];
    }
	if ($rs->fields["ANEX_SALIDA"]==1 )	$num_archivos++;
	$puedeRadicarAnexo = $objCtrlAplInt->contiInstancia($coddocu,$MODULO_RADICACION_DOCS_ANEXOS,2);
	$linkarchivo=$directoriobase.substr(trim($coddocu),0,4)."/".substr(trim($coddocu),4,3)."/docs/".trim($rs->fields["NOMBRE"]);
	$linkarchivo_vista="$ruta_raiz/bodega/".substr(trim($coddocu),0,4)."/".substr(trim($coddocu),4,3)."/docs/".trim($rs->fields["NOMBRE"])."?time=".time();
	$linkarchivotmp=$directoriobase.substr(trim($coddocu),0,4)."/".substr(trim($coddocu),4,3)."/docs/tmp".trim($rs->fields["NOMBRE"]);
	if(!trim($rs->fields["NOMBRE"])) $linkarchivo = "";
?>
<tr>
<?php
if($origen==1)
{	echo " class='timpar' ";
	if ($rs->fields["NOMBRE"]=="No"){$linkarchivo= "";}
	echo "";
}
if($rs->fields["RADI_NUME_SALIDA"]!=0)
{
    $cod_radi =$rs->fields["RADI_NUME_SALIDA"];
    $sqlTotal="select anex_codigo from anexos where radi_nume_salida=$cod_radi and sgd_rem_destino <> 7 or (radi_nume_salida=anex_radi_nume and radi_nume_salida=$cod_radi and sgd_rem_destino <> 7) ";
    $sqlEnviados="select anex_codigo from anexos where radi_nume_salida=$cod_radi and anex_estado=4 ";
    $ADODB_COUNTRECS=true;
    $rsTot= $db->conn->Execute($sqlTotal);
    $rsEnv= $db->conn->Execute($sqlEnviados);
    $ADODB_COUNTRECS=false;
    //$tEnv=$radicado_rem==7?+1:$rsEnv->RecordCount();
    if($rsTot->RecordCount()>$rsEnv->RecordCount() or $rsEnv->RecordCount()==0)
    {
        $bandEnv=1;
    }
}
else
{	
    $cod_radi =$coddocu;
    $bandEnv=1;
}

$anex_estado = $rs->fields["ANEX_ESTADO"];
if($anex_estado<=1) {$img_estado = "<img src=$ruta_raiz/imagenes/docRecibido.gif "; }
if($anex_estado==2)
{	$estadoFirma = $objFirma->firmaCompleta($cod_radi);
	if ($estadoFirma == "NO_SOLICITADA")
		$img_estado = "<img src=$ruta_raiz/imagenes/docRadicado.gif  border=0>";
	else if ($estadoFirma == "COMPLETA")
		{	$img_estado = "<img src=$ruta_raiz/imagenes/docFirmado.gif  border=0>";
		}else if ($estadoFirma == "INCOMPLETA")
			{	$img_estado = "<img src=$ruta_raiz/imagenes/docEsperaFirma.gif border=0>";	}
}
if($anex_estado==3) {$img_estado = "<img src=$ruta_raiz/imagenes/docImpreso.gif>"; }
if($anex_estado==4) {$img_estado = "<img src=$ruta_raiz/imagenes/docEnviado.gif>"; }
?>
    <td height="21" <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> > <font size=1> <?=$img_estado?> </TD>
	<TD  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>><font size=1>

	<?php if(trim($linkarchivo)){echo "<b><a class=vinculos href='".trim(strtolower($linkarchivo_vista))."'>".trim(strtolower($cod_radi))."</a>";}else{echo trim(strtolower($cod_radi));} ?>
      </font> </td>
    <td <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> ><font size=1> <?
     if(trim($linkarchivo))
		    {
		      echo $rs->fields["EXT"];
			}
		     else
			{
			 echo $msg;
			}
    if($rs->fields["SGD_DIR_TIPO"]==7) $msg = "Otro Destinatario"; else $msg="Otro Destinatario";
	?> </font> </td>
	<td <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> width="1%" valign="middle"><font face="Arial, Helvetica, sans-serif" class="etextomenu">
  <?
     /*

	 * Indica si el Radicado Ya tiene asociado algun TRD
	 */
	   $isql_TRDA = "SELECT *
					FROM SGD_RDF_RETDOCF
					WHERE RADI_NUME_RADI = '$cod_radi'
				  ";
	  $rs_TRA = $db->conn->Execute($isql_TRDA);
	  $radiNumero = $rs_TRA->fields["RADI_NUME_RADI"];
		if ($radiNumero !='') {
	      $msg_TRD = "S";
		  }
	   else
	     {
		  $msg_TRD = "";
		  }
         ?>
		  <center>
		  <?
		  echo $msg_TRD;
	       ?>
	 </center>
    </font> </td>

	<td <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> width="1%" valign="middle"><font face="Arial, Helvetica, sans-serif" class="etextomenu">
	<?php
	    /**
		  *  $perm_radi_sal  Viene del campo PER_RADI_SAL y Establece permiso en la rad. de salida
		  *  1 Radicar documentos,  2 Impresion de Doc's, 3 Radicacion e Impresion.
		  *  (Por. Jh)
		  *  Ademas verifica que el documento no este radicado con $rowwan[9] y [10]
		  *  El jefe con $codusuario=1 siempre podra radicar
		  */
	  if(($rs->fields["EXT"]=="rtf" or $rs->fields["EXT"]=="doc" or $rs->fields["EXT"]=="odt" or $rs->fields["EXT"]=="xml") AND $rs->fields["ANEX_ESTADO"]<=3)
	   {
	     echo "<a class=vinculos href=javascript:vistaPreliminar('$coddocu','$linkarchivo','$linkarchivotmp')>";
		 ?>
		 <img src="<?=$ruta_raiz?>/iconos/vista_preliminar.gif" alt="Vista Preliminar" border="0">
         <font face="Arial, Helvetica, sans-serif" class="etextomenu"><font face="Arial, Helvetica, sans-serif" class="etextomenu"><font face="Arial, Helvetica, sans-serif" class="etextomenu">
         <?
		 echo "</a>";
		 $radicado = "false";
		 $anexo = $cod_radi;
	   }
	     ?>
         </font></font> </font> </font></th>
    <td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> ><font size=1> <?=$rs->fields["TAMA"]?> </font></td>
    <td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>  ><font size=1> <?=$rs->fields["RO"]?> </font></td>
    <td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> >  <font size=1> <?=$rs->fields["CREA"]?> </font></td>
    <td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>  ><font size=1> <?=$rs->fields["DESCR"]?> </font></td>
    <td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>  ><font size=1> <?=$rs->fields["FEANEX"]?> </font></td>
    <td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>  ><font size=1>
    <?
	if ($rs->fields["SGD_PNUFE_CODI"]&& strcmp($cod_radi,$rs->fields["SGD_DOC_PADRE"])==0&& strlen($rs->fields["SGD_DOC_SECUENCIA"])>0 )
	{
		$anex->anexoRadicado($verrad,$rs->fields["DOCU"]);
		echo ($anex->get_doc_secuencia_formato($dependencia)."<BR>".$rs->fields["FECDOC"]);
	}
    ?></font>
    </td>
    <td<?if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>  >
            <font size=1>
	<?php
	if($origen!=1 and $linkarchivo  and $verradPermisos == "Full"   and $rs->fields["RO"]=='N' or $rs->fields["ANEX_CREADOR"]==$_GET['krd'] and $linkarchivo and $origen!=1 and $verradPermisos == "Full")
	{	if ($bandEnv==1)
			echo "<a class=vinculos href=javascript:verDetalles('$coddocu','$tpradic','$radicado_rem','$id_Dir_otro','$sol_lect','$numextdoc')>Modificar</a> ";
	}
	?>
            </font>
	</td>
	<?
		//Estas variables se utilizan para verificar si se debe mostrar la opci�n de tipificaci�n de anexo .TIF
		$anexTipo = $rs->fields["ANEX_TIPO"];
    	$anexTPRActual = $rs->fields["SGD_TPR_CODIGO"];
   	if ($verradPermisos == "Full")
	{
    ?>
		<td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?>  ><font size=1>
    	<?php
    	$radiNumeAnexo = $rs->fields["RADI_NUME_SALIDA"];
    	
		if($radiNumeAnexo>0 and trim($linkarchivo))
		{
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculos href=javascript:ver_tipodocuATRD($radiNumeAnexo,$codserie,$tsub);>Tipificar</a> ";
		}elseif ($perm_tipif_anexo == 1 && $anexTPRActual == '' && ($anexTipo == 4 ||$anexTipo == 7) )
		{ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, adem�s el anexo no ha sido tipificado
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi',$codserie,$tsub);> Tipificar </a> ";
		}elseif ($perm_tipif_anexo == 1 && $anexTPRActual != '' && ($anexTipo == 4 ||$anexTipo == 7))
		{ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, adem�s el anexo YA ha sido tipificado antes
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi',$codserie,$tsub,$anexTPRActual);> Re-Tipificar </a> ";
		}
		
		?>
	 	</font>
	 	</td>

	 	<td <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> ><font size=1>
		<?php
		
		if ($rs->fields["RADI_NUME_SALIDA"]==0 and $ruta_raiz != ".." and (trim($rs->fields["ANEX_CREADOR"])==trim($krd) OR $codusuario==1) and
		(($rs->fields["SGD_PNUFE_CODI"] and strlen($rs->fields["SGD_DOC_SECUENCIA"])==0 and strcmp ($cod_radi,$rs->fields["SGD_DOC_PADRE"])==0) or
		 (!$rs->fields["SGD_PNUFE_CODI"])))
		{
			if($origen!=1  and $linkarchivo)
			{	echo "<a class=vinculos href=javascript:borrarArchivo('$coddocu','$linkarchivo','$cod_radi','".$rs->fields["SGD_PNUFE_CODI"]."')>Borrar</a> ";	}
		}
		?>
		</font>
		</td>
		<td <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?> ><font size=1>
		<?php
				
	    /**
		  *  $perm_radi_sal  Viene del campo PER_RADI_SAL y Establece permiso en la rad. de salida
		  *  1 Radicar documentos,  2 Impresion de Doc's, 3 Radicacion e Impresion.
		  *  (Por. Jh)
		  *  Ademas verifica que el documento no este radicado con $rowwan[9] y [10]
		  *  El jefe con $codusuario=1 siempre podra radicar
		  */
		if  ($rs->fields["ANEX_SALIDA"]==1 AND ($codusuario==1 OR $perm_radi_sal==1 OR $perm_radi_sal==3) and
	  		(($ruta_raiz != ".." AND !$rs->fields["SGD_PNUFE_CODI"]) OR
	  		($rs->fields["SGD_PNUFE_CODI"] AND $rs->fields["SGD_DOC_SECUENCIA"] AND $rs->fields["SGD_DOC_SECUENCIA"]>0 )))
		{	if (!$rs->fields["RADI_NUME_SALIDA"])
			{	if(substr($verrad,-1)==2 && $puedeRadicarAnexo==1 )
				{	$rs->fields["SGD_PNUFE_CODI"]=0;
					echo "<a class=vinculos href=javascript:radicarArchivo('$coddocu','$linkarchivo','si',".$rs->fields["SGD_PNUFE_CODI"].",'$tpradic','$aplinteg','$numextdoc','$radicado_rem','$sol_lect')>Radicar(-$tpradic)</a>";
				  	$radicado = "false";
				  	$anexo = $cod_radi;
				}
				else
					if ($puedeRadicarAnexo!=1)
					{	$objError = new AplExternaError();
						$objError->setMessage($puedeRadicarAnexo);
						echo ($objError->getMessage());
					}
					else
					{	if((substr($verrad,-1)!=2) and $num_archivos==1 and !$rs->fields["SGD_PNUFE_CODI"] and $swRadDesdeAnex==false )
						{
							echo "<a class=vinculos href=javascript:asignarRadicado('$coddocu','$linkarchivo','$cod_radi','$numextdoc','$radicado_rem','$sol_lect')>Asignar Rad</a>";
							$radicado = "false";
							$anexo = $cod_radi;
						}
						else if ($rs->fields["SGD_PNUFE_CODI"]&& strcmp($cod_radi,$rs->fields["SGD_DOC_PADRE"])==0 && !$anex->seHaRadicadoUnPaquete($rs->fields["SGD_DOC_PADRE"]))
							{	echo "<a class=vinculos href=javascript:radicarArchivo('$coddocu','$linkarchivo','si',".$rs->fields["SGD_PNUFE_CODI"].",'$tpradic','$aplinteg','$numextdoc','$radicado_rem','$sol_lect')>Radicar(-$tpradic)</a>";
				  				$radicado = "false";
				  				$anexo = $cod_radi;
							}
							else if ($puedeRadicarAnexo==1)
							{
				  				$rs->fields["SGD_PNUFE_CODI"]=0;
								echo "<a class=vinculos href=javascript:radicarArchivo('$coddocu','$linkarchivo','si',".$rs->fields["SGD_PNUFE_CODI"].",'$tpradic','$aplinteg',$numextdoc,'$radicado_rem','$sol_lect')>Radicar(-$tpradic)</a>";
				  				$radicado = "false";
				  				$anexo = $cod_radi;
			}		}		}
			else
			{	if (!$rs->fields["SGD_PNUFE_CODI"])$rs->fields["SGD_PNUFE_CODI"]=0;
				if ($bandEnv==1)
				{	echo "<a class=vinculos href=javascript:radicarArchivo('$coddocu','$linkarchivo','$cod_radi',".$rs->fields["SGD_PNUFE_CODI"].",'','',$numextdoc,'$radicado_rem','$sol_lect',1)>Re-Generar</a>";
		    		$radicado = "true";
		}	}	}
		else if ( $rs->fields["SGD_PNUFE_CODI"]  && ($usua_perm_numera_res==1) && $ruta_raiz != ".." && !$rs->fields["SGD_DOC_SECUENCIA"] && strcmp($cod_radi,$rs->fields["SGD_DOC_PADRE"])==0) // SI ES PAQUETE DE DOCUMENTOS Y EL USUARIO TIENE PERMISOS
			{	echo "<a class=vinculos href=javascript:numerarArchivo('$coddocu','$linkarchivo','si',".$rs->fields["SGD_PNUFE_CODI"].")>Numerar</a>";
			}
	  		if($rs->fields["RADI_NUME_SALIDA"]) {$radicado="true";}
		?>
		</font>
		</td>
		
	<?php
	}else { 
	?>
		<td  <? if (!$rs->fields["SGD_PNUFE_CODI"]) echo " class='listado2 ' "; else echo " class='e_tablas ' "; ?><font size=1>
		
		<?php
		
		if ( $origen!=1  and $linkarchivo and $perm_borrar_anexo == 1 && $anexTipo == 4 )
		{
			echo "<a class=vinculoTipifAnex href=javascript:borrarArchivo('$coddocu','$linkarchivo','$cod_radi','".$rs->fields["SGD_PNUFE_CODI"]."')>Borrar</a> ";
		}
		if ( $perm_tipif_anexo == 1 && $anexTPRActual == '' && ($anexTipo == 4 ||$anexTipo == 7) )
		{ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, adem�s el anexo no ha sido tipificado
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi','$anexo',$codserie,$tsub);> Tipificar </a> ";
		}elseif ( $perm_tipif_anexo == 1 && $anexTPRActual != '' && ($anexTipo == 4 ||$anexTipo == 7) )
		{ //Es un anexo de tipo tif (4) y el usuario tiene permiso para Tipificar, adem�s el anexo YA ha sido tipificado antes
			if(!$codserie) $codserie="0";
			if(!$tsub) $tsub="0";
			echo "<a class=vinculoTipifAnex href=javascript:ver_tipodocuAnex('$cod_radi','$anexo',$codserie,$tsub,$anexTPRActual);> Re-Tipificar </a> ";
		}
		

	?>
	
	</td>
		
	<?php
	
	}
	?>
		
</tr>
<?php
	$rs->MoveNext();
}
/*
$mostrar_lista = 0;
if($mostrar_lista==1)
{
?>
</TABLE>
<?
}*/
?>
</table>
<?
if($verradPermisos == "Full")
{
?>
<br>
<table  width="100%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
  <tr align="center">
     <td >
     <a class="vinculos" href='javascript:nuevoArchivo(<? if ( $num_archivos==0 && $swRadDesdeAnex==false)  echo "1"; else echo "0";  ?>)' class="timpar">Anexar
      Archivo ... </a>

      </td>
    <script>
    	 swradics=<?=$num_archivos?>;
    </script>
    <?
	/* Anexar plantillas, keda por ahora aplazado el proyecto
	<td class="celdaGris"> <a href='javascript:Plantillas(0)' class="timparr">Anexar
      Plantilla ...</a>
      <!-- <a href='plantilla.php?<?=SID ?>'>Anexar Plantilla ... </a> </td>-->
    </TD>

    <td class="celdaGris"> <a href='javascript:Plantillas_pb(0)' class="timparr">A</a>
      <!-- <a href='plantilla.php?<?=SID ?>'>Anexar Plantilla ... </a> </td>-->
    </TD>
	*/

	?>
  </tr>
</table>
   <?
   }
   ?>
<br>
</body>
