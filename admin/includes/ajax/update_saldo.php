<?php
/**
* Update del saldo de la unitat_c, per ajax.
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
include('../../../includes/iniciar_aplicacion.php');
require(RUTA_WEB_INCLUDE.'admin/includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');

if(isset($_POST['saldo']) && isset($_POST['id_uc'])){
	$id_uc=$_POST['id_uc'];
	$sqlSaldoUC='SELECT saldo FROM saldo WHERE id_uc="'.$id_uc.'" AND id_moneda="1"'; //Saldo en euros
	$resultSaldoUC=mysql_query($sqlSaldoUC) or die(mysql_error());
	if(mysql_num_rows($resultSaldoUC)>0)
		$sqlSaldo='UPDATE saldo SET saldo="'.(round($_POST['saldo']*100)/100).'" WHERE id_uc="'.$id_uc.'" AND id_moneda="1"'; //Saldo en euros
	else
		$sqlSaldo='INSERT INTO saldo (id_uc,id_moneda,saldo) VALUES ("'.$id_uc.'","1","'.(round($_POST['saldo']*100)/100).'")'; //Saldo en euros
}
mysql_query($sqlSaldo) or die(mysql_error());
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>