<?
session_start();
if (!$ruta_raiz)
    $ruta_raiz = ".";
include("$ruta_raiz/config.php");
define('ADODB_ASSOC_CASE', 1);
include($ADODB_PATH . '/adodb.inc.php'); // $ADODB_PATH configurada en config.php
$error = 0;
$dsn = $driver . "://" . $usuario . ":" . $contrasena . "@" . $servidor . "/" . $servicio;
$nombreTp3 = $tip3Nombre[3][$ent];
$conn = NewADOConnection($dsn);
if ($conn) {   //$conn->debug=true;
    include "$ruta_raiz/include/class/DatoOtros.php";
    $objOtro = new DatoOtros($conn);
    if ($_GET['dir_codigo_us1']) {
        $dato_dir_direccion1 = $objOtro->obtieneDatosDir($_GET['dir_codigo_us1']);
        $datos1 = $objOtro->obtieneDatosReales($_GET['dir_codigo_us1']);
        $otro_us11 = $datos1[0]['NOMBRE'];
        $dpto_nombre_us11 = $dato_dir_direccion1[0]['DEPARTAMENTO'];
        $direccion_us11 = $dato_dir_direccion1[0]['DIRECCION'];
        $muni_nombre_us11 = $dato_dir_direccion1[0]['MUNICIPIO'];
        $nombret_us11 = $datos1[0]['APELLIDO'];
    }
    if ($_GET['dir_codigo_us2']) {
        $dato_dir_direccion2 = $objOtro->obtieneDatosDir($_GET['dir_codigo_us2']);
        $datos2 = $objOtro->obtieneDatosReales($_GET['dir_codigo_us2']);
        $otro_us2 = $datos2[0]['NOMBRE'];
        $dpto_nombre_us2 = $dato_dir_direccion2[0]['DEPARTAMENTO'];
        $muni_nombre_us2 = $dato_dir_direccion2[0]['MUNICIPIO'];
        $direccion_us2 = $dato_dir_direccion2[0]['DIRECCION'];
        $nombret_us2 = $datos2[0]['APELLIDO'];
    }
    if ($_GET['dir_codigo_us3']) {
        $objOtro->setdatoEnt($_GET['dir_codigo_us3']);
        $datos3 = $objOtro->getdatoEnt();
        $dpto_nombre_us3 = $datos3[0]['DEPARTAMENTO'];
        $muni_nombre_us3 = $datos3[0]['MUNICIPIO'];
        $direccion_us3 = $datos3[0]['DIRECCION'];
        $nombret_us3 = $datos3[0]['NOMBRE'];
    }

    $enviado = "";
    if (isset($_POST['sololect'])) {
        $solec = "checked";
    } else if ($sol_lect == 'S') {
        $solec = "checked";
    }
    else
        $solec = "";
    //borra otros destinatarios o cc
    if ($borrar) {
        $isql = "delete from sgd_dir_drecciones
		         where sgd_anex_codigo='$codigo' and sgd_dir_tipo = $borrar ";
        $rsBorra = $conn->Execute($isql);
        $rsBorra ? $error = 7 : $error = 8;
    }
    if ($ent != 2 and !$codigo) {
        $sqlAnex = "select * from anexos where anex_radi_nume=$radi and anex_salida = 1 and anex_borrado <> 'S'";
        $ADODB_COUNTRECS = true;
        $rsAnex = $conn->Execute($sqlAnex);
        $ADODB_COUNTRECS = true;
        $nAnex = $rsAnex->RecordCount();
        if ($nAnex == 0) {
            $sqlAsun = "select ra_asun from radicado  where radi_nume_radi = $numrad";
            $rsAsun = $conn->Execute($sqlAsun);
            $descr = $rsAsun->fields['RA_ASUN'];
            $primero = 1;
        }
    }

    //datos si ya fue anexado y radicado
    if ($codigo) {
        $q_Anex = "select CODI_NIVEL
                ,ANEX_SOLO_LECT
                ,ANEX_CREADOR
                ,ANEX_DESC
                ,ANEX_TIPO_EXT
                ,ANEX_NUMERO
                ,ANEX_RADI_NUME 
                ,ANEX_NOMB_ARCHIVO AS nombre
                ,ANEX_SALIDA,ANEX_ESTADO,SGD_DIR_TIPO,RADI_NUME_SALIDA,SGD_DIR_DIRECCION from anexos, anexos_tipo,radicado " .
                "where anex_codigo='$codigo' and anex_radi_nume=radi_nume_radi and anex_tipo=anex_tipo_codi";

        $conn->SetFetchMode(ADODB_FETCH_ASSOC);
        $rsAnex = $conn->Execute($q_Anex);
        if (!$rsAnex->EOF && $rsAnex) {
            $docunivel = ($rsAnex->fields["CODI_NIVEL"]);
            $remitente = $rsAnex->fields["SGD_DIR_TIPO"];
            $extension = $rsAnex->fields["ANEX_TIPO_EXT"];
            $radicado_salida = $rsAnex->fields["ANEX_SALIDA"];
            $anex_estado = $rsAnex->fields["ANEX_ESTADO"];
            $descr = $rsAnex->fields["ANEX_DESC"];
            $radsalida = $rsAnex->fields["RADI_NUME_SALIDA"];
            $direccionAlterna = $rsAnex->fields["SGD_DIR_DIRECCION"];
        }
        if ($radsalida) {
            $chk = " checked ";
            $dsbl = " disabled=true ";
            $sqlEnvDest = "select sgd_dir_tipo, sgd_deve_fech from sgd_renv_regenvio where radi_nume_sal=$radsalida and sgd_dir_tipo=1";
            $ADODB_COUNTRECS = true;
            $rsEnvDest = $conn->Execute($sqlEnvDest);
            $ADODB_COUNTRECS = false;
            if ($rsEnvDest && $rsEnvDest->RecordCount() > 0) {
                $enviado = "disabled";
                //$rsEnvDest->fields['SGD_DEVE_FECH']?$enviado="":0;
            }
        }
        if ($tpradic != 0)
            $chk = " checked ";
        $ro = "disabled";
    }

    //llena combo de tipo de anexos
    $sqlConcat = $conn->Concat("ANEX_TIPO_DESC", "' - '", "'(.'", "ANEX_TIPO_EXT", "')'");
    $q_tAnex = "select  $sqlConcat,ANEX_TIPO_CODI,ANEX_TIPO_EXT from anexos_tipo order by anex_tipo_desc desc";
    $rs_tAnex = $conn->Execute($q_tAnex);
    $rs_tAnex_aux = $conn->GetArray($q_tAnex); //arreglo para validar javascript escogio_archivo()
    $rs_tAnex ? $sel_tAnex = $rs_tAnex->GetMenu2("tipo", $tipo, false, false, 0, "class='select' id='tipo_clase' $ro") : $error = 1;

    //llena combo de Tipos de radicacion
    foreach ($_SESSION["tpNumRad"]as $key => $valueTp) {
        if ($tpPerRad[$valueTp] == 2 or $tpPerRad[$valueTp] == 3)
            $radtp[] = $valueTp;
        else
            $radtp[] = 0;
    }
    $sqlConcat = $conn->Concat("SGD_TRAD_DESCR", "' '", "'(-'", "SGD_TRAD_CODIGO", "')'");
    $q_tRad = "select  $sqlConcat,SGD_TRAD_CODIGO from SGD_TRAD_TIPORAD where SGD_TRAD_CODIGO in (" . implode(',', $radtp) . ")";
    $rs_tRad = $conn->Execute($q_tRad);
    $rs_tRad ? $sel_tRad = $rs_tRad->GetMenu2("tpradic", $tpradic, "0:&lt;&lt;Seleccione &gt;&gt;", false, 0, "class='select' $dsbl") : $error = 1;

    //trae los datos Expediente
    $q_exp = "SELECT DISTINCT SGD_EXP_NUMERO as valor, SGD_EXP_NUMERO as etiqueta, SGD_EXP_FECH as fecha";
    $q_exp .= " FROM SGD_EXP_EXPEDIENTE ";
    $q_exp .= " WHERE RADI_NUME_RADI = " . $numrad;
    $q_exp .= " AND SGD_EXP_ESTADO <> 2";
    $q_exp .= " ORDER BY fecha desc";
    $ADODB_COUNTRECS = true;
    $rs_exp = $conn->Execute($q_exp);
    $ADODB_COUNTRECS = false;
    $rs_exp->RecordCount() ? $sel_exp = $rs_exp->GetMenu2("expIncluidoAnexo", $expIncluidoAnexo, false, false, 0, " multiple class='select'") : $sel_exp = "<b>EL RADICADO PADRE NO ESTA INCLUIDO  EN UN EXPEDIENTE.</b>";

    //agrega nuevos destinatarios
    if ($cc) {
        $nombre_us1 = $_POST["nombre_us1"];
        $prim_apel_us1 = $_POST["prim_apel_us1"];
        $seg_apel_us2 = $_POST["seg_apel_us1"];
        if ((!empty($nombre_us1) or !empty($prim_apel_us1) or !empty($seg_apel_us2)) and $direccion_us1 and $muni_us1 and $codep_us1) {
            $isql = "select sgd_dir_tipo as NUM
					from sgd_dir_drecciones
				 where
					sgd_dir_tipo like '7%' and sgd_anex_codigo='$codigo'
					order by sgd_dir_tipo desc";
            $rsCC = $conn->Execute($isql);
            if (!$rsCC->EOF)
                $num_anexos = substr($rsCC->fields["NUM"], 1, 2);
            $nurad = $radi;
            if (!$conexion)
                $conexion = new ConnectionHandler($ruta_raiz);
            include "$ruta_raiz/radicacion/grb_direcciones.php";
            $error = 5;
        }
        else {
            $error = 6;
        }
    }

    ///datos a enviar
    $variables = "ent=$ent&radi=$radi&krd=$krd&" . session_name() . "=" . trim(session_id()) . "&usua=$krd&contra=$drde&tipo=$tipo&ent=$ent&codigo=$codigo&numrad=$numrad&sololect=$sololect&radicado_rem=$radicado_rem&dir_codigo_us1=" . $_GET['dir_codigo_us1'] . "&dir_codigo_us2=" . $_GET['dir_codigo_us2'] . "&dir_codigo_us3=" . $_GET['dir_codigo_us3'];

    //otros usuarios
    if ($id_Dir_otro) {
        $datos_otros_dir = $objOtro->obtieneDatosDir($id_Dir_otro);
        $datos_otros = $objOtro->obtieneDatosReales($id_Dir_otro);
        $datos_otros ? $otro_dest = "<br>" . $datos_otros[0]["NOMBRE"] . " " . $datos_otros[0]["APELLIDO"] . "<br>" . $datos_otros_dir[0]["DIRECCION"] . "<br>" . $datos_otros_dir[0]["DEPARTAMENTO"] . "/" . $datos_otros_dir[0]["MUNICIPIO"] : 0;
    }
    else
        $radicado_rem == 1;
    include_once "$ruta_raiz/include/query/queryNuevo_archivo.php";
    $isql = $query1;
    $ADODB_COUNTRECS = true;
    $rs = $conn->Execute($isql);
    $ADODB_COUNTRECS = false;
    $i_copias = 0;
    if ($rs && $rs->RecordCount() > 0) {
        while (!$rs->EOF) {
            $sqlRenv = "select sgd_dir_codigo, sgd_deve_fech from sgd_renv_regenvio where sgd_dir_codigo=" . $rs->fields["SGD_DIR_CODIGO"];
            $ADODB_COUNTRECS = true;
            $rsRenv = $conn->Execute($sqlRenv);
            $ADODB_COUNTRECS = false;
            $val = $rsRenv->RecordCount();
            $i_copias++;
            $sgd_ciu_codigo = "";
            $sgd_esp_codi = "";
            $sgd_oem_codi = "";
            $sgd_ciu_codi = $rs->fields["SGD_CIU_CODIGO"];
            $sgd_esp_codi = $rs->fields["SGD_ESP_CODI"];
            $sgd_oem_codi = $rs->fields["SGD_OEM_CODIGO"];
            $sgd_dir_tipo = $rs->fields["SGD_DIR_TIPO"];
            $sgd_doc_fun = $rs->fields["SGD_DOC_FUN"];

            if ($sgd_ciu_codi > 0) {
                $isql = "select SGD_CIU_NOMBRE AS NOMBRE,SGD_CIU_APELL1 AS APELL1,SGD_CIU_APELL2 AS APELL2,SGD_CIU_CEDULA AS IDENTIFICADOR,SGD_CIU_EMAIL AS MAIL,SGD_CIU_DIRECCION  AS DIRECCION from sgd_ciu_ciudadano where sgd_ciu_codigo=$sgd_ciu_codi";
            }
            if ($sgd_esp_codi > 0) {
                $isql = "select nombre_de_la_empresa AS NOMBRE, identificador_empresa AS IDENTIFICADOR,EMAIL AS MAIL,DIRECCION AS DIRECCION from bodega_empresas where identificador_empresa=$sgd_esp_codi";
            }
            if ($sgd_oem_codi > 0) {
                $isql = "select sgd_oem_oempresa AS NOMBRE, SGD_OEM_DIRECCION AS DIRECCION, sgd_oem_codigo AS IDENTIFICADOR from sgd_oem_oempresas  where sgd_oem_codigo=$sgd_oem_codi";
            }
            if ($sgd_doc_fun > 0) {
                $isql = "select usua_nomb AS NOMBRE, d.depe_nomb AS DIRECCION, usua_doc AS IDENTIFICADOR, usua_email AS MAIL from usuario u ,dependencia d  where usua_doc='$sgd_doc_fun'
                          and  u.DEPE_CODI = d.DEPE_CODI ";
            }
//            exit($isql);
            $rs2 = $conn->Execute($isql);
            if ($rs2 && !$rs2->EOF) {
                $otros_usuarios .="   <tr>
                                        <td  class='listado2'>
                                        <font size=1>" .
                        $rs2->fields["IDENTIFICADOR"] .
                        "</font>
                                        </td>
                                        <td class='listado2' colspan='2'>&nbsp;
                                        <font size=1>" .
                        $rs2->fields["NOMBRE"] . " " .
                        $rs2->fields["APELL1"] . " " .
                        $rs2->fields["APELL2"] . "&nbsp;
                                        </font>
                                        </td>
                                        <td  class='listado2' colspan='1'>&nbsp;
                                        <font size=1>" .
                        $rs->fields["SGD_DIR_NOMBRE"] . "&nbsp;
                                        </font>
                                        </td>
                                        <td align='center' class='listado2'>&nbsp;
                                        <font size=1>" .
                        $rs2->fields["DIRECCION"] . "
                                        </font>
                                        </td>
                                        <td width='68' align='center' class='listado2'>&nbsp;
                                        <font size=1>" .
                        $rs2->fields["MAIL"] . "
                                        </font>
                                        </td>
                                        <td width='68' align='center' class='listado2'>&nbsp;
                                        <font size=1>";
                if (!$val) {
                    $otros_usuarios.="   <a href='nuevo_archivo.php?$variables&borrar=$sgd_dir_tipo&tpradic=$tpradic&radicado_rem=$radicado_rem'>Borrar</a>";
                } else {
                    $rsRenv->fields['SGD_DEVE_FECH'] ? $otros_usuarios.="Devuelto" : $otros_usuarios.="Enviado";
                    $i_copias = $i_copias - 1;
                }
                $otros_usuarios.="   </font>
                                        </td>
                                        </tr>";
            }
            $rs->MoveNext();
        }
    }
}
else
    $error = 0;
