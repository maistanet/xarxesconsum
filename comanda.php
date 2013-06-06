<?php
/**
* Apartat comanda.
* Llista els productes per a categoria on podràs posar les quantitats que vulgues (per unitat o kg).
* Llista els productes entre dies de venda. Agafa dades de bd config
* L'unitat pot modificar la seua comanda una vegada feta.
* Envia un correu de la comanda de l'unitat de consum
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
header("content-type:text/html; charset=utf-8");

//Configuraciones + abrir sesión
include('includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'funcions/funcions.php');
include(RUTA_WEB_INCLUDE.'includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');

$sqlDiaHoraLimite="SELECT dia_limit,dia_venda,hora_limit FROM config";
$resultDiaHoraLimite=mysql_query($sqlDiaHoraLimite) or die(mysql_error());
$limite=mysql_fetch_object($resultDiaHoraLimite);

$data_actual = date("H:i:s");

if(date("l") == $limite->dia_limit && $limite->hora_limit <= $data_actual) //Para evitar el acceso a comanda.php incluso si se escribe en la caja de direcciones del navegador
	header('location: unitatsconsum.php');

//Devuelve el string $hoy.'!'.$ultimoDiaLimite.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
$limitesAutoBloqueo=explode('!',diasAutoBloqueoPanelPedido());
$hoyAutoBloqueo=$limitesAutoBloqueo[0];
$ultimoDiaLimiteAutoBloqueo=$limitesAutoBloqueo[1];
$proxDiaVentaAutoBloqueo=$limitesAutoBloqueo[2];
if($hoyAutoBloqueo>$ultimoDiaLimiteAutoBloqueo && $hoyAutoBloqueo<=$proxDiaVentaAutoBloqueo) //Panel de pedido autobloqueado
	header('location: unitatsconsum.php');

if(!panelPedidoActivo()) //Panel de pedido desactivado
	header('location: unitatsconsum.php');

include(RUTA_WEB_INCLUDE.'funcions/mail.php');

//Consultamos la configuracion del monedero
$limitComandaSaldo=configMonedero();

//Devuelve el string $data_abans.'!'.$data_limit.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
$limites=explode('!',diasLimiteVenta());
$data_abans=$limites[0];
$data_limit=$limites[1];
$proxDiaVenta=$limites[2];

//Para traducir el $limite->dia_venda del ingles
$NombreProxDiaVenta=trans_nombre_dia_venta();

if( isset($_POST['cancel']) ){
	if($limitComandaSaldo!=='off'){ //Monedero activado. Actualizamos saldo.
		$sqlSaldo='SELECT saldo FROM saldo WHERE id_uc="'.$_SESSION['s_idusuari'].'" AND id_moneda="1"'; //Saldo en euros
		$resultSaldo=mysql_query($sqlSaldo) or die(mysql_error());
		$saldoUc=mysql_fetch_object($resultSaldo);

		$sqlPrecioComanda='SELECT total FROM comanda WHERE (data BETWEEN "'.$data_abans.'" AND "'.$data_limit.'") && (id_unitat_c="'.$_SESSION['s_idusuari'].'")';
		$resultPrecioComanda=mysql_query($sqlPrecioComanda) or die(mysql_error());
		$precioComanda=mysql_fetch_object($resultPrecioComanda);

		$sqlSaldo='UPDATE saldo SET saldo="'.($saldoUc->saldo+$precioComanda->total).'" WHERE id_uc="'.$_SESSION['s_idusuari'].'" AND id_moneda="1"'; //Saldo en euros
		mysql_query($sqlSaldo) or die(mysql_error());
	}
	$consulta_del = "DELETE FROM comanda WHERE (data BETWEEN '".$data_abans."' AND '".$data_limit."') && (id_unitat_c = ".$_SESSION['s_idusuari'].")";
	$resultat_del = mysql_query($consulta_del) or die(mysql_error());
}elseif(isset($_POST['enviado']) && $_POST['enviado'] == 'enviado'){
	$comanda_val = true;
	//validem la comanda i la posem en bd
	$total = 0;
	$quan = 0;
	foreach($_POST as $campo => $valor){
		if($campo != 'enviar'  && $campo != 'id_unitat_c' && $campo != 'observacions' && $campo != 'saldo' && $campo != 'saldoDisplay'){
			if($valor != 0){
				$producte[] = $campo;
				$quantitat[] = $valor;
				//variable de verificacio si totes les quantitats estan a zero
				$quan += $valor;
				$sql_preu = "SELECT nom_producte,format,preu FROM producte WHERE actiu = 1 && id = ".$campo;
				$res_preu = mysql_query($sql_preu) or die(mysql_error());
				$temp = mysql_fetch_object($res_preu);
				$preu[] = $temp->preu;
				$total += $temp->preu * $valor;
			}
		}
	}

	//si es una modificación, cancelamos la antigua comanda si quantitat no es 0
	if($quan != '0'){
		$consulta_delete = "DELETE FROM comanda WHERE (data BETWEEN '".$data_abans."' AND '".$data_limit."') && (id_unitat_c = ".$_SESSION['s_idusuari'].")";
		$resultat_delete = mysql_query($consulta_delete) or die(mysql_error());
	}

	//posem la comanda correcta
	$consulta_ = "INSERT INTO comanda (data,id_unitat_c,t_producte,t_quantitat,t_pvp,total,observacions) VALUES (NOW(), '".$_SESSION['s_idusuari']."', '".serialize($producte)."', '".serialize($quantitat)."', '".serialize($preu)."', '".(round($total*100)/100)."', '".$_POST['observacions']."' )";
	$resultat_ = mysql_query($consulta_) or die(mysql_error());

	if($limitComandaSaldo!=='off' && isset($_POST['saldoDisplay']) && $_POST['saldoDisplay']!==''){ //Monedero activado. Actualizamos saldo.
		$sqlSaldo='SELECT saldo FROM saldo WHERE id_uc="'.$_SESSION['s_idusuari'].'" AND id_moneda="1"'; //Saldo en euros
		$resultSaldo=mysql_query($sqlSaldo) or die(mysql_error());
		if(mysql_num_rows($resultSaldo)>0){
			$sqlSaldo='UPDATE saldo SET saldo="'.$_POST['saldoDisplay'].'" WHERE id_uc="'.$_SESSION['s_idusuari'].'" AND id_moneda="1"'; //Saldo en euros
			mysql_query($sqlSaldo) or die(mysql_error());
		}else{
			$sqlSaldo='INSERT INTO saldo (id_uc,id_moneda,saldo) VALUES ("'.$_SESSION['s_idusuari'].'","1","'.$_POST['saldoDisplay'].'")'; //Saldo en euros
			mysql_query($sqlSaldo) or die(mysql_error());
		}
	}
}

//creación del la contingut de la comanda si está hecha
$consulta = "SELECT * FROM comanda WHERE (data BETWEEN '".$data_abans."' AND '".$data_limit."') && (id_unitat_c = ".$_SESSION['s_idusuari'].")";
$resultat = mysql_query($consulta) or die(mysql_error());

if(mysql_num_rows($resultat)>0 && !isset($_POST['modificar'])){
	$saldoUsuarix='';
	if($limitComandaSaldo!=='off'){ //Monedero activado
		$sqlSaldoUser='SELECT saldo FROM saldo WHERE id_uc="'.$_SESSION['s_idusuari'].'" AND id_moneda="1"'; //Saldo en euros
		$resultSaldoUser=mysql_query($sqlSaldoUser) or die(mysql_error());
		$filaSaldoUser=mysql_fetch_object($resultSaldoUser);
		$saldoUsuarix=SALDO.': '.$filaSaldoUser->saldo;
	}
	$comanda = true;
	while( $unitat = mysql_fetch_object($resultat) ){
		$t_producte = unserialize($unitat->t_producte);
		$t_quantitat = unserialize($unitat->t_quantitat);
		$t_pvp = unserialize($unitat->t_pvp);

		$text .= '<p>'.PEDIDO_HECHO_EL_INI.' '.trans_data($unitat->data).PEDIDO_HECHO_EL_FIN.' '.$NombreProxDiaVenta.' '.trans_data($proxDiaVenta).'</p>';
		$text .='<table align="center" cellpadding="3" cellspacing="3">
				<tr>
					<td align="left">'.CANTIDAD.'</td>
					<td>&nbsp;</td>
					<td align="left">'.PRODUCTO.'</td>
					<td align="left">'.PRODUCTORX.'</td>
					<td align="right">'.PRECIO.'</td>
				</tr>';
		$color1=$_SESSION['color1'];
		$color2=$_SESSION['color2'];
		$color=$color2;
		for( $i=0; $i<count($t_producte); $i++ ){
			($color==$color1) ? $color=$color2 : $color=$color1;
			$con_format = "SELECT format FROM producte WHERE id = ".$t_producte[$i];
			$res_format = mysql_query($con_format) or die(mysql_error());
			$format = mysql_fetch_object($res_format);
			if($format->format == 'unitat') $format = UNIDADES;
			else $format = 'Kg';
			$text .= '<tr bgcolor="'.$color.'"><td>'.$t_quantitat[$i].'&nbsp;'.$format.'</td><td>&nbsp;</td>';
			$con_prod = "SELECT nom_productor,nom_producte FROM productors AS t1, producte AS t2 WHERE t1.id = t2.id_productor AND t2.id = ".$t_producte[$i];
			$res_prod = mysql_query($con_prod) or die(mysql_error());
			$nom_prod = mysql_fetch_array($res_prod);
			$text .= '<td>'.htmlentities($nom_prod[1], ENT_QUOTES, "UTF-8").'</td>
						<td>('.htmlentities(substr($nom_prod[0], 0, 2)).')</td>
						<td align="right">'.(round(($t_pvp[$i]*$t_quantitat[$i])*100)/100).'&euro;</td></tr>';
		}
		$text.='<tr><td colspan="5">&nbsp;</td></tr>
					<tr>
						<td colspan="5" align="right">'.TOTAL.': '.$unitat->total.'&euro;</td>
					</tr>';
		if($limitComandaSaldo!=='off'){ //Monedero activado
			$text.='<tr>
						<td colspan="5" align="right">'.$saldoUsuarix.'&euro;</td>
					</tr>';
		}
		$text.='</table>';
		$id_comanda = $unitat->id;
		if($unitat->observacions != ""){$text .= '<p>'.OBSERVACIONES.': </p>'.$unitat->observacions;}
	}
	$text .= '<p align="center">';
}else{
	$comanda = false;
}

if(isset($comanda_val)){
	//enviem un email de confirmació
	$id = $_SESSION['s_idusuari'];
	$consulta_correu = "SELECT correu FROM unitat_c WHERE id = $id";
	$resultat_correu = mysql_query($consulta_correu) or die(mysql_error());
	$dest = mysql_fetch_object($resultat_correu);
	$destinatario = $dest->correu;
	$subject = ASUNTO_PEDIDO_DEL.' '.date('d/m/y');
	//cap del correu diferent de cada xarxa
	$con_text = "SELECT text FROM correu";
	$res_text = mysql_query($con_text) or die(mysql_error());
	$row = mysql_fetch_array($res_text);

	$contingut_correu =	'<html><head></head><body>
							<table align="center">'.$row[0].'
								<tr><td>'.$text.'</td></tr>
							</table></body></html>';
	if($destinatario != "")
		$error = smtpmailer($destinatario, $subject, $contingut_correu);
	else
		$errormail = htmlspecialchars_decode(htmlentities(MENSAJE_ERROR_NO_MAIL_UC, ENT_NOQUOTES, 'UTF-8'));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="estils/template.css" />
	<link rel="stylesheet" type="text/css" href="estils/validate.css" />
	<link rel="stylesheet" type="text/css" href="estils/jquery-ui-1.8.13.custom.css" />

	<script type="text/javascript" src="funcions/jquery.min.js"></script>
	<script type="text/javascript" src="funcions/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="funcions/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="funcions/ckeditor/adapters/jquery.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.js'; ?>"></script>
	<script type="text/javascript">
		function restar(id){ //Monedero desactivado
			var q = parseFloat($('#'+id).val());
			q = Math.round(q*100)/100;
			var format = $('#'+id).attr('class');
			if(q > 0) {
				if(format == 'unitat') q--;
				if(format == 'Kg') q = q-0.5;
				if(format == 'gr') q = q-0.05;
				q = Math.round(q*100)/100;
				$('#'+id).val( q );
			}
		}
		function sumar(id){ //Monedero desactivado
			var q = parseFloat($('#'+id).val());
			q = Math.round(q*100)/100;
			var format = $('#'+id).attr('class');
			if(format == 'unitat') q++;
			if(format == 'Kg') q = q+0.5;
			if(format == 'gr') q = q+0.05;
			q = Math.round(q*100)/100;
			$('#'+id).val( q );
		}
		function restarConSinLimite(id,precio){ //Monedero activado, con o sin limite
			var q = parseFloat($('#'+id).val());
			q = Math.round(q*100)/100;
			var saldo=parseFloat(document.getElementById('saldo').value);
			var cadencia=0;
			var format = $('#'+id).attr('class');
			if(q > 0) {
				if(format=='unitat') cadencia=1;
				if(format=='Kg') cadencia=0.5;
				if(format=='gr') cadencia=0.05;
				q=q-cadencia;
				q=Math.round(q*100)/100;
				$('#'+id).val( q );
				saldo=saldo+(precio*cadencia);
				document.getElementById('saldo').value=saldo;
				document.getElementById('saldoDisplay').value=Math.round(saldo*100)/100;
				if(document.getElementById('saldoDisplay').value>=0){
					document.getElementById('divSaldo').style.color="#4B4137";
					document.getElementById('celdaSaldo').style.color='#EB8F00';
					document.getElementById('saldoDisplay').style.color='#4B4137';
				}
			}
		}
		function sumarConLimite(id,precio){ //Monedero activado con limite 0
			var q = parseFloat($('#'+id).val());
			q = Math.round(q*100)/100;
			var saldo=parseFloat(document.getElementById('saldo').value);
			var saldoDisplay=parseFloat(document.getElementById('saldoDisplay').value);
			saldoDisplay = Math.round(saldoDisplay*100)/100;
			var cadencia=0;
			var format = $('#'+id).attr('class');
			if(format=='unitat') cadencia=1;
			if(format=='Kg') cadencia=0.5;
			if(format=='gr') cadencia=0.05;
			if(saldoDisplay>=(precio*cadencia)){
				q=q+cadencia;
				q=Math.round(q*100)/100;
				$('#'+id).val( q );
				saldo=saldo-(precio*cadencia);
				document.getElementById('saldo').value=saldo;
				document.getElementById('saldoDisplay').value=Math.round(saldo*100)/100;
			}else{
				alert(SALDO_INSUFICIENTE);
			}
		}
		function sumarSinLimite(id,precio){ //Monedero activado sin limite (admite saldo negativo)
			var q = parseFloat($('#'+id).val());
			q = Math.round(q*100)/100;
			var saldo=parseFloat(document.getElementById('saldo').value);
			var cadencia=0;
			var format = $('#'+id).attr('class');
			if(format=='unitat') cadencia=1;
			if(format=='Kg') cadencia=0.5;
			if(format=='gr') cadencia=0.05;
			q=q+cadencia;
			q=Math.round(q*100)/100;
			$('#'+id).val( q );
			saldo=saldo-(precio*cadencia);
			document.getElementById('saldo').value=saldo;
			document.getElementById('saldoDisplay').value=Math.round(saldo*100)/100;
			if(document.getElementById('saldoDisplay').value<0){
				document.getElementById('divSaldo').style.color="#FF0000";
				document.getElementById('celdaSaldo').style.color='#FF0000';
				document.getElementById('saldoDisplay').style.color='#FF0000';
			}
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#llistar").accordion({ autoHeight: false });
			$( 'textarea' ).ckeditor(function() { /* callback code */ }, {
				toolbar : 'Basic',
				forcePasteAsPlainText : true,
				scayt_autoStartup : false,
				uiColor : '#9AB8F3'
			});
		});
		$(function(){
			// jQuery UI Dialog
			$('#dialog').dialog({
				autoOpen: false,
				width: 600,
				modal: true,
				resizable: false,
				buttons: {
					<?php echo ACEPTAR; ?> : function() {
						document.comanda.submit();
					},
					<?php echo CANCELAR_PEDIDO; ?> : function() {
						$(this).dialog("close");
					}
				}
			});
			//recapitulivo comanda
			$('form#comanda').submit(function(){
				//calcula total
				var total = 0;
				var add='';
				var productes = 0;
				add += '<table>';
				$("input").each(function() {
					if(  $(this).val() != "0" && $(this).is(":not('[id^=enviar]')") && $(this).val() != "enviado" && $(this).is(":not('[id^=saldo]')") && $(this).is(":not('[id^=saldoDisplay]')") && $(this).is(":not('[id^=idioma]')")){
						add+='<tr><td>' + $(this).parent().prev().prev().prev().prev().prev().prev().text() + '</td><td>' + $(this).val() + $(this).parent().text() + '</td><td>&nbsp;x&nbsp;</td><td>' + $(this).parent().prev().prev().text() + '&nbsp;&euro;</td></tr>';
						total+=Number( $(this).val() ) * parseFloat( $(this).parent().prev().prev().text() );
						productes++;
					}
				});
				total=parseFloat(total);
				total=Math.round(total*100)/100;
				add += '<tr><td colspan="4">&nbsp;</td></tr><tr><td colspan="3" align="right">' + TOTAL + ':</td><td>' + total + ' &euro;</td></tr></table>';
				$("#conf_comanda").html( add );
				if (productes != "0"){
					$('#dialog').dialog('open');
					return false;
				}else{
					alert(PEDIDO_VACIO);
					return false;
				}
			});
			$('.del_comanda').click(function(){
				var answer = confirm(DEL_COMANDA);
				return answer; // answer is a boolean
			});
		});
		function ltrim(cadena){
			while(1){
				if(cadena.substring(0, 1)!=" "){
					break;
				}
			cadena=cadena.substring(1, cadena.length);
			}
			return cadena;
		}
		function rtrim(cadena){
			while(1){
				if(cadena.substring(cadena.length - 1, cadena.length)!=" "){
					break;
				}
			cadena=cadena.substring(0, cadena.length - 1);
			}
			return cadena;
		}
		function trim(cadena){
			var tmpstr=ltrim(cadena);
			return rtrim(tmpstr);
		}
	</script>
