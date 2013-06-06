<?php
/**
* Connexió en la bd corresponent.
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
//header("content-type:text/html; charset=utf-8");
// dades per a la conexió a la BD
// com les diferents xarxes tenen bd diferents seleccionem la bd
$host=HOST;
$user=MYSQL_USER;
if ($_SESSION["xarxa"] == null ) $db=MYDB;
else $db=$_SESSION["xarxa"];

$passwd=MYSQL_PASSWORD;
$errorConexion=false;

// conexió a la BD
if($enlace=mysql_connect($host,$user,$passwd)){
	// seleccionem la BD que anem a utilitzar
	if(!mysql_select_db($db))
		$errorConexion=true;
}else{
	$errorConexion=true;
}
//pasar dades a mysql en utf8
@mysql_query("SET NAMES 'utf8'",$enlace); 
if($errorConexion){
	mysql_close($enlace);
	header('location: '.RUTA_WEB_INCLUDE.'index.php');
	exit();
}
?>