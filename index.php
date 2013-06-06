<?php
/**
* Pagina principal.
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
$cons = "SELECT id,nom FROM xarxes ORDER BY nom";
$res = mysql_query($cons) or die(mysql_error());
$i = 0;
while ( $row = mysql_fetch_array($res) ){
	$id_xarxa[$i] = $row[0];
	$nom_xarxa[$i] = $row[1];
	$i++;
}
//tanquem
mysql_free_result($res);
require('includes/bd/tanca.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="description" content="Xarxes agrocològiques, Redes agroecológicas" />
	<title><?php echo PAGE_TITLE; ?></title>

	<link rel="stylesheet" type="text/css" href="estils/estils_inici.css" />

	<link rel="stylesheet" type="text/css" href="estils/validate.css" />
	<script type="text/javascript" src="funcions/jquery.min.js"></script>
	<script type="text/javascript" src="funcions/validate/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.js'; ?>"></script>

	<script type="text/javascript">
		$().ready(function() {
			$("#formulari").validate( {
				rules: { 'xarxa': 'required' },
				messages: { 'xarxa': ELIGE_XARXA }
			});
		});
	</script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'funcions/validate/messages_'.$_SESSION['lang'].'.js'; ?>"></script>
</head>
<body>
	<div id="principal">
		<div id="cap" align="center"><img align="middle" src="estils/capsalera.png" alt=""/></div>
		<div id="separacio">&nbsp;</div>
		<div id="loginh">
			<form action='login.php' method='post' id="formulari">
				<table style="padding: 2px 0;" align="right" width="90%">
					<tr>
						<td width="30%" align="right">
							<label for="usuari"><?php echo EMAIL; ?>: <input style="width: 60%;" type="text" class="required" size="25" maxlength="50" name="usuari" /></label>
						</td>
						<td width="25%" align="right">
							<label for="pass"><?php echo PASSWORD; ?>: <input style="width: 45%;" type="password" class="required" size="15" maxlength="50" name="pass" /></label>
						</td>
						<td width="5%" align="center" style="color:red;">
							<label for="error"><?php if( $_GET['error'] == '1' ) echo ERROR_LOGIN; ?></label>
						</td>
						<td width="35%" align="center">
							<label for="xarxa">
								<select name="xarxa">
									<option value=""><?php echo ELIGE_XARXA; ?></option>
<?php
									for( $j = 0; $j < count($nom_xarxa); $j++ ){
										echo '<option value="'.$id_xarxa[$j].'">'.$nom_xarxa[$j].'</option>';
									}
?>
								</select>
							</label>
							<input style="margin-left:15px" class="entrar" name="accept" type="submit" value="<?php echo ENTRAR; ?>" />
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div id="separacio">&nbsp;</div>
		<div id="contingut" class="index">
			<div id="floatRight">
				<?php echo muestraSelectorIdioma(); ?>
				<div id="unitats">
					<h5><?php echo ACCESO_U_CONSUMO; ?></h5>
					<ul>
<?php
						for( $j = 0; $j < count($nom_xarxa); $j++ ){
							echo '<li><a href="xarxa.php?xarxa='.$id_xarxa[$j].'">'.$nom_xarxa[$j].'</a></li>';
							if($j<count($nom_xarxa)-1) echo '<br />';
						}
?>
					</ul>
				</div>
			</div>
			<?php echo TEXTO_PORTADA; ?>
			<div style="clear:both;"></div>
		</div>
		<div id="separacio">&nbsp;</div>
		<div id="peu"><?php echo AUTORES; ?></div>
	</div>
</body>
</html>