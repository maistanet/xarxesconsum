<?php
/**
* Apartat productors. Et llista els productors.
* Modificar, editar i eliminar productors.
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
$apartat=PRODUCTORXS;
$fet='';

//per a mod, add o del
$mode = $_GET['mode'];
$id = $_GET['id'];
// Sols per a add i mod (formulari)
$validar = $_POST['accept'];

if($mode == 'del'){
	if(isset($_GET['id'])){
		$sqlDelProdsProductor='DELETE FROM producte WHERE id_productor="'.$id.'"';
		mysql_query($sqlDelProdsProductor) or die(mysql_error());
		$sqlDelProductor='DELETE FROM productors WHERE id="'.$id.'"';
		mysql_query($sqlDelProductor) or die(mysql_error());
		$apartat.=' -> '.ELIMINAR;
		$fet=CORRECTO_ELIMINAR_PRODUCTORX;
	}
}elseif($validar==ACEPTAR){
	$nom = $_POST['nom'];
	$localitat = $_POST['localitat'];
	$descripcio = $_POST['descripcio'];
	$posicio = mysql_real_escape_string($_POST['posicio']);
	$contacte = $_POST['contacte'];
	$correu = decode($_POST['correu']);
	if( $_POST['soci'] == 'on') $soci = '1';
	else $soci = '0';
    switch($mode){
		case 'mod':
			if(isset($_GET['id'])){
				$mod='UPDATE productors SET nom_productor="'.$nom.'", localitat="'.$localitat.'", posicio="'.$posicio.'", descripcio="'.$descripcio.'", contacte="'.$contacte.'", correu="'.$correu.'", socis="'.$soci.'" WHERE id="'.$id.'"';
				mysql_query($mod) or die(mysql_error());
				$apartat.=' -> '.MODIFICAR;
				$fet=CORRECTO_MODIF_PRODUCTORX;
			}
		break;
		case 'add':
			$add='INSERT INTO productors (nom_productor, localitat, posicio, descripcio, contacte, correu, socis) VALUES ("'.$nom.'", "'.$localitat.'", "'.$posicio.'", "'.$descripcio.'", "'.$contacte.'", "'.$correu.'", "'.$soci.'")';
			mysql_query($add) or die(mysql_error());
			$apartat.=' -> '.ANYADIR_NUEVO_PRODUCTORX;
			$fet=CORRECTO_ANYADIR_PRODUCTORX;
		break;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="../estils/template.css" />
	<script type="text/javascript" src="../funcions/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.js'; ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.delete').click(function(){
				var answer = confirm(BAJA_PRODUCTORX);
				return answer; // answer is a boolean
			});
		});
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
		<h4><?php echo $apartat; ?> </h4>
		<p><?php echo $fet; ?></p>
		<a href="productormod.php?mode=add"><?php echo ANYADIR_PRODUCTORX; ?></a>
		<table border="0" align="center" cellspacing="0">
			<tr>
				<td><?php echo NUM_ORDEN; ?></td>
				<td>&nbsp;</td>
				<td><?php echo NOMBRE; ?></td>
				<td>&nbsp;</td>
				<td><?php echo LOCALIDAD; ?></td>
				<td>&nbsp;</td>
				<td align="center"><?php echo OPCIONES; ?></td>
			</tr>
<?php
			$consulta = "SELECT id, nom_productor, localitat FROM productors ORDER BY nom_productor ASC";
			$resultat = mysql_query($consulta) or die(mysql_error());
			$i = 1;
			$color1=$_SESSION['color1'];
			$color2=$_SESSION['color2'];
			$color=$color2;
			while ($productor = mysql_fetch_object($resultat)) {
				($color==$color1) ? $color=$color2 : $color=$color1;
?>
				<tr bgcolor="<?php echo $color; ?>">
					<td><?php echo $i;?></td>
					<td>&nbsp;</td>
					<td><?php echo $productor->nom_productor;?></td>
					<td>&nbsp;</td>
					<td><?php echo $productor->localitat;?></td>
					<td>&nbsp;</td>
					<td align="center">
						<a href="productormod.php?mode=mod&id=<?php echo $productor->id;?>"><img src="../estils/mod.png" title="<?php echo MODIFICAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
						&nbsp;
						<a class="delete" href="productors.php?mode=del&id=<?php echo $productor->id;?>"><img src="../estils/del.png" title="<?php echo ELIMINAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
					</td>
				</tr>
<?php
				$i++;
			}
?>
		</table>
	</div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php'); ?>