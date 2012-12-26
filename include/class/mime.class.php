<?PHP
/**
 * Clase donde gestionamos informacion referente a los tipos de archivo.
 *
 * @copyright Sistema de Gestion Documental ORFEO
 * @version 1.0
 * @author Desarrollado por Grupo Iyunxi Ltda.
 */
class Mime
{
private $cnn;	//Conexion a la BD.
private $flag;	//Bandera para usos varios.
private $vector;//Vector con los datos.

/**
 * Constructor de la classe.
 *
 * @param ConnectionHandler $db
 */
function __construct($db)
{
	$this->cnn = $db;
	$this->cnn->SetFetchMode(ADODB_FETCH_ASSOC);
}

/**
 * Agrega un nuevo tipo de archivo.
 *
 * @param array $datos  Vector asociativo con todos los campos y sus valores.
 * @return boolean $flag False on Error /
 */
function SetInsDatos($datos)
{
	return $this->flag;
}

/**
 * Modifica datos a un tipo de archivo.
 *
 * @param array $datos  Vector asociativo con todos los campos y sus valores.
 * @return boolean $flag False on Error
 *
 * No se debiría modificar el código ANEX_TIPO_CODI, validar esto en el cliente.
 */
function SetModDatos($datos)
{
	return $this->flag;
}

/**
 * Elimina un tipo de archivo.
 *
 * @param  int $dato  Id del tipo de archivo a eliminar.
 * @return boolean $flag False on Error /
 */
function SetDelDatos($dato)
{
	$sql = "SELECT COUNT(*) FROM ANEXOS WHERE ANEX_TIPO =".$dato;
	if ($this->cnn->GetOne($sql) > 0)
	{
		$this->flag = 0;
	}
	else
	{
		$this->cnn->BeginTrans();
		$ok = $this->cnn->Execute('DELETE FROM ANEXOS_TIPO WHERE ANEX_TIPO_CODI='.$dato);
		if($ok)
		{
			$this->cnn->CommitTrans();
			$this->flag = true;
		}
		else
		{
			$this->cnn->RollbackTrans() ;
			$this->flag = false;
		}
	}
	return $this->flag;
}

/**
 * Retorna un combo con las opciones de la tabla Anexos_tipo.
 *
 * @param  boolean Habilita/Deshabilita la 1a opcion SELECCIONE.
 * @param  boolean Habilita/Deshabilita la validacion Onchange hacia una funcion llamada Actual().
 * @return string Cadena con el combo - False on Error.
 */
function Get_ComboOpc($dato1, $dato2)
{
	$sql = "SELECT ANEX_TIPO_DESC AS DESCRIP, ANEX_TIPO_CODI AS ID FROM ANEXOS_TIPO ORDER BY 1";
	$rs = $this->cnn->Execute($sql);
	if (!$rs)
		$this->flag = false;
	else
	{
		($dato1) ? $tmp1="0:&lt;&lt;SELECCIONE&gt;&gt;" : $tmp1 = false;
		($dato2) ? $tmp2="Onchange='Actual()'" : $tmp2 = '';
		$this->flag = $rs->GetMenu('slc_cmb2',false,$tmp1,false,false,"id='slc_cmb2' class='select' $tmp2");
		unset($rs); unset($tmp1); unset($tmp2);
	}
	return $this->flag;
}

/**
 * Retorna un vector.
 *
 * @return Array Vector numÃ©rico con los datos - False on error.
 */
function Get_ArrayDatos()
{
	$sql = "SELECT ANEX_TIPO_DESC AS DESCRIP, ANEX_TIPO_CODI AS ID, ANEX_TIPO_EXT AS EXTE FROM ANEXOS_TIPO ORDER BY 1";
	$rs = $this->cnn->Execute($sql);
	if (!$rs)
		$this->vector = false;
	else
	{	$it = 1;
		while (!$rs->EOF)
		{	$vdptosv[$it]['ID'] = $rs->fields['ID'];
			$vdptosv[$it]['NOMBRE'] = $rs->fields['DESCRIP'];
			$vdptosv[$it]['USUARIO'] = $rs->fields['EXTE'];
			$it += 1;
			$rs->MoveNext();
		}
		$rs->Close();
		$this->vector = $vdptosv;
		unset($rs); unset($sql);
	}
	return $this->vector;
}
}
?>