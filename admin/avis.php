<?php
/**
* Apartat avis.
* Editor de text per posar avisos per a les unitats de consum.
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

//segons l'apartat
$apartat=AVISOS_U_CONSUMO;
$fet='';
//modificar l'avis
$avisos = mysql_real_escape_string( $_POST['avisos'] );
if($_POST['accept']==ACEPTAR){
	$consulta = "UPDATE config SET avisos='".$avisos."'";
	$resultat = mysql_query($consulta) or die(mysql_error());
	$apartat=AVISOS.' -> '.MODIFICAR;
	$fet=CORRECTO_MODIF_AVISOS;
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
			$('input:checkbox').click(function(){
				var chks=$(this).attr('checked');
				if(chks) var clicat=1;
				else var clicat=0;
				$.post('includes/ajax/update_bloqueo_panel_pedido.php',{clicat:clicat});
				document.getElementById('Separacion').style.display='none';
				if(chks) document.getElementById('mensajeText').value=PANEL_PEDIDO_BLOQUEADO;
				else document.getElementById('mensajeText').value=PANEL_PEDIDO_DESBLOQUEADO;
				document.getElementById('Mensaje').style.display='block';
			});
		});
		function ocultarMensaje(){
			document.getElementById('Mensaje').style.display='none';
			document.getElementById('Separacion').style.display='block';
		}
	</script>
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
		<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td width="30%" align="left"><h4><?php echo $apartat; ?></h4></td>
				<td width="40%" align="center">
					<div id="Mensaje" style="display: none;">
						<p><?php echo correcto('<input type="text" id="mensajeText" name="mensajeText" style="text-align: center; border-width: 0px; font-size: medium; font-weight: bold; color: #000000; background-color: #CCFFCD;" readonly="readonly" value="" />'); ?></p>
					</div>
					<div id="Separacion" style="display: block;">
						<p><?php echo separacion(); ?></p>
					</div>
				</td>
				<td width="30%">&nbsp;</td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td width="3%" align="left"><input type="checkbox" name="panelPedidoBloqueado" <?php if(!panelPedidoActivo()) echo 'checked="checked"';?> onblur="ocultarMensaje();" /></td>
				<td width="97%" align="left"><?php echo BLOQUEAR_PANEL_PEDIDO; ?></td>
			</tr>
		</table>
		<p><?php echo $fet; ?></p>
		<form method="post" action="avis.php" enctype="multipart/form-data">
<?php
			$consulta = "SELECT avisos FROM config";
			$resultat = mysql_query($consulta) or die(mysql_error());
			$linea = mysql_fetch_object($resultat);
?>
			<textarea cols="40%" rows="10" name="avisos"><?php echo $linea->avisos; ?></textarea>
			<div align="center" style="margin-top: 20px;">
				<input type="submit" name="accept" value="<?php echo ACEPTAR; ?>" />
			</div>
		</form>
	</div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');?>