if ($resp1 == "OK")
    $error = 2;
else if ($resp1 == "ERROR")
    $error = 3;
if ($error) {
    $alert = '<tr bordercolor="#FFFFFF">
			<td width="3%" align="center" class="titulosError" colspan="3" bgcolor="#FFFFFF">';
    switch ($error) {
        case 0: //NO CONECCION A BD
            $alert .="Error al conectar a BD, comun&iacute;quese con el Administrador de sistema !!";
            break;
        case 1: //ERROR EJECUCCIÓN SQL
            $alert .="Error al cargar datos de tipos de anexos";
            break;
        case 2: //ARCHIVO SUBIDO SATISFACTORIAMENTE
            $alert .="Archivo anexado correctamente";
            break;
        case 3: //NO SUBIO EL ARCHIVO
            $alert .="Error al anexar archivos!";
            break;
        case 4: //NO AENXO DESTINATARIO
            $alert .="NO ANEXO NING&Uacute;N DESTINATARIO";
            break;
        case 5: //ANEXO DESTINATARIO
            $alert .="Ha sido agregado el destinatario.";
            break;
        case 6: //NO ANEXO DESTINATARIO
            $alert .="No se pudo grabar otro destinatario, ya que faltan datos.(Los datos m&iacute;nimos de envio: Nombre, direccion, departamento, municipio)";
            break;
        case 7: //Se borra otro destinatario linea 22.
            $alert .="Se borr&oacute; destinatario correctamente";
            break;
        case 8: //Error borrando destnatario linea 22
            $alert .="Error Borrando destinatario";
            break;
    }
    $alert .= '</td></tr>';
}
?>
<html>
    <head>
        <title>Informaci&oacute;n de Anexos</title>
        <link rel="stylesheet" href="estilos/orfeo.css">
        <SCRIPT Language="JavaScript" SRC="js/crea_combos_2.js"></SCRIPT>
        <script language="javascript">
            function habilitar()
            { <? if ($chk) { ?>
                    document.formulario.radicado_salida.checked=true;
                    mostrarForm();
<? } ?>
<? if ($dsbl) { ?>
            document.formulario.radicado_salida.disabled=true;
<? } ?>
<? if ($enviado == true) { ?>
            document.formulario.radicado_rem.disabled=true;
<? } ?>
<? if (!$codigo) { ?>
            document.getElementById("tbl_otros").style.display = 'none';
<? } ?>
<? if ($primero) { ?>
            document.formulario.radicado_salida.checked=true;
            document.formulario.tpradic.value=<?= $ent ?>;
            mostrarForm();
<? } ?>
     
    }
    function mostrar(nombreCapa)
    {
        document.getElementById(nombreCapa).style.display="";
    }
    function continuar_grabar(){	
        document.formulario.tpradic.disabled=false;
        document.formulario.action=document.formulario.action+"&cc=GrabarDestinatario";
        document.formulario.submit();
    }
    function mostrarNombre(nombreCapa)
    {
        document.formulario.elements[nombreCapa].style.display="";
    }
    function ocultarNombre(nombreCapa)
    {
        document.formulario.elements[nombreCapa].style.display="none";
    }
    function ocultar(nombreCapa)
    {
        document.getElementById(nombreCapa).style.display="none";
    }
    function procEst(dato1,dato2,valor)
    {
    }
    function Start(URL, WIDTH, HEIGHT)
    {
        windowprops = "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes,width=1020,height=500";
        preview = window.open(URL , "preview", windowprops);
    }


    function f_close(){
        //window.history.go(0);
        opener.regresar();
        window.close();
    }

    function regresar(){
        f_close();
    }

    function escogio_archivo()
    { 
        var valor,lonExt;
        document.getElementById('tipo_clase').disabled = false;
        archivo_up = document.getElementById('userfile').value;
        valor=0;
        lonExt= archivo_up.lastIndexOf('.')+1;
        extension = archivo_up.substr(lonExt).toLowerCase();
<?php
foreach ($rs_tAnex_aux as $i) {
    echo "\tif (extension=='" . $i["ANEX_TIPO_EXT"] . "')	{valor=" . $i["ANEX_TIPO_CODI"] . "; }\n";
}
if ($radsalida) {
    ?>
                if(document.getElementById('tipo_clase').value==14 && valor!=14)
                {
                    alert("El archivo que escogi\363 debe ser de tipo ODT.");
                    document.getElementById('tipo_clase').disabled = true;
                    return false;
                }
<? } ?>
        document.getElementById('tipo_clase').value = valor;
        if(document.getElementById('radicado_salida').checked==true && valor!=14 && valor!=16)
        {
            alert("Atenci\363n. Si el archivo no es ODT o XML no podr\341 realizar combinaci\363n de correspondencia. \n\n otros archivos no facilitan su acceso");
            document.formulario.radicado_salida.checked=false;
            mostrarForm();
            document.formulario.tpradic.value=0;
            return true;
        }  
    }



    function actualizar()
    {	retorno = true;
        msg = '';
        if(!document.formulario.radicado_salida.checked && document.formulario.tpradic.value!=0)
        {
            msg = msg + '\n' + "Debe marcar 'Documento ser\xE1 radicado o quitar seleccion en \xABTipo de radicaci\xF3n\xBB'";
            retorno = false;
        }
        if(document.formulario.radicado_salida.checked == true)
        {
            if(document.formulario.tpradic.value==0)
            {
                msg = msg + '\n' + "Debe seleccionar un tipo de radicaci\xF3n";
                retorno = false;
            }
            if(document.formulario.descr.value.length <6)
            {
                msg = msg + '\n' + "Debe llenar el campo asunto con minimo 6 caracteres"+"(Digitos:"+document.formulario.descr.value.length+")";
                retorno = false;
            }
            var destino = document.getElementsByName('radicado_rem');
            if (destino[0].checked == false && destino[1].checked == false && destino[2].checked == false && destino[3].checked == false)
            {
                msg = msg + '\n' + "Debe seleccionar el tipo de destinatario.";
                retorno = false;
            } 
        }
        if(document.formulario.descr.value.length > 349)
        {
            msg = msg + '\n' + 'Demasiados caracteres en el texto asunto, solo se permiten 350';
            retorno = false;
        }
        if(document.getElementById("rusuario").checked==false && document.getElementById("rotro").checked==false)
        {
<?php
if ($_GET['dir_codigo_us2'] && $_GET['dir_codigo_us3']) {
    ?>
                    if(document.getElementById("rempre").checked==false && document.getElementById("rpredi").checked==false)
                    {
                        msg = msg + '\n' + "Debe seleccionar un destinatario";
                        retorno = false;
                    }
    <?php
}
?>
<?php
if (!$_GET['dir_codigo_us2'] && !$_GET['dir_codigo_us3']) {
    ?>
                    if(document.getElementById("rempre").checked==true || document.getElementById("rpredi").checked==true)
                    {
                        msg = msg + '\n' + "Debe seleccionar un destinatario";
                        retorno = false;
                    }
    <?php
}
?>
        }
        if(!document.formulario.id_Dir_otro.value && document.getElementById("rotro").checked==true)
        {
            if(document.getElementById("i_copias").value==0 && document.getElementById("rotro").checked==true)
            {
                msg = msg + '\n' + "!!No agreg\xf3 ning\xFAn Destinatario!!";
                retorno = false;
            }
        }
<?php if (!$codigo) { ?>
            archivo = document.getElementById('userfile').value;
            if (archivo=="")
            {
                msg = msg + '\n' + 'Por favor escoja un archivo';
                retorno = false;
            }
            else
            {
                if(retorno)
                {
                    msg = msg;
                    retorno = escogio_archivo();
                }
            }
<?php } else {
    ?>
                archivo=document.getElementById('userfile').value;
                if (archivo!="")
                {
                    msg = msg;
                    retorno = escogio_archivo();
                }
                else if(document.getElementById('tipo_clase').value != 14)
                {
                    document.formulario.radicado_salida.checked=false;
                    mostrarForm();
                    document.formulario.tpradic.value=0
                }
<?php } ?>
        document.formulario.radicado_salida.disabled=false;
        document.formulario.tpradic.disabled=false;
        if (msg!='') alert(msg);
        return retorno;
    }

    function mostrarForm()
    {
        var tipifica = document.formulario.radicado_salida.checked;
        if(tipifica)
            document.getElementById("anexaExp").style.display = 'block';
        else
            document.getElementById("anexaExp").style.display = 'none';
    }
        </script>
    </head>
    <body bgcolor="#FFFFFF" topmargin="0">
        <div id="spiffycalendar" class="text"></div>
        <link rel="stylesheet" type="text/css" href="js/spiffyCal/spiffyCal_v2_1.css">
        <script language="JavaScript" src="js/spiffyCal/spiffyCal_v2_1.js"></script>
        <script language="javascript"><!--
            var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "formulario", "fecha_doc","btnDate1","",scBTNMODE_CUSTOMBLUE);
        </script>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <form enctype="multipart/form-data" method="post" name="formulario" id="formulario" action='upload2.php?<?= $variables ?>'>
                        <input type="hidden" name="usua" value="<?= $usua ?>">
                        <input type="hidden" name="contra" value="<?= $contra ?>">
                        <input type="hidden" name="anex_origen" value="<?= $tipo ?>">
                        <input type="hidden" name="tipo" value="<?= $tipo ?>">
                        <input type="hidden" name="tipoLista" value="<?= $tipoLista ?>">
                        <input type="hidden" name="krd" value="<?= $krd ?>">
                        <input type="hidden" name="id_Dir_otro" value="<?= $id_Dir_otro ?>">
                        <input type="hidden" name="i_copias" id="i_copias" value="<?= $i_copias ?>">
                        <input type="hidden" name="tipoDocumentoSeleccionado" value="<?php echo $tipoDocumentoSeleccionado ?>">
                        <div align="center">
                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
                                <tr>
                                    <td  height="25" align="center" class="titulos4" colspan="2">DESCRIPCI&Oacute;N DEL DOCUMENTO</td>
                                </tr>
                                <tr>
                                    <td class="titulos2" height="25" align="left" colspan="2" > ATRIBUTOS </td>
                                </tr>
                                <tr>
                                    <td  colspan="2">
                                        <table border=0 width=100% class="borde_tab" >
                                            <tr>
                                                <td height="23" align="left" colspan="3" class="listado2">Tipo de Anexo:
                                                    <? echo $sel_tAnex ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td height="23" colspan="3" class="listado2">
                                                    <input type="checkbox" class="select"  name="sololect" id="sololect" value='1' <?= $solec ?>>Solo lectura</td>
                                            </tr>
                                            <tr>
                                                <td height="23" colspan="3" class="listado2">
                                                    <table border=0 width=100%>
                                                        <tr>
                                                            <td width=50% class="listado2">
                                                                <input type="checkbox" class="select" name="radicado_salida" onclick="mostrarForm();" id="radicado_salida">Este documento ser&aacute; radicado
                                                            </td>
                                                            <td class="listado2" colspan="2">Tipos de Radicacion: </td>
                                                            <td  valign="top" class="listado2" width="100%"  colspan="2"><? echo $sel_tRad; ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <table  width="100%"  class="borde_tab" id="anexaExp" style="display:none">
                                                        <tr>
                                                            <td class="titulos2"  width="50%">Guardar en Expediente:</td>
                                                            <td  valign="top" class="listado2" width="100%"  colspan="4"><? echo $sel_exp ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="titulos2"  colspan="2" >Destinatario</td>
                                            </tr>
                                            <tr valign="top">
                                                <td  valign="top" class="listado2" >
                                                    <input type="radio"   name="radicado_rem"  value=1  id="rusuario" <?=
                                                    $datoss1;
                                                    echo $enviado
                                                    ?> <?php if ($radicado_rem == 1) echo " checked "; ?> >
                                                    <?= $tip3Nombre[1][$ent] ?>
                                                    <br>
                                                    <?= $otro_us11 . " - " . substr($nombret_us11, 0, 35) ?>
                                                    <br>
                                                    <?= $direccion_us11 ?>
                                                    <br>
