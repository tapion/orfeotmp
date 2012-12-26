<?php
switch($db->driver)
{	case 'mssql':
	{	
		
		$radi_nume_radi = "convert(varchar(14),a.RADI_NUME_RADI)";
		$tmp_cad1 = "convert(varchar,".$db->conn->concat("'0'","'-'",$radi_nume_radi).")";
		$tmp_cad2 = "convert(varchar,".$db->conn->concat('c.info_codi',"'-'",$radi_nume_radi).")";
		$redondeo = $db->conn->round($sqlOffset."-".$systemDate);
		$concatenar = "CAST(DEPE_CODI AS VARCHAR(10))";
		$isql = 'select '.$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			c.info_desc "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			'.$redondeo.' as "Dias Restantes",
			'.chr(39).chr(39).'  AS "Informador",
			'.$tmp_cad1.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO"
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is null
		UNION
		select '.$radi_nume_radi.' "IMG_Numero Radicado",
			a.RADI_PATH  "HID_RADI_PATH",
			'.$sqlFecha.'  "DAT_Fecha Radicado",
			'.$radi_nume_radi.' "HID_RADI_NUME_RADI",
			c.info_desc "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			'.$redondeo.' as "Dias Restantes",
			d2.usua_nomb  AS "Informador",
			'.$tmp_cad2.' "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO"
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d, usuario d2
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is not null and d2.usua_doc = c.info_codi
		order by '.$order.' '.$orderTipo;		
	}break;
	case 'oracle':
	case 'oci8':
	{ 	$radi_nume_radi = "cast(a.RADI_NUME_RADI as varchar(14))";
		$tmp_cad1 = "cast( ".$db->conn->concat("'0'","'-'",$radi_nume_radi)." as varchar(20) )";
		$tmp_cad2 = "cast( ".$db->conn->concat("c.info_codi","'-'",$radi_nume_radi)." as varchar(50) )";
		$redondeo = round($sqlOffset."-".$systemDate);
		//$tmp_cad2 = "to_char(".$db->conn->concat('c.info_codi',"'-'",$radi_nume_radi).")";
		$redondeo="round(((a.radi_fech_radi+(b.sgd_tpr_termino * 7/5))-sysdate))+(select count(*) from sgd_noh_nohabiles where NOH_FECHA between a.radi_fech_radi and ".$db->conn->sysTimeStamp.")";
		$concatenar = "CAST(DEPE_CODI AS VARCHAR(10))";
				
		$isql = '
		select '.$radi_nume_radi.' 	AS "IMG_Numero Radicado",
			a.RADI_PATH 		AS "HID_RADI_PATH",
			'.$sqlFecha.'		AS "DAT_Fecha Radicado",
			'.$radi_nume_radi.' 	AS "HID_RADI_NUME_RADI",
			c.info_desc 		AS "Asunto",
			b.sgd_tpr_descrip 	AS "Tipo Documento",
			d2.usua_nomb  		AS "Informador",
			'.$tmp_cad2.' 		AS "CHK_checkValue",
			c.INFO_LEIDO 		AS "HID_RADI_LEIDO"
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d, usuario d2
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and d2.usua_doc (+) = c.info_codi 
		order by '.$order.' '.$orderTipo;		
	}break;
	case 'postgres':
	{
		$radi_nume_radi = "a.RADI_NUME_RADI";
		$tmp_cad1 = "cast( ".$db->conn->concat("'0'","'-'",$radi_nume_radi)." as varchar(20) )";
		$tmp_cad2 = "cast( ".$db->conn->concat("c.info_codi","'-'",$radi_nume_radi)." as varchar(50) )";
		$concatenar = "CAST(DEPE_CODI AS VARCHAR(10))";
		//$redondeo="date_part('days', radi_fech_radi-".$db->conn->sysTimeStamp.")+floor(sgd_tpr_termino * 7/5)+(select count(*) from sgd_noh_nohabiles where NOH_FECHA between radi_fech_radi and ".$db->conn->sysTimeStamp.")";
                $diasHabiles=" diashabiles((sumadiashabiles(cast(radi_fech_radi as date),sgd_tpr_termino)),cast(".$db->conn->sysTimeStamp." as date))";
	}break;
}

$isql = 'select '.$radi_nume_radi.' AS "IDT_Numero Radicado",
			a.RADI_PATH AS "HID_RADI_PATH",
			'.$sqlFecha.' AS "DAT_Fecha Radicado",
			'.$radi_nume_radi.' AS "HID_RADI_NUME_RADI",
			c.info_desc AS "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			'.$diasHabiles.' as "Dias Restantes",
			'.chr(39).chr(39).' AS "Informador",
			'.$tmp_cad1.' AS "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO"
		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is null
		UNION
	select '.$radi_nume_radi.' AS "IDT_Numero Radicado",
			a.RADI_PATH AS "HID_RADI_PATH",
			'.$sqlFecha.' AS "DAT_Fecha Radicado",
			'.$radi_nume_radi.' AS "HID_RADI_NUME_RADI",
			c.info_desc AS "Asunto",
			b.sgd_tpr_descrip as "Tipo Documento",
			'.$diasHabiles.' as "Dias Restantes",
			d2.usua_nomb  AS "Informador",
			'.$tmp_cad2.' AS "CHK_checkValue",
			c.INFO_LEIDO as "HID_RADI_LEIDO"
 		from radicado a,
 			sgd_tpr_tpdcumento b,
 			informados c,
 			usuario d, usuario d2
		where a.radi_nume_radi=c.radi_nume_radi and a.tdoc_codi=b.sgd_tpr_codigo
			and a.radi_usua_actu=d.usua_codi and a.radi_depe_actu=d.depe_codi
			and c.depe_codi='.$dependencia.' and c.usua_codi='.$codusuario.' '.$where_filtro .'
			and c.info_codi is not null and d2.usua_doc = c.info_codi
		order by '.$order.' '.$orderTipo;		
?>