</head>
<body>
	<div id="cap" align="center"><img align="middle" src="<?php echo $_SESSION['imatge_cap'];?>" alt=""/></div>
	<div id="separacio">&nbsp;</div>
	<div id="menu" align="center">
		<?php include(RUTA_WEB_INCLUDE.'includes/menu_u.php');?>
	</div>
	<div id="contingut">
		<div id="floatRight">
			<?php echo muestraSelectorIdioma(); ?>
		</div>
	</div>
	<div id="contingut">
		<br />
<?php
		if(isset($errormail)){echo $errormail;}
		//impr la comanda si ja esta feta
		if($comanda){
			$text = '<form action="comanda.php" id="modificacion" name="modificacion" method="post">'.$text;
			$text .= '<input type="submit" align="center" name="modificar" class="submit" value="'.MODIFICAR.'" />
						<input type="submit" align="center" name="cancel" class="del_comanda" value="'.CANCELAR.'" /></p>
					</form>';
			echo $text;
		}

		//Listamos los diferentes tipos de productos
		$i = 0;
		$nom_productors = array();
		$consulta_productors = "SELECT nom_productor,id FROM productors ORDER BY id ASC";
		$resulta_productors = mysql_query($consulta_productors) or die(mysql_error());
		while ($linia_productors = mysql_fetch_object($resulta_productors)) {
			$nom_productors[$linia_productors->id] = $linia_productors->nom_productor;
		}