<?= "$dpto_nombre_us11/$muni_nombre_us11" ?>
                                                </td>
                                                <td  valign="top" class="listado2">
                                                    <input type="radio" name="radicado_rem" id="rempre"  value=3 <?=
$datoss3;
echo $enviado
?> <?php
                                                    if ($radicado_rem == 3) {
                                                        echo " checked ";
                                                    }
?>>
                                                    <?= $tip3Nombre[3][$ent] ?>
                                                    <br>
                                                    <?= substr($nombret_us3, 0, 35) ?>
                                                    <br>
                                                    <?= $direccion_us3 ?>
                                                    <br>
<?= "$dpto_nombre_us3/$muni_nombre_us3" ?><br>
                                                </td>
                                            </tr>
                                            <tr valign="top">
                                                <td valign="top" class="listado2">
                                                    <input type="radio" name="radicado_rem" id="rpredi"  value=2  <?=
$datoss2;
echo $enviado
?> <?php
                                                    if ($radicado_rem == 2) {
                                                        echo " checked ";
                                                    }
?>>
                                                    <?= $tip3Nombre[2][$ent] ?><br>
                                                    <?= $otro_us2 . " - " . substr($nombret_us2, 0, 35) ?>
                                                    <br>
<?= $direccion_us2 ?>
                                                    <br>
                                                    <?= "$dpto_nombre_us2/$muni_nombre_us2" ?>
                                                </td>
                                                <td  valign="top" class="listado2">
                                                    <input type="radio" name="radicado_rem"  value=7 id="rotro" <?=
                                                    $datoss4;
                                                    echo $enviado
                                                    ?> <?php if ($radicado_rem == 7) echo " checked "; ?>  id="rotro">
                                                    Otro:<?= $otro_dest ?>
                                                </td>



                                            </tr>
                                            <tr valign="top" >
                                                <td  class='titulos2' valign="top" colspan="2">Descripcion o Asunto</td>
                                            </tr>
                                            <tr valign="top">
                                                <td  valign="top" colspan="2" height="66" class="listado2"  >
                                                    <br>
                                                    <textarea name="descr" cols="35" rows="4" class="tex_area" id="descr"><?= $descr ?></textarea>

                                                    <input name="usuar" type="hidden" id="usuar" value="<?php echo $usuar ?>"><br>
                                                    <input name="predi" type="hidden" id="predi" value="<?php echo $predi ?>">
                                                    <input name="empre" type="hidden" id="empre" value="<?php echo $empre ?>">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div align="center">
                            <table width="100%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab" >
                                <tr>
                                    <td class='celdaGris'  colspan="2">
                                        <font size="1">
                                        <table  width="100%" border="0" cellpadding="0" cellspacing="5" class="borde_tab"  id='tbl_otros'>
                                            <tr>
                                                <td width="100%" class='titulos2' colspan="4" > <font size="1" class="etextomenu"><center>
                                                Otro Destinatario
                                            </center></font>
                                    </td>
                                    <td width="25%" class='titulos2' colspan="4">
                                        <input type="button" name="Button" value="BUSCAR" class="botones" onClick="Start('<?= $ruta_raiz ?>/radicacion/buscar_usuario.php?busq_salida=true&nombreTp3=<?= $nombreTp3 ?>&krd=<?= $krd ?>',1024,500);">
                                    </td>
                                </tr>
                                <tr align="center" >
                                    <td width="203" CLASS=titulos2 >DOCUMENTO</td>
                                    <td CLASS=titulos2 colspan="2" >NOMBRE Y APELLIDOS<NOMBRE</td>
                                    <td CLASS=titulos2 >DIRIGIDO A:</td>
                                    <td CLASS=titulos2 width="103" colspan="2">DIRECCION</td>
                                    <td CLASS=titulos2 width="68">EMAIL</td>
                                    <td CLASS=titulos2 width="68"></td>
                                    </tr>
                                    <tr>
                                        <td align="center" class="listado2">
                                            <input type=hidden name=telefono_us1 value='' class=tex_area  size=10>
                                            <input type=hidden name=tipo_emp_us1 class=tex_area size=3 value='<?= $tipo_emp_us1 ?>' >
                                            <input type=hidden name=documento_us1 class=tex_area size=3 value='<?= $documento_us1 ?>' >
                                            <input type=hidden name="idcont1" id="idcont1" value='<?= $idcont1 ?>' class=e_cajas size=4 >
                                            <input type=hidden name="idpais1" id="idpais1" value='<?= $idpais1 ?>' class=e_cajas size=4 >
                                            <input type=hidden name="codep_us1" id="codep_us1" value='<?= $codep_us1 ?>' class=e_cajas size=4 >
                                            <input type=hidden name="muni_us1" id="muni_us1"  value='<?= $muni_us1 ?>' class=e_cajas size=4 >
                                            <input type=text name=cc_documento_us1 value='<?= $cc_documento_us1 ?>' class=e_cajas size=8 readonly >
                                        </td>
                                        <td width="329" align="center" class="listado2" colspan="2">
                                            <table>
                                                <tr>
                                                    <td class="titulos2">
                                                        Nombre
                                                    </td>
                                                    <td>
                                                        <input type=text name=nombre_us1 value=''  size=20 class=tex_area>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="titulos2">
                                                        Primer Apellido
                                                    </td>
                                                    <td>
                                                        <input type=text name=prim_apel_us1 value=''   size=20 class=tex_area>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="titulos2">
                                                        Segundo Apellido
                                                    </td>
                                                    <td>
                                                        <input type=text name=seg_apel_us1 value=''   size=20 class=tex_area>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td width="140" align="center" class="listado2">
                                            <input type=text name=otro_us7 value='' class=tex_area   size=20 maxlength="45">
                                        </td>
                                        <td align="center" class="listado2" colspan="2">
                                            <input type=text name=direccion_us1 value='' class=tex_area  size=6>
                                        </td>
                                        <td width="68" align="center" class="listado2">
                                            <input type=text name=mail_us1 value='' class=tex_area size=11>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="listado2" align="center">
                                    <center><input type="button" name="cc" value="Grabar Destinatario" class="botones_mediano"  onClick="continuar_grabar()" ></center>
                                    </td>
                                    </tr>
