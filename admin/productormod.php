<?php
/**
* Apartat productors, editar.
* Quan has d'editar un productor o vas a fer-ne un de nou.
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

// Mode: add->afegir; del->eliminar; mod->modificar
$mode = $_GET['mode'];
$id = $_GET['id'];

if($mode == 'mod') {
	$conmod = "SELECT * FROM productors WHERE id = '$id'";
	$resmod = mysql_query($conmod) or die(mysql_error());
	$linea = mysql_fetch_object($resmod);
	$apartat=PRODUCTORXS.' -> '.MODIFICAR;
}else{
	$apartat=PRODUCTORXS.' -> '.ANYADIR_NUEVO_PRODUCTORX;
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
			$( 'textarea' ).ckeditor(function() { /* callback code */ }, {
				toolbar : 'Basic',
				forcePasteAsPlainText : true,
				scayt_autoStartup : false,
				uiColor : '#9AB8F3'
			});
			$("#formulari").validate();
		});
		function ltrim(cadena){
			while(1){
				if(cadena.substring(0, 1)!=" "){
					break;
				}
			cadena=cadena.substring(1, cadena.length);
			}
			return cadena;
		}
		function rtrim(cadena){
			while(1){
				if(cadena.substring(cadena.length - 1, cadena.length)!=" "){
					break;
				}
			cadena=cadena.substring(0, cadena.length - 1);
			}
			return cadena;
		}
		function trim(cadena){
			var tmpstr=ltrim(cadena);
			return rtrim(tmpstr);
		}
		function ComprobarNumerico(elemento){
			if(isNaN(document.getElementById(elemento).value)){
				document.getElementById(elemento).value="";
			}else{
				document.getElementById(elemento).value=trim(document.getElementById(elemento).value);
			}
		}
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
		<h4><?php echo $apartat; ?></h4>
		<form method="post" action="productors.php?mode=<?php echo $mode;?>&id=<?php echo $id;?>" enctype="multipart/form-data" id="formulari">
			<fieldset>
				<legend><?php echo PRODUCTORX; ?></legend>
				<table border="0" cellpadding="3" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="15%" align="left"><?php echo NOMBRE; ?></td>
						<td width="73%" align="left"><input style="width: 98%;" name="nom" type="text" class="required" value="<?php echo htmlentities($linea->nom_productor, ENT_QUOTES, "UTF-8");?>" size="60"/></td>
						<td width="7%" align="right"><?php echo SOCIXS; ?></td>
						<td width="5%" align="left"><input name="soci" type="checkbox" <?php if($linea->socis == '1')echo 'checked="checked"';?> size="60"/></td>
					</tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td><?php echo LOCALIDAD; ?></td>
						<td><input style="width: 98%;" name="localitat" type="text" class="required" value="<?php echo htmlentities($linea->localitat, ENT_QUOTES, "UTF-8");?>" size="60"/></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo POSICION; ?></td>
						<td><input style="width: 98%;" id="posicio" name="posicio" type="text" value="<?php echo $linea->posicio;?>" size="60" onkeyup="ComprobarNumerico('posicio');" /></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td><?php echo DESCRIPCION; ?></td>
						<td><textarea cols="40%" rows="10" name="descripcio"><?php echo $linea->descripcio;?></textarea></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td><?php echo CONTACTO; ?></td>
						<td><input style="width: 98%;" name="contacte" type="text" value="<?php echo htmlentities($linea->contacte, ENT_QUOTES, "UTF-8");?>" size="60"/></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td><?php echo CORREO; ?></td>
						<td><input style="width: 98%;" name="correu" type="text" class="email" value="<?php echo $linea->correu;?>" size="60"/></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td colspan="4" align="center">
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
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php'); ?>