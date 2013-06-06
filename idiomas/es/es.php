<?php
/**
* PHP version 5
*
* @author     Maistanet.com <info@maistanet.com> i Atornallom - Cooperativa Integral Valenciana - http://atornallom.net
* @copyright  2012 Maistanet.com i Atornallom - Cooperativa Integral Valenciana - http://atornallom.net
* @package    xarxa
* @license    http://www.gnu.org/copyleft/gpl.html  Distributed under the General Public License (GPL) 
* @version    1.1
* @link       https://github.com/maistanet/xarxesconsum
*/
//CONSTANTES DE TEXTOS DE LA INTERFAZ
//================================================================================================================================================
//PANEL ADMINISTRADOR
//================================================================================================================================================
//************************************************************************************************************************************************
//BOTONES
//************************************************************************************************************************************************
define('BAJA','Baja');
define('CONSULTAR','Consultar');
define('CONSULTAR_TODO_HISTORIAL','Todo el historial');
//************************************************************************************************************************************************
//CAMPOS
//************************************************************************************************************************************************
define('ACTIVO_NO','No');
define('ACTIVO_SI','Si');
define('ACTIVO_TODOS','Todos');
define('CANTIDAD_FIN','Cantidad total conjunta por producto m&aacute;xima');
define('CANTIDAD_INI','Cantidad total conjunta por producto m&iacute;nima');
define('DIA_LIMITE_PEDIDO','D&iacute;a l&iacute;mite pedido');
define('DIA_VENTA','D&iacute;a venta');
define('FECHA_FIN','Fecha fin');
define('FECHA_INI','Fecha inicio');
define('GASTO_TOTAL_PEDIDO_FIN','Gasto total conjunto por producto m&aacute;ximo');
define('GASTO_TOTAL_PEDIDO_INI','Gasto total conjunto por producto m&iacute;nimo');
define('HORA_LIMITE_PEDIDO','Hora l&iacute;mite (00:00:00)');
define('MONEDERO','¿Limitar pedidos seg&uacute;n saldo?');
define('MONEDERO_ACTIVADO_CON_LIMITE','Si');
define('MONEDERO_ACTIVADO_SIN_LIMITE','No');
define('MONEDERO_DESACTIVADO','Desactivado');
define('NUM_ORDEN','N&ordm;');
define('POSICION','Posici&oacute;n');
define('PRECIO_FIN','Precio actual m&aacute;ximo');
define('PRECIO_INI','Precio actual m&iacute;nimo');
define('SELECCIONA_CATEGORIA','--- Selecciona categor&iacute;a ---');
define('SELECCIONA_LOCALIDAD','--- Selecciona localidad ---');
define('SELECCIONA_PRODUCTO','--- Selecciona producto ---');
define('SELECCIONA_PRODUCTORX','--- Selecciona productorx ---');
define('SERVIDOR_SMTP','Servidor smtp');
define('SOCIXS','Socio/a');
define('SOCIXS_NO','No');
define('SOCIXS_SI','Si');
define('SOCIXS_TODXS','Todxs');
define('TEXTO_CABECERA_CORREO_RED','Texto base, cabecera');
define('TEXTO_PORTADA_RED','Texto de la portada de la red');
//************************************************************************************************************************************************
//MENSAJES
//************************************************************************************************************************************************
define('ANTERIOR','Anterior');
define('ANYADIR_CATEGORIA','A&ntilde;ade categor&iacute;a');
define('ANYADIR_NUEVA_CATEGORIA','A&ntilde;ade una nueva categor&iacute;a');
define('ANYADIR_NUEVO_PRODUCTO','A&ntilde;ade un nuevo producto');
define('ANYADIR_NUEVO_PRODUCTORX','A&ntilde;ade un/a nuevo/a productor/a');
define('ANYADIR_PRODUCTO','A&ntilde;adir producto');
define('ANYADIR_PRODUCTORX','A&ntilde;adir productor/a');
define('ANYADIR_U_CONSUMO','A&ntilde;ade una nueva unidad de consumo');
define('COMENTARIOS','Comentarios');
define('CONFIG_RED','Configuraci&oacute;n red');
define('CONSULTAR_HISTORIAL','Consultar historial de pedidos');
define('CORREO_RED','Correo red');
define('DAR_DE_ALTA','Dar de alta');
define('ENTER_GUARDAR','Editar + enter para guardar');
define('ELIMINAR','Eliminar');
define('ESCAPE_VACIAR','Escape para campo en blanco');
define('HISTORIAL_COMPLETO','Historial completo');
define('HISTORIAL_PARAMETRIZADO','Historial consultado');
define('HISTORIAL_PARAMETRIZADO_SIN_PARAMETROS','No has especificado ning&uacute;n par&aacute;metro<br />Se muestra todo el historial');
define('HISTORIAL_PEDIDOS','Historial de pedidos');
define('NO_PRODUCTORXS_NI_CATEGORIAS','¡Debe existir al menos una categor&iacute;a y unx productorx para poder a&ntilde;adir productos!');
define('NOMBRE_USUARIO','Nombre de usuario/a');
define('PAG_NUM','P&aacute;g. n&ordm;&nbsp;');
define('SIGUIENTE','Siguiente');
define('SUBIR','Subir');
define('UNIDADES_DE_CONSUMO','Unidades de consumo');
//----------------------
//MENSAJES TIPO PREGUNTA
//----------------------