<?php
echo $otros_usuarios;
?>
                            </table>
                            </font>
                            </td>
                            </tr>
                            <tr>
                                <td class='celdaGris' align="center" colspan="2"><font size="1">&nbsp;</font></td>
                            </tr>
                            </table>
                        </div>
                        <table><tr><td></td></tr></table>
                        <table width="100%"  border="0" cellpadding="0" cellspacing="5" class="borde_tab">
                            <tr align="center">
                                <td width="18%"  class="titulos2">&nbsp;</td>
                                <td width="82%" height="25"  class="titulos2">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="">ADJUNTAR ARCHIVO
                                </td>
                            </tr>
                            <tr align="center"  >
                                <td>
                                    <font size="-3" class="etextomenu">
                                    <div id="LabelPlantillaPliego" ></div>
                                    </font>
                                </td>
                                <td>
                                    <p align="left">
                                        <input name="userfile1" type="file" class="tex_area" onChange="escogio_archivo();" id="userfile" value="valor">
                                        <label>
                                            <input name="button" type="submit" class="botones_largo" onClick="return actualizar();" value="ACTUALIZAR <?= $codigo ?>">
                                        </label>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <input type=hidden name=i_copias value='<?= $i_copias ?>'id="i_copias" >
                    </form>
                </td>
            </tr>
        </table>
    <center>
        <span class="etextomenu">
            <table width="95%" border="0" cellspacing="1" cellpadding="0" align="center" class="t_bordeGris">
<? echo $alert ?>
                <tr align="center">
                    <td class="celdaGris" height="25">
                        <span class="etextomenu">

                            <input type='button' class ='botones' value='cerrar' onclick='f_close()'>
                        </span>
                    </td>
                </tr>
            </table>
        </span>
    </center>
    <script>setTimeout("habilitar()", 0);</script>
</body>
</html>
