<?php
session_start();
if (!$no_planilla or intval($no_planilla) == 0)
    die("<table class=borde_tab width='100%'><tr><td class=titulosError><center>Debe colocar un Numero de Planilla v�lido</center></td></tr></table>");
if ($generar_listado) {
    error_reporting(7);
    $ruta_raiz = "..";
    if (!defined('ADODB_FETCH_NUM'))
        define('ADODB_FETCH_NUM', 1);
    $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
    $fecha_ini = $fecha_busq;
    $fecha_fin = $fecha_busq;
    $fecha_ini = mktime($hora_ini, $minutos_ini, 00, substr($fecha_ini, 5, 2), substr($fecha_ini, 8, 2), substr($fecha_ini, 0, 4));
    $fecha_fin = mktime($hora_fin, $minutos_fin, 59, substr($fecha_fin, 5, 2), substr($fecha_fin, 8, 2), substr($fecha_fin, 0, 4));
    $fecha_ini1 = "$fecha_busq $hora_ini:$minutos_ini:00";
    $fecha_mes = "'" . substr($fecha_ini1, 0, 7) . "'";
    $sqlChar = $db->conn->SQLDate("Y-m", "SGD_RENV_FECH");
    $order_isql = "  ORDER BY  SGD_RENV_CODIGO,SGD_RENV_VALOR";
    include "./oracle_pdf.php";
    $pdf = new PDF('L', 'pt', 'A3');
    $pdf->lmargin = 0.2;
    $pdf->SetFont('Arial', '', 8);
    $pdf->AliasNbPages();

    $head_table = array("CANTIDAD", "CATEGORIA DE CORRESPONDENCIA", "NUMERO DE REGISTRO", "DESTINATARIO", "DESTINO", "PESO EN GRAMOS", "VALOR PORTE", "NORMAL", "VALOR ASEGURADO", "TASA DE SEGURO", "VALOR REEMBOLSABLE", "AVISO DE LLEGADA", "SERVICIOS ESPECIALES", "VALOR TOTAL PORTES Y TASAS");
    $head_table_size = array(57, 90, 60, 200, 120, 53, 44, 74, 72, 55, 88, 65, 75, 80);
    $attr = array('titleFontSize' => 10, 'titleText' => '');
    $arpdf_tmp = "../bodega/pdfs/planillas/$dependencia_" . date("Ymd_hms") . "_jhlc.pdf";
    $pdf->SetFont('Arial', '', 8);
    $pdf->usuario = $usua_nomb;
    $pdf->dependencia = $dependencianomb;
    $pdf->depe_municipio = $depe_municipio;
    $pdf->entidad_largo = $db->entidad_largo;
    $total_registros = 0;
    $pdf->lmargin = 0.2;
    $i_total3 = 0;
    do {  // Amplia
        include "$ruta_raiz/include/query/radsalida/queryListado_planillas_normal.php";
        $pdf->planilla = $no_planilla;

// Si la variable $generar_listado_existente viene entonces este if genera la planilla existente
        if ($generar_listado_existente) {
            $where_isql = $where_isql1;
        } else {  // o genera una nueva...
            $where_isql = $where_isql2;
        }
        $query_t = $query . $where_isql . $order_isql;
        $_SESSION["SQLTMPLISTADO"] = "select SGD_RENV_NOMBRE,SGD_RENV_DIR,SGD_RENV_DESTINO,SGD_RENV_DEPTO,SGD_RENV_PESO,RADI_NUME_SAL from SGD_RENV_REGENVIO ".$where_isql . $order_isql;
        $pdf->oracle_report($db, $query_t, false, $attr, $head_table, $head_table_size, $arpdf_tmp, 0, 31);
        if ($i_total3 == 0) {
            $i_total3 = $pdf->numrows;
            $total_registros += $i_total3;
        }

        if ($generar_listado_existente) {
            $i_total3 = 0;
        } else {
            error_reporting(7);

            $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
            $nregis = 0;
            $rsParaUp = $db->conn->Execute("select * from sgd_renv_regenvio  $where_isql $order_isql");
            $rvCodigo = $rsParaUp->fields["SGD_RENV_CODIGO"];
            if ($rvCodigo) {
                while (!$rsParaUp->EOF) {
                    $nregis = $nregis + 1;
                    $rsParaUp->MoveNext();
                }
            }
            if ($nregis > 0) {
                $rsParaUp = $db->conn->Execute("select * from sgd_renv_regenvio  $where_isql $order_isql");
                if ($nregis <= 32)
                    $maximo = $nregis; else
                    $maximo = 32;
                for ($cont = 1; $cont <= $maximo; $cont++) {
                    $renv_codigo = $rsParaUp->fields["SGD_RENV_CODIGO"];
                    include "$ruta_raiz/include/query/radsalida/queryListado_planillas_normal.php";
                    $update_isql = "update sgd_renv_regenvio set sgd_renv_planilla='$no_planilla' $wrc";
                    $rs = $db->query($update_isql);
                    $rsParaUp->MoveNext();
                }
            }
        }
        $no_planilla++;
        $iii++;
        $i_total3 = $i_total3 - 32;
    } while ($i_total3 > 0);
    $pdf->Output($arpdf_tmp);
}
if($total_registros > 0){
?>
<TABLE BORDER=0 WIDTH=100%>
    <TR>
        <TD class="etextomenu"  align="center">
            <b>Se han Generado <?php echo $total_registros; ?> Registros para Imprimir en <?php echo $paginas; ?> Planillas. <br>
                <a href='<?php echo $arpdf_tmp; ?>' target='<?php echo date("dmYh") . time("his"); ?>'>Abrir Archivo</a>
                <a href='exportaHojaCalculo.php?opc=listado' target='_new'>Abrir hoja de cálculo</a></b>
        </td>
    </TR>
</TABLE>
<?php }else{ ?>
<div id="divErrorResultados">No se encontraron registros para imprimir</div>
<?php } ?>
</body>