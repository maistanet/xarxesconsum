<?php
/**
* Update dels dels camps del producte: actiu, preu i comentari, per ajax.
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

$id_producte=$_POST['id_producte'];

//construim la consulta segons el que ens vinga
if( isset( $_POST['clicat']) ) $consulta = "UPDATE producte SET actiu='".$_POST['clicat']."' WHERE id = ".$id_producte;
elseif( isset($_POST['preu']) ) $consulta = "UPDATE producte SET preu='".(round($_POST['preu']*100)/100)."' WHERE id = ".$id_producte;
elseif( isset($_POST['comentari']) ) $consulta = "UPDATE producte SET comentari='".$_POST['comentari']."' WHERE id = ".$id_producte;
elseif( isset($_POST['selectCatId']) ) $consulta = "UPDATE producte SET id_categoria='".$_POST['selectCatId']."' WHERE id = ".$id_producte;
else echo no;
$resultat=mysql_query($consulta) or die(mysql_error());
echo hola;
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>