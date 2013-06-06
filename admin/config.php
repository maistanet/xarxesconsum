<?php
/**
* Apartat de configuració de la xarxa.
* ..
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

//validar els formularis
if ( isset($_POST["accept1"]) ){
	//correu
	$sqlCorreo="SELECT correu FROM correu";
	if(mysql_num_rows(mysql_query($sqlCorreo))){
		$con_upd = "UPDATE correu SET correu='".decode($_POST['correu'])."', text='".$_POST['textcorreu']."'";
		$res_upd = mysql_query($con_upd) or die(mysql_error());
	}else{
		$sqlInsertCorreo="INSERT INTO correu (correu, text) VALUES ('".decode($_POST['correu'])."', '".$_POST['textcorreu']."')";
		$resInsertCorreo=mysql_query($sqlInsertCorreo) or die(mysql_error());
	}
	$apartat=CONFIG.' -> '.MODIFICAR_CORREO;
}
if ( isset($_POST["accept2"]) ){
	//configuracio xarxa
	$sqlConfig="SELECT nom_xarxa FROM config";
	if(mysql_num_rows(mysql_query($sqlConfig))){
		$con_upd = "UPDATE config SET nom_xarxa='".$_POST['nomxarxa']."', dia_venda='".$_POST['diavenda']."', dia_limit='".$_POST['dialimit']."', hora_limit='".$_POST['horalimit']."', descripcio='".$_POST['textweb']."', limit_comanda_saldo='".$_POST['limit_comanda_saldo']."'";
		$res_upd = mysql_query($con_upd) or die(mysql_error());
	}else{
		$sqlInsertCorreo="INSERT INTO config (nom_xarxa, dia_venda, dia_limit, hora_limit, descripcio, limit_comanda_saldo) VALUES ('".$_POST['nomxarxa']."', '".$_POST['diavenda']."', '".$_POST['dialimit']."', '".$_POST['horalimit']."', '".$_POST['textweb']."', '".$_POST['limit_comanda_saldo']."')";
		$resInsertCorreo=mysql_query($sqlInsertCorreo) or die(mysql_error());
	}
	$apartat=CONFIG.' -> '.MODIFICAR_CONFIG;
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
	<script type="text/javascript" src="../funcions/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="../funcions/ckeditor/adapters/jquery.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.js'; ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$( 'textarea' ).ckeditor({
				toolbar: 'Full',
				forcePasteAsPlainText : true,
				scayt_autoStartup : false
			});
			$("#formulari").validate();
			$("#formulari2").validate();
		});
	</script>
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
		<h4><?php echo $apartat;?></h4>
		<form method="post" action="config.php" enctype="multipart/form-data" id="formulari">
			<fieldset>
				<legend><?php echo CORREO_RED; ?></legend>
<?php
				$consulta = "SELECT * FROM correu";
				$resultat = mysql_query($consulta) or die(mysql_error());
				$correu = mysql_fetch_object($resultat);
?>
				<table border="0" cellpadding="3" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="25%"><?php echo CORREO; ?></td>
						<td width="75%"><input style="width: 40%;" name="correu" type="text" class="required email" value="<?php echo $correu->correu;?>" size="40" maxlength="100" /></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr><td colspan="2"><?php echo TEXTO_CABECERA_CORREO_RED; ?></td></tr>
					<tr>
						<td colspan="2"><textarea id="textcorreu" name="textcorreu" class="required" cols="40%" rows="10"><?php echo $correu->text;?></textarea></td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="center" colspan="2">
							<input type="submit" name="accept1" value="<?php echo ACEPTAR; ?>" />
							<input type="submit" name="cancel" value="<?php echo CANCELAR; ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
		<br />
		<form method="post" action="config.php" enctype="multipart/form-data" id="formulari2">
			<fieldset>
				<legend><?php echo CONFIG_RED; ?></legend>
<?php
				$consulta = "SELECT * FROM config";
				$resultat = mysql_query($consulta) or die(mysql_error());
				$xarxa = mysql_fetch_object($resultat);
?>
				<table border="0" cellpadding="3" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="30%"><?php echo NOMBRE; ?></td>
						<td width="15%"><?php echo DIA_VENTA; ?></td>
						<td width="20%"><?php echo DIA_LIMITE_PEDIDO; ?></td>
						<td width="15%"><?php echo HORA_LIMITE_PEDIDO; ?></td>
						<td width="20%"><?php echo MONEDERO; ?></td>
					</tr>
					<tr>
						<td><input style="width: 95%;" name="nomxarxa" type="text" class="required" value="<?php echo $xarxa->nom_xarxa;?>" size="30"/></td>
						<td>
							<select name="diavenda" class="required">
								<option value="Monday" <?php if($xarxa->dia_venda == 'Monday') echo 'selected="selected"';?> ><?php echo LUNES; ?></option>
								<option value="Tuesday" <?php if($xarxa->dia_venda == 'Tuesday') echo 'selected="selected"';?> ><?php echo MARTES; ?></option>
								<option value="Wednesday" <?php if($xarxa->dia_venda == 'Wednesday') echo 'selected="selected"';?> ><?php echo MIERCOLES; ?></option>
								<option value="Thursday" <?php if($xarxa->dia_venda == 'Thursday') echo 'selected="selected"';?> ><?php echo JUEVES; ?></option>
								<option value="Friday" <?php if($xarxa->dia_venda == 'Friday') echo 'selected="selected"';?> ><?php echo VIERNES; ?></option>
								<option value="Saturday" <?php if($xarxa->dia_venda == 'Saturday') echo 'selected="selected"';?> ><?php echo SABADO; ?></option>
								<option value="Sunday" <?php if($xarxa->dia_venda == 'Sunday') echo 'selected="selected"';?> ><?php echo DOMINGO; ?></option>
							</select>
						</td>
						<td>
							<select name="dialimit" class="required">
								<option value="Monday" <?php if($xarxa->dia_limit == 'Monday') echo 'selected="selected"';?> ><?php echo LUNES; ?></option>
								<option value="Tuesday" <?php if($xarxa->dia_limit == 'Tuesday') echo 'selected="selected"';?> ><?php echo MARTES; ?></option>
								<option value="Wednesday" <?php if($xarxa->dia_limit == 'Wednesday') echo 'selected="selected"';?> ><?php echo MIERCOLES; ?></option>
								<option value="Thursday" <?php if($xarxa->dia_limit == 'Thursday') echo 'selected="selected"';?> ><?php echo JUEVES; ?></option>
								<option value="Friday" <?php if($xarxa->dia_limit == 'Friday') echo 'selected="selected"';?> ><?php echo VIERNES; ?></option>
								<option value="Saturday" <?php if($xarxa->dia_limit == 'Saturday') echo 'selected="selected"';?> ><?php echo SABADO; ?></option>
								<option value="Sunday" <?php if($xarxa->dia_limit == 'Sunday') echo 'selected="selected"';?> ><?php echo DOMINGO; ?></option>
							</select>
						</td>
						<td><input style="width: 60%;" name="horalimit" type="text" class="required valid" value="<?php echo $xarxa->hora_limit;?>" size="8"/></td>
						<td>
							<select name="limit_comanda_saldo" class="required">
								<option value="off" <?php if($xarxa->limit_comanda_saldo=='off') echo 'selected="selected"';?> ><?php echo MONEDERO_DESACTIVADO; ?></option>
								<option value="si" <?php if($xarxa->limit_comanda_saldo=='si') echo 'selected="selected"';?> ><?php echo MONEDERO_ACTIVADO_CON_LIMITE; ?></option>
								<option value="no" <?php if($xarxa->limit_comanda_saldo=='no') echo 'selected="selected"';?> ><?php echo MONEDERO_ACTIVADO_SIN_LIMITE; ?></option>
							</select>
						</td>
					</tr>
					<tr><td colspan="5" align="center"><h6><?php echo NOTA_OPCIONES_MONEDERO; ?></h6></td></tr>
					<tr><td colspan="5"><?php echo TEXTO_PORTADA_RED; ?></td></tr>
					<tr>
						<td colspan="5"><textarea id="textweb" name="textweb" class="required" cols="40%" rows="10"><?php echo $xarxa->descripcio; ?></textarea></td>
					</tr>
					<tr><td colspan="5">&nbsp;</td></tr>
					<tr>
						<td align="center" colspan="5">
							<input type="submit" name="accept2" value="<?php echo ACEPTAR; ?>" />
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