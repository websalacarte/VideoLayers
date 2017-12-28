<?php
//session_start();
//session_write_close();

// para links, emails, ...
$protocolo_ssl = 'https:';
$protocolo_no_ssl = 'http:';
$ruta_dominio = '//www.websalacarte.com/';
$path_vl = 'videolayers/v0.9/';

$email_sender_notificaciones = 'josep@websalacarte.com';	// v0.9 register, ...
$email_sender_notificaciones_nombre = 'VideoLayers';
$email_sender_notificaciones_noreply = 'info@websalacarte.com';


//var_dump($_SESSION);
//echo $_SERVER['DOCUMENT_ROOT'];
define('DOC_ROOT' , $_SERVER['DOCUMENT_ROOT'] . '/' . $path_vl);
define('DS' , DIRECTORY_SEPARATOR);
define('INC' , DOC_ROOT . 'includes' . DS );

// Conexión DB mysql sencilla (old method)
define('MYSQL_DIR' , DOC_ROOT . 'mysql' . DS );
define('MODELS_DIR' , DOC_ROOT . 'mysql' . DS . 'models' . DS );
if( !isset($db) ) {
	//require_once MYSQL_DIR . 'db_connect.php';
}
// Conexión DB PDO (mejor, más adaptable a BBDD no-mysql
define('SCRIPT_DIR', DOC_ROOT . 'scripts' . DS );
if( !isset($db) ) {
	//echo 'db is not set';
	require SCRIPT_DIR . 'connect.php';
}


$ruta_raiz = "/".$path_vl;	// "/videolayers/v0.8/";
$site_url = "VL-demo07.php";


define('FACEBOOK_SDK_V4_SRC_DIR', DOC_ROOT . '/fb/Facebook_v5/');
?>