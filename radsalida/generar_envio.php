<?php
session_start();
error_reporting(7);
$ruta_raiz = "..";
if (!isset($_SESSION['dependencia']))
    include "../rec_session.php";
include_once "../include/db/ConnectionHandler.php";
//$db->conn->debug = true;
if (!defined('ADODB_FETCH_ASSOC'))
    define('ADODB_FETCH_ASSOC', 2);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$db = new ConnectionHandler("..");
if (!$fecha_busq)
    $fecha_busq = date("Y-m-d");
$mensajeAlert = "";
if (isset($_POST["ibtnModifica"]) && !empty($_POST["plaAnt"]) && !empty($_POST["plaNuevo"])) {
    $fecha_mes = substr($fecha_busq, 0, 7);
    // conte de el ultimo numero de planilla generado.
    $sqlChar = $db->conn->SQLDate("Y-m", "SGD_RENV_FECH");
    //include "$ruta_raiz/include/query/radsalida/queryGenerar_envio.php";	
    $query = "update sgd_renv_regenvio set sgd_renv_planilla = '{$_POST["plaNuevo"]}'
                WHERE DEPE_CODI = $dependencia                 
                AND " . $db->conn->length . "(sgd_renv_planilla) > 0 
                AND sgd_fenv_codigo = $tipo_envio 
                AND sgd_renv_planilla = '{$_POST["plaAnt"]}'";
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
    $rs = $db->query($query);
    $mensajeAlert = "Se ha actualizado correctamente el número de planilla";
}
?>
<head>
    <link rel="stylesheet" href="../estilos/orfeo.css">
    <style type="text/css">
        #divErrorResultados{
            color: white;
            background-color: red;
            font-weight: bolder;
            padding: .5%;
        }
        .tbModifica caption{
            font-size: 14px;
            font-weight: bolder;
        }
        .tbModifica{
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10px;
            border-collapse: collapse;            
            display: <?php echo (isset($_POST["tipo_envio"]) && !empty($_POST["tipo_envio"])) ? "block" : "none"; ?>;
        }
        .tbModifica td{
            text-align: center;
            padding: 0%;
            margin: 0%;
        }
        .tbModifica input{
            font-family: Verdana;
            font-size: 10px;
            color: #000000;
            width: 60%;
        }
        #ibtnModifica{
            margin-bottom: 4px;
        }
        #tdmodifica{
            background-color: #E3E8EC;
            /*            width:100%;*/
        }
    </style>
</head>
<script type="text/javascript">
    function validar(action){
        if(action!=2){
            document.new_product.action = "generar_envio.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fecha_h=$fechah" ?>&generar_listado_existente= Generar Plantilla existente ";
        }else{
            document.new_product.action = "generar_envio.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fecha_h=$fechah" ?>&generar_listado= Generar Nuevo Envio ";
        }
    }
    function rightTrim(sString){
        while (sString.substring(sString.length-1, sString.length) == ' '){	
            sString = sString.substring(0,sString.length-1);  
        }
        return sString;
    }

    function solonumeros()
    {	jh =  document.getElementById('no_planilla');
        if(rightTrim(jh.value) == "" || isNaN(jh.value))
        {	alert('Solo introduzca numeros.' );
            jh.value = "";
            jh.focus();
            return false;
        }
        else
        {	document.new_product.submit();	}
    }
<?php if ($mensajeAlert !== "") { ?>
            alert("<?php echo $mensajeAlert; ?>");
<?php } ?>
</script>
<BODY>
    <div id="spiffycalendar" class="text"></div>
    <link rel="stylesheet" type="text/css" href="../js/spiffyCal/spiffyCal_v2_1.css">
    <script language="JavaScript" src="../js/spiffyCal/spiffyCal_v2_1.js"></script>
    <script language="javascript">
        var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "fecha_busq","btnDate1","<?= $fecha_busq ?>",scBTNMODE_CUSTOMBLUE);
    </script>
    <table class=borde_tab width='100%' cellspacing="5"><tr><td class=titulos2><center>GENERACION PLANILLAS Y GUIAS DE CORREO</center></td></tr></table>
