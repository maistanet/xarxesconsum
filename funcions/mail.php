<?php
/**
* Funcio mail, per enviar correus des del servidor
* Per si el servidor en qüestió no te un servidor de correu utilitzaríem phpmailer (està comentat*1)
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
include(RUTA_WEB_INCLUDE.'/includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');

//cometat*1
require_once(RUTA_WEB_INCLUDE.'funcions/PHPMailer_v5.1/class.phpmailer.php');
require_once(RUTA_WEB_INCLUDE.'funcions/PHPMailer_v5.1/class.smtp.php');


function smtpmailer($to, $subject, $body){
	//segons la bd serà una adreça
	$consulta = "SELECT correu FROM correu";
	$resultat = mysql_query($consulta) or die(mysql_error());
	$row = mysql_fetch_object($resultat);

//comentat*1
	//correu
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = REMITENTE_SMTP;
	$mail->Port = 465;
	$mail->Username = REMITENTE_MAIL;
	$mail->Password = REMITENTE_PASS;
	$mail->SetFrom(REMITENTE_MAIL, REMITENTE_NOMBRE);
	$mail->ClearReplyTos();
	$mail->AddReplyTo($row->correu,utf8_decode($_SESSION['nom_xarxa']));
	$mail->Subject = $subject;
	$mail->IsHTML(true);
	$mail->Body = $body;
	$mail->AddAddress($to);

	if(!$mail->Send()){
		$error = 'Mail error: '.$mail->ErrorInfo;
		return false;
	}else{
		$error = 'Message sent';
		return true;
	}
	return $error;
//Fin comentat*1
/*
	$headers = 'From: '.$_SESSION["nom_xarxa"].'<'.$row->correu.">\r\n" .
		'Reply-To: '.$row->correu. "\r\n" .
		"MIME-Version: 1.0" . "\r\n" .
		"Content-type: text/html; charset=UTF-8";

	mail($to, $subject, $body, $headers);
*/
}
?>