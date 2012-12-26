<?php
error_reporting(0);

function checkldapuser($username,$password)
{   require('config.php');
    $connect = ldap_connect($ldapServer);
	if($connect != false)
    {
        ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
        // enlace a la conexión
        $bind = ldap_bind($connect,$usrLDAP,$pwdLDAP);
        //$bind = ldap_bind($connect);
        if($bind == false)
        {   $mensajeError =  "Falla la conexi&oacute;n con el servidor LDAP";
            return $mensajeError;
        }
       // busca el usuario
       $username=strtolower($username);
       $tmp="($campoBusqLDAP=$username)";
       $res_id = ldap_search( $connect, $cadenaBusqLDAP, $tmp);
       if ($res_id == false)
       {
            $mensajeError = "No encontrado el usuario en el A.D.";
            return $mensajeError;
       }

       $cant = ldap_count_entries($connect, $res_id);
       if ($cant == 0)
       {
           $mensajeError =  "El usuario $username NO se encuentra en el A.D.";
           return $mensajeError;
       }

       if ($cant > 1)
       {
           $mensajeError =  "El usuario $username se encuentra $cant veces en el A.D.";
           return $mensajeError;
       }

       $entry_id = ldap_first_entry($connect, $res_id);
       if ($entry_id == false)
       {
           $mensajeError =  "No se obtuvieron resultados";
           return $mensajeError;
       }

       if (( $user_dn = ldap_get_dn($connect, $entry_id)) == false) {
            $mensajeError = "No se puede obtener el dn del usuario";
         return $mensajeError;
       }
        error_reporting( 0 );
       /* Autentica el usuario */
       if (($link_id = ldap_bind($connect, $user_dn, $password)) == false) {
        error_reporting( 0 );
        $mensajeError = "USUARIO O CONTRASE&Ntilde;A INCORRECTOS";
         return $mensajeError;
       }

       return '';
       @ldap_close($connect);
  }
  else {
   $mensajeError = "no hay conexi&oacute;n a '$ldap_server'";
   return $mensajeError;
  }

  @ldap_close($connect);
  return(false);

}

?>