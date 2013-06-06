<?php
/**
* Menú per a unitats de consum
* Depen de cada xarxa serà un dia i una hora limit per a fer comandes.
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
$_SESSION["xarxa"] = null;
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');
$cons = "SELECT xarxa_bd,nom,imatge_cap FROM xarxes WHERE id = '".$_GET['xarxa']."'";
$res = mysql_query($cons) or die(mysql_error());
$row = mysql_fetch_array($res);
$_SESSION["xarxa"] = $row[0];
$_SESSION["nom_xarxa"] = $row[1];
$_SESSION['imatge_cap'] = 'estils/'.$row[2];

//tanquem
mysql_free_result($res);
require('includes/bd/tanca.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="description" content="Xarxes agrocològiques" />
	<title><?php echo $_SESSION["nom_xarxa"];?></title>

	<link rel="stylesheet" type="text/css" href="estils/estils_inici.css" />

	<link rel="stylesheet" type="text/css" href="estils/validate.css" />
	<script type="text/javascript" src="funcions/jquery.min.js"></script>
	<script type="text/javascript" src="funcions/validate/jquery.validate.js"></script>

	<script type="text/javascript">
		$().ready(function() {
			$("#formulari").validate();
		});
	</script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'funcions/validate/messages_'.$_SESSION['lang'].'.js'; ?>"></script>
</head>
<body>
	<div id="principal">
		<div id="cap" align="center"><img align="middle" src="<?php echo $_SESSION['imatge_cap'];?>" alt=""/></div>
		<div id="separacio">&nbsp;</div>
		<div id="floatRight">
			<?php echo muestraSelectorIdioma(); ?>
			<div id="minilogin"><h5><?php echo ACCESO_U_CONSUMO; ?></h5>
				<form action='login.php' method='post' id="formulari">
					<table style="padding: 2px 0;" align="right">
						<tr>
							<td align="right">
								<label for="usuari"><?php echo EMAIL; ?>: <input type="text" class="required" size="16" maxlength="50" name="usuari" /></label>
							</td>
						</tr>
						<tr>
							<td align="right">
								<label for="pass"><?php echo PASSWORD; ?>: <input type="password" class="required" size="16" maxlength="50" name="pass" /></label>
							</td>
						</tr>
						<tr>
							<td align="center" style="color:red;">
								<label for="error" ><?php echo $fet;?></label>
							</td>
						</tr>
						<tr>
							<td align="center">
								<input class="entrar zn" name="accept" type="submit" value="<?php echo ENTRAR; ?>" />
								<input name="xarxa" type="hidden" value="<?php echo $_GET['xarxa']; ?>" />
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div id="contingut">
<?php
			require('includes/bd/obri.php');
			$consulta = "SELECT descripcio FROM config";
			$resultat = mysql_query($consulta) or die(mysql_error());
			$xarxa = mysql_fetch_object($resultat);
			echo $xarxa->descripcio;
?>
			<span id="tornar" class="zn"><a href="index.php"><?php echo VOLVER; ?></a></span>
		</div>
		<div id="separacio">&nbsp;</div>
		<div id="peu"><?php echo AUTORES; ?></div>
	</div>
</body>
</html>