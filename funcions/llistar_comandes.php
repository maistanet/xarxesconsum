<?php
/**
* Llistar comandes, segons del dia de venda i el dia limit llistem les comandes
* Tindrem els totals per a enviar-los als productors
* Genera un pdf amb les comandes de les unitats de consum
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
//Configuraciones + abrir sesión
include('../includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'funcions/funcions.php');
include(RUTA_WEB_INCLUDE.'includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');
include(RUTA_WEB_INCLUDE.'funcions/generapdf.php');

//Devuelve el string $data_abans.'!'.$data_limit.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
$limites=explode('!',diasLimiteVenta());
$data_abans=$limites[0];
$data_limit=$limites[1];
$proxDiaVenta=$limites[2];

//creem l'array amb tots els productes, ordenats per productor
$productes = array(); //creem matriu
$con = "SELECT id FROM producte WHERE actiu = 1 ORDER BY id_productor,nom_producte ASC";
$res = mysql_query($con) or die(mysql_error());
while ($prod = mysql_fetch_object($res)) {
	$productes[]["id"] = $prod->id;
}

$text='';
$text='
<html>
<head>
<style>
.comanda{
float: left;
width: 80mm;
margin-left: 10mm;
}
.totals{
clear:left;}
</style>
</head>';
$text.='<body>';
$text.='<h2 align="center"><u>'.$_SESSION["nom_xarxa"].'</u></h2><br />';
$text.='<h3 align="center">'.SEMANA.' '.date("W").', '.trans_data($proxDiaVenta).'</h3>';
$comandaNumero = 1;
$consulta = "SELECT * FROM comanda AS t1, unitat_c AS t2 WHERE t1.id_unitat_c = t2.id && data BETWEEN '".$data_abans."' AND '".$data_limit."' ORDER BY n_caixa ASC";
$resultat = mysql_query($consulta) or die(mysql_error());
$color1=$_SESSION['color1'];
$color2=$_SESSION['color2'];
while ($comanda = mysql_fetch_object($resultat)) {
	$t_producte = unserialize($comanda->t_producte);
	$t_quantitat = unserialize($comanda->t_quantitat);
	$t_pvp = unserialize($comanda->t_pvp);

	//per als totals, array agafa les quantitats
	for($j=0; $j<count($t_producte); $j++) {
		for($k=0; $k<count($productes); $k++) {
			if($t_producte[$j] == $productes[$k]["id"]) {
				$productes[$k]["quantitat"] += $t_quantitat[$j];
			}else $productes[$k]["quantitat"] += 0;
		}
	}

	$text.='<div class="comanda"><h4><u>'.$comandaNumero.') ['.$comanda->n_caixa.'] '.$comanda->nom.'</u></h4>';
	if($comanda->observacions != "") $text.=NOTAS.': '.$comanda->observacions;
	$text.='<table width="95%">';
	$text.='<tr><td width="25%" align="left">'.CANTIDAD.'</td>';
	$text.='<td width="40%" align="left">'.PRODUCTO.'</td>';
	$text.='<td width="20%" align="left">'.PRODUCTORX.'</td>';
	$text.='<td width="15%" align="right">'.PRECIO.'</td></tr>';
	$color=$color2;
	for($i=0; $i<count($t_producte); $i++){
		($color==$color1) ? $color=$color2 : $color=$color1;
		$con_prod = "SELECT nom_productor,nom_producte,format FROM productors AS t1, producte AS t2 WHERE t1.id = t2.id_productor AND t2.id = ".$t_producte[$i];
		$res_prod = mysql_query($con_prod) or die(mysql_error());
		$nom_prod = mysql_fetch_array($res_prod);
		if($nom_prod[2] == 'unitat') $format=UNIDADES;
		else $format='Kg';
		$text.='<tr bgcolor="'.$color.'"><td>'.$t_quantitat[$i].'&nbsp;'.$format.'</td>';
		$text.='<td>'.htmlentities($nom_prod[1], ENT_QUOTES, "UTF-8").'</td>
					<td>('.substr($nom_prod[0], 0, 2).')</td>
					<td align="right">'.(round(($t_pvp[$i]*$t_quantitat[$i])*100)/100).'&euro;</td></tr>';
	}
	$text.='<tr><td colspan="4">&nbsp;</td></tr>
			<tr>
				<td colspan="3" align="right">'.TOTAL.':</td>
				<td align="right">'.$comanda->total.'&euro;</td>
			</tr>
		</table>
	</div>';
	$comandaNumero++;
}

//pagina nova apreixen els totals sumats
$text.='<div class="totals"><h3 align="center"><br /><br />'.TOTALES_DE_LOS_PEDIDOS.'</h3><table width="90%" align="center">';
$text.='<tr>
			<td width="32%" align="left">'.PRODUCTORX.'</td>
			<td width="35%" align="left">'.PRODUCTO.'</td>
			<td width="15%" align="center">'.PRECIO_UNIDAD.'</td>
			<td width="10%" align="center">'.CANTIDAD.'</td>
			<td width="8%" align="center">'.FORMATO.'</td>
		</tr>';
//si el producte està a zero no apareix
$color=$color2;
for($i=0; $i<count($productes); $i++){
	if( $productes[$i]["quantitat"] != "0" ) {
		($color==$color1) ? $color=$color2 : $color=$color1;
		$consulta = "SELECT nom_productor,nom_producte,format,preu FROM productors AS t1, producte AS t2 WHERE t1.id = t2.id_productor AND t2.id = ".$productes[$i]["id"];
		//$text .= $productes[$i]["id"];
		$resultat = mysql_query($consulta) or die(mysql_error());
		$producte = mysql_fetch_array($resultat);
		if($producte[2] == 'unitat') $format=UNIDADES;
		else $format='Kg';
		$text.='<tr bgcolor="'.$color.'">
					<td>'.htmlentities($producte[0], ENT_QUOTES, "UTF-8").'</td>
					<td>'.htmlentities($producte[1], ENT_QUOTES, "UTF-8").'</td>
					<td align="center">'.$producte[3].'&euro;</td>
					<td align="right">'.$productes[$i]["quantitat"].'</td>
					<td align="left">'.$format.'</td>
				</tr>';
	}
}
$text.='</table></div>';
$text.='</body></html>';
//generem pdf
generarpdf($text);
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>