<?php 

	//$connect = mysql_connect('server', 'user', 'password');
	//$connect = mysql_connect('websalac_videolayers', 'vladmin', '8464vdEmm') or die("Problems!");



//$connect = mysql_connect('localhost', 'websalac_vladmin', '8464vdEmm') or die("Problems!" . mysql_error());
$connect = mysql_connect('localhost', 'websalac_vladmin', '8464vdEmm') or die("Problems!" . mysql_error());

	//$connect = mysql_connect('localhost', 'root', '') or die("Problems!" . mysql_error());

//$db = mysql_select_db('websalac_videolayers');

//if (!isset ($db)) {
	$db = mysql_select_db('websalac_videolayers_v09');
	//$db = mysql_select_db('websalac_voctours');					// local portatil
//}

if( !$connect ) {
}

?>