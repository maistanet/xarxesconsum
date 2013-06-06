<?php
/**
* Apartat totals de les comandes.
* Llista els totals per productor dels productes sumats de totes les comandes. 
* Llista comentaris referents a les comandes de cada unitat de consum que haja posat comentari.
* Imprimeix pdf de les comandes i dels totals sumats dels productes.
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
$apartat=TOTALES_PEDIDO;
$fet='';

//Devuelve el string $data_abans.'!'.$data_limit.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
$limites=explode('!',diasLimiteVenta());
$data_abans=$limites[0];
$data_limit=$limites[1];
$proxDiaVenta=$limites[2];

//Para traducir el $limite->dia_venda del ingles
$NombreProxDiaVenta=trans_nombre_dia_venta();

//creem l'array amb tots els productes, ordenats per productor
$productes = array(); //creem matriu
$consulta = "SELECT id FROM producte WHERE actiu = 1 ORDER BY id_productor,nom_producte ASC";
$resultat = mysql_query($consulta) or die(mysql_error());
while ($producte = mysql_fetch_object($resultat)) {
	$productes[]["id"] = $producte->id;
}
//where data
$consulta = "SELECT t_producte,t_quantitat FROM comanda WHERE data BETWEEN '".$data_abans."' AND '".$data_limit."'";
$resultat = mysql_query($consulta) or die(mysql_error());
while ($comanda = mysql_fetch_object($resultat)) { //Cuando no hay comandas, no se inicializan las cantidades
	$t_producte = unserialize($comanda->t_producte);
	$t_quantitat = unserialize($comanda->t_quantitat);
	for($j=0; $j<count($t_producte); $j++) {
		for($i=0; $i<count($productes); $i++) {
			if($t_producte[$j] == $productes[$i]["id"]) {
				$productes[$i]["quantitat"] += $t_quantitat[$j];
			}else $productes[$i]["quantitat"] += 0;
		}
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
		<h6><?php echo PROXIMA_SEMANA_INI; ?> <?php echo date("W").', '.$NombreProxDiaVenta; ?> <?php echo PROXIMA_SEMANA_FIN; ?> <?php echo trans_data($proxDiaVenta);?></h6>
		<a href="<?php echo RUTA_WEB.'funcions/llistar_comandes.php'; ?>" target="_blank"><?php echo PEDIDOS_U_CONSUMO; ?></a>
		<table width="100%" cellpadding="4px" cellspacing="0">
			<tr>
				<td width="32%" align="left"><?php echo PRODUCTORX; ?></td>
				<td width="35%" align="left"><?php echo PRODUCTO; ?></td>
				<td width="15%" align="center"><?php echo PRECIO_UNIDAD; ?></td>
				<td width="10%" align="center"><?php echo CANTIDAD; ?></td>
				<td width="8%" align="center"><?php echo FORMATO; ?></td>
			</tr>
<?php
			//si el producte està a zero no apareix (Cuando hay comandas)
			//Si no hay comandas aparecen todos los productos (Cuando no hay comandas, no se inicializan las cantidades)
			$color1=$_SESSION['color1'];
			$color2=$_SESSION['color2'];
			$color=$color2;
			for($i=0; $i<count($productes); $i++){
				if( $productes[$i]["quantitat"] !== 0 ) {
					($color==$color1) ? $color=$color2 : $color=$color1;
					$consulta = "SELECT nom_productor,nom_producte,format,preu FROM productors AS t1, producte AS t2 WHERE t1.id = t2.id_productor AND t2.id = ".$productes[$i]["id"];
					$resultat = mysql_query($consulta) or die(mysql_error());
					$producte = mysql_fetch_array($resultat);
					$formatoMostrar=$producte[2];
					if($producte[2] == 'gr') $formatoMostrar='Kg';
					if($producte[2] == 'unitat') $formatoMostrar=UNIDADES;
					echo '<tr bgcolor="'.$color.'">
						<td>'.$producte[0].'</td>
						<td>'.$producte[1].'</td>
						<td align="center">'.$producte[3].'&nbsp;&euro;</td>
						<td align="right">'.$productes[$i]["quantitat"].'</td>
						<td>'.$formatoMostrar.'</td>
					</tr>';
				}
			}
?>
		</table>
<?php
		//where data
		$consulta = "SELECT observacions,nom FROM comanda AS t1, unitat_c AS t2 WHERE t1.id_unitat_c = t2.id && data BETWEEN '".$data_abans."' AND '".$data_limit."'";
		$resultat = mysql_query($consulta) or die(mysql_error());
?>
		<br />
		<fieldset>
			<legend><?php echo COMENTARIOS; ?></legend>
			<table width="100%" cellpadding="3" cellspacing="3">
				<tr>
					<td width="20%" align="left"><?php echo U_CONSUM; ?></td>
					<td width="5%" align="left">&nbsp;</td>
					<td width="75%" align="left"><?php echo COMENTARIO; ?></td>
				</tr>
<?php
				while ($comanda = mysql_fetch_object($resultat)) {
					if( $comanda->observacions != ""){
						echo '<tr>
							<td>'.$comanda->nom.'</td>
							<td>&nbsp;</td>
							<td>'.$comanda->observacions.'</td>
						</tr>';
					}
				}
?>
			</table>
		</fieldset>
	</div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php'); ?>