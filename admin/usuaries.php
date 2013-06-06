<?php
/**
* Apartat unitats de consum.
* Gestió de les unitats: baixa, alta, canvi de contrassenya.
* Llista totes les unitats de consum
* Màxim d'unitats de consum: 70.
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
include('../includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'funcions/funcions.php');
include(RUTA_WEB_INCLUDE.'admin/includes/sessio.php');
require(RUTA_WEB_INCLUDE.'includes/bd/obri.php');
require(RUTA_WEB_INCLUDE.'funcions/mail.php');

// segons l'apartat
$apartat=UNIDADES_DE_CONSUMO;
$fet = "";

//Consultamos la configuracion del monedero
$limitComandaSaldo=configMonedero();

//Devuelve el string $data_abans.'!'.$data_limit.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
$limites=explode('!',diasLimiteVenta());
$data_abans=$limites[0];
$data_limit=$limites[1];

//per a mod, add o del
$mode = $_GET['mode'];
$id = $_GET['id'];
// Sols per a add i mod (formulari)
$validar = $_POST['accept'];

if($mode == 'del'){
	//Enviar correo baja
	$consulta='SELECT text FROM correu';
	$resultat=mysql_query($consulta) or die(mysql_error());
	$row=mysql_fetch_object($resultat);
	$con_Uc='SELECT nom,correu FROM unitat_c WHERE id="'.$id.'"';
	$res_Uc=mysql_query($con_Uc) or die(mysql_error());
	$row_Uc=mysql_fetch_object($res_Uc);
	$body='<html><head></head><body>'.$row->text.'<p>'.CORREO_BAJA_BODY_1.utf8_decode($row_Uc->nom).',</p>
	<p>'.CORREO_BAJA_BODY_2.utf8_decode($_SESSION['nom_xarxa']).'.</p>
	<p>'.CORREO_BAJA_BODY_3.'</p>
	<p>'.CORREO_BAJA_BODY_4.'</p>
	<p>'.utf8_decode($_SESSION['nom_xarxa']).'</p></body></html>';
	$to=$row_Uc->correu;
	$subject=CORREO_BAJA_ASUNTO.utf8_decode($_SESSION['nom_xarxa']);
	smtpmailer($to,$subject,$body);

	//Eliminamos
	$sqlDelPedidoSemanaActual='DELETE FROM comanda WHERE id_unitat_c="'.$id.'" AND data BETWEEN "'.$data_abans.'" AND "'.$data_limit.'"';
	mysql_query($sqlDelPedidoSemanaActual) or die(mysql_error());
	$del='DELETE FROM unitat_c WHERE id="'.$id.'"';
	mysql_query($del) or die(mysql_error());
	$sqlDelUc='DELETE FROM saldo WHERE id_uc="'.$id.'"';
	mysql_query($sqlDelUc) or die(mysql_error());
	$apartat.=' -> '.ELIMINAR;
	$fet=CORRECTO_ELIMINAR_U_CONSUMO;
}elseif( $validar == ACEPTAR ){
	$nom = $_POST['nom'];

	if($_POST['mode'] == 'mod')$mode = $_POST['mode'];

	switch($mode){
		case "mod":
			$errorMod=false;
			$apartat.=' -> '.MODIFICAR;
			if(isset($_POST['correu']) && $_POST['correu']!='' && isset($_POST['pass']) && $_POST['pass']!='' && isset($_POST['user']) && $_POST['user']!='' && isset($_POST['nom']) && $_POST['nom']!=''){
				$passMod=decode($_POST['pass']);
				$correoMod=decode($_POST['correu']);
				if($_POST['caixa']==0){
					$sqlPass='SELECT pass FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa>0 AND pass="'.$passMod.'"';
					$resultPass=mysql_query($sqlPass);
					if(!mysql_fetch_object($resultPass)){
						$fet=CORRECTO_MODIF_U_CONSUMO;
					}else{
						$fet=ERROR_EXISTE_PAR_EMAIL_CONTRASENYA;
						$errorMod=true;
					}
				}else{
					$sqlEmail='SELECT correu FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa>0';
					$resultEmail=mysql_query($sqlEmail);
					if(!mysql_fetch_object($resultEmail)){
						$sqlPass='SELECT pass FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa=0 AND pass="'.$passMod.'"';
						$resultPass=mysql_query($sqlPass);
						if(!mysql_fetch_object($resultPass)){
							$fet=CORRECTO_MODIF_U_CONSUMO;
						}else{
							$fet=ERROR_EXISTE_PAR_EMAIL_CONTRASENYA;
							$errorMod=true;
						}
					}else{
						$sqlEmail='SELECT correu FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa="'.$_POST['caixa'].'"';
						$resultEmail=mysql_query($sqlEmail);
						if(mysql_fetch_object($resultEmail)){
							$sqlPass='SELECT pass FROM unitat_c WHERE correu="'.$correoMod.'" AND n_caixa=0 AND pass="'.$passMod.'"';
							$resultPass=mysql_query($sqlPass);
							if(!mysql_fetch_object($resultPass)){
								$fet=CORRECTO_MODIF_U_CONSUMO;
							}else{
								$fet=ERROR_EXISTE_PAR_EMAIL_CONTRASENYA;
								$errorMod=true;
							}
						}else{
							$fet=ERROR_EXISTE_EMAIL.': '.$correoMod;
							$errorMod=true;
						}
					}
				}
			}else{
				$fet=ERROR_DATOS_INTRODUCIDOS;
				$errorMod=true;
			}
			if(!$errorMod){
				$user = $_POST['user'];
				$mod = "UPDATE unitat_c SET user='".$user."', pass='".$passMod."', nom='".$nom."', correu='".$correoMod."' WHERE id = $id";
				$resultat = mysql_query($mod) or die(mysql_error());
			}
		break;
		case "add":
			$apartat.=' -> '.ANYADIR_U_CONSUMO;
			if(isset($_POST['correu']) && $_POST['correu']!='' && isset($_POST['nom']) && $_POST['nom']!=''){
				$correoAdd=decode($_POST['correu']);
				$sqlEmail='SELECT correu FROM unitat_c WHERE correu="'.$correoAdd.'" AND n_caixa>0';
				$resultEmail=mysql_query($sqlEmail);
				if(!mysql_fetch_object($resultEmail)){
					$idUnitat='';
					$sqlIdUc='SELECT max(id) FROM unitat_c';
					$resultIdUc=mysql_query($sqlIdUc);
					$idUnitat=mysql_fetch_row($resultIdUc);
					$id_uc=$idUnitat[0]+1;
					$passAdd=$nom.$id_uc;

					$sqlPass='SELECT pass FROM unitat_c WHERE correu="'.$correoAdd.'" AND n_caixa=0 AND pass="'.$passAdd.'"';
					$resultPass=mysql_query($sqlPass);
					if(mysql_fetch_object($resultPass))
						$passAdd.='1';
					$add='INSERT INTO unitat_c (id, user, pass, nom, correu, n_caixa, fecha_alta) VALUES ("'.$id_uc.'","'.$nom.'","'.$passAdd.'","'.$nom.'", "'.$correoAdd.'", "'.$id_uc.'",CURDATE())';
					$resultat = mysql_query($add) or die(mysql_error());
		
					$sqlSaldo='INSERT INTO saldo (id_uc,id_moneda,saldo) VALUES ("'.$id_uc.'","1","0")'; //Saldo en euros
					mysql_query($sqlSaldo) or die(mysql_error());
		
					//envia correu d'alta
					$consulta='SELECT text FROM correu';
					$resultat=mysql_query($consulta) or die(mysql_error());
					$row=mysql_fetch_object($resultat);
					$body='<html><head></head><body>'.$row->text.'<p>'.CORREO_ALTA_BODY_1.$_POST['nom'].',</p>
					<p>'.CORREO_ALTA_BODY_2.utf8_decode($_SESSION['nom_xarxa']).'.</p>
					<p>'.CORREO_ALTA_BODY_3.'<a href="'.RUTA_WEB.'">'.CORREO_ALTA_BODY_4.'</a>'.CORREO_ALTA_BODY_5.'</p>
					<p>'.CORREO_ALTA_BODY_6.$correoAdd.'</p>
					<p>'.CORREO_ALTA_BODY_7.$passAdd.'</p>
					<p>'.CORREO_ALTA_BODY_8.'</p><p>'.utf8_decode($_SESSION['nom_xarxa']).'</p></body></html>';
					$to = $correoAdd;
					$subject=CORREO_ALTA_ASUNTO.utf8_decode($_SESSION['nom_xarxa']);
					smtpmailer($to,$subject,$body);
					$fet=CORRECTO_ANYADIR_U_CONSUMO;
				}else{
					$fet=ERROR_EXISTE_EMAIL.': '.$correoAdd;
				}
			}else{
				$fet=ERROR_DATOS_INTRODUCIDOS;
			}
		break;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="../estils/template.css" />
	<link rel="stylesheet" type="text/css" href="../estils/validate.css" />
	<script type="text/javascript" src="../funcions/jquery.min.js"></script>
	<script type="text/javascript" src="../funcions/validate/jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.js'; ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#formulari").validate();
			$('.saldo').keydown(function(e){
				if(e.keyCode == 13 || e.keyCode == 9){
					var id_uc = $(this).attr("name");
					var saldo = $(this).val();
					$.post("includes/ajax/update_saldo.php",{saldo:saldo, id_uc:id_uc});
					document.getElementById('Separacion').style.display='none';
					document.getElementById('mensajeText').value=SALDO_ACTUALIZADO;
					document.getElementById('Mensaje').style.display='block';
				}
			});
			$('.delete').click(function(){
				var answer=confirm(BAJA_UC);
				return answer; // answer is a boolean
			});
			$('.deleteConPedido').click(function(){
				var answer=confirm(BAJA_UC_Y_PEDIDO);
				return answer; // answer is a boolean
			});
		});
		function ocultarMensaje(){
			document.getElementById('Mensaje').style.display='none';
			document.getElementById('Separacion').style.display='block';
		}
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
		function ComprobarNumerico(elemento){
			if(isNaN(document.getElementById(elemento).value)){
				document.getElementById(elemento).value="";
			}else{
				document.getElementById(elemento).value=trim(document.getElementById(elemento).value);
			}
		}
		function redondear(elemento){
			document.getElementById(elemento).value=Math.round(document.getElementById(elemento).value*100)/100;
		}
	</script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'funcions/validate/messages_'.$_SESSION['lang'].'.js'; ?>"></script>
</head>
<body>
	<div id="cap" align="center"><img align="middle" src="<?php echo '../'.htmlentities($_SESSION['imatge_cap'], ENT_QUOTES, 'UTF-8');?>" alt=""/></div>
	<div id="separacio">&nbsp;</div>
	<div id="menu" align="center">
		<?php include(RUTA_WEB_INCLUDE.'admin/includes/menu.php');?>
	</div>
	<div id="contingut">
		<div id="floatRight">
			<?php echo muestraSelectorIdioma(); ?>
		</div>
	</div>
	<div id="contingut">
		<br />
		<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td width="30%" align="left"><h4><?php echo $apartat; ?></h4></td>
				<td width="40%" align="center">
					<div id="Mensaje" style="display: none;">
						<p><?php echo correcto('<input type="text" id="mensajeText" name="mensajeText" style="text-align: center; border-width: 0px; font-size: medium; font-weight: bold; color: #000000; background-color: #CCFFCD;" readonly="readonly" value="" />'); ?></p>
					</div>
					<div id="Separacion" style="display: block;">
						<p><?php echo separacion(); ?></p>
					</div>
				</td>
				<td width="30%">&nbsp;</td>
			</tr>
		</table>
		<p><?php echo $fet; ?></p>
		<form method="post" action="usuaries.php?mode=add" enctype="multipart/form-data" id="formulari">
			<fieldset>
				<legend><?php echo DAR_DE_ALTA; ?></legend>
				<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center">
					<tr>
						<td width="20%" align="right"><?php echo NOMBRE_USUARIO; ?></td>
						<td width="30%" align="left"><input style="width: 95%;" name="nom" id="nom" class="required" type="text" value="" size="25"/></td>
						<td width="5%" align="right"><?php echo CORREO; ?></td>
						<td width="30%" align="left"><input style="width: 95%;" type="text" id="correu" class="required email" name="correu" value="" size="40" /></td>
						<td width="15%" align="center"><input type="submit" name="accept" value="<?php echo ACEPTAR; ?>" /></td>
					</tr>
				</table>
			</fieldset>
		</form>
		<br /><br />
		<table width="85%" border="0" align="center" cellspacing="0" cellpadding="4px">
			<tr>
<?php
				if($limitComandaSaldo=='off'){ //Monedero desactivado
?>
					<td width="20%" align="left"><?php echo NUM_CAJA; ?></td>
					<td width="30%" align="left"><?php echo NOMBRE; ?></td>
					<td width="30%" align="left"><?php echo CORREO; ?></td>
					<td width="20%" align="center"><?php echo OPCIONES; ?></td>
<?php
				}else{ //Monedero activado
?>
					<td width="15%" align="left"><?php echo NUM_CAJA; ?></td>
					<td width="30%" align="left"><?php echo NOMBRE; ?></td>
					<td width="30%" align="left"><?php echo CORREO; ?></td>
					<td width="10%" align="left"><?php echo SALDO; ?></td>
					<td width="15%" align="center"><?php echo OPCIONES; ?></td>
<?php
				}
?>
			</tr>
<?php
			$con = "SELECT id,nom,n_caixa,correu FROM unitat_c ORDER BY n_caixa ASC";
			$res = mysql_query($con) or die(mysql_error());
			$color1=$_SESSION['color1'];
			$color2=$_SESSION['color2'];
			$color=$color2;
			while($unitat_c = mysql_fetch_object($res)){
				($color==$color1) ? $color=$color2 : $color=$color1;
				$classDelete='delete';
				$sqlPedidoSemanaActual='SELECT id_unitat_c FROM comanda WHERE id_unitat_c="'.$unitat_c->id.'" AND data BETWEEN "'.$data_abans.'" AND "'.$data_limit.'"';
				$resultPedidoSemanaActual=mysql_query($sqlPedidoSemanaActual) or die(mysql_error());
				if(mysql_fetch_object($resultPedidoSemanaActual))
					$classDelete='deleteConPedido';
				//Recortamos el correo si es largo para que no se descuadre la tabla
				$longMaxCorreo=25;
				$correoUser=$unitat_c->correu;
				if(strlen($unitat_c->correu)>$longMaxCorreo)
					$correoUser=substr($unitat_c->correu,0,$longMaxCorreo)."...";
?>
				<tr bgcolor="<?php echo $color; ?>">
					<td><?php echo $unitat_c->n_caixa; ?></td>
					<td><?php echo $unitat_c->nom; ?></td>
					<td><?php echo $correoUser; ?></td>
<?php
					if($limitComandaSaldo!=='off'){ //Monedero activado
						//Consultamos el saldo
						$saldo=0;
						$sqlSaldo='SELECT saldo FROM saldo WHERE id_uc="'.$unitat_c->id.'" AND id_moneda="1"'; //Saldo en euros
						$resultSaldo=mysql_query($sqlSaldo) or die(mysql_error());
						if(mysql_num_rows($resultSaldo)>0){
							$lineaSaldo=mysql_fetch_object($resultSaldo);
							$saldo=$lineaSaldo->saldo;
						}
						if($unitat_c->n_caixa != 0){
?>
							<td><a title="<?php echo ENTER_GUARDAR; ?>"><input style="width: 70%;" type="text" class="saldo" id="saldo<?php echo $unitat_c->id;?>" name="<?php echo $unitat_c->id;?>" value="<?php echo $saldo;?>" size="3" onblur="redondear('saldo<?php echo $unitat_c->id;?>'); ocultarMensaje();" onkeyup="ComprobarNumerico('saldo<?php echo $unitat_c->id;?>');" /></a>&nbsp;&euro;</td>
<?php
						}else{
							echo '<td>&nbsp;</td>';
						}
					}
?>
					<td align="center">
						<a href="usuariesmod.php?mode=mod&id=<?php echo $unitat_c->id;?>"><img src="../estils/mod.png" title="<?php echo MODIFICAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
						&nbsp;
						<?php if($unitat_c->n_caixa != 0) echo '<a class="'.$classDelete.'" href="usuaries.php?mode=del&id='.$unitat_c->id.'"><img src="../estils/baixa.png" title="'.BAJA.'" width="20" height="20" border="0" align="absmiddle" alt="" /></a>'; ?>
					</td>
				</tr>
<?php
			}
?>
		</table>
	</div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');?>