?>
		<div id="form_comanda">
<?php
			// si la comanda ya está hecha, escondemos el formulario de comanda
			if($comanda){
?>
				<script type="text/javascript">
					form_comanda.style.display = "none";
				</script>
<?php
			}
?>
			<form action="comanda.php" id="comanda" name="comanda" method="post">
<?php
				if($limitComandaSaldo!=='off'){ //Monedero activado
					//Consultamos el saldo
					$saldo=0;
					$colorCeldaSaldo='#EB8F00';
					$colorDivSaldo='#4B4137';
					$colorSaldoDisplay='#4B4137';
					$sqlSaldo='SELECT saldo FROM saldo WHERE id_uc="'.$_SESSION['s_idusuari'].'" AND id_moneda="1"'; //Saldo en euros
					$resultSaldo=mysql_query($sqlSaldo) or die(mysql_error());
					if(mysql_num_rows($resultSaldo)>0){
						$lineaSaldo=mysql_fetch_object($resultSaldo);
						$saldo=$lineaSaldo->saldo;
						if($saldo<0){
							$colorCeldaSaldo='#FF0000';
							$colorDivSaldo='#FF0000';
							$colorSaldoDisplay='#FF0000';
						}
					}
?>
					<div>
						<table width="30%">
							<tr>
								<td align="center">
									<div id="celdaSaldo" style="border: 4px solid; color: <?php echo $colorCeldaSaldo; ?>;">
										<div id="divSaldo" style="margin-bottom: 20px; font-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif; color: <?php echo $colorDivSaldo; ?>;">
											<h3><?php echo SALDO; ?>: <input type="text" id="saldoDisplay" name="saldoDisplay" style="width: 30%; text-align: right; border-width: 0px; font-size: medium; font-weight: bold; color: <?php echo $colorSaldoDisplay; ?>;" readonly="readonly" value="<?php echo $saldo;?>" />&nbsp;&euro;</h3>
											<input type="hidden" id="saldo" name="saldo" value="<?php echo $saldo;?>" />
										</div>
									</div>
								</td>
							</tr>
						</table>
					</div>
<?php
				}
