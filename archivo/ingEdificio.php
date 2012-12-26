<? 
session_start();
/**
  * Modificacion Variables Globales Fabian losada 2009-07
  * Licencia GNU/GPL 
  */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;
$krd = $_SESSION["krd"];
//var_dump($_POST);
$dependencia = $_SESSION["dependencia"];
$usua_doc = $_SESSION["usua_doc"];
$codusuario = $_SESSION["codusuario"];
$tpNumRad = $_SESSION["tpNumRad"];
$tpPerRad = $_SESSION["tpPerRad"];
$tpDescRad = $_SESSION["tpDescRad"];
$tpDepeRad = $_SESSION["tpDepeRad"];
$ruta_raiz = "..";
include_once("$ruta_raiz/include/db/ConnectionHandler.php");
$db = new ConnectionHandler("$ruta_raiz");
include_once "$ruta_raiz/include/tx/Historico.php";
include_once "$ruta_raiz/include/tx/Expediente.php";
include("Combos.Class.php");
$obj= new Combos();
$continente=$obj->getContinentes();
$db->debug = true;
$encabezadol = "$PHP_SELF?".session_name()."=".session_id()."&dependencia=$dependencia&krd=$krd";
?>
<script>
function RegresarV(){
	window.location.assign("adminEdificio.php?<?=$encabezado1?>&fechah=$fechah&$orno&adodb_next_page");
}
</script>
<?
/**
  * Grabar los datos del edificio.
  */
if( isset( $_POST['btnGrabar'] ) && $_POST['btnGrabar'] != "" )
{
    //$db->conn->BeginTrans();
    /**
      * Crea el registro con los datos del edificio.
      */
    $q_insertE  = "INSERT INTO SGD_EIT_ITEMS( SGD_EIT_CODIGO, SGD_EIT_COD_PADRE,";
    $q_insertE .= " SGD_EIT_NOMBRE, SGD_EIT_SIGLA, ID_CONT, ID_PAIS,CODI_DPTO, CODI_MUNI)";
    $sec=$db->conn->nextId( 'SEC_EDIFICIO' );
    $q_insertE .= " VALUES( '$sec', 0,";
    $q_insertE .= " UPPER( '".$_POST['edificioNom']."' ),";
    $q_insertE .= " UPPER( '".$_POST['edificioSig']."' ),";
    $q_insertE .= " ".$_POST['selCont'].", ".$_POST['selPais'].",".$_POST['selDepto'].", ".$_POST['selMnpio']." )";
    echo $muni_us;
    $listo = $db->conn->Execute( $q_insertE );
    /**
      * Datos de las unidades de almacenamiento del edificio.
      */
    foreach( $_POST as $clavePOST => $valorPOST )
    {
        if( strncmp( $clavePOST, 'nombre_', 7 ) == 0 )
        {
            $nombreUA = $valorPOST;
        }
        if( strncmp( $clavePOST, 'sigla_', 6 ) == 0 )
        {
            $siglaUA = $valorPOST;
        }
        
        if( $nombreUA != "" && $siglaUA != "" )
        {
            /*
             * Crea el registro correspondiente a la unidad de almacenamiento.
             */
            $q_insertUA  = "INSERT INTO SGD_EIT_ITEMS( SGD_EIT_CODIGO,SGD_EIT_COD_PADRE, SGD_EIT_NOMBRE,";
            $q_insertUA .= " SGD_EIT_SIGLA )";
            $q_insertUA .= " VALUES( ".$db->conn->nextId( 'SEC_EDIFICIO' ).", $sec,";
            $q_insertUA .= " UPPER( '".$nombreUA."' ), UPPER( '".$siglaUA."' ) )";
            if( $listo )
            {
                $listo = $db->conn->Execute( $q_insertUA );
            }
            $nombreUA = "";
            $siglaUA = "";
        }
    }
    
    if( $listo->EOF )
    {
       // $db->conn->CommitTrans();
	?>
	<script>
	window.open('<?=$ruta_raiz?>/archivo/relacionTiposAlmac.php?dependencia=<?=$dependencia?>&krd=<?=$krd?>&tipo=<?=$tipo?>&idEdificio=<?=$idEdificio?>&codp=<?=$sec?>',"Relacion Tipos Almacenamiento","height=350,width=550,scrollbars=yes");
	</script>
	<?	
    }
    else
    {
        $db->conn->RollbackTrans(); 
    }

   // header( "Location: relacionTiposAlmac.php?".$encabezadol."&idEdificio=".$idEdificio);
}
?>
<html>
<head>
<title>INGRESO DE EDIFICIOS</title>
<link rel="stylesheet" href="../estilos/orfeo.css">
<script language="JavaScript" src="../js/ajax.js"></script>
<script language="JavaScript" type="text/javascript">

function validar()
{
    var band=false;
    var msg='';
    if(document.getElementById( 'selCont' ).value ==0){
        band=true;
        msg+='Seleccione Continente\n';
    }
    if(document.getElementById( 'selPais' ).value==0){
        band=true;
        msg+='Seleccione pa\xEDs\n';
    }
    if(document.getElementById( 'selDepto' ).value==0){
        band=true;
        msg+='Seleccione Departamento\n';
    }
    if(document.getElementById( 'selMnpio' ).value==0){
        band=true;
        msg+='Seleccione Municipio\n';
    }
    if(document.getElementById( 'edificioNom' ).value==''){
        band=true;
        msg+='Debe digitar nombre del edificio\n';
    }
    if(document.getElementById( 'edificioSig' ).value==''){
        band=true;
        msg+='Debe digitar sigla del edificio\n';
    }
    if( document.getElementById( 'numero' ).value == "" || isNaN( document.getElementById( 'numero' ).value ) )
    {
        band=true;
        msg+= 'Debe ingresar N\xFAmero de Tipos de Almacenamiento.';
    }
    if(band)
    {
      alert(msg);
      return !band
    }
    return !band
}

