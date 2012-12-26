<?php
/**
  * CONSULTA VERIFICACION PREVIA A LA RADICACION
  */
switch($db->driver)
{  
	case 'mssql':
		$isql = 'select
					convert(varchar(14), b.RADI_NUME_RADI) "IMG_Numero Radicado",
					b.RADI_PATH "HID_RADI_PATH",
					convert(varchar(14),b.RADI_NUME_DERI) "Radicado Padre",
					b.RADI_FECH_RADI "HOR_RAD_FECH_RADI",'.
					$sqlFecha.' "Fecha Radicado",
					b.RA_ASUN "Descripcion",
					c.SGD_TPR_DESCRIP "Tipo Documento",
					convert(varchar(14),b.RADI_NUME_RADI) "CHK_CHKANULAR"
				from
					radicado b, SGD_TPR_TPDCUMENTO c
				where 
					b.radi_nume_radi is not null
					and '.$db->conn->substr.'(convert(char(15),b.radi_nume_radi), 5, 3)=\''.str_pad($dep_sel,3,"0", STR_PAD_LEFT).'\'
					and '.$db->conn->substr.'(convert(char(15),b.radi_nume_radi), 14, 1) in (1,3,5)
					and b.tdoc_codi=c.sgd_tpr_codigo
					and sgd_eanu_codigo is null '.
					$whereTpAnulacion.' '.$whereFiltro.'
				order by '.$order .' ' .$orderTipo;
		break;
	case 'oracle':
	case 'oci8':
	case 'oci805':	
	default:
		$isql = 'select distinct
                        b.RADI_NUME_RADI as "IMG_Numero Radicado",
                        b.RADI_PATH as "HID_RADI_PATH",
                        b.RADI_NUME_DERI as "Radicado Padre",'.
                        $db->conn->SQLDate('Y-m-d H:i:s', 'b.RADI_FECH_RADI').' as "HOR_RAD_FECH_RADI",'.
                        $db->conn->SQLDate('Y-m-d H:i:s', 'b.RADI_FECH_RADI').' as "Fecha Radicado",
                        b.RA_ASUN as "Descripcion",
                        c.SGD_TPR_DESCRIP as "Tipo Documento",
                        b.RADI_NUME_RADI as "CHK_CHKANULAR"
                 from  radicado b
                 inner join SGD_TPR_TPDCUMENTO c on b.tdoc_codi=c.sgd_tpr_codigo
                 left  join anexos a on  b.RADI_NUME_RADI=a.RADI_NUME_SALIDA
                 left  join sgd_renv_regenvio env on b.RADI_NUME_RADI= env.radi_nume_sal
                 where
                       b.radi_nume_radi is not null
                       and (a.anex_estado is null or a.anex_estado < 4)
                       and '.$db->conn->substr.'(b.radi_nume_radi, 5, 3)=\''.str_pad($dep_sel,3,"0", STR_PAD_LEFT).'\'
                       and '.$db->conn->substr.'(b.radi_nume_radi, 14, 1) not in (2)
                       and (sgd_eanu_codigo is null or sgd_eanu_codigo=9)
                                and (env.sgd_renv_planilla = \'00\' or env.sgd_renv_planilla is null)'.
                       $whereTpAnulacion.' '.$whereFiltro.'
                       order by '.$order .' ' .$orderTipo;
}
?>