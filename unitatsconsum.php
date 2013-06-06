<?php
/**
* On entres després del login. Es visualitza, les notícies o avisos que ha posat el coordinador.
*
* PHP version 5
*
* @author     Maistanet.com <info@maistanet.com> i Atornallom - Cooperativa Integral Valenciana - http://atornallom.net
* @copyright  2012 Maistanet.com i Atornallom - Cooperativa Integral Valenciana - http://atornallom.net
* @package    xarxa
* @license    http://www.gnu.org/copyleft/gpl.html  Distributed under the General Public License (GPL) 
* @version    1.1
* @link       https://github.com/maistanet/xarxesconsum
*/
header("content-type:text/html; charset=utf-8");

//Configuraciones + abrir sesión
include('includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'funcions/funcions.php');
include(RUTA_WEB_INCLUDE.'includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="estils/template.css" />
	<link rel="stylesheet" type="text/css" href="estils/validate.css" />
</head>
<body>
	<div id="cap" align="center"><img align="middle" src="<?php echo htmlentities($_SESSION['imatge_cap'], ENT_QUOTES, 'UTF-8');?>" alt=""/></div>
	<div id="separacio">&nbsp;</div>
	<div id="menu" align="center">
		<?php include(RUTA_WEB_INCLUDE.'includes/menu_u.php');?>
	</div>
	<div id="contingut">
		<div id="floatRight">
			<?php echo muestraSelectorIdioma(); ?>
		</div>
	</div>
	<div id="contingut">
		<br />
<?php
		$consulta = "SELECT nom FROM unitat_c WHERE id = ".$_SESSION["s_idusuari"];
		$resultat = mysql_query($consulta) or die(mysql_error());
		$unitat_c = mysql_fetch_object($resultat);
?>
		<p><?php echo BIENVENIDX_INI.' '.$unitat_c->nom; if(!$pedidoBloqueado){ echo BIENVENIDX_FIN; } ?></p>
<?php
		$consulta = "SELECT avisos FROM config";
		$resultat = mysql_query($consulta) or die(mysql_error());
		$avis = mysql_fetch_row($resultat);
		if($avis[0]!=''){
?>
			<p><u><?php echo AVISOS; ?>:</u></p>
			<p><?php echo $avis[0];?></p>
<?php
		}
?>
	</div>
	<div id="peu"></div>
</body>
</html>
<?php
// alliberem el resultat de la consulta
mysql_free_result($resultat);
// tanquem la conexió a la BD
include(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>