<table><tr><td></td></tr></table>
<form name="new_product"  action='generar_envio.php?<?= session_name() . "=" . session_id() . "&krd=$krd&fecha_h=$fechah" ?>' method="post">
    <center>
        <TABLE width="450" class="borde_tab" cellspacing="5">
            <!--DWLayoutTable-->
            <TR>
                <TD width="125" height="21"  class='titulos2'> Fecha<br>
                    <?
                    echo "(" . date("Y-m-d") . ")";
                    ?>
                </TD>
                <TD width="225" align="right" valign="top" class='listado2'>

                    <script language="javascript">
                        dateAvailable.date = "2003-08-05";
                        dateAvailable.writeControl();
                        dateAvailable.dateFormat="yyyy-MM-dd";
                    </script>
                </TD>
            </TR>
            <TR>
                <TD height="26" class='titulos2'> Desde la Hora</TD>
                <TD valign="top" class='listado2'>
                    <?
                    if (!$hora_ini)
                        $hora_ini = 01;
                    if (!$hora_fin)
                        $hora_fin = date("H");
                    if (!$minutos_ini)
                        $minutos_ini = 01;
                    if (!$minutos_fin)
                        $minutos_fin = date("i");
                    if (!$segundos_ini)
                        $segundos_ini = 01;
                    if (!$segundos_fin)
                        $segundos_fin = date("s");
                    ?>
                    <select name="hora_ini" class='select'>
                        <?
                        for ($i = 0; $i <= 23; $i++) {
                            if ($hora_ini == $i) {
                                $datoss = " selected ";
                            } else {
                                $datoss = " ";
                            }
                            ?>
                            <option value='<?= $i ?>' '<?= $datoss ?>'>
                            <?= $i ?>
                        </option>
                        <?
                    }
                    ?>
                </select>:<select name=minutos_ini class='select'>
                    <?
                    for ($i = 0; $i <= 59; $i++) {
                        if ($minutos_ini == $i) {
                            $datoss = " selected ";
                        } else {
                            $datoss = " ";
                        }
                        ?>
                        <option value='<?= $i ?>' '<?= $datoss ?>'>
                        <?= $i ?>
                    </option>
                    <?
                }
                ?>
            </select>
        </TD>
    </TR>
    <Tr>
        <TD height="26" class='titulos2'> Hasta</TD>
        <TD valign="top" class='listado2'><select name=hora_fin class=select>
                <?
                for ($i = 0; $i <= 23; $i++) {
                    if ($hora_fin == $i) {
                        $datoss = " selected ";
                    } else {
                        $datoss = " ";
                    }
                    ?>
                    <option value='<?= $i ?>' '<?= $datoss ?>'>
                    <?= $i ?>
                </option>
                <?
            }
            ?>
        </select>:<select name=minutos_fin class=select>
            <?
            for ($i = 0; $i <= 59; $i++) {
                if ($minutos_fin == $i) {
                    $datoss = " selected ";
                } else {
                    $datoss = " ";
                }
                ?>
                <option value='<?= $i ?>' '<?= $datoss ?>'>
                <?= $i ?>
            </option>
            <?
        }
        ?>
    </select>
</TD>
</TR>
<tr>
    <TD height="26" class='titulos2'>Tipo de Salida</TD>
    <TD valign="top" align="left" class='listado2'>

        <?php
        $sqlfenv = "select  " . $db->conn->Concat("sgd_fenv_codigo", "'-'", " sgd_fenv_descrip") . ",sgd_fenv_codigo from sgd_fenv_frmenvio order by 2";
        $rs = $db->conn->Execute($sqlfenv);

        echo $rs->GetMenu2('tipo_envio', $tipo_envio, "0:&lt;&lt; SELECCIONE &gt;&gt;", false, 0, "class='select' onChange='this.form.submit();'");
        ?>
    </TD>
</tr>
<tr>
    <td colspan="9" id="tdModifica">
        <table class="tbModifica">
            <caption>Modificar planilla</caption>
            <tr>
                <th>Planilla antigüa</th>
                <th>Planilla Nueva</th>
                <th></th>
            </tr>
            <tr>
                <td><input type="text" id="plaAnt" name="plaAnt" /></td>
                <td><input type="text" id="plaNueva" name="plaNuevo" /></td>
                <td><button type="submit" id="ibtnModifica" name="ibtnModifica" >Modificar</button></td>
            </tr>
        </table>
    </td>

