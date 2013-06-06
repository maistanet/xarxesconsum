<?php
/**
* Menú per a unitats de consum
* Depen de cada xarxa serà un dia i una hora limit per a fer comandes.
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
if(isset($_SESSION['connectat']) && $_SESSION["connectat"]=='true'){
	//Con esto conseguimos que desloguee al usuarix al caducar la sesion
	$duracionSesion=((ini_get('session.gc_maxlifetime')-10)*1000);
?>
	<script type="text/javascript">
	<!--
		window.setInterval('alert("<?php echo SESION_CADUCADA; ?>");',<?php echo $duracionSesion-1000; ?>);
		window.setInterval('window.location.href="logout.php";',<?php echo $duracionSesion; ?>);
	-->
	</script>
<?php
}
//include('includes/sessio.php');

//require('includes/bd/obri.php');

//data i hora que no es podra entrar
//date('l') = Saturday, Sunday, ...
$consulta='SELECT dia_limit,dia_venda,hora_limit,panel_pedido_activo FROM config';
$resultat=mysql_query($consulta) or die(mysql_error());
$limite=mysql_fetch_object($resultat);

//ara
$data_actual=date('H:i:s');

//Devuelve el string $hoy.'!'.$ultimoDiaLimite.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
$limitesAutoBloqueo=explode('!',diasAutoBloqueoPanelPedido());
$hoyAutoBloqueo=$limitesAutoBloqueo[0];
$ultimoDiaLimiteAutoBloqueo=$limitesAutoBloqueo[1];
$proxDiaVentaAutoBloqueo=$limitesAutoBloqueo[2];
?>
<ul>
<?php
$pedidoBloqueado=false;
if(date('l')==$limite->dia_limit && $limite->hora_limit<=$data_actual){
	$pedidoBloqueado=true;
	$avis=FUERA_DE_TIEMPO_INI.$limite->hora_limit.FUERA_DE_TIEMPO_FIN;
	echo '<li><a href="productors.php">'.PRODUCTORXS.'</a></li>
		<li><a href="dpersonals.php">'.D_PERSONALES.'</a></li>
		<li><a href="logout.php">'.SALIR.'</a></li>';
}elseif($hoyAutoBloqueo>$ultimoDiaLimiteAutoBloqueo && $hoyAutoBloqueo<=$proxDiaVentaAutoBloqueo){
	$pedidoBloqueado=true;
	$avis=PANEL_PEDIDO_AUTOBLOQUEADO;
	echo '<li><a href="productors.php">'.PRODUCTORXS.'</a></li>
		<li><a href="dpersonals.php">'.D_PERSONALES.'</a></li>
		<li><a href="logout.php">'.SALIR.'</a></li>';
}elseif(!$limite->panel_pedido_activo){
	$pedidoBloqueado=true;
	$avis=PANEL_PEDIDO_BLOQUEADO;
	echo '<li><a href="productors.php">'.PRODUCTORXS.'</a></li>
		<li><a href="dpersonals.php">'.D_PERSONALES.'</a></li>
		<li><a href="logout.php">'.SALIR.'</a></li>';
}else{
	echo '<li><a href="comanda.php">'.PEDIDO.'</a></li>
		<li><a href="productors.php">'.PRODUCTORXS.'</a></li>
		<li><a href="dpersonals.php">'.D_PERSONALES.'</a></li>
		<li><a href="logout.php">'.SALIR.'</a></li>';
}
?>
</ul>
<h4><?php echo $avis?></h4>