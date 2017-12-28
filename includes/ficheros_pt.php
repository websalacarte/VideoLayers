<?php
/* Este fichero define los nombres de los ficheros de contenido.			*/
/* Para cada fichero, define su id, nombre, ubicación, "cajas", ...			*/

//require_once('defines.php');
define('video-demo-05' , "164218427");	//define('video-demo-04' , "24819045");
define('title_video' , "Pedro Torres Chiropraxis");
$caja[1]='ES02';
$caja[2]='EN';
$caja[3]='FR';
$caja[4]='RU';

if ($subtitles_caja != "ru") {
	// todos los casos (english, none?, ...)
	$get_caja = 1;
}
else {
	// russian
	$get_caja = 2;
}

$usernames = array("Josep", "Websalacarte", "Pepe");

?>