<?php
/**
* Apartat dades personals.
* 
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

// segons l'apartat
$apartat=MODIFICAR.' -> '.D_PERSONALES;
$fet='';

//validem formulari i modifiquem bd
if($_POST['accept'] == ACEPTAR ){
	if(isset($_POST['correu']) && $_POST['correu']!='' && isset($_POST['pass']) && $_POST['pass']!='' && isset($_POST['user']) && $_POST['user']!='' && isset($_POST['nom']) && $_POST['nom']!=''){
		$passMod=decode($_POST['pass']);
		$correoMod=decode($_POST['correu']);
		$errorMod=false;
		$sqlEmail='SELECT correu FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa>0';
		$resultEmail=mysql_query($sqlEmail);
		if(!mysql_fetch_object($resultEmail)){
			$sqlPass='SELECT pass FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa=0 AND pass="'.$passMod.'"';
			$resultPass=mysql_query($sqlPass);
			if(!mysql_fetch_object($resultPass)){
				$fet=CORRECTO_MODIF_U_CONSUMO;
			}else{
				$fet=ERROR_EXISTE_PAR_EMAIL_CONTRASENYA;
				$errorMod=true;
			}
		}else{
			$sqlEmail='SELECT correu FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa="'.$_POST['caixa'].'"';
			$resultEmail=mysql_query($sqlEmail);
			if(mysql_fetch_object($resultEmail)){
				$sqlPass='SELECT pass FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa=0 AND pass="'.$passMod.'"';
				$resultPass=mysql_query($sqlPass);
				if(!mysql_fetch_object($resultPass)){
					$fet=CORRECTO_MODIF_U_CONSUMO;
				}else{
					$fet=ERROR_EXISTE_PAR_EMAIL_CONTRASENYA;
					$errorMod=true;
				}
			}else{
				$fet=ERROR_EXISTE_EMAIL.': '.$correoMod;
				$errorMod=true;
			}
		}
		if(!$errorMod){
			$nom = mysql_real_escape_string($_POST['nom']);
			$user = mysql_real_escape_string($_POST['user']);
			$mod = "UPDATE unitat_c SET user='".$user."', pass='".$passMod."', nom='".$nom."', correu='".$correoMod."' WHERE id = '".$_SESSION['s_idusuari']."'";
			$res = mysql_query($mod) or die(mysql_error());
		}
	}else{
		$fet=ERROR_DATOS_INTRODUCIDOS;
	}
}

// unitat de consum que es
$consulta = "SELECT * FROM unitat_c WHERE id = ".$_SESSION["s_idusuari"];
$resultat = mysql_query($consulta) or die(mysql_error());
$unitat_c = mysql_fetch_object($resultat);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="estils/template.css" />
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
	<div id="cap" align="center"><img align="middle" src="<?php echo $_SESSION['imatge_cap'];?>" alt=""/></div>
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
		<h4><?php echo $apartat; ?></h4>
		<p><?php echo $fet; ?></p>
		<form method="post" action="dpersonals.php" enctype="multipart/form-data" id="formulari">
			<fieldset>
				<legend><?php echo U_CONSUM; ?></legend>
				<table width="90%" border="0" cellpadding="3" cellspacing="1" align="center">
					<tr>
						<td width="38%" align="left"><label for="user"><?php echo USUARIO; ?></label></td>
						<td width="38%" align="left"><label for="pass"><?php echo PASSWORD; ?>*</label></td>
						<td width="24%" align="left"><label for="pass2"><?php echo PASSWORD_CONFIRM; ?></label></td>
					</tr>
					<tr>
						<td><input style="width: 60%;" name="user" id="user" class="required" type="text" value="<?php echo $unitat_c->user;?>" size="15" maxlength="50" /></td>
						<td><input style="width: 60%;" name="pass" id="pass" class="required password" type="password" value="<?php echo $unitat_c->pass;?>" size="15" maxlength="50" /></td>
						<td><input style="width: 95%;" name="pass2" id="pass2" class="required" equalTo="#pass" type="password" value="<?php echo $unitat_c->pass;?>" size="15" maxlength="50" /></td>
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
						<td><input style="width: 35%;" name="caixa" type="text" value="<?php echo $unitat_c->n_caixa;?>" size="2" readonly="readonly" /></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
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