//-----------------------
//MENSAJES TIPO RESPUESTA
//-----------------------
define('CORRECTO_ANYADIR_CATEGORIA','La categor&iacute;a ha sido a&ntilde;adida correctamente');
define('CORRECTO_ANYADIR_PRODUCTO','El producto ha sido a&ntilde;adido correctamente');
define('CORRECTO_ANYADIR_PRODUCTORX','El/La produtor/a ha sido a&ntilde;adido/a correctamente');
define('CORRECTO_ANYADIR_U_CONSUMO','La unidad de consumo ha sido a&ntilde;adida correctamente');
define('CORRECTO_ELIMINAR_CATEGORIA','La categor&iacute;a se ha eliminado correctamente');
define('CORRECTO_ELIMINAR_PRODUCTO','El producto se ha eliminado correctamente');
define('CORRECTO_ELIMINAR_PRODUCTORX','El/La produtor/a y todos sus productos se han eliminado correctamente');
define('CORRECTO_ELIMINAR_U_CONSUMO','La unidad de consumo se ha eliminado correctamente');
define('CORRECTO_MODIF_AVISOS','Los avisos han sido modificados correctamente');
define('CORRECTO_MODIF_PRODUCTO','El producto ha sido modificado correctamente');
define('CORRECTO_MODIF_PRODUCTORX','El/La produtor/a ha sido modificado/modificada correctamente');
define('ERROR_DATOS_INTRODUCIDOS','<b>Comprueba los datos introducidos</b>');
define('ERROR_EXISTE_EMAIL','<b>Ya existe unx usuarix con este email</b>');
define('ERROR_EXISTE_PAR_EMAIL_CONTRASENYA','<b>Ya existe otrx usuarix con mismo email y misma contrase&ntilde;a. Por favor introduce una contrase&ntilde;a distinta.</b>');
define('ERROR_SIN_RESULTADOS','Sin resultados');
//-------------------------
//MENSAJES TIPO DESCRIPCION
//-------------------------
define('BIENVENIDX_ADMIN','A la faena...');
define('BLOQUEAR_PANEL_PEDIDO','Marca esta casilla para <b>bloquear el apartado "pedido" en el panel de usuarix</b>, por motivos de mantenimiento, o si esta semana no os toca pedir, etc...<br />
			Puedes acompa&ntilde;ar el bloqueo, con un aviso explicando el motivo a tus compa&ntilde;erxs.');
define('CATEGORIA_TIENE_PRODUCTOS_INI','La categor&iacute;a ');
define('CATEGORIA_TIENE_PRODUCTOS_FIN',' tiene productos asociados.<br />Has de cambiarles la categor&iacute;a para poder eliminarla.');
define('ELIMINAR_CATEGORIA','Eliminar categor&iacute;a');
define('MODIFICAR_CONFIG','Modificar configuraci&oacute;n');
define('MODIFICAR_CORREO','Modificar correo');
define('NOTA_OPCIONES_MONEDERO','(Opciones de limitar pedidos seg&uacute;n saldo:<br />
			- Desactivado: El "monedero" quedar&aacute; desactivado.<br />
			- Si: Limitar&aacute; el pedido al saldo de la unidad de consumo.<br />
			- No: No limitar&aacute; el pedido al saldo. Contar&aacute; la deuda de la unidad de consumo en n&uacute;meros negativos)');
define('NOTA_PRECIOS','(Nota: Los precios siempre son por unidades o Kg.<br />
			El formato seleccionado indicar&aacute; la cadencia al sumar o restar la cantidad deseada de producto al hacer el pedido.)');
define('NOTAS','Notas');
//define('OBTENER_PDF_HISTORIAL','Obtener resultado en formato pdf');
define('PEDIDOS_U_CONSUMO','Pedidos unidades de consumo');
define('PROXIMA_SEMANA_INI','Semana');
define('PROXIMA_SEMANA_FIN','siguiente');
define('PUEDES_ELIMINAR_CATEGORIA','Ahora puedes eliminar la categor&iacute;a ');
define('SEMANA','Semana');
//---------------------------------------------------------
//MENSAJES CORREO CONFIRMACION DE ALTA DE UNIDAD DE CONSUMO
//---------------------------------------------------------
define('CORREO_ALTA_BODY_1','Bienvenido/a ');
define('CORREO_ALTA_BODY_2','ahora ya est&aacute;s dado/dada de alta en el grupo de consumo ');
define('CORREO_ALTA_BODY_3','Para entrar en la web ');
define('CORREO_ALTA_BODY_4','xarxesconsum');
define('CORREO_ALTA_BODY_5',' te har&aacute;n falta los siguientes datos:');
define('CORREO_ALTA_BODY_6','Email: ');
define('CORREO_ALTA_BODY_7','Contrase&ntilde;a: ');
define('CORREO_ALTA_BODY_8','Un cordial saludo');
define('CORREO_ALTA_ASUNTO','Alta en el grupo de consumo ');
//---------------------------------------------------------
//MENSAJES CORREO CONFIRMACION DE BAJA DE UNIDAD DE CONSUMO
//---------------------------------------------------------
define('CORREO_BAJA_BODY_1','Hola, ');
define('CORREO_BAJA_BODY_2','te hemos dado de baja del grupo de consumo ');
define('CORREO_BAJA_BODY_3','Cualquier duda responde a este correo.');
define('CORREO_BAJA_BODY_4','Un cordial saludo');
define('CORREO_BAJA_ASUNTO','Baja del grupo de consumo ');
//-----------------------------------------------
//MENSAJES ENVIO AUTOMATICO PEDIDOS A PRODUCTORXS
//-----------------------------------------------
define('AVISO_NO_MAIL_PRODUCTORX','no tiene email. NO SE LE HA ENVIADO EL PEDIDO.');
define('ASUNTO_INI','Pedido del grupo de consumo');
define('ASUNTO_FIN','para el');
define('HOLA','Hola');
define('PEDIDO_DEL_GC','este es el pedido del grupo de consumo');
define('SALUDOS','Un saludo, gracias.');
//************************************************************************************************************************************************
//MENU 
//************************************************************************************************************************************************
define('CATEGORIAS','Categorias');
define('CONFIG','Configuraci&oacute;n');
define('HISTORIAL','Historial');
define('PEDIDO_CONJUNTO','Pedido conjunto');
define('PRODUCTOS','Productos');
define('TOTALES_DE_LOS_PEDIDOS','Totales de los pedidos');
define('TOTALES_PEDIDO','Totales pedido');
define('UNIDADES_C','Unidades C.');
//************************************************************************************************************************************************
//TITULOS
//************************************************************************************************************************************************
//-------------------
//TITULOS EN LISTADOS
//-------------------
define('ACTIVO','Activo');
define('CATEGORIA','Categor&iacute;a');
define('DESCRIPCION','Descripci&oacute;n');
define('FECHA','Fecha');
define('FORMATO','Formato');
define('GRAMOS','Gramos');
define('KILOS','Kilos');
define('LOCALIDAD','Localidad');
define('OPCIONES','Opciones');
define('PRECIO_UNIDAD','Precio unidad');
define('UNIDAD','Unidad');
//================================================================================================================================================
//FINAL PANEL ADMINISTRADOR
//================================================================================================================================================
//************************************************************************************************************************************************
//================================================================================================================================================
//PANEL USUARIOS
//================================================================================================================================================
//************************************************************************************************************************************************
//BOTONES
//************************************************************************************************************************************************
define('CANCELAR_PEDIDO','Cancelar'); //Para ventana javascript al hacer el pedido
define('HACER_PEDIDO','Hacer pedido');
//************************************************************************************************************************************************
//MENSAJES
//************************************************************************************************************************************************
define('PRODUCTORX_SOCIX','Labradores/as socios/as');
define('PRODUCTORX_NO_SOCIX','Labradores/as no socios/as');
define('ENERO','ENERO');
define('FEBRERO','FEBRERO');
define('MARZO','MARZO');
define('ABRIL','ABRIL');
define('MAYO','MAYO');
define('JUNIO','JUNIO');
define('JULIO','JULIO');
define('AGOSTO','AGOSTO');
define('SEPTIEMBRE','SEPTIEMBRE');
define('OCTUBRE','OCTUBRE');
define('NOVIEMBRE','NOVIEMBRE');
define('DICIEMBRE','DICIEMBRE');
//----------------------
//MENSAJES TIPO PREGUNTA
//----------------------
define('PREGUNTA_ES_TU_PEDIDO','¿Este es tu pedido?');
//-----------------------
//MENSAJES TIPO RESPUESTA
//-----------------------
//-------------------------
//MENSAJES TIPO DESCRIPCION
//-------------------------
define('ACEPTAR_CANCELAR_PEDIDO','Si es correcto el pedido, clica Aceptar, sino cancela.');
define('BIENVENIDX_INI','Bienvenido/a');
define('BIENVENIDX_FIN',', a hacer el pedido...');
define('FUERA_DE_TIEMPO_INI','Son m&aacute;s de las ');
define('FUERA_DE_TIEMPO_FIN',', no podr&aacute;s hacer el pedido');
define('PANEL_PEDIDO_AUTOBLOQUEADO','El apartado pedido se autobloquea hasta el d&iacute;a siguiente al d&iacute;a de venta');
define('PANEL_PEDIDO_BLOQUEADO','El apartado pedido est&aacute; bloqueado');
define('PEDIDO_HECHO_EL_INI','Pedido hecho el');
define('PEDIDO_HECHO_EL_FIN',', para el');
define('VER_DESCRIPCION_PRODUCTO','Posa el rat&oacute;n sobre el nombre de los productos para ver su descripci&oacute;n. (Si la tienen).');
//--------------------------------------
//MENSAJES CORREO CONFIRMACION DE PEDIDO
//--------------------------------------
define('ASUNTO_PEDIDO_DEL','Pedido del');
define('MENSAJE_ERROR_NO_MAIL_UC','Si quieres recibir un email de confirmaci&oacute;n de tu pedido, tendr&aacute;s que a&ntilde;adir tu email <a href="dpersonals.php">aqu&iacute;</a>');
//************************************************************************************************************************************************
//MENU 
//************************************************************************************************************************************************
define('D_PERSONALES','Datos personales');
define('PEDIDO','Pedido');
//************************************************************************************************************************************************
//TITULOS
//************************************************************************************************************************************************
//-------------------
//TITULOS EN LISTADOS
//-------------------
define('CANTIDAD','Cantidad');
define('COMENTARIO','Comentario');
define('MAS','m&aacute;s');
define('MENOS','menos');
define('OBSERVACIONES','Observaciones para el/la administrador/a');
define('PRECIO','Precio');
define('PRODUCTO','Producto');
define('PRODUCTORX','Productor/a');
define('UNIDADES','unidad/es');
define('TOTAL','Total');
//================================================================================================================================================
//FINAL PANEL USUARIOS
//================================================================================================================================================
//================================================================================================================================================
//COMUNES
//================================================================================================================================================
//************************************************************************************************************************************************
//BOTONES
//************************************************************************************************************************************************
define('ACCESO_U_CONSUMO','Acceso unidades de consumo');
define('ACEPTAR','Aceptar');
define('CANCELAR','Cancelar');
define('CASTELLANO','Castellano');
define('ENTRAR','Entrar');
define('SALIR','Salir');
define('TRADUCIR','Traducir:');
define('VALENCIANO','Valenci&agrave;');
define('VOLVER','Volver');
//************************************************************************************************************************************************
//CAMPOS
//************************************************************************************************************************************************
define('CORREO','Correo');
define('CONTACTO','Contacto');
define('ELIGE_XARXA','¿Cu&aacute;l es la tuya?');
define('EMAIL','Email');
define('NOMBRE','Nombre');
define('NUM_CAJA','N. caja');
define('PASSWORD','Contrase&ntilde;a');
define('PASSWORD_CONFIRM','Repite contrase&ntilde;a');
define('USUARIO','Usuario');
//************************************************************************************************************************************************
//MENSAJES
//************************************************************************************************************************************************
define('AVISOS','Avisos');
define('AVISOS_U_CONSUMO','Avisos para las unidades de consumo');
define('CARACTERES_NO_PERMITIDOS','*Si te cambias la contrase&ntilde;a, estos car&aacute;cteres no est&aacute;n permitidos:');
define('MODIFICAR','Modificar');
define('PEDIDOS','Pedidos');
define('SALDO','Saldo');
define('U_CONSUM','Unidad de consumo');
define('LUNES','lunes');
define('MARTES','martes');
define('MIERCOLES','mi&eacute;rcoles');
define('JUEVES','jueves');
define('VIERNES','viernes');
define('SABADO','s&aacute;bado');
define('DOMINGO','domingo');
define('SESION_CADUCADA','Tiempo de la sesi\u00F3n agotado. Debes volver a entrar para continuar.'); //Ventana javascript
//-----------------------
//MENSAJES TIPO RESPUESTA
//-----------------------
define('CORRECTO_MODIF_U_CONSUMO','La unidad de consumo ha sido modificada correctamente');
define('ERROR_CARGA_BD','ERROR: No se ha podido conectar con la Base de Datos:');
define('ERROR_LOGIN','Login incorrecto');
define('ERROR_SELEC_BD','ERROR: No se ha podido seleccionar la Base de Datos:');
//************************************************************************************************************************************************
//MENU 
//************************************************************************************************************************************************
define('PRODUCTORXS','Productores/as');
//************************************************************************************************************************************************
//PORTADAS
//************************************************************************************************************************************************
define('TEXTO_PORTADA','<h3>Agrupaci&oacute;n de Redes Agroecol&oacute;gicas y/o de consumo</h3>
			<p>Bienvenido/da a la aplicaci&oacute;n web para grupos de consumo. Esta herramienta web &eacute;s de uso interno para los grupos de consumo y cada uno la gestiona a su manera.</p>
			<p>El c&oacute;digo est&aacute; bajo licencia <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a>, para que sea p&uacute;blico y para poder mejorarlo entre todos/as. Lo pod&eacute;is encontrar aqu&iacute; <a href="https://github.com/maistanet/xarxesconsum">https://github.com/maistanet/xarxesconsum</a>.</p>
			<p>Cualquier donaci&oacute;n o intercambio tambi&eacute;n ser&aacute; bien recibida, gracias i a disfrutar.</p>');
define('AUTORES','<span class="autors">Programado por <a href="http://www.maistanet.com" target="_blank"></span><span id="logopeu"><span class="logomaistanetpeu">maistanet</span><span class="logocompeu">.com</span></span></a><span class="autors">y <a href="http://atornallom.net" target="_blank" style="color: #FFFFFF">Atornallom - Cooperativa Integral Valenciana</a></span>');
define('PAGE_TITLE','Redes agrocol&oacute;gicas'); //Titulo de la web
//================================================================================================================================================
//FINAL COMUNES
//================================================================================================================================================
?>