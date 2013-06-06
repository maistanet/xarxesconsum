<?php
/**
* Llistat de productors socis i no socis. Conctacte i descripció
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
<?php
		//llistar productors separats per socis i no socis
		$llistar_text1='<h2 align="center">'.PRODUCTORX_SOCIX.'</h2>';
		$llistar_text2='<h2 align="center">'.PRODUCTORX_NO_SOCIX.'</h2>';
		$consulta='SELECT * FROM productors ORDER BY socis,posicio ASC';
		$resultat=mysql_query($consulta) or die(mysql_error());
		$color1=$_SESSION['color1'];
		$color2=$_SESSION['color2'];
		while($llauro=mysql_fetch_object($resultat)){
			if($llauro->socis==1){
				$llistar_text1.='<div style="font-size: 12px;">
									<table width="70%" align="center" cellpadding="3" cellspacing="3">
										<tr bgcolor="'.$color1.'">
											<td width="20%" align="left">'.$llauro->nom_productor.'</td>
											<td width="20%" align="left">'.$llauro->localitat.'</td>
											<td width="30%" align="left">'.CONTACTO.': '.$llauro->contacte.'</td>
											<td width="30%" align="left">'.CORREO.': '.$llauro->correu.'</td>
										</tr>
										<tr bgcolor="'.$color2.'">
											<td colspan="4" align="left">'.$llauro->descripcio.'</td>
										</tr>
									</table>
								</div><br />';
			}else{
				$llistar_text2.='<div style="font-size: 12px;">
									<table width="70%" align="center" cellpadding="3" cellspacing="3">
										<tr bgcolor="'.$color1.'">
											<td width="20%" align="left">'.$llauro->nom_productor.'</td>
											<td width="20%" align="left">'.$llauro->localitat.'</td>
											<td width="30%" align="left">'.CONTACTO.': '.$llauro->contacte.'</td>
											<td width="30%" align="left">'.CORREO.': '.$llauro->correu.'</td>
										</tr>
										<tr bgcolor="'.$color2.'">
											<td colspan="4" align="left">'.$llauro->descripcio.'</td>
										</tr>
									</table>
								</div><br />';
			}
		}
		echo $llistar_text1."<br />".$llistar_text2."<br />";
?>
	</div>
	<div id="peu"></div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php'); ?>