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
define('BAJA','Baixa');
define('CONSULTAR','Consultar');
define('CONSULTAR_TODO_HISTORIAL','Tot el historial');
//************************************************************************************************************************************************
//CAMPOS
//************************************************************************************************************************************************
define('ACTIVO_NO','No');
define('ACTIVO_SI','Si');
define('ACTIVO_TODOS','Tots');
define('CANTIDAD_FIN','Quantitat total conjunta per producte m&agrave;xima');
define('CANTIDAD_INI','Quantitat total conjunta per producte m&iacute;nima');
define('DIA_LIMITE_PEDIDO','Dia limit comanda');
define('DIA_VENTA','Dia venda');
define('FECHA_FIN','Data fi');
define('FECHA_INI','Data inici');
define('GASTO_TOTAL_PEDIDO_FIN','Despesa total conjunta per producte m&agrave;xima');
define('GASTO_TOTAL_PEDIDO_INI','Despesa total conjunta per producte m&iacute;nima');
define('HORA_LIMITE_PEDIDO','Hora limit (00:00:00)');
define('MONEDERO','¿Limitar comandes segons saldo?');
define('MONEDERO_ACTIVADO_CON_LIMITE','Si');
define('MONEDERO_ACTIVADO_SIN_LIMITE','No');
define('MONEDERO_DESACTIVADO','Desactivat');
define('NUM_ORDEN','N&ordm;');
define('POSICION','Posici&oacute;');
define('PRECIO_FIN','Preu actual m&agrave;xim');
define('PRECIO_INI','Preu actual m&iacute;nim');
define('SELECCIONA_CATEGORIA','--- Selecciona categoria ---');
define('SELECCIONA_LOCALIDAD','--- Selecciona localitat ---');
define('SELECCIONA_PRODUCTO','--- Selecciona producte ---');
define('SELECCIONA_PRODUCTORX','--- Selecciona productorx ---');
define('SERVIDOR_SMTP','Servidor smtp');
define('SOCIXS','Soci/S&ograve;cia');
define('SOCIXS_NO','No');
define('SOCIXS_SI','Si');
define('SOCIXS_TODXS','Tots/es');
define('TEXTO_CABECERA_CORREO_RED','Text base, cap&ccedil;alera');
define('TEXTO_PORTADA_RED','Text de la portada de la xarxa');
//************************************************************************************************************************************************
//MENSAJES
//************************************************************************************************************************************************
define('ANTERIOR','Anterior');
define('ANYADIR_CATEGORIA','Afegeix categoria');
define('ANYADIR_NUEVA_CATEGORIA','Afegeix una nova categoria');
define('ANYADIR_NUEVO_PRODUCTO','Afegeix un nou producte');
define('ANYADIR_NUEVO_PRODUCTORX','Afegeix un/a nou/nova productor/a');
define('ANYADIR_PRODUCTO','Afegir producte');
define('ANYADIR_PRODUCTORX','Afegir productor/a');
define('ANYADIR_U_CONSUMO','Afegeix una nova unitat de consum');
define('COMENTARIOS','Comentaris');
define('CONFIG_RED','Configuraci&oacute; xarxa');
define('CONSULTAR_HISTORIAL','Consultar historial de comandas');
define('CORREO_RED','Correu xarxa');
define('DAR_DE_ALTA','Donar d\'alta');
define('ENTER_GUARDAR','Editar + enter per a guardar');
define('ELIMINAR','Eliminar');
define('ESCAPE_VACIAR','Escape per camp en blanc');
define('HISTORIAL_COMPLETO','Historial complet');
define('HISTORIAL_PARAMETRIZADO','Historial consultat');
define('HISTORIAL_PARAMETRIZADO_SIN_PARAMETROS','No heu especificat cap par&agrave;metre<br />Es mostra tot l\'historial');
define('HISTORIAL_PEDIDOS','Historial de comandas');
define('NO_PRODUCTORXS_NI_CATEGORIAS','Hi ha d\'haver almenys una categoria i unx productorx per poder afegir productes!');
define('NOMBRE_USUARIO','Nom d\'usuari/a');
define('PAG_NUM','P&agrave;g. n&ordm;&nbsp;');
define('SIGUIENTE','Següent');
define('SUBIR','Pujar');
define('UNIDADES_DE_CONSUMO','Unitats de consum');
//----------------------
//MENSAJES TIPO PREGUNTA
//----------------------

