<?php
/**
* Rellena el array $productosConCantidades con el resultado de la consulta de historial, ya sea el historial completo o parametrizado
* por el usuario. También se contempla el caso de error al introducir los parámetros de consulta.
* Incluido desde /admin/historial.php
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
$consultar='';
$errorDatosForm=false;
$algunParametro=false;
if(isset($_POST['consultar']) || (isset($_POST['mode']) && $_POST['mode']=='consultar')){ //Consulta parametrizada por el usuario
	$consultar=CONSULTAR;
	$modo='consultar';
	//Recogemos los datos introducidos
	$productorForm='';
	if(isset($_POST['productor']) && $_POST['productor']!='' && $_POST['productor']!='noSeleccionado'){ //id productor
		$productorForm=mysql_real_escape_string($_POST['productor']);
		$algunParametro=true;
	}
	$socixForm='';
	if(isset($_POST['socix']) && $_POST['socix']!=''){ //'si', 'no', 'todos'
		$socixForm=addslashes(strip_tags($_POST['socix']));
		if($socixForm=='si' || $socixForm=='no')
			$algunParametro=true;
	}
	$localidadForm='';
	if(isset($_POST['localidad']) && $_POST['localidad']!='' && $_POST['localidad']!='noSeleccionado'){ //string
		$localidadForm=mysql_real_escape_string($_POST['localidad']);
		$algunParametro=true;
	}
	$categoriaForm='';
	if(isset($_POST['categoria']) && $_POST['categoria']!='' && $_POST['categoria']!='noSeleccionado'){ //id categoria
		$categoriaForm=mysql_real_escape_string($_POST['categoria']);
		$algunParametro=true;
	}
	$productoForm='';
	if(isset($_POST['producto']) && $_POST['producto']!='' && $_POST['producto']!='noSeleccionado'){ //string
		$productoForm=mysql_real_escape_string($_POST['producto']);
		$algunParametro=true;
	}
	$activoForm='';
	if(isset($_POST['activo']) && $_POST['activo']!=''){ //'si', 'no', 'todos'
		$activoForm=addslashes(strip_tags($_POST['activo']));
		if($activoForm=='si' || $activoForm=='no')
			$algunParametro=true;
	}

	//Rangos
	$precioIniForm='';
	if(isset($_POST['precioIni']) && $_POST['precioIni']!=''){ //Precio actual minimo
		$precioIniForm=addslashes(strip_tags(trim($_POST['precioIni'])));
		$algunParametro=true;
	}
	$precioFinForm='';
	if(isset($_POST['precioFin']) && $_POST['precioFin']!=''){ //Precio actual maximo
		$precioFinForm=addslashes(strip_tags(trim($_POST['precioFin'])));
		$algunParametro=true;
	}
	$fechaIniForm='';
	if(isset($_POST['fechaIni']) && $_POST['fechaIni']!=''){ //Fecha inicio
		$fechaIniForm=addslashes(strip_tags(trim($_POST['fechaIni'])));
		$fechaIniArray=array();
		$fechaIniArray=explode('/',$fechaIniForm);
		$algunParametro=true;
	}
	$fechaFinForm='';
	if(isset($_POST['fechaFin']) && $_POST['fechaFin']!=''){ //Fecha fin
		$fechaFinForm=addslashes(strip_tags(trim($_POST['fechaFin'])));
		$fechaFinArray=array();
		$fechaFinArray=explode('/',$fechaFinForm);
		$algunParametro=true;
	}
	$cantidadIniForm='';
	if(isset($_POST['cantidadIni']) && $_POST['cantidadIni']!=''){ //Cantidad total conjunta inicio
		$cantidadIniForm=addslashes(strip_tags(trim($_POST['cantidadIni'])));
		$algunParametro=true;
	}
	$cantidadFinForm='';
	if(isset($_POST['cantidadFin']) && $_POST['cantidadFin']!=''){ //Cantidad total conjunta fin
		$cantidadFinForm=addslashes(strip_tags(trim($_POST['cantidadFin'])));
		$algunParametro=true;
	}
	$gastoTotalPedidoIniForm='';
	if(isset($_POST['gastoTotalPedidoIni']) && $_POST['gastoTotalPedidoIni']!=''){ //Gasto total conjunto inicio
		$gastoTotalPedidoIniForm=addslashes(strip_tags(trim($_POST['gastoTotalPedidoIni'])));
		$algunParametro=true;
	}
	$gastoTotalPedidoFinForm='';
	if(isset($_POST['gastoTotalPedidoFin']) && $_POST['gastoTotalPedidoFin']!=''){ //Gasto total conjunto fin
		$gastoTotalPedidoFinForm=addslashes(strip_tags(trim($_POST['gastoTotalPedidoFin'])));
		$algunParametro=true;
	}	

	//Comprobamos los datos introducidos
	//Rango precios
	$errorPrecioIniForm=false;
	if($precioIniForm!='' && !is_numeric($precioIniForm)){
		$errorDatosForm=true;
		$errorPrecioIniForm=true;
		$precioIniForm='';
	}
	$errorPrecioFinForm=false;
	if($precioFinForm!='' && !is_numeric($precioFinForm)){
		$errorDatosForm=true;
		$errorPrecioFinForm=true;
		$precioFinForm='';
	}
	if(!$errorPrecioIniForm && !$errorPrecioFinForm){
		if($precioIniForm!='' && $precioFinForm!='' && $precioIniForm>$precioFinForm){
			$errorDatosForm=true;
			$errorPrecioIniForm=true;
			$errorPrecioFinForm=true;
			$precioIniForm='';
			$precioFinForm='';
		}
	}
	//Rango fechas
	$errorFechaIniForm=false;
	$fechaIniFormMostrar='';
	if($fechaIniForm!=''){
		if(count($fechaIniArray)!=3){
			$errorDatosForm=true;
			$errorFechaIniForm=true;
			$fechaIniForm='';
		}else{
			$fechaIniFormMostrar=$fechaIniForm;
			$fechaIniForm=$fechaIniArray[2].'-'.$fechaIniArray[1].'-'.$fechaIniArray[0];
		}
	}
	$errorFechaFinForm=false;
	$fechaFinFormMostrar='';
	if($fechaFinForm!=''){
		if(count($fechaFinArray)!=3){
			$errorDatosForm=true;
			$errorFechaFinForm=true;
			$fechaFinForm='';
		}else{
			$fechaFinFormMostrar=$fechaFinForm;
			$fechaFinForm=$fechaFinArray[2].'-'.$fechaFinArray[1].'-'.$fechaFinArray[0];
		}
	}
	if(!$errorFechaIniForm && !$errorFechaFinForm){
		if($fechaIniForm!='' && !strtotime($fechaIniForm)){
			$errorDatosForm=true;
			$errorFechaIniForm=true;
			$fechaIniFormMostrar='';
			$fechaIniForm='';
		}
		if($fechaFinForm!='' && !strtotime($fechaFinForm)){
			$errorDatosForm=true;
			$errorFechaFinForm=true;
			$fechaFinFormMostrar='';
			$fechaFinForm='';
		}
		if(!$errorFechaIniForm && !$errorFechaFinForm){
			if($fechaIniForm!='' && $fechaFinForm!='' && strtotime($fechaIniForm)>strtotime($fechaFinForm)){
				$errorDatosForm=true;
				$errorFechaIniForm=true;
				$errorFechaFinForm=true;
				$fechaIniFormMostrar='';
				$fechaFinFormMostrar='';
				$fechaIniForm='';
				$fechaFinForm='';
			}
		}
	}
	//Rango cantidades conjuntas
	$errorCantidadIniForm=false;
	if($cantidadIniForm!='' && !is_numeric($cantidadIniForm)){
		$errorDatosForm=true;
		$errorCantidadIniForm=true;
		$cantidadIniForm='';
	}
	$errorCantidadFinForm=false;
	if($cantidadFinForm!='' && !is_numeric($cantidadFinForm)){
		$errorDatosForm=true;
		$errorCantidadFinForm=true;
		$cantidadFinForm='';
	}
	if(!$errorCantidadIniForm && !$errorCantidadFinForm){
		if($cantidadIniForm!='' && $cantidadFinForm!='' && $cantidadIniForm>$cantidadFinForm){
			$errorDatosForm=true;
			$errorCantidadIniForm=true;
			$errorCantidadFinForm=true;
			$cantidadIniForm='';
			$cantidadFinForm='';
		}
	}
	//Rango gastos conjuntos
	$errorGastoTotalPedidoIniForm=false;
	if($gastoTotalPedidoIniForm!='' && !is_numeric($gastoTotalPedidoIniForm)){
		$errorDatosForm=true;
		$errorGastoTotalPedidoIniForm=true;
		$gastoTotalPedidoIniForm='';
	}
	$errorGastoTotalPedidoFinForm=false;
	if($gastoTotalPedidoFinForm!='' && !is_numeric($gastoTotalPedidoFinForm)){
		$errorDatosForm=true;
		$errorGastoTotalPedidoFinForm=true;
		$gastoTotalPedidoFinForm='';
	}
	if(!$errorGastoTotalPedidoIniForm && !$errorGastoTotalPedidoFinForm){
		if($gastoTotalPedidoIniForm!='' && $gastoTotalPedidoFinForm!='' && $gastoTotalPedidoIniForm>$gastoTotalPedidoFinForm){
			$errorDatosForm=true;
			$errorGastoTotalPedidoIniForm=true;
			$errorGastoTotalPedidoFinForm=true;
			$gastoTotalPedidoIniForm='';
			$gastoTotalPedidoFinForm='';
		}
	}

	//Si todos los datos introducidos son correctos generamos la consulta parametrizada
	if(!$errorDatosForm){
		//Obtenemos todos los productos
		$productes=array();
		$consulta='SELECT id FROM producte ORDER BY id_productor,nom_producte ASC';
		$resultat=mysql_query($consulta) or die(mysql_error());
		while($producte=mysql_fetch_object($resultat)){
			$productes[]["id"]=$producte->id;
		}
		//Filtramos los pedidos primero por rango fechas, si lo hay, si no todos
		$consulta='SELECT t_producte,t_quantitat,t_pvp FROM comanda';
		if($fechaIniForm!='' || $fechaFinForm!=''){
			if($fechaIniForm=='' && $fechaFinForm!='') $fechaIniForm=$fechaFinForm;
			if($fechaFinForm=='' && $fechaIniForm!='') $fechaFinForm=$fechaIniForm;
			$consulta.=' WHERE data BETWEEN "'.$fechaIniForm.'" AND "'.$fechaFinForm.'"';
		}
		$resultat=mysql_query($consulta) or die(mysql_error());
		while($comanda=mysql_fetch_object($resultat)){
			$t_producte=unserialize($comanda->t_producte);
			$t_quantitat=unserialize($comanda->t_quantitat);
			$t_pvp=unserialize($comanda->t_pvp);
			for($j=0; $j<count($t_producte); $j++){
				for($i=0; $i<count($productes); $i++){
					if($t_producte[$j]==$productes[$i]["id"]){
						$productes[$i]["quantitat"]+=$t_quantitat[$j];
						$productes[$i]["gasto"]+=(round(($t_pvp[$j]*$t_quantitat[$j])*100)/100);
					}else{
						$productes[$i]["quantitat"]+=0;
						$productes[$i]["gasto"]+=0;
					}
				}
			}
		}
		$productosConCantidades=array();
		for($i=0,$j=0;$i<count($productes);$i++){ //Hacemos una copia del array $productes, pero solo con los elementos con cantidad>0
			if($productes[$i]['quantitat']!==0){
				$productosConCantidades[$j]['id']=$productes[$i]['id'];
				$productosConCantidades[$j]['quantitat']=$productes[$i]['quantitat'];
				$productosConCantidades[$j]['gasto']=$productes[$i]['gasto'];
				$j++;
			}
		}
		//Filtro por rango cantidades conjuntas
		if($cantidadIniForm!='' || $cantidadFinForm!=''){
			if($cantidadIniForm=='' && $cantidadFinForm!='') $cantidadIniForm=$cantidadFinForm;
			if($cantidadFinForm=='' && $cantidadIniForm!='') $cantidadFinForm=$cantidadIniForm;
			$productosTemp=array();
			for($i=0,$j=0;$i<count($productosConCantidades);$i++){ //Hacemos una copia del array
				$productosTemp[$j]['id']=$productosConCantidades[$i]['id'];
				$productosTemp[$j]['quantitat']=$productosConCantidades[$i]['quantitat'];
				$productosTemp[$j]['gasto']=$productosConCantidades[$i]['gasto'];
				$j++;
			}
			unset($productosConCantidades);
			$productosConCantidades=array();
			for($i=0,$j=0;$i<count($productosTemp);$i++){ //Hacemos una copia del array, filtrando por cantidades conjuntas por producto
				if($productosTemp[$i]['quantitat']>=$cantidadIniForm && $productosTemp[$i]['quantitat']<=$cantidadFinForm){
					$productosConCantidades[$j]['id']=$productosTemp[$i]['id'];
					$productosConCantidades[$j]['quantitat']=$productosTemp[$i]['quantitat'];
					$productosConCantidades[$j]['gasto']=$productosTemp[$i]['gasto'];
					$j++;
				}
			}
		}
		//Filtro por rango gastos conjuntos
		if($gastoTotalPedidoIniForm!='' || $gastoTotalPedidoFinForm!=''){
			if($gastoTotalPedidoIniForm=='' && $gastoTotalPedidoFinForm!='') $gastoTotalPedidoIniForm=$gastoTotalPedidoFinForm;
			if($gastoTotalPedidoFinForm=='' && $gastoTotalPedidoIniForm!='') $gastoTotalPedidoFinForm=$gastoTotalPedidoIniForm;
			unset($productosTemp);
			$productosTemp=array();
			for($i=0,$j=0;$i<count($productosConCantidades);$i++){ //Hacemos una copia del array
				$productosTemp[$j]['id']=$productosConCantidades[$i]['id'];
				$productosTemp[$j]['quantitat']=$productosConCantidades[$i]['quantitat'];
				$productosTemp[$j]['gasto']=$productosConCantidades[$i]['gasto'];
				$j++;
			}
			unset($productosConCantidades);
			$productosConCantidades=array();
			for($i=0,$j=0;$i<count($productosTemp);$i++){ //Hacemos una copia del array, filtrando por gasto conjunto por producto
				if($productosTemp[$i]['gasto']>=$gastoTotalPedidoIniForm && $productosTemp[$i]['gasto']<=$gastoTotalPedidoFinForm){
					$productosConCantidades[$j]['id']=$productosTemp[$i]['id'];
					$productosConCantidades[$j]['quantitat']=$productosTemp[$i]['quantitat'];
					$productosConCantidades[$j]['gasto']=$productosTemp[$i]['gasto'];
					$j++;
				}
			}
		}
		unset($productosTemp);
		$productosTemp=array();
		for($i=0,$j=0;$i<count($productosConCantidades);$i++){ //Hacemos una copia del array
			$productosTemp[$j]['id']=$productosConCantidades[$i]['id'];
			$productosTemp[$j]['quantitat']=$productosConCantidades[$i]['quantitat'];
			$productosTemp[$j]['gasto']=$productosConCantidades[$i]['gasto'];
			$j++;
		}
		//El resto de filtros
		unset($productosConCantidades);
		$productosConCantidades=array();
		for($i=0,$j=0;$i<count($productosTemp);$i++){
			$consulta='SELECT t2.id AS id_producto FROM productors AS t1, producte AS t2 WHERE t1.id=t2.id_productor AND t2.id="'.$productosTemp[$i]['id'].'"';
			if($productorForm!='')
				$consulta.=' AND t1.id="'.$productorForm.'"';
			if($socixForm!=''){
				switch($socixForm){
					case 'si':
						$consulta.=' AND socis="1"';
					break;
					case 'no':
						$consulta.=' AND socis="0"';
					break;
				}
			}
			if($localidadForm!='')
				$consulta.=' AND localitat="'.$localidadForm.'"';
			if($categoriaForm!='')
				$consulta.=' AND id_categoria="'.$categoriaForm.'"';
			if($productoForm!='')
				$consulta.=' AND nom_producte="'.$productoForm.'"';
			if($activoForm!=''){
				switch($activoForm){
					case 'si':
						$consulta.=' AND actiu="1"';
					break;
					case 'no':
						$consulta.=' AND actiu="0"';
					break;
				}
			}
			if($precioIniForm!='' || $precioFinForm!=''){
				if($precioIniForm=='' && $precioFinForm!='') $precioIniForm=$precioFinForm;
				if($precioFinForm=='' && $precioIniForm!='') $precioFinForm=$precioIniForm;
				$consulta.=' AND preu BETWEEN "'.$precioIniForm.'" AND "'.($precioFinForm+0.1).'"';
			}
			if($resultat=mysql_query($consulta)){
				$producte=mysql_fetch_object($resultat);
				//Recogemos en el array $productosConCantidades el resultado final de la consulta
				if($producte->id_producto==$productosTemp[$i]['id']){
					$productosConCantidades[$j]['id']=$productosTemp[$i]['id'];
					$productosConCantidades[$j]['quantitat']=$productosTemp[$i]['quantitat'];
					$productosConCantidades[$j]['gasto']=$productosTemp[$i]['gasto'];
					$j++;
				}
			}
		}
	}
}elseif(isset($_POST['consultarTodo']) || (isset($_POST['mode']) && $_POST['mode']=='consultarTodo')){ //Consulta todo el historial
	$consultar=CONSULTAR_TODO_HISTORIAL;
	$modo='consultarTodo';
	//Obtenemos todos los productos
	$productes=array();
	$consulta='SELECT id FROM producte ORDER BY id_productor,nom_producte ASC';
	$resultat=mysql_query($consulta) or die(mysql_error());
	while($producte=mysql_fetch_object($resultat)){
		$productes[]["id"]=$producte->id;
	}
	$consulta='SELECT t_producte,t_quantitat,t_pvp FROM comanda';
	$resultat=mysql_query($consulta) or die(mysql_error());
	while($comanda=mysql_fetch_object($resultat)){
		$t_producte=unserialize($comanda->t_producte);
		$t_quantitat=unserialize($comanda->t_quantitat);
		$t_pvp=unserialize($comanda->t_pvp);
		for($j=0; $j<count($t_producte); $j++){
			for($i=0; $i<count($productes); $i++){
				if($t_producte[$j]==$productes[$i]["id"]){
					$productes[$i]["quantitat"]+=$t_quantitat[$j];
					$productes[$i]["gasto"]+=(round(($t_pvp[$j]*$t_quantitat[$j])*100)/100);
				}else{
					$productes[$i]["quantitat"]+=0;
					$productes[$i]["gasto"]+=0;
				}
			}
		}
	}
	//Recogemos en el array $productosConCantidades el resultado final de la consulta
	$productosConCantidades=array();
	for($i=0,$j=0;$i<count($productes);$i++){ //Hacemos una copia del array $productes, pero solo con los elementos con cantidad>0
		if($productes[$i]['quantitat']!==0){
			$productosConCantidades[$j]['id']=$productes[$i]['id'];
			$productosConCantidades[$j]['quantitat']=$productes[$i]['quantitat'];
			$productosConCantidades[$j]['gasto']=$productes[$i]['gasto'];
			$j++;
		}
	}
}
?>