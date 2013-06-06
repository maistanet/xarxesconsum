<?php
/**
* Apartat productes. Et llista els productes per productors.
* Modificar, editar i eliminar productes.
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

// segons l'apartat
$apartat=PRODUCTOS;
$fet='';
//per a mod, add o del
$mode = $_GET['mode'];
$id = $_GET['id'];
$validar = $_POST['accept'];

if($mode == 'del'){
	$del = "DELETE FROM producte WHERE id = '$id'";
	$resultat = mysql_query($del) or die(mysql_error());
	$apartat.=' -> '.ELIMINAR;
	$fet=CORRECTO_ELIMINAR_PRODUCTO;
}elseif($validar==ACEPTAR){
	// Sols per a add i mod (formulari)
	if ($_POST['actiu'] == 'on') {
		$actiu = 1;
	}else $actiu = 0;
	$nom = $_POST['nom'];
	$format = $_POST['format'];
	$id_productor = intval($_POST['id_productor']);
	$descripcio = $_POST['descripcio'];
	$preu = floatval($_POST['preu']);
	if($preu=='') $preu=0;
	$preu=round($preu*100)/100;
	$comentari = $_POST['comentari'];
	$categoria = $_POST['categoria'];
	switch($mode){
		case "mod":
			$mod = "UPDATE producte SET actiu='$actiu', nom_producte='$nom', format='$format', id_productor='$id_productor', id_categoria='$categoria', descripcio='$descripcio', preu='$preu', comentari='$comentari' WHERE id = '$id'";
			$resultat = mysql_query($mod) or die(mysql_error());
			$apartat.=' -> '.MODIFICAR;
			$fet=CORRECTO_MODIF_PRODUCTO;
		break;
		case "add":
			$add = "INSERT INTO producte (actiu, nom_producte, format, id_productor, id_categoria, descripcio, preu, comentari) VALUES ( '$actiu', '$nom', '$format', '$id_productor', '$categoria','$descripcio', '$preu', '$comentari')";
			$resultat = mysql_query($add) or die(mysql_error());
			$apartat.=' -> '.ANYADIR_NUEVO_PRODUCTO;
			$fet=CORRECTO_ANYADIR_PRODUCTO;
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
	<script type="text/javascript" src="../funcions/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'idiomas/'.$_SESSION['lang'].'/'.$_SESSION['lang'].'.js'; ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("input:checkbox").click(function(){
				var chks = $(this).attr("checked");
				if(chks) var clicat = 1;
				else var clicat = 0;
				var id_producte = $(this).attr("name");
				$.post("includes/ajax/update_productes.php",{clicat:clicat, id_producte:id_producte});
				document.getElementById('Separacion').style.display='none';
				if(chks) document.getElementById('mensajeText').value=PRODUCTO_ACTIVADO;
				else document.getElementById('mensajeText').value=PRODUCTO_DESACTIVADO;
				document.getElementById('Mensaje').style.display='block';
			});
			$('.preu').keydown(function(e){
				if(e.keyCode == 13 || e.keyCode == 9){
					var id_producte = $(this).attr("name");
					var preu = $(this).val();
					$.post("includes/ajax/update_productes.php",{preu:preu, id_producte:id_producte});
					document.getElementById('Separacion').style.display='none';
					document.getElementById('mensajeText').value=PRECIO_ACTUALIZADO;
					document.getElementById('Mensaje').style.display='block';
				}
			});
			$('.comentari').keydown(function(e){
				if(e.keyCode == 13 || e.keyCode == 9){
					var id_producte = $(this).attr("name");
					var comentari = $(this).val();
					$.post("includes/ajax/update_productes.php",{comentari:comentari, id_producte:id_producte});
					document.getElementById('Separacion').style.display='none';
					document.getElementById('mensajeText').value=COMENTARIO_ACTUALIZADO;
					document.getElementById('Mensaje').style.display='block';
				}
			});
			$('.productor').click(function(){
				$(this).next().slideToggle('fast');
				return false;
			});
			$('.delete').click(function(){
				var answer = confirm(BAJA_PRODUCTO);
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
<?php
		$hayCategs=false;
		$sqlHayCategs='SELECT id FROM categoria';
		$resultHayCategs=mysql_query($sqlHayCategs);
		if(mysql_fetch_object($resultHayCategs))
			$hayCategs=true;
		$hayProductorxs=false;
		$sqlHayProductorxs='SELECT id FROM productors';
		$resultHayProductorxs=mysql_query($sqlHayProductorxs);
		if(mysql_fetch_object($resultHayProductorxs))
			$hayProductorxs=true;
		if($hayCategs && $hayProductorxs){
?>
			<a href="productesmod.php?mode=add"><?php echo ANYADIR_PRODUCTO; ?></a><br /><br />
<?php
		}else{
			echo NO_PRODUCTORXS_NI_CATEGORIAS.'<br /><br />';
		}
		$categorias=array();
		$sqlCategorias='SELECT * FROM categoria ORDER BY nom';
		$resultCategorias=mysql_query($sqlCategorias) or die(mysql_error());
		for($numCat=0;$filaCategorias=mysql_fetch_object($resultCategorias);$numCat++){
			$categorias[$numCat]['id']=$filaCategorias->id;
			$categorias[$numCat]['nom']=$filaCategorias->nom;
		}
		$con_prod = "SELECT id,nom_productor FROM productors ORDER BY nom_productor ASC";
		$res_prod = mysql_query($con_prod) or die(mysql_error());
		while ($productor = mysql_fetch_object($res_prod)) {
?>
			<a class="productor" href=""><?php echo $productor->nom_productor;?></a>

			<table width="100%" class="productes" border="0" cellspacing="0" style="display: none">
				<tr>
					<td width="4%" align="left"><?php echo ACTIVO; ?>&nbsp;</td>
					<td width="14%" align="left"><?php echo NOMBRE; ?></td>
					<td width="20%" align="left"><?php echo CATEGORIA; ?></td>
					<td width="7%" align="left"><?php echo FORMATO; ?>&nbsp;</td>
					<td width="11%" align="left"><?php echo PRECIO; ?></td>
					<td width="36%" align="left"><?php echo COMENTARIO; ?></td>
					<td width="8%" align="center"><?php echo OPCIONES; ?></td>
				</tr>
<?php
				$color1=$_SESSION['color1'];
				$color2=$_SESSION['color2'];
				$color=$color2;
				for($j=0;$j<count($categorias);$j++){
					$consulta='SELECT * FROM producte WHERE id_productor="'.$productor->id.'" AND id_categoria="'.$categorias[$j]['id'].'" ORDER BY actiu DESC, nom_producte ASC';
					$resultat=mysql_query($consulta) or die(mysql_error());
					while($producte=mysql_fetch_object($resultat)){
						($color==$color1) ? $color=$color2 : $color=$color1;
?>
						<tr bgcolor="<?php echo $color; ?>">
							<td><input type="checkbox" name="<?php echo $producte->id;?>" <?php if($producte->actiu == '1') echo 'checked="checked"';?> onblur="ocultarMensaje();" /></td>
							<td><?php echo $producte->nom_producte;?></td>
							<td>
<?php
								echo $categorias[$j]['nom'];
?>
							</td>
<?php
							$formatoMostrar=$producte->format;
							if($producte->format=='unitat') $formatoMostrar=UNIDAD;
?>
							<td><?php echo $formatoMostrar;?></td>
							<td><a title="<?php echo ENTER_GUARDAR; ?>"><input style="width: 60%;" type="text" class="preu" id="preu<?php echo $producte->id;?>" name="<?php echo $producte->id;?>" value="<?php echo $producte->preu;?>" size="3" onblur="redondear('preu<?php echo $producte->id;?>'); ocultarMensaje();" onkeyup="ComprobarNumerico('preu<?php echo $producte->id;?>');" /></a>&nbsp;&euro;</td>
							<td><a title="<?php echo ENTER_GUARDAR; ?>"><input style="width: 98%;" type="text" class="comentari" name="<?php echo $producte->id;?>" value="<?php echo $producte->comentari;?>" size="30" onblur="ocultarMensaje();" /></a></td>
							<td align="center">
								<a href="productesmod.php?mode=mod&id=<?php echo $producte->id;?>"><img src="../estils/mod.png" title="<?php echo MODIFICAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
								&nbsp;
								<a class="delete" href="productes.php?mode=del&id=<?php echo $producte->id;?>"><img src="../estils/del.png" title="<?php echo ELIMINAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
							</td>
						</tr>
<?php
					}
				}
?>
			</table>
			<br />
<?php
		}
?>
	</div>
</body>
</html>
<?php
// alliberem el resultat de la consulta
mysql_free_result($resultat);
// tanquem la conexió a la BD
require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php');
?>