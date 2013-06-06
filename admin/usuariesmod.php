<?php
/**
* Apartat unitats de consum, editar.
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
include('../includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'funcions/funcions.php');
include(RUTA_WEB_INCLUDE.'admin/includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');

// segons l'apartat
$apartat=MODIFICAR.' -> '.UNIDADES_DE_CONSUMO;
$fet = "";

//per a mod
$mode = $_GET['mode'];
$id = $_GET['id'];

if($mode == 'mod'){
	$consulta = "SELECT * FROM unitat_c WHERE id = ".$id;
	$resultat = mysql_query($consulta) or die(mysql_error());
	$unitat_c = mysql_fetch_object($resultat);
}else{
	header('Location: '.$_SERVER['HTTP_REFERER']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="../estils/template.css" />
	<link rel="stylesheet" type="text/css" href="../estils/validate.css" />
	<script type="text/javascript" src="../funcions/jquery.min.js"></script>
	<script type="text/javascript" src="../funcions/validate/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'funcions/validate/messages_'.$_SESSION['lang'].'.js'; ?>"></script>
</head>
<body>
	<div id="cap" align="center"><img align="middle" src="<?php echo '../'.htmlentities($_SESSION['imatge_cap'], ENT_QUOTES, 'UTF-8');?>" alt=""/></div>
	<div id="separacio">&nbsp;</div>
	<div id="menu" align="center">
		<?php include(RUTA_WEB_INCLUDE.'admin/includes/menu.php');?>
	</div>
	<div id="contingut">
		<div id="floatRight">
			<?php echo muestraSelectorIdioma(); ?>
		</div>
	</div>
	<div id="contingut">
		<br />
		<h4><?php echo $apartat; ?> </h4>
		<p><?php echo $fet; ?></p>
		<form method="post" action="usuaries.php?id=<?php echo $id;?>&mode=<?php echo $mode;?>" enctype="multipart/form-data" id="formulari">
			<fieldset>
				<legend><?php echo U_CONSUM; ?></legend>
				<table width="80%" border="0" cellpadding="3" cellspacing="1" align="center">
					<tr>
						<td width="40%" align="left"><label for="user"><?php echo NOMBRE_USUARIO; ?></label></td>
						<td width="40%" align="left"><label for="pass"><?php echo PASSWORD; ?></label></td>
						<td width="20%" align="left"><label for="pass2"><?php echo PASSWORD_CONFIRM; ?></label></td>
					</tr>
					<tr>
						<td><input style="width: 95%;" name="user" id="user" class="required" type="text" value="<?php echo $unitat_c->user;?>" size="15" maxlength="50" /></td>
						<td><input style="width: 50%;" name="pass" id="pass" class="required password" type="password" value="<?php echo $unitat_c->pass;?>" size="15" maxlength="50" /></td>
						<td><input style="width: 90%;" name="pass2" id="pass2" class="required" equalTo="#pass" type="password" value="<?php echo $unitat_c->pass;?>" size="15" maxlength="50" /></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo NOMBRE; ?></td>
						<td><?php echo CORREO; ?></td>
						<td><?php echo NUM_CAJA; ?></td>
					</tr>
					<tr>
						<td><input style="width: 95%;" name="nom" class="required" type="text" value="<?php echo $unitat_c->nom;?>" size="25" /></td>
						<td><input style="width: 95%;" name="correu" class="required email" type="text" value="<?php echo $unitat_c->correu;?>" size="30" /></td>
						<td><input style="width: 60%;" name="caixa" class="required" type="text" readonly="readonly" value="<?php echo $unitat_c->n_caixa;?>" size="2" /></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td align="center" colspan="3">
							<input type="submit" name="accept" value="<?php echo ACEPTAR; ?>" />
							<input type="submit" name="cancel" value="<?php echo CANCELAR; ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
	</div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');?>