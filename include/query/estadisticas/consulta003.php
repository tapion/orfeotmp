<?php
/** CONSUTLA 001 
  *Estadisticas por medio de envio -Salida *******
  *se tienen en cuenta los registros enviados por la dep xx contando la masiva ----
	*ej. COnsulta Base SELECT a.sgd_fenv_codigo,b.sgd_fenv_descrip,a.tot_reg 
  * FROM (SELECT SUM(SGD_RENV_CANTIDAD)AS tot_reg,sgd_fenv_codigo FROM fldoc.SGD_RENV_REGENVIO 
 	*  WHERE TO_CHAR(SGD_RENV_FECH,'yyyy/mm/%') LIKE '2005/05/%' 
	*   AND depe_codi LIKE 529
	*   AND RADI_NUME_SAL LIKE '2005%'
	*  GROUP BY sgd_fenv_codigo) a, fldoc.SGD_FENV_FRMENVIO b
  *  WHERE a.sgd_fenv_codigo=b.sgd_fenv_codigo;
	*
	* @autor JAIRO H LOSADA - SSPD
	* @version ORFEO 3.1
	* 
	*/
$coltp3Esp = '"'.$tip3Nombre[3][2].'"';	
if(!$orno) $orno=2;
 /**
   * $db-driver Variable que trae el driver seleccionado en la conexion
   * @var string
   * @access public
   */
 /**
   * $fecha_ini Variable que trae la fecha de Inicio Seleccionada  viene en formato Y-m-d
   * @var string
   * @access public
   */
/**
   * $fecha_fin Variable que trae la fecha de Fin Seleccionada
   * @var string
   * @access public
   */
/**
   * $mrecCodi Variable que trae el medio de recepcion por el cual va a sacar el detalle de la Consulta.
   * @var string
   * @access public
   * 
   */
