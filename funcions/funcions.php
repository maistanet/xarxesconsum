<?php
/**
* Funcions varies
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
function decode($string){
	//$nopermitidos = array("'",'\\','<','>',"\"","-","%");
	$nopermitidos=array("'",'\\','<','>',"\"","%");
	$string=str_replace($nopermitidos, "", $string);
	return $string;
}
//posar la data bé en noticies
function trans_data($data){
	list($year, $month, $day ) = split('-', $data);
	//definim tots el mesos
	if('01' == $month) $month = ENERO;
	if('02' == $month) $month = FEBRERO;
	if('03' == $month) $month = MARZO;
	if('04' == $month) $month = ABRIL;
	if('05' == $month) $month = MAYO;
	if('06' == $month) $month = JUNIO;
	if('07' == $month) $month = JULIO;
	if('08' == $month) $month = AGOSTO;
	if('09' == $month) $month = SEPTIEMBRE;
	if('10' == $month) $month = OCTUBRE;
	if('11' == $month) $month = NOVIEMBRE;
	if('12' == $month) $month = DICIEMBRE;

	$data = $day."  ".$month." de ".$year;
	return $data;
}
//Traduce el $limite->dia_venda del ingles
function trans_nombre_dia_venta(){
	$sqlDiaHoraLimite='SELECT dia_venda FROM config';
	$resultDiaHoraLimite=mysql_query($sqlDiaHoraLimite) or die(mysql_error());
	$limite=mysql_fetch_object($resultDiaHoraLimite);
	$nombresDias=array();
	$nombresDias['Monday']=LUNES;
	$nombresDias['Tuesday']=MARTES;
	$nombresDias['Wednesday']=MIERCOLES;
	$nombresDias['Thursday']=JUEVES;
	$nombresDias['Friday']=VIERNES;
	$nombresDias['Saturday']=SABADO;
	$nombresDias['Sunday']=DOMINGO;
	return $nombresDias[$limite->dia_venda];
}
//Devuelve el string $data_abans.'!'.$data_limit.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
function diasLimiteVenta(){
	$sqlDiaHoraLimite='SELECT dia_limit,dia_venda,hora_limit FROM config';
	$resultDiaHoraLimite=mysql_query($sqlDiaHoraLimite) or die(mysql_error());
	$limite=mysql_fetch_object($resultDiaHoraLimite);
	//Ultimo dia de venta
	$dia_a = date('d',strtotime('last '.$limite->dia_venda));
	$mes_a = date('m',strtotime('last '.$limite->dia_venda));
	$any_a = date('Y',strtotime('last '.$limite->dia_venda));
	$data_abans = $any_a.'-'.$mes_a.'-'.$dia_a;
	//Dia limite para hacer la comanda
	$dia = date('d',strtotime($limite->dia_limit));
	$mes = date('m',strtotime($limite->dia_limit));
	$any = date('Y',strtotime($limite->dia_limit));
	$data_limit = $any.'-'.$mes.'-'.$dia;
	//Proximo dia de venta
	$dia_v = date('d',strtotime($limite->dia_venda));
	$mes_v = date('m',strtotime($limite->dia_venda));
	$any_v = date('Y',strtotime($limite->dia_venda));
	$proxDiaVenta = $any_v.'-'.$mes_v.'-'.$dia_v;
	//WHERE data BETWEEN '".$data_abans."' AND '".$data_limit."'";
	return $data_abans.'!'.$data_limit.'!'.$proxDiaVenta;
}
//Devuelve el string $hoy.'!'.$ultimoDiaLimite.'!'.$proxDiaVenta listo para hacerle un explode('!',$string);
//Para evitar que se hagan pedidos entre el dia limite y el dia de venta incluido, ya que los pedidos se obtienen desde el dia siguiente al dia de venta, hasta el dia limite.
//Permite que se autobloquee el apartado pedido en el panel de usuarix. (Es fijo e independiente del bloqueo manual).
function diasAutoBloqueoPanelPedido(){
	$sqlDiaHoraLimite='SELECT dia_limit,dia_venda,hora_limit FROM config';
	$resultDiaHoraLimite=mysql_query($sqlDiaHoraLimite) or die(mysql_error());
	$limite=mysql_fetch_object($resultDiaHoraLimite);
	//Fecha actual
	$dia=date('d');
	$mes=date('m');
	$any=date('Y');
	$hoy=$any.$mes.$dia;
	//Ultimo dia limite para hacer el pedido
	$dia_l=date('d',strtotime('last '.$limite->dia_limit));
	$mes_l=date('m',strtotime('last '.$limite->dia_limit));
	$any_l=date('Y',strtotime('last '.$limite->dia_limit));
	$ultimoDiaLimite=$any_l.$mes_l.$dia_l;
	$ultimoDiaLimiteJuliano=gregoriantojd($mes_l,$dia_l,$any_l);
	//Proximo dia de venta
	$dia_v=date('d',strtotime($limite->dia_venda));
	$mes_v=date('m',strtotime($limite->dia_venda));
	$any_v=date('Y',strtotime($limite->dia_venda));
	$proxDiaVenta=$any_v.$mes_v.$dia_v;
	$proxDiaVentaJuliano=gregoriantojd($mes_v,$dia_v,$any_v);
	if($proxDiaVentaJuliano-$ultimoDiaLimiteJuliano>7){
		$dia_v=date('d',strtotime('last '.$limite->dia_venda));
		$mes_v=date('m',strtotime('last '.$limite->dia_venda));
		$any_v=date('Y',strtotime('last '.$limite->dia_venda));
		$proxDiaVenta=$any_v.$mes_v.$dia_v;
	}
	return $hoy.'!'.$ultimoDiaLimite.'!'.$proxDiaVenta;
}
//Devuelve la configuracion del monedero
function configMonedero(){
	$limitComandaSaldo='off';
	$sqlLimitComandaSaldo='SELECT limit_comanda_saldo FROM config';
	$resultLimitComandaSaldo=mysql_query($sqlLimitComandaSaldo) or die(mysql_error());
	if(mysql_num_rows($resultLimitComandaSaldo)>0){
		$configMonedero=mysql_fetch_object($resultLimitComandaSaldo);
		$limitComandaSaldo=$configMonedero->limit_comanda_saldo;
	}
	return $limitComandaSaldo;
}
function panelPedidoActivo(){
	$activo=1;
	$sqlPanelPedidoActivo='SELECT panel_pedido_activo FROM config';
	$resultPanelPedidoActivo=mysql_query($sqlPanelPedidoActivo) or die(mysql_error());
	if(mysql_num_rows($resultPanelPedidoActivo)>0){
		$filaPanelPedidoActivo=mysql_fetch_object($resultPanelPedidoActivo);
		$activo=$filaPanelPedidoActivo->panel_pedido_activo;
	}
	return $activo;
}
function error($textoError){
	return '<div align="center" style="background-color: #FFE6E6; border-bottom: 1px solid #DF7B7B; border-top: 1px solid #DF7B7B; margin: 15px 0; padding: 10px 15px;">
				<b>'.$textoError.'</b>
			</div>';
}
function correcto($textoCorrecto){
	return '<div align="center" style="background-color: #CCFFCD; border-bottom: 1px solid #019609; border-top: 1px solid #019609; margin: 15px 0; padding: 10px 15px;">
				<b>'.$textoCorrecto.'</b>
			</div>';
}
function separacion(){
	return '<div align="center" style="background-color: #FFFFFF; border-bottom: 1px solid #FFFFFF; border-top: 1px solid #FFFFFF; margin: 15px 0; padding: 10px 15px;">
				<b>&nbsp;</b>
			</div>';
}
function muestraSelectorIdioma(){
	return '<div id="selectorIdiomas">
				<form method="post" action="" id="formIdiomas">
					<table width="95%" border="0" cellpadding="0" cellspacing="0" align="right">
						<tr>
							<td width="34%" align="right"><h6>'.TRADUCIR.'</h6></td>
							<td width="33%" align="center"><input style="margin-left:15px; border: 1px solid black;" class="lang" id="idioma" name="ca" type="submit" value="'.VALENCIANO.'" /></td>
							<td width="33%" align="center"><input style="margin-left:15px; border: 1px solid black;" class="lang" id="idioma" name="es" type="submit" value="'.CASTELLANO.'" /></td>
						</tr>
					</table>
				</form>
			</div>';
}
?>