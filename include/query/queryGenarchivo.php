<?
switch ($db->driver) { 
	case 'oracle':
	case 'mssql':
	case 'oci8':
	// Modificado SGD 06-Septiembre-2007
	case 'postgres':
		$query1="select a.sgd_dir_codigo,a.sgd_dir_direccion,a.sgd_dir_telefono,a.sgd_dir_mail,b.sgd_ciu_nombre AS NOMBRE,b.SGD_CIU_APELL1 AS APELL1,b.SGD_CIU_APELL2 AS APELL2,b.SGD_CIU_CEDULA,a.SGD_DIR_TIPO
	         from sgd_dir_drecciones a
	         LEFT OUTER JOIN  sgd_ciu_ciudadano b ON   a.sgd_ciu_codigo = b.sgd_ciu_codigo
	         where
			   a.sgd_dir_tipo like '7%' and a.sgd_dir_tipo !=7  and a.sgd_anex_codigo='$anexo'
			 ";
	break;
	}

?>