?>
				<div style="margin: 15px 0px 15px 0px;">
					<h5><?php echo VER_DESCRIPCION_PRODUCTO; ?></h5>
				</div>
				<div id="llistar">
<?php
					//Consultamos los datos de la comanda para rellenar las cantidades con lo que había
					$sqlComanda="SELECT * FROM comanda WHERE (data BETWEEN '".$data_abans."' AND '".$data_limit."') && (id_unitat_c = ".$_SESSION['s_idusuari'].")";
					$resultComanda=mysql_query($sqlComanda) or die(mysql_error());
					$consulta_categoria="SELECT * FROM categoria ORDER BY id ASC";
					$resulta_categoria=mysql_query($consulta_categoria) or die(mysql_error());
					while($linia_categoria=mysql_fetch_object($resulta_categoria)){
						$id_categoria=$linia_categoria->id;
						//Mostramos sólo las categorías que tienen algún producto activo
						$sqlCategContieneProds='SELECT id FROM producte WHERE actiu=1 && id_categoria="'.$id_categoria.'"';
						$resultCategContieneProds=mysql_query($sqlCategContieneProds) or die(mysql_error());
						if(mysql_num_rows($resultCategContieneProds)>0){
?>
							<h3><a href="#" title="<?php echo $linia_categoria->nom;?>"><?php echo $linia_categoria->nom;?></a></h3>
							<div>
								<table cellpadding="3" cellspacing="3">
									<tr>
										<td><?php echo PRODUCTO; ?></td>
										<td>&nbsp;</td>
										<td><?php echo PRODUCTORX; ?></td>
										<td>&nbsp;</td>
										<td><?php echo PRECIO; ?> (&euro;)</td>
										<td>&nbsp;</td>
										<td><?php echo CANTIDAD; ?></td>
										<td>&nbsp;</td>
										<td><?php echo COMENTARIO; ?></td>
									</tr>
<?php
									//buscamos dentro la ultima comanda
									if(mysql_num_rows($resultComanda)>0){
										while( $unitat = mysql_fetch_object($resultComanda) ){
											$t_producte = unserialize($unitat->t_producte);
											$t_quantitat = unserialize($unitat->t_quantitat);
											$t_pvp = unserialize($unitat->t_pvp);
											$observacions = $unitat->observacions;
										}
									}
									//rellenamos las columnas con los productos activos
									$con_prod="SELECT id, nom_producte, descripcio, format, preu, comentari, id_productor FROM producte WHERE actiu=1 && id_categoria=".$id_categoria." ORDER BY nom_producte";
									$res_prod=mysql_query($con_prod) or die(mysql_error());
									while($lin_prod=mysql_fetch_object($res_prod)){
?>
										<tr>
											<td><a title="<?php echo strip_tags(html_entity_decode($lin_prod->descripcio, ENT_QUOTES,"UTF-8"));?>"><?php echo $lin_prod->nom_producte;?></a></td>
											<td>&nbsp;</td>
											<td><?php echo $nom_productors[$lin_prod->id_productor];?><br /></td>
											<td>&nbsp;</td>
											<td><?php echo $lin_prod->preu;?></td>
											<td>&nbsp;</td>
<?php
											$format = $lin_prod->format;
											if ($format == 'unitat') $format = UNIDADES;
											else $format = "Kg";
											if($limitComandaSaldo=='off'){ //Monedero desactivado
												$sumar='sumar('.$lin_prod->id.');return false;';
												$restar='restar('.$lin_prod->id.');return false;';
											}elseif($limitComandaSaldo=='si'){ //Monedero activado con limite 0
												$sumar='sumarConLimite('.$lin_prod->id.','.$lin_prod->preu.');return false;';
												$restar='restarConSinLimite('.$lin_prod->id.','.$lin_prod->preu.');return false;';
											}elseif($limitComandaSaldo=='no'){ //Monedero activado sin limite (admite saldo negativo)
												$sumar='sumarSinLimite('.$lin_prod->id.','.$lin_prod->preu.');return false;';
												$restar='restarConSinLimite('.$lin_prod->id.','.$lin_prod->preu.');return false;';
											}
?>
											<td>
												<a href="#" onclick="<?php echo $restar; ?>" ><img src="estils/menos.png" width="11" height="11" alt="<?php echo MENOS; ?>"/></a>
<?php
												//Si es una modificación, ponemos las quantidad de los productos ya pedido
												$quantitat = 0;
												if(isset($_POST['modificar'])){
													for( $i=0; $i<count($t_producte); $i++ ){
														$con_nom_prod = "SELECT nom_producte FROM productors AS t1, producte AS t2 WHERE t1.id = t2.id_productor AND t2.id = ".$t_producte[$i];
														$res_nom_prod = mysql_query($con_nom_prod) or die(mysql_error());
														$nom_prod = mysql_fetch_array($res_nom_prod);
														if($lin_prod->nom_producte == $nom_prod[0]){
															$quantitat = $t_quantitat[$i];
														}
													}
												}
?>
												<input type="text" name="<?php echo $lin_prod->id;?>" id="<?php echo $lin_prod->id;?>" class="<?php echo $lin_prod->format;?>" size="5" readonly="readonly" value="<?php echo $quantitat;?>"/>
												<a href="#" onclick="<?php echo $sumar; ?>" ><img src="estils/mas.png" width="11" height="11" alt="<?php echo MAS; ?>"/></a><?php echo " ".$format;?>
											</td>
											<td>&nbsp;</td>
											<td><?php echo $lin_prod->comentari;?></td>
										</tr>
<?php
									}
?>
								</table>
							</div>
<?php
						}
					}
?>
				</div>
				<br />
				<label><?php echo OBSERVACIONES; ?></label>
				<textarea cols="40%" rows="5" name="observacions">
<?php
					//si es una modificación, ponemos las observaciones de la comanda ya hecha
					if(isset($_POST['modificar'])) echo $observacions;
?>
				</textarea><br />
				<input type="hidden" name="enviado" value="enviado" />
				<p align="center"><input type="submit" align="middle" id="enviar" name="enviar" class="submit" value="<?php echo HACER_PEDIDO; ?>" /></p>
			</form>
		</div>
		<div id="dialog" title="<?php echo PREGUNTA_ES_TU_PEDIDO; ?>">
			<div id="conf_comanda" style="font-size:14px;"></div>
			<h2><?php echo ACEPTAR_CANCELAR_PEDIDO; ?></h2>
		</div>
	</div>
</body>
</html>
<?php
// alliberem el resultat de la consulta
mysql_free_result($resultat);
// tanquem la conexió a la BD
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>