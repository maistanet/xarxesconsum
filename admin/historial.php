<?php
/**
* Apartado historial.
* Permite hacer previsiones de consumo de cara a los/las productores/as.
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

$apartat=HISTORIAL_PEDIDOS;

//Comprobamos si se ha hecho consulta
include(RUTA_WEB_INCLUDE.'admin/includes/consultarHistorial.php');

//OBTENCIÓN DE DATOS PARA EL FORMULARIO DE CONSULTA
//-------------------------------------------------
//Mediante la combinación de callback y usort: usort(array_de_strings,'callback');
//ordenamos alfabéticamente el array teniendo en cuenta los signos de acentuación
function callback($name1,$name2){
	$patterns=array(
		'a'=>'(á|à|â|ä|Á|À|Â|Ä)',
		'e'=>'(é|è|ê|ë|É|È|Ê|Ë)',
		'i'=>'(í|ì|î|ï|Í|Ì|Î|Ï)',
		'o'=>'(ó|ò|ô|ö|Ó|Ò|Ô|Ö)',
		'u'=>'(ú|ù|û|ü|Ú|Ù|Û|Ü)'
	);
	$name1=preg_replace(array_values($patterns),array_keys($patterns),$name1);
	$name2=preg_replace(array_values($patterns),array_keys($patterns),$name2);
	return strnatcasecmp($name1,$name2);
}
//Productorxs
$productorx=array();
$localidadProductorx=array();
$sqlProductorx='SELECT id,nom_productor,localitat FROM productors ORDER BY nom_productor ASC';
$resultProductorx=mysql_query($sqlProductorx) or die(mysql_error());
for($i=0;$filaProductorx=mysql_fetch_object($resultProductorx);$i++){
	$productorx[$i]['id']=$filaProductorx->id;
	$productorx[$i]['nom_productor']=$filaProductorx->nom_productor;
	$localidadProductorx[$i]=$filaProductorx->localitat;
}
usort($localidadProductorx,'callback');
	

//Productos
$producto=array();
$sqlProducto='SELECT nom_producte FROM producte ORDER BY nom_producte ASC';
$resultProducto=mysql_query($sqlProducto) or die(mysql_error());
for($i=0;$filaProducto=mysql_fetch_object($resultProducto);$i++){
	$producto[$i]=$filaProducto->nom_producte;
}

//Categorias
$categoria=array();
$sqlCategoria='SELECT id,nom FROM categoria ORDER BY id ASC';
$resultCategoria=mysql_query($sqlCategoria) or die(mysql_error());
for($i=0;$filaCategoria=mysql_fetch_object($resultCategoria);$i++){
	$categoria[$i]['id']=$filaCategoria->id;
	$categoria[$i]['nom']=$filaCategoria->nom;
}
//FIN OBTENCIÓN DE DATOS PARA EL FORMULARIO DE CONSULTA
//-----------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="robots" content="index,follow" />
	<title><?php echo $_SESSION["nom_xarxa"]; ?></title>

	<link rel="stylesheet" type="text/css" href="../estils/template.css" />
	<link rel="stylesheet" type="text/css" href="../estils/validate.css" />
	<link rel="stylesheet" type="text/css" href="../estils/jquery-ui-1.8.13.custom.css" />
	<script type="text/javascript" src="../funcions/jquery.min.js"></script>
	<script type="text/javascript" src="../funcions/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo RUTA_WEB.'funcions/jquery.ui.datepicker-'.$_SESSION['lang'].'.js'; ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#fechaIni").datepicker({
				showOn: 'both',
				buttonImage: '../estils/calendar.png',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				maxDate: "0",
				dateFormat: "dd/mm/yy",
				onClose: function(selectedDate){
					$("#fechaFin").datepicker("option","minDate",selectedDate);
				}
			});
			$("#fechaFin").datepicker({
				showOn: 'both',
				buttonImage: '../estils/calendar.png',
				buttonImageOnly: true,
				changeMonth: true,
				changeYear: true,
				numberOfMonths: 1,
				maxDate: "0",
				dateFormat: "dd/mm/yy",
				onClose: function(selectedDate){
					$("#fechaIni").datepicker("option","maxDate",selectedDate);
				}
			});
			$('.fechaIni').keydown(function(e){
				if(e.keyCode==27){ //Tecla escape
					document.getElementById('fechaIni').value='';
				}
			});
			$('.fechaFin').keydown(function(e){
				if(e.keyCode==27){ //Tecla escape
					document.getElementById('fechaFin').value='';
				}
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
		function ComprobarNumerico(elemento){
			if(isNaN(document.getElementById(elemento).value)){
				document.getElementById(elemento).value='';
			}else{
				document.getElementById(elemento).value=trim(document.getElementById(elemento).value);
			}
		}
		function redondearSiNoVacio(elemento){
			if(document.getElementById(elemento).value!=''){
				document.getElementById(elemento).value=Math.round(document.getElementById(elemento).value*100)/100;
			}
		}
		function comprobarRangoNumerico(elementoIni,elementoFin,elementoEditado){
			if(document.getElementById(elementoIni).value!='' && document.getElementById(elementoFin).value!=''){
				if(document.getElementById(elementoIni).value > document.getElementById(elementoFin).value!=''){
					document.getElementById(elementoEditado).value='';
				}
			}
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
<?php
					if($consultar==CONSULTAR && $errorDatosForm){
						echo '<div>
								<p>'.error('<input type="text" id="mensajeText" name="mensajeText" style="width: 90%; text-align: center; border-width: 0px; font-size: medium; font-weight: bold; color: #000000; background-color: #FFE6E6;" readonly="readonly" value="'.ERROR_DATOS_INTRODUCIDOS.'" />').'</p>
							</div>';
					}elseif($consultar!='' && count($productosConCantidades)==0){
						echo '<div>
								<p>'.error('<input type="text" id="mensajeText" name="mensajeText" style="width: 90%; text-align: center; border-width: 0px; font-size: medium; font-weight: bold; color: #000000; background-color: #FFE6E6;" readonly="readonly" value="'.ERROR_SIN_RESULTADOS.'" />').'</p>
							</div>';
					}else{
						echo '<div>
								<p>'.separacion().'</p>
							</div>';
					}
?>
				</td>
				<td width="30%">&nbsp;</td>
			</tr>
		</table>
		<form method="post" action="historial.php" enctype="multipart/form-data" id="formulari">
			<fieldset>
				<legend><?php echo CONSULTAR_HISTORIAL; ?></legend>
				<table width="100%" border="0" cellpadding="3" cellspacing="4" align="center">
					<tr>
						<td colspan="6">
							<b><?php echo PRODUCTORX; ?></b>
						</td>
					</tr>
					<tr>
<?php
						$optionNoSeleccionado='selected="selected"';
						if(isset($productorForm) && $productorForm!=''){
							$optionNoSeleccionado='';
						}
?>
						<td width="10%" align="left"><label for="productor"><?php echo PRODUCTORX; ?></label></td>
						<td width="55%" align="left">
							<select name="productor">
								<option value="noSeleccionado" <?php echo $optionNoSeleccionado; ?> ><?php echo SELECCIONA_PRODUCTORX; ?></option>
<?php
								for($i=0;$i<count($productorx);$i++){
									if(isset($productorForm) && $productorForm!='' && $productorForm==$productorx[$i]['id'])
										echo '<option value="'.$productorx[$i]['id'].'" selected="selected" >'.$productorx[$i]['nom_productor'].'</option>';
									else
										echo '<option value="'.$productorx[$i]['id'].'" >'.$productorx[$i]['nom_productor'].'</option>';
								}
?>
							</select>
						</td>
<?php
						$radioSi='';
						$radioNo='';
						$radioTodos='';
						if(isset($socixForm) && $socixForm!=''){
							if($socixForm=='si') $radioSi='checked="checked"';
							if($socixForm=='no') $radioNo='checked="checked"';
							if($socixForm=='todos') $radioTodos='checked="checked"';
						}else{
							$radioTodos='checked="checked"';
						}
?>
						<td width="10%" align="left"><label for="socix"><?php echo SOCIXS; ?></label></td>
						<td width="7%" align="left"><?php echo SOCIXS_SI; ?><input name="socix" type="radio" value="si" <?php echo $radioSi; ?> /></td>
						<td width="8%" align="left"><?php echo SOCIXS_NO; ?><input name="socix" type="radio" value="no" <?php echo $radioNo; ?> /></td>
						<td width="10%" align="left"><?php echo SOCIXS_TODXS; ?><input name="socix" type="radio" value="todos" <?php echo $radioTodos; ?> /></td>
					</tr>
					<tr>
<?php
						$optionNoSeleccionado='selected="selected"';
						if(isset($localidadForm) && $localidadForm!=''){
							$optionNoSeleccionado='';
						}
?>
						<td align="left"><label for="localidad"><?php echo LOCALIDAD; ?></label></td>
						<td align="left">
							<select name="localidad">
								<option value="noSeleccionado" <?php echo $optionNoSeleccionado; ?> ><?php echo SELECCIONA_LOCALIDAD; ?></option>
<?php
								$localidadAnterior='';
								for($i=0;$i<count($localidadProductorx);$i++){
									if($localidadProductorx[$i]!==$localidadAnterior && $localidadProductorx[$i]!==''){
										if(isset($localidadForm) && $localidadForm!='' && $localidadForm==$localidadProductorx[$i])
											echo '<option value="'.$localidadProductorx[$i].'" selected="selected" >'.$localidadProductorx[$i].'</option>';
										else
											echo '<option value="'.$localidadProductorx[$i].'" >'.$localidadProductorx[$i].'</option>';
									}
									$localidadAnterior=$localidadProductorx[$i];
								}
?>
							</select>
						</td>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">
							<b><?php echo CATEGORIA; ?></b>
						</td>
					</tr>
					<tr>
<?php
						$optionNoSeleccionado='selected="selected"';
						if(isset($categoriaForm) && $categoriaForm!=''){
							$optionNoSeleccionado='';
						}
?>
						<td><label for="categoria"><?php echo CATEGORIA; ?></label></td>
						<td>
							<select name="categoria">
								<option value="noSeleccionado" <?php echo $optionNoSeleccionado; ?> ><?php echo SELECCIONA_CATEGORIA; ?></option>
<?php
								for($i=0;$i<count($categoria);$i++){
									if(isset($categoriaForm) && $categoriaForm!='' && $categoriaForm==$categoria[$i]['id'])
										echo '<option value="'.$categoria[$i]['id'].'" selected="selected" >'.$categoria[$i]['nom'].'</option>';
									else
										echo '<option value="'.$categoria[$i]['id'].'" >'.$categoria[$i]['nom'].'</option>';
								}
?>
							</select>
						</td>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">
							<b><?php echo PRODUCTO; ?></b>
						</td>
					</tr>
					<tr>
<?php
						$optionNoSeleccionado='selected="selected"';
						if(isset($productoForm) && $productoForm!=''){
							$optionNoSeleccionado='';
						}
?>
						<td><label for="producto"><?php echo PRODUCTO; ?></label></td>
						<td>
							<select name="producto">
								<option value="noSeleccionado" <?php echo $optionNoSeleccionado; ?> ><?php echo SELECCIONA_PRODUCTO; ?></option>
<?php
								$productoAnterior='';
								for($i=0;$i<count($producto);$i++){
									if($producto[$i]!==$productoAnterior && $producto[$i]!==''){
										if(isset($productoForm) && $productoForm!='' && $productoForm==$producto[$i])
											echo '<option value="'.$producto[$i].'" selected="selected" >'.$producto[$i].'</option>';
										else
											echo '<option value="'.$producto[$i].'" >'.$producto[$i].'</option>';
									}
									$productoAnterior=$producto[$i];
								}
?>
							</select>
						</td>
<?php
						$radioSi='';
						$radioNo='';
						$radioTodos='';
						if(isset($activoForm) && $activoForm!=''){
							if($activoForm=='si') $radioSi='checked="checked"';
							if($activoForm=='no') $radioNo='checked="checked"';
							if($activoForm=='todos') $radioTodos='checked="checked"';
						}else{
							$radioTodos='checked="checked"';
						}
?>
						<td><label for="activo"><?php echo ACTIVO; ?></label></td>
						<td><?php echo ACTIVO_SI; ?><input name="activo" type="radio" value="si" <?php echo $radioSi; ?> /></td>
						<td><?php echo ACTIVO_NO; ?><input name="activo" type="radio" value="no" <?php echo $radioNo; ?> /></td>
						<td><?php echo ACTIVO_TODOS; ?><input name="activo" type="radio" value="todos" <?php echo $radioTodos; ?> /></td>
					</tr>
				</table>
				<table width="100%" border="0" cellpadding="3" cellspacing="4" align="center">
					<tr>
						<td width="25%" align="left"><label for="precioIni"><?php echo PRECIO_INI; ?></label></td>
						<td width="25%" align="left"><input style="width: 60%;<?php echo ($errorPrecioIniForm ? ' border-color: #FF0000;' : ''); ?>" type="text" name="precioIni" id="precioIni" value="<?php echo $precioIniForm; ?>" onblur="redondearSiNoVacio('precioIni');comprobarRangoNumerico('precioIni','precioFin','precioIni');" onkeyup="ComprobarNumerico('precioIni');" />&nbsp;&euro;</td>
						<td width="25%" align="left"><label for="precioFin"><?php echo PRECIO_FIN; ?></label></td>
						<td width="25%" align="left"><input style="width: 60%;<?php echo ($errorPrecioFinForm ? ' border-color: #FF0000;' : ''); ?>" type="text" name="precioFin" id="precioFin" value="<?php echo $precioFinForm; ?>" onblur="redondearSiNoVacio('precioFin');comprobarRangoNumerico('precioIni','precioFin','precioFin');" onkeyup="ComprobarNumerico('precioFin');" />&nbsp;&euro;</td>
					</tr>
					<tr>
						<td colspan="4">
							<b><?php echo PEDIDO_CONJUNTO; ?></b>
						</td>
					</tr>
					<tr>
						<td><label for="fechaIni"><?php echo FECHA_INI; ?></label></td>
						<td><a title="<?php echo ESCAPE_VACIAR; ?>"><input style="width: 60%;<?php echo ($errorFechaIniForm ? ' border-color: #FF0000;' : ''); ?>" type="text" class="fechaIni" name="fechaIni" id="fechaIni" value="<?php echo $fechaIniFormMostrar; ?>" readonly="readonly" /></a></td>
						<td><label for="fechaFin"><?php echo FECHA_FIN; ?></label></td>
						<td><a title="<?php echo ESCAPE_VACIAR; ?>"><input style="width: 60%;<?php echo ($errorFechaFinForm ? ' border-color: #FF0000;' : ''); ?>" type="text" class="fechaFin" name="fechaFin" id="fechaFin" value="<?php echo $fechaFinFormMostrar; ?>" readonly="readonly" /></a></td>
					</tr>
					<tr>
						<td><label for="cantidadIni"><?php echo CANTIDAD_INI; ?></label></td>
						<td><input style="width: 60%;<?php echo ($errorCantidadIniForm ? ' border-color: #FF0000;' : ''); ?>" type="text" name="cantidadIni" id="cantidadIni" value="<?php echo $cantidadIniForm; ?>" onblur="redondearSiNoVacio('cantidadIni');comprobarRangoNumerico('cantidadIni','cantidadFin','cantidadIni');" onkeyup="ComprobarNumerico('cantidadIni');" /></td>
						<td><label for="cantidadFin"><?php echo CANTIDAD_FIN; ?></label></td>
						<td><input style="width: 60%;<?php echo ($errorCantidadFinForm ? ' border-color: #FF0000;' : ''); ?>" type="text" name="cantidadFin" id="cantidadFin" value="<?php echo $cantidadFinForm; ?>" onblur="redondearSiNoVacio('cantidadFin');comprobarRangoNumerico('cantidadIni','cantidadFin','cantidadFin');" onkeyup="ComprobarNumerico('cantidadFin');" /></td>
					</tr>
					<tr>
						<td><label for="gastoTotalPedidoIni"><?php echo GASTO_TOTAL_PEDIDO_INI; ?></label></td>
						<td><input style="width: 60%;<?php echo ($errorGastoTotalPedidoIniForm ? ' border-color: #FF0000;' : ''); ?>" type="text" name="gastoTotalPedidoIni" id="gastoTotalPedidoIni" value="<?php echo $gastoTotalPedidoIniForm; ?>" onblur="redondearSiNoVacio('gastoTotalPedidoIni');comprobarRangoNumerico('gastoTotalPedidoIni','gastoTotalPedidoFin','gastoTotalPedidoIni');" onkeyup="ComprobarNumerico('gastoTotalPedidoIni');" />&nbsp;&euro;</td>
						<td><label for="gastoTotalPedidoFin"><?php echo GASTO_TOTAL_PEDIDO_FIN; ?></label></td>
						<td><input style="width: 60%;<?php echo ($errorGastoTotalPedidoFinForm ? ' border-color: #FF0000;' : ''); ?>" type="text" name="gastoTotalPedidoFin" id="gastoTotalPedidoFin" value="<?php echo $gastoTotalPedidoFinForm; ?>" onblur="redondearSiNoVacio('gastoTotalPedidoFin');comprobarRangoNumerico('gastoTotalPedidoIni','gastoTotalPedidoFin','gastoTotalPedidoFin');" onkeyup="ComprobarNumerico('gastoTotalPedidoFin');" />&nbsp;&euro;</td>
					</tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td colspan="4" align="center">
							<input type="submit" name="consultar" value="<?php echo CONSULTAR; ?>" />
							<input type="submit" name="consultarTodo" value="<?php echo CONSULTAR_TODO_HISTORIAL; ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
			<br /><br />
<?php
			$historialConsultado='';
			if($consultar==CONSULTAR)
				$historialConsultado=HISTORIAL_PARAMETRIZADO;
			if($consultar==CONSULTAR_TODO_HISTORIAL)
				$historialConsultado=HISTORIAL_COMPLETO;
			if($historialConsultado!='' && !$errorDatosForm && count($productosConCantidades)>0){
?>
				<table width="100%" cellpadding="4px" cellspacing="0">
					<tr>
						<td colspan="10">
							<h4>
<?php
								echo $historialConsultado;
								if($consultar==CONSULTAR && !$algunParametro)
									echo '<br /><br />'.HISTORIAL_PARAMETRIZADO_SIN_PARAMETROS;
?>
							</h4>
						</td>
					</tr>
					<tr>
						<td width="7%" align="center"><?php echo SOCIXS; ?></td>
						<td width="15%" align="left"><?php echo PRODUCTORX; ?></td>
						<td width="10%" align="left"><?php echo LOCALIDAD; ?></td>
						<td width="15%" align="left"><?php echo CATEGORIA; ?></td>
						<td width="7%" align="center"><?php echo ACTIVO; ?></td>
						<td width="15%" align="left"><?php echo PRODUCTO; ?></td>
						<td width="7%" align="center"><?php echo PRECIO; ?></td>
						<td width="10%" align="center"><?php echo CANTIDAD; ?></td>
						<td width="7%" align="center"><?php echo FORMATO; ?></td>
						<td width="7%" align="center"><?php echo TOTAL; ?></td>
					</tr>
<?php
					//------------------------------------------------
					//PAGINADOR
					//------------------------------------------------
					//Variables de configuracion editables
					//------------------------------------------------
					$numEnlaces=5; //Cantidad de enlaces(los numeros de paginas)(para que salga centrado poner un numero IMPAR)
					$rango=25; //Cantidad de registros a mostrar por pagina
					$simboloAnt="&lt;"; //Simbolo de pagina anterior
					$simboloSig="&gt;"; //Simbolo de pagina siguiente
					$color1=$_SESSION['color1'];
					$color2=$_SESSION['color2'];
					$color=$color2;
					//------------------------------------------------
					if(isset($_POST['n']) && $_POST['n']!='')
						$n=addslashes(strip_tags($_POST['n']));
					else
						$n=0;
					$cantidadRegistros=count($productosConCantidades);
					//------------------------------------------------
					//FIN PAGINADOR
					//------------------------------------------------
					for($i=$n;$i<$cantidadRegistros && $i<$n+$rango;$i++){
						($color==$color1) ? $color=$color2 : $color=$color1;
						$consulta='SELECT socis,nom_productor,localitat,actiu,nom_producte,format,preu,id_categoria FROM productors AS t1, producte AS t2 WHERE t1.id=t2.id_productor AND t2.id="'.$productosConCantidades[$i]['id'].'"';
						$resultat=mysql_query($consulta) or die(mysql_error());
						$producte=mysql_fetch_object($resultat);
						$socixMostrar=SOCIXS_NO;
						if($producte->socis=='1') $socixMostrar=SOCIXS_SI;
						$activoMostrar=ACTIVO_NO;
						if($producte->actiu=='1') $activoMostrar=ACTIVO_SI;
						$formatoMostrar=$producte->format;
						if($producte->format=='gr') $formatoMostrar='Kg';
						if($producte->format=='unitat') $formatoMostrar=UNIDADES;
						$sqlCategoria='SELECT nom FROM categoria WHERE id="'.$producte->id_categoria.'"';
						$resultCategoria=mysql_query($sqlCategoria) or die(mysql_error());
						$filaCategoria=mysql_fetch_object($resultCategoria);
						echo '<tr bgcolor="'.$color.'">
							<td align="center">'.$socixMostrar.'</td>
							<td align="left">'.$producte->nom_productor.'</td>
							<td align="left">'.$producte->localitat.'</td>
							<td align="left">'.$filaCategoria->nom.'</td>
							<td align="center">'.$activoMostrar.'</td>
							<td align="left">'.$producte->nom_producte.'</td>
							<td align="center">'.$producte->preu.'&nbsp;&euro;</td>
							<td align="right">'.$productosConCantidades[$i]["quantitat"].'</td>
							<td align="left">'.$formatoMostrar.'</td>
							<td align="right">'.$productosConCantidades[$i]["gasto"].'&nbsp;&euro;</td>
						</tr>';
					}
?>
				</table>
				<br />
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center">
							<input type="hidden" name="n" id="n" value="" />
							<input type="hidden" name="mode" id="mode" value="" />
<?php
							echo PAG_NUM;
							//---------------------
							//ENLACES DEL PAGINADOR
							//---------------------
							if($n!=0){
?>
								<a title="<?php echo ANTERIOR; ?>"><input type="button" name="bAnt" value="<?php echo $simboloAnt; ?>" onclick="document.getElementById('n').value='<?php echo $n-$rango; ?>'; document.getElementById('mode').value='<?php echo $modo; ?>'; this.form.submit();" /></a>
<?php
							}
							$primerEnlace=($n/$rango)-floor($numEnlaces/2);
							if($primerEnlace<0)
								$primerEnlace=0;
							$ultimoEnlace=($n/$rango)+ceil($numEnlaces/2);
							for($pag=$primerEnlace;$pag<ceil($cantidadRegistros/$rango) && $pag<$ultimoEnlace;$pag++){
								if($rango*$pag==$n){
?>
									<span><?php echo ($pag+1); ?></span>
<?php
								}else{
?>
									<input type="button" name="bPag" value="<?php echo ($pag+1); ?>" onclick="document.getElementById('n').value='<?php echo $rango*$pag; ?>'; document.getElementById('mode').value='<?php echo $modo; ?>'; this.form.submit();" />
<?php
								}
							}
							if(($n+$rango)<$cantidadRegistros){
?>
								<a title="<?php echo SIGUIENTE; ?>"><input type="button" name="bSig" value="<?php echo $simboloSig; ?>" onclick="document.getElementById('n').value='<?php echo $n+$rango; ?>'; document.getElementById('mode').value='<?php echo $modo; ?>'; this.form.submit();" /></a>
<?php
							}
							//-------------------------
							//FIN ENLACES DEL PAGINADOR
							//-------------------------
?>
						</td>
					</tr>
				</table>
<?php
			}
?>
		</form>
	</div>
</body>
</html>
<?php require(RUTA_WEB_INCLUDE.'includes/bd/tanca.php'); ?>