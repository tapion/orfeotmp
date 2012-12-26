<?php
require_once($ruta_raiz."/include/myPaginador.inc.php");
require_once($ruta_raiz."/radsalida/masiva/OpenDocText.class.php");
$odt = new OpenDocText();
$paginador=new myPaginador($db,($queryE),$orden,$ascdesc,1000);
if(isset($_GET['genDetalle']) ||isset($_GET['genTodosDetalle']))
{
	$paginador->setFuncionFilas("pintarEstadisticaDetalle");
}
else
{
	$orden=isset($orden)?$orden:"";
	$paginador->setFuncionFilas("pintarEstadistica");
}
$paginador->setImagenASC($ruta_raiz."iconos/flechaasc.gif");
$paginador->setImagenDESC($ruta_raiz."iconos/flechadesc.gif");
echo $paginador->generarPagina($titulos,"titulos3");
if(!isset($_GET['genDetalle']) && !isset($_GET['genTodosDetalle']) && $paginador->getTotal() > 0)
{
    $total=$paginador->getId()."_total";
	$res=$db->query($queryE);
	$datos=0;
	while(!$res->EOF)
	{
		$data1y[]=$res->fields[1];
		$nombUs[]= iconv($odt->codificacion($res->fields[0]), 'ISO-8859-1',$res->fields[0]);
		$nombPqr[]=iconv($odt->codificacion($res->fields[2]), 'ISO-8859-1',$res->fields[2]);
		$nombPqrLbl[]=iconv($odt->codificacion($res->fields[2]), 'ISO-8859-1',$res->fields[2])." %.1f%%";
		$res->MoveNext();
	}
	$nombXAxis=substr($titulos[1],strpos($titulos[1],"#")+1);
	$nombYAxis=substr($titulos[2],strpos($titulos[2],"#")+1);
	$nombreGraficaTmp = $ruta_raiz."bodega/tmp/E_$krd.png";
	$rutaImagen = $nombreGraficaTmp;
	if(file_exists($rutaImagen))
	{
		unlink($rutaImagen);
	}
	$notaSubtitulo = $subtituloE[$tipoEstadistica]."\n";
	$tituloGraph = $tituloE[$tipoEstadistica];
	if($tipoEstadistica==15)include "PdfEstadistica015.php";
    else include "genBarras1.php";

?>
    <table align="center">
    <tr>
    	<td>
            <!-- Modificado SGD 21-Agosto-2007
            <input type=button class="botones_largo" value="Ver Grafica" onClick='window.open("image.php?rutaImagen=<?=$rutaImagen."&fechaH=".date("YmdHis")?>" , "Grafica_Estadisticas_-_Orfeo", "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes,width=560,height=720");' />
            -->
            <input type=button class="botones_largo" value="Ver Grafica" onClick='window.open("<?php print $ruta_raiz; ?>/estadisticas/image.php?rutaImagen=<?=$rutaImagen."&fechaH=".date("YmdHis")?>" , "Grafica_Estadisticas_-_Orfeo", "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes,width=560,height=720");' />
            <?php echo "<!-- $queryE -->"; ?>
        </td>
   </tr>
   <?if($tipoEstadistica==1 or $tipoEstadistica==5){?>
   <tr>
        <td align="center">
            <a href="genEstadistica.php?<?=$datosEnvioDetalle?>&genTodosDetalle=1&<?=$datosaenviar?>" Target="detallesSec" class="vinculos">VER TODOS LOS DETALLES</a>
        </td>
    </tr>
   <?}}?>
    </table>

</center>
</body>
</html>