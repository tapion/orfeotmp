<table border="0" cellpadding="0" cellspacing="0" width="160">
<!-- fwtable fwsrc="Sin tï¿½ulo" fwbase="menu.gif" fwstyle="Dreamweaver" fwdocid = "742308039" fwnested="0" -->
  <tr>
   <td><img src="imagenes/spacer.gif" width="10" height="1" border="0" alt=""></td>
   <td><img src="imagenes/spacer.gif" width="150" height="1" border="0" alt=""></td>
   <td><img src="imagenes/spacer.gif" width="1" height="1" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="2"><img name="menu_r3_c1" src="imagenes/menu_r3_c1.gif" width="148" height="31" border="0" alt=""></td>
   <td><img src="imagenes/spacer.gif" width="1" height="25" border="0" alt=""></td>
  </tr>
  <tr>
   <td>&nbsp;</td>
   <td valign="top"><table width="150" border="0" cellpadding="0" cellspacing="0" bgcolor="c0ccca">
     <tr>
       <td valign="top"><table width="150"  border="0" cellpadding="0" cellspacing="3" bgcolor="#C0CCCA">
		<?
		$i++;
		foreach ($_SESSION["tpNumRad"]as $key => $valueTp) 
		{
  			$valueImg = "";
			$valueDesc = $tpDescRad[$key];
			$valueImg = $tpImgRad[$key];
    		$encabezado = "$phpsession&krd=$krd&fechah=$fechah&primera=1&ent=$valueTp&depende=$dependencia";
    		if($tpPerRad[$valueTp]==1 or $tpPerRad[$valueTp]==3)
			{ 
			 
	?>
		
       	<tr valign="middle">
           <td width="25"><img src="imagenes/menu.gif" width="15" height="18" name="plus<?=$i?>"></td>
           <td width="125"><a onclick="cambioMenu(<?=$i?>);" href="radicacion/chequear.php?<?=$encabezado?>" alt='<?=$valueDesc?>' title='<?=$valueDesc?>'  target='mainFrame' class="menu_princ"><?=$valueDesc?></a></td>
         </tr>
        
		<?
		}
		$i++;
		}
		// Realiza Link a pagina de combianciï¿½ de correspondencia masiva
		if ($_SESSION["usua_masiva"]==1) {

		?>
		
		<tr valign="middle">
           <td width="25"><img src="imagenes/menu.gif" width="15" height="18" name="plus<?=$i?>"></td>
           <td width="125"><a  onclick="cambioMenu(<?=$i?>);" href='radsalida/masiva/menu_masiva.php?<?=$phpsession ?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>'  target='mainFrame' class="menu_princ">Masiva</a></td>
         </tr>
         <?
		}
         $i++;
		if ($_SESSION["dependencia"]==900 || $_SESSION["dependencia"]==529 || $_SESSION["dependencia"]==810)
	 		{
		?>
         <tr valign="middle">
           <td width="25"><img src="imagenes/menu.gif" width="15" height="18" name="plus<?=$i?>"></td>
           <td width="125"><a  onclick="cambioMenu(<?=$i?>);" href='fax/index.php?<?=$phpsession ?>&krd=<?=$krd?>&<? echo "fechah=$fechah&usr=".md5($dep)."&primera=1&ent=2&depende=$dependencia"; ?>' alt='Rad Fax'  target='mainFrame' class="menu_princ">Rad Fax</a></td>
         </tr>
          <?
			}
			 $i++;	
		if ($_SESSION["perm_radi"]>=1)
	 		{
		?>
         <tr valign="middle">
           <td width="25"><img src="imagenes/menu.gif" width="15" height="18" name="plus<?=$i?>"></td>
           <td width="125"><a  onclick="cambioMenu(<?=$i?>);" href='uploadFiles/uploadFileRadicado.php?<?=$phpsession ?>&krd=<?=$krd?>&<? echo "fechah=$fechah&usr=".md5($dep)."&primera=1&ent=2&depende=$dependencia"; ?>' alt='Asociar imagen de radicado'  target='mainFrame' class="menu_princ">Asociar Im&aacute;genes</a></td>
         </tr>
          <?
			}
			 $i++;	
			?>
       </table></td>
     </tr>
   </table></td>
   </tr>
   </table>
 
  