$_POST['resol']? $resolucion=" and r.sgd_tres_codigo = ".$_POST['resol']." ":0;
$_GET['resol']? $resolucion=" and r.sgd_tres_codigo = ".$_GET['resol']." ":0;
$seguridad=",B.CODI_NIVEL USUA_NIVEL,R.SGD_SPUB_CODIGO";
switch($db->driver)
{
	case 'oracle':
	case 'oci8':
	case 'oci805':
	case 'ocipo':
		{
			if ($whereDependencia && $dependencia_busq != 99999)
			{
				$wdepend = " AND b.depe_codi = $dependencia_busq ";
			}
			$queryE = 
			"SELECT 
            b.USUARIO
            ,b.tot_reg TOTAL_ENVIADOS
			,b.USUA_DOC HID_COD_USUARIO
			, HID_DEPE_USUA
			,b.sgd_fenv_codigo CODIGO_ENVIO
			,c.sgd_fenv_descrip MEDIO_ENVIO
			,b.sgd_fenv_codigo HID_CODIGO_ENVIO
		   FROM 
			(SELECT COUNT(c.SGD_RENV_CANTIDAD) tot_reg,c.sgd_fenv_codigo , b.USUA_NOMB USUARIO, MIN(b.depe_codi) HID_DEPE_USUA, MIN(b.usua_doc) USUA_DOC
				FROM SGD_RENV_REGENVIO c, USUARIO b, radicado r
				WHERE 
					TO_CHAR(c.SGD_RENV_FECH,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin'
					AND r.radi_nume_radi = c.radi_nume_sal
					$wdepend
					AND substr(c.usua_doc,1,15) = b.usua_doc
					AND (c.sgd_renv_planilla != '00' or c.sgd_renv_planilla is null)
					and (c.sgd_renv_observa not like 'Masiva%' or  c.sgd_renv_observa is null)
					$whereTipoRadicado $resolucion
				GROUP BY b.USUA_NOMB, c.sgd_fenv_codigo) b, SGD_FENV_FRMENVIO c
    		WHERE b.sgd_fenv_codigo=c.sgd_fenv_codigo
			ORDER BY $orno $ascdesc";
			/** CONSULTA PARA VER DETALLES 
	 		*/ 
			$condicionDep = " AND b.depe_codi = $depeUs ";
			$condicionE = " AND c.sgd_fenv_codigo = $fenvCodi AND b.USUA_doc = $docUs ";

			$queryEDetalle = "SELECT  c.RADI_NUME_SAL RADICADO
				,d.sgd_fenv_descrip ENVIO_POR
				,b.USUA_NOMB USUARIO_QUE_ENVIO
				,c.sgd_renv_fech FECHA_ENVIO
				,c.sgd_renv_planilla PLANILLA
				,c.sgd_fenv_codigo HID_CODIGO_ENVIO				
				FROM SGD_RENV_REGENVIO c, SGD_FENV_FRMENVIO d, USUARIO b, radicado r
				WHERE 
				    c.sgd_fenv_codigo=d.sgd_fenv_codigo
					AND TO_CHAR(c.SGD_RENV_FECH,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin'
					AND r.radi_nume_radi = c.radi_nume_sal
					and substr(c.usua_doc,1,15) =  b.USUA_doc
					$wdepend
					AND (c.sgd_renv_planilla != '00' or c.sgd_renv_planilla is null)
					and (c.sgd_renv_observa not like 'Masiva%' or  c.sgd_renv_observa is null)
					
					$whereTipoRadicado $resolucion";

			$orderE = "	ORDER BY $orno $ascdesc ";
 			/** CONSULTA PARA VER TODOS LOS DETALLES 
	 		*/ 
			$queryETodosDetalle = $queryEDetalle . $orderE;
			$queryEDetalle .= $condicionE . $condicionDep . $orderE;
		}break;
	default:
		{	// Este default trabaja con Mssql 2K, 2K5.
			if ($whereDependencia && $dependencia_busq != 99999)	$wdepend = " AND b.depe_codi = $dependencia_busq ";
			$queryE = " SELECT  b.USUARIO
                                , b.tot_reg AS TOTAL_ENVIADOS
                                ,b.USUA_DOC AS HID_COD_USUARIO
                                , HID_DEPE_USUA
                                , b.sgd_fenv_codigo AS CODIGO_ENVIO
                                , c.sgd_fenv_descrip AS MEDIO_ENVIO
                                , b.sgd_fenv_codigo AS HID_CODIGO_ENVIO
			    		FROM  (SELECT COUNT(c.SGD_RENV_CANTIDAD) AS tot_reg, c.sgd_fenv_codigo, b.USUA_NOMB AS USUARIO,
									MIN(b.depe_codi) AS HID_DEPE_USUA, MIN(b.usua_doc) AS USUA_DOC
								FROM  SGD_RENV_REGENVIO c, USUARIO b, radicado r
								WHERE ".$db->conn->SQLDate('Y/m/d', 'c.SGD_RENV_FECH')." BETWEEN '$fecha_ini'  AND '$fecha_fin' AND
									r.radi_nume_radi = c.radi_nume_sal $wdepend AND c.usua_doc = b.usua_doc AND
									(c.sgd_renv_planilla != '00' or c.sgd_renv_planilla is null) and
									(c.sgd_renv_observa not like 'Masiva%' or  c.sgd_renv_observa is null)
									$whereTipoRadicado
								GROUP BY b.USUA_NOMB, c.sgd_fenv_codigo
							  ) b, SGD_FENV_FRMENVIO c
						WHERE b.sgd_fenv_codigo=c.sgd_fenv_codigo
						ORDER BY $orno $ascdesc";
		
			/** CONSULTA PARA VER DETALLES   */ 
			$condicionDep = ($dependencia_busq == 99999) ? '' : " and b.depe_codi = ".$dependencia_busq;
			$condicionE = " AND c.sgd_fenv_codigo = $fenvCodi AND b.USUA_doc = $docUs ";
			$queryEDetalle = "SELECT $radi_nume_radi AS RADICADO
                                    ,d.sgd_fenv_descrip AS ENVIO_POR
                                    ,b.USUA_NOMB AS USUARIO_QUE_ENVIO
                                    ,c.sgd_renv_fech AS FECHA_ENVIO
                                    ,c.sgd_renv_planilla AS PLANILLA
                                    ,c.sgd_fenv_codigo AS HID_CODIGO_ENVIO
                               FROM SGD_RENV_REGENVIO c, SGD_FENV_FRMENVIO d, USUARIO b, radicado r
                               WHERE c.sgd_fenv_codigo=d.sgd_fenv_codigo AND
                               ".$db->conn->SQLDate('Y/m/d', 'c.SGD_RENV_FECH')." BETWEEN '$fecha_ini'  AND '$fecha_fin' AND
                               r.radi_nume_radi = c.radi_nume_sal and c.usua_doc =  b.USUA_doc $wdepend AND
                               (c.sgd_renv_planilla != '00' or c.sgd_renv_planilla is null) and
                               (c.sgd_renv_observa not like 'Masiva%' or  c.sgd_renv_observa is null)
                               $whereTipoRadicado ";
	
			$orderE = "     ORDER BY $orno $ascdesc ";
		
	 		/** CONSULTA PARA VER TODOS LOS DETALLES */
			$queryETodosDetalle = $queryEDetalle . $orderE;
			$queryEDetalle .= $condicionE . $condicionDep . $orderE;	
		}break;
}

if(isset($_GET['genDetalle'])&& $_GET['denDetalle']=1)
	$titulos=array("#","1#RADICADO","2#ENVIO POR","3#USUARIO QUE ENVIO","4#FECHA ENVIO","5#PLANILLA");
else             
	$titulos=array("#","1#USUARIO","2#TOTAL ENVIADOS","3#MEDIO DE ENVIO");
                 
function pintarEstadistica($fila,$indice,$numColumna){ 
                global $ruta_raiz,$_POST,$_GET,$krd; 
                $salida=""; 
                switch ($numColumna){ 
                        case  0: 
                                $salida=$indice; 
                                break;
                        case 1:
                                $salida=$fila['USUARIO'];
                                 break;
                        case 2:
                                 $datosEnvioDetalle="tipoEstadistica=".$_POST['tipoEstadistica']."&amp;genDetalle=1&amp;usua_doc=".urlencode($fila['HID_USUA_DOC'])."&amp;dependencia_busq=".$_POST['dependencia_busq']."&amp;docUs=".$fila['HID_COD_USUARIO']."&amp;depeUs=".$fila['HID_DEPE_USUA']."&amp;fenvCodi=".$fila['HID_CODIGO_ENVIO']."&amp;fecha_ini=".$_POST['fecha_ini']."&amp;fecha_fin=".$_POST['fecha_fin']."&amp;tipoRadicado=".$_POST['tipoRadicado']."&amp;tipoDocumento=".$_POST['tipoDocumento']."&amp;resol=".$_POST['resol'];
                                 $datosEnvioDetalle=(isset($_POST['usActivos']))?$datosEnvioDetalle."&amp;usActivos=".$_POST['usActivos']:$datosEnvioDetalle;
                                 $salida="<a href=\"genEstadistica.php?{$datosEnvioDetalle}&amp;krd={$krd}\"  target=\"detallesSec\" >".$fila['TOTAL_ENVIADOS']."</a>";
                                break;
                        case 3:
                                $salida=$fila['MEDIO_ENVIO'];
                                break;
                        
                        default: $salida=false;
                }
                return $salida;
        }
function pintarEstadisticaDetalle($fila,$indice,$numColumna){
                        global $ruta_raiz,$encabezado,$krd;
                        //$verImg=($fila['SGD_SPUB_CODIGO']==1)?($fila['USUARIO']!=$_SESSION['usua_nomb']?false:true):($fila['USUA_NIVEL']>$_SESSION['nivelus']?false:true);
                $numRadicado=$fila['RADICADO'];
                        switch ($numColumna){
                                        case 0:
                                                $salida=$indice;
                                                break;
                                        case 1:
                                                if($fila['HID_RADI_PATH'] && $verImg)
                                                        $salida="<center><a href=\"{$ruta_raiz}bodega".$fila['RADI_PATH']."\">".$fila['RADICADO']."</a></center>";
                                                else
                                                        $salida="<center class=\"leidos\">{$numRadicado}</center>";
                                                break;
                                        case 2:
						$salida=$fila['ENVIO_POR'];
                                                break;
                                        case 3:  
                                              $salida="<center class=\"leidos\">".$fila['USUARIO_QUE_ENVIO']."</center>";                                                 break;
                                        case 4:
                                                $salida="<center class=\"leidos\">".$fila['FECHA_ENVIO']."</center>";
                                                break;
                                        case 5:
                                                $salida="<center class=\"leidos\">".$fila['PLANILLA']."</center>";
                                                break;
                        }
                        return $salida;               
		
}
?>
