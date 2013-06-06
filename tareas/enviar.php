<?php
/**
* Genera los envios de los pedidos de los grupos de consumo en la tabla envio.
* Finalmente se basa en dicha tabla para efectuar el envio de los correos.
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
//set_time_limit(0);
//Descomentar el idioma elegido para el envio de los pedidos
//$_POST['ca']='ca';
//$_POST['es']='es';
//Configuraciones + abrir sesion
include('../includes/iniciar_aplicacion.php');
$emailXarxa='';
$emailAdmin='';
//Array con pedidos conjuntos a productorxs
$pedidos=array();
$contadorPedidos=0;
//Array con los grupos ya "preparados para enviar" (los que tienen algun registro de hoy o de "ayer" en tabla envio (para cuando se ejecute pasadas las 00:00))
$gruposYaPreparados=array();
//Array con los grupos aun "no preparados para enviar"
$grupos=array();

//Conectamos a BBDD xa_general
$_SESSION['xarxa']=null;
require(RUTA_WEB_INCLUDE.'tareas/obri.php');
$sqlYaPreparados='SELECT id_xarxa FROM envio WHERE DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= fecha_envio ORDER BY id_xarxa ASC';
$resultYaPreparados=mysql_query($sqlYaPreparados) or die();
while($filaYaPreparados=mysql_fetch_object($resultYaPreparados))
	$gruposYaPreparados[]=$filaYaPreparados->id_xarxa;
//Rellenamos el array con los grupos aun "no preparados para enviar"
$sqlGrupos='SELECT id,xarxa_bd FROM xarxes ORDER BY id ASC';
$resultGrupos=mysql_query($sqlGrupos) or die();
$i=0;
while($filaGrupos=mysql_fetch_object($resultGrupos)){
	if(!in_array($filaGrupos->id,$gruposYaPreparados)){
		$grupos[$i]['id']=$filaGrupos->id;
		$grupos[$i]['xarxa_bd']=$filaGrupos->xarxa_bd;
		$i++;
	}
}
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');

//En el array $grupos solo han quedado los que aun no hemos preparado sus pedidos de esta semana
for($i=0;$i<count($grupos);$i++){
	//Conectamos a BBDD del GC
	$_SESSION['xarxa']=$grupos[$i]['xarxa_bd'];
	$idXarxa=$grupos[$i]['id'];
	require(RUTA_WEB_INCLUDE.'tareas/obri.php');
	//Comprobamos si se ha cerrado el plazo para hacer el pedido
	$sqlConfig='SELECT nom_xarxa,dia_limit,dia_venda,hora_limit FROM config';
	$resultConfig=mysql_query($sqlConfig) or die();
	$filaConfig=mysql_fetch_object($resultConfig);
	if((date('l')==$filaConfig->dia_limit && date('H:i:s')>=$filaConfig->hora_limit) || date('l',time()-86400)==$filaConfig->dia_limit){
		$nomXarxa=$filaConfig->nom_xarxa;
		//Obtenemos fechas
		$ultimoDiaVenta='';
		$diaLimitePedido='';
		$proxDiaVenta='';
		//Ultimo dia de venta
		$dia_a=date('d',strtotime('last '.$filaConfig->dia_venda));
		$mes_a=date('m',strtotime('last '.$filaConfig->dia_venda));
		$any_a=date('Y',strtotime('last '.$filaConfig->dia_venda));
		$ultimoDiaVenta=$any_a.'-'.$mes_a.'-'.$dia_a;
		//Dia limite para hacer el pedido
		$dia=date('d',strtotime($filaConfig->dia_limit));
		$mes=date('m',strtotime($filaConfig->dia_limit));
		$any=date('Y',strtotime($filaConfig->dia_limit));
		$diaLimitePedido=$any.'-'.$mes.'-'.$dia;
		//Proximo dia de venta
		$dia_v=date('d',strtotime($filaConfig->dia_venda));
		$mes_v=date('m',strtotime($filaConfig->dia_venda));
		$any_v=date('Y',strtotime($filaConfig->dia_venda));
		$proxDiaVenta=$dia_v.'-'.$mes_v.'-'.$any_v; //Para el asunto en el envio del pedido

		$sqlEmailXarxa='SELECT correu FROM correu';
		$resultEmailXarxa=mysql_query($sqlEmailXarxa) or die();
		$filaEmailXarxa=mysql_fetch_object($resultEmailXarxa);
		$emailXarxa=$filaEmailXarxa->correu;

		$sqlEmailAdmin='SELECT correu FROM unitat_c WHERE n_caixa=0';
		$resultEmailAdmin=mysql_query($sqlEmailAdmin) or die();
		$filaEmailAdmin=mysql_fetch_object($resultEmailAdmin);
		$emailAdmin=$filaEmailAdmin->correu;

		//Obtenemos todos los productos del grupo
		$productos=array();
		$sqlProductos='SELECT id FROM producte ORDER BY id_productor,nom_producte ASC';
		$resultProductos=mysql_query($sqlProductos) or die();
		while($filaProductos=mysql_fetch_object($resultProductos)){
			$productos[]['id']=$filaProductos->id;
		}

		//Obtenemos los pedidos conjuntos del grupo
		$sqlPedidos='SELECT t_producte,t_quantitat,t_pvp FROM comanda WHERE data BETWEEN "'.$ultimoDiaVenta.'" AND "'.$diaLimitePedido.'"';
		$resultPedidos=mysql_query($sqlPedidos) or die();
		while($filaPedidos=mysql_fetch_object($resultPedidos)){
			$t_producte=unserialize($filaPedidos->t_producte);
			$t_quantitat=unserialize($filaPedidos->t_quantitat);
			$t_pvp=unserialize($filaPedidos->t_pvp);
			for($j=0;$j<count($t_producte);$j++){
				for($k=0;$k<count($productos);$k++){
					if($t_producte[$j]==$productos[$k]['id']){
						$productos[$k]['quantitat']+=$t_quantitat[$j];
						$productos[$k]['gasto']+=(round(($t_pvp[$j]*$t_quantitat[$j])*100)/100);
					}else{
						$productos[$k]['quantitat']+=0;
						$productos[$k]['gasto']+=0;
					}
				}
			}
		}

		//Rellenamos el array $pedidos con los datos necesarios para el envio de los correos, solo con los productos con cantidad>0
		for($z=0;$z<count($productos);$z++){
			if($productos[$z]['quantitat']>0){
				$sqlProd='SELECT t1.id AS id_productorx,nom_productor,correu,nom_producte,format,preu FROM productors AS t1,producte AS t2 WHERE t1.id=t2.id_productor AND t2.id="'.$productos[$z]['id'].'"';
				$resultProd=mysql_query($sqlProd) or die();
				$filaProd=mysql_fetch_object($resultProd);
				$formatoMostrar=$filaProd->format;
				if($filaProd->format=='gr') $formatoMostrar='Kg';
				if($filaProd->format=='unitat') $formatoMostrar=UNIDADES;
				//Datos grupo
				$pedidos[$contadorPedidos]['id_xarxa']=$idXarxa;
				$pedidos[$contadorPedidos]['nomXarxa']=$nomXarxa;
				$pedidos[$contadorPedidos]['emailXarxa']=$emailXarxa;
				$pedidos[$contadorPedidos]['emailAdmin']=$emailAdmin;
				$pedidos[$contadorPedidos]['proxDiaVenta']=$proxDiaVenta;
				//Datos productorx
				$pedidos[$contadorPedidos]['id_productorx']=$filaProd->id_productorx;
				$pedidos[$contadorPedidos]['nombre_productorx']=$filaProd->nom_productor;
				$pedidos[$contadorPedidos]['email_productorx']=$filaProd->correu;
				//Datos producto
				$pedidos[$contadorPedidos]['nom_producte']=$filaProd->nom_producte;
				$pedidos[$contadorPedidos]['preu']=$filaProd->preu;
				$pedidos[$contadorPedidos]['quantitat']=$productos[$z]['quantitat'];
				$pedidos[$contadorPedidos]['format']=$formatoMostrar;
				$pedidos[$contadorPedidos]['gasto']=$productos[$z]['gasto'];
				$contadorPedidos++;
			}
		}
	}
	require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
}

//Conectamos a BBDD xa_general
$_SESSION['xarxa']=null;
require(RUTA_WEB_INCLUDE.'tareas/obri.php');
if($contadorPedidos>0){
	$color1='#BADBBA';
	$color2='#DBD0D0';
	$color=$color2;
	$cabeceraTabla='<table width="100%" cellpadding="4px" cellspacing="0">
						<tr>
							<td width="52%" align="left">'.PRODUCTO.'</td>
							<td width="18%" align="center">'.PRECIO_UNIDAD.'</td>
							<td width="10%" align="right">'.CANTIDAD.'</td>
							<td width="10%" align="left">'.FORMATO.'</td>
							<td width="10%" align="right">'.TOTAL.'</td>
						</tr>';
	$asunto='';
	$cuerpo=$cabeceraTabla;
	$gastoTotal=0;
	//Generamos un envio para cada productorx por cada GC
	for($i=0;$i<count($pedidos);$i++){
		($color==$color1) ? $color=$color2 : $color=$color1;
		$gastoTotal+=$pedidos[$i]['gasto'];
		$cuerpo.='<tr bgcolor="'.$color.'">
					<td align="left">'.utf8_decode($pedidos[$i]['nom_producte']).'</td>
					<td align="center">'.$pedidos[$i]['preu'].'&euro;</td>
					<td align="right">'.$pedidos[$i]['quantitat'].'</td>
					<td align="left">'.$pedidos[$i]['format'].'</td>
					<td align="right">'.$pedidos[$i]['gasto'].'&euro;</td>
				</tr>';
		if($pedidos[$i]['id_xarxa']!=$pedidos[$i+1]['id_xarxa'] || $pedidos[$i]['id_productorx']!=$pedidos[$i+1]['id_productorx']){
			$emailXarxa='';
			if(filter_var($pedidos[$i]['emailXarxa'],FILTER_VALIDATE_EMAIL))
				$emailXarxa=$pedidos[$i]['emailXarxa'];
			$emailAdmin='';
			if(filter_var($pedidos[$i]['emailAdmin'],FILTER_VALIDATE_EMAIL))
				$emailAdmin=$pedidos[$i]['emailAdmin'];
			$emailProductorx='';
			if(filter_var($pedidos[$i]['email_productorx'],FILTER_VALIDATE_EMAIL))
				$emailProductorx=$pedidos[$i]['email_productorx'];
			$nombreXarxa=utf8_decode($pedidos[$i]['nomXarxa']);
			$asunto=ASUNTO_INI.' '.$nombreXarxa.' '.ASUNTO_FIN.' '.$pedidos[$i]['proxDiaVenta'];
			$cuerpo.='<tr><td colspan="5">&nbsp;</td></tr>
						<tr>
							<td colspan="4" align="right">'.TOTAL.':</td>
							<td align="right">'.$gastoTotal.'&euro;</td>
						</tr>
					</table>
					<br /><br />'.SALUDOS;
			$cuerpo=HOLA.' '.utf8_decode($pedidos[$i]['nombre_productorx']).', '.PEDIDO_DEL_GC.' '.$nombreXarxa.' '.ASUNTO_FIN.' '.$pedidos[$i]['proxDiaVenta'].'<br /><br />'.$cuerpo;
			$sqlInsertEnvio='INSERT INTO envio (id_xarxa,nombre_xarxa,email_xarxa,email_admin,id_productorx,nombre_productorx,email_productorx,fecha_envio,asunto,cuerpo,enviado) VALUES ("'.$pedidos[$i]['id_xarxa'].'","'.htmlentities(addslashes($nombreXarxa),ENT_COMPAT,'ISO-8859-1').'","'.$emailXarxa.'","'.$emailAdmin.'","'.$pedidos[$i]['id_productorx'].'","'.$pedidos[$i]['nombre_productorx'].'","'.$emailProductorx.'",NOW(),"'.htmlentities(addslashes($asunto),ENT_COMPAT,'ISO-8859-1').'","'.htmlentities(addslashes($cuerpo),ENT_COMPAT,'ISO-8859-1').'","0")';
			mysql_query($sqlInsertEnvio) or die();
			$color=$color2;
			$asunto='';
			$cuerpo=$cabeceraTabla;
			$gastoTotal=0;
		}
	}
}

$rango=30;
$sqlEnvio='SELECT id_envio,nombre_xarxa,email_xarxa,email_admin,email_productorx,nombre_productorx,asunto,cuerpo FROM envio WHERE enviado="0" ORDER BY id_envio ASC LIMIT 0,'.$rango;
if($resultEnvio=mysql_query($sqlEnvio)){
	require_once(RUTA_WEB_INCLUDE.'funcions/PHPMailer_v5.1/class.phpmailer.php');
	require_once(RUTA_WEB_INCLUDE.'funcions/PHPMailer_v5.1/class.smtp.php');
	while($filaEnvio=mysql_fetch_object($resultEnvio)){
		$error=false;
		$idEnvio='';
		$nombreXarxa='';
		$to='';
		$aviso='';
		$idEnvio=$filaEnvio->id_envio;
		$nombreXarxa=html_entity_decode($filaEnvio->nombre_xarxa,ENT_COMPAT,'ISO-8859-1');
		$nombreProductorx=utf8_decode($filaEnvio->nombre_productorx);
		$emailXarxa='';
		if(filter_var($filaEnvio->email_xarxa,FILTER_VALIDATE_EMAIL))
			$emailXarxa=$filaEnvio->email_xarxa;
		$emailAdmin='';
		if(filter_var($filaEnvio->email_admin,FILTER_VALIDATE_EMAIL))
			$emailAdmin=$filaEnvio->email_admin;
		$emailProductorx='';
		if(filter_var($filaEnvio->email_productorx,FILTER_VALIDATE_EMAIL))
			$emailProductorx=$filaEnvio->email_productorx;
		if($emailXarxa=='' && $emailAdmin=='')
			$error=true;
		//to
		if($emailXarxa!='')
			$to=$emailXarxa;
		if($emailXarxa=='' && $emailAdmin!='')
			$to=$emailAdmin;
		if(!$error){
			$asunto=html_entity_decode($filaEnvio->asunto,ENT_COMPAT,'ISO-8859-1');
			$mail = new PHPMailer(); // create a new object
			$mail->IsSMTP(); // enable SMTP
			$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
			$mail->SMTPAuth = true; // authentication enabled
			$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
			$mail->Host = REMITENTE_SMTP;
			$mail->Port = 465;
			$mail->Username = REMITENTE_MAIL;
			$mail->Password = REMITENTE_PASS;
			$mail->SetFrom(REMITENTE_MAIL, REMITENTE_NOMBRE);
			$mail->ClearReplyTos();
			$mail->AddReplyTo($to,$nombreXarxa);
			$mail->Subject = $asunto;
			$mail->IsHTML(true);
			$mail->AddAddress($to,$nombreXarxa);
			//bcc SIN $emailProductorx
			if($emailProductorx=='' && $emailAdmin!=''){
				$aviso='<h4>'.$nombreProductorx.' '.AVISO_NO_MAIL_PRODUCTORX.'</h4><br />';
				$mail->AddBCC($emailAdmin);
			}
			if($emailProductorx=='' && $emailAdmin=='' && $emailXarxa!=''){
				$aviso='<h4>'.$nombreProductorx.' '.AVISO_NO_MAIL_PRODUCTORX.'</h4><br />';
				$mail->AddBCC($emailXarxa,$nombreXarxa);
			}
			//bcc CON $emailProductorx
			if($emailProductorx!='' && $emailAdmin!=''){
				$mail->AddBCC($emailProductorx,$nombreProductorx);
				$mail->AddBCC($emailAdmin);
			}
			if($emailProductorx!='' && $emailAdmin=='' && $emailXarxa!=''){
				$mail->AddBCC($emailProductorx,$nombreProductorx);
				$mail->AddBCC($emailXarxa,$nombreXarxa);
			}
			$mail->Body ='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
					<html>
					<head>
						<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
						<title>'.$asunto.'</title>
					</head>
					<body>'.$aviso.html_entity_decode($filaEnvio->cuerpo,ENT_COMPAT,'ISO-8859-1').'</body>
					</html>';
			//Mando el correo...
			if($mail->Send()){
				//Marco como enviado el registro en envio
				$sqlUpdateEnvio='UPDATE envio SET enviado="1" WHERE id_envio="'.$idEnvio.'"';
				mysql_query($sqlUpdateEnvio) or die();
			}
		}
	}
}
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
session_destroy();
?>