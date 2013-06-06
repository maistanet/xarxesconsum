<?php
/**
* Al intentar borrar una categoría que tiene productos asociados, muestra el listado, con posibilidad de modificar la categoría de los mismos.
* Así se evita que queden productos sin categoría, ya que obliga a revisarlos.
* Incluido desde /admin/categories.php
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
$sqlNomCateg='SELECT nom FROM categoria WHERE id="'.$id.'"';
$resultNomCateg=mysql_query($sqlNomCateg) or die(mysql_error());
$filaNomCateg=mysql_fetch_object($resultNomCateg);
?>
<div id="NoBotonEliminar" style="display: block;">
	<p><?php echo CATEGORIA_TIENE_PRODUCTOS_INI; ?><b><?php echo $filaNomCateg->nom; ?></b><?php echo CATEGORIA_TIENE_PRODUCTOS_FIN; ?></p>
	<input type="hidden" id="idCategoria" name="idCategoria" value="<?php echo $id;?>" />
</div>
<div id="BotonEliminar" style="display: none;">
	<p align="left"><?php echo PUEDES_ELIMINAR_CATEGORIA; ?><b><?php echo $filaNomCateg->nom; ?></b>.<br />
	<a class="delete" href="categories.php?mode=del&id=<?php echo $id; ?>"><?php echo ELIMINAR_CATEGORIA; ?></a></p>
</div>
<?php
$sqlProductores='SELECT id,nom_productor FROM productors ORDER BY nom_productor ASC';
$resultProductores=mysql_query($sqlProductores) or die(mysql_error());
while($filaProductor=mysql_fetch_object($resultProductores)){
	$sqlProdsCateg='SELECT * FROM producte WHERE id_productor="'.$filaProductor->id.'" AND id_categoria="'.$id.'" ORDER BY actiu DESC, nom_producte ASC';
	$resultProdsCateg=mysql_query($sqlProdsCateg) or die(mysql_error());
	if(mysql_num_rows($resultProdsCateg)>0){
?>
		<h4><?php echo $filaProductor->nom_productor;?></h4>
		<table width="100%" class="productes" border="0" cellspacing="0">
			<tr>
				<td width="5%" align="left"><?php echo ACTIVO; ?></td>
				<td width="14%" align="left"><?php echo NOMBRE; ?></td>
				<td width="20%" align="left"><?php echo CATEGORIA; ?></td>
				<td width="7%" align="left"><?php echo FORMATO; ?></td>
				<td width="11%" align="left"><?php echo PRECIO; ?></td>
				<td width="36%" align="left"><?php echo COMENTARIO; ?></td>
				<td width="7%" align="center"><?php echo OPCIONES; ?></td>
			</tr>
<?php
			$color1=$_SESSION['color1'];
			$color2=$_SESSION['color2'];
			$color=$color2;
			while($filaProdsCateg=mysql_fetch_object($resultProdsCateg)){
				($color==$color1) ? $color=$color2 : $color=$color1;
?>
				<tr bgcolor="<?php echo $color; ?>">
					<td><input type="checkbox" name="<?php echo $filaProdsCateg->id; ?>" <?php if($filaProdsCateg->actiu=='1') echo 'checked="checked"'; ?> onblur="ocultarMensaje();" /></td>
					<td><?php echo $filaProdsCateg->nom_producte;?></td>
					<td>
						<select name="categoria" class="selectCategoria" id="<?php echo $filaProdsCateg->id; ?>" onblur="ocultarMensaje();" />
<?php
							$sqlCateg='SELECT * FROM categoria';
							$resultCateg=mysql_query($sqlCateg) or die(mysql_error());
							while($filaCateg=mysql_fetch_object($resultCateg)){
?>
								<option value="<?php echo $filaCateg->id; ?>" <?php if($filaCateg->id==$id) echo 'selected="selected"'; ?> /><?php echo $filaCateg->nom;?></option>
<?php
							}
?>
						</select>
					</td>
					<td><?php echo $filaProdsCateg->format;?></td>
					<td><?php echo $filaProdsCateg->preu;?>&nbsp;&euro;</td>
					<td><?php echo $filaProdsCateg->comentari;?></td>
					<td align="center">
						<a href="productesmod.php?mode=mod&id=<?php echo $filaProdsCateg->id;?>"><img src="../estils/mod.png" title="<?php echo MODIFICAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
						&nbsp;
						<a class="deleteProdCat" href="productes.php?mode=del&id=<?php echo $filaProdsCateg->id;?>"><img src="../estils/del.png" title="<?php echo ELIMINAR; ?>" width="20" height="20" border="0" align="absmiddle" alt="" /></a>
					</td>
				</tr>
<?php
			}
?>
		</table>
		<br />
<?php
	}
}
?>