<?
session_start();
$verrad = "";
$ruta_raiz = "..";
if (!$dependencia)   include "$ruta_raiz/rec_session.php";
if (!$dep_sel) $dep_sel = $dependencia;
?>
<html>
<head>
<title>Envio de Documentos. Orfeo...</title>
<link rel="stylesheet" href="../estilos/orfeo.css">
</head>
<body bgcolor="#FFFFFF" topmargin="0">
<div id="spiffycalendar" class="text"></div>
<link rel="stylesheet" type="text/css" href="js/spiffyCal/spiffyCal_v2_1.css">
<?
 $ruta_raiz = "..";
 // Modificado SGD 14-Septiembre-2007
 define('ADODB_ASSOC_CASE', 2);
 include_once "$ruta_raiz/include/db/ConnectionHandler.php";
 $db = new ConnectionHandler("$ruta_raiz");	 

 if(!$carpeta) $carpeta=0;
 if(!$estado_sal)   {$estado_sal=2;}
 if(!$estado_sal_max) $estado_sal_max=3;

 if($estado_sal==3) {
    $accion_sal = "Envio de Documentos";
	$pagina_sig = "cuerpoEnvioNormal.php";
	$nomcarpeta = "Radicados Para Envio";
	if(!$dep_sel) $dep_sel = $dependencia;

	$dependencia_busq1 = " and c.radi_depe_radi = $dep_sel ";
	$dependencia_busq2 = " and c.radi_depe_radi = $dep_sel";
 }
 
  if ($orden_cambio==1)  {
 	if (!$orderTipo)  {
	   $orderTipo="desc";
	}else  {
	   $orderTipo="";
	}
 }
 $encabezado = "".session_name()."=".session_id()."&krd=$krd&estado_sal=$estado_sal&estado_sal_max=$estado_sal_max&accion_sal=$accion_sal&dependencia_busq2=$dependencia_busq2&dep_sel=$dep_sel&filtroSelect=$filtroSelect&tpAnulacion=$tpAnulacion&nomcarpeta=$nomcarpeta&orderTipo=$orderTipo&orderNo=";
 $linkPagina = "$PHP_SELF?$encabezado&orderNo=$orderNo";
 $swBusqDep = "si";
 $carpeta = "nada";
 include "../envios/paEncabeza.php";
 $pagina_actual = "../envios/cuerpoEnvioNormal.php";
 $varBuscada = "radi_nume_salida";
 include "../envios/paBuscar.php";   
 $pagina_sig = "../envios/envia.php";
 include "../envios/paOpciones.php";   

	/*  GENERACION LISTADO DE RADICADOS
	 *  Aqui utilizamos la clase adodb para generar el listado de los radicados
	 *  Esta clase cuenta con una adaptacion a las clases utiilzadas de orfeo.
	 *  el archivo original es adodb-pager.inc.php la modificada es adodb-paginacion.inc.php
	 */
?>
  <form name=formEnviar action='../envios/envia.php?<?=$encabezado?>' method=post>
 <?
    if ($orderNo==98 or $orderNo==99) {
       $order=1; 
	   if ($orderNo==98)   $orderTipo="desc";
       if ($orderNo==99)   $orderTipo="";
	}  
    else  {
	   if (!$orderNo)  {
	   		$orderNo=3;
			$orderTipo="desc";
		}
	   $order = $orderNo + 1;
    }

 	$radiPath = $db->conn->Concat($db->conn->substr."(a.anex_codigo,1,4) ", "'/'",$db->conn->substr."(a.anex_codigo,5,3) ","'/docs/'","a.anex_nomb_archivo");
 	include "$ruta_raiz/include/query/envios/queryCuerpoEnvioNormal.php";
    $rs=$db->conn->Execute($isql);

	//$nregis = $rs->recordcount();
	if (!$rs->fields["IMG_Radicado Salida"])  {
		echo "<table class=borde_tab width='100%'><tr><td class=titulosError><center>NO se encontro nada con el criterio de busqueda</center></td></tr></table>";}
	else  {
		$pager = new ADODB_Pager($db,$isql,'adodb', true,$orderNo,$orderTipo);
		$pager->toRefLinks = $linkPagina;
		$pager->toRefVars = $encabezado;
		$pager->Render($rows_per_page=20,$linkPagina,$checkbox=chkEnviar);
	}
 ?>
  </form>

</body>

</html>