function mostrarCampos()
{
    if(validar())
    {
        var i;
        var j = parseInt( document.getElementById( 'numero' ).value );
        var obj = document.getElementById('Lista');
        var tbl= "\t<table width='100%' border='1'>" +
                         "\t\t<tr>\n" +
                            "\t\t\t<td  class='titulos5'> NOMBRE</td>\n" +
                            "\t\t\t<td  class='titulos5'>SIGLA</td>\n"+
                        "\t\t</tr>\n";
        for ( i = 0; i < j; i++ )
        {
            tbl+= "\t\t<tr>\n" +
                            "\t\t\t<td  class='titulos5'><input type='text' name='nombre_" + i + "'  size='40' maxlength='40'></td>\n" +
                            "\t\t\t<td  class='titulos5'><input type='text' name='sigla_" + i + "' size='4' maxlength='4'></td>\n" +
                            "\t\t</tr>\n";
        }
        tbl+= "\t\t<tr>\n" +
                        "\t\t\t<td  colspan='2' class='titulos5' align='center'><input type='submit' class='botones' value='Grabar' name='btnGrabar' onClick='return validar();'></td>\n" +
                        "\t\t</tr>\n" +
                        "\t</table>\n";
        obj.innerHTML=tbl;
        //document.close()
    }
}

var divAutilizar;

function pedirCombosDivipola(fuenteDatos, divID,tipo,continente,pais,departamento)
{
	if(xmlHttp)
	{
		// obtain a reference to the <div> element on the page
		divAutilizar = document.getElementById(divID);
		try
		{
			xmlHttp.open("GET", fuenteDatos+"?tipo="+tipo+"&continente="+continente.value+"&pais="+pais.value+"&departamento="+departamento.value);
			xmlHttp.onreadystatechange = handleRequestStateChange;
			xmlHttp.send(null);
		}
		//display the error in case of failure
		catch (e)
		{
			alert("Can't connect to server:\n" + e.toString());
		}
	}
}

//handles the response received from the server
function handleServerResponse()
{
	// read the message from the server
	var xmlResponse = xmlHttp.responseText;
	// display the HTML output
	divAutilizar.innerHTML = xmlResponse;
}

</script>
</head>
<body bgcolor="#FFFFFF">
<form name="inEdificio" action="<?=$encabezadol?>" method="post" >
<table border="0" width="90%" cellpadding="0"  class="borde_tab">
<tr>
  <td height="35" colspan="5" class="titulos2">
  <center>INGRESO DE EDIFICIOS</center>
  </td>
</tr>
<tr><td valign="middle" class="titulos2">1.Ubicaci&oacute;n</td>
    <td height="30" class="titulos5" align="center"><b>Continente</b><br><?php echo $continente;?> </td>
    <td height="30" class="titulos5" align="center">
    	<b>Pais</b><br>
    	<div id="DivPais"><select class="select" id="selPais"><option value="0">&lt;&lt Seleccione &gt;&gt</select></div>
     </td>
    <td height="30" class="titulos5" align="center"><b>Departamento</b><br><div id="DivDepto"><select class="select" id="selDepto"><option value="0">&lt;&lt Seleccione &gt;&gt</select></div></td>
    <td height="30" class="titulos5" align="center"><b>Municipio</b><br><div id="DivMnpio"><select class="select" id="selMnpio"><option value="0">&lt;&lt Seleccione &gt;&gt</select></div></td>
</tr>
<tr>
  <td rowspan="2" valign="middle" class="titulos2">2.Datos</td>
  <td height="23" class="titulos5" colspan="2">
    <div align="left">
      Nombre
      <input type="text" name="edificioNom" id="edificioNom" value="<?php print $_POST['nombre']; ?>" size="40" maxlength="40" align="right">
    </div>
  </td>
  <td class="titulos5" colspan="2">
    <div align="left">
      Sigla
      <input type="text" name="edificioSig" id="edificioSig" value="<?php print $_POST['sigla']; ?>" size="4" maxlength="4" align="right">
    </div>
  </td>
</tr>
<tr>
  <td height="26" class="titulos5" colspan="2">
    <div align="left">
      Ingrese N&uacute;mero de Tipos de Almacenamiento
      <input type="text" name="numero" id="numero" value="<?php print $_POST['numero']; ?>" size="2" maxlength="2" align="right">
    </div>
  </td>
  <td class="titulos5" colspan="2">
    <input type="button" name="btnMostrarCampos" class="botones_2" value="&gt;&gt;" onClick="mostrarCampos();">
  </td>
</tr>
<tr>
    <td class="titulos5" colspan="5"><div id="Lista"></div></td>
</tr>
<tr>
    <td><input name='SALIR' type="button" class="botones" id="envia22" onClick="opener.regresar();window.close();" value="SALIR" align="middle" ></td>
</tr>
</table>
</form>
</body>
</html>