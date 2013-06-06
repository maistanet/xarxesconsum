<?php
/**
* Menu para el administrador
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
		window.setInterval('window.location.href="../logout.php";',<?php echo $duracionSesion; ?>);
	-->
	</script>
<?php
}
?>
<ul>
	<li><a href="historial.php"><?php echo HISTORIAL; ?></a></li>
	<li><a href="totalscomanda.php"><?php echo TOTALES_PEDIDO; ?></a></li>
	<li><a href="productors.php"><?php echo PRODUCTORXS; ?></a></li>
	<li><a href="productes.php"><?php echo PRODUCTOS; ?></a></li>
	<li><a href="categories.php"><?php echo CATEGORIAS; ?></a></li>
	<li><a href="avis.php"><?php echo AVISOS; ?></a></li>
</ul>
<br />
<ul>
	<li><a href="usuaries.php"><?php echo UNIDADES_C; ?></a></li>
	<li><a href="config.php"><?php echo CONFIG; ?></a></li>
	<li><a href="../logout.php"><?php echo SALIR; ?></a></li>
</ul>
<br />