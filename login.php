<?php
/**
* Login
* Denpen de quina xarxa obrira una base de dades diferent.
* hi ha una base de dades general, per a saber a quina bd referir-se
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
include('includes/iniciar_aplicacion.php');

//lleva caracters especials par a evitar sqlinjectio
function decode($string) {
	//$nopermitidos = array("'",'\\','<','>',"\"","-","%");
	$nopermitidos = array("'",'\\','<','>',"\"","%");
	$string = str_replace($nopermitidos, "", $string);
	return $string;
}
// Obrim sessió, si està correcte l'usuari i pas posem a la sessió l'usuari sinó tanquem sessió
// el n_caixa = 0 és administrador

// verificació de l'usuari
if( $_POST['accept']==ENTRAR) {
	//Comprobació del nom i password
	$usuari = decode($_POST['usuari']);
	$pass = decode($_POST['pass']);

	$_SESSION["xarxa"] = null;
	require('includes/bd/obri.php');
	$cons = "SELECT xarxa_bd,nom,imatge_cap FROM xarxes WHERE id = '".$_POST['xarxa']."'";
	$res = mysql_query($cons) or die(mysql_error());
	$row = mysql_fetch_array($res);
	$_SESSION["xarxa"] = $row[0];
	//$_SESSION["nom_xarxa"] = $row[1];
	$_SESSION['imatge_cap'] = 'estils/'.$row[2];

	// obrim la bd corresponent
	mysql_free_result($res);
	require('includes/bd/tanca.php');
	require('includes/bd/obri.php');
/*
	// Obrim sessió
	session_start();
	$_SESSION["xarxa"] = $_POST['xarxa'];
	require('includes/bd/obri.php');
*/
	if ($pass==NULL || $usuari==NULL){
		header("location: index.php?error=1");
	}else{
		$cons='SELECT id,user,pass,n_caixa FROM unitat_c WHERE correu="'.$usuari.'" AND pass="'.$pass.'"';
		$res=mysql_query($cons) or die(mysql_error());
		$row=mysql_fetch_object($res);
		if($row->pass != $pass) {
			header("location: index.php?error=1");
		}else{
			$_SESSION["s_idusuari"]=$row->id;
			$_SESSION["s_usuari"]=$row->user;
			$_SESSION["connectat"]="true";
			$sqlNomXarxa="SELECT nom_xarxa FROM config";
			$resNomXarxa=mysql_query($sqlNomXarxa) or die(mysql_error());
			$filaNomXarxa=mysql_fetch_object($resNomXarxa);
			$_SESSION["nom_xarxa"]=$filaNomXarxa->nom_xarxa;
			//Colores para las tablas de registros
			$_SESSION['color1']="#BADBBA";
			$_SESSION['color2']="#DBD0D0";
			// per saber si es l'administrador o l'usuari
			if($row->n_caixa=='0') header("location: admin/index.php");
			else header("location: unitatsconsum.php");
		}
	}
}
// alliberem el resultat de la consulta
mysql_free_result($res);
mysql_free_result($resNomXarxa);
// tanquem la conexió a la BD
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>