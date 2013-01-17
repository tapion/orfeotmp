<table><tr><td> </td></tr></table>
<form name="formListado" action='../envios/paramListaImpresos.php?<?= $encabezado ?>' method="post" id="formBotonTempListadoFecha"> 
    <table BORDER=0  cellpad=2 cellspacing='2' WIDTH=100%  align='center' class="borde_tab" cellpadding="2">
        <tr> 
            <td width='50%' align='left' height="30" class="titulos2" ><img src="<?= $ruta_raiz ?>/imagenes/estadoDocInfo.gif" height="30">
            </td>
            <td width='50%' align="center" class="titulos2" > 
                <a href='<?php echo $pagina_actual."?".$encabezado ?> '></a>
                <?php if(!isset($ocultarBoton)){ ?>
                <input type="submit" value="<?php echo $accion_sal2; ?>" name=Enviar id=Enviar valign='middle' class='botones_largo'>			
                <?php } ?>
            </td>

        </tr>
    </table>
</form>