//-----------------------
//MENSAJES TIPO RESPUESTA
//-----------------------
define('CORRECTO_ANYADIR_CATEGORIA','La categoria ha sigut desada correctament');
define('CORRECTO_ANYADIR_PRODUCTO','El producte ha sigut desat correctament');
define('CORRECTO_ANYADIR_PRODUCTORX','El/La produtor/a ha sigut desat/desada correctament');
define('CORRECTO_ANYADIR_U_CONSUMO','La unitat de consum ha sigut desada correctament');
define('CORRECTO_ELIMINAR_CATEGORIA','La categoria s\'ha eliminat correctament');
define('CORRECTO_ELIMINAR_PRODUCTO','El producte s\'ha eliminat correctament');
define('CORRECTO_ELIMINAR_PRODUCTORX','El/La produtor/a i tots els seus productes s\'han eliminat correctament');
define('CORRECTO_ELIMINAR_U_CONSUMO','La unitat de consum s\'ha eliminat correctament');
define('CORRECTO_MODIF_AVISOS','Els avisos han sigut modificats correctament');
define('CORRECTO_MODIF_PRODUCTO','El producte ha sigut modificat correctament');
define('CORRECTO_MODIF_PRODUCTORX','El/La produtor/a ha sigut modificat/modificada correctament');
define('ERROR_DATOS_INTRODUCIDOS','<b>Comprova les dades introduïdes</b>');
define('ERROR_EXISTE_EMAIL','<b>Ja existeix unx usuarix amb aquest email</b>');
define('ERROR_EXISTE_PAR_EMAIL_CONTRASENYA','<b>Ja existeix otrx usuarix amb mateix correu electr&ograve;nic i mateixa contrasenya. Si us plau introdueix una contrasenya diferent.</b>');
define('ERROR_SIN_RESULTADOS','Sense resultats');
//-------------------------
//MENSAJES TIPO DESCRIPCION
//-------------------------
define('BIENVENIDX_ADMIN','A la feina...');
define('BLOQUEAR_PANEL_PEDIDO','Marca aquesta casella per <b>bloquejar l\'apartat "comanda" del panel de usuarix</b>, per motius de manteniment, o si aquesta setmana no us toca demanar, etc...<br />
			Pots acompanyar el bloqueig, amb un av&iacute;s explicant el motiu als teus/teves companys/companyes.');
define('CATEGORIA_TIENE_PRODUCTOS_INI','La categoria ');
define('CATEGORIA_TIENE_PRODUCTOS_FIN',' t&eacute; productes associats.<br />Has de canviar-les la categoria per a poder eliminar-la.');
define('ELIMINAR_CATEGORIA','Eliminar categoria');
define('MODIFICAR_CONFIG','Modificar configuraci&oacute;');
define('MODIFICAR_CORREO','Modificar correu');
define('NOTA_OPCIONES_MONEDERO','(Opcions de limitar comandes segons saldo:<br />
			- Desactivat: El "moneder" quedar&agrave; desactivat.<br />
			- Si: Limitar&agrave; la comanda al saldo de la unitat de consum.<br />
			- No: No limitar&agrave; la comanda al saldo. Comptar&agrave; el deute de la unitat de consum en n&uacute;meros negatius)');
define('NOTA_PRECIOS','(Nota: Els preus sempre s&oacute;n per unitats o Kg.<br />
			El format seleccionat indicar&agrave; la cad&egrave;ncia en sumar o restar la quantitat desitjada de producte en fer la comanda.)');
define('NOTAS','Notes');
//define('OBTENER_PDF_HISTORIAL','Obtenir resultat en format pdf');
define('PEDIDOS_U_CONSUMO','Comandes unitats de consum');
define('PROXIMA_SEMANA_INI','Setmana');
define('PROXIMA_SEMANA_FIN','vinent');
define('PUEDES_ELIMINAR_CATEGORIA','Ara pots eliminar la categoria ');
define('SEMANA','Setmana');
//---------------------------------------------------------
//MENSAJES CORREO CONFIRMACION DE ALTA DE UNIDAD DE CONSUMO
//---------------------------------------------------------
define('CORREO_ALTA_BODY_1','Benvingut/da ');
define('CORREO_ALTA_BODY_2','ara ja est&agrave;s donat/donada d\'alta en el grup de consum ');
define('CORREO_ALTA_BODY_3','Per a entrar a la web ');
define('CORREO_ALTA_BODY_4','xarxesconsum');
define('CORREO_ALTA_BODY_5',' et far&agrave; falta les seg&uuml;ents dades:');
define('CORREO_ALTA_BODY_6','Email: ');
define('CORREO_ALTA_BODY_7','Contrasenya: ');
define('CORREO_ALTA_BODY_8','Una cordial salutaci&oacute;');
define('CORREO_ALTA_ASUNTO','Alta en el grup de consum ');
//---------------------------------------------------------
//MENSAJES CORREO CONFIRMACION DE BAJA DE UNIDAD DE CONSUMO
//---------------------------------------------------------
define('CORREO_BAJA_BODY_1','Hola, ');
define('CORREO_BAJA_BODY_2','t\'hem donat/donada de baixa del grup de consum ');
define('CORREO_BAJA_BODY_3','Per a qualsevol dubte respon aquest correu.');
define('CORREO_BAJA_BODY_4','Una cordial salutaci&oacute;');
define('CORREO_BAJA_ASUNTO','Baixa del grup de consum ');
//-----------------------------------------------
//MENSAJES ENVIO AUTOMATICO PEDIDOS A PRODUCTORXS
//-----------------------------------------------
define('AVISO_NO_MAIL_PRODUCTORX','no t&eacute; email. NO SE LI HA ENVIAT LA COMANDA.');
define('ASUNTO_INI','Comanda del grup de consum');
define('ASUNTO_FIN','per al');
define('HOLA','Hola');
define('PEDIDO_DEL_GC','aquesta &eacute;s la comanda del grup de consum');
define('SALUDOS','Una salutaci&oacute;, gr&agrave;cies.');
//************************************************************************************************************************************************
//MENU 
//************************************************************************************************************************************************
define('CATEGORIAS','Categories');
define('CONFIG','Configuraci&oacute;');
define('HISTORIAL','Historial');
define('PEDIDO_CONJUNTO','Comanda conjunta');
define('PRODUCTOS','Productes');
define('TOTALES_DE_LOS_PEDIDOS','Totals de les comandes');
define('TOTALES_PEDIDO','Totals comanda');
define('UNIDADES_C','Unitats C.');
//************************************************************************************************************************************************
//TITULOS
//************************************************************************************************************************************************
//-------------------
//TITULOS EN LISTADOS
//-------------------
define('ACTIVO','Actiu');
define('CATEGORIA','Categoria');
define('DESCRIPCION','Descripci&oacute;');
define('FECHA','Data');
define('FORMATO','Format');
define('GRAMOS','Grams');
define('KILOS','Kilos');
define('LOCALIDAD','Localitat');
define('OPCIONES','Opcions');
define('PRECIO_UNIDAD','Preu unitat');
define('UNIDAD','Unitat');
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
define('HACER_PEDIDO','Fer comanda');
//************************************************************************************************************************************************
//MENSAJES
//************************************************************************************************************************************************
define('PRODUCTORX_SOCIX','Llauradors/es socis/s&ograve;cies');
define('PRODUCTORX_NO_SOCIX','Llauradors/es no socis/s&ograve;cies');
define('ENERO','GENER');
define('FEBRERO','FEBRER');
define('MARZO','MAR&Ccedil;');
define('ABRIL','ABRIL');
define('MAYO','MAIG');
define('JUNIO','JUNY');
define('JULIO','JULIOL');
define('AGOSTO','AGOST');
define('SEPTIEMBRE','SETEMBRE');
define('OCTUBRE','OCTUBRE');
define('NOVIEMBRE','NOVEMBRE');
define('DICIEMBRE','DESEMBRE');
//----------------------
//MENSAJES TIPO PREGUNTA
//----------------------
define('PREGUNTA_ES_TU_PEDIDO','Esta &eacute;s la teua comanda?');
//-----------------------
//MENSAJES TIPO RESPUESTA
//-----------------------
//-------------------------
//MENSAJES TIPO DESCRIPCION
//-------------------------
define('ACEPTAR_CANCELAR_PEDIDO','Si &eacute;s correcta la comanda, clica Acceptar, sin&oacute; cancel&middot;la.');
define('BIENVENIDX_INI','Benvingut/da');
define('BIENVENIDX_FIN',', a fer la comanda...');
define('FUERA_DE_TIEMPO_INI','Son m&eacute;s de les ');
define('FUERA_DE_TIEMPO_FIN',', no podr&agrave;s fer la comanda');
define('PANEL_PEDIDO_AUTOBLOQUEADO','L\'apartat comanda es autobloquejat fins el dia seg&uuml;ent al dia de venda');
define('PANEL_PEDIDO_BLOQUEADO','L\'apartat comanda est&agrave; bloquejat');
define('PEDIDO_HECHO_EL_INI','Comanda feta el');
define('PEDIDO_HECHO_EL_FIN',', per al');
define('VER_DESCRIPCION_PRODUCTO','Posa el cursor sobre el nom dels productes per veure la seua descripci&oacute;. (Si en tenen).');
//--------------------------------------
//MENSAJES CORREO CONFIRMACION DE PEDIDO
//--------------------------------------
define('ASUNTO_PEDIDO_DEL','Comanda del');
define('MENSAJE_ERROR_NO_MAIL_UC','Si vols recibir un email de confirmaci&oacute; de la teua comanda, tendras que anyadir el teu email <a href="dpersonals.php">a&ccedil;i</a>');
//************************************************************************************************************************************************
//MENU 
//************************************************************************************************************************************************
define('D_PERSONALES','Dades personals');
define('PEDIDO','Comanda');
//************************************************************************************************************************************************
//TITULOS
//************************************************************************************************************************************************
//-------------------
//TITULOS EN LISTADOS
//-------------------
define('CANTIDAD','Quantitat');
define('COMENTARIO','Comentari');
define('MAS','mes');
define('MENOS','menys');
define('OBSERVACIONES','Observacions per l\'administrador/a');
define('PRECIO','Preu');
define('PRODUCTO','Producte');
define('PRODUCTORX','Productor/a');
define('UNIDADES','unitat/s');
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
define('ACCESO_U_CONSUMO','Acc&eacute;s unitats de consum');
define('ACEPTAR','Acceptar');
define('CANCELAR','Cancel&middot;lar');
define('CASTELLANO','Castellano');
define('ENTRAR','Entrar');
define('SALIR','Eixir');
define('TRADUCIR','Traduir:');
define('VALENCIANO','Valenci&agrave;');
define('VOLVER','Tornar');
//************************************************************************************************************************************************
//CAMPOS
//************************************************************************************************************************************************
define('CORREO','Correu');
define('CONTACTO','Contacte');
define('ELIGE_XARXA','Quina &eacute;s la teua?');
define('EMAIL','Email');
define('NOMBRE','Nom');
define('NUM_CAJA','N. caixa');
define('PASSWORD','Contrasenya');
define('PASSWORD_CONFIRM','Repeteix la contrasenya');
define('USUARIO','Usuari');
//************************************************************************************************************************************************
//MENSAJES
//************************************************************************************************************************************************
define('AVISOS','Avisos');
define('AVISOS_U_CONSUMO','Avisos per a les unitats de consum');
define('CARACTERES_NO_PERMITIDOS','*Si et canvies la contrasenya, estos car&agrave;cters no estan permessos:');
define('MODIFICAR','Modificar');
define('PEDIDOS','Comandes');
define('SALDO','Saldo');
define('U_CONSUM','Unitat de consum');
define('LUNES','dilluns');
define('MARTES','dimarts');
define('MIERCOLES','dimecres');
define('JUEVES','dijous');
define('VIERNES','divendres');
define('SABADO','dissabte');
define('DOMINGO','diumenge');
define('SESION_CADUCADA','Temps de la sessi\u00F3 esgotat. Has de tornar a entrar per continuar.'); //Ventana javascript
//-----------------------
//MENSAJES TIPO RESPUESTA
//-----------------------
define('CORRECTO_MODIF_U_CONSUMO','La unitat de consum ha sigut modificada correctament');
define('ERROR_CARGA_BD','ERROR: No s\'ha pogut conectar a la Base de Dades:');
define('ERROR_LOGIN','Login incorrecte');
define('ERROR_SELEC_BD','ERROR: No s\'ha pogut seleccionar la Base de Dades:');
//************************************************************************************************************************************************
//MENU 
//************************************************************************************************************************************************
define('PRODUCTORXS','Productors/es');
//************************************************************************************************************************************************
//PORTADAS
//************************************************************************************************************************************************
define('TEXTO_PORTADA','<h3>Agrupaci&oacute; de Xarxes Agroecol&ograve;giques i/o de consum</h3>
			<p>Benvingut/da a l\'aplicaci&oacute; web per a xarxes de consum. Aquesta ferramenta web &eacute;s d\'&uacute;s intern per a les xarxes de consum i cadascuna la gestiona a la seua manera.</p>
			<p>El codi est&agrave; sota llici&egrave;ncia <a href="http://www.gnu.org/copyleft/gpl.html">GPL</a>, per a que siga p&uacute;blic i per poder millorar-lo entre totes. El podeu trobar a <a href="https://github.com/maistanet/xarxesconsum">https://github.com/maistanet/xarxesconsum</a>.</p>
			<p>Qualsevol donaci&oacute; o intercanvi tamb&eacute; ser&agrave; ben rebuda, gr&agrave;cies i a disfrutar.</p>');
define('AUTORES','<span class="autors">Programat per <a href="http://www.maistanet.com" target="_blank"></span><span id="logopeu"><span class="logomaistanetpeu">maistanet</span><span class="logocompeu">.com</span></span></a><span class="autors">i <a href="http://atornallom.net" target="_blank" style="color: #FFFFFF">Atornallom - Cooperativa Integral Valenciana</a></span>');
define('PAGE_TITLE','Xarxes agrocol&ograve;giques'); //Titulo de la web
//================================================================================================================================================
//FINAL COMUNES
//================================================================================================================================================
?>