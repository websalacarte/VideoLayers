<?php
/* Este fichero define los nombres de los ficheros de contenido.			*/
/* Para cada fichero, define su id, nombre, ubicación, "cajas", ...			*/

//require_once('defines.php');
define('video-demo-04' , "24819045");
define('title_video' , "The Last Sohour");
$caja[1]='ES02';
$caja[2]='EN';
$caja[3]='FR';
$caja[4]='RU';

if ($subtitles_caja != "ar") {
	// todos los casos (english, none?, ...)
	$get_caja = 1;
}
else {
	// arabic
	$get_caja = 2;
}

$usernames = array("Josep", "Websalacarte", "Pepe");

?>