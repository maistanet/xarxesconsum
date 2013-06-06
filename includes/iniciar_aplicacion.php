<?php
/**
* PHP version 5
*
* @author     Maistanet.com <info@maistanet.com> i Atornallom - Cooperativa Integral Valenciana - http://atornallom.net
* @copyright  2012 Maistanet.com i Atornallom - Cooperativa Integral Valenciana - http://atornallom.net
* @package    xarxa
* @license    http://www.gnu.org/copyleft/gpl.html  Distributed under the General Public License (GPL) 
* @version    1.1
* @link       https://github.com/maistanet/xarxesconsum
*/
//Configuraciones
//---------------------------------
//DATOS DE ACCESO A LA BBDD GENERAL
//---------------------------------
define('HOST','localhost');
define('MYSQL_USER','jyscnfnt_gconsum');
define('MYSQL_PASSWORD','CA?^~zM~~8J;');
define('MYDB','jyscnfnt_gc_general');
//------------------------------------------------------------
//CONSTANTES DE CONFIGURACION DEL DIRECTORIO DONDE ESTA LA WEB
//------------------------------------------------------------
define('DIR_WEB','xarxesconsum/'); //Directorio donde esta la pagina
if ($_SERVER['DOCUMENT_ROOT'] == ""){
   define('SERVER_DIR', realpath(dirname(__FILE__).'/..')); //Directorio raiz del documento en el servidor
   define('RUTA_WEB_INCLUDE', SERVER_DIR.'/');
}
else {
  define('SERVER_DIR',$_SERVER['DOCUMENT_ROOT'].'/'); //Directorio raiz del documento en el servidor
  define('RUTA_WEB_INCLUDE',SERVER_DIR.DIR_WEB); //Ruta al directorio raiz donde esta la pagina para "includes y requires"
  }
  define('RUTA_WEB','http://'.$_SERVER['HTTP_HOST'].'/'.DIR_WEB); //Ruta al directorio raiz donde esta la pagina
//------------------------------------------
//DATOS DEL MAIL REMITENTE DE LA HERRAMIENTA
//------------------------------------------
define('REMITENTE_MAIL','');
define('REMITENTE_NOMBRE','');
define('REMITENTE_PASS','');
define('REMITENTE_SMTP','');
//-------------------------------
//Definimos el idioma por defecto
define('IDIOMA_X_DEFECTO','ca');
//-------------------------------
//Inicio sesion
session_start();

//Detectamos el idioma actual
if(!isset($_SESSION['lang']))
	$_SESSION['lang']=IDIOMA_X_DEFECTO;
if(isset($_POST['ca']))
	$_SESSION['lang']='ca'; //Catalan / Valenciano
if(isset($_POST['es']))
	$_SESSION['lang']='es'; //Castellano

//Cargamos el fichero de idioma
if(file_exists(RUTA_WEB_INCLUDE.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.php'))
	require(RUTA_WEB_INCLUDE.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.php');
else //Si no existe el fichero de idioma para el idioma actual, cargamos el fichero de idioma por defecto
	require(RUTA_WEB_INCLUDE.'idiomas/'.IDIOMA_X_DEFECTO.'/'.IDIOMA_X_DEFECTO.'.php');
?>
