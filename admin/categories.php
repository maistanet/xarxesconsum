<?php
/**
* Apartat Categories.
* Les categories son de cada producte. Podrem crear-ne de noves i vorer l'ordre d'aparició.
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
$apartat=CATEGORIAS;
$fet='';
//per a mod, add o del
$mode = $_GET['mode'];
$id = $_GET['id'];
$validar = $_POST['accept'];

//Variable de control para cuando se quiere borrar una categoría que tiene productos asociados
$listarProdsCateg=false;

switch($mode){
	case "del":
		if(isset($_GET['id'])){
			$sqlProdsCateg='SELECT id FROM producte WHERE id_categoria="'.$id.'"';
			$resultProdsCateg=mysql_query($sqlProdsCateg) or die(mysql_error());
			if(mysql_num_rows($resultProdsCateg)>0){
				$listarProdsCateg=true;
			}else{
				$sqlDelCategoria='DELETE FROM categoria WHERE id="'.$id.'"';
				mysql_query($sqlDelCategoria) or die(mysql_error());
				$apartat.=' -> '.ELIMINAR;
				$fet=CORRECTO_ELIMINAR_CATEGORIA;
			}
		}
	break;
	case "puj":
		$id_pujant = $id;
		$cons = "SELECT * FROM categoria ORDER BY id";
		$res = mysql_query($cons) or die(mysql_error());
		$i = 0;
		while ($linea_categoria = mysql_fetch_object($res)) {
			$id_categoria[$i] = $linea_categoria->id;
			$nom_categoria[$i] = $linea_categoria->nom;
			if($linea_categoria->id==$id_pujant){$num_pujant = $i;}
			$i++;
		}
		$num_baixant = $num_pujant - 1;
		$id_baixant = $id_categoria[$num_baixant];
		$cons = "UPDATE categoria SET id = -1 WHERE id ='$id_pujant'";
		$cons_prod = "UPDATE  producte SET id_categoria = -1 WHERE id_categoria='$id_pujant'";
		$resultat = mysql_query($cons) or die(mysql_error());
		$resultat_prod = mysql_query($cons_prod) or die(mysql_error());
		$cons = "UPDATE categoria SET id = '$id_pujant' WHERE id = '$id_baixant'";
		$cons_prod = "UPDATE producte SET id_categoria = '$id_pujant' WHERE id_categoria = '$id_baixant'";
		$resultat = mysql_query($cons) or die(mysql_error());
		$resultat_cons = mysql_query($cons_prod) or die(mysql_error());
		$cons = "UPDATE categoria SET id = '$id_baixant' WHERE id = -1";
		$cons_prod = "UPDATE producte SET id_categoria = '$id_baixant' WHERE id_categoria = -1";
		$resultat = mysql_query($cons) or die(mysql_error());
		$resultat_prod = mysql_query($cons_prod) or die(mysql_error());
	break;
	case "mod":
/*		$nom = mysql_real_escape_string($_POST['nom_mod']);
		$mod = "UPDATE categoria SET nom='$nom' WHERE id = '$id'";
		$resultat = mysql_query($mod) or die(mysql_error());
		$apartat .= " -> Modificar";
		$fet = "La categoria ha sigut modificada correctament";
	break;*/
	case "add":
		$nom = mysql_real_escape_string($_POST['nom']);
		$add = "INSERT INTO categoria (nom) VALUES ('$nom')";
		$resultat = mysql_query($add) or die(mysql_error());
		$apartat.=' -> '.ANYADIR_NUEVA_CATEGORIA;
		$fet=CORRECTO_ANYADIR_CATEGORIA;
	break;
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
			$('.selectCategoria').change(function(){
				var selectCatId=$(this).attr("value");
				var id_producte=$(this).attr("id");
				$.post("includes/ajax/update_productes.php",{selectCatId:selectCatId, id_producte:id_producte});
				document.getElementById('Separacion').style.display='none';
				document.getElementById('mensajeText').value=CATEGORIA_ACTUALIZADA;
				document.getElementById('Mensaje').style.display='block';
				var idCategoria=document.getElementById('idCategoria').value;
				if(categoriasCambiadas(idCategoria)){
					document.getElementById('BotonEliminar').style.display='block';
					document.getElementById('NoBotonEliminar').style.display='none';
				}else{
					document.getElementById('BotonEliminar').style.display='none';
					document.getElementById('NoBotonEliminar').style.display='block';
				}
			});
			$('.delete').click(function(){
				var answer = confirm(BAJA_CATEGORIA);
				return answer; // answer is a boolean
			});
			$('.deleteProdCat').click(function(){
				var answer = confirm(BAJA_PRODUCTO);
				return answer; // answer is a boolean
			});
		});
		function ocultarMensaje(){
			document.getElementById('Mensaje').style.display='none';
			document.getElementById('Separacion').style.display='block';
		}
		function categoriasCambiadas(idCategoria){
			var cambiadas=true;
			$('select').each(function(){
				if($(this).val()==idCategoria) cambiadas=false;
			});
			return cambiadas;
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
<?php
		if($listarProdsCateg===true) include(RUTA_WEB_INCLUDE.'admin/includes/llistarProductesCategoria.php');
		else{
?>
			<form method="post" action="categories.php?mode=add&id=<?php echo $id; ?>" id="formulari">
				<fieldset>
					<legend><?php echo ANYADIR_CATEGORIA; ?></legend>
					<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
						<tr>
							<td width="15%" align="right"><?php echo NOMBRE; ?>:&nbsp;</td>
							<td width="60%" align="left"><input style="width: 95%;" name="nom" type="text" class="required" value="" size="60"/></td>
							<td width="25%" align="center"><input type="submit" name="accept" value="<?php echo ACEPTAR; ?>" /></td>
						</tr>
					</table>
				</fieldset>
			</form>
			<br />
			<table class="categoria" border="0" cellspacing="0" align="center">
				<tr>
					<td><?php echo NUM_ORDEN; ?></td>
					<td>&nbsp;</td>
	                <td><?php echo NOMBRE; ?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td align="center"><?php echo OPCIONES; ?></td>
				</tr>
<?php
				$i=1;
				$consulta="SELECT * FROM categoria order by id";
				$resultat=mysql_query($consulta) or die(mysql_error());
				$color1=$_SESSION['color1'];
				$color2=$_SESSION['color2'];
				$color=$color2;
				while($categoria=mysql_fetch_object($resultat)){
					($color==$color1) ? $color=$color2 : $color=$color1;
?>
					<tr bgcolor="<?php echo $color; ?>">
						<td><?php echo $i; ?></td>
						<td>&nbsp;</td>
						<td><?php echo $categoria->nom; ?></td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align="center">
							<a class="delete" href="categories.php?mode=del&id=<?php echo $categoria->id; ?>"><img src="../estils/del.png" title="<?php echo ELIMINAR; ?>" width="20" height="20" border="0" align="left" alt="" /></a>
							<!-- <a href="categories.php?mode=mod&id=<?php echo $categoria->id; ?>"><img src="../estils/mod.gif" title="Modificar" width="20" height="20" border="0" align="left" alt="" /></a> -->
							<?php if($i!=1){ ?><a href="categories.php?mode=puj&id=<?php echo $categoria->id; ?>"><img src="../estils/pujar.png" title="<?php echo SUBIR; ?>" width="20" height="20" border="0" align="left" alt="" /></a><?php } ?>
						</td>
					</tr>
<?php
					$i++;
				}
?>
			</table>
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