</tr>
<? if ($tipo_envio != 105) { ?>
    <tr>
        <TD height="26" class='titulos2'>Numero de Planilla</TD>
        <TD valign="top" align="left" class='listado2'>
            <input type="text" name="no_planilla" id="no_planilla" value='<?= $no_planilla ?>' class='tex_area' size=11 maxlength="9" >
            <?php
            $fecha_mes = substr($fecha_busq, 0, 7);
            // conte de el ultimo numero de planilla generado.
            $sqlChar = $db->conn->SQLDate("Y-m", "SGD_RENV_FECH");
            //include "$ruta_raiz/include/query/radsalida/queryGenerar_envio.php";	
            $query = "SELECT sgd_renv_planilla, sgd_renv_fech FROM sgd_renv_regenvio
				WHERE DEPE_CODI=$dependencia AND $sqlChar = '$fecha_mes'
					AND " . $db->conn->length . "(sgd_renv_planilla) > 0 
					AND sgd_fenv_codigo = $tipo_envio ORDER BY sgd_renv_fech desc, SGD_RENV_PLANILLA desc";
            $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
            $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
            $rs = $db->query($query);
            if ($rs) {
                $planilla_ant = $rs->fields["SGD_RENV_PLANILLA"];
                $fecha_planilla_ant = $rs->fields["SGD_RENV_FECH"];
            }
            echo "<br><span class=etexto>&Uacute;ltima planilla generada : <b> $planilla_ant </b>  Fec:$fecha_planilla_ant";
            // Fin conteo planilla generada
            ?>
        </TD>
    <tr><? } ?>
    <td height="26" colspan="2" valign="top" class='titulos2'> <center>
    <INPUT TYPE=button name=generar_listado_existente Value=' Generar Plantilla existente ' class='botones_funcion' onClick="validar(1);">
</center>
</td>
</tr>
<tr><td height="26" colspan="2" valign="top" class='titulos2'> <center>
    <INPUT TYPE=button name=generar_listado Value=' Generar Nuevo Envio ' class='botones_largo' onClick="validar(2);">
</center></td>
</tr>
</TABLE>
</form>
<table><tr><td></td></tr></table>
<?php
if (!$fecha_busq)
    $fecha_busq = date("Y-m-d");
if ($generar_listado or $generar_listado_existente) {
    if ($tipo_envio == 101) {
        error_reporting(7);
        if ($generar_listado_existente) {
            $generar_listado = "Genzzz";
            echo "<table class=borde_tab width='100%'><tr><td class=listado2><CENTER>Generar Listado Existente</td></tr></table>";
        }
        include "./listado_planillas.php";
        echo "<table class=borde_tab width='100%'><tr><td class=titulos2><CENTER>FECHA DE BUSQUEDA $fecha_busq</cebter></td></tr></table>";
    }
    if ($tipo_envio == 105) {
        include "./listado_guias.php";
        echo "<table class=borde_tab width='100%'><tr><td class=listado2><CENTER>FECHA DE BUSQUEDA $fecha_busq </center></td></tr></table>";
    }
    if ($tipo_envio == 108) {
        echo "<table class=borde_tab width='100%'><tr><td class=titulos2><CENTER>PLANILLA NORMAL</center></td></tr></table>";
        if ($generar_listado_existente)
            $generar_listado = "Genzzz";
        include "./listado_planillas_normal.php";
        echo "<table class=borde_tab width='100%'><tr><td class=titulos2><CENTER>FECHA DE BUSQUEDA $fecha_busq </center></td></tr></table>";
    }
    if ($tipo_envio == 109) {
        echo "<table class=borde_tab width='100%'><tr><td class=titulos2><CENTER>PLANILLA ACUSE DE RECIBO</center></td></tr></table>";
        if ($generar_listado_existente)
            $generar_listado = "Genzzz";
        include "./lPlanillaAcuseR.php";
        echo "<table class=borde_tab width='100%'><tr><td class=listado2><CENTER>FECHA DE BUSQUEDA $fecha_busq </td></tr></table>";
    }
}
?>
