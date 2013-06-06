<?php
/**
* Genera pdf a partir d'un text
* Opcions del pdf a crear
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
include('../includes/iniciar_aplicacion.php');
include(RUTA_WEB_INCLUDE.'includes/sessio.php');
include(RUTA_WEB_INCLUDE.'funcions/mpdf/mpdf.php');

function generarpdf($text){
	// llengua, A4, tamany i tipus de lletra
	$mpdf=new mPDF('ca','A4',9,'DejaVuSans');

	// titol, autor
	$mpdf->SetTitle(PEDIDOS);
	$mpdf->SetAuthor('-'.$_SESSION["nom_xarxa"].'-');
	$mpdf->ignore_invalid_utf8 = true;

	// generem el document
//	$stylesheet = file_get_contents('comanda.css');
//	$mpdf->WriteHTML($stylesheet,1);
//	$mpdf->WriteHTML($text,2);
	$mpdf->WriteHTML($text);

	// guardem el document i tanquem
	$mpdf->Output();
}
?>