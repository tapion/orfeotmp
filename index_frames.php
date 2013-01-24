<?php
error_reporting(0);
session_start();
$krd = strtoupper($krd);
$ruta_raiz = ".";
if (!isset($_SESSION['dependencia']))
    include "./rec_session.php";
?>
<html>
    <head>
        <title>.:: Sistema de Gesti&oacute;n Documental ::.</title>
        <link rel="shortcut icon" href="imagenes/arpa.gif">
        <script type="text/javascript">
            function cerrar_ventana()
            {
                window.close();
            }
            var GLOBAL = {
                radicados: new Array()
            };
            function eliminaCarrito(valor){
                valor = parseInt(valor);
                var ind = GLOBAL.radicados.indexOf(valor);
                if(ind >= 0){
                    GLOBAL.radicados.splice(GLOBAL.radicados.indexOf(valor),1);
                }
                cuentaRadicados();
            } //fin function eliminaCarrito(valor){
            function agregaCarrito(valor){
                var valId = parseInt(valor);
                if(valId == "" || isNaN(valId)){
                    return true;
                }
                var ind = GLOBAL.radicados.indexOf(valId);
                if(ind >= 0){
                    return true;
                }
                GLOBAL.radicados.push(valId);
                cuentaRadicados();
            } //fin function agregaCarrito(valor){
            function cuentaRadicados(){
                window.frames.topFrame.document.getElementById("txtRadicados").value = GLOBAL.radicados.length;
            }
            function fnEjecutaCarro(){
                if(GLOBAL.radicados.length > 0){
                    var tmp = GLOBAL.radicados;                    
                    window.frames.topFrame.document.getElementById("radicadosCarrito").value = tmp.toString();
                    window.frames.topFrame.document.getElementById("frmCarrito").submit();
                }else{
                    alert("No hay radicados para procesar");
                }
            }
        </script>
    </head>
    <frameset rows="75,864*" frameborder="NO" border="0" framespacing="0" cols="*">
        <frame name="topFrame" scrolling="NO" noresize src='f_top.php'>
            <frameset cols="175,947*" border="0" framespacing="0" rows="*">
                <frame name='leftFrame' scrolling='AUTO' src='correspondencia.php' marginwidth='0' marginheight='0' scrolling='AUTO'>
                    <frame name='mainFrame' src='cuerpo.php' scrolling='AUTO'>
                        </frameset>
                        </frameset>
                        </html>
