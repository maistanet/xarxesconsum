<?php
/**
* Apartat productes, editar.
* Quan has d'editar un producte o vas a fer-ne un de nou.
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

$hayCategs=false;
$sqlHayCategs='SELECT id FROM categoria';
$resultHayCategs=mysql_query($sqlHayCategs);
if(mysql_fetch_object($resultHayCategs))
	$hayCategs=true;
$hayProductorxs=false;
$sqlHayProductorxs='SELECT id FROM productors';
$resultHayProductorxs=mysql_query($sqlHayProductorxs);
if(mysql_fetch_object($resultHayProductorxs))
	$hayProductorxs=true;
if(!$hayCategs || !$hayProductorxs)
	header('location: productes.php');

// Mode: add->afegir; del->eliminar; mod->modificar
$mode = $_GET['mode'];
$id = $_GET['id'];

if($mode == 'mod') {
	$conmod = "SELECT * FROM producte WHERE id = '$id'";
	$resmod = mysql_query($conmod) or die(mysql_error());
	$linea = mysql_fetch_object($resmod);

	$apartat=PRODUCTO.' -> '.MODIFICAR;
}else{
	$apartat=PRODUCTO.' -> '.ANYADIR_NUEVO_PRODUCTO;
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
		function redondear(elemento){
			document.getElementById(elemento).value=Math.round(document.getElementById(elemento).value*100)/100;
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
		<h4><?php echo $apartat;?></h4>
		<form method="post" action="productes.php?mode=<?php echo $mode;?>&id=<?php echo $id;?>" id="formulari">
			<fieldset>
				<legend><?php echo PRODUCTO; ?></legend>
				<table border="0" cellpadding="0" cellspacing="0" align="center" width="90%">
					<tr>
						<td width="55%" align="left" style="padding-right: 10px;"><?php echo NOMBRE; ?> <input style="width: 80%;" name="nom" type="text" class="required" value="<?php echo htmlentities($linea->nom_producte, ENT_QUOTES, "UTF-8");?>" size="60"/></td>
						<td width="20%" align="left"><?php echo ACTIVO; ?> <input type="checkbox" name="actiu" <?php echo ($linea->actiu == 1 ? 'checked="checked"' : ''); ?> /></td>
						<td width="25%" align="left">
							<select name="categoria">
<?php
								$concat = "SELECT * FROM categoria";
								$rescat = mysql_query($concat) or die(mysql_error());
								while ($categoria = mysql_fetch_object($rescat)) {
?>
									<option value="<?php echo $categoria->id;?>" <?php if($linea->id_categoria == $categoria->id) echo 'selected="selected"';?>><?php echo $categoria->nom;?></option>
<?php
								}
?>
							</select>
						</td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr>
						<td><?php echo PRODUCTORX; ?></td>
						<td><?php echo FORMATO; ?></td>
						<td><?php echo PRECIO; ?> <input style="width: 30%;" id="preu" name="preu" type="text" value="<?php echo $linea->preu;?>" size="5" onblur="redondear('preu');" onkeyup="ComprobarNumerico('preu');" />&nbsp;&euro;</td>
					</tr>
					<tr>
						<td>
							<select name="id_productor">
<?php
								$conprod = "SELECT id,nom_productor FROM productors";
								$resprod = mysql_query($conprod) or die(mysql_error());
								while ($productor = mysql_fetch_object($resprod)) {
?>
									<option value="<?php echo $productor->id;?>" <?php if($productor->id == $linea->id_productor) echo 'selected="selected"';?>><?php echo $productor->nom_productor;?></option>
<?php
								}
?>
							</select>
						</td>
						<td colspan="2" align="left">
							<select name="format">
								<option value="unitat" <?php if($linea->format == "unitat") echo 'selected="selected"';?>><?php echo UNIDAD; ?></option>
								<option value="Kg" <?php if($linea->format == "Kg") echo 'selected="selected"';?>><?php echo KILOS; ?></option>
								<option value="gr" <?php if($linea->format == "gr") echo 'selected="selected"';?>><?php echo GRAMOS; ?></option>
							</select>
						</td>
					</tr>
					<tr><td colspan="3" align="center"><h6><?php echo NOTA_PRECIOS; ?></h6></td></tr>
					<tr>
						<td colspan="3"><?php echo DESCRIPCION; ?> <textarea cols="40%" rows="5" name="descripcio"><?php echo $linea->descripcio;?></textarea></td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr>
						<td colspan="3"><?php echo COMENTARIO; ?> <input style="width: 35%;" name="comentari" type="text" value="<?php echo $linea->comentari;?>" size="30"/></td>
					</tr>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr>
						<td colspan="3" align="center">
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