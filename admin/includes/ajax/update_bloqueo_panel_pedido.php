<?php
/**
* Bloqueo y desbloqueo del apartado "pedido" en el panel de usuarix, mediante ajax.
* Para cuando se está modificando precios, etc... o para cuando estamos en la semana que no toca pedir, etc...
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
header('content-type:text/html; charset=utf-8');

//Configuraciones + abrir sesión
include('../../../includes/iniciar_aplicacion.php');
require(RUTA_WEB_INCLUDE.'admin/includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');

if(isset($_POST['clicat'])){
	$activo=1;
	if($_POST['clicat']==1)
		$activo=0;
	$consulta='UPDATE config SET panel_pedido_activo="'.$activo.'"';
	mysql_query($consulta) or die(mysql_error());
}
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>